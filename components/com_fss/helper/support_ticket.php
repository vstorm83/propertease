<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'support_users.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'support_actions.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'support_source.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'permission.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'helper.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'files.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'parser_ticket.php');

define("TICKET_MESSAGE_USER", 0);
define("TICKET_MESSAGE_ADMIN", 1);
define("TICKET_MESSAGE_PRIVATE", 2);
define("TICKET_MESSAGE_AUDIT", 3);
define("TICKET_MESSAGE_DRAFT", 4);
define("TICKET_MESSAGE_TIME", 5);
define("TICKET_MESSAGE_OPENEDBY", 6);

define("TICKET_ASSIGN_FORWARD", 0);
define("TICKET_ASSIGN_TOOK_OWNER", 1);
define("TICKET_ASSIGN_UNASSIGNED", 2);
define("TICKET_ASSIGN_ASSIGNED", 3);
		
class SupportTicket
{
	var $loaded = false;
	var $loaded_tags = false;
	var $loaded_attachments = false;
	var $loaded_groups = false;
	var $loaded_messages = false;
	var $loaded_custom = false;
	var $loaded_cc = false;
	
	/* Storage for sub things */
	var $tags = array();
	var $attach = array();
	var $messages = array();
	var $groups = array();
	var $fields = array();
	var $related = array();
	var $related_ids = array();
	var $cc = array();
	var $user_cc = array();
	var $admin_cc = array();
	
	/* Options */
	var $audit_changes = true;
	var $send_emails = true;
	
	var $current_user = 0;
	
	var $is_batch = false;
	var $update_last_updated = true;
	var $is_new_ticket = false;
		
	function create($data)
	{
		$db = JFactory::getDBO();

		$base_data = array(
			'ticket_status_id' => FSS_Ticket_Helper::GetStatusID('def_open'),
			'opened' => date("Y-m-d H:i:s"),
			'lastupdate' => date("Y-m-d H:i:s")
		);
		
		foreach ($base_data as $key => $value)
		{
			if (!array_key_exists($key, $data))
				$data[$key] = $value;	
		}
		
		$keys = array();
		$values = array();
		foreach ($data as $key => $value)
		{
			$keys[] = $db->escape($key);
			$values[] = "'" . $db->escape($value) . "'";
		}	
			
		$qry = "INSERT INTO #__fss_ticket_ticket (" . implode(", ", $keys) . ") VALUES (" . implode(", ", $values) . ")";
		$db->setQuery($qry);
		$db->query();
		
		$id = $db->insertid();
		$this->load($id, true);
		
		$fields = FSSCF::GetCustomFields($this->id,$this->prod_id,$this->ticket_dept_id);
		FSSCF::StoreFields($fields,$this->id);
		
		$this->reference = FSS_Ticket_Helper::createRef($this->id);
		
		$qry = "UPDATE #__fss_ticket_ticket SET reference = '" . $db->escape($this->reference) . "' WHERE id = " . (int)$id;
		$db->setQuery($qry);
		$db->query();
		
	}
	
	function load($ticket_id, $for_user = false)
	{
		$this->id = $ticket_id;
		
		$db = JFactory::getDBO();

		$query = "SELECT t.*, s.title as status, s.color, u.name, au.name as assigned, u.email as useremail, u.username as username, au.email as handleremail, au.username as handlerusername, ";
		$query .= " dept.title as department, cat.title as category, prod.title as product, pri.title as priority, pri.color as pricolor, ";
		$query .= " grp.groupname as groupname, grp.id as group_id ";
		$query .= " , pri.translation as ptl, dept.translation as dtr, s.translation as str, cat.translation as ctr, prod.translation as prtr";
		$query .= " FROM #__fss_ticket_ticket as t ";
		$query .= " LEFT JOIN #__fss_ticket_status as s ON t.ticket_status_id = s.id ";
		$query .= " LEFT JOIN #__users as u ON t.user_id = u.id ";
		$query .= " LEFT JOIN #__users as au ON t.admin_id = au.id ";
		$query .= " LEFT JOIN #__fss_ticket_dept as dept ON t.ticket_dept_id = dept.id ";
		$query .= " LEFT JOIN #__fss_ticket_cat as cat ON t.ticket_cat_id = cat.id ";
		$query .= " LEFT JOIN #__fss_prod as prod ON t.prod_id = prod.id ";
		$query .= " LEFT JOIN #__fss_ticket_pri as pri ON t.ticket_pri_id = pri.id ";
		$query .= " LEFT JOIN (SELECT group_id, user_id FROM #__fss_ticket_group_members GROUP BY user_id) as mem ON t.user_id = mem.user_id ";
		$query .= " LEFT JOIN #__fss_ticket_group as grp ON grp.id = mem.group_id ";

		$query .= " WHERE t.id = '".(int)$ticket_id."' AND ";

		if ($for_user)
		{
			$query .= " 1 ";
		} else {
			$query .= SupportUsers::getAdminWhere();
		}
		
		//echo $query . "<br>";
		
		$db->setQuery($query);
		$row = $db->loadObject();
		
		if (!$row)
			return false;
		
		return $this->loadFromRow($row);
	}
	
	function checkExist($ticket_id)
	{
		$db = JFactory::getDBO();
		$qry = "SELECT id FROM #__fss_ticket_ticket WHERE id = " . $db->escape($ticket_id);
		$db->setQuery($qry);
		$row = $db->loadObject();
		if ($row)
			return true;
		
		return false; 
	}
	
