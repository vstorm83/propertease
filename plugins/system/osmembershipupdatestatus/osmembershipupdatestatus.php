<?php
/**
 * @version		1.0.0
 * @package		Joomla
 * @subpackage	OS Membership
 * @author  Tuan Pham Ngoc
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die;
error_reporting(0);
class plgSystemOSMembershipUpdateStatus extends JPlugin
{
	function onAfterInitialise()
	{
		if (file_exists(JPATH_ROOT.'/components/com_osmembership/osmembership.php')) {
			$lastRun = (int) $this->params->get('last_run', 0);
			$numberEmailSendEachTime = (int) $this->params->get('number_subscribers', 5);
			$currentTime = time() ;
			$numberMinutes = ($currentTime - $lastRun)/60 ;
						
			//This plugin win runs in each 2 hours
			if ($numberMinutes >= 120)
            {
				require_once JPATH_ROOT.'/components/com_osmembership/helper/helper.php' ;
				$db = JFactory::getDbo() ;
                $sql = ' SELECT a.* FROM #__osmembership_subscribers AS a '
                    .' INNER JOIN #__osmembership_plans AS b ON a.plan_id = b.id '
                    .' WHERE b.lifetime_membership !=1 AND a.published=1 AND a.to_date < NOW() ORDER BY a.to_date ';
				$db->setQuery($sql) ;
				$rows = $db->loadObjectList() ;				
				$ids = array();
				if (count($rows)) {
					//Load Plugin to trigger OnMembershipExpire event
					JPluginHelper::importPlugin( 'osmembership' );
					$dispatcher = JDispatcher::getInstance();
					foreach ($rows as $row) {						
						//Trigger plugins
						$dispatcher->trigger( 'onMembershipExpire', array($row));
						$ids[] = $row->id ;
					}
					$sql = 'UPDATE #__osmembership_subscribers SET published=2 WHERE id IN ('.implode(',', $ids).')';
					$db->setQuery($sql) ;
					$db->query();
				}	
				//Update Last run times
				//Store last run time
				$this->params->set('last_run', $currentTime);
				$params = $this->params->toString();
				if (version_compare(JVERSION, '1.6.0', 'ge')) {
					$sql = 'SELECT extension_id FROM #__extensions WHERE `element`="osmembershipupdatestatus" AND `folder`="system"';
					$db->setQuery($sql) ;					
					$pluginId = $db->loadResult() ;														
					$sql = 'UPDATE #__extensions SET 	params='.$db->quote($params).' WHERE extension_id='.$pluginId ;
					$db->setQuery($sql);
					$db->query();
				} else {
					$sql = 'SELECT id FROM #__plugins WHERE `element`="osmembershipupdatestatus" AND `folder`="system"';
					$db->setQuery($sql) ;
					$pluginId = $db->loadResult() ;
					$sql = 'UPDATE #__plugins SET 	params='.$db->quote($params).' WHERE id='.$pluginId ;
					$db->setQuery($sql);
					$db->query();
				}								
			}													
		}					
		return true ;		
	}
}
