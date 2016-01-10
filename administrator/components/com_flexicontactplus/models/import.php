<?php
/********************************************************************
Product		: Flexicontact Plus
Date		: 23 January 2014
Copyright	: Les Arbres Design 2010-2014
Contact		: http://www.lesarbresdesign.info
Licence		: GNU General Public License
*********************************************************************/
defined('_JEXEC') or die('Restricted Access');

class FlexicontactplusModelImport extends LAFP_model
{
var $_app = null;					// constructor gets the app
var $_data = null;

function __construct()
{
	parent::__construct();
	$this->_app = JFactory::getApplication();
}

//-------------------------------------------------------------------------------
// Return information about the free flexicontact_log, if it exists
//
function free_log_info()
{
	$query = "SELECT COUNT(*) AS count, 
			DATE_FORMAT(MIN(`datetime`),'%d %M %Y') AS date_from, 
			DATE_FORMAT(MAX(`datetime`),'%d %M %Y') AS date_to
				FROM  `#__flexicontact_log`";
	$result_row = null;
	try {$result_row = $this->ladb_loadObject($query);}
		catch (Exception $e) { }
	return $result_row;
}

//-------------------------------------------------------------------------------
// Return the number of imported rows already in flexicontact_plus_log
//
function count_imported_rows()
{
	$query = "SELECT COUNT(*) FROM `#__flexicontact_plus_log` WHERE `imported` = 1";
	return $this->ladb_loadResult($query);
}

//-------------------------------------------------------------------------------
// Import log data from Free Flexicontact
//
function import(&$return_message)
{
	if (FCP_trace::tracing())
		{
		$count_imported_rows = $this->count_imported_rows();
		FCP_trace::trace("Log import deleting $count_imported_rows rows");
		$free_log_info = $this->free_log_info();
		FCP_trace::trace(" Free log info: ".print_r($free_log_info,true));
		}

// delete any rows previously imported

	$query = "DELETE FROM `#__flexicontact_plus_log` WHERE `imported` = 1";
	$result = $this->ladb_execute($query);
	if ($result === false)
		{
		$return_message = $this->ladb_error_text();
		return false;
		}

// if Free FlexiContact is still installed, we can get the component parameters
// and then get the optional field names

	$component_params = JComponentHelper::getParams("com_flexicontact");
	$params = $component_params->toObject();
	$promptname1 = 'Field1';
	$promptname2 = 'Field2';
	$promptname3 = 'Field3';
	$promptname4 = 'Field4';
	$promptname5 = 'Field5';
	$list_prompt = 'List Choice';
	if (isset($params->field_prompt1))
		{
		FCP_trace::trace("Found free component parameters");
		$promptname1 = $params->field_prompt1;
		$promptname2 = $params->field_prompt2;
		$promptname3 = $params->field_prompt3;
		$promptname4 = $params->field_prompt4;
		$promptname5 = $params->field_prompt5;
		if (isset($params->list_prompt))			// versions < 5.10 only
			$list_prompt = $params->list_prompt;
		}

	$query = "SELECT * FROM `#__flexicontact_log` ";
	$rows = $this->ladb_loadObjectList($query);
	if ($rows === false)
		{
		$return_message = $this->ladb_error_text;
		return false;
		}
	$count = 0;
	foreach($rows as $row)
		{
		$other_data = '';
		if ((isset($row->list_choice)) and ($row->list_choice != ''))
			$other_data .= $list_prompt.': '.$row->list_choice."\n";
		if ($row->field1 != '')
			$other_data .= $promptname1.': '.$row->field1."\n";
		if ($row->field2 != '')
			$other_data .= $promptname2.': '.$row->field2."\n";
		if ($row->field3 != '')
			$other_data .= $promptname3.': '.$row->field3."\n";
		if ($row->field4 != '')
			$other_data .= $promptname4.': '.$row->field4."\n";
		if ($row->field5 != '')
			$other_data .= $promptname5.': '.$row->field5."\n";
		$other_data .= $row->message;
		
		$query = 'INSERT INTO `#__flexicontact_plus_log` 
			(`datetime`, `name`, `email`, `admin_email`, `subject`, `message`, `status_main`, `status_copy`, 
				`ip`, `browser_id`, `browser_string`, `imported`) 
			VALUES ('.
				$this->_db->Quote($row->datetime).','.
				$this->_db->Quote($row->name).','.
				$this->_db->Quote($row->email).','.
				'"",'.
				$this->_db->Quote($row->subject).','.
				$this->_db->Quote($other_data).','.
				$this->_db->Quote($row->status_main).','.
				$this->_db->Quote($row->status_copy).','.
				$this->_db->Quote($row->ip).','.
				$this->_db->Quote($row->browser_id).','.
				$this->_db->Quote($row->browser_string).','.
				'1)';		// set imported to 1
	
		FCP_trace::trace(" IMPORTING ROW: ".$row->id." DATE: ".$row->datetime." NAME: ".$row->name." EMAIL: ".$row->email);

		$result = $this->ladb_execute($query);
		if ($result === false)
			{
			$return_message = $this->ladb_error_text;
			return false;
			}
		$count ++;
		}

	FCP_trace::trace("Imported $count rows");
	
	$return_message = JText::sprintf('COM_FLEXICONTACT_IMPORTED_ROWS',$count);

	return true;
}


}