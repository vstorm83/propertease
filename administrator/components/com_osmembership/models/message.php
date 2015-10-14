<?php
/**
 * @version		1.6.8
 * @package		Joomla
 * @subpackage	Membership Pro
 * @author  Tuan Pham Ngoc
 * @copyright	Copyright (C) 2013 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die();

/**
 * Membership Pro Component Message Model
 *
 * @package		Joomla
 * @subpackage	Membership Pro
 */
class OSMembershipModelMessage extends JModelLegacy
{

	/**
	 * Containing all config data,  store in an object with key, value
	 *
	 * @var object
	 */
	var $_data = null;
	
	/**
	 * Get configuration data
	 *
	 */
	function getData()
	{
		if (empty($this->_data))
		{
			$db = $this->getDbo();
			$config = new stdClass();
			$sql = 'SELECT * FROM #__osmembership_messages';
			$db->setQuery($sql);
			$rows = $db->loadObjectList();
			if (count($rows))
			{
				for ($i = 0, $n = count($rows); $i < $n; $i++)
				{
					$row = $rows[$i];
					$key = $row->message_key;
					$value = $row->message;
					$config->$key = stripslashes($value);
				}
			}
			else
			{
				$config = new stdClass();
			}
			$this->_data = $config;
		}
		
		return $this->_data;
	}

	/**
	 * Store the configuration data
	 *
	 * @param array $post
	 */
	function store($data)
	{
		$db = $this->getDbo();
		$row = $this->getTable('OSMembership', 'Message');
		$db->truncateTable('#__osmembership_messages');
		foreach ($data as $key => $value)
		{
			$row->id = 0;
			$row->message_key = $key;
			$row->message = $value;
			$row->store();
		}
		
		return true;
	}
}