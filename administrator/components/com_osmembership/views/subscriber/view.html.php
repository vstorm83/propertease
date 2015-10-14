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
class OSMembershipViewSubscriber extends OSViewForm
{

	function _buildListArray(&$lists, $item)
	{		
		$db = JFactory::getDbo();
		$config = OSMembershipHelper::getConfig();
		$sql = 'SELECT id, title FROM #__osmembership_plans WHERE published = 1 ORDER BY ordering ';
		$db->setQuery($sql);
		$options = array();
		$options[] = JHtml::_('select.option', 0, JText::_('OSM_ALL_PLANS'), 'id', 'title');
		$options = array_merge($options, $db->loadObjectList());
		$lists['plan_id'] = JHtml::_('select.genericlist', $options, 'plan_id', ' class="inputbox" ', 'id', 'title', $item->plan_id);																								
		//Subscription status
		$options = array();
		$options[] = JHtml::_('select.option', -1, JText::_('OSM_ALL'));
		$options[] = JHtml::_('select.option', 0, JText::_('OSM_PENDING'));
		$options[] = JHtml::_('select.option', 1, JText::_('OSM_ACTIVE'));
		$options[] = JHtml::_('select.option', 2, JText::_('OSM_EXPIRED'));
		$options[] = JHtml::_('select.option', 3, JText::_('OSM_CANCELLED_PENDING'));
		$options[] = JHtml::_('select.option', 4, JText::_('OSM_CANCELLED_REFUNDED'));
		$lists['published'] = JHtml::_('select.genericlist', $options, 'published', ' class="inputbox" ', 'value', 'text', $item->published);
		
		//Get list of payment methods
		$sql = 'SELECT name, title FROM #__osmembership_plugins WHERE published=1 ORDER BY ordering';
		$db->setQuery($sql);
		$options = array();
		$options[] = JHtml::_('select.option', '', JText::_('OSM_PAYMENT_METHOD'), 'name', 'title');
		$options = array_merge($options, $db->loadObjectList());
		$lists['payment_method'] = JHtml::_('select.genericlist', $options, 'payment_method', ' class="inputbox" ', 'name', 'title', 
			$item->payment_method);				
		$rowFields = OSMembershipHelper::getProfileFields($item->plan_id, true, $item->language);
		$data = array();
		if ($item->id)
		{						
			$data = OSMembershipHelper::getProfileData($item, $item->plan_id, $rowFields);						
			$setDefault = false;
		}
		else 
		{
			$setDefault = true;
		}
		if (!isset($data['country']) || !$data['country'])
		{
			$data['country'] = $config->default_country;
		}
		$form = new RADForm($rowFields);	
		$form->setData($data)->bindData($setDefault);
		//Custom fields processing goes here		
		if ($item->plan_id)
		{			
			$sql = 'SELECT lifetime_membership FROM #__osmembership_plans WHERE id='.$item->plan_id;
			$db->setQuery($sql);
			$item->lifetime_membership = (int) $db->loadResult();
		}
		else 
		{
			$item->lifetime_membership = 0;	
		}			
		$this->config = $config;
		$this->form = $form;
		return true;
	}
}