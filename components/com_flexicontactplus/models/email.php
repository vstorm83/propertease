<?php
/********************************************************************
Product		: FlexicontactPlus
Date		: 6 September 2014
Copyright	: Les Arbres Design 2010-2014
Contact		: http://www.lesarbresdesign.info
Licence		: GNU General Public License
*********************************************************************/
defined('_JEXEC') or die('Restricted access');

class FlexicontactplusModelEmail extends JModelLegacy
{
var $_data;
var $_app = null;

function __construct()
{
	parent::__construct();
	$this->_app = JFactory::getApplication();
	$this->data = new stdClass();
}

//--------------------------------------------------------------------------------
// Initialise data for merging with the email templates
// - we setup some extra variables that supplement the field data
//
function init_data($config_data)
{
	$this->data->ip = $this->getIPaddress();
	$this->data->browser_id = $this->getBrowser($this->data->browser_string);
	$this->data->site_url = JURI::root();
	$this->data->site_name = $this->_app->getCfg('sitename');
	$this->data->attached_file = 0;
	
// Get the Get and Post data

	$data = array_merge($_GET, $_POST);			// get both as a single array
	foreach ($data as $key => $value)			// create objects for each field
		{
	    $this->data->$key = rawurldecode(rawurldecode($value));
	    if ((substr($key,0,5) == 'field') and (strlen($key) > 8))	// multiple checkboxes come in as 'fieldnnnmmm'
	    	{
	    	$parent_field_name = substr($key,0,8);					// 'fieldnnn'
	    	$this->data->$parent_field_name = '1';					// create a dummy entry for the parent field
	    	}
	    }

// we now have all the form data in $this->data
// build a few more data items

	$this->data->all_data   = '';				// all data as a single string
	$this->data->other_data = '';				// all data except name, address and subject
	
	foreach ($config_data->all_fields as $field_index => $field)
		{
		$field_id = sprintf('field%03d',$field_index);
		switch ($field->field_type)
			{
			case LAFC_FIELD_FROM_NAME:
				$this->data->from_name = $this->data->$field_id;
				$this->data->all_data .= '['.$field->prompt.'] '.$this->data->from_name.'<br />';
				break;
				
			case LAFC_FIELD_FROM_ADDRESS:
				$this->data->from_email = $this->data->$field_id;
				$this->data->all_data  .= '['.$field->prompt.'] '.$this->data->from_email.'<br />';
				break;
				
			case LAFC_FIELD_SUBJECT:
				if ($field->visible)
					$raw_subject = $this->data->$field_id;
				else
					$raw_subject = $field->default_value;
				$this->data->subject = $this->email_merge($raw_subject, $config_data);
				FCP_trace::trace("Merged subject: ".$this->data->subject);
				$this->data->all_data .= '['.$field->prompt.'] '.$this->data->subject.'<br />';
				break;

			case LAFC_FIELD_RECIPIENT:
				$list_index = $this->data->$field_id;							// get the selection index
				if (!FCP_Common::is_posint($list_index,false))					// should be an integer
					break;
				$list_array = FCP_Common::split_list($field->list_list, ';', $field->delimiter);
				$this->data->recipient_email = $list_array['RIGHT'][$list_index];
				$this->data->recipient_name =  $list_array['LEFT'][$list_index];
				FCP_trace::trace("Overriding recipient from ".$config_data->email_to." to ".$this->data->recipient_email);
				$config_data->email_to = $this->data->recipient_email;			// override the "email_to" in general configuration
				$this->data->all_data   .= '['.$field->prompt.'] '.$this->data->recipient_name.' ('.$this->data->recipient_email.')<br />';
				break;
				
			case LAFC_FIELD_CHECKBOX_M:					// The post data looks like this (example for field 20):
				$text = '';								//   [field0200] => 1
				if (isset($this->data->$field_id))		//   [field0202] => 1
					{									//   [field0204] => 1
					$text = '';							//   (no value is sent if the box is not checked)
					$comma = '';
					$list_array = FCP_Common::split_list($field->list_list, $field->delimiter);
					foreach ($list_array['LEFT'] as $key => $value)
						{
						$child_field_id = $field_id.$key;
						if (isset($this->data->$child_field_id))
							{
							$text .= $comma.$value;
							$comma = ', ';
							}
						}
					$this->data->$field_id = $text;
					}
				$this->data->all_data   .= '['.$field->prompt.'] '.$text.'<br />';
				$this->data->other_data .= '['.$field->prompt.'] '.$text.'<br />';
				break;
			
			case LAFC_FIELD_FIELDSET_START:
			case LAFC_FIELD_FIELDSET_END:
			case LAFC_FIELD_FIXED_TEXT:
				break;						// we don't want these in all_data or other_data
				
			default:
				$this->data->all_data   .= '['.$field->prompt.'] '.$this->get_field_value($config_data, $field_index).'<br />';
				$this->data->other_data .= '['.$field->prompt.'] '.$this->get_field_value($config_data, $field_index).'<br />';
				break;
			}
		}
		
// get the value of the show_copy checkbox, if any

	$jinput = JFactory::getApplication()->input;
	$this->data->show_copy = $jinput->get('show_copy','', 'STRING');

// if there is no subject field, create the subject property as an empty string

	if (!isset($this->data->subject))
		$this->data->subject = '';
		
	FCP_trace::trace("Email model data: ".print_r($this->data,true));
}

// -------------------------------------------------------------------------------
// Retrieve any files that were uploaded
// We re-validate them here, even though the extension and size have already been validated via Ajax
// If a file fails validation here we delete it immediately without reporting an error as this must indicate
// some kind of attack.
//
function get_files($config_data)
{
	$this->files = array();
	if ( (!isset($_FILES)) or (empty($_FILES)) )
		return;
	FCP_trace::trace("Getting Files ..");
		
	$app = JFactory::getApplication();
	$tmp_path = $app->getCfg('tmp_path');								// get the site temp directory from Joomla global configuration
	$white_list_array = explode(',',$config_data->white_list);
	
	foreach ($config_data->all_fields as $field_index => $field)
		{
		if ($field->field_type != LAFC_FIELD_ATTACHMENT)
			continue;
		$field_id = sprintf('field%03d',$field_index);
		FCP_trace::trace(" Processing file for field $field_id");
		if (isset($_FILES[$field_id]))
			{
			FCP_trace::trace(" Filename: ".$_FILES[$field_id]['name']);
			if ($_FILES[$field_id]['error'] != 0)
				{
				FCP_trace::trace("  Error ".$_FILES[$field_id]['error']);
				continue;
				}
			$file_extension = pathinfo($_FILES[$field_id]['name'], PATHINFO_EXTENSION);		// validate extension
			if (!in_array(strtolower($file_extension), $white_list_array))
				{
				FCP_trace::trace("  Discarded, extension: ".$file_extension);
				continue;
				}
			if (move_uploaded_file($_FILES[$field_id]['tmp_name'], $tmp_path.'/'.$_FILES[$field_id]['name']))
				FCP_trace::trace("  Saved to ".$tmp_path.'/'.$_FILES[$field_id]['name']);
			$file_size = filesize($tmp_path.'/'.$_FILES[$field_id]['name']);				// validate file size
			if ($file_size > ($config_data->max_file_size * 1024))
				{
				unlink($tmp_path.'/'.$_FILES[$field_id]['name']);
				FCP_trace::trace("  Deleted, size: ".$file_size);
				continue;
				}
			$this->files[] = $tmp_path.'/'.$_FILES[$field_id]['name'];					// store file names in a separate array
			}
		}
}

// -------------------------------------------------------------------------------
// Retrieve any files that were uploaded
// We re-validate them here, even though the extension and size have already been validated via Ajax
// If a file fails validation here we delete it immediately without reporting an error as this must indicate
// some kind of attack.
//
function delete_files()
{
	foreach ($this->files as $file)
		unlink($file);
}
	
//-------------------------------------------------------------------------------
// Get a field value for a user defined field
// - for most types we can just return the raw post data
// - for a few types we need to do some special processing
//
function get_field_value($config_data, $field_index)
{
	$field_id = sprintf('field%03d',$field_index);					// form the post data field name
	
	if (!isset($config_data->all_fields[$field_index]))
		return '';
		
	$field = $config_data->all_fields[$field_index];				// point to the field configuration
	
	$yes_text = JText::_('JYES');
	$no_text = JText::_('JNO');

	switch ($field->field_type)
		{
		case LAFC_FIELD_CHECKBOX_L:
		case LAFC_FIELD_CHECKBOX_R:
		case LAFC_FIELD_CHECKBOX_H:									// CHECKBOX_M has already been built by init_data()
			if (isset($this->data->$field_id))
				return $yes_text;
			else
				return $no_text;
			break;
			
		case LAFC_FIELD_LIST:
			$list_index = $this->data->$field_id;					// get the selection from the input data
			if (!FCP_Common::is_posint($list_index,false))			// should be an integer
				break;
			$list_array = FCP_Common::split_list($field->list_list, $field->delimiter);
			return $list_array['LEFT'][$list_index];				// only one string for list boxes
			break;

		case LAFC_FIELD_RADIO_V:
		case LAFC_FIELD_RADIO_H:
			$list_index = $this->data->$field_id;					// get the selection from the input data
			if (!FCP_Common::is_posint($list_index,false))
				return '';
			$list_array = FCP_Common::split_list($field->list_list, ';', $field->delimiter);
			return $list_array['RIGHT'][$list_index];				// return the right hand string
			break;

		case LAFC_FIELD_RECIPIENT:
			return $this->data->recipient_name;
			break;
			
		case LAFC_FIELD_TEXTAREA:
			return nl2br(htmlspecialchars($this->data->$field_id));			

		case LAFC_FIELD_ATTACHMENT:
			$pathname = str_replace('\\','/',$this->data->$field_id);	// otherwise basename does not remove C:\fakepath\ in Unix
			return basename($pathname);
			
		default:
			return $this->data->$field_id;
		}
}

//-------------------------------------------------------------------------------
// Resolve a single email variable
//
function email_resolve($config_data, $variable)
{
// field prompts

	if (strncmp($variable, LAFC_T_FIELD_PROMPT, LAFC_T_OFFSET_P_XX) == 0)	// e.g: %V_FIELD_PROMPT_03%
		{
		$field_number = substr($variable, LAFC_T_OFFSET_P_XX, 2);			// 1-based field number
		if (!FCP_Common::is_posint($field_number,false))
			return '';
		$field_index = $field_number - 1;									// 0-based array index
		if (!isset($config_data->all_fields[$field_index]->prompt))
			return '';
		return $config_data->all_fields[$field_index]->prompt;				// get the prompt from the config data
		}

// field values

	if (strncmp($variable, LAFC_T_FIELD_VALUE, LAFC_T_OFFSET_V_XX) == 0)	// e.g: %V_FIELD_VALUE_03%
		{
		$field_number = substr($variable, LAFC_T_OFFSET_V_XX, 2);			// 1-based field number
		if (!FCP_Common::is_posint($field_number,false))
			return '';
		$field_index = $field_number - 1;									// 0-based array index
		return $this->get_field_value($config_data, $field_index);
		}

// other variables

	switch ($variable)
		{
		case LAFC_T_FROM_NAME:
			if (isset($this->data->from_name))
				return $this->data->from_name;
			else
				return '';
			
		case LAFC_T_FROM_EMAIL:
			if (isset($this->data->from_email))
				return $this->data->from_email;
			else
				return '';
			
		case LAFC_T_SUBJECT:
			return $this->data->subject;
			
		case LAFC_T_ALL_DATA:
			return $this->data->all_data;
			
		case LAFC_T_OTHER_DATA:
			return $this->data->other_data;
			
		case LAFC_T_BROWSER:
			return $this->data->browser_string;
			
		case LAFC_T_IP_ADDRESS:
			return $this->data->ip;
			
		case LAFC_T_URL_PATH:
			$app = JFactory::getApplication();
			return $app->getUserState(LAFC_COMPONENT."_url_path",'');		// we stored it earlier
			
		case LAFC_T_SITE_URL:
			return $this->data->site_url;
			
		case LAFC_T_SITE_NAME:
			return $this->data->site_name;
			
		case LAFC_T_PAGE_TITLE:
			$app = JFactory::getApplication();
			return $app->getUserState(LAFC_COMPONENT."_page_title",'');		// we stored it earlier
			
		default:
			return '';
		}
}

//-------------------------------------------------------------------------------
// Merge an email template with post data
//
function email_merge($template_text, $config_data)
{
	$text = $template_text;
	$variable_regex = "#%V_*(.*?)%#s";

	preg_match_all($variable_regex, $text, $variable_matches, PREG_SET_ORDER);

	foreach ($variable_matches as $match)
		{
		$resolved_text = $this->email_resolve($config_data, $match[0]);
		$text = str_replace($match[0], $resolved_text, $text);
		}
	return $text;
}

// -------------------------------------------------------------------------------
// Send the email
// Returns blank if ok, or an error message on failure
//
function sendEmail($config_data)
{
	if (FCP_trace::tracing())
		{
		FCP_trace::trace("=====> Send Email() - Config Data: ".print_r($config_data,true));
		FCP_trace::trace("=====> Send Email() - Email Model Data: ".print_r($this->data,true));
		}
		
// build the message to be sent to the site admin

	$body = $this->email_merge($config_data->admin_template, $config_data);
	jimport('joomla.mail.helper');
	$clean_body = JMailHelper::cleanBody($body);
	$clean_subject = JMailHelper::cleanSubject($this->data->subject);

// build the Joomla mail object

	$mail = JFactory::getMailer();

	if ($config_data->email_html)
		$mail->IsHTML(true);
	else
		$clean_body = $this->html2text($clean_body);
		
	if ($config_data->email_from == '')						// v7.01
		$email_from = $this->data->from_email;				// use form data
	else
		$email_from = $config_data->email_from;				// use FlexiContact Global Configuration
		
	if ($config_data->email_from_name == '')				// v7.01
		$email_from_name = $this->data->from_name;			// use form data
	else
		$email_from_name = $config_data->email_from_name;	// use FlexiContact Global Configuration

// 8.00: don't try to send an email with a blank from name or address
// this could happen if those fields are non-mandatory on the email form

	$app = JFactory::getApplication();
	if (empty($email_from))
		$email_from = $app->getCfg('mailfrom');				// use Joomla Global Configuration
	if (empty($email_from_name))
		$email_from_name = $app->getCfg('fromname'); 		// use Joomla Global Configuration

	$mail->setSender(array($email_from, $email_from_name));
	$mail->addRecipient($config_data->email_to);
	$this->data->admin_email = $config_data->email_to;		// store it for the log model
	if (!empty($config_data->email_cc))
		{
		$addresses = explode(',', $config_data->email_cc);
		foreach ($addresses as $address)
			$mail->addCC($address);
		}
	if (!empty($config_data->email_bcc))
		{
		$addresses = explode(',', $config_data->email_bcc);
		foreach ($addresses as $address)
			$mail->addBCC($address);
		}
	if (!empty($this->data->from_email))
		$mail->addReplyTo(array($this->data->from_email, $this->data->from_name));
	$mail->setSubject($clean_subject);
	$mail->setBody($clean_body);
	
// add any file attachments	

	foreach ($this->files as $attachment)
		{
		FCP_trace::trace("Attaching file $attachment");
		$mime_type = self::getMimeType($attachment);
		$mail->addAttachment($attachment,'','base64',$mime_type);
		$this->data->attached_file = 1;						// store it for the log model
		}
	
	if (FCP_trace::tracing())
		FCP_trace::trace("=====> Sending admin email: ".print_r($mail,true));
	
	if (defined('LAFC_DEMO_MODE'))
		$ret_main = true;
	else
		{
		FCP_trace::trace("****> Calling mail->Send()");	
		$ret_main = $mail->Send();
		FCP_trace::trace("****> Back from mail->Send()");	
		}

	if ($ret_main === true)
		{
		$this->data->status_main = '1';
		FCP_trace::trace("=====> Admin email sent ok");
		}
	else
		{
		$this->data->status_main = $mail->ErrorInfo;
		FCP_trace::trace("=====> Admin email send failed: ".$mail->ErrorInfo);
		}
	
// if we should send the user a copy, send it separately
// don't even attempt it if the from_email address is blank

	if ((!empty($this->data->from_email)) and (($config_data->show_copy == LAFC_COPYME_ALWAYS) or ($this->data->show_copy == 1)))
		{
		$body = $this->email_merge($config_data->user_template, $config_data);
		$clean_body = JMailHelper::cleanBody($body);
		$mail = JFactory::getMailer();
		if ($config_data->email_html)
			$mail->IsHTML(true);
		else
			$clean_body = $this->html2text($clean_body);
			
		if ($config_data->email_from == '')						// v7.01
			$email_from = $app->getCfg('mailfrom');				// use Joomla Global Configuration
		else
			$email_from = $config_data->email_from;				// use FlexiContact Global Configuration

		if ($config_data->email_from_name == '')				// v7.01
			$email_from_name = $app->getCfg('fromname'); 		// use Joomla Global Configuration
		else
			$email_from_name = $config_data->email_from_name;	// use FlexiContact Global Configuration
			
		$mail->setSender(array($email_from, $email_from_name));
		$mail->addRecipient($this->data->from_email);
		$mail->setSubject($clean_subject);
		$mail->setBody($clean_body);

		if (FCP_trace::tracing())
			FCP_trace::trace("=====> Sending user email: ".print_r($mail,true));
			
		if (defined('LAFC_DEMO_MODE'))
			$ret_copy = true;
		else
			$ret_copy = $mail->Send();
			
		if ($ret_copy === true)
			{
			$this->data->status_copy = '1';
			FCP_trace::trace("=====> User email sent ok");
			}
		else
			{
			$this->data->status_copy = $mail->ErrorInfo;
			FCP_trace::trace("=====> User email send failed: ".$mail->ErrorInfo);
			}
		}
	else
		$this->data->status_copy = '0';		// copy not requested or no email address provided
		
	FCP_trace::trace("=====> SendEmail function returning: ".$this->data->status_main);
	return $this->data->status_main;		// both statuses are logged, but the main status decides what happens next
}

// -------------------------------------------------------------------------------
// Convert html to plain text
//
function html2text($html)
{
    $tags = array (
		0 => '~<h[123][^>]+>~si',
		1 => '~<h[456][^>]+>~si',
		2 => '~<table[^>]+>~si',
		3 => '~<tr[^>]+>~si',
		4 => '~<li[^>]+>~si',
		5 => '~<br[^>]+>~si',
		6 => '~<p[^>]+>~si',
		7 => '~<div[^>]+>~si',
		);
    $html = preg_replace($tags,"\n",$html);
    $html = preg_replace('~</t(d|h)>\s*<t(d|h)[^>]+>~si',' - ',$html);
    $html = preg_replace('~<[^>]+>~s','',$html);
    $html = preg_replace('~ +~s',' ',$html);
    $html = preg_replace('~^\s+~m','',$html);
    $html = preg_replace('~\s+$~m','',$html);
    $html = preg_replace('~\n+~s',"\n",$html);
    return $html;
}

//-----------------------------------------
// Get client's IP address
//
function getIPaddress()
{
	if (isset($_SERVER["REMOTE_ADDR"]))
		return $_SERVER["REMOTE_ADDR"];
	if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
		return $_SERVER["HTTP_X_FORWARDED_FOR"];
	if (isset($_SERVER["HTTP_CLIENT_IP"]))
		return $_SERVER["HTTP_CLIENT_IP"];
	return "unknown";
} 

//-------------------------------------------------------------------------------
// Get client's browser
// Returns 99 for unknown, 0 for msie, 1 for Firefox, etc
//
function getBrowser(&$browser_string)
{ 
    $u_agent = $_SERVER['HTTP_USER_AGENT']; 
    $browser_string = 'Unknown';

    if (strstr($u_agent, 'MSIE') && !strstr($u_agent, 'Opera')) 
    	{ 
        $browser_string = 'MSIE'; 
        return 0; 
    	} 
    if (strstr($u_agent, 'Trident')) 
    	{ 
        $browser_string = 'MSIE'; 
        return 0; 
    	} 
    if (strstr($u_agent, 'Firefox')) 
    	{ 
        $browser_string = 'Firefox'; 
        return 1; 
    	} 
    if (strstr($u_agent, 'Chrome')) 	 // must test for Chrome before Safari!
    	{ 
        $browser_string = 'Chrome'; 
        return 3; 
    	} 
    if (strstr($u_agent, 'Safari')) 
    	{ 
        $browser_string = 'Safari'; 
        return 2; 
    	} 
    if (strstr($u_agent, 'Opera')) 
    	{ 
        $browser_string = 'Opera'; 
        return 4; 
    	} 
    if (strstr($u_agent, 'Netscape')) 
    	{ 
        $browser_string = 'Netscape'; 
        return 5; 
    	} 
    if (strstr($u_agent, 'Konqueror')) 
    	{ 
        $browser_string = 'Konqueror'; 
        return 6; 
    	} 
} 

//-------------------------------------------------------------------------------
// get the mime type for an extension
//
static function getMimeType($path)
{
	if (function_exists('mime_content_type'))
        return mime_content_type($path);
        
    if (function_exists('finfo_open')) 
    	{
	    $finfo = finfo_open(FILEINFO_MIME_TYPE);
	    $mimetype = finfo_file($finfo, $path);
	    finfo_close($finfo);
        return $mimetype;
        }

// if we don't have either of the above functions, use this list

	$file_extension = pathinfo($attachment, PATHINFO_EXTENSION);
	switch ($file_extension)
		{
		case 'avi':   return 'video/x-msvideo';
		case 'bmp':   return 'image/bmp';
		case 'css':   return 'text/css';
		case 'doc':   return 'application/msword';
		case 'dvi':   return 'application/x-dvi';
		case 'eps':   return 'application/postscript';
		case 'gif':   return 'image/gif';
		case 'gtar':  return 'application/x-gtar';
		case 'htm':   return 'text/html';
		case 'html':  return 'text/html';
		case 'jpe':   return 'image/jpeg';
		case 'jpeg':  return 'image/jpeg';
		case 'jpg':   return 'image/jpeg';
		case 'js':    return 'application/x-javascript';
		case 'log':   return 'text/plain';
		case 'mov':   return 'video/quicktime';
		case 'movie': return 'video/x-sgi-movie';
		case 'mp2':   return 'audio/mpeg';
		case 'mp3':   return 'audio/mpeg';
		case 'mpe':   return 'video/mpeg';
		case 'mpeg':  return 'video/mpeg';
		case 'mpg':   return 'video/mpeg';
		case 'mpga':  return 'audio/mpeg';
		case 'oda':   return 'application/oda';
		case 'pdf':   return 'application/pdf';
		case 'png':   return 'image/png';
		case 'ppt':   return 'application/vnd.ms-powerpoint';
		case 'ps':    return 'application/postscript';
		case 'qt':    return 'video/quicktime';
		case 'ra':    return 'audio/x-realaudio';
		case 'ram':   return 'audio/x-pn-realaudio';
		case 'rm':    return 'audio/x-pn-realaudio';
		case 'rpm':   return 'audio/x-pn-realaudio-plugin';
		case 'rtf':   return 'text/rtf';
		case 'rtx':   return 'text/richtext';
		case 'rv':    return 'video/vnd.rn-realvideo';
		case 'shtml': return 'text/html';
		case 'swf':   return 'application/x-shockwave-flash';
		case 'tar':   return 'application/x-tar';
		case 'text':  return 'text/plain';
		case 'tgz':   return 'application/x-tar';
		case 'tif':   return 'image/tiff';
		case 'tiff':  return 'image/tiff';
		case 'txt':   return 'text/plain';
		case 'wav':   return 'audio/x-wav';
		case 'word':  return 'application/msword';
		case 'xht':   return 'application/xhtml+xml';
		case 'xhtml': return 'application/xhtml+xml';
		case 'xl':    return 'application/excel';
		case 'xls':   return 'application/vnd.ms-excel';
		case 'xml':   return 'text/xml';
		case 'xsl':   return 'text/xml';
		case 'zip':   return 'application/zip';
		default:      return 'application/octet-stream';
		}
}

}
		
		