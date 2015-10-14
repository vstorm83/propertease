<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'fields.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'tickethelper.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'support_helper.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'mailer.php');

class FSS_EMail
{
	// any extra variables needed when parsing a tempalte
	static $extra_vars = array();

	/**
	 * Emails to users
	 */	
	// do the actual send to the users
	static function EMail_To_Ticket_User($template_name, &$ticket, $subject = "", $body = "", $files = array())
	{
		$cc_admins = $template_name == "email_on_create" || 
					 $template_name == "email_on_create_unreg" || 
					 $template_name == "email_unreg_passwords" 
					 ? false : true;

		$is_create = $template_name == "email_on_create" || 
					 $template_name == "email_on_create_unreg" 
					 ? true : false;

		$ticket = FSS_Helper::ObjectToArray($ticket);
		
		$db = JFactory::getDBO();

		$mailer = new FSSMailer();

		self::Ticket_To_Users($mailer, $ticket);

		// Add bcc to admins if set up. Dont send when creating a ticket as they get notifications from
		// Admin_Create function
		if ($cc_admins && FSS_Settings::get('support_email_bcc_handler')) 
			self::Ticket_To_Admins($mailer, $ticket);

		// build template and parse it
		$template = self::Get_Template($template_name, $ticket['lang']);	
		$email = self::ParseTemplate($template,$ticket,$subject,$body,$template['ishtml'], true);
		
		// set result to mailer
		$mailer->isHTML($template['ishtml']);
		$mailer->setSubject($email['subject']);
		$mailer->setBody($email['body']);

		// Only send attachments to users when creating if the ticket is created by an admin

		$session = JFactory::getSession();
		if (FSS_Settings::get('support_email_file_user') && (!$is_create || $session->Get('admin_create') > 0)) 
			$mailer->addFiles($files);

		// add debug info
		$mailer->addDebug('Ticket', $ticket);

		// send actual mail
		$mailer->send();
	}

	// Admin has replied to a ticket, send notification to user
	static function Admin_Reply(&$ticket, $subject, $body, $files = array())
	{
		if (self::ShouldSend('email_on_reply') == 1)
			self::EMail_To_Ticket_User('email_on_reply', $ticket, $subject, $body, $files);
	}
	
	// Admin has closed to a ticket, send notification to user
	static function Admin_Close(&$ticket, $subject, $body, $files)
	{
		if (self::ShouldSend('email_on_close') == 1)
			self::EMail_To_Ticket_User('email_on_close', $ticket, $subject, $body, $files);
	}
	
	// Ticket autoclosed, send notification to user
	static function Admin_AutoClose(&$ticket)
	{
		self::EMail_To_Ticket_User('email_on_autoclose', $ticket, $subject, $body, $files);
	}

	// User has created a ticket, send notification to user
	static function User_Create(&$ticket, $subject, $body, $files = array())
	{
		if (self::ShouldSend('email_on_create') == 1)
			self::EMail_To_Ticket_User('email_on_create', $ticket, $subject, $body, $files);
	}

	// Unregistered user has created a ticket, send notification to user
	static function User_Create_Unreg(&$ticket, $subject, $body, $files = array())
	{
		if (self::ShouldSend('email_on_create') == 1)
			self::EMail_To_Ticket_User('email_on_create_unreg', $ticket, $subject, $body, $files);
	}
	
	// Sends list of tickets and their passwords out
	static function User_Unreg_Passwords($tickets)
	{
		// need to know if template is html or not to make the pass list variable
		$template = self::Get_Template('email_unreg_passwords', $ticket['lang']);	
		self::$extra_vars['passlist'] = self::MakePassList($tickets, $template['ishtml']);	
		
		// single ticket to get user info and other stuff	
		$ticket = FSS_Helper::ObjectToArray($tickets[0]);

		// send email as usual
		self::EMail_To_Ticket_User('email_unreg_passwords', $ticket);
	}

