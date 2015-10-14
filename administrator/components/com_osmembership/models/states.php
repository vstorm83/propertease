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

class OSMembershipModelStates extends OSModelList
{
	function __construct($config)
	{
		$config['search_fields'] = array('a.state_name', 'b.name');
		$config['state_vars'] = array(
			'filter_country_id' => array(0, 'int', true), 
			'filter_order' => array('a.state_name', 'string', true));
		parent::__construct($config);
	}
	
	function _buildQuery()
	{
		$where = $this->_buildContentWhere();
		$orderby = $this->_buildContentOrderBy();
		$query =  ' SELECT a.*, b.name AS country_name FROM #__osmembership_states  AS a ' 
				. ' LEFT JOIN #__osmembership_countries AS b ON a.country_id = b.id ' 
				. $where 
				. $orderby;
		
		return $query;
	}

	function _buildContentWhereArray()
	{
		$where = parent::_buildContentWhereArray();
		$state = $this->getState();
		if ($state->filter_country_id)
		{
			$where[] = ' a.country_id = ' . $state->filter_country_id;
		}
		return $where;
	}
	
	function getTotal()
	{
		if (empty($this->_total))
		{
			$db = $this->getDbo();
			$where = $this->_buildContentWhere();
			$sql = 	  ' SELECT COUNT(a.id) FROM #__osmembership_states AS a ' 
					. ' LEFT JOIN #__osmembership_countries AS b ON a.country_id = b.id ' 
					. $where;
			$db->setQuery($sql);
			$this->_total = $db->loadResult();
		}
		
		return $this->_total;
	}
}