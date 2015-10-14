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

class SupportActionsSample extends SupportActionsPlugin
{
	var $title = "Sample Plugin";
	var $description = "";

	static function Admin_Reply($ticket, $params)
	{
	}	

	static function beforeEMailSend($ticket, $params)
	{
		/*$params['mailer']->SetFrom("test@domain.com", "Test Name", 0);
		print_p($ticket);
		print_p($params);
		exit;*/
	}
}