<?php
/**
 * @version		1.6.8
 * @package		Joomla
 * @subpackage	Membership Pro
 * @author  Tuan Pham Ngoc
 * @copyright	Copyright (C) 2012 - 2014 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined( '_JEXEC' ) or die ;
class OSMembershipModelCategories extends OSModelList
{

	function __construct()
	{
		parent::__construct();

	}
		
	function _buildContentWhereArray()
	{
		$user = JFactory::getUser();
		$where = array();
		$where[] = ' a.published = 1 ';
		$where[] = ' a.access IN ('.implode(',', $user->getAuthorisedViewLevels()).')' ;
				
		return $where;
	}
	
	function _buildQuery()
	{
		$fieldSuffix = OSMembershipHelper::getFieldSuffix();
		// Get the WHERE and ORDER BY clauses for the query
		$where = $this->_buildContentWhere();
		$query = 'SELECT a.*, a.title'.$fieldSuffix.' AS title, a.description'.$fieldSuffix.' AS description, COUNT(b.id) AS total_plans FROM #__osmembership_categories AS a'
			.' LEFT JOIN #__osmembership_plans AS b '
			.' ON (a.id=b.category_id AND b.published=1)'
			.$where
			.' GROUP BY a.id '
			.' ORDER BY a.ordering '		
		;
				
		return $query;
	}	
}