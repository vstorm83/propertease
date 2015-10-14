<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class FSS_GUIPlugin_Department_Tabs
{
	var $title = "Department Tabs";
	var $description = "Add a new tab to the admin support interface for each department, linking to the tickets for that department";
	
	// adds a tab to the admin tabs list
	function adminSupportTabs_End()
	{
		$output = "";

		$departments = SupportHelper::getDepartments();

		$class = "";
		
		foreach ($departments as $dept)
		{
			$class = "";
			if (JRequest::getVar('department') == $dept->id) $class = "active";
			$output[] = '<li class="' . $class . '">';
			$output[] = '<a href="' . JRoute::_( 'index.php?option=com_fss&view=admin_support&what=search&searchtype=advanced&showbasic=1&department=' . $dept->id ) . '">';
			$output[] = $dept->title;
			$output[] = '	</a>';
			$output[] = '</li> ';
		}

		return implode("\n", $output);
	}
}