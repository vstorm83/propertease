<?php
/********************************************************************
Product		: FlexicontactPlus
Date		: 14 August 2014
Copyright	: Les Arbres Design 2010-2014
Contact		: http://www.lesarbresdesign.info
Licence		: GNU General Public License
*********************************************************************/
defined('_JEXEC') or die('Restricted Access');

class FlexicontactplusController extends JControllerLegacy
{

function __construct()
{
	parent::__construct();
	$this->registerTask('send1', 'send');
	$this->registerTask('send2', 'send');
	$this->_app = JFactory::getApplication();
}

//---------------------------------------------------------------------------------------------
// Display the contact form
// Used by the component view only
//
function display($cachable = false, $urlparams = false)
{
	$this->_app->setUserState(LAFC_COMPONENT."_secure_captcha_passed", "N");
	$this->_app->setUserState(LAFC_COMPONENT."_recaptcha_passed", "N");

// get the config name from the menu parameters

	$menu_params = $this->_app->getParams();
	$config_name = $menu_params->get('config_name', '');
	
// if there is a config name in the get data, it overrides the menu parameters

	$jinput = JFactory::getApplication()->input;
	$config_name = $jinput->get('config_name',$config_name, 'STRING');
	
// Load the configuration

	$this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.'/models');
	$config_model = $this->getModel('config');
	$config_data = $config_model->getConfigData($config_name);
	if ($config_data === false)
		{
		FCP_trace::trace("No configuration found");	
		echo "No configuration found.";
		return;
		}
	FCP_trace::trace("Controller display() requested config [$config_name] loaded config [$config_data->id] [$config_data->name] [$config_data->language]");	
	FCP_trace::trace(" JURI::current(): ".JURI::current());


// save the page_title and url_path in case the %V_PAGE_TITLE% or %V_URL_PATH% variables are used later

	$page_title = self::get_page_title($menu_params);
	$this->_app->setUserState(LAFC_COMPONENT."_page_title", $page_title);
	$uri = JURI::getInstance();
	$url_path = ltrim($uri->getPath(),'/\\');
	$this->_app->setUserState(LAFC_COMPONENT."_url_path", $url_path) ;
	FCP_trace::trace(" Page Title [$page_title] Url_path [$url_path]");

// initialise data for the view and call it

	$data_model = $this->getModel('data');
	$data_model->init_data($config_data);

	$view = $this->getView('contact','html');
	$view->config_data = $config_data;
	$view->menu_params = $menu_params;
	$view->data = $data_model->data;
	$view->display();
}

//---------------------------------------------------------------------------------------------
// Handle a validation request for a single field
// Called via Ajax from the browser regardless of whether the form was drawn by the component or the plugin
//
function validate_field()
{
// get the config data

	$jinput = JFactory::getApplication()->input;
	$this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.'/models');
	$config_id = $jinput->get('config_id', 0, 'INT');
	$config_model = $this->getModel('config');
	$config_row = $config_model->getOneById($config_id);
	if ($config_row === false)
		{
		FCP_trace::trace("Config $config_id not found");	
		echo "Config $config_id not found";
		return;
		}
	FCP_trace::trace("Controller validate_field() loaded config $config_id: ".$config_row->name);	
	$config_data = $config_row->config_data;

// validate the field

	$data_model = $this->getModel('data');
	$data_model->get_data($config_data);
	
	$response_array = array();
	$data_model->validate_fields($config_row->config_data, $response_array);
	
	$json_response = json_encode($response_array);
	FCP_trace::trace(" Sending: ".$json_response);	
	
	$document = JFactory::getDocument();
	$document->setMimeEncoding('application/json');
	echo $json_response;
}

//---------------------------------------------------------------------------------------------
// Called when the user clicks the send button
// Called via Ajax from the browser regardless of whether the form was drawn by the component or the plugin
// A "send1" includes form data and information about file attachments but not the actual files
// A "send2" only occurs if there are file attachments. It includes all form data and the actual files
//
function send()
{
// code to simulate an error for testing the Ajax error handling
//	header('Location: http://www._nowhere_.com/');
//	echo "Failure test";
//	return;

// get the config data

	$this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.'/models');
	$jinput = JFactory::getApplication()->input;
	$task = $jinput->get('task', '', 'STRING');
	$config_id = $jinput->get('config_id', 0, 'INT');
	$config_model = $this->getModel('config');
	$config_row = $config_model->getOneById($config_id);
	if ($config_row === false)							// this really should not happen
		{
		FCP_trace::trace("Config $config_id not found");	
		$response = array();
		$response['fcp_err_top'] = "Config $config_id not found";
		$response_array[] = $response;
		$json_response = json_encode($response_array);
		$document = JFactory::getDocument();
		$document->setMimeEncoding('application/json');
		echo $json_response;
		return;
		}
	FCP_trace::trace("Controller send() loaded config $config_id: ".$config_row->name);	
	$config_data = $config_row->config_data;

// validate the fields
// if there are any errors, send back the error response

	$data_model = $this->getModel('data');
	$data_model->get_data($config_data);

	$response_array = array();
	$valid = $data_model->validate_all($config_data, $response_array);

// if validate_all() returns -1 we need to kill the session

	if ($valid === -1)
		{
		$session = JFactory::getSession();
		$session->destroy();
		$response = array();
		$response['hide'] = 'fcp_top';					// remove the top text
		$response_array[] = $response;
		$response = array();
		$response['hide'] = 'fcp_bottom';				// remove the bottom text
		$response_array[] = $response;
		$json_response = json_encode($response_array);
		$document = JFactory::getDocument();
		$document->setMimeEncoding('application/json');
		FCP_trace::trace(" Sending request to re-start session: ".$json_response);	
		echo $json_response;
		return;
		}
	
