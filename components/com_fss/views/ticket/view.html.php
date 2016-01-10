<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;


jimport( 'joomla.application.component.view');
jimport('joomla.filesystem.file');
jimport('joomla.utilities.date');

require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'pagination.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'tickethelper.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'fields.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'email.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'helper.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'parser.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'parser_ticket.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'support_actions.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'support_ticket.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'support_print.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'support_tickets.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'third'.DS.'simpleimage.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'multicol.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'support_canned.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'captcha.php');

class FssViewTicket extends FSSView
{
	function display($tpl = null)
	{
		$autologin = FSS_Input::getCmd('login');
		if ($autologin != "") FSS_Helper::AutoLogin($autologin);

		if (!FSS_Permission::auth("fss.ticket.view", "com_fss.support_user") && 
			!FSS_Permission::auth("fss.ticket.open", "com_fss.support_user"))
			return FSS_Helper::NoPerm();	

		$session = JFactory::getSession();
		
		$user = JFactory::getUser();
		$this->userid = $user->get('id');
		
		$this->ticket = null;
		$this->assign('tmpl','');
		
		$what = FSS_Input::getCmd('what');
		$layout = FSS_Input::getCmd('layout');

		$this->ticket_view = FSS_Input::getCmd('tickets');
		
		if (!$this->ticket_view && FSS_Settings::get('support_simple_userlist_tabs'))
			$this->ticket_view = "all";
		
		// reset the login and password
		if ($what == "reset")
		{
			$session->clear('ticket_email');
			$session->clear('ticket_reference');
			$session->clear('ticket_name');
			$session->clear('ticket_pass');
		}

		if ($what == "unreg_passlist")
			return $this->Unreg_Passlist();

		if ($what == "addccuser")
			return $this->AddCCUser();
		if ($what == "removeccuser")
			return $this->RemoveCCUser();

		if ($what == "pickccuser")
			return $this->PickCCUser();

		// should we display the edit field screen 
		if ($what == 'editfield')
			return $this->EditField();	
		
		// save an edited field and continue what we were doing afterwards
		if ($what == 'savefield')
			if ($this->SaveField())
				return;
		
		FSS_Helper::AddSCEditor();

		// check for product search ajax display
		if (FSS_Input::getString('prodsearch') != "")
			return $this->searchProducts();	

		if (FSS_Input::getString('deptsearch') != "")
			return $this->searchDepartments();	
		
		// page to hunt for unregistered ticket
		if ($what == "find")
			return $this->findTicket();

		// save status changes
		if ($what == "statuschange")
			return $this->saveStatusChanges();
		
		// save any replys
		if ($what == 'savereply')
			return $this->saveReply();
			
		// save any replys
		if ($what == 'messages')
			return $this->showMessages();

		// process any file downloads
		$fileid = FSS_Input::getInt('fileid');            
		if ($fileid > 0)
		{
			$ticketid = FSS_Input::GetInt('ticketid');
			if ($what == 'attach_thumb')
			{
				return SupportHelper::attachThumbnail($ticketid, $fileid, true);
			} else {
				return SupportHelper::attachDownload($ticketid, $fileid, true);
			}
		}    
				
		$this->count = $this->get('TicketCount');
		// handle opening ticket
		if ($layout == "open")
			return $this->doOpenTicket();
	
		// handel ticket reply
		if ($layout == "reply")
			return $this->doUserReply();

		// display ticket list / ticket
		return $this->doDisplayTicket();
	}
	
	function searchProducts()
	{
		$mainframe = JFactory::getApplication();
		$aparams = $mainframe->getPageParameters('com_fss');
				
		$pagination = $this->get('ProdPagination');
		$this->pagination = $pagination;

		$search = FSS_Input::getString('prodsearch');  
		
		$this->prodsearch = $search;
		$this->products = $this->get("Products");
		
		parent::display("search"); 
		exit;
	} 
	
	function searchDepartments()
	{
		$mainframe = JFactory::getApplication();
		$aparams = $mainframe->getPageParameters('com_fss');
		
		$pagination = $this->get('DeptPagination');
		$this->pagination = $pagination;

		$search = FSS_Input::getString('deptsearch');  
		
		$this->deptsearch = $search;
		$this->depts = $this->get("Departments");

		parent::display("searchdept"); 
		exit;
	} 	
	// display the reply form
	function doUserReply()
	{
		$db = JFactory::getDBO();
		$mainframe = JFactory::getApplication();
		
		$errors['subject'] = '';
		$errors['body'] = '';
		$errors['cat'] = '';
		$this->errors = $errors;
		

		if (!$this->GetTicket())
			return;
		
		$this->getCCInfo();
				
		$pathway = $mainframe->getPathway();
		$pathway->addItem(JText::_("TICKET") . " : " . $this->ticket['reference'] . " - " . $this->ticket['title'],FSSRoute::_( 'index.php?option=com_fss&view=ticket&ticketid=' . $this->ticket['id'] ));
		$pathway->addItem(JText::_("POST_REPLY"));
		
		$this->setLayout("reply");
		parent::display();	
	}
		
	function saveStatusChanges()
	{ 
		$user = JFactory::getUser();
		$userid = $user->get('id');

		if (!$this->ValidateUser())
			return;
				
		$ticketid = FSS_Input::getInt('ticketid');
		$now = FSS_Helper::CurDate();
		
		// check for any changed status submitted and save em
		$new_status = FSS_Input::getInt('new_status'); 
		$new_pri = FSS_Input::getInt('new_pri'); 
		
		$model = $this->getModel();
		$this->ticket = $model->getTicket($ticketid);
		
		$this->GetTicketPerms($this->ticket);
		
		// check for status change
		if ($this->ticket['ticket_status_id'] != $new_status && !$this->ticket['can_close'])
			return $this->noPermission();	
			
		// check for pri change	
		if ($this->ticket['ticket_pri_id'] != $new_pri && !$this->ticket['can_edit'])
			return $this->noPermission();		
		
		$uids = $model->getUIDS($userid);

		if (!array_key_exists($this->ticket['user_id'], $uids))
		{
			$this->getCCInfo();
			// doesnt have permission to view, check cc list
			if (!array_key_exists("cc",$this->ticket))
				return $this->noPermission();
				
			$found = false;
			foreach ($this->ticket['cc'] as &$user)
			{
				if ($user['id'] == $userid)
					$found = true;		
			}
			if (!$found)
				return $this->noPermission();
		}
		
		/*$model = $this->getModel(); 
		$ticket = $model->getTicket($ticketid);*/
		//print_r($ticket);
		$changed = false;
		$date = false;

		if ($new_status != $this->ticket['ticket_status_id'])
		{
			$oldstatus = $model->GetStatus($this->ticket['ticket_status_id']);
			$newstatus = $model->GetStatus($new_status);
			$this->AddTicketAuditNote($this->ticket['id'],"Status changed from '" . $oldstatus['title'] . "' to '" . $newstatus['title'] . "'");
			
			$st = FSS_Ticket_Helper::GetStatusByID($new_status);
			
			if (!$st->is_closed)
				$date = true;
			$changed = true;
			
		}
		if ($new_pri != $this->ticket['ticket_pri_id'])
		{
			$oldpri = $model->GetPriority($this->ticket['ticket_pri_id']);
			$newpri = $model->GetPriority($new_pri);
			$this->AddTicketAuditNote($this->ticket['id'],"Priority changed from '" . $oldpri['title'] . "' to '" . $newpri['title'] . "'");
			
			$date = true;
			$changed = true;
		}
		
		if ($new_status > 0)
		{
			$db = JFactory::getDBO();
			$sets = array();
			
			$st = FSS_Ticket_Helper::GetStatusByID($new_status);
			if ($new_status != $this->ticket['ticket_status_id'])
			{
				if ($st->is_closed)
				{
					$sets[] = "closed = '{$now}'";	
				} else {
					$sets[] = "closed = NULL";	
				}	
			}	
			
			$qry = "UPDATE #__fss_ticket_ticket SET ";
			if ($new_pri != $this->ticket['ticket_pri_id'])
				$sets[] = "ticket_pri_id = '".FSSJ3Helper::getEscaped($db, $new_pri)."'";
			if ($new_status != $this->ticket['ticket_status_id'])
				$sets[] = "ticket_status_id = '".FSSJ3Helper::getEscaped($db, $new_status)."'";
				
			if ($date)
				$sets[] = "lastupdate = '{$now}'";

			if (count($sets) > 0)
			{
				$qry .= implode(", ", $sets);
				$qry .= " WHERE id = '".FSSJ3Helper::getEscaped($db, $ticketid)."'";
				$db->setQuery($qry);$db->Query();
			}
			$this->ticket = $model->getTicket($ticketid);
		}	
		
		// forward with what=
		$mainframe = JFactory::getApplication();
		$link = FSSRoute::_('&what=new_status=new_pri=',false);// FIX LINK
		$mainframe->redirect($link);		
	}	
	
