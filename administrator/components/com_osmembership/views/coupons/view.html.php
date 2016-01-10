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
class OSMembershipViewCoupons extends OSViewList
{

	function _buildListArray(&$lists, $state)
	{
		$db = JFactory::getDbo();
		$discountTypes = array(0 => '%', 1 => OSMembershipHelper::getConfigValue('currency_symbol'));
		$nullDate = $db->getNullDate();
		
		$sql = 'SELECT id, title FROM #__osmembership_plans WHERE published=1 ORDER BY ordering ';
		$db->setQuery($sql);
		$options = array();
		$options[] = JHtml::_('select.option', 0, JText::_('OSM_PLAN'), 'id', 'title');
		$options = array_merge($options, $db->loadObjectList());
		$lists['plan_id'] = JHtml::_('select.genericlist', $options, 'plan_id', ' class="inputbox" onchange="submit();"', 'id', 'title', 
			$state->plan_id);
		
		$this->dateFormat = OSMembershipHelper::getConfigValue('date_format');
		$this->nullDate = '0000-00-00';
		$this->discountTypes = $discountTypes;
	}
}