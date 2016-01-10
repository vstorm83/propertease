<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'support_ticket.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'support_source.php');

class SupportTickets
{
	var $tickets = array();
	var $ids = array();

	static $ref_lookup = array();

	function loadFromRows($rows)
	{
		foreach ($rows as $row)
		{
			$ticket = new SupportTicket();
			$ticket->loadFromRow($row);
			
			$this->ids[] = $ticket->id;
			$this->tickets[] = $ticket;
			$this->tickets_indexed[$ticket->id] = $ticket;

			static::$ref_lookup[$ticket->id] = $ticket->id . "-" . strtolower(preg_replace("/[^A-Za-z0-9]/", '-', $ticket->title));
		}
	}	

	function loadTags()
	{
		if (count($this->tickets) < 1)
			return;

		$db = JFactory::getDBO();
		$qry = "SELECT * FROM #__fss_ticket_tags WHERE ticket_id IN (" . implode(", ",$this->ids) . ")";
		$db->setQuery($qry);
		$items = $db->loadObjectList();

		foreach($items as $item)
			$this->tickets_indexed[$item->ticket_id]->tags[] = $item->tag;	
	}
	
	function loadAttachments()
	{
		if (count($this->tickets) < 1)
			return;

		$db = JFactory::getDBO();
		$qry = "SELECT * FROM #__fss_ticket_attach WHERE ticket_ticket_id IN (" . implode(", ",$this->ids) . ")";
		$db->setQuery($qry);
		$items = $db->loadObjectList();
		foreach($items as $item)
			$this->tickets_indexed[$item->ticket_ticket_id]->attach[] = $item;	
	}
	
	function loadGroups()
	{
		if (count($this->tickets) < 1)
			return;

		$user_ids = array();
		$db = JFactory::getDBO();
		
		foreach($this->tickets as &$ticket)
		{
			if ($ticket->user_id > 0)
				$user_ids[] = $ticket->user_id;
		}

		if (count($user_ids) == 0)
			return;

		$qry = "SELECT m.user_id, g.* FROM #__fss_ticket_group_members as m LEFT JOIN #__fss_ticket_group as g ON m.group_id = g.id WHERE m.user_id IN (" . implode(",",$user_ids) . ")";
		$db->setQuery($qry);
		$items = $db->loadObjectList();
		
		foreach($this->tickets as &$ticket)
		{
			if ($ticket->user_id == 0) continue;
			
			foreach ($items as $item)
			{
				if ($item->user_id == $ticket->user_id)
					$ticket->groups[] = $item;		
			}	
		}
	}
	
	function loadMessageCounts()
	{
		if (count($this->tickets) < 1)
			return;
		
		/*
		0 - User Message
		1 - Admin Message
		2 - Private Message
		3 - Audit message
		*/

		$db = JFactory::getDBO();
		$qry = "SELECT ticket_ticket_id, admin, count(*) as msgcnt FROM #__fss_ticket_messages WHERE ticket_ticket_id IN (" . implode(", ",$this->ids) . ") GROUP BY ticket_ticket_id, admin";
		$db->setQuery($qry);
		$items = $db->loadObjectList();
		
		foreach($items as $item)
		{
			$this->tickets_indexed[$item->ticket_ticket_id]->msgcount[$item->admin] = $item->msgcnt;
			if ($item->admin < 3)
			{
				$this->tickets_indexed[$item->ticket_ticket_id]->msgcount['total'] += $item->msgcnt;
			}
		}	
	}
	
	function loadMessages($reverse = null, $types = array())
	{
		if (count($this->tickets) < 1)
			return;
		
		$query = "SELECT m.*, u.name FROM #__fss_ticket_messages as m LEFT JOIN #__users as u ON m.user_id = u.id WHERE ticket_ticket_id IN (" . implode(", ",$this->ids) . ") ";
		
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
			
		//echo $query . "<br>";
		
		$messages = $db->loadObjectList();

		foreach ($messages as $message)
		{
			// skip based on admin id if required
			if (count($types) > 0)
			{
				if (!in_array($message->admin, $types))
				{
					//echo "Skipping {$message->admin_id}<br>";
					continue;	
				}
			}	
			
			$this->tickets_indexed[$message->ticket_ticket_id]->messages[] = $message;
		}
	}
	