	function loadFromRow($row)
	{
		foreach ($row as $key => $value)
			$this->$key = $value;
		
		$this->loaded = true;
		
		// verify that the id is an integer
		$this->id = (int)$this->id;
		
		$this->current_user = JFactory::getUser()->id;
		
		$this->msgcount = array();
		$this->msgcount[0] = 0;
		$this->msgcount[1] = 0;
		$this->msgcount[2] = 0;
		$this->msgcount[3] = 0;
		$this->msgcount[4] = 0;
		$this->msgcount['total'] = 0;
			
		return true;
	}
	
	function loadAll($reverseMessages = null)
	{
		$this->loadTags();
		$this->loadAttachments();
		$this->loadGroups();
		$this->loadMessages($reverseMessages);
		$this->loadLockedUser();
		$this->loadCustomFields();
		$this->loadRelated();
		$this->loadCC();
		
		$this->linkMessagesAttach();		
	}
	
	function loadTags()
	{
		if (!$this->loaded_tags)
		{		
			$qry = "SELECT * FROM #__fss_ticket_tags WHERE ticket_id = {$this->id} ORDER BY tag";
			$db = JFactory::getDBO();
			$db->setQuery($qry);
			$tags = $db->loadObjectList();
			$this->tags = array();
			foreach ($tags as $tag)
				$this->tags[] = $tag->tag;
			$this->loaded_tags = true;	
		}
	}
	
	function loadAttachments()
	{
		if (!$this->loaded_attachments)
		{
			$db = JFactory::getDBO();
			$qry = "SELECT a.*, u.name FROM #__fss_ticket_attach as a LEFT JOIN #__users as u ON a.user_id = u.id WHERE ticket_ticket_id = {$this->id} ORDER BY added DESC";
			$db->setQuery($qry);
			$this->attach = $db->loadObjectList();
			$this->loaded_attachments = true;
			
			// check for any missing name entries, and look them up from message or ticket if needed
			foreach ($this->attach as &$attach)
			{
				if ($attach->name == "")
				{
					if (!$this->loaded_messages) $this->loadMessages();
					
					foreach ($this->messages as $message)
					{
						if ($message->id !=	$attach->message_id) continue;
						
						$attach->name = $message->name;
					}
				}
				
				if ($attach->name == "") // still no name, use name from ticket
				{
					if ($this->name)
					{
						$attach->name = $this->name;
					} else {
						$attach->name = $this->unregname;
					}
				}
			}
			
		}	
	}
	
	function loadGroups()
	{
		if (!$this->loaded_groups)
		{
			$db = JFactory::getDBO();
			$query = "SELECT g.* FROM #__fss_ticket_group_members as m LEFT JOIN #__fss_ticket_group as g ON m.group_id = g.id WHERE m.user_id = " . (int)$this->user_id;
			$db->setQuery($query);
			$this->groups = $db->loadObjectList();
			$this->loaded_groups = true;	
		}
	}
	
	function loadMessages($reverse = null, $types = array())
	{
		if (!$this->loaded_messages)
		{
			$query = "SELECT m.*, u.name FROM #__fss_ticket_messages as m LEFT JOIN #__users as u ON m.user_id = u.id WHERE ticket_ticket_id = {$this->id}";
		
			if (count($types) > 0)
				$query .= " AND m.admin IN (" . implode(", ", $types) . ") ";
					
			if ($reverse === null)
			{
				if (SupportUsers::getSetting("reverse_order"))
				{
					$query .= " ORDER BY posted ASC";
				} else {
					$query .= " ORDER BY posted DESC";
				}
			} else if ($reverse)
			{
				$query .= " ORDER BY posted DESC";
			} else {
				$query .= " ORDER BY posted ASC";	
			}
			
			$db = JFactory::getDBO();
			$db->setQuery($query);
			
			$this->messages = $db->loadObjectList();
			$this->loaded_messages = true;
		}
	}
	
	function loadLockedUser()
	{
		$cotime = FSS_Helper::GetDBTime() - strtotime($this->checked_out_time);
		if ($cotime < FSS_Settings::get('support_lock_time') && $this->checked_out != JFactory::getUser()->id)
		{
			$this->co_user = JFactory::getUser($this->checked_out);
		}
	}
	
	function loadCustomFields()
	{
		if (!$this->loaded_custom)
		{
			$this->customfields = FSSCF::GetCustomFields($this->id,$this->prod_id,$this->ticket_dept_id,3);
			$this->custom = FSSCF::GetTicketValues($this->id, $this);
			$this->loaded_custom = true;
		}
	}
	
	function linkMessagesAttach()
	{
		foreach($this->attach as &$attach)
		{
			$message_id = $attach->message_id;
			foreach($this->messages as &$message)
			{
				if ($message->id == $message_id)
				{
					if (!array_key_exists('attach', $message))
						$message->attach = array();
						
					$message->attach[] = $attach;		
				}	
			}
		}
	}
	
	function loadRelated()
	{
		$db = JFactory::getDBO();
		$qry = "SELECT * FROM #__fss_ticket_related WHERE source_id = " . $this->id;
		$db->setQuery($qry);
		$rows = $db->loadObjectList();
		foreach ($rows as $row)
		{
			$this->related_ids[$row->dest_id] = $row->dest_id;
			
			$ticket = new SupportTicket();
			if ($ticket->load($row->dest_id))
			{
				$this->related[$row->dest_id] = $ticket;
			}
		}			
	}
	
	function loadCC()
	{
		if (!$this->loaded_cc)
		{
			$db = JFactory::getDBO();
			$qry = "SELECT u.name, u.username, u.email, u.id, c.isadmin, c.readonly, c.email as uremail FROM #__fss_ticket_cc as c LEFT JOIN #__users as u ON c.user_id = u.id WHERE c.ticket_id = '{$this->id}' ORDER BY name";
			$db->setQuery($qry);
			$this->cc = $db->loadObjectList();
			
			$this->user_cc = array();
			$this->admin_cc = array();
			
			foreach ($this->cc as $cc)
			{
				if ($cc->isadmin)
					$this->admin_cc[] = $cc;
				else	
					$this->user_cc[] = $cc;
			}
			
			$this->loaded_cc = true;	
		}	
	}
	
/***************************************/
/** Modify functions for the ticket.  **/
/***************************************/

