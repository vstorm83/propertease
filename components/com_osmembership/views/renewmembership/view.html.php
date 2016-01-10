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
class OSMembershipViewRenewmembership extends JViewLegacy
{
	function display($tpl = null)
	{
		$db   = JFactory::getDbo();
		$user = JFactory::getUser();
		if (!$user->id)
		{
			$return = JRoute::_('index.php?option=com_osmembership&view=renewmembership&Itemid=' . JRequest::getInt('Itemid'));
			JFactory::getApplication()->redirect('index.php?option=com_users&view=login&return=' . base64_encode($return), JText::_('OSM_LOGIN_TO_RENEW_MEMBERSHIP'));
		}
		$config = OSMembershipHelper::getConfig();
		$Itemid = JRequest::getInt('Itemid');
		$query  = $db->getQuery(true);
		$query->select('a.*, b.username')
			->from('#__osmembership_subscribers AS a ')
			->leftJoin('#__users AS b ON a.user_id=b.id')
			->where('is_profile=1')
			->where("(a.email='$user->email' OR a.user_id=$user->id)");
		$db->setQuery($query);
		$item = $db->loadObject();
		if (!$item)
		{
			JFactory::getApplication()->redirect('index.php', JText::_('OSM_DONOT_HAVE_SUBSCRIPTION_RECORD_TO_RENEW'));
		}
		//Get renew and upgrade options
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

		if (!$canRenew)
		{
			JFactory::getApplication()->redirect('index.php', JText::_('OSM_NO_RENEW_OPTIONS_AVAILABLE'));
		}

		//Load js file to support state field dropdown
		JFactory::getDocument()->addScript(JUri::base(true) . '/components/com_osmembership/assets/js/paymentmethods.js');

		//Need to get subscriptions information of the user
		$this->Itemid   = $Itemid;
		$this->planIds  = $planIds;
		$this->canRenew = $canRenew;
		$this->config   = $config;
		parent::display($tpl);
	}
}