	function loadLockedUsers()
	{
		$user_id = JFactory::getUser()->id;
		
		foreach($this->tickets as $ticket)
		{
			$cotime = FSS_Helper::GetDBTime() - strtotime($ticket->checked_out_time);
			if ($cotime < FSS_Settings::get('support_lock_time') && $ticket->checked_out != $user_id)
			{
				$ticket->co_user = JFactory::getUser($ticket->checked_out);
			}
		}
	}
	
	function loadCustomFields()
	{
		$this->customfields = FSSCF::GetAllCustomFields(true);
		
		if (count($this->tickets) == 0)
			return;

		$db = JFactory::getDBO();

		$qry = "SELECT * FROM #__fss_ticket_field WHERE ticket_id IN (" . implode(", ",$this->ids) . ")";
		$db->setQuery($qry);
		$rows = $db->loadObjectList();

		foreach ($rows as $row)
		{
			if (array_key_exists($row->ticket_id,$this->tickets_indexed))
			{
				$this->tickets_indexed[$row->ticket_id]->custom[$row->field_id] = $row->value;	
			}
		}
		
		$user_ids = array();
		
		foreach ($this->tickets as $ticket)
		{
			$ticket->customfields = $this->customfields;
			if ($ticket->user_id > 0)
				$user_ids[$ticket->user_id] = $ticket->user_id;
		}
		
		//print_p($user_ids);
		if (count($user_ids) > 0)
		{
			$qry = "SELECT * FROM #__fss_ticket_user_field WHERE user_id IN (" . implode(", ", $user_ids) . ")";
			$db->setQuery($qry);
			$rows = $db->loadObjectList();

			foreach ($rows as $row)
			{
				foreach ($this->tickets as $ticket)
				{
					if ($ticket->user_id == $row->user_id)
					{
						$ticket->custom[$row->field_id] = $row->value;	
					}	
				}
			}
		}
	}
	
