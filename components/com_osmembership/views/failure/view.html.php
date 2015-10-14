<?php
/**
 * @version        1.6.8
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2014 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
// no direct access
defined( '_JEXEC' ) or die ;

/**
 * HTML View class for OS Membership component
 *
 * @static
 * @package		Joomla
 * @subpackage	Membership Pro
 */
class OSMembershipViewFailure extends JViewLegacy
{
	function display($tpl = null)
	{										
	    $this->setLayout('default') ;	
		$reason =  isset($_SESSION['reason']) ? $_SESSION['reason'] : '';
		if (!$reason) 
		{
			$reason = JRequest::getVar('failReason', '') ;
		}
		
		$this->assignRef('reason', $reason);												
		parent::display($tpl);				
	}
}