	function doOpenTicket()
	{
		if (!FSS_Permission::auth("fss.ticket.open", "com_fss.support_user"))
			return FSS_Helper::NoPerm();	

		$mainframe = JFactory::getApplication();
		$user = JFactory::getUser();
		$userid = $user->get('id');
		$this->userid = $userid;
		$this->email = '';
		$this->admin_create = 0;
		
		$session = JFactory::getSession();
			
		if (FSS_Input::getInt('admincreate') > 0)
		{
			$session->Set("admin_create", FSS_Input::getInt('admincreate'));
			
			if ($session->Get("admin_create") == 1 && FSS_Input::getInt('user_id') > 0)
			{
				$session->Set('admin_create_user_id', FSS_Input::getInt('user_id'));
			} else if ($session->Get("admin_create") == 2 && (FSS_Input::getString('admin_create_email') || FSS_Input::getString('admin_create_name')))
			{
				$session->Set('ticket_email', FSS_Input::getEMail('admin_create_email'));
				$session->Set('ticket_name', FSS_Input::getString('admin_create_name'));
			}
		}
		
		if ($session->Get("admin_create") == 1)
		{
			$this->admin_create = 1;
			$model = $this->getModel();
			$this->user = $model->getUser($session->Get('admin_create_user_id'));
		} else if ($session->Get("admin_create") == 2) {
			$this->unreg_email = $session->Get('ticket_email');
			$this->unreg_name = $session->Get('ticket_name');
			$this->admin_create = 2;
		}
		
		if (FSS_Settings::Get('support_only_admin_open') && $this->admin_create < 1)
		{
			return $this->noPermission("Access Denied", "CREATING_NEW_TICKETS_BY_USERS_IS_CURRENTLY_DISABLED");	
		}
		
		// store in session and data for an unregistered ticket
		$type = FSS_Input::getCmd('type');
		if ($type == "without")
		{
			$email = FSS_Input::getEMail('email');
			$name = FSS_Input::getString('name');
			
			if ($name == "")
				$name = $email;

			if ($email != "")
			{
				$session->Set('ticket_email', $email);
				$session->Set('ticket_name', $name);
			}
		}
	
		if (!$this->ValidateUser('open'))
			return;

		// defaults for blank ticket
		$ticket['priid'] = FSS_Input::getInt('priid', FSS_Settings::get('support_default_priority'));
		$ticket['body'] = FSS_Input::GetString('body');
		$ticket['subject'] = FSS_Input::GetString('subject');
		$ticket['handler'] = FSS_Input::getInt('handler',0);
		
		$this->ticket = $ticket;
		
		$errors['subject'] = '';
		$errors['body'] = '';
		$errors['cat'] = '';
		$errors['captcha'] = '';
		
		$this->errors = $errors;

		$prodid = FSS_Input::getInt('prodid');
		
		// prod id not set, should we display product list???
		if ($prodid < 1)
		{
			$this->products = $this->get('Products');
			if (count($this->products) > 1)
			{
				$this->search = "";
					
				$pagination = $this->get('ProdPagination');
				$this->pagination = $pagination;
				$this->limit = $this->get("ProdLimit");

				parent::display("product");
				return;
			} else if (count($this->products) == 1)
			{
				$prodid = $this->products[0]['id'];
				JRequest::setVar('prodid',$prodid);
				//echo "Setting prodid to $prodid<br>";
			}
		}
		
		$this->assign('prodid',$prodid);
		
		$deptid = FSS_Input::getInt('deptid');
		
		// dept id not set, should we display department list?
		if ($deptid < 1)
		{
			$this->search = "";
			$this->depts = $this->get('Departments');
			$this->limit = $this->get("ProdLimit");

			if (count($this->depts) > 1)
			{
				$this->pagination = $this->get('DeptPagination');

				$this->product = $this->get('Product');
				parent::display("department");
				return;
			} else if (count($this->depts) == 1)
			{
				$deptid = $this->depts[0]['id'];
				JRequest::setVar('deptid',$deptid);
				//echo "Setting deptid to $deptid<br>";
			}
		}
			
		$what = FSS_Input::getCmd('what');
		
		// done with ticket, try and save, if not, display any errors
		if ($what == "add")
		{
			if ($this->saveTicket())
			{

				$message = FSS_Helper::HelpText("support_open_thanks", true);
				if ($message != "")	FSS_Helper::enqueueMessage($message, "success");

				//exit;
				if ($this->admin_create > 0)
				{
					$link = 'index.php?option=com_fss&view=admin_support&Itemid=' . FSS_Input::getInt('Itemid','') . '&ticketid=' . $this->ticketid;
					$mainframe->redirect(FSSRoute::_($link, false));
				} else {
					// need to set the session info that will display the ticket to the user here!
					$link = 'index.php?option=com_fss&view=ticket&layout=view&Itemid=' . FSS_Input::getInt('Itemid','') . '&ticketid=' . $this->ticketid;
					$mainframe->redirect(FSSRoute::_($link, false));
				}		
				return;
			} else {
				//echo "Error saving ticket<br>";
			}
		}
		
		// load handlers if required. This depends on what product and department have been selected
		if (FSS_Settings::get('support_choose_handler') != "none")
		{
			$allow_no_auto = 0;
			
			if ($this->admin_create > 0)
			{ 
				$allow_no_auto = 1;
				$this->autohandlers = SupportUsers::getHandlersTicket($prodid, $deptid, 0);
			}
			
			$handlers = SupportUsers::getHandlersTicket($prodid, $deptid, 0, $allow_no_auto);

			/**
			 * I DONT KNOW IF THIS IS A GOOD CHANGE OR NOT, BUT IT MAKES IT CONSISTANT EVERYWHERE I THINK 
			 **/

			// if the hide super users checkbox is tickets, hide them all from the dropdown
			if (FSS_Settings::get('support_hide_super_users'))
			{
				foreach ($handlers as $offset => $handler)
				{
					$fssuser = SupportUsers::getUser($handler);
					$juser = JFactory::getUser($handler);
					if ($juser->get('isRoot') && $userid != $juser->id)
					{
						unset($handlers[$offset]);
					}
				}
			}

			if (count($handlers) == 0)
				$handlers[] = 0;
		
			$qry = "SELECT * FROM #__users WHERE id IN (" . implode(", ", $handlers) . ")";
			$db = JFactory::getDBO();
			
			$db->setQuery($qry);
			$handlers = $db->loadAssocList();
			
			$this->handlers = array();
			$h = array();
			$h['id'] = 0;
			$h['name'] = JText::_('AUTO_ASSIGN');
			$this->handlers[] = $h;
				
			if (is_array($handlers))
			{
				foreach ($handlers as $handler)
				{
					$this->handlers[] = $handler;
				}
			}
		}
				
		$this->assign('deptid',$deptid);
		
		$this->product = $this->get('Product');
		$this->dept = $this->get('Department');
		$this->cats = $this->get('Cats');
		$this->pris = $this->get('Priorities');
		$this->support_user_attach = FSS_Settings::get('support_user_attach');
		
		$this->fields = FSSCF::GetCustomFields(0,$prodid,$deptid);

		// load in captch and display if needed
		$this->sortCaptchaType();

		$captcha = new FSS_Captcha();
		$this->captcha = $captcha->GetCaptcha('support_captcha_type');
		parent::display();
	}

