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
 * Membership Pro controller
 *
 * @package		Joomla
 * @subpackage	Membership Pro
 */
class OSMembershipControllerConfiguration extends JControllerLegacy
{	
	/**
	 * Save the category
	 *
	 */
	function save()
	{
		jimport('joomla.filesystem.folder');
		$post = JRequest::get('post', JREQUEST_ALLOWRAW);
		$model = $this->getModel('configuration');
		$ret = $model->store($post);
		if ($ret)
		{
			$msg = JText::_('OSM_CONFIGURATION_SAVED');
		}
		else
		{
			$msg = JText::_('OSM_CONFIGURATION_SAVING_ERROR');
		}
		$this->setRedirect('index.php?option=com_osmembership&view=configuration', $msg);
	}

	/**
	 * Cancel the configuration . Redirect user to pictures list page
	 *
	 */
	function cancel()
	{
		$this->setRedirect('index.php?option=com_osmembership&view=plans');
	}
}