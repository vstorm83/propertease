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
 * HTML View class for Membership Pro component
 *
 * @static
 * @package        Joomla
 * @subpackage     Membership Pro
 */
class OSMembershipViewSubscriptions extends JViewLegacy
{

	function display($tpl = null)
	{
		$user   = JFactory::getUser();
		$userId = $user->get('id');
		if (!$userId)
		{
			$Itemid    = JRequest::getInt('Itemid');
			$returnUrl = JRoute::_('index.php?option=com_osmembership&view=subscriptions&Itemid=' . $Itemid);
			$app       = JFactory::getApplication();
			$url       = JRoute::_('index.php?option=com_users&view=login&return=' . base64_encode($returnUrl));
			$app->redirect($url, JText::_('OSM_PLEASE_LOGIN'));
		}
		$config           = OSMembershipHelper::getConfig();
		$Itemid           = JRequest::getInt('Itemid');
		$items            = $this->get('Data');
		$pagination       = $this->get('Pagination');
		$this->items      = $items;
		$this->Itemid     = $Itemid;
		$this->config     = $config;
		$this->pagination = $pagination;

		parent::display($tpl);
	}
}