<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

/**
 * Handle all ticket events from the ticket plugins we have available
 */
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'email.php');

class SupportActionsEMailSend extends SupportActionsPlugin
{
	var $title = "EMail Notifications";
	var $description = "Send EMail notification on ticket actions, such as new tickets, or admins replying etc. This plugin is configured from the Settings menu, in the support tab.";
	
	function Admin_Reply($ticket, $params)
	{
		if (!$params['user_message'])
			return;
		
		$status = $ticket->getStatus();
		if ($status->is_closed)
		{
			FSS_EMail::Admin_Close($ticket, $params['subject'], $params['user_message'], $params['files']);
		} else {
			FSS_EMail::Admin_Reply($ticket, $params['subject'], $params['user_message'], $params['files']);
		}
	}
	
	function Admin_Private($ticket, $params)
	{
		if (!$params['handler_message'])
			return;
		
		$ticket->loadAll();
		//if (JFactory::getUser()->id != $ticket->admin_id || count($ticket->admin_cc) > 0)
		{
			FSS_EMail::Admin_Forward($ticket, $params['subject'], $params['handler_message'], $params['files'], true);
		}
	}
		
	function Admin_ForwardUser($ticket, $params)
	{
		if (!$params['user_message'])
			return;
		
		FSS_EMail::User_Create($ticket, $params['subject'], $params['user_message'], $params['files']);
	}
		
	function Admin_ForwardProduct($ticket, $params)
	{
		if ($params['handler_message'])
		{
			FSS_EMail::Admin_Forward($ticket, $params['subject'], $params['handler_message'], $params['files']);
		} else if ($params['user_message']) {
			FSS_EMail::Admin_Forward($ticket, $params['subject'], $params['user_message'], $params['files']);
		}	
		
		if ($params['user_message'])
			FSS_EMail::Admin_Reply($ticket, $params['subject'], $params['user_message'], $params['files']);
	}
		
	function Admin_ForwardHandler($ticket, $params)
	{
		$handler_msg = $params['handler_message'];
		$user_msg = $params['user_message'];
		
		
		if (FSS_Settings::Get('support_email_send_empty_handler'))
		{
			if (!$user_msg)
				$user_msg = JText::_("THIS_TICKET_HAS_BEEN_FORWARDED_TO_ANOTHER_HANDLER");
			
			// translation message missing, provide english version just in case
			if (!$user_msg || $user_msg == "THIS_TICKET_HAS_BEEN_FORWARDED_TO_ANOTHER_HANDLER")
				$user_msg = 'This ticket has been forwarded to another handler';
		}
		
		if ($handler_msg)
		{
			FSS_EMail::Admin_Forward($ticket, $params['subject'], $handler_msg, $params['files']);
		} else if ($user_msg) {
			FSS_EMail::Admin_Forward($ticket, $params['subject'], $user_msg, $params['files']);
		}
		
		if ($user_msg)
			FSS_EMail::Admin_Reply($ticket, $params['subject'], $user_msg, $params['files']);
	}
	
	function User_Open($ticket, $params)
	{
		
		if ($ticket->email)
		{
			FSS_EMail::User_Create_Unreg($ticket, $params['subject'], $params['user_message'], $params['files']);
		} else {
			FSS_EMail::User_Create($ticket, $params['subject'], $params['user_message'], $params['files']);
		}
		FSS_EMail::Admin_Create($ticket, $params['subject'], $params['user_message'], $params['files']);
	}
	
	function User_Reply($ticket, $params)
	{
		if ($params['user_message'])
			FSS_EMail::User_Reply($ticket, $params['subject'], $params['user_message'], $params['files']);
	}
}