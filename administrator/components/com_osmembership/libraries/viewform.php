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

class OSViewForm extends JViewLegacy
{

	/**
	 * Language prefix
	 * @var string
	 */
	var $lang_prefix = OSF_LANG_PREFIX;

	function display($tpl = null)
	{
		$db = JFactory::getDbo();
		$item = $this->get('Data');
		$languages = OSMembershipHelper::getLanguages();
		$lists = array();
		if (property_exists($item, 'published'))
		{
			$lists['published'] = JHtml::_('select.booleanlist', 'published', ' class="inputbox" ', $item->published);
		}
		if (property_exists($item, 'access'))
		{
			$lists['access'] = JHtml::_('access.level', 'access', $item->access, 'class="inputbox"', false);
		}
		$this->_buildListArray($lists, $item);
		$this->item = $item;
		$this->lists = $lists;
		$this->languages = $languages;
		$this->_buildToolbar();
		
		parent::display($tpl);
	}

	/**
	 * Build all the lists items used in the form and store it into the array
	 * @param  $lists
	 * @return boolean
	 */
	function _buildListArray(&$lists, $item)
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
		$edit = JRequest::getVar('edit');
		if ($edit)
		{
			$toolbarTitle = $this->lang_prefix.'_'.$viewName.'_EDIT';
		}
		else
		{
			$toolbarTitle = $this->lang_prefix.'_'.$viewName.'_NEW';
		}
		JToolBarHelper::title(JText::_(strtoupper($toolbarTitle)));
		JToolBarHelper::save($controller . '.save');
		JToolBarHelper::apply($controller . '.apply');
		JToolBarHelper::cancel($controller . '.cancel');
	}
}