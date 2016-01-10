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

class OSMembershipModelSubscribers extends OSModelList
{

	function __construct($config)
	{
		$config['search_fields'] = array('a.first_name', 'a.last_name', 'a.email', 'c.username');
		$config['state_vars'] = array(
			'plan_id' => array(0, 'int', true),
			'subscription_type' => array(0, 'int', true),
			'filter_order' => array('a.created_date', 'string', true),
			'filter_order_Dir' => array('DESC', 'cmd', 1),
			'published' => array(-1, 'int', true));
		
		parent::__construct($config);
	}

	function _buildQuery()
	{
		$where = $this->_buildContentWhere();
		$orderby = $this->_buildContentOrderBy();
		$query = 'SELECT a.*, b.title AS plan_title, b.lifetime_membership, c.username AS username FROM #__osmembership_subscribers  AS a ' .
			 ' LEFT JOIN #__osmembership_plans AS b ' . ' ON a.plan_id = b.id ' . ' LEFT JOIN #__users AS c ' . ' ON a.user_id = c.id ' . $where .
			 $orderby;
		
		return $query;
	}

	function _buildContentWhereArray()
	{
		$where = parent::_buildContentWhereArray();
		$state = $this->getState();
		if ($state->plan_id)
		{
			$where[] = ' a.plan_id = ' . $state->plan_id;
		}

		if ($state->published != -1)
		{
			$where[] = ' a.published = ' . $state->published;
		}

		switch($state->subscription_type)
		{
			case 1:
				$where[] = ' a.act = "subscribe"' ;
				break;
			case 2:
				$where[] = ' a.act = "renew"' ;
				break;
			case 3:
				$where[] = ' a.act = "upgrade"' ;
				break;
		}
		
		return $where;
	}
	
	function getTotal()
	{
		if (empty($this->_total))
		{
			$db = $this->getDbo();
			$where = $this->_buildContentWhere();
			$sql = 'SELECT COUNT(*) FROM #__osmembership_subscribers AS a LEFT JOIN  #__users AS c ON a.user_id=c.id ' . $where;
			$db->setQuery($sql);
			$this->_total = $db->loadResult();
		}
		
		return $this->_total;
	}

}