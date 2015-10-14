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
 * Membership Pro Component Subscriber Model
 *
 * @package		Joomla
 * @subpackage	Membership Pro
 */
class OSMembershipModelSubscriber extends OSModel
{

	/**
	 * Override store function to perform specific saving
	 * @see OSModel::store()
	 */
	function store(&$data)
	{		
		$row = $this->getTable('OsMembership', 'Subscriber');
		$isNew = true;
		if (!$data['id'] && $data['username'] && $data['password'])
		{
			//Store this account into the system and get the username
			jimport('joomla.user.helper');
			$params = JComponentHelper::getParams('com_users');
			$newUserType = $params->get('new_usertype', 2);
			
			$data['groups'] = array();
			$data['groups'][] = $newUserType;
			$data['block'] = 0;
			$data['name'] = $data['first_name'] . ' ' . $data['last_name'];
			$data['password1'] = $data['password2'] = $data['password'];
			$data['email1'] = $data['email2'] = $data['email'];
			$user = new JUser();
			$user->bind($data);
			if (!$user->save())
			{
				JFactory::getApplication()->redirect('index.php?option=com_osmembership&view=subscribers', $user->getError(), 'error');
			}
			$data['user_id'] = $user->id;
		
		}
		if ($data['id'])
		{
			$isNew = false;
			$row->load($data['id']);
			$published = $row->published;
		}
		else
		{
			$published = 0; //Default is pending
		}		
		if (!$row->bind($data))
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}		
		if (!$row->check())
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		$row->user_id = (int) $row->user_id;
		$db = $this->getDbo();
		$sql = "SELECT id FROM #__osmembership_subscribers WHERE is_profile=1 AND ((user_id=$row->user_id AND user_id>0) OR email='$row->email')";
		$db->setQuery($sql);
		$profileId = $db->loadResult();				
		if ($profileId && ($profileId != $row->id))
		{
			$row->is_profile = 0;
			$row->profile_id = $profileId;
		}			
		else
		{
			$row->is_profile = 1;
		}			
        $sql = ' SELECT lifetime_membership FROM #__osmembership_plans WHERE id='.(int)$data['plan_id'];
        $db->setQuery($sql);
        $lifetimeMembership = $db->loadResult();
        if($lifetimeMembership == 1 && $data['to_date'] == '')
        {
            $row->to_date = "2099-31-12 00:00:00";
        }
        if (!$row->store())
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}	
		if (!$row->profile_id)
		{
			$row->profile_id = $row->id;
			$row->store();
		}			
		$rowFields = OSMembershipHelper::getProfileFields($row->plan_id, false);
		$form = new RADForm($rowFields);				
		$form->storeData($row->id, $data);
		JPluginHelper::importPlugin('osmembership');
		$dispatcher = JDispatcher::getInstance();
		if ($isNew)
		{						
			$dispatcher->trigger('onAfterStoreSubscription', array($row));
		}		
		if ($published != 1 && $row->published == 1)
		{
            //Membership active, trigger plugin                        
            $dispatcher->trigger('onMembershipActive', array($row));
            OSMembershipHelper::sendMembershipApprovedEmail($row);
		}
		elseif ($published == 1)
		{
			if ($row->published != 1)
			{				
				$dispatcher->trigger('onMembershipExpire', array($row));
			}
		}		
		$data['id'] = $row->id;				
		if (!$isNew)
		{			
			$dispatcher->trigger('onMembershipUpdate', array($row));
		}				
		OSMembershipHelper::syncronizeProfileData($row, $data);		
		return true;
	}

    function delete($cid = array())
    {
        if (count($cid))
        {
	        // Delete custom fields data related to selected subscribers
	        $db = $this->getDbo();
	        $query = $db->getQuery(true);
	        $query->delete('#__osmembership_field_value')
		        ->where('subscriber_id IN (' . implode(',', $cid) . ')');
	        $db->setQuery($query);
	        $db->execute();
            JPluginHelper::importPlugin('osmembership');
            $dispatcher = JDispatcher::getInstance();
            $row = $this->getTable('OsMembership', 'Subscriber');
            foreach($cid as $id)
            {
                $row->load($id);
                $dispatcher->trigger('onMembershipExpire', array($row));
            }
        }
        return parent::delete($cid);
    }
	/**
	 * Handling publish, unpublish subscriber by clicking on publish button in the checkbox
	 * Allows running OSMembership Plugins 
	 * @see OSModel::publish()
	 */
	function publish($cid, $state)
	{
		if (count($cid))
		{
			if ($state == 1)
			{
				$row = $this->getTable('OsMembership', 'Subscriber');
				JPluginHelper::importPlugin('osmembership');
				$dispatcher = JDispatcher::getInstance();
				foreach ($cid as $id) 
				{
					$row->load($id);					
					if (!$row->published)
					{
						$dispatcher->trigger('onMembershipActive', array($row));
						OSMembershipHelper::sendMembershipApprovedEmail($row);
					}											
				}
			}
			parent::publish($cid, $state);				
		}
		
		return true;
	}
}