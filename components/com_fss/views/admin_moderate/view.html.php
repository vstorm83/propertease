<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'comments.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'admin_helper.php');

class FssViewAdmin_Moderate extends FSSView
{
	function display($tpl = NULL)
	{
		if (!FSS_Permission::CanModerate())
			return FSS_Admin_Helper::NoPerm();
		
		$this->comments = new FSS_Comments(null,null);
		if ($this->comments->Process())
			return;
			
		parent::display();	
	}
}