	// functions to search and load tickets should be in here!
	
	
	function loadTicketsByStatus($tickets)
	{
		// Load a list of tickets based on the current view mode

		$db = JFactory::getDBO();
		
		$query = "SELECT t.*, s.title as status, s.color, u.name, au.name as assigned, u.email as useremail, u.username as username, au.email as handleremail, au.username as handlerusername, \n";
		$query .= " dept.title as department, cat.title as category, prod.title as product, pri.title as priority, pri.color as pricolor, \n";
		$query .= " grp.groupname as groupname, grp.id as group_id \n";
		$query .= " , pri.translation as ptl, dept.translation as dtr, s.translation as str, cat.translation as ctr, prod.translation as prtr\n";
		$query .= " FROM #__fss_ticket_ticket as t\n ";
		$query .= " LEFT JOIN #__fss_ticket_status as s ON t.ticket_status_id = s.id \n";
		$query .= " LEFT JOIN #__users as u ON t.user_id = u.id \n";
		$query .= " LEFT JOIN #__users as au ON t.admin_id = au.id \n";
		$query .= " LEFT JOIN #__fss_ticket_dept as dept ON t.ticket_dept_id = dept.id \n";
		$query .= " LEFT JOIN #__fss_ticket_cat as cat ON t.ticket_cat_id = cat.id \n";
		$query .= " LEFT JOIN #__fss_prod as prod ON t.prod_id = prod.id \n";
		$query .= " LEFT JOIN #__fss_ticket_pri as pri ON t.ticket_pri_id = pri.id \n";
		$query .= " LEFT JOIN (SELECT group_id, user_id FROM #__fss_ticket_group_members GROUP BY user_id) as mem ON t.user_id = mem.user_id \n";
		$query .= " LEFT JOIN #__fss_ticket_group as grp ON grp.id = mem.group_id \n";
		
		// add custom fields to the sql
		foreach (FSSCF::GetAllCustomFields() as $field)
		{
			if (!$field['inlist']) continue;

			$id = $field['id'];

			if ($field['peruser'])
			{
				$query .= " LEFT JOIN #__fss_ticket_user_field as cf{$id} ON cf{$id}.user_id = t.user_id AND cf{$id}.field_id = {$id} \n";
			} else {
				$query .= " LEFT JOIN #__fss_ticket_field as cf{$id} ON cf{$id}.ticket_id = t.id AND cf{$id}.field_id = {$id} \n";
			}
		}
		
			
		$def_open = FSS_Ticket_Helper::GetStatusID('def_open');
			
		$tickets = FSS_Input::getCmd('tickets',$def_open);

		if ($tickets == "open")
		{
			$open = FSS_Ticket_Helper::GetStatusIDs("def_open");
			// tickets that arent closed
			$query .= " WHERE ticket_status_id IN ( " . implode(", ", $open) . ")\n ";			
		} else if ($tickets == 'allopen')
		{
			$allopen = FSS_Ticket_Helper::GetStatusIDs("is_closed", true);
			// tickets that arent closed
			$query .= " WHERE ticket_status_id IN ( " . implode(", ", $allopen) . ") \n";
		}
		elseif ($tickets == 'closed')
		{
			$allopen = FSS_Ticket_Helper::GetStatusIDs("is_closed");
			// remove the archived tickets from the list to deal with
				
			$def_archive = FSS_Ticket_Helper::GetStatusID('def_archive');
			foreach ($allopen as $offset => $value)
				if ($value == $def_archive)
					unset($allopen[$offset]);

			// tickets that are closed
			$query .= " WHERE ticket_status_id IN ( " . implode(", ", $allopen) . ")\n ";
		}
		elseif ($tickets == 'all')
		{
			// need all tickets that arent archived
			$allopen = FSS_Ticket_Helper::GetStatusIDs("def_archive", true);
			$query .= " WHERE ticket_status_id IN ( " . implode(", ", $allopen) . " ) \n";
		}
		elseif ($tickets == 'archived')
		{
			// need all tickets that arent archived
			$allopen = FSS_Ticket_Helper::GetStatusIDs("def_archive");
			$query .= " WHERE ticket_status_id IN ( " . implode(", ", $allopen) . " ) \n";
		}
		else
		{
			$query .= " WHERE ticket_status_id = " . (int)FSSJ3Helper::getEscaped($db, $tickets) . "\n";
		}

		$query .= " AND " . SupportUsers::getAdminWhere() . "\n";
		$query .= " AND " . SupportSource::admin_show_sql() . "\n";

		$order = array();
		if (SupportUsers::getSetting("group_products"))
			$order[] = "prod.ordering";
				
		if (SupportUsers::getSetting("group_departments"))
			$order[] = "dept.title";
				
		if (SupportUsers::getSetting("group_cats"))
			$order[] = "cat.title";
				
		if (SupportUsers::getSetting("group_pri"))
			$order[] = "pri.ordering DESC";
				
		if (SupportUsers::getSetting("group_group"))
		{
			$order[] = "case when grp.groupname is null then 1 else 0 end";
			$order[] = "grp.groupname";
		}
				
		$ordering = JFactory::getApplication()->getUserStateFromRequest("fss_admin.ordering","ordering","");
		if ($ordering)
		{
			$order = array();
			$ordering = str_replace(".asc", " ASC", $ordering);
			$ordering = str_replace(".desc", " DESC", $ordering);

			$order[] = $ordering;
		} else {
			$order[] = "lastupdate DESC";
		}

		$query .= " ORDER BY " . implode(", ", $order) . "\n";

		$db->setQuery($query);
		$db->query();
		$this->ticket_count = $db->getNumRows();
					
		//echo "<pre>".$query . "</pre>";
		$session = JFactory::getSession();
		$session->set("last_admin_query", (string)$query);
		$session->set("last_admin_list", $_SERVER['REQUEST_URI']);
		$session->set("last_admin_post", $_POST);

		$db->setQuery($query, $this->limitstart, $this->limit);
			
		$this->LoadFromRows($db->loadObjectList());	
	}
	
	private function getTagFilter()
	{
		$tags = FSS_Input::getString('tags','');
		$tags = trim($tags,';');
		if ($tags)
		{
			$tags_ = explode(";",$tags);
			$tags = array();
			foreach($tags_ as $tag)
			{
				if ($tag)
					$tags[$tag] = $tag;
			}

			if (count($tags) > 0)
			{
				foreach($tags as $tag)
				{
					$qry = "SELECT ticket_id FROM #__fss_ticket_tags WHERE tag = '".FSSJ3Helper::getEscaped($db, $tag)."'";
					$db->setQuery($qry);
					return $this->TicketIDsToWhere($db->loadObjectList(), "ticket_id");	
				}	
			}
		}
		
		return "1";
	}
	
