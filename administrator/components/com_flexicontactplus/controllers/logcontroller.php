<?php
/********************************************************************
Product		: Flexicontact Plus
Date		: 08 January 2014
Copyright	: Les Arbres Design 2010-2014
Contact		: http://www.lesarbresdesign.info
Licence		: GNU General Public License
*********************************************************************/
defined('_JEXEC') or die('Restricted Access');

class FlexicontactplusControllerLog extends JControllerLegacy
{
function __construct()
{
	parent::__construct();
}

function display($cachable = false, $urlparams = false)
{
	FCP_Admin::addSubMenu('log');
	$log_model = $this->getModel('log');
	$log_list = $log_model->getList();
	$config_list = $log_model->getConfigArray();
	$pagination = $log_model->getPagination();
	$import_model = $this->getModel('import');
	$free_log_info = $import_model->free_log_info();

	$view = $this->getView('log_list', 'html');
	$view->log_list = $log_list;
	$view->pagination = $pagination;
	$view->free_log_info = $free_log_info;
	$view->config_list = $config_list;
	$view->display();
}

function log_detail()
{
	FCP_Admin::addSubMenu('log');
	$jinput = JFactory::getApplication()->input;
	$log_id = $jinput->get('log_id', '', 'STRING');
	$log_model = $this->getModel('log');
	$log_data = $log_model->getOne($log_id);

	$view = $this->getView('log_detail', 'html');
	$view->log_data = $log_data;
	$view->display();
}

function delete_log()
{
	$log_model = $this->getModel('log');
	$jinput = JFactory::getApplication()->input;
	$cids = $jinput->get('cid', array(0), 'ARRAY');
	foreach ($cids as $log_id)
		$log_model->delete($log_id);
	$this->setRedirect(LAFC_COMPONENT_LINK."&controller=log&task=display");
}

function stats()
{
	FCP_Admin::addSubMenu('log');
	$log_model = $this->getModel('log');
	$title = '';
	$chart_data = $log_model->getLogChart($title);
	$num_rows = $log_model->count_rows();
	$view = $this->getView('log_chart', 'html');
	$view->title = $title;
	$view->data = $chart_data;
	$view->num_rows = $num_rows;
	$view->display();
}

function import()
{
	FCP_Admin::addSubMenu('log');
	$import_model = $this->getModel('import');
	$free_log_info = $import_model->free_log_info();
	$count_imported_rows = $import_model->count_imported_rows();

	$view = $this->getView('log_import', 'html');
	$view->free_log_info = $free_log_info;
	$view->count_imported_rows = $count_imported_rows;
	$view->display();
}

function import_confirmed()
{
	$import_model = $this->getModel('import');
	$return_message = '';
	$ret = $import_model->import($return_message);
	$app = JFactory::getApplication();
	if ($ret)
		$app->enqueueMessage($return_message, 'message');
	else
		$app->enqueueMessage($return_message, 'error');
	$this->display();
}

function download()
{
	$log_model = $this->getModel('log');
	$ret = $log_model->export_csv();
	return;				// we cannot send a page now because we just sent a file

}

function cancel()
{
	$this->setRedirect(LAFC_COMPONENT_LINK."&controller=log&task=display");
}


} // end of Class