	function sortCaptchaType()
	{
		if ($this->admin_create > 0) FSS_Settings::set('support_captcha_type', 'none');
		$capset = FSS_Settings::get('support_captcha_type');
		if (substr($capset, 0, 3) == "ur-")
		{
			if (JFactory::getUser()->id == 0)
			{
				$capset = substr($capset, 3);	
			} else {
				$capset = "";
			}
			FSS_Settings::set('support_captcha_type', $capset);
		}
	}
	
	function saveReply()
	{
		$user = JFactory::getUser();
		$userid = $user->get('id');
		$db = JFactory::getDBO();

		if (!$this->ValidateUser())
			return;
				
		$ticketid = FSS_Input::getInt('ticketid');
		$this->ticketid = $ticketid;

		$subject = FSS_Input::getString('subject');
		$body = FSS_Input::getBBCode('body');
		$replytype = FSS_Input::getCmd('replytype');

		$model = $this->getModel();
		$ticket = $model->getTicket($ticketid);
		
		$this->GetTicketPerms($ticket);
		
		if (!$ticket['can_edit'])
			return $this->noPermission();
		$now = FSS_Helper::CurDate();
		
		$posted = false;
		
		$messageid = -1;
		if ($body)
		{
			$qry = "INSERT INTO #__fss_ticket_messages (ticket_ticket_id, subject, body, user_id, posted) VALUES ('";
			$qry .= FSSJ3Helper::getEscaped($db, $ticketid) . "','".FSSJ3Helper::getEscaped($db, $subject)."','".FSSJ3Helper::getEscaped($db, $body)."','".FSSJ3Helper::getEscaped($db, $userid)."','{$now}')";
			$db->setQuery($qry);$db->Query();
			$messageid = $db->insertid();
			//echo $qry."<br>";
			$posted = true;
		}
		
		$t = new SupportTicket();
		$t->load($this->ticketid, true);
		$files = $t->addFilesFromPost($messageid, $userid);		
		$t->stripImagesFromMessage($messageid);		
			
		if ($posted)
		{
			$should_close = FSS_Input::getInt('should_close');
			
			$def_user = FSS_Ticket_Helper::GetStatusID('def_user');
			
			
			if ($should_close && FSS_Settings::get('support_user_can_close') && FSS_Settings::get('support_user_show_close_reply')) // if we have requested a close of the ticket, set the status to the default closed instead of default reply
			{
				$def_user = FSS_Ticket_Helper::GetStatusID('def_closed');
			}
			
			if ($def_user > 0)
			{
				$qry = "UPDATE #__fss_ticket_ticket SET ticket_status_id = '$def_user', closed = NULL WHERE id = '".FSSJ3Helper::getEscaped($db, $ticketid)."'";
			} else {
				$qry = "UPDATE #__fss_ticket_ticket SET closed = NULL WHERE id = '".FSSJ3Helper::getEscaped($db, $ticketid)."'";
			}
			
			$db->setQuery($qry);
			$db->Query();
			
			if ($def_user > 0)
			{
				$oldstatus = $model->GetStatus($ticket['ticket_status_id']);
				$newstatus = $model->GetStatus($def_user);
				$this->AddTicketAuditNote($ticket['id'],"Status changed from '" . $oldstatus['title'] . "' to '" . $newstatus['title'] . "'");
			}		
		
			$qry = "UPDATE #__fss_ticket_ticket SET lastupdate = '{$now}' WHERE id = '".FSSJ3Helper::getEscaped($db, $ticketid)."'";
			$db->setQuery($qry);$db->Query(); 
			//echo $qry."<br>";
			
			$model = $this->getModel(); 
			$this->ticket = $model->getTicket($this->ticketid);
		
			$t = new SupportTicket();
			$t->load($this->ticketid, true);
			
			$subject = FSS_Input::getString('subject');
			$body = FSS_Input::getBBCode('body');
			
			$action_name = "User_Reply";
			$action_params = array('subject' => $subject, 'user_message' => $body, 'files' => $files);
			SupportActions::DoAction($action_name, $t, $action_params);
		}
			
		if ($replytype == "full")
		{
			// forward with what=
			$mainframe = JFactory::getApplication();
			$link = FSSRoute::_('&what=',false);// FIX LINK
			$mainframe->redirect($link);
			return;
		}
		ob_clean();
		
		// need to display the messages for the ticket
		$this->GetTicket();
		
		include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_messages.php');
		echo "<script>\n window.parent.location = window.parent.location;\n </script>";
		exit;
	}
	
	function showMessages()
	{
		$this->GetTicket();	
		include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_messages.php');
		exit;
	}
	
	function cleanAdminCreate()
	{	
		// remove any admin open stuff
		$session = JFactory::getSession();
		$session->clear('admin_create');
		$session->clear('admin_create_user_id');
	}
	