	/**
	 * Emails to admins
	 */	
	static function EMail_To_Ticket_Handler($template, &$ticket, $subject, $body, $files = array())
	{
		$ticket = FSS_Helper::ObjectToArray($ticket);

		$mailer = new FSSMailer();
		self::Ticket_To_Admins($mailer, $ticket);
			
		$lang = "";
		if ($ticket['admin_id'] > 0)
		{
			$user = JFactory::getUser($ticket['admin_id']);
			$lang = $user->getParam('language');
		}
		//$lang = 

		// parse template etc
		$template = self::Get_Template($template, $lang);
		$email = self::ParseTemplate($template,$ticket,$subject,$body,$template['ishtml']);

		$mailer->isHTML($template['ishtml']);
		$mailer->setSubject($email['subject']);
		$mailer->setBody($email['body']);

		if (FSS_Settings::get('support_email_file_handler') == 1){
			$mailer->addFiles($files);
		}
			
		$mailer->addDebug('Ticket', $ticket);
		$mailer->send();
	}

	// User created a ticket, send notification to admin
	static function Admin_Create(&$ticket, $subject, $body, $files = array())
	{
		if (self::ShouldSend('email_handler_on_create') == 1)
			self::EMail_To_Ticket_Handler('email_handler_on_create', $ticket, $subject, $body, $files);
	}

	// Ticket forwarded to another admin / product / department, send notification to admins
	static function Admin_Forward(&$ticket, $subject, $body, $files, $email_all = false)
	{
		if (self::ShouldSend('email_handler_on_create') == 1)
			self::EMail_To_Ticket_Handler('email_handler_on_create', $ticket, $subject, $body, $files);
	}

	// New ticket via email pending approval, send notification to admins
	static function Admin_Pending(&$ticket, $subject, $body, $files = array())
	{
		if (self::ShouldSend('email_handler_on_pending') == 1)
			self::EMail_To_Ticket_Handler('email_handler_on_pending', $ticket, $subject, $body, $files);
	}

	// User had replied to a ticket, send notification to admins
	static function User_Reply(&$ticket, $subject, $body, $files = array())
	{
		if (self::ShouldSend('email_handler_on_reply') == 1)
			self::EMail_To_Ticket_Handler('email_handler_on_reply', $ticket, $subject, $body, $files);
	}

	/**
	 * Misc Emails
	 **/

	// comment awaiting moderation, send notification
	static function Send_Comment($comments)
	{	
		$fssmail = new FSSMailer();
		$fssmail->AddMultiAddress($comments->dest_email);
		
		$tpl = $comments->handler->EMail_GetTemplate($comments->moderate);
		$template = self::Get_Template($tpl);
		
		$data = $comments->comment;
		$data['moderated'] = $comments->moderate ? $comments->moderate : "";
		$data['linkmod'] = $comments->moderate ? $comments->GetModLink() : "";	
		if (!array_key_exists('customfields',$data)) $data['customfields'] = "";
		if (!array_key_exists('email',$data)) $data['email'] = "";
		if (!array_key_exists('website',$data)) $data['website'] = "";
		if (!array_key_exists('linkart',$data))	$data['linkart'] = "";	
		$comments->handler->EMail_AddFields($data);

		$customfields = "";
		foreach($comments->customfields as &$field)
			$customfields .= $field['description'] . ": " . $data['custom_' . $field['id']] . ($template['ishtml'] ? "<br />" : "\n");
		$data['customfields'] = $customfields;

		if ($template['ishtml'])
		{
			$data['article'] = "<a href='{$data['linkart']}'>{$data['article']}</a>";
			$data['linkart'] = "<a href='{$data['linkart']}'>here</a>";	
			if ($comments->moderate) $data['linkmod'] = "<a href='{$data['linkmod']}'>here</a>";	
		}

		$email = self::ParseGeneralTemplate($template, $data);

		$fssmail->isHTML($template['ishtml']);
		$fssmail->setSubject($email['subject']);
		$fssmail->setBody($email['body']);

		$fssmail->addDebug('Comments', $comments);
		$fssmail->addDebug('Data', $data);
		$fssmail->addDebug('EMail', $email);

		$fssmail->send();
	}

	/*******************
	 * Helper Functions
	 *******************/	
	
	static function ShouldSend($tag)
	{
		return FSS_Settings::get('support_' . $tag);
	}
	
