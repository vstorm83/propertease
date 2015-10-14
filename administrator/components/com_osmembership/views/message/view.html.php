<?php
/**
 * @version		1.6.8
 * @package		Joomla
 * @subpackage	Membership Pro
 * @author  Tuan Pham Ngoc
 * @copyright	Copyright (C) 2013 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

/**
 * HTML View class for Membership Pro component
 *
 * @static
 * @package		Joomla
 * @subpackage	Membership Pro
 */
class OsMembershipViewMessage extends JViewLegacy
{

	function display($tpl = null)
	{	
		$languages = OSMembershipHelper::getLanguages();	
		$item = $this->get('Data');				
		$this->item = $item;
		$this->languages = $languages;
																													
		parent::display($tpl);
	}
}