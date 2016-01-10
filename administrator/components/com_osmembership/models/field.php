<?php
/**
 * @version		1.6.8
 * @package		Joomla
 * @subpackage	Membership Pro
 * @author  Tuan Pham Ngoc
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

/**
 * OS Membership Component Field Model
 *
 * @package		Joomla
 * @subpackage	Membership Pro
 */
class OSMembershipModelField extends OSModel
{

	/**
	 * Store the custom fields
	 * @see OSModel::store()
	 */
	function store(&$data)
	{
        $db = JFactory::getDbo();
		$row = & $this->getTable('osmembership', 'field');
		if ($data['id'])
		{
			$row->load($data['id']);
		}
		if ($row->is_core)
		{
			$ignores = array('name', 'fee_field');
		}
		else
		{
			$ignores = array();
		}
		if (!$row->bind($data, $ignores))
		{
			$this->setError($db->getErrorMsg());
			return false;
		}
        if (!isset($data['plan_id']) || $data['plan_id'][0] == 0 || $row->name == 'first_name' || $row->name == 'email') 
        {
			$row->plan_id = 0;
		} 
        else 
        {
			$row->plan_id = 1;
		}	        
		if (!$row->check())
		{
			$this->setError($db->getErrorMsg());
			return false;
		}
		if (!$row->id)
		{
			$row->ordering = $row->getNextOrder($this->getWhereNextOrdering());
		}
		
		if (!$row->store())
		{
			$this->setError($db->getErrorMsg());
			return false;
		}
		$data['id'] = $row->id;
        $sql = 'DELETE FROM #__osmembership_field_plan WHERE field_id='.$row->id;
		$db->setQuery($sql) ;
		$db->execute();
		if ($row->plan_id != 0) {
			if (isset($data['plan_id'])) {
				$planIds = $data['plan_id'] ;
				for ($i = 0 , $n = count($planIds); $i < $n ; $i++) {
					$planId = $planIds[$i] ;
                    if ($planId > 0)
                    {
                        $sql = "INSERT INTO #__osmembership_field_plan(field_id, plan_id) VALUES($row->id, $planId);";
                        $db->setQuery($sql) ;
                        $db->execute();
                    }					
				}
			}
		}	                
		return true;
	}

	/**
	 * Publish, unpublish the custom fields
	 * @see OSModel::publish()
	 */
	function publish($cid, $state)
	{
		$cids = implode(',', $cid);
		$sql = 'UPDATE #__osmembership_fields SET published=' . $state . ' WHERE id IN (' . $cids . ' ) AND name NOT IN("first_name", "email")';
		$this->_db->setQuery($sql);
		if ($this->_db->query())
			return true;
		else
			return false;
	}

	/**
	 * Delete the custom fields
	 * @see OSModel::delete()
	 */
	function delete($cid = array())
	{
		if (count($cid))
		{
			$db = JFactory::getDbo();
			$cids = implode(',', $cid);
			
			$sql = 'DELETE FROM #__osmembership_fields WHERE is_core = 0 AND id IN (' . $cids . ')';
			$db->setQuery($sql);
			$db->execute();
			
			$sql = 'DELETE FROM #__osmembership_field_value WHERE field_id IN (' . $cids . ')';
			$db->setQuery($sql);
			$db->execute();
		}
		
		return true;
	}
}