	function saveTicket()
	{
		$subject = FSS_Input::getString('subject');
		$body = FSS_Input::getBBCode('body');
		$prodid = FSS_Input::getInt('prodid');
		$deptid = FSS_Input::getInt('deptid');
		$catid = FSS_Input::getInt('catid');
		$priid = FSS_Input::getInt('priid');
		$handler = FSS_Input::getInt('handler');
		$user = JFactory::getUser();
		$userid = $user->get('id');
		$name = "";
		
		$session = JFactory::getSession();
		$this->admin_create = 0;
		
		if ($session->Get('admin_create'))
			$this->admin_create = $session->Get('admin_create');
		
		if ($this->admin_create == 1)
		{
			$this->admin_create = 1;
			$userid = $session->Get('admin_create_user_id');
		} else if ($this->admin_create == 2)
		{
			$userid = 0;
		}
			
		$db = JFactory::getDBO();
		
		if ($priid < 1)
			$priid = FSS_Settings::get('support_default_priority');

		$ticket['subject'] = $subject;
		$ticket['body'] = $body;
		$ticket['priid'] = $priid;
		$ticket['handler'] = $handler;
	
		$ok = true;
		$errors['subject'] = '';
		$errors['body'] = '';
		$errors['cat'] = '';
		$errors['captcha'] = '';
		$fields = FSSCF::GetCustomFields(0,$prodid,$deptid);
		
		if (FSS_Settings::get('support_subject_message_hide') == "subject")
		{
			$ticket['subject'] = substr(strip_tags($ticket['body']), 0, 40);
			$subject = $ticket['subject'];
		}
		
		if (FSS_Settings::get('support_altcat'))
		{
			$cats = $this->get('Cats');
			
			if (count($cats) > 0 && FSS_Input::getInt('catid') < 1)
			{
				$errors['cat'] = JText::_("YOU_MUST_SELECT_A_CATEGORY");	
				$ok = false;
			}
		}
		
		if ($body == "" && FSS_Settings::get('support_subject_message_hide') != "message" && FSS_Settings::get('support_subject_message_hide') != "both")
		{
			$errors['body'] = JText::_("YOU_MUST_ENTER_A_MESSAGE_FOR_YOUR_SUPPORT_TICKET");	
			$ok = false;
		}
		
		if (!FSSCF::ValidateFields($fields,$errors))
		{
			$ok = false;	
		}
		
		$email = "";
		$password = "";
		$now = FSS_Helper::CurDate();
	
		$this->sortCaptchaType();

		$captcha = new FSS_Captcha();
		if (!$captcha->ValidateCaptcha('support_captcha_type'))
		{
			$errors['captcha'] = JText::_("INVALID_SECURITY_CODE");
			$ok = false;	
		}
		
		if ($userid < 1)
		{	
			$email = FSSJ3Helper::getEscaped($db, $session->Get('ticket_email'));
			if ($session->Get('ticket_name'))
				$name = FSSJ3Helper::getEscaped($db, $session->Get('ticket_name'));

			if ($email == "" && $this->admin_create != 2)
			{
				$ok = false;
			} else {
				$password = FSS_Helper::createRandomPassword();	
				$session->Set('ticket_pass', $password);
			}
		}

		// assign handler to ticket
		$admin_id = $handler;
		if (!$admin_id) $admin_id = FSS_Ticket_Helper::AssignHandler($prodid, $deptid, $catid);

		$now = FSS_Helper::CurDate();
		$def_open = FSS_Ticket_Helper::GetStatusID('def_open');

		if (FSS_Settings::get('support_subject_format') != "")
		{
			$parser = new FSSParser();
			foreach ($_POST as $var => $value)
			{
				$parser->setVar($var, FSS_Input::GetString($var));
			}

			foreach ($fields as $field)
			{
				$parser->setVar('custom_' . $field['alias'], FSS_Input::GetString('custom_' . $field['id']));
			}
			

			$user = JFactory::getUser($userid);
			$parser->setVar('userid', $userid);
			$parser->setVar('username', $user->username);
			$parser->setVar('name', $user->name);
			$parser->setVar('email', $user->email);
			$parser->setVar('subject', $subject);

			$parser->SetVar('product', FSS_EMail::GetProduct($prodid));
			$parser->SetVar('department', FSS_EMail::GetDepartment($deptid));
			$parser->SetVar('category', FSS_EMail::Getcategory($catid));
			$parser->SetVar('date', FSS_Helper::Date($now, FSS_DATE_SHORT));
			$parser->SetVar('time', FSS_Helper::Date($now, FSS_DATETIME_SHORT));

			$parser->SetTemplate(FSS_Settings::get('support_subject_format'));

			$result = $parser->Parse();

			if (trim($result) == "")
			{
				$sj = array();
				foreach ($fields as $field)
					$sj[] = FSS_Input::GetString('custom_' . $field['id']);

				$result = implode(", ", $sj);
			}

			$ticket['subject'] = $result;
			$subject = $ticket['subject'];

			if ($subject == "")
			{
				$errors['subject'] = JText::_("YOU_MUST_ENTER_A_SUBJECT_FOR_YOUR_SUPPORT_TICKET");	
				$ok = false;
			}
		}

		if ($ok)
		{		
			
			$qry = "INSERT INTO #__fss_ticket_ticket (reference, ticket_status_id, ticket_pri_id, ticket_cat_id, ticket_dept_id, prod_id, title, opened, lastupdate, user_id, admin_id, email, password, unregname, lang) VALUES ";
			$qry .= "('', $def_open, '".FSSJ3Helper::getEscaped($db, $priid)."', '".FSSJ3Helper::getEscaped($db, $catid)."', '".FSSJ3Helper::getEscaped($db, $deptid)."', '".FSSJ3Helper::getEscaped($db, $prodid)."', '".FSSJ3Helper::getEscaped($db, $subject)."', '{$now}', '{$now}', '".FSSJ3Helper::getEscaped($db, $userid)."', '".FSSJ3Helper::getEscaped($db, $admin_id)."', '{$email}', '".FSSJ3Helper::getEscaped($db, $password)."', '{$name}', '".JFactory::getLanguage()->getTag()."')";
			
			$db->setQuery($qry);$db->Query();
			$this->ticketid = $db->insertid();
			
			$ref = FSS_Ticket_Helper::createRef($this->ticketid);

			$session->Set('ticket_reference', $ref);


			$qry = "UPDATE #__fss_ticket_ticket SET reference = '".FSSJ3Helper::getEscaped($db, $ref)."' WHERE id = '" . FSSJ3Helper::getEscaped($db, $this->ticketid) . "'";  
			$db->setQuery($qry);$db->Query();

			if ($this->admin_create)
			{
				$curuser = JFactory::getUser();
				
				$premsg = date("Y-m-d H:i:s", strtotime($now) - 1);
				
				$msg = JText::sprintf('TICKET_OPENED_BY', $curuser->name, $curuser->username);
				$qry = "INSERT INTO #__fss_ticket_messages (ticket_ticket_id, subject, body, user_id, posted, admin) VALUES ('";
				$qry .= FSSJ3Helper::getEscaped($db, $this->ticketid) . "','".FSSJ3Helper::getEscaped($db, $subject)."','".FSSJ3Helper::getEscaped($db, $msg)."','".FSSJ3Helper::getEscaped($db, $curuser->id)."','{$premsg}', 6)";
				$db->setQuery($qry);$db->Query();
			}

			$qry = "INSERT INTO #__fss_ticket_messages (ticket_ticket_id, subject, body, user_id, posted) VALUES ('";
			$qry .= FSSJ3Helper::getEscaped($db, $this->ticketid) . "','".FSSJ3Helper::getEscaped($db, $subject)."','".FSSJ3Helper::getEscaped($db, $body)."','".FSSJ3Helper::getEscaped($db, $userid)."','{$now}')";
			
			$db->setQuery($qry);$db->Query();
			$messageid = $db->insertid();
				
			FSSCF::StoreFields($fields,$this->ticketid);
	
			// store tags if there are any posted
			$tags_input = FSS_Input::getString('tags');
			$parts = explode("|", $tags_input);
			foreach ($parts as $part)
			{
				$tag = trim($part);
				if (!$tag || $tag == "") continue;
				
				$tags[] = $tag;	
				
				$qry = "INSERT INTO #__fss_ticket_tags (ticket_id, tag) VALUES ('" . $this->ticketid . "', '" . $db->escape($tag) . "')";
				$db->setQuery($qry);$db->Query();
			}
			
			$t = new SupportTicket();
			$t->load($this->ticketid, true);
			$files = $t->addFilesFromPost($messageid, $userid);
			$t->stripImagesFromMessage($messageid);
			
			$subject = FSS_Input::getString('subject');
			$body = FSS_Input::getBBCode('body');
			
			$action_name = "User_Open";
			$action_params = array('subject' => $subject, 'user_message' => $body, 'files' => $files);
			SupportActions::DoAction($action_name, $t, $action_params);
			
			// additional users and emails if posted
			if ($this->admin_create > 0)
			{
				$additionalusers = JRequest::getVar('additionalusers');
				$additionalusers = explode(",", $additionalusers);
				$t->addCC($additionalusers, 0, 0);
				
				$additionalemails = JRequest::getVar('additionalemails');
				$additionalemails = explode(",", $additionalemails);
				foreach ($additionalemails as $email)
				{
					$email = trim($email);
					if ($email == "") continue;
					
					$t->addEMailCC($email);
				}

				if ($t->admin_id != JFactory::getUser()->id)
				{
					$t->addCC(JFactory::getUser()->id, 1, 0);
				}
			}		
			
			$this->cleanAdminCreate();

			// if related is passed as part of ticket open, relate the 2 tickets
			$related = JRequest::getVar('related');
			if ($related > 0)
			{
				$t->addRelated($related);
			}
		}
			
		$this->errors = $errors;
		$this->ticket = $ticket;

		return $ok;
	}

