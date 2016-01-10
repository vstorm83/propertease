<?php
/**
 * @version		1.6.8
 * @package		Joomla
 * @subpackage	Membership Pro
 * @author  Tuan Pham Ngoc
 * @copyright	Copyright (C) 2012 - 2014 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
$librariesPath = dirname(__FILE__);
$classes = array(
	'OSViewList' => $librariesPath . '/viewlist.php', 
	'OSViewForm' => $librariesPath . '/viewform.php', 
	'OSModelList' => $librariesPath . '/modellist.php', 
	'OSModel' => $librariesPath . '/model.php', 
	'OSController' => $librariesPath . '/controller.php', 
	'OSMembershipHelper' => JPATH_ROOT . '/components/com_osmembership/helper/helper.php',
    'OSMembershipHelperHtml' => JPATH_ROOT . '/components/com_osmembership/helper/html.php',
    'OSMembershipHelperJquery' => JPATH_ROOT . '/components/com_osmembership/helper/jquery.php',
    'OSMembershipHelperRoute' => JPATH_ROOT . '/components/com_osmembership/helper/route.php',
    'OSMembershipHelperEuvat' => JPATH_ROOT . '/components/com_osmembership/helper/euvat.php',
	'os_payments' => JPATH_ROOT . '/components/com_osmembership/plugins/os_payments.php', 
	'os_payment' => JPATH_ROOT . '/components/com_osmembership/plugins/os_payment.php');

foreach ($classes as $className => $path)
{
	JLoader::register($className, $path);
}