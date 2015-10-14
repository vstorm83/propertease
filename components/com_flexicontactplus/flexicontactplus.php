<?php
/********************************************************************
Product		: FlexicontactPlus
Date		: 24 July 2014
Copyright	: Les Arbres Design 2010-2014
Contact		: http://www.lesarbresdesign.info
Licence		: GNU General Public License
*********************************************************************/
defined('_JEXEC') or die('Restricted Access');

// Pull in the helper file

require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/flexi_common_helper.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/db_helper.php';

if (file_exists(JPATH_ROOT.'/demo_mode.txt'))		// used on our demo site
	define("LAFC_DEMO_MODE", "1");
	
require_once LAFC_HELPER_PATH.'/trace_helper.php';
FCP_trace::trace_entry_point(true);
if (FCP_trace::tracing())
	ini_set("display_errors","1");

require_once( JPATH_COMPONENT.'/controller.php' );
$controller = new FlexicontactplusController();

$jinput = JFactory::getApplication()->input;
$task = $jinput->get('task', '', 'STRING');
$controller->execute($task);

$controller->redirect();

?>
