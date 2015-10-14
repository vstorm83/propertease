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
class OSMembershipModelPlan extends OSModel
{

	function __construct($config)
	{
		
		parent::__construct($config);
		
		$this->setId(JRequest::getInt('id'));
	}

	function _loadData()
	{
		$fieldSuffix = OSMembershipHelper::getFieldSuffix();
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('*, title'.$fieldSuffix.' AS title, description'.$fieldSuffix.' AS description')
		->from('#__osmembership_plans')
		->where('id=' . $this->_id);
		$db->setQuery($query);
		$this->_data = $db->loadObject();		
	}
}