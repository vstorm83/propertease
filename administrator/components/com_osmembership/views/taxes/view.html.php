<?php
/**
 * @version        1.6.8
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die();

/**
 * HTML View class for Membership Pro component
 *
 * @static
 * @package        Joomla
 * @subpackage     Membership Pro
 */
class OSMembershipViewTaxes extends OSViewList
{

	function _buildListArray(&$lists, $state)
	{
		$db = JFactory::getDbo();

		// Build plans dropdown
		$options   = array();
		$options[] = JHtml::_('select.option', 0, JText::_('OSM_PLAN'), 'id', 'title');
		$sql       = 'SELECT id, title FROM #__osmembership_plans WHERE published=1 ORDER BY ordering ';
		$db->setQuery($sql);
		$options          = array_merge($options, $db->loadObjectList());
		$lists['plan_id'] = JHtml::_('select.genericlist', $options, 'plan_id', ' class="inputbox" onchange="submit();"', 'id', 'title',
			$state->plan_id);

		// Build countries dropdown
		$options   = array();
		$options[] = JHtml::_('select.option', 0, JText::_('OSM_SELECT_COUNTRY'));
		$sql       = 'SELECT `name` AS value, `name` AS text FROM `#__osmembership_countries` WHERE `published`=1';
		$db->setQuery($sql);
		$options          = array_merge($options, $db->loadObjectList());
		$lists['country'] = JHtml::_('select.genericlist', $options, 'country', ' class="inputbox" onchange="submit();" ', 'value', 'text', $state->country);

		$defaultCountry = OSMembershipHelper::getConfigValue('default_country');
		$countryCode    = OSmembershipHelper::getCountryCode($defaultCountry);

		if (OSMembershipHelperEuvat::isEUCountry($countryCode))
		{
			$this->showVies = true;
			$options   = array();
			$options[] = JHtml::_('select.option', -1, JText::_('OSM_VIES'));
			$options[] = JHtml::_('select.option', 0, JText::_('OSM_NO'));
			$options[] = JHtml::_('select.option', 1, JText::_('OSM_YES'));
			$lists['vies'] = JHtml::_('select.genericlist', $options, 'vies', ' class="inputbox" onchange="submit();" ', 'value', 'text', $state->vies);
		}
		else
		{
			$this->showVies = false;
		}
	}
}