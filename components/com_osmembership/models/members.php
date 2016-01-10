<?php
/**
 * @version        1.6.7
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2014 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

class OSMembershipModelMembers extends OSModelList
{
	function __construct($config)
	{
		$config['search_fields'] = array('a.first_name', 'a.last_name', 'a.email', 'b.title');
		$config['main_table']    = '#__osmembership_subscribers';
		$config['state_vars']    = array(
			'id'               => array(0, 'int', true),
			'search' => array('', 'string', 1),
			'filter_order'     => array('a.created_date', 'string', true),
			'filter_order_Dir' => array('DESC', 'cmd', 1)
		);

		parent::__construct($config);
	}

	function _buildQuery()
	{
		$where       = $this->_buildContentWhere();
		$orderby     = $this->_buildContentOrderBy();
		$fieldSuffix = OSMembershipHelper::getFieldSuffix();
		$query       = 'SELECT a.*, b.title' . $fieldSuffix . ' AS plan_title FROM #__osmembership_subscribers  AS a '
			. ' LEFT JOIN #__osmembership_plans AS b ' . ' ON a.plan_id = b.id ' . $where .
			$orderby;

		return $query;
	}

	function _buildContentWhereArray()
	{
		$where   = parent::_buildContentWhereArray();
		$where[] = 'a.is_profile = 1';
		if ($this->getState('id'))
		{
			$where[] = ' a.plan_id = ' . (int) $this->state->get('id');
		}
		$where[] = ' a.id IN (SELECT DISTINCT profile_id FROM #__osmembership_subscribers WHERE published = 1)';
		return $where;
	}

	public function getFieldsData()
	{
		$fieldsData = array();
		$rows       = $this->getData();
		$fields     = OSMembershipHelper::getProfileFields($this->getState('id'), false);
		if (count($rows) && count($fields))
		{
			$db  = $this->getDbo();
			$ids = array();
			foreach ($rows as $row)
			{
				$ids[] = $row->id;
			}
			$sql = 'SELECT * FROM #__osmembership_field_value WHERE subscriber_id IN (' . implode(',', $ids) . ')';
			$db->setQuery($sql);
			$fieldValues = $db->loadObjectList();
			if (count($fieldValues))
			{
				foreach ($fieldValues as $fieldValue)
				{
					$fieldsData[$fieldValue->subscriber_id][$fieldValue->field_id] = $fieldValue->field_value;
				}
			}
		}

		return $fieldsData;
	}

	function getTotal()
	{
		if (empty($this->_total))
		{
			$db    = $this->getDbo();
			$where = $this->_buildContentWhere();
			$sql   = 'SELECT COUNT(*) FROM #__osmembership_subscribers AS a LEFT JOIN  #__osmembership_plans AS b ON a.plan_id=b.id ' . $where;
			$db->setQuery($sql);
			$this->_total = $db->loadResult();
		}

		return $this->_total;
	}
}