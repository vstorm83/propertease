<?php
/**
 * @version		1.6.8
 * @package		Joomla
 * @subpackage	Membership Pro
 * @author  Tuan Pham Ngoc
 * @copyright	Copyright (C) 2010 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die();

/**
 * Membership Pro Component Dashboard Model
 *
 * @package		Joomla
 * @subpackage	Membership pro
 */
class OSMembershipModelDashboard extends JModelLegacy
{

	function __construct()
	{
		parent::__construct();
	}

	function getSubscriptions()
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('a.*, b.title AS plan_title, c.username AS username')
			->from('#__osmembership_subscribers  AS a')
			->leftJoin('#__osmembership_plans AS b ON a.plan_id=b.id')
			->leftJoin('#__users AS c ON a.user_id=c.id')
			->order('a.created_date DESC');
		$db->setQuery($query, 0, 10);
				
		return $db->loadObjectList();
	}
}