	static function MakePassList($tickets, $is_html)
	{
		if ($is_html)
		{
			$output[] = "<table><tr><th>".JText::_('SUBJECT')."</th><th>".JText::_('LAST_UPDATE')."</th><th>".JText::_('STATUS')."</th>";
			
			if (in_array(FSS_Settings::get('support_unreg_type'), array(1,2)))
				$output[] = "<th>".JText::_('REFERENCE')."</th></tr>";

			if (in_array(FSS_Settings::get('support_unreg_type'), array(0,1)))
				$output[] = "<th>".JText::_('PASSWORD')."</th></tr>";


			foreach ($tickets as $ticket)
			{
				$output[] = "<tr><td>" . $ticket->title . "</td>";		
				$output[] = "<td>" . FSS_Helper::Date($ticket->lastupdate, FSS_DATETIME_MID) . "</td>";		
				$output[] = "<td>" . $ticket->status . "</td>";		

				if (in_array(FSS_Settings::get('support_unreg_type'), array(1,2)))
					$output[] = "<td>" . $ticket->reference . "</td>";		

				if (in_array(FSS_Settings::get('support_unreg_type'), array(0,1)))
					$output[] = "<td>" . $ticket->password . "</td>";	

				$output[] = "</tr>";	
			}
			$output[] = "</table>";
		} else {
			$output[] = "";
			foreach ($tickets as $ticket)
			{
				$output[] = JText::_('SUBJECT') . " : " . $ticket->title . "\n";		
				$output[] = JText::_('LAST_UPDATE') . " : " . FSS_Helper::Date($ticket->lastupdate, FSS_DATETIME_MID) . "\n";		
				$output[] = JText::_('STATUS') . " : " . $ticket->status . "\n";
						
				if (in_array(FSS_Settings::get('support_unreg_type'), array(1,2)))
					$output[] = JText::_('REFERENCE') . " : " . $ticket->reference . "\n";		

				if (in_array(FSS_Settings::get('support_unreg_type'), array(0,1)))
					$output[] = JText::_('PASSWORD') . " : " . $ticket->password . "\n";	
						
				$output[] = "\n";
			}
		}
		
		return implode($output);
	}

	static function ParseGeneralTemplate($template, $data)
	{
		if ($template['ishtml'])
		{
			$data['body'] = str_replace("\n","<br>\r\n",$data['body']);	
		}
	
		foreach($data as $var => $value)
			$vars[] = self::BuildVar($var,$value);

		$email['subject'] = self::ParseText($template['subject'],$vars);
		$email['body'] = self::ParseText($template['body'],$vars);
	
		if ($template['ishtml'])
			$email['body'] = FSS_Helper::MaxLineLength($email['body']);
		
		return $email;			
	}
	
	static function Ticket_To_Users(&$mailer, &$ticket)
	{
		$db = JFactory::getDBO();
		$count = 0;
		
		$recipient = array();
		// add ticket user as recipient
		if ($ticket['user_id'] == 0 && $ticket['email'])
		{
			$mailer->addTo($ticket['email'], $ticket['unregname'], "User: Ticket Unreg EMail");
		} else {
			$db->setQuery("SELECT * FROM #__users WHERE id = " . (int)$ticket['user_id']);
			$userrec = $db->loadObject();
			if ($userrec) 
				$mailer->addTo($userrec->email, $userrec->name, "User: Ticket User ID");
		}

		// check for any ticket cc users
		if ($ticket['id'] > 0)
		{
			$qry = "SELECT u.name, u.id, u.email, c.email as uremail FROM #__fss_ticket_cc as c LEFT JOIN #__users as u ON c.user_id = u.id WHERE c.ticket_id = {$ticket['id']} AND isadmin = 0 ORDER BY name";
			$db->setQuery($qry);
			$ticketcc = $db->loadObjectList();
			foreach ($ticketcc as $cc)
			{
				if ($cc->email)
				{
					$mailer->addTo($cc->email, $cc->name, "User: CC User ID");
				} else if ($cc->uremail) 
				{
					$mailer->addTo($cc->uremail, "", "User: CC Unreg EMail");
				}
			}
		}

		// if user_id on ticket is set, then check for any group recipients
		if ($ticket['user_id'] > 0)
		{
			// get groups that the user belongs to
			$qry = "SELECT * FROM #__fss_ticket_group WHERE id IN (SELECT group_id FROM #__fss_ticket_group_members WHERE user_id = '".FSSJ3Helper::getEscaped($db, $ticket['user_id'])."')";
			$db->setQuery($qry);
			$groups = $db->loadObjectList('id');
			
			if (count($groups) > 0)
			{
				$gids = array();
				foreach ($groups as $id => &$group)
					$gids[$id] = $id;	
			
				// get list of users in the groups
				$qry = "SELECT m.*, u.email, u.name FROM #__fss_ticket_group_members as m LEFT JOIN #__users as u ON m.user_id = u.id WHERE group_id IN (" . implode(", ",$gids) . ")";
				$db->setQuery($qry);
				$users = $db->loadObjectList();
				
				// for all users, if group has cc or user has cc then add to cc list			
				foreach($users as &$user)
					if ($user->allemail || $groups[$user->group_id]->allemail) 
					$mailer->addTo($user->email, $user->name, "User: Ticket Group " . $user->group_id);
			}
		}
	}

