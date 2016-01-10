<?php
/**
 * @version		1.0
 * @package		Joomla
 * @subpackage	OSFramework
 * @author  Tuan Pham Ngoc
 * @copyright	Copyright (C) 2012 - 2014 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die();

class OSModelList extends JModelLegacy
{

	/**
	 * Context, using for store permanent information 
	 * @var string
	 */
	var $context = null;

	/**
	 * Search fields using for searching
	 * @var string
	 */
	var $searchFields = null;

	/**
	 * 
	 * @var main database table which we will query data from
	 */
	var $mainTable = null;

	/**
	 * Entitires data array
	 *
	 * @var array
	 */
	var $_data = null;

	/**
	 * Pagination object
	 *
	 * @var object
	 */
	var $_pagination = null;

	/**
	 * Constructor.
	 *
	 * @param    array    An optional associative array of configuration settings.
	 * @see        JController
	 * @since    1.6
	 */
	public function __construct($config = array())
	{
		parent::__construct();
		
		$mainframe = JFactory::getApplication();
		$baseStateVars = array(
			'search' => array('', 'string', 1),  //default, type, store in session
			'filter_order' => array('a.ordering', 'cmd', 1), 
			'filter_order_Dir' => array('', 'cmd', 1), 
			'filter_state' => array('', 'cmd', 1));
		if (isset($config['state_vars']))
			$config['state_vars'] = array_merge($baseStateVars, $config['state_vars']);
		else
			$config['state_vars'] = $baseStateVars;
		
		if (isset($config['search_fields']))
			$this->searchFields = $config['search_fields'];
		else
			$this->searchFields = 'a.title';
		
		if (isset($config['main_table']))
		{
			$this->mainTable = $config['main_table'];
		}
		else
		{
			$this->getMainTable();
		}
		
		if (isset($config['context']))
		{
			$this->context = $config['context'];
		}
		else
		{
			$this->getContext();
		}
		
		if ($config['state_vars'])
		{
			foreach ($config['state_vars'] as $name => $values)
			{
				$storeInSession = isset($values[2]) ? $values[2] : 0;
				$type = isset($values[1]) ? $values[1] : null;
				$default = isset($values[0]) ? $values[0] : null;
				if ($storeInSession)
				{
					$value = $mainframe->getUserStateFromRequest($this->context . '.' . $name, $name, $default, $type);
				}
				else
				{
					$value = JRequest::getVar($name, $default, 'default', $type);
				}
				$this->setState($name, $value);
			}
		}
		// Get the pagination request variables
		$limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');		
		$limitstart = $mainframe->getUserStateFromRequest($this->context . '.limitstart', 'limitstart', 0, 'int');
		
		// In case limit has been changed, adjust limitstart accordingly
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
	}

	/**
	 * Method to get categories data
	 *
	 * @access public
	 * @return array
	 */
	function getData()
	{
		// Lets load the content if it doesn't already exist
		

		if (empty($this->_data))
		{
			$query = $this->_buildQuery();
			$this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
		}
		
		return $this->_data;
	}

	/**
	 * Get total entities
	 *
	 * @return int
	 */
	function getTotal()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_total))
		{
			$where = $this->_buildContentWhere();
			$sql = 'SELECT COUNT(*) FROM ' . $this->mainTable . ' AS a ' . $where;
			$this->_db->setQuery($sql);
			$this->_total = $this->_db->loadResult();
		}
		
		return $this->_total;
	}

	/**
	 * Method to get a pagination object
	 *
	 * @access public
	 * @return integer
	 */
	function getPagination()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_pagination))
		{
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit'));
		}
		
		return $this->_pagination;
	}

	/**
	 * Basic build Query function. The child class must override it if it is necessary
	 *
	 * @return string
	 */
	function _buildQuery()
	{
		// Get the WHERE and ORDER BY clauses for the query
		$where = $this->_buildContentWhere();
		$orderby = $this->_buildContentOrderBy();
		$query = 'SELECT a.* FROM ' . $this->mainTable . ' AS a ' . $where . $orderby;
		
		return $query;
	}

	/**
	 * Build order by clause for the select command
	 *
	 * @return string order by clause
	 */
	function _buildContentOrderBy()
	{
		$state = $this->getState();
		
		$orderby = ' ORDER BY ' . $state->filter_order . ' ' . $state->filter_order_Dir;
		
		return $orderby;
	}

	/**
	 * Build an where clause array	 
	 * @return array
	 */
	function _buildContentWhereArray()
	{
		$db = JFactory::getDbo();
		$state = $this->getState();
		$where = array();
		if ($state->filter_state == 'P')
			$where[] = ' a.published=1 ';
		elseif ($state->filter_state == 'U')
			$where[] = ' a.published = 0';
		
		if ($state->search)
		{
			$search = $db->Quote('%' . $db->escape($state->search, true) . '%', false);
			if (is_array($this->searchFields))
			{
				$whereOr = array();
				foreach ($this->searchFields as $searchField)
				{
					$whereOr[] = " LOWER($searchField) LIKE " . $search;
				}
				$where[] = ' (' . implode(' OR ', $whereOr) . ') ';
			}
			else
			{
				$where[] = 'LOWER(' . $this->searchFields . ') LIKE ' . $db->Quote('%' . $db->escape($state->search, true) . '%', false);
			}
		}
		
		return $where;
	}

	/**
	 * Build the where clause
	 *
	 * @return string
	 */
	function _buildContentWhere()
	{
		$where = $this->_buildContentWhereArray();
		
		return (count($where) ? ' WHERE ' . implode(' AND ', $where) : '');
	}

	function getContext()
	{
		if (empty($this->context))
		{
			$r = null;
			if (preg_match('/(.*)Model/i', get_class($this), $r))
			{
				$component = $r[1];
				$this->context = $component . '.' . $this->getName();
			}
		}
		
		return $this->context;
	}

	/**
	 * Get name of database table use for query 
	 * @return string The main database table
	 */
	function getMainTable()
	{
		if (empty($this->mainTable))
		{
			$this->mainTable = $this->_db->getPrefix() . strtolower(OSF_TABLE_PREFIX . '_' . $this->getName());
		}
		
		return $this->mainTable;
	}
}