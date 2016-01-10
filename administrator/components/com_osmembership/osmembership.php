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
//Require the controller
jimport('joomla.filesystem.file');
if (!JFactory::getUser()->authorise('core.manage', 'com_osmembership'))
{
    return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}
error_reporting(0);
//Form lib
require_once JPATH_ROOT.'/components/com_osmembership/libraries/rad/bootstrap.php';
//OS Framework
require_once JPATH_ROOT . '/administrator/components/com_osmembership/libraries/defines.php';
require_once JPATH_ROOT . '/administrator/components/com_osmembership/libraries/inflector.php';
require_once JPATH_ROOT . '/administrator/components/com_osmembership/libraries/autoload.php';

if (JLanguageMultilang::isEnabled() && !OSMembershipHelper::isSyncronized())
{
	OSMembershipHelper::setupMultilingual();
}
$command = JRequest::getVar('task', 'display');
// Check for a controller.task command.
if (strpos($command, '.') !== false)
{
	list ($controller, $task) = explode('.', $command);
	$path = JPATH_COMPONENT . '/controllers/' . $controller . '.php';
	if (file_exists($path))
	{
		require_once $path;
		$className = 'OSMembershipController' . ucfirst($controller);
		$controller = new $className();
	}
	else
	{
		//Fallback to default controller
		$controller = new OSController(array('entity_name' => $controller, 'name' => 'OSMembership'));
	}
	JRequest::setVar('task', $task);
}
else
{	
	require_once JPATH_COMPONENT . '/controller.php';	
	$controller = new OSMembershipController();
}
//Ass css file
$document = JFactory::getDocument();
$document->addStyleSheet(JUri::base(true) . '/components/com_osmembership/assets/css/style.css');
if (version_compare(JVERSION, '3.0', 'le'))
{
	OSMembershipHelper::loadBootstrap();
}
// Perform the Request task
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();