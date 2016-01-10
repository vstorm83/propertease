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
 * OS Membership Component Importy Model
 *
 * @package		Joomla
 * @subpackage	Membership Pro
 */
class OSMembershipModelImport extends JModelLegacy
{

	function _getSubscriberCSV()
	{
		$keys = array();
		$subscribers = array();
		$subscriber = array();
		jimport('joomla.filesystem.file');
		$allowedExts = array('csv');
		$csvFile = $_FILES['csv_subscribers'];
		$csvFileName = $csvFile['tmp_name'];
		$fileName = $csvFile['name'];
		$fileExt = strtolower(JFile::getExt($fileName));
		if (in_array($fileExt, $allowedExts))
		{
			$line = 0;
			$fp = fopen($csvFileName, 'r');
			while (($cells = fgetcsv($fp)) !== FALSE)
			{
				if ($line == 0)
				{
					foreach ($cells as $key)
					{
						$keys[] = $key;
					}
					$line++;
				}
				else
				{
					$i = 0;
					foreach ($cells as $cell)
					{
						$subscriber[$keys[$i]] = $cell;
						$i++;
					}
					$subscribers[] = $subscriber;
				}
			}
			fclose($fp);
			
			return $subscribers;
		}
	}

	/**
	 * Override store function to perform specific saving
	 * @see OSModel::store()
	 */
	function store()
	{
		jimport('joomla.user.helper');
		$db = JFactory::getDbo();
		$params = JComponentHelper::getParams('com_users');		
        $newUserType = $params->get('new_usertype', 2);
		$subscribers = $this->_getSubscriberCSV();
		$data = array();				
		$data['groups'] = array();
		$data['groups'][] = $newUserType;
		$data['block'] = 0;
		$rowFieldValue = JTable::getInstance('OsMembership', 'FieldValue');
		$query = "SELECT id,name FROM #__osmembership_fields WHERE is_core = 0";
		$db->setQuery($query);
		$customFields = $db->loadObjectList();
		$imported = 0;
		JPluginHelper::importPlugin( 'osmembership' );
		$dispatcher = JDispatcher::getInstance();						
		if (count($subscribers))
		{
			foreach ($subscribers as $subscriber)
			{
				$userId = 0;
				//check username exit in table users
				if ($subscriber['username'])
				{
					$sql = 'SELECT id FROM #__users WHERE username="' . $subscriber['username'] . '"';
					$db->setQuery($sql);
					$userId = (int) $db->loadResult();
					if (!$userId)
					{
						$data['name'] = $subscriber['first_name'] . ' ' . $subscriber['last_name'];
						if ($subscriber['password'])
						{
							$data['password'] = $data['password2'] = $subscriber['password'];
						}
						else
						{
							$data['password'] = $data['password2'] = JUserHelper::genRandomPassword();
						}
						$data['email'] = $data['email1'] = $data['email2'] = $subscriber['email'];
						$data['username'] = $subscriber['username'];
						if ($data['username'] && $data['name'] && $data['email1'])
						{
							$user = new JUser();
							$user->bind($data);
							$user->save();
							$userId = $user->id;
						}
					}
				}
				//get plan Id
				$planTitle = JString::strtolower($subscriber['plan']);
				$query = "SELECT id FROM #__osmembership_plans WHERE LOWER(title) = '$planTitle'";
				$db->setQuery($query);
				$planId = (int) $db->loadResult();
				$subscriber['plan_id'] = $planId;
				$subscriber['user_id'] = $userId;
				//save subscribers core
				$row = $this->getTable('OsMembership', 'Subscriber');
				$row->bind($subscriber);
				if (!$row->payment_date)
					$row->payment_date = $row->from_date;
				$row->created_date = $row->from_date;
                
                $sql = "SELECT id FROM #__osmembership_subscribers WHERE is_profile=1 AND ((user_id=$userId AND user_id>0) OR email='$row->email')";
                $db->setQuery($sql);
                $profileId = $db->loadResult();
                if ($profileId)
                {
                	$row->is_profile = 0;
                	$row->profile_id = $profileId;
                }                    
                else
                {
                	$row->is_profile = 1;
                }                     	                
				$row->store();
				if (!$row->profile_id)
				{
					$row->profile_id = $row->id;
					$row->store();
				}
				//get Extra Field				
				if (count($customFields))
				{
					foreach ($customFields as $customField)
					{
						if (isset($subscriber[$customField->name]) && $subscriber[$customField->name])
						{
							$rowFieldValue->id = 0;
							$rowFieldValue->field_id = $customField->id;
							$rowFieldValue->subscriber_id = $row->id;
							$rowFieldValue->field_value = $subscriber[$customField->name];
							$rowFieldValue->store();
						}
					}
				}																		
				if ($row->published == 1) 
				{
					$dispatcher->trigger( 'onMembershipActive', array($row));
				}				
				$imported++;
			}
		}
		return $imported;
	}
}