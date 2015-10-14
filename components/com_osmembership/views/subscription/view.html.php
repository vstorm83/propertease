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
class OSMembershipViewSubscription extends JViewLegacy
{

	function display($tpl = null)
	{
		$user   = JFactory::getUser();
		$config = OSMembershipHelper::getConfig();
		$Itemid = JRequest::getInt('Itemid', 0);
		$item   = $this->get('Data');
		if ($item->user_id != $user->get('id'))
		{
			$app = JFactory::getApplication();
			$app->redirect('index.php', JText::_('OSM_INVALID_ACTION'));
		}
		//Form		
		$rowFields = OSMembershipHelper::getProfileFields($item->plan_id, true, $item->language);
		$data      = OSMembershipHelper::getProfileData($item, $item->plan_id, $rowFields);
		$form      = new RADForm($rowFields);
		$form->setData($data)->bindData();

		$this->Itemid = $Itemid;
		$this->config = $config;
		$this->item   = $item;
		$this->form   = $form;

		parent::display($tpl);
	}
}