	private function TicketIDsToWhere($ticketids, $field)
	{
		return $this->IDsToWhere($ticketids, "t.id", $field);
	}
	
	private function IDsToWhere($ticketids, $target, $field)
	{
		if (!$ticketids)
			return "0";
		
		$tids = array();
		if (count($ticketids) < 1)
			return "0";
		
		foreach ($ticketids as $ticketid)
		{
			$id = $ticketid->$field;
			if ($id > 0) $tids[] = $id;
		}
		
		if (count($tids) > 0)
			return "$target IN (".implode(",",$tids).")";

		return "0";
	}
		
	function loadTicketsBySearch()
	{
		$db = JFactory::getDBO();
		
		$query = "SELECT t.*, s.title as status, s.color, u.name, au.name as assigned, u.email as useremail, u.username as username, au.email as handleremail, au.username as handlerusername, \n";
		$query .= " dept.title as department, cat.title as category, prod.title as product, pri.title as priority, pri.color as pricolor, \n";
		$query .= " grp.groupname as groupname, grp.id as group_id \n";
		$query .= " , pri.translation as ptl, dept.translation as dtr, s.translation as str, cat.translation as ctr, prod.translation as prtr\n";
		$query .= " FROM #__fss_ticket_ticket as t \n";
		$query .= " LEFT JOIN #__fss_ticket_status as s ON t.ticket_status_id = s.id \n";
		$query .= " LEFT JOIN #__users as u ON t.user_id = u.id \n";
		$query .= " LEFT JOIN #__users as au ON t.admin_id = au.id \n";
		$query .= " LEFT JOIN #__fss_ticket_dept as dept ON t.ticket_dept_id = dept.id \n";
		$query .= " LEFT JOIN #__fss_ticket_cat as cat ON t.ticket_cat_id = cat.id \n";
		$query .= " LEFT JOIN #__fss_prod as prod ON t.prod_id = prod.id \n";
		$query .= " LEFT JOIN #__fss_ticket_pri as pri ON t.ticket_pri_id = pri.id \n";
		$query .= " LEFT JOIN (SELECT group_id, user_id FROM #__fss_ticket_group_members GROUP BY user_id) as mem ON t.user_id = mem.user_id \n";
		$query .= " LEFT JOIN #__fss_ticket_group as grp ON grp.id = mem.group_id \n";
		
		// add custom fields to the sql
		foreach (FSSCF::GetAllCustomFields() as $field)
		{
			if (!$field['inlist']) continue;

			$id = $field['id'];

			if ($field['peruser'])
			{
				$query .= " LEFT JOIN #__fss_ticket_user_field as cf{$id} ON cf{$id}.user_id = t.user_id AND cf{$id}.field_id = {$id} \n";
			} else {
				$query .= " LEFT JOIN #__fss_ticket_field as cf{$id} ON cf{$id}.ticket_id = t.id AND cf{$id}.field_id = {$id} \n";
			}
		}
		

		$searchtype = FSS_Input::getCmd('searchtype','basic');
		$wherebits = array();

		if ($searchtype == "basic")
		{
			$search = FSS_Input::getString('search','');

			if ($search != "")
			{
				$mode = "";
				if (FSS_Helper::contains($search, array('*', '+', '-', '<', '>', '(', ')', '~', '"')))
					$mode = "IN BOOLEAN MODE";

				//$wherebits[] = " t.title LIKE '%".FSSJ3Helper::getEscaped($db, $search)."%' ";
				$wherebits[] = " MATCH (t.title) AGAINST ('" . $db->escape($search) . "' $mode) /* Title */ ";
				$wherebits[] = " t.reference LIKE '%".FSSJ3Helper::getEscaped($db, $search)."%' /* Reference */ ";
			
				// search custom fields that are set to be searched
				$this->searchFields($wherebits, "basicsearch");
				
				// basic search optional fields
				if (FSS_Settings::get('support_basic_name'))
				{
					$wherebits[] = " u.name LIKE '%".FSSJ3Helper::getEscaped($db, $search)."%' /* Name */ ";
					$wherebits[] = " unregname LIKE '%".FSSJ3Helper::getEscaped($db, $search)."%' /* UnReg Name */ ";
				}

				if (FSS_Settings::get('support_basic_username'))
				{
					$wherebits[] = " u.username LIKE '%".FSSJ3Helper::getEscaped($db, $search)."%' /* Username */ ";
				}

				if (FSS_Settings::get('support_basic_email'))
				{
					$wherebits[] = " u.email LIKE '%".FSSJ3Helper::getEscaped($db, $search)."%' /* User email */ ";
					$wherebits[] = " t.email LIKE '%".FSSJ3Helper::getEscaped($db, $search)."%' /* Unreg Email */ ";
				}

				if (FSS_Settings::get('support_basic_messages'))
				{
					$qry = "SELECT ticket_ticket_id FROM #__fss_ticket_messages WHERE subject LIKE '%" . FSSJ3Helper::getEscaped($db, $search) . "%' OR ";
					$qry .= " MATCH (body) AGAINST ('" . $db->escape($search) . "' $mode) GROUP BY ticket_ticket_id";
					$qry .= " AND admin IN (0, 1, 2, 4) ";
					$db->setQuery($qry);	
					$wherebits[] = $this->TicketIDsToWhere($db->loadObjectList(), "ticket_ticket_id") . " /* Messages */ ";			
				}
			}
			
			if (count($wherebits) == 0)
				$wherebits[] = "1 /* Catch All */";

			$query .= "\n WHERE (" . implode("\n OR ", $wherebits) . ")";

		} else if ($searchtype == "advanced")
		{
			$search = FSS_Input::getString('search','');
			$wherebits = array();
			
			$subject = FSS_Input::getString('subject','');
			if ($subject)
				$wherebits[] = " t.title LIKE '%".FSSJ3Helper::getEscaped($db, $subject)."%' /* Title */ ";
			
			$reference = FSS_Input::getString('reference','');
			if ($reference)
				$wherebits[] = " t.reference LIKE '%".FSSJ3Helper::getEscaped($db, $reference)."%' /* Reference */ ";
			
			$username = FSS_Input::getString('username','');
			if ($username)
				$wherebits[] = " u.username LIKE '%".FSSJ3Helper::getEscaped($db, $username)."%' /* Username */ ";
			
			$useremail = FSS_Input::getString('useremail','');
			if ($useremail)
				$wherebits[] = " ( u.email LIKE '%".FSSJ3Helper::getEscaped($db, $useremail)."%' OR t.email LIKE '%".FSSJ3Helper::getEscaped($db, $useremail)."%' ) /* EMail */";
			
			$userfullname = FSS_Input::getString('userfullname','');
			if ($userfullname)
				$wherebits[] = " ( u.name LIKE '%".FSSJ3Helper::getEscaped($db, $userfullname)."%' OR unregname LIKE '%".FSSJ3Helper::getEscaped($db, $userfullname)."%' ) /* Name */";
			
			$content = FSS_Input::getString('content','');
			if ($content)
			{
				$mode = "";
				if (FSS_Helper::contains($search, array('*', '+', '-', '<', '>', '(', ')', '~', '"')))
					$mode = "IN BOOLEAN MODE";

				$qry = "SELECT ticket_ticket_id FROM #__fss_ticket_messages WHERE subject LIKE '%" . FSSJ3Helper::getEscaped($db, $content) . "%' OR ";
				$qry .= " MATCH (body) AGAINST ('" . $db->escape($content) . "' $mode)";
				$qry .= " AND admin IN (0, 1, 2, 4) ";
				$db->setQuery($qry);	
				$wherebits[] = $this->TicketIDsToWhere($db->loadObjectList(), "ticket_ticket_id") . " /* Messages */ ";			
			}
			
			$handler = FSS_Input::getInt('handler','');
			if ($handler)
			{
				$user = JFactory::getUser();
				if ($handler == -5) // my tickets
				{						
					$wherebits[] = " t.admin_id = '".$user->id."' /* Handler -1 */ ";
				} else if ($handler == -2) // unassigned
				{
					$wherebits[] = " t.admin_id != '".$user->id."' /* Handler -2 */ ";
					$wherebits[] = " t.admin_id != 0 /* Handler -2 */";
				} else if ($handler == -3) // unassigned
				{
					$wherebits[] = " t.admin_id = 0 /* Handler -3 */" ;
				}  elseif ($handler == -4) // mine and cc'd
				{
					$temp_where[] = " t.admin_id = '".$db->escape($user->id)."' ";
					
					$qry = "SELECT ticket_id FROM #__fss_ticket_cc WHERE isadmin = 1 AND user_id = " . $db->escape($user->id);
					
					$db->setQuery($qry);
					$wherebits[] = $this->TicketIDsToWhere($db->loadObjectList(), "ticket_id") . " /* Handler -4 */";		

				} else { // handler
				
					if ($handler == -1)
						$handler = JFactory::getUser()->id;
				
					$qry = "SELECT * FROM #__fss_ticket_cc WHERE isadmin = 1 AND user_id = " . $db->escape($handler);
					$db->setQuery($qry);
					
					$temp_where[] = $this->TicketIDsToWhere($db->loadObjectList(), "ticket_id");	
					$temp_where[] = " t.admin_id = '".$db->escape($handler)."' ";
					
					$wherebits[] = " ( " . implode(" OR ", $temp_where) . " ) /* Handler specified */ ";					
					
					//$wherebits[] = " t.admin_id = '".FSSJ3Helper::getEscaped($db, $handler)."' ";
				}
			}
			
			$status = FSS_Input::getCmd('status','');
			if ($status)
			{
				if ($status == "open")
				{
					$open = FSS_Ticket_Helper::GetStatusIDs("def_open");
					// tickets that arent closed
					$wherebits[] = " t.ticket_status_id IN ( " . implode(", ", $open) . ") /* Def open */ ";			
				} 
				elseif ($status == 'allopen')
				{
					$allopen = FSS_Ticket_Helper::GetStatusIDs("is_closed", true);
					// tickets that arent closed
					$wherebits[] = " t.ticket_status_id IN ( " . implode(", ", $allopen) . ") /* All open */ ";
				}
				elseif ($status == 'closed')
				{
					$allopen = FSS_Ticket_Helper::GetStatusIDs("is_closed");
					// remove the archived tickets from the list to deal with
				
					$def_archive = FSS_Ticket_Helper::GetStatusID('def_archive');
					foreach ($allopen as $offset => $value)
						if ($value == $def_archive)
							unset($allopen[$offset]);

					// tickets that are closed
					$wherebits[] = " t.ticket_status_id IN ( " . implode(", ", $allopen) . ") /* all closed */";
				}
				elseif ($status == 'all')
				{
					// need all tickets that arent archived
					$allopen = FSS_Ticket_Helper::GetStatusIDs("def_archive", true);
					$wherebits[] = " t.ticket_status_id IN ( " . implode(", ", $allopen) . " ) /* all (not archived) */ ";
				}
				elseif ($status == 'archived')
				{
					// need all tickets that arent archived
					$allopen = FSS_Ticket_Helper::GetStatusIDs("def_archive");
					$wherebits[] = " t.ticket_status_id IN ( " . implode(", ", $allopen) . " ) /* archived */ ";
				}
				else
				{
					$wherebits[] = " t.ticket_status_id = " . (int)FSSJ3Helper::getEscaped($db, $status) . " /* specified status */";
				}
			}
			
			$product = FSS_Input::getInt('product','');
			if ($product)
				$wherebits[] = " t.prod_id = '".FSSJ3Helper::getEscaped($db, $product)."' /* Product */";
			
			$department = FSS_Input::getInt('department','');
			if ($department)
				$wherebits[] = " t.ticket_dept_id = '".FSSJ3Helper::getEscaped($db, $department)."' /* Department */ ";
			
			$cat = FSS_Input::getInt('cat','');
			if ($cat)
			$wherebits[] = " t.ticket_cat_id = '".FSSJ3Helper::getEscaped($db, $cat)."' /* Category */";
			 
			$pri = FSS_Input::getInt('priority','');
			if ($pri)
			$wherebits[] = " t.ticket_pri_id = '".FSSJ3Helper::getEscaped($db, $pri)."' /* Priority */";
				
			$group = FSS_Input::getInt('group','');
			if ($group > 0)
			{
				$wherebits[] = " t.user_id IN (SELECT user_id FROM #__fss_ticket_group_members WHERE group_id = '".FSSJ3Helper::getEscaped($db, $group)."' GROUP BY user_id) /* Ticket Group */";
			}
		
			$date_from = FSS_Helper::DateValidate(FSS_Input::getString('date_from',''));
			$date_to = FSS_Helper::DateValidate(FSS_Input::getString('date_to',''));

			if ($date_from)
			$wherebits[] = " t.lastupdate > DATE_SUB('".FSSJ3Helper::getEscaped($db, $date_from)."',INTERVAL 1 DAY) /* Date From */";
			
			if ($date_to)
			$wherebits[] = " t.opened < DATE_ADD('".FSSJ3Helper::getEscaped($db, $date_to)."',INTERVAL 1 DAY) /* Date To */";

			$this->searchFields($wherebits, "advancedsearch");

			if (count($wherebits) == 0) $wherebits[] = "1  /* Catch All */";
			
			$query .= "\n WHERE " . implode("\n AND ", $wherebits);
		} else {
			$query .= " WHERE 1  /* Catch All */";
		}

		$query .= "\n AND " . $this->getTagFilter() . " /* Tag Filter */";
		$query .= "\n AND " . SupportUsers::getAdminWhere() . " /* getAdminWhere */";
		$query .= "\n AND " . SupportSource::admin_show_sql() . " /* admin_show_sql */";

		$order = array();
		if (SupportUsers::getSetting("group_products"))
			$order[] = "prod.ordering";
				
		if (SupportUsers::getSetting("group_departments"))
			$order[] = "dept.title";
				
		if (SupportUsers::getSetting("group_cats"))
			$order[] = "cat.title";
				
		if (SupportUsers::getSetting("group_pri"))
			$order[] = "pri.ordering DESC";
				
		if (SupportUsers::getSetting("group_group"))
		{
			$order[] = "case when grp.groupname is null then 1 else 0 end";
			$order[] = "grp.groupname";
		}
							
		$ordering = JFactory::getApplication()->getUserStateFromRequest("fss_admin.ordering","ordering","");
		if ($ordering)
		{
			$order = array();
			$ordering = str_replace(".asc", " ASC", $ordering);
			$ordering = str_replace(".desc", " DESC", $ordering);

			$order[] = $ordering;
		} else {
			$order[] = "lastupdate DESC";
		}

		$query .= "\n ORDER BY " . implode(", ", $order);
		
		$session = JFactory::getSession();
		$session->set("last_admin_query", (string)$query);
		$session->set("last_admin_list", $_SERVER['REQUEST_URI']);
		$session->set("last_admin_post", $_POST);
							
		//echo "<br>".str_replace("\n", "<br>", $query)."<br>";
		$db->setQuery($query);
		$db->query();
		$this->ticket_count = $db->getNumRows();
			
		$db->setQuery($query, $this->limitstart, $this->limit);
		$this->LoadFromRows($db->loadObjectList());
	}
	
