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
defined('_JEXEC') or die;
//Require the controller

jimport('joomla.filesystem.file');
require_once JPATH_ROOT . '/components/com_osmembership/libraries/rad/bootstrap.php';
//OS Framework
require_once JPATH_ROOT . '/administrator/components/com_osmembership/libraries/defines.php';
require_once JPATH_ROOT . '/administrator/components/com_osmembership/libraries/inflector.php';
require_once JPATH_ROOT . '/administrator/components/com_osmembership/libraries/autoload.php';
require_once JPATH_COMPONENT . '/controller.php';
$controller = new OSMembershipController();
// Perform the Request task
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();