	function updateLastUpdated()
	{
		if (!$this->update_last_updated)
			return; 
		
		$now = FSS_Helper::CurDate();
		$qry = "UPDATE #__fss_ticket_ticket SET lastupdate = '{$now}' WHERE id = {$this->id}";
		$db = JFactory::getDBO();
		$db->setQuery($qry);
		$db->Query();	
		
		$this->lastupdate = $now;
	}
	
	function updatePriority($new_pri_id)
	{
		if ($new_pri_id == $this->ticket_pri_id)
			return true;
				
		$priorities = SupportHelper::getPriorities(false);
		
		$old_pri_id = $this->ticket_pri_id;
		
		$qry = "UPDATE #__fss_ticket_ticket SET ticket_pri_id = ".(int)$new_pri_id." WHERE id = {$this->id}";
		$db = JFactory::getDBO();
		$db->setQuery($qry);
		$db->Query();	
		
		$this->updateLastUpdated();
			
		$this->addAuditNote("Priority changed from '" . $priorities[$old_pri_id]->title . "' to '" . $priorities[$new_pri_id]->title . "'");
		
		$this->ticket_pri_id = $new_pri_id;
		
		SupportActions::DoAction_Ticket("updatePriority", $this, array('old_pri_id' => $old_pri_id, 'new_pri_id' => $new_pri_id));
	}
		
	function updateStatus($new_status_id)
	{
		// dont process if unchanged
		if ($new_status_id == $this->ticket_status_id)
			return true;
				
		// load in status list
		$statuss = SupportHelper::getStatuss(false);
	
		$old_status_id = $this->ticket_status_id;
	
		$old_st = $statuss[$old_status_id];
		$new_st = $statuss[$new_status_id];
		
		$now = FSS_Helper::CurDate();
		
		if ($new_st->is_closed)
		{
			$isclosed = "closed = '{$now}'";	
		} else {
			$isclosed = "closed = NULL";	
		}
			
		$qry = "UPDATE #__fss_ticket_ticket SET ticket_status_id = {$new_status_id}, {$isclosed} WHERE id = {$this->id}";
		$db = JFactory::getDBO();
		$db->setQuery($qry);
		$db->Query();	
		
		$this->updateLastUpdated();
				
		$this->addAuditNote("Status changed from '" . $statuss[$old_status_id]->title . "' to '" . $statuss[$new_status_id]->title . "'");
				
		// update the object with the new status
		$this->ticket_status_id = $new_status_id;
		
		// If we have closed the ticket, the closed field needs updating
		if ($new_st->is_closed)
		{
			$this->closed = $now;
		} else {
			// Otherwise it should be null
			$this->closed = null;
		}
	
		SupportActions::DoAction_Ticket("updateStatus", $this, array('old_status_id' => $old_status_id, 'new_status_id' => $new_status_id));
	}
	
	function updateCategory($new_cat_id)
	{
		if ($new_cat_id == $this->ticket_cat_id)
			return true;
		
		$cats = SupportHelper::getCategories(false);		
		
		$old_cat_id = $this->ticket_cat_id;

		$qry = "UPDATE #__fss_ticket_ticket SET ticket_cat_id = ".(int)$new_cat_id." WHERE id = {$this->id}";
		$db = JFactory::getDBO();
		$db->setQuery($qry);
		$db->Query();	
		
		$this->updateLastUpdated();
			
		$this->addAuditNote("Category changed from '" . $cats[$old_cat_id]->title . "' to '" . $cats[$new_cat_id]->title . "'");
		
		$this->ticket_cat_id = $new_cat_id;	
		
		SupportActions::DoAction_Ticket("updateCategory", $this, array('old_cat_id' => $old_cat_id, 'new_cat_id' => $new_cat_id));
	}
	
	function updateUser($new_user_id)
	{
		if ($new_user_id == $this->user_id)
			return true;
		
		$old_user = JFactory::getUser($this->user_id);
		$new_user = JFactory::getUser($new_user_id);
		$qry = "UPDATE #__fss_ticket_ticket SET user_id = ".(int)$new_user_id." WHERE id = {$this->id}";
		$db = JFactory::getDBO();
		$db->setQuery($qry);
		$db->Query();	
		
		$this->updateLastUpdated();
			
		$this->addAuditNote("User changed from '{$old_user->name} ({$old_user->username})' to '{$new_user->name} ({$new_user->username})'");
		
		$this->user_id = $new_user_id;	
		
		SupportActions::DoAction_Ticket("updateUser", $this, array('old_user_id' => $this->user_id, 'new_user_id' => $new_user_id));
	}
	
	function updateProduct($new_product_id)
	{
		if ($new_product_id == $this->prod_id)
			return true;
		
		$old_product = $this->getProduct();
		
		$qry = "UPDATE #__fss_ticket_ticket SET prod_id = ".(int)$new_product_id." WHERE id = {$this->id}";
		$db = JFactory::getDBO();
		$db->setQuery($qry);
		$db->Query();	
		
		$this->updateLastUpdated();
		
		$this->prod_id = $new_product_id;	
		$new_product = $this->getProduct();
		
		$this->addAuditNote("Product changed from '{$old_product->title})' to '{$new_product->title})'");
		
		SupportActions::DoAction_Ticket("updateProduct", $this, array('old_prod_id' => $old_product->id, 'new_prod_id' => $new_product_id));	
	}
	