	static function Ticket_To_Admins($mailer, &$ticket)
	{
		// add the actual assigned ticket handler
		if ($ticket['admin_id'] > 0)
		{
			$mailer->AddUserAddress($ticket['admin_id'], null, 'addTo', 'Admin: Ticket Admin ID');
		} else { // no assigned handler, so use the unassigned email list
			$mailer->AddMultiAddress(FSS_Settings::get('support_email_unassigned'), 'addTo', 'Admin: Unassigned ticket setting');
		} 	

		// if email all admins is set
		if (FSS_Settings::get('support_email_all_admins') && !(FSS_Settings::get('support_email_all_admins_only_unassigned') && $ticket['admin_id'] > 0))
		{
			// Build a list of all available ticket handlers
			$mailer->AddUserAddress(
				SupportUsers::getHandlersTicket(
						$ticket['prod_id'], $ticket['ticket_dept_id'], $ticket['ticket_cat_id'], 
						FSS_Settings::get('support_email_all_admins_ignore_auto'), 
						FSS_Settings::get('support_email_all_admins_can_view'),
						true)
					, null, 'addTo', 'Admin: All admins setting');
		}
		
		// any cc emails need adding	
		$mailer->AddMultiAddress(FSS_Settings::get('support_email_admincc'), 'addTo', 'Admin: CC all setting');

		// any admins that are cc'd on the ticket
		$db = JFactory::getDBO();
		$qry = "SELECT user_id FROM #__fss_ticket_cc as c LEFT JOIN #__users as u ON c.user_id = u.id WHERE ticket_id = " . $ticket['id'] . " AND isadmin = 1";
		$db->setQuery($qry);
		$mailer->AddUserAddress($db->loadObjectList(), 'user_id', 'addTo', 'Admin: CCd on ticket');
	}
	
	static $last_vars = array();

