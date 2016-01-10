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
class OSMembershipViewSubscribers extends OSViewList
{

	function _buildListArray(&$lists, $state)
	{
		$db = JFactory::getDbo();
		$config = OSMembershipHelper::getConfig();
		$sql = 'SELECT id, title FROM #__osmembership_plans WHERE published = 1 ORDER BY ordering ';
		$db->setQuery($sql);
		$options = array();
		$options[] = JHtml::_('select.option', 0, JText::_('OSM_ALL_PLANS'), 'id', 'title');
		$options = array_merge($options, $db->loadObjectList());
		$lists['plan_id'] = JHtml::_('select.genericlist', $options, 'plan_id', ' class="inputbox" onchange="submit();" ', 'id', 'title', $state->plan_id);

		$options = array();
		$options[] = JHtml::_('select.option', 0, JText::_('OSM_ALL_SUBSCRIPTIONS'));
		$options[] = JHtml::_('select.option', 1, JText::_('OSM_NEW_SUBSCRIPTION'));
		$options[] = JHtml::_('select.option', 2, JText::_('OSM_SUBSCRIPTION_RENEWAL'));
		$options[] = JHtml::_('select.option', 3, JText::_('OSM_SUBSCRIPTION_UPGRADE'));
		$lists['subscription_type'] = JHtml::_('select.genericlist', $options, 'subscription_type', ' class="inputbox" onchange="submit();" ', 'value', 'text', $state->subscription_type);

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
}