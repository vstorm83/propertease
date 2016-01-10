<?php
/**
 * @version		1.6.8
 * @package		Joomla
 * @subpackage	Membership Pro
 * @author  Tuan Pham Ngoc
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die();

/**
 * HTML View class for Membership Pro component
 *
 * @static
 * @package		Joomla
 * @subpackage	Membership Pro
 */
class OSMembershipViewStates extends OSViewList
{

	function _buildListArray(&$lists, $state)
	{
		$db = JFactory::getDbo();
		$db->setQuery("SELECT `id` AS value, `name` AS text FROM `#__osmembership_countries` WHERE `published`=1");
		$options = $db->loadObjectList();
		array_unshift($options,JHtml::_('select.option',0,' - '.JText::_('OSM_SELECT_COUNTRY').' - '));
		$lists['filter_country_id'] = JHtml::_('select.genericlist', $options, 'filter_country_id', ' class="inputbox" onchange="submit();" ','value', 'text', $this->state->filter_country_id);
		
		return true;
	}
	
/**
	 * Build the toolbar for view list 
	 */
	function _buildToolbar()
	{
		$viewName = $this->getName();
		$controller = OSInflector::singularize($this->getName());
		JToolBarHelper::title(JText::_($this->lang_prefix . '_' . strtoupper($viewName) . '_MANAGEMENT'));
		JToolBarHelper::deleteList(JText::_($this->lang_prefix . '_DELETE_' . strtoupper($this->getName()) . '_CONFIRM'), $controller . '.remove');
		JToolBarHelper::editList($controller . '.edit');
		JToolBarHelper::addNew($controller . '.add');		
		JToolBarHelper::publishList($controller . '.publish');
		JToolBarHelper::unpublishList($controller . '.unpublish');
        if (JFactory::getUser()->authorise('core.admin', 'com_osmembership'))
        {
            JToolBarHelper::preferences('com_osmembership');
        }
	}
}