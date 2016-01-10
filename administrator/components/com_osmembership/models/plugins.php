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

class OSMembershipModelPlugins extends OSModelList
{

	function __construct($config)
	{
		$config['state_vars'] = array('plugin_type' => array(-1, 'int', true));
		
		parent::__construct($config);
	}

	function _buildContentWhereArray()
	{
		$state = $this->getState();
		$where = parent::_buildContentWhereArray();
		if ($state->plugin_type != -1)
			$where[] = ' a.plugin_type=' . $state->plugin_type;
		
		return $where;
	}
}