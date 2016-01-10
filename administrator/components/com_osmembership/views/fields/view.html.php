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
 * HTML View class for OS Membership Component
 *
 * @static
 * @package		Joomla
 * @subpackage	Membership Pro
 */
class OSMembershipViewFields extends OSViewList
{

	function _buildListArray(&$lists, $state)
	{
		$db = JFactory::getDbo();
		$sql = 'SELECT id, title FROM #__osmembership_plans WHERE published = 1 ORDER BY ordering ';
		$db->setQuery($sql);
		$options = array();
		$options[] = JHtml::_('select.option', 0, JText::_('OSM_ALL_PLANS'), 'id', 'title');
		$options = array_merge($options, $db->loadObjectList());
		$lists['plan_id'] = JHtml::_('select.genericlist', $options, 'plan_id', ' class="inputbox" onchange="submit();" ', 'id', 'title', 
			$state->plan_id);
		$options = array();
		$options[] = JHtml::_('select.option', 1, JText::_('Yes'));
		$options[] = JHtml::_('select.option', 2, JText::_('No'));
		$lists['show_core_field'] = JHtml::_('select.genericlist', $options, 'show_core_field', ' class="input-mini" onchange="submit();" ', 'value', 
			'text', $state->show_core_field);
		
		return true;
	}
}