<?php
/********************************************************************
Product		: Flexicontact Plus
Date		: 08 January 2014
Copyright	: Les Arbres Design 2010-2014
Contact		: http://www.lesarbresdesign.info
Licence		: GNU General Public License
*********************************************************************/
defined('_JEXEC') or die('Restricted Access');

class FlexicontactplusControllerMulticonfig extends JControllerLegacy
{
function __construct()
{
	parent::__construct();
	$this->registerTask('save', 'apply');
	$this->registerTask('copy', 'edit');
}

function display($cachable = false, $urlparams = false)
{
	FCP_Admin::addSubMenu('config');

	$config_model = $this->getModel('config');
	$config_list = $config_model->getList();
	$count_unique = $config_model->countConfig(true);
		
	$view = $this->getView('config_list', 'html');
	$view->config_list = $config_list;
	$view->count_unique = $count_unique;
	$view->display();
}

function add()											// Add  a new configuration
{
	FCP_Admin::addSubMenu('config');

	$config_model = $this->getModel('config');
	$config_data  = $config_model->initData();
	$config_names = $config_model->getListNames();
	$config_count = $config_model->countConfig();
	$new_flag = 1;
	$copy_flag = 0;

	$view = $this->getView('config_edit', 'html');
	$view->config_data = $config_data;
	$view->config_names = $config_names;
	$view->new_flag = $new_flag;
	$view->copy_flag = $copy_flag;
	$view->config_count = $config_count;
	$view->display();
}

function edit()											// Edit Configuration Name, Description and Language
{
	FCP_Admin::addSubMenu('config');

	$jinput = JFactory::getApplication()->input;
	$config_model = $this->getModel('config');
	$cids = $jinput->get('cid', array(0), 'ARRAY');
	$config_id = (int) $cids[0];
	$config_data = $config_model->getOneById($config_id);
	$config_count = $config_model->countConfig();
	$new_flag = 0;
	$copy_flag = 0;
	
	$task = $jinput->get('task', '', 'STRING');
	if ($task == 'copy')
		{		
 		if ($this->_allLang($config_data->name))		// Has this config already been copied to all languages?
			{
			$this->setRedirect(LAFC_COMPONENT_LINK."&controller=multiconfig&task=display", JText::_('COM_FLEXICONTACT_CONFIG_NO_COPY'));
			return;
			}
		else
			{
			$copy_flag = 1;
			$new_flag = 1;
			}
		}
	
	$view = $this->getView('config_edit', 'html');
	$view->config_data = $config_data;
	$view->config_count = $config_count;
	$view->copy_flag = $copy_flag;
	$view->new_flag = $new_flag;
	$view->display();
}

function apply()
{
	$jinput = JFactory::getApplication()->input;
	$config_id = $jinput->get('config_id', 0, 'INT');		// for a new config, this is the chosen "base config"
	$task = $jinput->get('task', '', 'STRING');				// 'save' or 'apply'

	$config_model = $this->getModel('config');
	$config_model->getOneById($config_id);					// Get the current (or new) config
	$config_model->getPostData('config_edit');
	$config_count = $config_model->countConfig();
	
	if ($config_model->_data->new_flag)
		$config_model->_data->id = 0;						// Force a new record to be created
	$stored = $config_model->store('config_edit');			// The view specifies validation in the store function
	
	if ($stored)
		{
		$stored_config_id = $config_model->_data->id;		// Get the stored config id
		$app = JFactory::getApplication();
		$app->setUserState(LAFC_COMPONENT."_current_config", $stored_config_id);	// make it the new current config
		if ($task == 'apply')
			$this->setRedirect(LAFC_COMPONENT_LINK."&controller=multiconfig&task=edit&cid[]=".$config_model->_data->id, JText::_('COM_FLEXICONTACT_SAVED'));
		else		
			$this->setRedirect(LAFC_COMPONENT_LINK."&controller=multiconfig&task=display", JText::_('COM_FLEXICONTACT_SAVED'));
		}
	else
		{
		FCP_Admin::addSubMenu('config');
		$view = $this->getView('config_edit', 'html');
		$view->config_data = $config_model->_data;
		$view->config_count = $config_count;
		$view->copy_flag = $config_model->_data->copy_flag;
		$view->new_flag = $config_model->_data->new_flag;

		if ($config_model->_data->new_flag == 1)
			{
			$config_model->_data->id = $config_id;		// restore the user's "base config" selection
			$config_names = $config_model->getListNames();
			$view->config_names = $config_names;
			$view->new_flag = $config_model->_data->new_flag;
			}
		$view->display();
		}
}   

function remove()							// delete selected configs
{
	$config_model = $this->getModel('config');
	$config_model->delete_config();

// if the current config got deleted, set the current config to the default config

	$jinput = JFactory::getApplication()->input;
	$cids = $jinput->get('cid', array(0), 'ARRAY');
	$app = JFactory::getApplication();
	$current_config = $app->getUserState(LAFC_COMPONENT."_current_config",0);
	if (in_array($current_config,$cids))
		$app->setUserState(LAFC_COMPONENT."_current_config", 0);

	$this->setRedirect( LAFC_COMPONENT_LINK."&controller=multiconfig&task=display");
}

function publish()							// publish selected configs
{
	$config_model = $this->getModel('config');
	$config_model->publish_config(1);
	$this->setRedirect( LAFC_COMPONENT_LINK."&controller=multiconfig&task=display");
}

function unpublish()						// unpublish selected configs
{
	$config_model = $this->getModel('config');
	$config_model->publish_config(0);
	$this->setRedirect( LAFC_COMPONENT_LINK."&controller=multiconfig&task=display");
}

function cancel()
{
	$jinput = JFactory::getApplication()->input;
	$view_name = $jinput->get('view', '', 'STRING');
	if ($view_name == 'config_edit')
		$this->setRedirect( LAFC_COMPONENT_LINK."&controller=multiconfig&task=display");
	else
		$this->setRedirect( LAFC_COMPONENT_LINK."&controller=menu&task=display");
}

//------------------------------------------------------------------------------
// Checks whether all languages have been set up for a given configuration
// Returns true if all have been set up
// Else returns false
//
function _allLang($name)
{
	$langs = FCP_Admin::make_lang_list();
	$config_model = $this->getModel('config');
	
	foreach ($langs as $key => $value)
		{
		$ret = $config_model->_exists($name, $key);			// Config does not exist in this language
		if (!$ret)
			return false;
		}
	
	return true;
		
}

}
