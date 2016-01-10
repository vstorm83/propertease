<?php
/**
 * @version		1.1.1
 * @package		Joomla
 * @subpackage	OS Membership
 * @author  Tuan Pham Ngoc
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die;
/**
 * OS Membership Reminder Plugin
 *
 * @package		Joomla
 * @subpackage	OS Membership
 */
class plgOSMembershipMembershipId extends JPlugin
{
	/**
	 * Run when a membership activated
	 * @param PlanOsMembership $row
	 */
	function onMembershipActive($row) {
		$db = JFactory::getDbo();
		if (!$row->membership_id) {
			if ($row->user_id) {				
				$sql = 'SELECT MAX(membership_id) FROM #__osmembership_subscribers WHERE user_id='.$row->user_id ;
				$db->setQuery($sql);
				$membershipId = (int) $db->loadResult() ;;
				if ($membershipId)
					$row->membership_id = $membershipId ;
			}
			if (!$row->membership_id)												
				$row->membership_id = OSMembershipHelper::getMembershipId();
			$row->store();			
		}
							
		return true ;
	}	
}
