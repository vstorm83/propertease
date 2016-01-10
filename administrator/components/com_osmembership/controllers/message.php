<?php
/**
 * @version		1.6.8
 * @package		Joomla
 * @subpackage	Membership Pro
 * @author  Tuan Pham Ngoc
 * @copyright	Copyright (C) 2013 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined( '_JEXEC' ) or die ;

/**
 * Membership Pro controller
 *
 * @package		Joomla
 * @subpackage	Membership Pro
 */
class OSMembershipControllerMessage extends JControllerLegacy
{
	/**
	 * Constructor function
	 *
	 * @param array $config
	 */
	function __construct($config = array())
	{
		parent::__construct($config);		
		
	}		
	/**
	 * Save the category
	 *
	 */
	function save() {		
		$post = JRequest::get('post' , JREQUEST_ALLOWRAW);
		unset($post['option']) ;
		unset($post['task']) ;
		$model =  $this->getModel('message') ;		
		$ret =  $model->store($post);				
		if ($ret) {
			$msg = JText::_('OSM_MESSAGES_SAVED') ;
		} else {
			$msg = JText::_('OMS_MESSAGES_SAVING_ERROR');
		}						
		$this->setRedirect('index.php?option=com_osmembership&view=message', $msg);
	}		
	/**
	 * Cancel the configuration . Redirect user to subscribers list page
	 *
	 */
	function cancel() {
		$this->setRedirect('index.php?option=com_osmembership&view=subscribers');
	}		
}