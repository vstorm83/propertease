<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class FSS_GUIPlugin_OpenRelatedTicket
{
	var $title = "Open Related Ticket";
	var $description = "Adds an item to the support ticket Tools menu to open a new ticket related to this one for the same user.";
	

	// adds item to the bottom of the view ticket tools menu
	function adminTicketViewTools($ticket)
	{
		$output = "";
        
        $link = JRoute::_('index.php?option=com_fss&view=ticket&layout=open&admincreate=1&user_id=' . $ticket->user_id . '&related=' . $ticket->id . '&prodid=' . $ticket->prod_id . "&subject=" . $ticket->title);

		// new item
		$output[] = '	<a class="pull-right btn btn-primary" style="margin-left: 8px" href="' . $link . '">';
		$output[] = 'Open related ticket';
		$output[] = '	</a>';

		return implode("\n", $output);
	}
}