	function updateDepartment($new_department_id)
	{
		if ($new_department_id == $this->ticket_dept_id)
			return true;
		
		$old_department = $this->getDepartment();
		
		$qry = "UPDATE #__fss_ticket_ticket SET ticket_dept_id = ".(int)$new_department_id." WHERE id = {$this->id}";
		$db = JFactory::getDBO();
		$db->setQuery($qry);
		$db->Query();	
		
		$this->updateLastUpdated();
		
		$this->ticket_dept_id = $new_department_id;	
		$new_department = $this->getDepartment();
		
		$this->addAuditNote("Department changed from '{$old_department->title})' to '{$new_department->title})'");
		
		SupportActions::DoAction_Ticket("updateDepartment", $this, array('old_department_id' => $old_product->id, 'new_department_id' => $new_department_id));	
	}
	
	function updateUnregEMail($new_email)
	{
		if ($new_email == $this->email)
			return true;

		$old_email = $this->email;

		$db = JFactory::getDBO();
		$qry = "UPDATE #__fss_ticket_ticket SET email = '".FSSJ3Helper::getEscaped($db,$new_email)."' WHERE id = {$this->id}";
		$db->setQuery($qry);
		$db->Query();	
		
		$this->updateLastUpdated();
			
		$this->addAuditNote("EMail changed from '" . $old_email . "' to '" . $new_email . "'");
		
		$this->email = $new_email;	
		
		SupportActions::DoAction_Ticket("updateUnregEMail", $this, array('old_email' => $old_email, 'new_email' => $new_email));
	}
	
	function updateSubject($new_subject)
	{
		if ($new_subject == $this->title)
			return true;

		$old_subject = $this->title;

		$db = JFactory::getDBO();
		$qry = "UPDATE #__fss_ticket_ticket SET title = '".FSSJ3Helper::getEscaped($db,$new_subject)."' WHERE id = {$this->id}";
		$db->setQuery($qry);
		$db->Query();	
		
		$this->updateLastUpdated();
			
		$this->addAuditNote("Subject changed from '" . $old_subject . "' to '" . $new_subject . "'");
		
		$this->title = $new_subject;	
		
		SupportActions::DoAction_Ticket("updateSubject", $this, array('old_subject' => $old_subject, 'new_subject' => $new_subject));
	}
	
	function updateSource($new_source)
	{
		if ($new_source == $this->source)
			return true;

		$old_source = $this->source;
		
		$db = JFactory::getDBO();
		$qry = "UPDATE #__fss_ticket_ticket SET source = '".FSSJ3Helper::getEscaped($db,$new_source)."' WHERE id = {$this->id}";
		$db->setQuery($qry);
		$db->Query();	
		
		$this->updateLastUpdated();
			
		$new_source_title = SupportSource::get_source_title($new_source);
		$old_source_title = SupportSource::get_source_title($old_source);
		
		$this->addAuditNote("Source changed from '" . $old_source_title . "' to '" . $new_source_title . "'");
		
		$this->source = $new_source;	
		
		SupportActions::DoAction_Ticket("updateSource", $this, array('old_source' => $old_source, 'new_source' => $new_source));
	}
	
	function updateLock()
	{
		$db = JFactory::getDBO();
		$now = FSS_Helper::CurDate();
		$qry = "UPDATE #__fss_ticket_ticket SET checked_out = '".(int)JFactory::getUser()->id."', checked_out_time = '{$now}' where id = {$this->id}";
		$db->setQuery($qry);
		$db->query();		
		
		SupportActions::DoAction_Ticket("updateLock", $this);
	}
	
	function updateCustomField($fieldid, $value, $max_permission = 3)
	{
		// TODO: Dont like how this works, needs the field data to be stored in the class object!
		if (empty($this->fields))
			$this->fields = FSSCF::GetCustomFields($this->id,$this->prod_id,$this->ticket_dept_id,$max_permission);
		
		list($old, $new) = FSSCF::StoreField($fieldid, $this->id, $this, $value);
					
		if ($old != $new)
		{
			$field = FSSCF::GetField($fieldid);
			if ($field->type == 'checkbox')
			{
				if ($old == "") $old = "No";
				if ($old == "on") $old = "Yes";	
				if ($new == "") $new = "No";
				if ($new == "on") $new = "Yes";	
			}
			$this->addAuditNote("Custom field '" . $field->description . "' changed from '" . $old . "' to '" . $new . "'");
			
			$this->updateLastUpdated();

			SupportActions::DoAction_Ticket("updateCustomField", $this, array('field_id' => $fieldid, 'old' => $old, 'new' => $new));
		}
	}
	
	function addTag($tag)
	{	
		$this->loadTags();

		if (in_array($tag, $this->tags))
			return true;
		
		$db = JFactory::getDBO();
		$qry = "REPLACE INTO #__fss_ticket_tags (ticket_id, tag) VALUES ({$this->id}, '".FSSJ3Helper::getEscaped($db, $tag)."')";
		$db->setQuery($qry);
		$db->query();		
			
		$this->addAuditNote("Add tag '" . $tag . "'");

		$this->tags[] = $tag;
		
		sort($this->tags);
		
		$this->updateLastUpdated();

		SupportActions::DoAction_Ticket("addTag", $this, array('tag' => $tag));
	}
	
	function removeTag($tag)
	{
		$this->loadTags();

		if (!in_array($tag, $this->tags))
			return true;	
		
		$db = JFactory::getDBO();
		$qry = "DELETE FROM #__fss_ticket_tags WHERE ticket_id = {$this->id} AND tag = '".FSSJ3Helper::getEscaped($db, $tag)."'";
		$db->setQuery($qry);
		$db->query();		
			
		$this->addAuditNote("Remove tag '" . $tag . "'");

		if (($key = array_search($tag, $this->tags)) !== false) {
			unset($this->tags[$key]);
		}

		sort($this->tags);
		
		$this->updateLastUpdated();

		SupportActions::DoAction_Ticket("removeTag", $this, array('tag' => $tag));
	}
	
