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
 * HTML View class for Membership Pro component
 *
 * @static
 * @package		Joomla
 * @subpackage	Membership Pro
 */
class OSMembershipViewPlugin extends OSViewForm
{

	function _buildListArray(&$lists, $item)
	{
		$registry = new JRegistry();
		$registry->loadString($item->params);
		$data = new stdClass();
		$data->params = $registry->toArray();
		$form = JForm::getInstance('osmembership', JPATH_ROOT . '/components/com_osmembership/plugins/' . $item->name . '.xml', array(), false, '//config');
		$form->bind($data);
		$this->form = $form;
		
		return true;
	}
}