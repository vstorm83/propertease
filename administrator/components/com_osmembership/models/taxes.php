<?php
/**
 * @version        1.6.7
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

class OSMembershipModelTaxes extends OSModelList
{

	function __construct($config)
	{
		$config['search_fields'] = array('a.country', 'a.state');
		$config['state_vars']    = array('filter_order' => array('a.country', 'string', 1), 'plan_id' => array(0, 'int', 1), 'country' => array('', 'string', 1), 'vies' => array(-1, 'int', 1));

		parent::__construct($config);
	}

	function _buildQuery()
	{
		$where   = $this->_buildContentWhere();
		$orderby = $this->_buildContentOrderBy();
		$query   = ' SELECT a.*, b.title FROM #__osmembership_taxes  AS a '
			. ' LEFT JOIN #__osmembership_plans AS b ON a.plan_id = b.id '
			. $where
			. $orderby;

		return $query;
	}

	function _buildContentWhereArray()
	{
		$state = $this->getState();
		$where = parent::_buildContentWhereArray();
		if ($state->plan_id > 0)
		{
			$where[] = ' a.plan_id=' . $state->plan_id;
		}
		if ($state->country)
		{
			$where[] = ' a.country=' . $this->getDbo()->quote($state->country);
		}

		if ($state->vies != -1)
		{
			$where[] = ' a.vies=' . $state->vies;
		}

		return $where;
	}
}