	function addTime($minutes, $notes = "", $post_message = false, $timestart = 0, $timeend = 0)
	{
		$db = JFactory::getDBO();
		$qry = "UPDATE #__fss_ticket_ticket SET timetaken = timetaken + " . (int)$minutes . " WHERE id = {$this->id}";
		$db->setQuery($qry);
		$db->Query();
		
		// extra query to force the time to always be above 0
		$qry = "UPDATE #__fss_ticket_ticket SET timetaken = 0 WHERE id = {$this->id} AND timetaken < 0";
		$db->setQuery($qry);
		$db->Query();
		
		$msg = "Added $minutes minutes to time taken.";
		if ($notes)
			$msg .= " Notes: " . $notes;
		// add audit message for the time logged
		$this->addAuditNote($msg);
		
		if ($minutes == 0)
		{
			$timestart = 0;
			$timeend = 0;
		}
		
		if ($post_message)
		{
			$this->addMessage($notes, "", -1, TICKET_MESSAGE_TIME, $minutes, $timestart, $timeend);	
		}
		
		SupportActions::DoAction_Ticket("addTime", $this, array('time' => $minutes, 'notes' => $notes));
	}
	
	function addTimeQuiet($minutes)
	{
		$db = JFactory::getDBO();
		$qry = "UPDATE #__fss_ticket_ticket SET timetaken = timetaken + " . (int)$minutes . " WHERE id = {$this->id}";
		$db->setQuery($qry);
		$db->Query();
	}
	
	function deleteAttach($file_id)
	{
		$attach = $this->getAttach($file_id);
		if ($attach)
		{
			$db = JFactory::getDBO();
			$qry = "DELETE FROM #__fss_ticket_attach WHERE ticket_ticket_id = {$this->id} AND id = {$attach->id}";
			$db->setQuery($qry);
			$db->Query();
			
			$destpath = JPATH_SITE.DS.FSS_Settings::get('attach_location').DS.'support'.DS;			
			$file = $destpath . $attach->diskfile;

			if (file_exists($file))
				JFile::delete($file);
			
			if (file_exists($file. ".thumb"))
				JFile::delete($file. ".thumb");
			
			$this->addAuditNote("Deleting attachment '" . $attach->filename . "'");
		
			SupportActions::DoAction_Ticket("deleteAttach", $this, array('attach' => $attach));
		}
	}
	
	function addMessage($body, $subject = "", $user_id = -1, $type = TICKET_MESSAGE_USER, $time = 0, $timestart = 0, $timeend = 0)
	{
		$db = JFactory::getDBO();
		
		// no user id passed, so use the one from the current user
		if ($user_id == -1)
			$user_id = JFactory::getUser()->id;
		
		if ($time == 0)
		{
			$timestart = 0;
			$timeend = 0;
		}
		
		// add a message to the ticket
		$qry = "INSERT INTO #__fss_ticket_messages (ticket_ticket_id, subject, body, user_id, admin, posted, `time`, timestart, timeend) VALUES ('";
		$qry .= FSSJ3Helper::getEscaped($db, $this->id) . "','".FSSJ3Helper::getEscaped($db, $subject)."','".FSSJ3Helper::getEscaped($db, $body)."',".(int)$user_id.", $type, '".FSS_Helper::CurDate()."', '" . $db->escape($time) ."', '" . $db->escape($timestart) ."', '" . $db->escape($timeend) ."')";
			
		//echo $qry . "<br>";
		
		$db->SetQuery( $qry );
		$db->Query();
		
		$message_id = $db->insertid();
		
		$this->updateLastUpdated();
			
		SupportActions::DoAction_Ticket("addMessage", $this, array('user_id' => $user_id, 'type' => $type, 'subject' => $subject, 'body' => $body, 'message_id' => $message_id));
			
		return $message_id;
	}
	
