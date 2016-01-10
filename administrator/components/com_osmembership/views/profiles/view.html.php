<?php
/**
 * @version		1.6.8
 * @package		Joomla
 * @subpackage	Membership Pro
 * @author  Tuan Pham Ngoc
 * @copyright	Copyright (C) 2012 - 2014 Ossolution Team
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
class OSMembershipViewProfiles extends OSViewList
{

	function _buildListArray(&$lists, $state)
	{		
		$config = OSMembershipHelper::getConfig();						
		$options = array();
		$options[] = JHtml::_('select.option', -1, JText::_('OSM_ALL'));
		$options[] = JHtml::_('select.option', 0, JText::_('OSM_PENDING'));
		$options[] = JHtml::_('select.option', 1, JText::_('OSM_ACTIVE'));
		$options[] = JHtml::_('select.option', 2, JText::_('OSM_EXPIRED'));
		$options[] = JHtml::_('select.option', 3, JText::_('OSM_CANCELLED_PENDING'));
		$options[] = JHtml::_('select.option', 4, JText::_('OSM_CANCELLED_REFUNDED'));
		$lists['published'] = JHtml::_('select.genericlist', $options, 'published', ' class="inputbox" onchange="submit();" ', 'value', 'text', $state->published);				
		$this->config = $config;
				
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
		JToolBarHelper::editList($controller . '.edit');						
		if (JFactory::getUser()->authorise('core.admin', 'com_osmembership'))
		{
			JToolBarHelper::preferences('com_osmembership');
		}
	}
}