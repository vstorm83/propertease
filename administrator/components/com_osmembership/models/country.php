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

/**
 * OSemmbership Component Category Model
 *
 * @package		Joomla
 * @subpackage	Membership Pro
 */
class OSMembershipModelCountry extends OSModel
{
	function store(&$data)
	{
		if ($data['id'])
		{
			$isNew = false;
		}
		else
		{
			$isNew = true;
		}
		parent::store($data);
		if ($isNew)
		{
			//Update country ID
			$db = $this->getDbo();
			$query = $db->getQuery(true);
			$query->update('#__osmembership_countries')
				->set('country_id=id')
				->where('id='.(int) $data['id']);
			$db->setQuery($query);
			$db->execute();
		}

		return true;
	}
}