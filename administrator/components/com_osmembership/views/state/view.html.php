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
class OSMembershipViewState extends OSViewForm
{

	function _buildListArray(&$lists, $item)
	{
		$db = JFactory::getDbo();
		$db->setQuery("SELECT `id` AS value, `name` AS text FROM `#__osmembership_countries` WHERE `published`=1");
		$options = $db->loadObjectList();
		array_unshift($options,JHtml::_('select.option',0,' - '.JText::_('OSM_SELECT_COUNTRY').' - '));
		$item->country_id = JHtml::_('select.genericlist', $options, 'country_id', ' class="inputbox"','value', 'text', $item->country_id);		
		return true;
	}
}