	private function searchFields(&$wherebits, $type)
	{
		// search custom fields that are set to be searched
		$fields = FSSCF::GetAllCustomFields(true);

		foreach ($fields as $field)
		{			
			if (!$field[$type])
				continue;
			
			$fieldid = $field['id'];

			$search = FSS_Input::getString('custom_' . $fieldid,"");
			
			if ($type == "basicsearch")
				$search = FSS_Input::getString('search','');
			
			if ($search == "")
				continue;
						
			if ($field['type'] == "checkbox")
			{
				if ($search == "1")
				{
					$search = "on";
				} else {
					$search = "";
				}
			}
						
			if ($field['type'] == "plugin")
			{
				// try to do a plugin based search
				$data = array();
				foreach ($field['values'] as $item)
				{
					list($key, $value) = explode("=", $item, 2);
					$data[$key] = $value;	
				}
				if (array_key_exists("plugin", $data))
				{
					$plugins = FSSCF::get_plugins();
					if (array_key_exists($data['plugin'], $plugins))
					{
						$po = $plugins[$data['plugin']];	

						if (method_exists($po, "Search"))
						{
							$res = $po->Search($data['plugindata'], $search, $field['peruser']);
							
							if ($res !== false)
							{
								if ($field['peruser'])
								{
									$wherebits[] = $this->IDsToWhere($res, "t.user_id", "user_id"). " /* Per User Plugin - " . $field['id'] . " / " . $field['alias'] . " */";
								} else {								
									$wherebits[] = $this->TicketIDsToWhere($res, "ticket_id"). " /* Plugin - " . $field['id'] . " / " . $field['alias'] . " */";			
								}	
								continue;
							}
						}
					}
				}
			}

			if ($field['peruser'])
			{
				
				$qry = "SELECT user_id FROM #__fss_ticket_user_field WHERE field_id = '" . FSSJ3Helper::getEscaped($db, $fieldid) . "' AND value LIKE '%" . FSSJ3Helper::getEscaped($db, $search) . "%'";	
				$db->setQuery($qry);
				$res = $db->loadObjectList();
				$wherebits[] = $this->IDsToWhere($res, "t.user_id", "user_id"). " /* Per User CF - " . $field['id'] . " / " . $field['alias'] . " */";
			} else {
				$qry = "SELECT ticket_id FROM #__fss_ticket_field WHERE field_id = '" . FSSJ3Helper::getEscaped($db, $fieldid) . "' AND value LIKE '%" . FSSJ3Helper::getEscaped($db, $search) . "%'";
				$db->setQuery($qry);	
				$res = $db->loadObjectList();
				$wherebits[] = $this->TicketIDsToWhere($res, "ticket_id"). " /* CF - " . $field['id'] . " / " . $field['alias'] . " */";			
			}
		}	
	}
	