	function addFilesFromPost($message_id, $user_id = -1, $hide_from_user = 0)
	{
		// ADD ALL POSTED FILES TO THE TICKET	
		if ($user_id == -1)
			$user_id = JFactory::getUser()->id;
		
		$files = array();
		
		// save any file attachments
		for ($i = 0; $i < 10; $i ++)
		{
			$file = JRequest::getVar('filedata_'.$i, '', 'FILES', 'array');
			if (array_key_exists('error',$file) && $file['error'] == 0 && $file['name'] != '')
			{
				$destpath = JPATH_SITE.DS.FSS_Settings::get('attach_location').DS.'support'.DS;					
				$destname = FSS_File_Helper::makeAttachFilename("support", $file['name'], date("Y-m-d"), $this, $user_id);
		 
				if (JFile::upload($file['tmp_name'], $destpath . $destname))
				{
					$qry = "INSERT INTO #__fss_ticket_attach (ticket_ticket_id, filename, diskfile, size, user_id, added, message_id, hidefromuser) VALUES ('";
					$qry .= FSSJ3Helper::getEscaped($db, $this->id) . "',";
					$qry .= "'" . FSSJ3Helper::getEscaped($db, $file['name']) . "',";
					$qry .= "'" . FSSJ3Helper::getEscaped($db, $destname) . "',";
					$qry .= "'" . $file['size'] . "',";
					$qry .= "'" . FSSJ3Helper::getEscaped($db, $user_id) . "',";
					$qry .= "'".FSS_Helper::CurDate()."', $message_id, '".FSSJ3Helper::getEscaped($db, $hide_from_user)."' )";
				
					echo $qry . "<br>";

					$file_obj = new stdClass();
					$file_obj->filename = $file['name'];
					$file_obj->diskfile = $destname;
					$file_obj->size = $file['size'];
					$files[] = $file_obj;

					$db->setQuery($qry);$db->Query();    
					
					SupportActions::DoAction_Ticket("addFile", $this, array('file' => $file_obj));
				}
			}
		}

		// new style posted files using jquery file uploaded
		$post_files = JRequest::getVar('new_filename', 'POST', 'array');
		$token = FSS_File_Helper::makeUploadSubdir(JRequest::getVar('upload_token'));
		if (is_array($post_files))
		{
			foreach ($post_files as $file)
			{
				$destpath = JPATH_SITE.DS.FSS_Settings::get('attach_location').DS.'support'.DS;					
				$destname = FSS_File_Helper::makeAttachFilename("support", $file, date("Y-m-d"), $this, $user_id);
				$source = JPATH_ROOT.'/tmp/fss/incoming/'.$token.'/'.$file;

				require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'bigfiletools.php');
				$f = BigFileTools::fromPath($source);
				$size  = $f->getSize();

				$dest = $destpath . $destname;

				if (JFile::move($source, $dest))
				{
					$qry = "INSERT INTO #__fss_ticket_attach (ticket_ticket_id, filename, diskfile, size, user_id, added, message_id, hidefromuser) VALUES ('";
					$qry .= FSSJ3Helper::getEscaped($db, $this->id) . "',";
					$qry .= "'" . FSSJ3Helper::getEscaped($db, $file) . "',";
					$qry .= "'" . FSSJ3Helper::getEscaped($db, $destname) . "',";
					$qry .= "'" . $size . "',";
					$qry .= "'" . FSSJ3Helper::getEscaped($db, $user_id) . "',";
					$qry .= "'".FSS_Helper::CurDate()."', $message_id, '".FSSJ3Helper::getEscaped($db, $hide_from_user)."' )";
					
					$file_obj = new stdClass();
					$file_obj->filename = $file;
					$file_obj->diskfile = $destname;
					$file_obj->size = $size;
					$files[] = $file_obj;

					$db->setQuery($qry);$db->Query();    
					
					SupportActions::DoAction_Ticket("addFile", $this, array('file' => $file_obj));
				}
			}	

		}

		if (is_dir(JPATH_ROOT.'/tmp/fss/incoming/'.$token))
			@rmdir(JPATH_ROOT.'/tmp/fss/incoming/'.$token);

		FSS_File_Helper::CleanupIncoming();

		if (count($files) < 1)
			return false;

		return $files;
	}
	
	function updateMessage($message_id, $subject, $body, $time = 0, $timestart = 0, $timeend = 0)
	{
		$db = JFactory::getDBO();
		$message = $this->getMessage($message_id);
		
		if (!$message)
			return;
		
		if ($message->subject != $subject)
		{
			$this->addAuditNote("Message on " . $message->posted . ", subject changed from '".$message->subject."'");
		} 
		if ($message->body != $body)
		{
			$this->addAuditNote("Message on " . $message->posted . ", body changed from '".$message->body."'");
		} 

		$qry = "UPDATE #__fss_ticket_messages SET subject = '".$db->escape($subject)."', body = '".$db->escape($body)."'";
		
		if ($time > 0)
		{
			$qry .= ", time = " . (int)$time . " ";	
			$qry .= ", timestart = " . (int)$timestart . " ";	
			$qry .= ", timeend = " . (int)$timeend . " ";	
			
			$old_time = $message->time;
			$this->addTimeQuiet(-$old_time);
			$this->addTimeQuiet($time);
			
			$this->addAuditNote("Message on " . $message->posted . ", time changed from '".$old_time."'");
		}
		
		$qry .= " WHERE id = " . FSSJ3Helper::getEscaped($db, $message_id);
		$db->setQuery($qry);
		$db->Query($qry);
		
		SupportActions::DoAction_Ticket("updateMessage", $this, array('message_id' => $message_id, 'old_subject' => $message->subject, 'new_subject' => $subject, 'old_body' => $message->body, 'new_body' => $body));
	}
	
	function deleteMessage($message_id, $subject, $body)
	{
		$message = $this->getMessage($message_id);
		
		if (!$message)
			return;
		
		$this->addAuditNote("Message on " . $message->posted . " deleted, '".$message->subject . "', '".$message->body."'");
		
		$qry = "DELETE FROM #__fss_ticket_messages WHERE id = " . FSSJ3Helper::getEscaped($db, $message_id);
		$db->setQuery($qry);
		$db->Query($qry);
		
		SupportActions::DoAction_Ticket("deleteMessage", $this, array('message_id' => $message_id, 'subject' => $message->subject, 'body' => $message->body));
	}
	
	function addRelated($ticketid)
	{
		$db = JFactory::getDBO();
		
		$qry = "REPLACE INTO #__fss_ticket_related (source_id, dest_id) VALUES ";
		$qry .= "(" . (int)$this->id . ", " . (int)$ticketid . ") ,";
		$qry .= "(" . (int)$ticketid . ", " . (int)$this->id . ")";
		
		$db->setQuery($qry);
		$db->Query();
	}
	
	function removeRelated($ticketid)
	{
		$db = JFactory::getDBO();
		
		$qry = "DELETE FROM #__fss_ticket_related WHERE source_id = " . (int)$this->id . " AND dest_id = " .  (int)$ticketid;
		$db->setQuery($qry);
		$db->Query();
		
		$qry = "DELETE FROM #__fss_ticket_related WHERE source_id = " . (int)$ticketid . " AND dest_id = " .  (int)$this->id;
		$db->setQuery($qry);
		$db->Query();
	}
	
	function addCC($ids, $is_admin, $is_readonly)
	{
		$db = JFactory::getDBO();
		
		if (!is_array($ids))
		{
			$t = $ids;
			$ids = array();
			$ids[] = $t;
		}

		foreach ($ids as $id)
		{
			if ($id > 0)
			{
				$qry = "REPLACE INTO #__fss_ticket_cc (ticket_id, user_id, isadmin, readonly) VALUES (" . $db->escape((int)$this->id) . ", ";
				$qry .= $db->escape((int)$id) . ", " . $db->escape($is_admin) . ", " . $db->escape($is_readonly) . ")";
			
				$db->setQuery($qry);
				$db->Query();
			}
		}
	}
	
	function addEMailCC($email)
	{
		$db = JFactory::getDBO();
		
		$id = 100000000 + mt_rand(100000,999999);
		
		$qry = "REPLACE INTO #__fss_ticket_cc (ticket_id, user_id, isadmin, email) VALUES (" . $db->escape((int)$this->id) . ", ";
		$qry .= $db->escape((int)$id) . ", 0, '" . $db->escape($email) . "')";
			
		$db->setQuery($qry);
		$db->Query();
	}
	
	function removeCC($ids, $is_admin)
	{
		$db = JFactory::getDBO();
		
		if (!is_array($ids))
		{
			$t = $ids;
			$ids = array();
			$ids[] = $t;
		}

		foreach ($ids as $id)
		{
			$qry = "DELETE FROM #__fss_ticket_cc WHERE ticket_id = " . $db->escape($this->id);
			$qry .= " AND user_id = " . $db->escape($id);
			$qry .= " AND isadmin = " . $db->escape($is_admin);
			
			$db->setQuery($qry);
			$db->Query();
		}
	}
	
