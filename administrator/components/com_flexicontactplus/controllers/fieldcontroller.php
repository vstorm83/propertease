<?php
/********************************************************************
Product		: Flexicontact Plus
Date		: 05 August 2014
Copyright	: Les Arbres Design 2010-2014
Contact		: http://www.lesarbresdesign.info
Licence		: GNU General Public License
*********************************************************************/
defined('_JEXEC') or die('Restricted Access');

class FlexicontactplusControllerField extends JControllerLegacy
{
function __construct()
{
	parent::__construct();
	$this->registerTask('save', 'apply');
}

function display($cachable = false, $urlparams = false)
{
	FCP_Admin::addSubMenu('config');

	$app = JFactory::getApplication();
	$config_id = $app->getUserState(LAFC_COMPONENT."_current_config",0);
	$config_model = $this->getModel('config');
	$config_names = $config_model->getListNames();				// Multiple configurations?
	$config_data = $config_model->getOneById($config_id);		// gets the default config if $config_id = 0
	$config_count = $config_model->countConfig();
	
	$view = $this->getView('config_field_list', 'html');
	$view->config_id = $config_id;
	$view->config_data = $config_data;
	$view->config_names = $config_names;
	$view->config_count = $config_count;
	$view->display();
}

function add()
{
	FCP_Admin::addSubMenu('config');

	$app = JFactory::getApplication();
	$config_id = $app->getUserState(LAFC_COMPONENT."_current_config",0);
	$config_model = $this->getModel('config');
	$config_data = $config_model->getOneById($config_id);
	$config_count = $config_model->countConfig();
	$new_flag = 1;
	$field_index = -1;
	
	$view = $this->getView('config_field', 'html');
	$view->config_data = $config_data;
	$field = $config_model->initField();
	$view->field = $field;
	$view->field_index = $field_index;
	$view->new_flag = $new_flag;
	$view->config_count = $config_count;
	$view->display();
}

function edit()
{
	FCP_Admin::addSubMenu('config');

	$app = JFactory::getApplication();
	$config_id = $app->getUserState(LAFC_COMPONENT."_current_config",0);
	$config_model = $this->getModel('config');
	$config_data = $config_model->getOneById($config_id);
	$config_count = $config_model->countConfig();
	$new_flag = 0;
	
	$view = $this->getView('config_field', 'html');
	$view->config_data = $config_data;
	$jinput = JFactory::getApplication()->input;
	$cids = $jinput->get('cid', array(0), 'ARRAY');
	$field_index = (int) $cids[0];
	$view->field = $config_data->config_data->all_fields[$field_index];
	$view->field_index = $field_index;
	$view->new_flag = $new_flag;
	$view->config_count = $config_count;
	$view->display();
}

function apply()													// save or apply a new or edited field
{
	$app = JFactory::getApplication();
	$config_id = $app->getUserState(LAFC_COMPONENT."_current_config",0);
	$config_model = $this->getModel('config');
	$config_model->getOneById($config_id);
	$config_count = $config_model->countConfig();
	$config_model->getPostData('config_field');						// get the field data from the post data
	if ($config_model->store('config_field'))							// store() also does validation
		{
		$jinput = JFactory::getApplication()->input;
		$task = $jinput->get('task', '', 'STRING');								// 'save_field' or 'apply_field'
		
		// Check White List if field = file attachment! 		
		if ((empty($config_model->_data->config_data->white_list)) AND ($config_model->_data->config_data->all_fields[$config_model->_data->field_index]->field_type == LAFC_FIELD_ATTACHMENT))
			$app->enqueueMessage(JText::_('COM_FLEXICONTACT_NO_WHITE_LIST'), 'notice');
			
		if ($task == 'apply')
			$this->setRedirect(LAFC_COMPONENT_LINK."&controller=field&task=edit&cid[]=".$config_model->_data->field_index);
		else
			$this->setRedirect(LAFC_COMPONENT_LINK."&controller=field&task=display");
		}
	else
		{
		FCP_Admin::addSubMenu('config');
		$view = $this->getView('config_field', 'html');
		$view->config_data = $config_model->_data;
		$view->config_count = $config_count;
		$view->new_flag = $config_model->_data->new_flag;
		$view->field = $config_model->_data->config_data->all_fields[$config_model->_data->field_index];
		$view->field_index = $config_model->_data->field_index;
		$view->display();
		}
}

function remove()
{
	$app = JFactory::getApplication();
	$config_id = $app->getUserState(LAFC_COMPONENT."_current_config",0);
	$jinput = JFactory::getApplication()->input;
	$cids = $jinput->get('cid', array(0), 'ARRAY');
	$config_model = $this->getModel('config');
	$config_model->delete_fields($config_id, $cids);
	$this->setRedirect(LAFC_COMPONENT_LINK."&controller=field&task=display");
}

function orderup()
{
	$app = JFactory::getApplication();
	$config_id = $app->getUserState(LAFC_COMPONENT."_current_config",0);
	$jinput = JFactory::getApplication()->input;
	$cids = $jinput->get('cid', array(0), 'ARRAY');
	$field_index  = (int) $cids[0];
	$config_model = $this->getModel('config');
	$config_model->move_field($config_id, $field_index, -1);
	$this->setRedirect(LAFC_COMPONENT_LINK."&controller=field&task=display");
}

function orderdown()
{
	$app = JFactory::getApplication();
	$config_id = $app->getUserState(LAFC_COMPONENT."_current_config",0);
	$jinput = JFactory::getApplication()->input;
	$cids = $jinput->get('cid', array(0), 'ARRAY');
	$field_index  = (int) $cids[0];
	$config_model = $this->getModel('config');
	$config_model->move_field($config_id, $field_index, 1);
	$this->setRedirect(LAFC_COMPONENT_LINK."&controller=field&task=display");
}

function saveorder()
{
	$app = JFactory::getApplication();
	$config_id = $app->getUserState(LAFC_COMPONENT."_current_config",0);
	$jinput = JFactory::getApplication()->input;
	$order = $jinput->get( 'order', array(), 'ARRAY' );
	JArrayHelper::toInteger($order);
	$config_model = $this->getModel('config');
	$config_model->save_field_order($config_id, $order);
	$this->setRedirect(LAFC_COMPONENT_LINK."&controller=field&task=display");
}

function add_default_fields()
{
	$app = JFactory::getApplication();
	$config_id = $app->getUserState(LAFC_COMPONENT."_current_config",0);
	$config_model = $this->getModel('config');
	$config_model->add_default_fields($config_id);
	$this->setRedirect(LAFC_COMPONENT_LINK."&controller=field&task=display");
}

function cancel()
{
	$jinput = JFactory::getApplication()->input;
	$view_name = $jinput->get('view', '', 'STRING');
	if ($view_name == 'config_field')
		$this->setRedirect(LAFC_COMPONENT_LINK."&controller=field&task=display");
	else
		$this->setRedirect( LAFC_COMPONENT_LINK."&controller=menu&task=display");
}


}
