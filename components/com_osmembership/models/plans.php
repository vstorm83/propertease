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
class OSMembershipModelPlans extends OSModelList
{	
	/**
	 * 
	 * @see OSModelList::_buildContentWhereArray()
	 */
	function _buildContentWhereArray()
	{
		$user = JFactory::getUser();
		$where = array();
		$categoryId = JRequest::getInt('id');
		$where[] = ' a.published = 1 ';
		if ($categoryId)
		{
			$where[] = 'a.category_id=' . $categoryId;
		}
		$where[] = ' a.access IN ('.implode(',', $user->getAuthorisedViewLevels()).')' ;
		
		return $where;
	}
	
	function _buildQuery()
	{
		$fieldSuffix = OSMembershipHelper::getFieldSuffix();
		// Get the WHERE and ORDER BY clauses for the query
		$where = $this->_buildContentWhere();
		$query = 'SELECT a.*, a.title'.$fieldSuffix.' AS title, a.description'.$fieldSuffix.' AS description, a.short_description'.$fieldSuffix.' AS short_description  FROM #__osmembership_plans AS a'			
			. $where				
			.' ORDER BY a.ordering ';		
	
		return $query;
	}	
}