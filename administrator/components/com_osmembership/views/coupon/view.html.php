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
class OSMembershipViewCoupon extends OSViewForm
{

	function _buildListArray(&$lists, $item)
	{
		$db = JFactory::getDbo();
		$sql = 'SELECT id, title FROM #__osmembership_plans WHERE published = 1 ORDER BY ordering ';
		$db->setQuery($sql);
		$options = array();
		$options[] = JHtml::_('select.option', 0, JText::_('OSM_ALL_PLANS'), 'id', 'title');
		$options = array_merge($options, $db->loadObjectList());
		$lists['plan_id'] = JHtml::_('select.genericlist', $options, 'plan_id', ' class="inputbox" ', 'id', 'title', $item->plan_id);
		
		$options = array();
		$options[] = JHtml::_('select.option', 0, JText::_('%'));
		//$options[] = JHtml::_('select.option', 1, OSMembershipHelper::getConfigValue('currency_symbol'));
		$options[] = JHtml::_('select.option', 1, '$');
		$lists['coupon_type'] = JHtml::_('select.genericlist', $options, 'coupon_type', 'class="inputbox"', 'value', 'text', $item->coupon_type);
		
		$this->nullDate = '0000-00-00';
		
		return true;
	}
}