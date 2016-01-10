<?php
/**
 * @version        1.6.8
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2014 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

/**
 * HTML View class for OS Membership component
 *
 * @static
 * @package        Joomla
 * @subpackage     Membership Pro
 */
class OSMembershipViewProfile extends JViewLegacy
{
	function display($tpl = null)
	{
		$db   = JFactory::getDbo();
		$user = JFactory::getUser();
		if (!$user->id)
		{
			$return = JRoute::_('index.php?option=com_osmembership&view=profile&Itemid=' . JRequest::getInt('Itemid'));
			JFactory::getApplication()->redirect('index.php?option=com_users&view=login&return=' . base64_encode($return), JText::_('OSM_LOGIN_TO_EDIT_PROFILE'));
		}
		$config = OSMembershipHelper::getConfig();
		$Itemid = JRequest::getInt('Itemid');
		$item   = $this->get('Data');
		if (!$item)
		{
			$redirectURL = OSMembershipHelper::getViewUrl(array('categories', 'plans', 'plan', 'register'));
			if (!$redirectURL)
			{
				$redirectURL = 'index.php';
			}
			JFactory::getApplication()->redirect('index.php', JText::_('OSM_DONOT_HAVE_SUBSCRIPTION_RECORD'));
		}
		//Get subscriptions history
		require_once JPATH_COMPONENT . '/models/subscriptions.php';
		$model = JModelLegacy::getInstance('Subscriptions', 'OSMembershipModel');
		$items = $model->getData();
		//Form
		$rowFields = OSMembershipHelper::getProfileFields(0, true, $item->language);
		$data      = OSMembershipHelper::getProfileData($item, 0, $rowFields);
		$form      = new RADForm($rowFields);
		$form->setData($data)->bindData();
		//Trigger third party add-on
		JPluginHelper::importPlugin('osmembership');
		$dispatcher = JDispatcher::getInstance();
		$results    = $dispatcher->trigger('onProfileDisplay', array($item));
		//Get renew and upgrade options, only allow renew for active or expired subscriptions
		$sql = 'SELECT DISTINCT plan_id FROM #__osmembership_subscribers WHERE profile_id=' . $item->id . ' AND (published = 1 OR published = 2)';
		$db->setQuery($sql);
		$planIds = $db->loadColumn();

		//Check to see whether the user can renew or not
		$canRenew = false;
		foreach ($planIds as $planId)
		{
			$sql = 'SELECT recurring_subscription, enable_renewal FROM #__osmembership_plans WHERE id=' . $planId;
			$db->setQuery($sql);
			$plan = $db->loadObject();
			if (!$plan->recurring_subscription && $plan->enable_renewal)
			{
				$canRenew = true;
				break;
			}
		}
		//Load js file to support state field dropdown
		JFactory::getDocument()->addScript(JUri::base(true) . '/components/com_osmembership/assets/js/paymentmethods.js');
		//Need to get subscriptions information of the user
		$plans          = OSMembershipHelper::getSubscriptions($item->profile_id);
		$renewOptions   = array();
		$this->item     = $item;
		$this->config   = $config;
		$this->items    = $items;
		$this->form     = $form;
		$this->Itemid   = $Itemid;
		$this->plugins  = $results;
		$this->planIds  = $planIds;
		$this->plans    = $plans;
		$this->canRenew = $canRenew;

		parent::display($tpl);
	}
}