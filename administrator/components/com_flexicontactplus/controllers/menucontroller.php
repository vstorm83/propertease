<?php
/********************************************************************
Product		: Flexicontact Plus
Date		: 11 November 2014
Copyright	: Les Arbres Design 2010-2014
Contact		: http://www.lesarbresdesign.info
Licence		: GNU General Public License
*********************************************************************/
defined('_JEXEC') or die('Restricted Access');

class FlexicontactplusControllerMenu extends JControllerLegacy
{
function __construct()
{
	parent::__construct();
	$this->registerTask('save', 'apply');
	$this->registerTask('save_css', 'apply_css');
	$this->registerTask('save_field', 'apply_field');
}

function display($cachable = false, $urlparams = false)
{
	$config_model = $this->getModel('config');
	$jinput = JFactory::getApplication()->input;
	$view_name = $jinput->get('view','config_menu', 'STRING');
	$menu = 'config';
	if ($view_name == 'config_css')
		$menu = 'css';
	if ($view_name == 'config_images')
		$menu = 'images';

	FCP_Admin::addSubMenu($menu);

	$param1 = $jinput->get('param1','', 'STRING');

// if we came here from somewhere else, get the current config from the session
// if we came here as a result of the config selector being changed, store the current config in the session

	$current_config = $jinput->get('current_config', 0, 'INT');

	$app = JFactory::getApplication();
	if ($current_config == 0)
		{
		$current_config = $app->getUserState(LAFC_COMPONENT."_current_config",0);
		if ($current_config == 0)									// if there is no current config
			{
			$current_config = $config_model->defaultConfigId();		// get the default config id
			$app->setUserState(LAFC_COMPONENT."_current_config", $current_config);
			}
		}
	else
		$app->setUserState(LAFC_COMPONENT."_current_config", $current_config);

	$config_model = $this->getModel('config');
	$config_names = $config_model->getListNames();				// Multiple configurations?
	$config_data = $config_model->getOneById($current_config);	// gets the default config if $current_config = 0
	if ($config_data === false)
		$config_data = $config_model->getOneById(0);			// if not found, get the default config
	if ($config_data === false)
		return;

	if ($menu == 'config')
		FCP_Admin::environment_check();

	$config_count = $config_model->countConfig();

	$view = $this->getView($view_name, 'html');
	$view->current_config = $current_config;
	$view->config_data = $config_data;
	$view->param1 = $param1;
	$view->config_names = $config_names;
	$view->config_count = $config_count;
	
	$view->display();
}

function apply()										// save changes to config
{
	$app = JFactory::getApplication();
	$config_id = $app->getUserState(LAFC_COMPONENT."_current_config",0);
	$jinput = JFactory::getApplication()->input;
	$task = $jinput->get('task', '', 'STRING');					// 'save' or 'apply'
	$view_name = $jinput->get('view', '', 'STRING');				// could be one of several

	$param1 = $jinput->get('param1','', 'STRING');			// 'user_template', 'admin_template', 'top_text', 'bottom_text', etc
	$config_model = $this->getModel('config');
		
	$config_model->getOneById($config_id);				// Get all the data (DB) for the current configuration
	$config_count = $config_model->countConfig();

	$config_model->getPostData($view_name, $param1);	// Overwrite with edited data

	$stored = $config_model->store($view_name);			// The view specifies validation performed by the store() function
	
	if ($stored)
		{
		FCP_trace::trace("stored current_config = $current_config, data: ".print_r($config_model->_data,true));
		if ($task == 'apply')
			$this->setRedirect(LAFC_COMPONENT_LINK."&controller=menu&task=display&view=$view_name&param1=$param1",JText::_('COM_FLEXICONTACT_SAVED'));
		else
			$this->setRedirect(LAFC_COMPONENT_LINK."&controller=menu&task=display",JText::_('COM_FLEXICONTACT_SAVED'));
		}
	else
		{
		FCP_Admin::addSubMenu('config');
		$config_names = $config_model->getListNames();				// Multiple configurations?
		$view = $this->getView($view_name, 'html');
		$view->config_id = $config_id;
		$view->config_data = $config_model->_data;
		$view->config_count = $config_count;
		$view->param1 = $param1;
		$view->config_names = $config_names;
		$view->display();
		}
}   

function cancel()
{
	$this->setRedirect(LAFC_COMPONENT_LINK."&controller=menu&task=display");
}

//--------------------------------------------------------------------------------------------------
// Help
//--------------------------------------------------------------------------------------------------

function help()
{
	FCP_Admin::addSubMenu('help');
	$app = JFactory::getApplication();
	$config_id = $app->getUserState(LAFC_COMPONENT."_current_config",0);
	$config_model = $this->getModel('config');
	$config_names = $config_model->getListNames();				// Multiple configurations?
	$config_data = $config_model->getOneById($config_id);		// gets the default config if $config_id = 0
	FCP_Admin::environment_check();

	$view = $this->getView('help', 'html');
	$view->config_id = $config_id;
	$view->config_data = $config_data;
	$view->config_names = $config_names;
	$view->display();
}
	
//--------------------------------------------------------------------------------------------------
// Images
//--------------------------------------------------------------------------------------------------

function delete_image()
{
	$jinput = JFactory::getApplication()->input;
	$cids = $jinput->get('cid', array(0), 'ARRAY');
	$msg = '';
	
	if (file_exists(JPATH_ROOT.'/demo_mode.txt'))		// used on our demo site
		$msg = "Images can not be deleted in demo mode";
	else
		{
		foreach ($cids as $file_name)
			@unlink(LAFC_SITE_IMAGES_PATH.'/'.$file_name);
		}
	$this->setRedirect(LAFC_COMPONENT_LINK."&controller=menu&task=display&view=config_images", $msg);
}

//--------------------------------------------------------------------------------------------------
// Css
//--------------------------------------------------------------------------------------------------

function apply_css()
{
	$jinput = JFactory::getApplication()->input;
	$task = $jinput->get('task', '', 'STRING');				// 'save_css' or 'apply_css'
	$css_file_name =$jinput->get('css_file_name', '', 'STRING');
	$css_contents = $_POST['css_contents'];
	$path = LAFC_SITE_ASSETS_PATH.'/';
	
	if (strlen($css_contents) == 0)
		$this->setRedirect(LAFC_COMPONENT_LINK."&controller=menu&task=display");

	if (file_exists(JPATH_ROOT.'/demo_mode.txt'))		// used on our demo site
		$msg = "Css file is not saved in demo mode";
	else
		{
		$length_written = file_put_contents ($path.$css_file_name, $css_contents);
		if ($length_written == 0)
			$msg = JText::_('COM_FLEXICONTACT_NOT_SAVED');
		else
			$msg = JText::_('COM_FLEXICONTACT_SAVED');
		}
		
	if ($task == 'apply_css')
		$this->setRedirect(LAFC_COMPONENT_LINK."&controller=menu&task=display&view=config_css&css_file_name=$css_file_name",$msg);
	else
		$this->setRedirect(LAFC_COMPONENT_LINK."&controller=menu&task=display",$msg);
}   

function trace_on()
{
	$app = JFactory::getApplication();
	$config_id = $app->getUserState(LAFC_COMPONENT."_current_config",0);
	$config_model = $this->getModel('config');
	FCP_trace::init_trace($config_model, $config_id);
	$this->setRedirect(LAFC_COMPONENT_LINK.'&controller=menu&task=help');
}

function trace_off()
{
	FCP_trace::delete_trace_file();
	$this->setRedirect(LAFC_COMPONENT_LINK.'&controller=menu&task=help');
}


}