	function GetTicket()
	{	
		$mainframe = JFactory::getApplication();
		$this->setLayout("view");
		$model = $this->getModel();
		  
		$user = JFactory::getUser();
		$userid = $user->get('id');
		$this->assign('userid',$userid);
		$db = JFactory::getDBO();
	
		if (!$this->ValidateUser())
		{
			return;
		}
				
		// get ticketid if we can	
		if (!$this->ticket)
		{
			$ticketid = FSS_Input::getInt('ticketid');
		} else {
			$ticketid = $this->ticketid;
		}
		
		// no ticket so display a list
		if ($ticketid < 1)
		{
			$tickets = FSS_Input::getCmd('tickets','open');
			$this->ticket_view = $tickets;

			$this->listTickets();	
			return false;
		}		
				
		// display ticket
		$this->ticket = $model->getTicket($ticketid);
		$this->getCCInfo();
		
		$uids = $model->getUIDS($userid);

		$session = JFactory::getSession();

		if ($session->Get('ticket_pass') && $this->ticket['password'] == $session->Get('ticket_pass'))
		{
			// ok, we have a password
		} elseif (!array_key_exists($this->ticket['user_id'], $uids))
		{
			//print_p($this->ticket['user_id']);
			//print_p($uids);
			
			// doesnt have permission to view, check cc list
			if (!array_key_exists("cc",$this->ticket))
			{
				//print_p($this->ticket);
				return $this->noPermission();
			}
				
			$found = false;
			foreach ($this->ticket['cc'] as &$user)
			{
				if ($user['id'] == $userid)
					$found = true;		
			}
			if (!$found)
			{
				return $this->noPermission();
			}
				
			$model->multiuser = 1;
		}
				
		$this->messages = $model->getMessages($ticketid);	
		$this->attach = $model->getAttach($ticketid);		
		

		foreach($this->attach as &$attach)
		{
			$message_id = $attach['message_id'];
			foreach($this->messages as &$message)
			{
				if ($message['id'] == $message_id)
				{
					if (!array_key_exists('attach', $message))
						$message['attach'] = array();
						
					$message['attach'][] = $attach;		
					
					if ($attach['name'] == "")
						$attach['name'] = $message['name'];
				}	
			}
			
			if ($attach['name'] == "")
			{
				if ($this->ticket['name'])
				{
					$attach['name'] = $this->ticket['name'];
				} else {
					$attach['name'] = $this->ticket['unregname'];
				}
			}
		}

		$this->pris = $this->get('Priorities');
		$this->statuss = $this->get('Statuss');
			
		$this->multiuser = $model->multiuser;
		if ($this->multiuser)
			$this->user = $model->getUser($this->ticket['user_id']);

		$pathway = $mainframe->getPathway();
		$pathway->addItem(JText::_("TICKET")." : " . $this->ticket['reference'] . " - " . $this->ticket['title']);
		
		$this->fields = FSSCF::GetCustomFields($ticketid,$this->ticket['prod_id'],$this->ticket['ticket_dept_id']);
		$this->fieldvalues = FSSCF::GetTicketValues($ticketid, $this->ticket);
		
		$this->support_user_attach = FSS_Settings::get('support_user_attach');
		$errors['subject'] = '';
		$errors['body'] = '';
		$errors['cat'] = '';

		$this->GetTicketPerms($this->ticket);

		$this->errors = $errors;
	
		$this->ticket_obj = new SupportTicket();
		$this->ticket_obj->load($ticketid, true);
		$this->ticket_obj->loadAll();

		return true;
	}
	
	function doDisplayTicket()
	{
		if (!FSS_Permission::auth("fss.ticket.view", "com_fss.support_user"))
			return FSS_Helper::NoPerm();	


		if (!$this->GetTicket())
			return;

		$this->readonly = false;

		SupportSource::doUser_View_Redirect($this->ticket);

		$this->redirectMergedTickets();

		$this->getCCInfo();
		
		// update lang code on ticket
		$lang = JFactory::getLanguage()->getTag();
		$db = JFactory::getDBO();
		$qry = "UPDATE #__fss_ticket_ticket SET lang = '" . FSSJ3Helper::getEscaped($db, $lang) . "' WHERE id = " . $this->ticket['id'];
		$db->setQuery($qry);
		$db->Query();
		
		$what = FSS_Input::getCmd('what');
		if ($what == "print")
		{
			return parent::display("print");
		}
		
		$this->FixTicketStatus();
		
		FSS_Helper::IncludeModal();

		parent::display();
	}
	
	function redirectMergedTickets()
	{
		if ($this->ticket['merged'] > 0 && JFactory::getSession()->Get('ticket_email') == "")
		{
			// ticket has been merged
			$link = "index.php?option=com_fss&view=ticket&layout=view&ticketid=" . $this->ticket['merged'] . "&Itemid=" . FSS_Input::getInt('Itemid');
			JFactory::getApplication()->redirect(FSSRoute::_($link, false));
		}
		
		$db = JFactory::getDBO();
		$qry = "SELECT * FROM #__fss_ticket_ticket WHERE merged = " . $db->escape($this->ticket['id']);
		$db->setQuery($qry);
		$this->merged = $db->loadObjectList();
	}
	
	function FixTicketStatus()
	{
		$statuss = SupportHelper::getStatuss(false);
		$cur_status = $statuss[$this->ticket['ticket_status_id']];
		if ($cur_status->combine_with > 0)
		{
			$new_status = $statuss[$cur_status->combine_with];
			$this->ticket['scolor'] = $new_status->color;
			$this->ticket['status'] = $new_status->title;
			if ($new_status->userdisp)
				$this->ticket['status'] = $new_status->userdisp;
			$this->ticket['ticket_status_id'] = $new_status->id;
		} else {
			if ($cur_status->userdisp)
				$this->ticket['status'] = $cur_status->userdisp;
		}
	}
	
	function getCCInfo()
	{
		$user = JFactory::getUser();
		$userid = $user->get('id');
		$db = JFactory::getDBO();

		// find if a user is a group member or not
		$qry = "SELECT g.id, g.ccexclude FROM #__fss_ticket_group_members AS gm LEFT JOIN #__fss_ticket_group AS g ON gm.group_id = g.id WHERE user_id = ".FSSJ3Helper::getEscaped($db, $userid);
		$db->setQuery($qry);
		$rows = $db->loadObjectList();

		$this->show_cc = 0;
		
		foreach ($rows as $row)
		{
			if ($row->ccexclude == 0)
				$this->show_cc = 1;
		}
		
		//if ($this->show_cc)
		{
			// can we only cc our own ticket? NO!
			
			// get current cc list
			$qry = "SELECT u.name, u.id, c.isadmin, c.readonly, c.email FROM #__fss_ticket_cc as c LEFT JOIN #__users as u ON c.user_id = u.id WHERE c.ticket_id = '{$this->ticket['id']}' AND isadmin = 0 ORDER BY name";
			$db->setQuery($qry);
			$this->ticket['cc'] = $db->loadAssocList();
			
			$userid = JFactory::getUser()->id ;
			
			if (is_array($this->ticket['cc']))
			{
				foreach ($this->ticket['cc'] as $row)
				{
					$this->show_cc = 1;
					if ($userid == $row['id'] && $row['readonly'])
						$this->readonly = true;
				}
			}
		}
	}
	