// if there were errors on the form, return the response_array to the browser

	if (!$valid)
		{
		$response = array();
		$response['fcp_spinner'] = $this->make_error($config_data, JText::_('COM_FLEXICONTACT_MESSAGE_NOT_SENT'));
		$response['e_error'] = 'fcp_spinner';
		$response_array[] = $response;
		$json_response = json_encode($response_array);
		$document = JFactory::getDocument();
		$document->setMimeEncoding('application/json');
		FCP_trace::trace(" Sending error report: ".$json_response);	
		echo $json_response;
		return;
		}	

// if this was a send1 task and there are non-blank file attachments, we need to request the actual files

	if ($task == 'send1')
		foreach ($config_data->all_fields as $field_index => $field)
			{
			$field_id = sprintf('field%03d',$field_index);
			if (($field->field_type == LAFC_FIELD_ATTACHMENT) and (!empty($data_model->data[$field_id])))
				{
				$response = array();
				$response['send_files'] = 'Yes';				// request the files
				$response_array[] = $response;
				$json_response = json_encode($response_array);
				$document = JFactory::getDocument();
				$document->setMimeEncoding('application/json');
				FCP_trace::trace(" Sending request for files: ".$json_response);	
				echo $json_response;
				return;
				}
			}

// all ok, send the emails	

	$email_model = $this->getModel('email');
	$email_model->get_files($config_data);			// get any files that were attached (must do this before init_data())
	$email_model->init_data($config_data);			// get all other data
	$email_status = $email_model->sendEmail($config_data);
	$email_model->delete_files();
	$email_errors = ob_get_contents();				// phpmailer has a nasty habit of echo'ing errors
	ob_clean();	
	if (strlen($email_errors) > 0)
		FCP_trace::trace("Phpmailer echoed: [$email_errors]");
	if ($config_data->logging)
		{
		$log_model = $this->getModel('log');
		$email_model->data->config_name = $config_data->name;
		$email_model->data->config_lang = $config_data->language;
		$log_model->store($email_model->data);
		FCP_trace::trace(" Message logged");	
		}
		
// reset captcha flags in the session

	if ($config_data->recaptcha_theme > 0)
		$this->_app->setUserState(LAFC_COMPONENT."_recaptcha_passed", "N");
	if ($config_data->secure_captcha)
		$this->_app->setUserState(LAFC_COMPONENT."_secure_captcha_passed", "N");

// if email sending failed, show a message at the top of the page

	if ($email_status != '1')					// if send failed, show status at the top of the form
		{
		$failed_message = JText::_('COM_FLEXICONTACT_MESSAGE_FAILED').': '.$email_status.' '.$email_errors;
		$response = array();
		$response['fcp_wrapper'] = '<div style="padding:20px">'.$failed_message.'</div>';
		$response_array = array();						// clear out any other responses
		$response_array[] = $response;
		$response = array();
		$response['hide'] = 'fcp_top';					// remove the top text
		$response_array[] = $response;
		$response = array();
		$response['hide'] = 'fcp_bottom';				// remove the bottom text
		$response_array[] = $response;
		$json_response = json_encode($response_array);
		$document = JFactory::getDocument();
		$document->setMimeEncoding('application/json');
		FCP_trace::trace(" Sending failure report: ".$json_response);	
		echo $json_response;
		return;
		}
		
// here if the email was sent ok
// if we have a confirmation link, tell the Javascript to go there

	if ($config_data->confirm_link)
		{
		$response = array();
		$response['redirect'] = $config_data->confirm_link;
		$response_array = array();						// clear out any other responses
		$response_array[] = $response;
		$json_response = json_encode($response_array);
		$document = JFactory::getDocument();
		$document->setMimeEncoding('application/json');
		FCP_trace::trace(" Redirecting to confirm link:: ".$json_response);	
		echo $json_response;
		return;
		}
		
// Merge the confirmation page and send it

	$message = $email_model->email_merge($config_data->confirm_text, $config_data);
	$response = array();
	$response_array = array();						// clear out any other responses
	$response['fcp_wrapper'] = $message;
	$response_array[] = $response;
	$response = array();
	$response['hide'] = 'fcp_top';					// remove the top text
	$response_array[] = $response;
	$response = array();
	$response['hide'] = 'fcp_bottom';				// remove the bottom text
	$response_array[] = $response;
	$json_response = json_encode($response_array);
	FCP_trace::trace("Sending confirmation text: $json_response");
	$document = JFactory::getDocument();
	$document->setMimeEncoding('application/json');
	echo $json_response;
}

//---------------------------------------------------------------------------------------------
// Serve a picture captcha image
//
function image()
{
	require_once(LAFC_HELPER_PATH.'/flexi_captcha.php');
	Flexi_captcha::show_image();
}

//---------------------------------------------------------------------------------------------
// Serve a secure captcha image
//
function captcha()
{
	require_once(LAFC_HELPER_PATH.'/secure_captcha.php');
	Secure_captcha::show_image();
}

function get_page_title($menu_params)
{
	$page_title = $menu_params->get('page_hdr','');		// try Basic Options
	if (!empty($page_title))
		return $page_title;
	$page_title = $menu_params->get('page_heading',''); // Page Display Options for Joomla > 1.5
	if (!empty($page_title))
		return $page_title;
	$page_title = $menu_params->get('page_title','');	// Page Display Options for Joomla 1.5
	if (!empty($page_title))
		return $page_title;
}

//-------------------------------------------------------------------------------
// Make an error message
//
function make_error($config_data, $msg)
{
	return '<span class="hasTooltip" title="'.$msg.'"></span><span class="fcp_err">'.$msg.'</span>';
}

}