	function loadTicketsByQuery($where_parts, $order = "t.id DESC")
	{
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
		
		$query .= " WHERE " . implode(" AND ", $where_parts);
		$query .= " ORDER BY " . $order;
		
		$db->setQuery($query);
		$db->query();
		$this->ticket_count = $db->getNumRows();

		$db->setQuery($query, $this->limitstart, $this->limit);
		$this->LoadFromRows($db->loadObjectList());	
	}
	
	
	static $counts;
	// at moment this is an ADMIN ONLY funciton, needs to be adapted for users
	static function &getTicketCount($foradmin = true, $current_handler_only = false)
	{
		$key = 0;
		if ($foradmin)
			$key += 1;
		if ($current_handler_only)
			$key += 2;
		
		if (empty(self::$counts))
			self::$counts = array();
		
		if (!array_key_exists($key, self::$counts))
		{
			$db = JFactory::getDBO();
			$query = "SELECT count( * ) AS count, ticket_status_id FROM #__fss_ticket_ticket as t WHERE 1 ";
						
			$query .= " AND " . SupportUsers::getAdminWhere();
			if ($foradmin)
			{
				$query .= " AND " . SupportSource::admin_list_sql();
			} else {
				$query .= " AND " . SupportSource::user_list_sql();
			}
			
			if ($current_handler_only)
				$query .= " AND admin_id = " . JFactory::getUser()->id;
			
			$query .= " GROUP BY ticket_status_id";
	
			$db->setQuery($query);
			$rows = $db->loadAssocList();
			
			$out = array();
			FSS_Ticket_Helper::GetStatusList();
			foreach (FSS_Ticket_Helper::$status_list as $status)
			{
				$out[$status->id] = 0;
			}
			
			if (count($rows) > 0)
			{
				foreach ($rows as $row)
				{
					$out[$row['ticket_status_id']] = $row['count'];
				}
			}
			
			// work out counts for allopen, closed, all, archived
			
			$archived = FSS_Ticket_Helper::GetStatusID("def_archive");
			$out['archived'] = 0;
			if (array_key_exists($archived, $out))
				$out['archived'] = $out[$archived];


			$allopen = FSS_Ticket_Helper::GetStatusIDs("is_closed", true);
			$out['allopen'] = 0;
			foreach ($allopen as $id)
			{
				if (array_key_exists($id, $out))
					$out['allopen'] += $out[$id];
			}
		
			
			$allclosed = FSS_Ticket_Helper::GetClosedStatus();
			$out['allclosed'] = 0;
			foreach ($allclosed as $id)
			{
				if (array_key_exists($id, $out))
					$out['allclosed'] += $out[$id];
			}

			
			$all = FSS_Ticket_Helper::GetStatusIDs("def_archive", true);
			$out['all'] = 0;
			foreach ($all as $id)
			{
				if (array_key_exists($id, $out))
					$out['all'] += $out[$id];
			}
			
			
			self::$counts[$key] = $out;
		}
		return self::$counts[$key];	
	}

}