/************************************/
/** Get functions for the ticket.  **/
/** Retrieve various data about it **/
/************************************/

	/**
	 * Returns the category object for the current ticket
	 */	
	function getCategory()
	{
		$cats = SupportHelper::getCategories(false);			
		return $cats[$this->ticket_cat_id];	
	}
	
	function getPriority()
	{
		$pris = SupportHelper::getPriorities(false);
		if (isset($pris[$this->ticket_pri_id]))
			return $pris[$this->ticket_pri_id];
		
		return reset($pris);
	}	
	
	function getProduct()
	{
		$prods = SupportHelper::getProducts(false);
		if (array_key_exists($this->prod_id, $prods))
			return $prods[$this->prod_id];
	
		return null;
	}	
	
	function getDepartment()
	{
		$depts = SupportHelper::getDepartments(false);
		if (array_key_exists($this->ticket_dept_id, $depts))
			return $depts[$this->ticket_dept_id];
	
		return null;
	}	
	
	function getStatus()
	{
		$statuss = SupportHelper::getStatuss(false);
		return $statuss[$this->ticket_status_id];
	}
	/**
	 * Return an array of the current tags
	 */	
	function getTags()
	{
		$this->loadTags();
		
		return $this->tags;
	}
	
	function getUserEMail()
	{
		if ($this->user_id > 0)
			return $this->useremail;
		
		if ($this->email == "@" || $this->email == "")
			return "Unknown";
		
		return $this->email;
	}	
	
	function getUserName()
	{
		if ($this->user_id > 0)
		{
			return $this->name;
		}
		
		if ($this->unregname == "@" || $this->unregname == "")
			return "Unknown";
		
		return $this->unregname;
	}	
	
	function getTitle()
	{
		return FSSParserTicket::parseTitle($this->title, $this->id);
	}
	
	function getAttach($file_id)
	{
		if (!$this->loaded_attachments)
			$this->loadAttachments();
		
		foreach ($this->attach as $file)
		{
			if ($file->id == $file_id)
				return $file;	
		}	
		
		return null;
	}
	
	function getMessage($message_id)
	{
		if (!$this->loaded_messages)
			$this->loadMessages();	
		
		foreach ($this->messages as $message)
		{
			if ($message->id == $message_id)
				return $message;	
		}	
		
		return null;
	}
	
	function isLocked()
	{
		if (empty($this->locked))
		{
			$cotime = FSS_Helper::GetDBTime(); - strtotime($this->checked_out_time);
			$this->locked = false;
			if ($cotime < FSS_Settings::get('support_lock_time') && $this->checked_out != JFactory::getUser()->id && $this->checked_out != 0)
			{
				$this->locked = true;
			}
		}
	
		return $this->locked;
	}

	/**
	 * Assign a new handler to the ticket
	 * 
	 * handler_id = the FSS User ID of the ticket handler
	 * 
	 * Type:
	 * 
	 * define("TICKET_ASSIGN_FORWARD", 0);
	 * define("TICKET_ASSIGN_TOOK_OWNER", 1);
	 * define("TICKET_ASSIGN_UNASSIGNED", 2);
	 * define("TICKET_ASSIGN_ASSIGNED", 3);
	 * 
	 * if handler_id is 0, then type gets set to 2 and ticket becomes unassigned 
	 **/
	function assignHandler($handler_id, $type = TICKET_ASSIGN_FORWARD)
	{
		if ($handler_id == $this->admin_id)
			return true;
		
		$qry = "UPDATE #__fss_ticket_ticket SET admin_id = {$handler_id} WHERE id = {$this->id}";
		$db = JFactory::getDBO();
		$db->setQuery($qry);
		$db->Query();	
	
		if ($handler_id == 0)
		{
			$type = TICKET_ASSIGN_UNASSIGNED;	
		}
		
		// update last_update
		$this->updateLastUpdated();
		
		// add audit note
		if ($type == TICKET_ASSIGN_FORWARD)
		{
			$this->addAuditNote("Forwarded to handler '" . SupportUsers::getUserName($handler_id) . "'");
		} else if ($type == TICKET_ASSIGN_TOOK_OWNER)
		{
			$this->addAuditNote("Handler '" . SupportUsers::getUserName($handler_id) . "' took ownership of the ticket");
		} else if ($type == TICKET_ASSIGN_UNASSIGNED)
		{
			$this->addAuditNote("Ticket set as unassigned");
		} else if ($type == TICKET_ASSIGN_ASSIGNED)
		{
			$this->addAuditNote("Ticket assigned to '" . SupportUsers::getUserName($handler_id) . "'");
		}
		
		// change this object
		$this->admin_id = $handler_id;
		
		SupportActions::DoAction_Ticket("assignHandler", $this, array('handler' => $handler_id, 'type' => $type));
	}
	
	/**
	 * Adds an audit note to this ticket
	 **/
	function addAuditNote($note)
	{
		if (!$this->audit_changes)
			return;
		
		$db = JFactory::getDBO();
		$now = FSS_Helper::CurDate();
		
		if ($this->is_batch)
			$note = "Batch: " . $note;
		
		$qry = "INSERT INTO #__fss_ticket_messages (ticket_ticket_id, subject, body, user_id, admin, posted) VALUES ('";
		$qry .= FSSJ3Helper::getEscaped($db, $this->id)."','Audit Message','".FSSJ3Helper::getEscaped($db, $note)."','".FSSJ3Helper::getEscaped($db, $this->current_user)."',3, '{$now}')";
			
  		$db->SetQuery( $qry );
		$db->Query();
	}
	
	// CANNOT BE UNDONE! USE WITH CAUTION!
	function delete()
	{
		$this->loadAttachments();
		
		foreach ($this->attach as $attach)
		{
			$image_file = JPATH_SITE.DS."components/com_fss/files/support/" . $attach->diskfile;
			$thumb_file = JPATH_SITE.DS."components/com_fss/files/support/" . $attach->diskfile . ".thumb";
			
			if (file_exists($image_file))
				@unlink($image_file);
			
			if (file_exists($thumb_file))
				@unlink($thumb_file);
		}
		
		$db = JFactory::getDBO();
		
		$qry = "DELETE FROM #__fss_ticket_messages WHERE ticket_ticket_id = {$this->id}";
		$db->setQuery($qry);
		$db->query();
			
		$qry = "DELETE FROM #__fss_ticket_attach WHERE ticket_ticket_id = {$this->id}";
		$db->setQuery($qry);
		$db->query();
		
		$qry = "DELETE FROM #__fss_ticket_cc WHERE ticket_id = {$this->id}";
		$db->setQuery($qry);
		$db->query();
		
		$qry = "DELETE FROM #__fss_ticket_field WHERE ticket_id = {$this->id}";
		$db->setQuery($qry);
		$db->query();
		
		$qry = "DELETE FROM #__fss_ticket_tags WHERE ticket_id = {$this->id}";
		$db->setQuery($qry);
		$db->query();
		
		$qry = "DELETE FROM #__fss_ticket_ticket WHERE id = {$this->id}";
		$db->setQuery($qry);
		$db->query();
				
	}

	function stripImagesFromMessage($message_id)
	{
		$db = JFactory::getDBO();

		$qry = "SELECT * FROM #__fss_ticket_messages WHERE id = " . $db->escape($message_id);
		$db->setQuery($qry);
		$message = $db->loadObject();

		$body = $message->body;
		$count = 0;

		while (strpos($body, "[img]data:") !== false)
		{		
			$start = strpos($body, "[img]data:");
			$end = strpos($body, "[/img]", $start);

			if ($end < 1)
				break;

			$count++;

			$content = substr($body, $start+5, ($end-$start)-5);

			list ($type, $rest) = explode(";", $content, 2);
			list ($encoding, $data) = explode(",", $rest, 2);

			$image_data = base64_decode($data);
			list ($junk, $extension) = explode("/", $type, 2);

			$filename = "message-$message_id-inline-image-$count." . $extension;

			$destpath = JPATH_SITE.DS.FSS_Settings::get('attach_location').DS.'support'.DS;					
			$destname = FSS_File_Helper::makeAttachFilename("support", $filename, date("Y-m-d", strtotime($message->posted)), $this, $message->user_id);

			if (file_put_contents($destpath.$destname, $image_data))
			{
				$size = filesize($destpath.$destname);

				$qry = "INSERT INTO #__fss_ticket_attach (ticket_ticket_id, filename, diskfile, size, user_id, added, message_id, inline) VALUES ('";
				$qry .= FSSJ3Helper::getEscaped($db, $this->id) . "',";
				$qry .= "'" . FSSJ3Helper::getEscaped($db, $filename) . "',";
				$qry .= "'" . FSSJ3Helper::getEscaped($db, $destname) . "',";
				$qry .= "'" . $size . "',";
				$qry .= "'" . FSSJ3Helper::getEscaped($db, $message->user_id) . "',";
				$qry .= "'".$message->posted."', ".$message->id.", 1)";

				$db->setQuery($qry);$db->Query();  
				
				$attach_id = $db->insertid();  
			}
			$key = FSS_Helper::base64url_encode(FSS_Helper::encrypt($attach_id, FSS_Helper::getEncKey("file")));
			$replace = "[img]" . JURI::base() . "index.php?option=com_fss&view=image&fileid={$attach_id}&key={$key}" . "[/img]";

			$body = substr($body, 0, $start) . $replace . substr($body, $end+6);
		}

		if ($count > 0)
		{
			$qry = "UPDATE #__fss_ticket_messages SET body = \"" . $db->escape($body) . "\" WHERE id = " . $db->escape($message_id);
			$db->setQuery($qry);
			$db->Query();
		}
	}
}
