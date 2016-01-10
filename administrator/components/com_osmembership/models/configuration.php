<?php
/**
 * @version		1.6.8
 * @package		Joomla
 * @subpackage	Membership Pro
 * @author  Tuan Pham Ngoc
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die();

/**
 * Membership Pro Component Configuration Model
 *
 * @package		Joomla
 * @subpackage	Membership Pro
 */
class OSMembershipModelConfiguration extends JModelLegacy
{

	/**
	 * Containing all config data,  store in an object with key, value
	 *
	 * @var object
	 */
	var $_data = null;

	function __construct()
	{
		parent::__construct();
	}

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
			$sql = 'SELECT config_key, config_value FROM #__osmembership_configs';
			$db->setQuery($sql);
			$rows = $db->loadObjectList();
			if (count($rows))
			{
				for ($i = 0, $n = count($rows); $i < $n; $i++)
				{
					$row = $rows[$i];
					$key = $row->config_key;
					$value = $row->config_value;
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
		$db->truncateTable('#__osmembership_configs');
		$row = $this->getTable('OSMembership', 'Config');
		foreach ($data as $key => $value)
		{
			$row->id = 0;
			if (is_array($value))
			{
				$value = implode(',', $value);
			}				
			$row->config_key = $key;
			$row->config_value = $value;
			$row->store();
		}
        $query = $db->getQuery(true);
        if ($data['create_account_when_membership_active'])
        {
            //Need to activate the account creation plugin
            $query->update('#__extensions')
                ->set('`enabled` = 1')
				->set('`ordering` = -1')
                ->where('`element`="account" AND `folder`="osmembership"');
            $db->setQuery($query);
            $db->execute();
        }
        else
        {
            //We should disable this plugin

            $query->update('#__extensions')
                ->set('`enabled` = 0')
                ->where('`element`="account" AND `folder`="osmembership"');
            $db->setQuery($query);
            $db->execute();
        }
		return true;
	}
}