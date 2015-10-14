<?php
/**
 * @version		1.6.8
 * @package		Joomla
 * @subpackage	Membership Pro
 * @author  Tuan Pham Ngoc
* @copyright	Copyright (C) 2012 - 2014 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die();
class OSMembershipModelSubscriptions extends OSModelList
{

	function __construct($config)
	{
		$config['main_table'] = '#__osmembership_subscribers';
		
		parent::__construct($config);
	}

	function _buildQuery()
	{
		$where = $this->_buildContentWhere();
		$orderby = $this->_buildContentOrderBy();
		$query = 'SELECT a.*, b.title AS plan_title, b.lifetime_membership, b.enable_renewal, b.recurring_subscription	FROM #__osmembership_subscribers  AS a ' .
			 ' LEFT JOIN #__osmembership_plans AS b ' . ' ON a.plan_id = b.id ' . $where . ' ORDER BY a.id DESC ';
			return $query;
	}
	
	function _buildContentWhereArray()
	{
		$user = JFactory::getUser();
		$where = array();
		$where[] = ' a.user_id= ' . $user->get('id');
		
		return $where;
	}
}