	static function &ParseTemplate($template,&$ticket,$subject,$body,$ishtml,$foruser = false)
	{
		$handler = self::GetHandler($ticket['admin_id'], $template['tmpl']);
		$custrec = self::GetUser($ticket['user_id']);
	
		$subject = trim(str_ireplace("re:","",$subject));
		$vars[] = self::BuildVar('subject',$subject);

		$body = FSS_Helper::ParseBBCode($body,null,false,false,$foruser);	
		$body = str_replace("&lt;", "XXXLTXXX", $body);
		$body = str_replace("&gt;", "XXXGTXXX", $body);
		
		$vars[] = self::BuildVar('body',$body);
		$vars[] = self::BuildVar('reference',$ticket['reference']);
		$vars[] = self::BuildVar('password',$ticket['password']);
		
		foreach (self::$extra_vars as $key => $value)
			$vars[] = self::BuildVar($key,$value);
		
		if ($ticket['user_id'] == 0)
		{
			$vars[] = self::BuildVar('user_name',$ticket['unregname']);
			$vars[] = self::BuildVar('user_username',JText::_("UNREGISTERED"));
			$vars[] = self::BuildVar('user_email',$ticket['email']);
		} else {
			$vars[] = self::BuildVar('user_name',$custrec['name']);
			$vars[] = self::BuildVar('user_username',$custrec['username']);
			$vars[] = self::BuildVar('user_email',$custrec['email']);
		}

		$vars[] = self::BuildVar('handler_name',$handler['name']);
		$vars[] = self::BuildVar('handler_username',$handler['username']);
		$vars[] = self::BuildVar('handler_email',$handler['email']);
		
		$vars[] = self::BuildVar('ticket_id',$ticket['id']);
		
		if ($foruser)
		{
			$statuss = SupportHelper::getStatuss(false);
			$status = $statuss[$ticket['ticket_status_id']];
			if ($status->combine_with > 0)
				$status = $statuss[$status->combine_with];
			
			FSS_Translate_Helper::TrO($status);
			$text = $status->title;
			if ($status->userdisp)
				$text = $status->userdisp;
			$vars[] = self::BuildVar('status',$text);
		} else {
			$vars[] = self::BuildVar('status',self::GetStatus($ticket['ticket_status_id']));
		}		
		
		$vars[] = self::BuildVar('priority',self::GetPriority($ticket['ticket_pri_id']));
		$vars[] = self::BuildVar('category',self::GetCategory($ticket['ticket_cat_id']));
		$vars[] = self::BuildVar('department',self::GetDepartment($ticket['ticket_dept_id']));
		$vars[] = self::BuildVar('department_desc',strip_tags(self::GetDepartment($ticket['ticket_dept_id'], 'description')));
		$vars[] = self::BuildVar('department_desc_html',self::GetDepartment($ticket['ticket_dept_id'], 'description'));
		$vars[] = self::BuildVar('product',self::GetProduct($ticket['prod_id']));
		$vars[] = self::BuildVar('product_desc',strip_tags(self::GetProduct($ticket['prod_id'], 'description')));
		$vars[] = self::BuildVar('product_desc_html',self::GetProduct($ticket['prod_id'], 'description'));
		
		if (strpos($template['body'],"{messagehistory}") > 0)
		{
			$messages = self::GetMessageHist($ticket['id']);
			$text = self::ParseMessageRows($messages, $ishtml, $foruser);
			$vars[] = self::BuildVar('messagehistory',$text);
		}
		
		$uri = JURI::getInstance();	
		$baseUrl = FSS_Settings::get('support_email_no_domain') ? "" : $uri->toString( array('scheme', 'host', 'port'));
		
		if (in_array(FSS_Settings::get('support_unreg_type'), array(0,1)) )
		{
			$vars[] = self::BuildVar('haspassword', 1);
		} else {
			$vars[] = self::BuildVar('haspassword', 0);
		}

		// choose which user link to generate
		if ($ticket['user_id'] < 1)
		{
			// unregistered user
			$url = 'index.php?option=com_fss&t=' . $ticket['id'] . "&p=" . $ticket['password'];
			if (FSS_Settings::get('support_email_link_unreg') > 0) // add fixed item id if needed
				$url .= "&Itemid=" . FSS_Settings::get('support_email_link_unreg');
			$vars[] = self::BuildVar('ticket_link',$baseUrl . JRoute::_($url, false));
		} else {
			// registered user
			$url = 'index.php?option=com_fss&view=ticket&layout=view&ticketid=' . $ticket['id'];

			if (FSS_Settings::get('support_email_include_autologin'))
				$url .= "&login={login_code}";

			if (FSS_Settings::get('support_email_link_reg') > 0) // add fixed item id if needed
				$url .= "&Itemid=" . FSS_Settings::get('support_email_link_reg');
			$vars[] = self::BuildVar('ticket_link',$baseUrl . JRoute::_($url, false));
		}
		
		// ticket admin link
		$url = 'index.php?option=com_fss&view=admin_support&layout=ticket&ticketid=' . $ticket['id'];
		if (FSS_Settings::get('support_email_link_admin') > 0) // add fixed item id if needed
			$url .= "&Itemid=" . FSS_Settings::get('support_email_link_admin');
		$vars[] = self::BuildVar('admin_link',$baseUrl . JRoute::_($url, false));

		// email pending link
		$url = 'index.php?option=com_fss&view=admin_support&layout=emails';
		if (FSS_Settings::get('support_email_link_pending') > 0) // add fixed item id if needed
			$url .= "&Itemid=" . FSS_Settings::get('support_email_link_pending');
		$vars[] = self::BuildVar('email_pending_link',$baseUrl . JRoute::_($url, false));

		$vars[] = self::BuildVar('websitetitle',FSS_Helper::getSiteName());
	
		// need to add the tickets custom fields to the output here
		
		$fields = FSSCF::GetAllCustomFields(true);
		$values = FSSCF::GetTicketValues($ticket['id'],$ticket);

		foreach ($fields as $fid => &$field)
		{
			$name = "custom_" . $fid;
			$value = "";
			if (array_key_exists($fid, $values))
				$value = (string)$values[$fid]['value'];

			$fieldvalues = array();
			$fieldvalues[0]['field_id'] = $fid;
			$fieldvalues[0]['value'] = $value;
			
			// only do area output processing if we are in html mode
			if ($field['type'] != "area" || $ishtml)
			{
				$newvalue = FSSCF::FieldOutput($field, $fieldvalues, '');
				if ($newvalue)
					$value = $newvalue;
			}
			
			$vars[] = self::BuildVar($name, $value);
			$vars[] = self::BuildVar("custom_" . $field['alias'], $value);
		}
	
		$email['subject'] = self::ParseText($template['subject'],$vars);
		$email['body'] = self::ParseText($template['body'],$vars);

		self::$last_vars = $vars;

		if ($template['ishtml'])
		{
			$email['body'] = FSS_Helper::MaxLineLength($email['body']);
		} else {	
			$email['body'] = str_replace("<br />","\n",$email['body']);
			$email['body'] = html_entity_decode($email['body']);
			$email['body'] = preg_replace_callback("/(&#[0-9]+;)/", array("FSS_Helper", "email_decode_utf8"), $email['body']); 
			$email['body'] = strip_tags($email['body']);
		}
		
		$email['body'] = str_replace("XXXLTXXX", "<", $email['body']);
		$email['body'] = str_replace("XXXGTXXX", ">", $email['body']);

		return $email;	
	}

