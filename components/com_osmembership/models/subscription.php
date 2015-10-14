<?php
/**
 * @version		1.6.8
 * @package		Joomla
 * @subpackage	Membership Pro
 * @author  Tuan Pham Ngoc
 * @copyright	Copyright (C) 2012 - 2014 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die();

class OSMembershipModelSubscription extends OSModel
{

	function __construct($config)
	{
		$config['table_name'] = '#__osmembership_subscribers';
		parent::__construct($config);
		
		$this->setId(JRequest::getInt('id'));
	}

	/**
	 * Load the record from database
	 * @see OSModel::_loadData()
	 */
	function _loadData()
	{
		$sql = 'SELECT a.*, b.lifetime_membership, b.title AS plan_title, b.enable_renewal, b.recurring_subscription, DATEDIFF(NOW(), from_date) AS presence FROM #__osmembership_subscribers AS a  ' .
			 ' LEFT JOIN #__osmembership_plans AS b ' . ' ON a.plan_id = b.id ' . ' WHERE a.id = ' . $this->_id;
		$this->_db->setQuery($sql);
		
		$this->_data = $this->_db->loadObject();
	}
}