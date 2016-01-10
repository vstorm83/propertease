<?php
/**
 * @version		1.0
 * @package		Joomla
 * @subpackage	OSFramework
 * @author  Tuan Pham Ngoc
 * @copyright	Copyright (C) 2012 - 2014 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die();

class OSViewList extends JViewLegacy
{

	var $lang_prefix = OSF_LANG_PREFIX;

	function display($tpl = null)
	{
		$state = $this->get('State');
		$items = $this->get('Data');
		$pagination = $this->get('Pagination');		
		$this->state = $state;		
		$lists = array();
		$lists['order_Dir'] = $state->filter_order_Dir;
		$lists['order'] = $state->filter_order;				
		$lists['filter_state'] = JHtml::_('grid.state', $state->filter_state);
		$this->_buildListArray($lists, $state);
		$this->lists = $lists;
		$this->items = $items;
		$this->pagination = $pagination;		
		$this->_buildToolbar();		
		parent::display($tpl);
	}

	/**
	 * Build all the lists items used in the form and store it into the array
	 * @param  $lists
	 * @return boolean
	 */
	function _buildListArray(&$lists, $state)
	{
		
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
		JToolBarHelper::custom($controller . '.copy', 'copy.png', 'copy_f2.png', 'Copy', true);
		JToolBarHelper::publishList($controller . '.publish');
		JToolBarHelper::unpublishList($controller . '.unpublish');
        if (JFactory::getUser()->authorise('core.admin', 'com_osmembership'))
        {
            JToolBarHelper::preferences('com_osmembership');
        }
	}
}