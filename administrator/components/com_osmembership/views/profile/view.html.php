<?php
/**
 * @version		1.6.8
 * @package		Joomla
 * @subpackage	OS Membership
 * @author  Tuan Pham Ngoc
 * @copyright	Copyright (C) 2011 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

/**
 * HTML View class for Membership Pro component
 *
 * @static
 * @package		Joomla
 * @subpackage	OS Membership
 */
class OSMembershipViewProfile extends JViewLegacy
{
	function display($tpl = null)
	{						
		$db = JFactory::getDbo();						
		$item = $this->get('Data');	
		//Get list of subscription records
		$sql = 'SELECT a.*, b.title AS plan_title, b.lifetime_membership, b.enable_renewal, b.recurring_subscription'	
			. ' FROM #__osmembership_subscribers  AS a'
			. ' INNER JOIN #__osmembership_plans AS b'				 
			. ' ON a.plan_id = b.id'
			. ' WHERE a.profile_id='.$item->id 
			. ' ORDER BY a.id DESC ';
		$db->setQuery($sql);
		$items = $db->loadObjectList();	
		//Form fields		
		$rowFields = OSMembershipHelper::getProfileFields(0, true, $item->language);
		$data = OSMembershipHelper::getProfileData($item, 0, $rowFields);		
		$form = new RADForm($rowFields);
		$form->setData($data)->bindData();		
		//Trigger third party add-on
		JPluginHelper::importPlugin('osmembership');
		$dispatcher = JDispatcher::getInstance();		
		//Trigger plugins
		$results = $dispatcher->trigger('onProfileDisplay', array($item));				
		$this->item = $item;
		$this->config = OSMembershipHelper::getConfig();							
		$this->plugins = $results;
		$this->items = $items;
		$this->form = $form;							
		parent::display($tpl);
	}
}