	// validate userid > 0 or valid ticket email and pass. Set ticket id in request to found id
	function ValidateUser($view = 'view')
	{

		$this->setLayout($view);
		
		$user = JFactory::getUser();
		$userid = $user->get('id');
		
		if ($userid > 0)
			return true;
	
		// use email for non registered ticket
		$session = JFactory::getSession();
		$sessionemail = "";
		$reference = "";

		if ($session->Get('ticket_email')) $sessionemail = $session->Get('ticket_email');	
		if ($session->Get('ticket_reference')) $reference = $session->Get('ticket_reference');
		
		$email = FSS_Input::getEMail('email',$sessionemail);
		$reference = FSS_Input::getEMail('reference',$reference);
		$session->Set('ticket_email', $email);
		$session->Set('ticket_reference', $reference);

		if ($email == "" && $reference == "")
		{
			$this->needLogin();
			return false;
		}
		
		$this->email = $email;
	
		if ($this->getLayout() == "open")
		{
			if (!$this->isValidEmail($email))
			{
				$this->needLogin(3);
				return false;	
			}
			
			if ($this->DupeEmail($email))
			{
				$this->needLogin(1);
				return false;
			}
		} else {	
		
			if (in_array(FSS_Settings::get('support_unreg_type'), array(1, 2)))
			{
				$need_pass = (FSS_Settings::get('support_unreg_type') == 1);
				
				if ($need_pass)
				{
					$sessionpass = "";
					if ($session->Get('ticket_pass')) $sessionpass = $session->Get('ticket_pass');

					$password = FSS_Input::getString('password',$sessionpass);
					$session->Set('ticket_pass', $password);
				}

				$db = JFactory::getDBO();
				
				$qry = "SELECT id FROM #__fss_ticket_ticket WHERE reference = '" . $db->escape($reference) . "'";
				if ($need_pass)
					$qry .= " AND password = '" . $db->escape($password) . "'";

				$db->setQuery($qry);
				$row = $db->loadAssoc();
				
				if ($row)
				{
					JRequest::setVar('ticketid',$row['id']);
				} else {
					$this->needLogin(2);
					return false;
				}

			} else {
				if ($email == "")
				{
					$this->needLogin(2);
					return false;
				}

				// validate ticket password and find ticket id!
				$sessionpass = "";
				if ($session->Get('ticket_pass')) $sessionpass = $session->Get('ticket_pass');

				$password = FSS_Input::getString('password',$sessionpass);
				$session->Set('ticket_pass', $password);
				
				$db = JFactory::getDBO();
				
				$qry = "SELECT id FROM #__fss_ticket_ticket WHERE email = '".FSSJ3Helper::getEscaped($db, $email)."' AND password = '".FSSJ3Helper::getEscaped($db, $password)."'";
				//echo $qry."<br>";
				$db->setQuery($qry);
				$row = $db->loadAssoc();
				
				if ($row)
				{
					JRequest::setVar('ticketid',$row['id']);
				} else {
					$this->needLogin(2);
					return false;
				}
			}
		}
		
		return true;	
	}
	
