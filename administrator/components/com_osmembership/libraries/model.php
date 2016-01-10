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

/**
 * Basic Model class to implement Generic function
 * @author Tuan Pham Ngoc
 *
 */
class OSModel extends JModelLegacy
{

	/**
	 * Entity ID
	 *
	 * @var int
	 */
	var $_id = null;

	/**
	 * Entity data
	 *
	 * @var array
	 */
	var $_data = null;

	/**
	 * Table name where the object stored
	 * @var 
	 */
	var $tableName = null;

	/**
	 * Filename where the table defined
	 * @var string
	 */
	var $tableFileName = null;

	/**
	 * Constructor
	 *
	 * @since 1.5
	 */
	function __construct($config = array())
	{
		parent::__construct();
		
		if (isset($config['table_filename']))
		{
			$this->tableFileName = $config['table_filename'];
		}
		else
		{
			$this->getTableFilename();
		}
		
		if (isset($config['table_name']))
		{
			$this->tableName = $config['table_name'];
		}
		else
		{
			$this->tableName = $this->_db->getPrefix() . strtolower(OSF_TABLE_PREFIX . '_' . OSInflector::pluralize($this->getName()));
		}
		
		$array = JRequest::getVar('cid', array(0), '', 'array');
		$edit = JRequest::getVar('edit', true);
		if ($edit)
		{
			$this->setId((int) $array[0]);
		}			
	}

	/**
	 * Method to set the category identifier
	 *
	 * @access	public
	 * @param	int category identifier
	 */
	function setId($id)
	{
		// Set category id and wipe data
		$this->_id = $id;
		$this->_data = null;
	}

	function setTableName($table)
	{
		$this->tableName = $table;
	}

	/**
	 * Method to get an object data
	 *
	 * @since 1.5
	 */
	function getData()
	{
		if (empty($this->_data))
		{
			if ($this->_id)
				$this->_loadData();
			else
				$this->_initData();
		}
		return $this->_data;
	}

	/**
	 * Method to store a category
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function store(&$data)
	{
		$row = $this->getTable($this->tableFileName, $this->getName());
		if ($data['id'])
		{
			$row->load($data['id']);
		}
        if (isset($data['alias']) && empty($data['alias']))
        {
            $data['alias'] = JApplication::stringURLSafe($data['title']);
        }
		if (!$row->bind($data))
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		if (!$row->check())
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		if (!$row->id && property_exists($row, 'ordering'))
		{
			$row->ordering = $row->getNextOrder($this->getWhereNextOrdering());
		}
		
		if (!$row->store())
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		$data['id'] = $row->id;
		
		return true;
	}

	/**
	 * Method to remove categories
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function delete($cid = array())
	{
		if (count($cid))
		{
			$cids = implode(',', $cid);
			$sql = 'DELETE FROM ' . $this->tableName . ' WHERE id IN (' . $cids . ')';
			$this->_db->setQuery($sql);
			if (!$this->_db->query())
				return false;
		}
		return true;
	}

	/**
	 * Load the data
	 *
	 */
	function _loadData()
	{
		$sql = 'SELECT * FROM ' . $this->tableName . ' WHERE id=' . $this->_id;
		$this->_db->setQuery($sql);
		$this->_data = $this->_db->loadObject();
	}

	/**
	 * Init Category data
	 *
	 */
	function _initData()
	{
		$row = $this->getTable($this->tableFileName, $this->getName());
		$this->_data = $row;
	}

	/**
	 * Publish the selected categories
	 *
	 * @param array $cid
	 * @return boolean
	 */
	function publish($cid, $state)
	{
		if (count($cid))
		{
			$cids = implode(',', $cid);
			$sql = 'UPDATE ' . $this->tableName . ' SET published = ' . $state . ' WHERE id IN (' . $cids . ')';
			$this->_db->setQuery($sql);
			if (!$this->_db->query())
				return false;
		}
		return true;
	}

	/**
	 * Save the order of entities
	 *
	 * @param array $cid
	 * @param array $order
	 */
	function saveOrder($cid, $order)
	{
		$row = $this->getTable($this->tableFileName, $this->getName());
		$groupings = array();
		// update ordering values
		for ($i = 0; $i < count($cid); $i++)
		{
			$row->load((int) $cid[$i]);
			// track parents
            if (property_exists($row, 'parent'))
            {
                $groupings[] = $row->parent;
            }
			if ($row->ordering != $order[$i])
			{
				$row->ordering = $order[$i];
				if (!$row->store())
				{
					$this->setError($this->_db->getErrorMsg());
					return false;
				}
			}
		}
		// execute updateOrder for each parent group
		$groupings = array_unique($groupings);
		foreach ($groupings as $group)
		{
			$row->reorder('parent = ' . (int) $group);
		}
		return true;
	}

	/**
	 * Change ordering of a category
	 *
	 */
	function move($direction)
	{
		$row = JTable::getInstance($this->tableFileName, $this->getName());
		$row->load($this->_id);
		if (!$row->move($direction))
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		return true;
	}

	/**
	 * Copy an entity
	 *
	 */
	function copy($id)
	{
		$rowOld = JTable::getInstance($this->tableFileName, $this->name);
		$row = JTable::getInstance($this->tableFileName, $this->name);
		$rowOld->load($id);
		$data = JArrayHelper::fromObject($rowOld);
		$data['id'] = 0;
		$data['title'] = $data['title'] . ' Copy';
		$row->bind($data);
		$row->check();
		if (property_exists($row, 'ordering'))
		{
			$row->ordering = $row->getNextOrder($this->getWhereNextOrdering());
		}
		$row->store();
		
		return $row->id;
	}

	function getWhereNextOrdering()
	{
		return '';
	}

	function getTableFilename()
	{
		$r = null;
		if (empty($this->tableFileName))
		{
			if (preg_match('/(.*)Model/i', get_class($this), $r))
			{
				$this->tableFileName = strtolower($r[1]);
			}
		}
		
		return $this->tableFileName;
	}
}