	static function ParseMessageRows(&$messages, $ishtml, $foruser = false)
	{
		$template = self::Get_Template('messagerow');
		$result = "";
		
		foreach ($messages as &$message)
		{
			$vars = array();
			//print_p($message);
			if ($message['name'])
			{
				$vars[] = self::BuildVar('name',$message['name']);
				$vars[] = self::BuildVar('email',$message['email']);
				$vars[] = self::BuildVar('username',$message['username']);
			} else {
				$vars[] = self::BuildVar('name','Unknown');
				$vars[] = self::BuildVar('email','Unknown');
				$vars[] = self::BuildVar('username','Unknown');
			}
			$vars[] = self::BuildVar('subject',$message['subject']);
			$vars[] = self::BuildVar('posted',FSS_Helper::Date($message['posted']));
			
			$message['body'] = FSS_Helper::ParseBBCode($message['body'],null,false,false,$foruser);

			if ($ishtml)
			{
				$message['body'] = str_replace("\n","<br>\n",$message['body']);	
				$vars[] = self::BuildVar('body',$message['body'] . "<br />");	
			} else {
				$vars[] = self::BuildVar('body',$message['body'] . "\n");	
			}
			
			$result .= self::ParseText($template['body'],$vars);
		}
		
		return $result;
	}

	static function BuildVar($name,$value)
	{
		return array('name' => $name, 'value' => $value);
	}

	static function ParseText($text,&$vars)
	{
		foreach ($vars as $var)
		{
			$value = $var['value'];
			$block = "{".$var['name']."}";
			$start = "{".$var['name']."_start}";
			$end = "{".$var['name']."_end}";
		
			if ($value != "")
			{
				$text = str_replace($block, $value, $text);	
				$text = str_replace($start, "", $text);	
				$text = str_replace($end, "", $text);	
			} else {
				$text = str_replace($block, "", $text);	
				while (strpos($text, $end) !== false && strpos($text, $start) !== false)
				{
					$pos_end = strpos($text, $end);
					$pos_beg = strpos($text, $start);

					if ($pos_end && $pos_beg){
						$text = substr_replace($text, '', $pos_beg, ($pos_end - $pos_beg) + strlen($end));
					}
				}
			}
		}
		return $text;
	}

	static function Get_Template($tmpl, $curlang = "")
	{
		$db = JFactory::getDBO();
		$qry = 	"SELECT body, subject, ishtml, translation, tmpl FROM #__fss_emails WHERE tmpl = '".FSSJ3Helper::getEscaped($db, $tmpl)."'";
		$db->setQuery($qry);
		$data = $db->loadAssoc();
		
		if ($curlang == "")
			return $data;

		$curlang = str_replace("-","",$curlang);
		
		if (!isset($data["translation"]))
			return $data;
		
		$translation = json_decode($data["translation"], true);
		
		if (!$translation)
			return $data;
		
		foreach ($translation as $field => $langs)
		{
			foreach ($langs as $lang => $text)
			{
				if ($lang == $curlang && trim($text) != "")
					$data[$field] = $text;
			}
		}
		
		return $data;
	}