	function isValidEmail($email){
		if (!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)*.([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/" , $email)) {
			return false;
		}else{
			$record = 'MX';
			list($user,$domain) = explode('@',$email);
			if(!checkdnsrr($domain,$record)){
				return false;
			}else{
				return true;
			}
		}
	}	
	
	function listTickets()
	{
		$user = JFactory::getUser();
		$userid = $user->get('id');
		
		// validate that we have a user id before listing tickets
		if (!$this->ValidateUser())
			return;
					
		$this->ClaimTickets();
			
		$stuff = $this->get('Tickets');
		$model = $this->getModel();
		
		$this->tickets = $stuff['tickets'];
				
		$this->pagination = $stuff['pagination'];

		$this->multiuser = $model->multiuser;
		$this->customfields = FSSCF::GetAllCustomFields();
		
		parent::display("list");	
	}
	
	function ClaimTickets()
	{
		$user = JFactory::getUser();
		//print_p($user);
		
		if ($user->email != "" && $user->get('id') > 0)
		{
			$db = JFactory::getDBO();
			$qry = "UPDATE #__fss_ticket_ticket SET user_id = " . $user->get('id') . " WHERE email = '" . FSSJ3Helper::getEscaped($db, $user->email) . "'";
			$db->setQuery($qry);
			$db->Query();
		}
		//echo $qry;
	}	
	
	// type 0 = normal
	// type 1 = dupe email
	// type 2 = no ticket
	function needLogin($type = 0)
	{
		$session = JFactory::getSession();
		$session->clear('ticket_pass');
		$session->clear('ticket_email');
		$session->clear('ticket_reference');

		//echo "needLogin : Current Layout : " . $this->getLayout() . "<br>";
		if (array_key_exists('REQUEST_URI',$_SERVER))
		{
			$url = $_SERVER['REQUEST_URI'];//JURI::current() . "?" . $_SERVER['QUERY_STRING'];
		} else {
			$option = FSS_Input::getCmd('option');
			$view = FSS_Input::getCmd('view');
			$layout = FSS_Input::getCmd('layout');
			$Itemid = FSS_Input::getInt('Itemid');
			$url = FSSRoute::_("index.php?option=" . $option . "&view=" . $view . "&layout=" . $layout . "&Itemid=" . $Itemid); 	
		}

		$url = str_replace("&what=find","",$url);
		$url = base64_encode($url);

		$this->assign('type',$type);		
		$this->return = $url;
		parent::display("login");	    
	}
	
	function noPermission($pagetitle = "INVALID_TICKET", $message = "YOU_ARE_TYING_TO_EITHER_ACCESS_AN_INVALID_TICKET_OR_DO_NOT_HAVE_PERMISSION_TO_VIEW_THIS_TICKET")
	{
		//echo dumpStack();
		
		$this->no_permission_title = $pagetitle;
		$this->no_permission_message = $message;
		
		$this->setLayout("nopermission");
		//print_r($this->ticket);
		parent::display();	    
	}
	
	function findTicket()
	{
		$this->setLayout("view");
		//echo "findTicket : Current Layout : " . $this->getLayout() . "<br>";
		/*$url = base64_encode($_SERVER['REQUEST_URI']);//JURI::current() . "?" . $_SERVER['QUERY_STRING'];
		$this->assign('type',3);		
		*/
		$session = JFactory::getSession();
		$session->clear('ticket_email');
		$session->clear('ticket_name');
		$session->clear('ticket_pass');
		$session->clear('ticket_reference');

		$mainframe = JFactory::getApplication();
		$link = FSSRoute::_('index.php?option=com_fss&view=ticket',false);
		$mainframe->redirect($link);
	}
	
	function DupeEmail($email)
	{
		if (FSS_Settings::get('support_dont_check_dupe'))
			return false;
			
		$db = JFactory::getDBO();
		$query = 'SELECT * FROM #__users WHERE email = "' . FSSJ3Helper::getEscaped($db, $email) . '"';
		$db->setQuery($query);
		$row = $db->loadAssoc();
		
		if ($row)
		{
			if (array_key_exists('block', $row) && $row['block'] > 0)
				return false;
			
			return true;
		}
		
		return false;		
	}
	
	function EditField()
	{
		if (!$this->ValidateUser())
			return;
			
		$ticketid = FSS_Input::getInt('ticketid');
		$model = $this->getModel();
		$this->ticket = $model->getTicket($ticketid);
		
		$this->GetTicketPerms($this->ticket);
		
		if (!$this->ticket['can_edit'])
			return;
		
		
		
		$this->fields = FSSCF::GetCustomFields($ticketid,$this->ticket['prod_id'],$this->ticket['ticket_dept_id']);
		$this->fieldvalues = FSSCF::GetTicketValues($ticketid, $this->ticket);

		$fieldid = FSS_Input::getInt('editfield',0,'','int');
		
		$this->assign('field','');
		$this->assign('fieldvalue','');
		$errors = array();
		$this->errors = $errors;
		
		foreach($this->fields as &$field)
		{
			if ($field['id'] == $fieldid)
				$this->field = $field;
		}
		
		if (!$this->CanEditField($this->field))
			return;

		foreach($this->fieldvalues as &$fieldvalue)
		{
			if ($fieldvalue['field_id'] == $fieldid)
			{
				JRequest::setVar('custom_' . $fieldid,$fieldvalue['value']);
			}
		}

		$this->assign('fieldid',$fieldid);

		parent::display("editfield");	    
	}
	
	function SaveField()
	{
		if (!$this->ValidateUser())
			return $this->noPermission();
			
		$ticketid = FSS_Input::getInt('ticketid');
		$savefield = FSS_Input::getInt('savefield');
		$model = $this->getModel();
		$ticket = $model->getTicket($ticketid);	
		$user = JFactory::getUser();
		$userid = $user->get('id');
		$uids = $model->getUIDS($userid);
		//print_r($uids);
			
		if (!array_key_exists($ticket['user_id'], $uids))
		{
			$this->ticket =$ticket;
			$this->getCCInfo();
			// doesnt have permission to view, check cc list
			if (!array_key_exists("cc",$ticket))
				return $this->noPermission();
				
			$found = false;
			foreach ($ticket['cc'] as &$user)
			{
				if ($user['id'] == $userid)
					$found = true;		
			}
			if (!$found)
				return $this->noPermission();
				
		}
		
		$this->GetTicketPerms($ticket);
		
		if (!$ticket['can_edit'])
			return $this->noPermission();

		$this->fields = FSSCF::GetCustomFields($ticketid,$ticket['prod_id'],$ticket['ticket_dept_id']);
		list($old, $new) = FSSCF::StoreField($savefield, $ticketid, $ticket);
			
		if ($old != $new)
		{
			$field = FSSCF::GetField($savefield);
			if ($this->CanEditField($field))
			{
				if ($field->type == 'checkbox')
				{
					if ($old == "") $old = "No";
					if ($old == "on") $old = "Yes";	
					if ($new == "") $new = "No";
					if ($new == "on") $new = "Yes";	
				}
				$this->AddTicketAuditNote($ticketid,"Custom field '" . $field->description . "' changed from '" . $old . "' to '" . $new . "'");
			}
		}
		//FSSCF::StoreFields($this->fields, $ticketid);	
		// forward with what=
		/*$mainframe = JFactory::getApplication();
		$link = FSSRoute::_('&what=new_status=new_pri=',false);
		$mainframe->redirect($link);
		*/
		echo "<script>parent.window.location.reload();</script>";
		exit;
		return true;				
	}

	function AddTicketAuditNote($ticketid,$note)
	{
		if ($ticketid < 1)
		{
			echo "ERROR: AddTicketAuditNote called with no ticket id ($note)<br>";
			exit;	
		}
		$db = JFactory::getDBO();
		$now = FSS_Helper::CurDate();
		$qry = "INSERT INTO #__fss_ticket_messages (ticket_ticket_id, subject, body, user_id, admin, posted) VALUES ('";
		$qry .= FSSJ3Helper::getEscaped($db, $ticketid) . "','Audit Message','".FSSJ3Helper::getEscaped($db, $note)."','".FSSJ3Helper::getEscaped($db, $this->userid)."',3, '{$now}')";
			
		$db->SetQuery( $qry );
		//echo $qry. "<br>";
		$db->Query();
		//echo "Audit: $ticketid - $note<br>";	
	}
	
	function GetTicketPerms(&$ticket)
	{
		$db = JFactory::getDBO();

		$ticketid = $ticket['id'];
		$owner = $ticket['user_id'];
		
		$user = JFactory::getUser();
		$userid = $user->get('id');

		$ticket['can_edit'] = 0;
		$ticket['can_close'] = 0;		

		if ($userid == $owner)
		{		
			$ticket['can_edit'] = 1;
			$ticket['can_close'] = 1;		
			return;
		}

		$session = JFactory::getSession();

		if ($ticket['email'] != "" && $ticket['email'] == $session->Get('ticket_email'))
		{
			$ticket['can_edit'] = 1;
			$ticket['can_close'] = 1;		
			return;
		}

		// not the ticket owner
		
		// check if on cc list, if so then have level 2 permissions
		$qry = "SELECT user_id FROM #__fss_ticket_cc WHERE ticket_id = '".FSSJ3Helper::getEscaped($db, $ticketid)."' AND user_id = '$userid' AND isadmin = 0";
		$db->setQuery($qry);
		$row = $db->loadObjectList();
		if (count($row) > 0)
			$ticket['can_edit'] = 1;
		
		// find a list of groups the owner belongs to
		$qry = "SELECT group_id FROM #__fss_ticket_group_members WHERE user_id = '".FSSJ3Helper::getEscaped($db, $ticket['user_id'])."'";
		$db->setQuery($qry);
		$owner_groups = $db->loadObjectList('group_id');
		
		// find a list of groups the user belongs to
		$qry = "SELECT * FROM #__fss_ticket_group_members WHERE user_id = '".FSSJ3Helper::getEscaped($db, $userid)."'";
		$db->setQuery($qry);
		$user_groups = $db->loadObjectList('group_id');
		
		// find common groups
		$groups = array();
		$gids = array();
		foreach ($user_groups as $group_id => $group)
		{
			if (array_key_exists($group_id, $owner_groups))
			{
				$groups[] =$group;	
				$gids[$group_id] = $group_id;
			}
		}

		if (count($gids) == 0)
			return;
			
		// for each of the common groups, check if the users permissions for em and elevate if available
		$qry = "SELECT * FROM #__fss_ticket_group WHERE id IN (" . implode(", ", $gids) . ")";
		$db->setQuery($qry);
		
		$groups = $db->loadObjectList('id');
		
		if (count($groups) == 0)
			return; 
			
		foreach($groups as $group_id => $group)
		{
			$perm = $user_groups[$group_id]->allsee;
			if ($perm == 0)
				$perm = $group->allsee;	
				
			if ($perm > 1)
				$ticket['can_edit'] = 1;
			if ($perm > 2)
				$ticket['can_close'] = 1;
		}
		
		return;
	}
	
	function CanEditField($field)
	{
		if (is_array($field) && $field['type'] == "plugin")
		{
			$aparams = FSSCF::GetValues($field);
			$plugin = FSSCF::get_plugin($aparams['plugin']);
			if (!$plugin->CanEdit())
				return false;
		}
		
		$peruser = "";
		if (is_array($field))
		{
			$peruser = $field['peruser'];			
		} else {
			$peruser = $field->peruser;
		}
		
		if ($peruser == 1)
		{
			$owner = $this->ticket['user_id'];
		
			$user = JFactory::getUser();
			$userid = $user->get('id');
			if ($owner == $userid)
				return true;
		} else {
			if ($this->ticket['can_edit'])
				return true;	
		}

		return false;
	}
	
	function PickCCUser()
	{
		$db	= JFactory::getDBO();
		// build query
		
		// get list of possible user ids
		$user = JFactory::getUser();
		$userid = $user->get('id');
	
		$qry = "SELECT g.id, g.ccexclude FROM #__fss_ticket_group_members AS gm LEFT JOIN #__fss_ticket_group AS g ON gm.group_id = g.id WHERE user_id = ".FSSJ3Helper::getEscaped($db, $userid);

		$db->setQuery($qry);
		$gids = array();
		$rows = $db->loadObjectList();
		foreach($rows as $row)
		{
			if ($row->ccexclude == 0)
				$gids[$row->id] = $row->id;		
		}
		
		if (count($gids) == 0)
			return;
	
		$qry = "SELECT user_id FROM #__fss_ticket_group_members WHERE group_id IN (" . implode(", ",$gids) . ")";
		$db->setquery($qry);
		$user_ids = $db->loadObjectList('user_id');
		
		$uids = array();
		foreach($user_ids as $uid => &$group)
			$uids[$uid] = $uid;
		
		unset($uids[$userid]);
		
		$ticketid = FSS_Input::getInt('ticketid');
		$this->GetTicket();
		$this->getCCInfo();
		
		if (array_key_exists("cc",$this->ticket))
		{
			foreach ($this->ticket['cc'] as $ccuser)
			{
				$userid = $ccuser['id'];
				unset($uids[$userid]);		
			}	
		}
	
		$qry = "SELECT * FROM #__users ";
		$where = array();
		
		$limitstart = FSS_Input::getInt('limitstart');
		$mainframe = JFactory::getApplication();
		$limit = $mainframe->getUserStateFromRequest('users.limit', 'limit', 10, 'int');
		$search = FSS_Input::getString('search');
		
		
		if ($search != "")
		{
			$where[] = "(username LIKE '%".FSSJ3Helper::getEscaped($db, $search)."%' OR name LIKE '%".FSSJ3Helper::getEscaped($db, $search)."%' OR email LIKE '%".FSSJ3Helper::getEscaped($db, $search)."%')";
		}
			
		
		if (count($uids) > 0)
		{
			$where[] = "id IN (" . implode(", ", $uids) . ")";
		} else {
			$where[] = "id = 0";		
		}
				
		if (count($where) > 0)
		{
			$qry .= " WHERE " . implode(" AND ", $where);	
		}

		
		// Sort ordering
		$qry .= " ORDER BY name ";
		
		
		// get max items
		
		$db->setQuery( $qry );
		$db->query();
		$maxitems = $db->getNumRows();
			
		
		//echo $qry . "<br>";
		// select picked items
		$db->setQuery( $qry, $limitstart, $limit );
		$this->users = $db->loadObjectList();

		
		// build pagination
		$this->pagination = new JPaginationEx($maxitems, $limitstart, $limit );
		$this->search = $search;
		
		parent::display("users");		
	}
	
	function AddCCUser()
	{
		$db	= JFactory::getDBO();
		$ticketid = FSS_Input::getInt('ticketid');
		$userid = FSS_Input::getInt('userid');
		$readonly = FSS_Input::getInt('readonly');
		
		$this->GetTicket();
		
		$this->GetTicketPerms($this->ticket);
		if ($this->ticket['can_edit'])
		{
			$qry = "REPLACE INTO #__fss_ticket_cc (ticket_id, user_id, isadmin, readonly) VALUES ('".$db->escape((int)$ticketid)."','".$db->escape((int)$userid)."',0, " . $db->escape($readonly) . ")";
			$db->setQuery($qry);
			$db->Query();
		}
		
		$this->getCCInfo();
		
		include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_ccusers.php');
		exit;
	}	
	
	function RemoveCCUser()
	{
		$db	= JFactory::getDBO();
		$ticketid = FSS_Input::getInt('ticketid');
		$userid = FSS_Input::getInt('userid');
		
		$this->GetTicket();
		$this->GetTicketPerms($this->ticket);
		
		if ($this->ticket['can_edit'])
		{
			$qry = "DELETE FROM #__fss_ticket_cc WHERE ticket_id = '".FSSJ3Helper::getEscaped($db, $ticketid)."' AND user_id = '".FSSJ3Helper::getEscaped($db, $userid)."' AND isadmin = 0";
			$db->setQuery($qry);
			$db->Query();
		}
		
		$this->getCCInfo();
		
		include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_ccusers.php');
		exit;
	}

	function getCustomFields()
	{
		$this->customfields = FSSCF::GetAllCustomFields(true);
		
		if (count($this->tickets) == 0)
			return;

		$db = JFactory::getDBO();
		
		$ids = array();
		foreach($this->tickets as &$ticket)
		{
			$ids[] = FSSJ3Helper::getEscaped($db, (int)$ticket->id);
		}
		
		$qry = "SELECT * FROM #__fss_ticket_field WHERE ticket_id IN (" . implode(" , ", $ids) . ")";
		$db->setQuery($qry);
		$rows = $db->loadAssocList();

		foreach ($rows as $row)
		{
			if (array_key_exists($row['ticket_id'],$this->tickets))
			{
				$this->tickets[$row['ticket_id']]->custom[$row['field_id']] = $row['value'];	
			}
		}
	}
	
	function getDBTime()
	{
		$this->db_time = strtotime(FSS_Helper::CurDate());		
	}	
	
	function OutputHeader()
	{
		if (empty($this->parser))
			$this->parser = new FSSParser();

		if (empty($this->db_time))
			$this->getDBTime();

		$this->getCustomFields();

		/*if ($this->layoutpreview)
		{
			$this->parser->Load('preview',1);
		} else {*/
		$this->parser->Load(FSS_Settings::get('support_user_template'),1);
		//}
		
		$this->parser->multiuser = $this->multiuser;
		/*$this->parser->ticket_view = $this->ticket_view;
		$this->parser->customfields = $this->customfields;*/

		FSSParserTicket::forUser($this->parser,null);	
		//$this->parser->ParserPopulateUserTicket($this->parser,null);

		$this->cst = FSS_Ticket_Helper::GetStatusByID($this->ticket_view);
		if ($this->cst)
		{		
			if ($this->cst->is_closed)
				$this->parser->SetVar('view', 'closed');
			if ($this->cst->def_archive)
				$this->parser->SetVar('view', 'archived');
		}
		echo $this->parser->Parse();
	}

	function OutputRow(&$row)
	{
		if (empty($this->parser))
		{
			$this->parser = new FSSParser();
		}
		
		$row->customfields = $this->customfields;
		
		if (!property_exists($this->parser, "priorities"))
		{
			$this->parser->priorities = $this->get('priorities');
		}

		if (empty($this->db_time))
			$this->getDBTime();

		$this->parser->userid = $this->userid;
		$this->parser->db_time = $this->db_time;
		
		/*if ($this->layoutpreview)
		{
			$this->parser->Load('preview',0);
		} else {*/
		$this->parser->Load(FSS_Settings::get('support_user_template'),0);
		//}
		
		$this->parser->customfields = $this->customfields;
		
		FSSParserTicket::forUser($this->parser,$row);

		if ($this->cst)
		{
			if ($this->cst->is_closed)
				$this->parser->SetVar('view', 'closed');
			if ($this->cst->def_archive)
				$this->parser->SetVar('view', 'archived');
		}

		echo $this->parser->Parse();
	}
	
	function Unreg_Passlist()
	{
		$email = FSS_Input::getEMail('email');
		if ($email == "") $email = FSS_Input::getEMail('reference');
				
		$tickets = new SupportTickets();
		$tickets->limitstart = 0;
		$tickets->limit = 500;
		$tickets->loadTicketsByQuery(array("t.email = '$email'"), "lastupdate DESC");
		
		if ($tickets->ticket_count > 0)
		{	
			FSS_EMail::User_Unreg_Passwords($tickets->tickets);
			
			$link = FSSRoute::_("index.php?option=com_fss&view=ticket", false);	
			JFactory::getApplication()->redirect($link, JText::sprintf("A_LIST_OF_YOUR_TICKETS_AND_PASSWORDS_HAS_BEEN_SENT_TO_YOU",$email));
		} else {
			
			$link = FSSRoute::_("index.php?option=com_fss&view=ticket", false);	
			JFactory::getApplication()->redirect($link, JText::sprintf("UNABLE_TO_FIND_ANY_TICKETS_FOR_EMAIL",$email));
		}
	}
}
