<?php
/********************************************************************
Product		: Flexicontact Plus
Date		: 04 January 2014
Copyright	: Les Arbres Design 2010-2014
Contact		: http://www.lesarbresdesign.info
Licence		: GNU General Public License
*********************************************************************/

defined('_JEXEC') or die('Restricted Access');

require_once JPATH_COMPONENT.'/helpers/flexi_common_helper.php';
require_once JPATH_COMPONENT.'/helpers/db_helper.php';

// Check for ACL access

if (!JFactory::getUser()->authorise('core.manage', 'com_flexicontactplus'))
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));

// Pull in the helper files

require_once JPATH_COMPONENT.'/helpers/flexi_admin_helper.php';
require_once JPATH_COMPONENT.'/helpers/trace_helper.php';

if (file_exists(JPATH_ROOT.'/LA.php'))
	require_once JPATH_ROOT.'/LA.php';

// load our css

$document = JFactory::getDocument();
$document->addStyleSheet(JURI::base().'components/'.LAFC_COMPONENT.'/assets/'.LAFC_COMPONENT.'.css');

JHtml::_('behavior.framework');	// load the Joomla Javascript framework

$jinput = JFactory::getApplication()->input;
$controller = $jinput->get('controller','menu', 'STRING');	// default to the config menu controller
$task = $jinput->get('task','display', 'STRING');			// default to the config menu controller

// create an instance of the controller and tell it to execute $task

$classname = 'FlexicontactplusController'.$controller;
require_once JPATH_COMPONENT.'/controllers/'.$controller.'controller.php';

$controller = new $classname();
$controller->execute($task);
$controller->redirect();