	static function GetHandler($admin_id, $tmpl)
	{
		// email to user from handler, if we have a logged in handler and its not cron, change the from id
		if ( ($tmpl == "email_on_reply" || $tmpl == "email_on_close" ||
			  $tmpl == "email_on_autoclose" || $tmpl == "email_handler_on_forward") && 
				JRequest::getVar('view') != "cron")
			$admin_id = JFactory::getUser()->id;
		
		if ($admin_id == 0)
			return array("name" => JText::_("UNASSIGNED"),"username" => JText::_("UNASSIGNED"),"email" => "");	
		
		$db = JFactory::getDBO();
		$query = " SELECT * FROM #__users WHERE id = '".FSSJ3Helper::getEscaped($db, $admin_id)."'";
		$db->setQuery($query);
		$handler = $db->loadAssoc();
		return $handler;
	}

	/**
	 * Fetch data relating to the ticket
	 **/
	static function GetUser($user_id)
	{
		$db = JFactory::getDBO();
		$qry = "SELECT * FROM #__users WHERE id = '".FSSJ3Helper::getEscaped($db, $user_id)."'";
		$db->setQuery($qry);
		$row = $db->loadAssoc();
		return $row;
	}

	static function GetStatus($status_id)
	{
		$db = JFactory::getDBO();
		$qry = "SELECT title FROM #__fss_ticket_status WHERE id = '".FSSJ3Helper::getEscaped($db, $status_id)."'";	
		$db->setQuery($qry);
		$row = $db->loadAssoc();
		return $row['title'];
	}

	static function GetArticle($artid)
	{
		$db = JFactory::getDBO();
		$qry = "SELECT title FROM #__fss_kb_art WHERE id = '".FSSJ3Helper::getEscaped($db, $artid)."'";	
		$db->setQuery($qry);
		$row = $db->loadAssoc();
		return $row['title'];
	}

	static function GetPriority($pri_id)
	{
		$db = JFactory::getDBO();
		$qry = "SELECT title FROM #__fss_ticket_pri WHERE id = '".FSSJ3Helper::getEscaped($db, $pri_id)."'";	
		$db->setQuery($qry);
		$row = $db->loadAssoc();
		return $row['title'];
	}

	static function GetCategory($cat_id)
	{
		$db = JFactory::getDBO();
		$qry = "SELECT title FROM #__fss_ticket_cat WHERE id = '".FSSJ3Helper::getEscaped($db, $cat_id)."'";	
		$db->setQuery($qry);
		$row = $db->loadAssoc();
		return $row['title'];
	}

	static function GetDepartment($dept_id, $field = 'title')
	{
		static $department;
		if (empty($department))
		{
			$db = JFactory::getDBO();
			$qry = "SELECT title, description FROM #__fss_ticket_dept WHERE id = '".FSSJ3Helper::getEscaped($db, $dept_id)."'";	
			$db->setQuery($qry);
			$department = $db->loadAssoc();
		}
		if (is_array($department) && array_key_exists($field, $department))
			return $department[$field];
		
		return "";
	}

	static function GetProduct($prod_id, $field = 'title')
	{
		static $product;
		if (empty($product))
		{
			$db = JFactory::getDBO();
			$qry = "SELECT * FROM #__fss_prod WHERE id = '".FSSJ3Helper::getEscaped($db, $prod_id)."'";	
			$db->setQuery($qry);
			$product = $db->loadAssoc();
		}
		if (is_array($product) && array_key_exists($field, $product))
		return $product[$field];
		
		return "";
	}
	
	static function GetMessageHist($ticket_id)
	{
		$db = JFactory::getDBO();
		$qry = "SELECT m.*, u.name, u.username, u.email FROM #__fss_ticket_messages as m";
		$qry .= " LEFT JOIN #__users as u ON m.user_id = u.id";
		$qry .= " WHERE ticket_ticket_id = '".FSSJ3Helper::getEscaped($db, $ticket_id)."'";	
		$qry .= " AND admin IN (0, 1) ORDER BY posted DESC";
		
		//echo $qry."<br>";
		$db->setQuery($qry);
		$rows = $db->loadAssocList();

		return $rows;
	}

}

