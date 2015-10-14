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
class OSMembershipViewTax extends OSViewForm
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
		$options[] = JHtml::_('select.option', '', JText::_('OSM_ALL_COUNTRIES'));
		$sql = 'SELECT `name` AS value, `name` AS text FROM `#__osmembership_countries` WHERE `published`=1';
		$db->setQuery($sql);
		$options = array_merge($options, $db->loadObjectList());
		$lists['country'] = JHtml::_('select.genericlist', $options, 'country', ' class="inputbox" ', 'value', 'text', $item->country, 'country');

		$defaultCountry = OSMembershipHelper::getConfigValue('default_country');
		$countryCode = OSMembershipHelper::getCountryCode($defaultCountry);
		if (OSMembershipHelperEuvat::isEUCountry($countryCode))
		{
			$lists['vies'] = JHtml::_('select.booleanlist', 'vies', ' class="inputbox" ', $item->vies);
		}
		
		return true;
	}
}