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

class OSMembershipModelFields extends OSModelList
{

	function __construct($config)
	{
		$config['search_fields'] = array('a.name', 'a.title');
		$config['state_vars'] = array('show_core_field' => array(0, 'int', true), 'plan_id' => array(0, 'int', true));
		
		parent::__construct($config);
	}
}