<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view' );
jimport('joomla.filesystem.file');
jimport('fsj_core.admin.update');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'comments.php');

class fssViewcomments extends FSSView
{
	function display($tpl = null)
	{
		$ident = JRequest::getVar('identifier');
		$item = JRequest::getVar('itemid');

		$this->comments = new FSS_Comments($ident,$item);
		if (JRequest::getVar('opt_show_posted_message_only'))
			$this->comments->opt_show_posted_message_only = JRequest::getVar('opt_show_posted_message_only');
		$this->comments->Process();
		exit;
	}
}
