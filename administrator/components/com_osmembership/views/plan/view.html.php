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
class OSMembershipViewPlan extends OSViewForm
{

	function _buildListArray(&$lists, $item)
	{
		
		JPluginHelper::importPlugin('osmembership');
		$dispatcher = JDispatcher::getInstance();
		$db = JFactory::getDbo();
		$nullDate = $db->getNullDate();
		//Trigger plugins		
		$results = $dispatcher->trigger('onEditSubscriptionPlan', array($item));		
		$lists['enable_renewal'] = JHtml::_('select.booleanlist', 'enable_renewal', ' class="inputbox" ', $item->enable_renewal);
		$lists['lifetime_membership'] = JHtml::_('select.booleanlist', 'lifetime_membership', ' class="inputbox" ', $item->lifetime_membership);
		$lists['recurring_subscription'] = JHtml::_('select.booleanlist', 'recurring_subscription', ' class="inputbox" ', 
			$item->recurring_subscription);
		$lists['thumb'] = JHtml::_('list.images', 'thumb', $item->thumb, ' ', '/media/com_osmembership/');		
		//Get list of renewal options	
		if ($item->id > 0)
		{
			$sql = 'SELECT number_days, price FROM #__osmembership_renewrates WHERE plan_id=' . $item->id . ' ORDER BY id ';
			$db->setQuery($sql);
			$prices = $db->loadObjectList();
		}
		else
		{
			$prices = array();
		}		
		$sql = 'SELECT id, title FROM #__osmembership_categories WHERE published = 1 ORDER BY title ';
		$db->setQuery($sql);
		$options = array();
		$options[] = JHtml::_('select.option', 0, JText::_('OSM_SELECT_CATEGORY'), 'id', 'title');
		$options = array_merge($options, $db->loadObjectList());
		$lists['category_id'] = JHtml::_('select.genericlist', $options, 'category_id', ' class="inputbox" ', 'id', 'title', $item->category_id);
		
		$options = array();
		$options[] = JHtml::_('select.option', 'D', JText::_('OSM_DAYS'));
		$options[] = JHtml::_('select.option', 'W', JText::_('OSM_WEEKS'));
		$options[] = JHtml::_('select.option', 'M', JText::_('OSM_MONTHS'));
		$options[] = JHtml::_('select.option', 'Y', JText::_('OSM_YEARS'));
		$lists['trial_duration_unit'] = JHtml::_('select.genericlist', $options, 'trial_duration_unit', ' class="input-medium" ', 'value', 'text', 
			$item->trial_duration_unit);
		$lists['subscription_length_unit'] = JHtml::_('select.genericlist', $options, 'subscription_length_unit', ' class="input-medium" ', 'value', 'text',
			$item->subscription_length_unit);
		
		
		
		$sql = 'SELECT id, title FROM #__osmembership_plans WHERE published=1 AND id !='.(int) $item->id. ' ORDER BY ordering ';
		$db->setQuery($sql);
		$this->plans = $db->loadObjectList();				
		if ($item->id)
		{
			$sql = "SELECT * FROM #__osmembership_upgraderules WHERE from_plan_id=".(int)$item->id;
			$db->setQuery($sql);
			$this->upgradeRules = $db->loadObjectList();
		}	
		else 
		{			
			$this->upgradeRules = array();
		}									
		$this->prices = $prices;
		$this->plugins = $results;
		$this->nullDate = $nullDate;		
		return true;
	}
}