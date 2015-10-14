<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

/**
 * Example GUI plugin for Freestyle Support
 * 
 * Please let us know in a support ticket if you would like any new places adding where you can output
 * custom content within the system and we will get support added to the next version 
 * 
 * To enable this example plugin goto Components -> Freestyle Support -> Overview -> Pluings
 **/
class FSS_GUIPlugin_Example
{
	var $title = "GUI Plugin Example";
	var $description = "Several examples of using the gui plugins";
	
	// adds a tab to the admin tabs list
	function adminTabs()
	{
		$output = "";

		$class = "";
		if (JRequest::getVar('view') == "myview") $class = "active";
		$output[] = '<li class="' . $class . '">';
		$output[] = '<a href="' . JRoute::_( 'index.php?option=com_content' ) . '">';
		$output[] = '	<img src="' .  JURI::root( true ) . '/components/com_fss/assets/images/save_16.png">';
		$output[] = JText::_("New Tab (Plugin)");
		$output[] = '	</a>';
		$output[] = '</li> ';

		return implode("\n", $output);
	}

	// output at top of the admin overview
	function adminOverviewTop()
	{
		return "<h4>Admin Overview Top</h4>";
	}

	// output at bottom of the admin overview
	function adminOverviewBottom()
	{
		return "<h4>Admin Overview Bottom</h4>";
	}

	// adds items to the bottom of the list ticket tools menu
	function adminTicketListTools()
	{
		$output = "";

		// divider
		$output[] = '<li class="divider"></li>';

		// new item
		$output[] = '<li>';
		$output[] = '	<a href="' . JRoute::_('index.php?option=com_content') . '">';
		$output[] = JText::_("New item (plugin)");
		$output[] = '	</a>';
		$output[] = '</li>';	

		return implode("\n", $output);
	}

	// adds item to the bottom of the view ticket tools menu
	function adminTicketViewTools($ticket)
	{
		$output = "";

		// divider
		$output[] = '<li class="divider"></li>';

		// new item
		$output[] = '<li>';
		$output[] = '	<a href="' . JRoute::_('index.php?option=com_content') . '">';
		$output[] = $ticket->title;
		$output[] = '	</a>';
		$output[] = '</li>';	

		return implode("\n", $output);
	}
}