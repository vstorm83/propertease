<?php
/**
 * @version		1.6.8
 * @package		Joomla
 * @subpackage	Membership Pro
 * @author  Tuan Pham Ngoc
 * @copyright	Copyright (C) 2012 - 2014 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

class OSMembershipModelProfiles extends OSModelList
{

	function __construct($config)
	{
		$config['search_fields'] = array('a.first_name', 'a.last_name', 'a.email', 'b.username');
		$config['main_table'] = '#__osmembership_subscribers';
		$config['state_vars'] = array(
			'plan_id' => array(0, 'int', true), 
			'filter_order' => array('a.created_date', 'string', true),
			'filter_order_Dir' => array('DESC', 'cmd', 1),
			'published' => array(-1, 'int', true));
		
		parent::__construct($config);
	}

	function _buildQuery()
	{
		$where = $this->_buildContentWhere();
		$orderby = $this->_buildContentOrderBy();						
		$query = 'SELECT a.*, b.username FROM #__osmembership_subscribers  AS a ' 
			 . ' LEFT JOIN #__users AS b ' . ' ON a.user_id = b.id ' . $where .
			 $orderby;
				
		return $query;
	}

	function _buildContentWhereArray()
	{
		$where = parent::_buildContentWhereArray();									
		$where[] = 'a.is_profile = 1';		
		return $where;
	}
	
	function getData()
	{
		if (empty($this->_data))
		{
			$db = JFactory::getDbo();			
			$rows = parent::getData();
			for ($i = 0 , $n = count($rows); $i < $n; $i++)
			{												
				$row = $rows[$i];
				$row->plans = OSMembershipHelper::getSubscriptions($row->id);								
			}						
			$this->_data = $rows;
		}												
		return $this->_data;
	}		
	
	function getTotal()
	{
		if (empty($this->_total))
		{
			$db = $this->getDbo();
			$where = $this->_buildContentWhere();
			$sql = 'SELECT COUNT(*) FROM #__osmembership_subscribers AS a LEFT JOIN  #__users AS b ON a.user_id=b.id ' . $where;
			$db->setQuery($sql);
			$this->_total = $db->loadResult();
		}
	
		return $this->_total;
	}
}