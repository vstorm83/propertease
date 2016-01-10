<?php
/**
 * @version		1.6.8
 * @package		Joomla
 * @subpackage	Membership Pro
 * @author  Tuan Pham Ngoc
 * @copyright	Copyright (C) 2012 - 2014 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined( '_JEXEC' ) or die ;

/**
 * HTML View class for the Membership Pro component
 *
 * @package		Joomla
 * @subpackage	Membership Pro
 */
class OSMembershipViewCancel extends JViewLegacy
{
	function display($tpl = null)
	{		
	    $this->setLayout('default') ;		
		$message = OSMembershipHelper::getConfigValue('cancel_message') ;		
		$this->assignRef('message', $message);				
		parent::display($tpl);				
	}
}