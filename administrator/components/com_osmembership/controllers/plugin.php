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
 * OSMembership Plugin controller
 *
 * @package		Joomla
 * @subpackage	Membership Pro
 */
class OSMembershipControllerPlugin extends OSController
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

	function install()
	{
		$model = & $this->getModel('plugin');
		$ret = $model->install();
		if ($ret)
		{
			$msg = JText::_('OSM_PLUGIN_INSTALLED');
		}
		else
		{
			$msg = JRequest::getVar('msg', 'OSM_PLUGIN_INSTALL_ERROR');
		}
		$this->setRedirect('index.php?option=com_osmembership&view=plugins', $msg);
	}
}