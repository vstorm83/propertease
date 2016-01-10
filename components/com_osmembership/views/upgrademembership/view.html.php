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
class OSMembershipViewUpgrademembership extends JViewLegacy
{
	function display($tpl = null)
	{
		$user   = JFactory::getUser();
		$db     = JFactory::getDbo();
		$config = OSMembershipHelper::getConfig();
		$Itemid = JRequest::getInt('Itemid');
		$query  = $db->getQuery(true);
		$query->select('a.*, b.username')
			->from('#__osmembership_subscribers AS a ')
			->leftJoin('#__users AS b ON a.user_id=b.id')
			->where('is_profile=1')
			->where("(a.email='$user->email' OR user_id=$user->id)");
		$db->setQuery($query);
		$item = $db->loadObject();
		if (!$item)
		{
			JFactory::getApplication()->redirect('index.php', JText::_('OSM_DONOT_HAVE_SUBSCRIPTION_RECORD_TO_UPGRADE'));
		}

		// Get upgrade options
		$query->clear();
		$query->select('DISTINCT plan_id')
			->from('#__osmembership_subscribers')
			->where('profile_id = ' . $item->id)
			->where('published = 1');
		$db->setQuery($query);
		$planIds = $db->loadColumn();

		$query->clear();
		$query->select('*')
			->from('#__osmembership_upgraderules')
			->where('from_plan_id IN (' . implode(',', $planIds) . ')')
			->order('from_plan_id');
		$db->setQuery($query);
		$upgradeRules = $db->loadObjectList();

		if (!count($upgradeRules))
		{
			JFactory::getApplication()->redirect('index.php', JText::_('OSM_NO_UPGRADE_OPTIONS_AVAILABLE'));
		}

		$query->clear();
		$query->select('*')
			->from('#__osmembership_plans')
			->where('published = 1');
		$db->setQuery($query);
		$plans = $db->loadObjectList('id');

		// Load js file to support state field dropdown
		JFactory::getDocument()->addScript(JUri::base(true) . '/components/com_osmembership/assets/js/paymentmethods.js');

		// Need to get subscriptions information of the user
		$this->Itemid       = $Itemid;
		$this->planIds      = $planIds;
		$this->upgradeRules = $upgradeRules;
		$this->config       = $config;
		$this->plans        = $plans;

		parent::display($tpl);
	}
}