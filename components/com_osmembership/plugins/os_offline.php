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
defined( '_JEXEC' ) or die ;

class os_offline extends os_payment {	
	/**
	 * Constructor functions, init some parameter
	 *
	 * @param object $params
	 */
	function os_offline($params) {
		parent::setName('os_offline');		
		parent::os_payment();				
		parent::setCreditCard(false);		
    	parent::setCardType(false);
    	parent::setCardCvv(false);
    	parent::setCardHolderName(false);		
	}	
	/**
	 * Process payment 
	 *
	 */
	function processPayment($row, $data) 
	{		
		$Itemid = JRequest::getint('Itemid');
		$config = OSMembershipHelper::getConfig() ;									
		OSMembershipHelper::sendEmails($row, $config);				
		$db = JFactory::getDbo();
		$sql = 'SELECT subscription_complete_url FROM #__osmembership_plans WHERE id='.$row->plan_id ;
		$db->setQuery($sql);
		$subscriptionCompleteURL =  $db->loadResult() ;
		if ($subscriptionCompleteURL)
		{
			JFactory::getApplication()->redirect($subscriptionCompleteURL);
		}			
		else
		{
			JFactory::getApplication()->redirect(JRoute::_('index.php?option=com_osmembership&view=complete&act='.$row->act.'&subscription_code='.$row->subscription_code.'&Itemid='.$Itemid, false, false));
		}										
	}		
}