<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view' );
jimport('joomla.utilities.date');
require_once( JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'support_actions.php' );

class FsssViewPlugins extends JViewLegacy
{
    function display($tpl = null)
    {
		JToolBarHelper::title( JText::_("Plugins"), 'fss_moderate' );
		FSSAdminHelper::DoSubToolbar();

		$task = JRequest::getVar('task');
		
		if ($task == "enable")
			return $this->enable(1);
		
		if ($task == "disable")
			return $this->enable(0);

		$this->plugins = $this->LoadPlugins();

        parent::display($tpl);
    }
	
	function LoadPlugins()
	{
		$db = JFactory::getDBO();
		$qry = "SELECT * FROM #__fss_plugins ORDER BY `type`, name";
		$db->setQuery($qry);
		return $db->loadObjectList();
	}
	
	function enable($enable = 1)
	{
		/*$plugins = $this->LoadPlugins();		
		$pluginid = JRequest::getVar('plugin');
		
		foreach ($plugins as $plugin)
		{
			if ($plugin->id != $pluginid) continue;
			
			$info = pathinfo($plugin->php_file);
			
			$dis_file = $info['dirname'] . DS . $info['filename'] . ".disabled";
			
			$test = $plugin->CanEnable();
			if ($test === true)
			{
				if (file_exists($dis_file))
					@unlink($dis_file);
			} else {
				JFactory::getApplication()->redirect(JRoute::_('index.php?option=com_fss&view=plugins', false), $test);
			}
		}
		*/

		$type = JRequest::getVar('type');
		$name = JRequest::getVar('name');

		$db = JFactory::getDBO();
		$sql = "UPDATE #__fss_plugins SET enabled = " . $db->escape($enable) . " WHERE `type` = '" . $db->escape($type) . "' AND name = '" . $db->escape($name) . "'";
		$db->setQuery($sql);
		$db->Query();
		$this->back_to_list();
	}
	
	function disable()
	{
		$plugins = $this->LoadPlugins();		
		$pluginid = JRequest::getVar('plugin');
		
		foreach ($plugins as $plugin)
		{
			if ($plugin->id != $pluginid) continue;
			
			$info = pathinfo($plugin->php_file);
			
			$dis_file = $info['dirname'] . DS . $info['filename'] . ".disabled";
			
			if (!file_exists($dis_file))
				touch($dis_file);
		}

		$this->back_to_list();
	}
	
	function back_to_list()
	{
		JFactory::getApplication()->redirect(JRoute::_('index.php?option=com_fss&view=plugins', false));
	}
}



