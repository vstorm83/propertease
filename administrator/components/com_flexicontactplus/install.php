<?php
/********************************************************************
Product		: FlexicontactPlus
Date		: 29 October 2014
Copyright	: Les Arbres Design 2009-2014
Contact		: http://www.lesarbresdesign.info
Licence		: GNU General Public License
*********************************************************************/
defined('_JEXEC') or die('Restricted Access');

class com_flexicontactplusInstallerScript
{
public function preflight($type, $parent) 
{
	$version = new JVersion();  			// get the Joomla version (JVERSION did not exist before Joomla 2.5)
	$joomla_version = $version->RELEASE.'.'.$version->DEV_LEVEL;

	if (version_compare($joomla_version,"2.5.5","<"))			// JDatabase::execute() was added in Joomla 2.5.5
		{
		Jerror::raiseWarning(null, "Flexicontact Plus requires at least Joomla 2.5.5");
		return false;
		}
		
	if (get_magic_quotes_gpc())
		{
		Jerror::raiseWarning(null, "Flexicontact Plus cannot run with PHP Magic Quotes ON. Please switch it off and re-install.");
		return false;
		}
		
	return true;
}

public function uninstall($parent)
{ 
	echo "<h2>FlexicontactPlus has been uninstalled</h2>";
	echo "<h2>The database tables were NOT deleted</h2>";
}

//-------------------------------------------------------------------------------
// The main install function
//
public function postflight($type, $parent)
{
// check the Joomla version

	if (substr(JVERSION,0,1) > "3")				// if > 3
		echo "This version of Flexicontact Plus has not been tested on this version of Joomla.";
	
// get the component version from the component manifest xml file		

	$component_version = $parent->get('manifest')->version;
	
// delete redundant files from older versions

	@unlink(JPATH_SITE.'/administrator/components/com_flexicontactplus/admin.flexicontactplus.php');
	@unlink(JPATH_SITE.'/components/com_flexicontactplus/views/_confirm/index.html');
	@unlink(JPATH_SITE.'/components/com_flexicontactplus/views/_confirm/view.html.php');
	@rmdir (JPATH_SITE.'/components/com_flexicontactplus/views/_confirm');
	@unlink(JPATH_SITE.'/components/com_flexicontactplus/assets/asterisk.png');
	@unlink(JPATH_SITE.'/components/com_flexicontactplus/assets/fcp_button.png');
	@unlink(JPATH_SITE.'/components/com_flexicontactplus/assets/fcp_button_hover.png');
	@unlink(JPATH_SITE.'/components/com_flexicontactplus/assets/reload_24.png');
	@unlink(JPATH_SITE.'/components/com_flexicontactplus/assets/reload_32.png');
	@unlink(JPATH_SITE.'/components/com_flexicontactplus/assets/js/fcp_front.js');
	@unlink(JPATH_SITE.'/components/com_flexicontactplus/assets/js/uncompressed-fcp_front.js');
	@unlink(JPATH_SITE.'/components/com_flexicontactplus/assets/js/fcp_front_1.js');
	@unlink(JPATH_SITE.'/components/com_flexicontactplus/assets/js/uncompressed-fcp_front_1.js');
	@unlink(JPATH_SITE.'/components/com_flexicontactplus/assets/js/fcp_front_2.js');
	@unlink(JPATH_SITE.'/components/com_flexicontactplus/assets/js/uncompressed-fcp_front_2.js');
	@unlink(JPATH_SITE.'/components/com_flexicontactplus/assets/js/fcp_front_3.js');
	@unlink(JPATH_SITE.'/components/com_flexicontactplus/assets/js/uncompressed-fcp_front_3.js');
	@unlink(JPATH_SITE.'/administrator/components/com_flexicontactplus/joomla15.xml');
	@unlink(JPATH_SITE.'/administrator/components/com_flexicontactplus/joomla16.xml');
	@unlink(JPATH_SITE.'/administrator/components/com_flexicontactplus/install.flexicontactplus.php');
	@unlink(JPATH_SITE.'/components/com_flexicontactplus/assets/mtcal/mtcal_1.1.js');
	@unlink(JPATH_SITE.'/components/com_flexicontactplus/assets/mtcal/mtcal_1.2.js');
	@unlink(JPATH_SITE.'/components/com_flexicontactplus/assets/jquery\ui\jquery.ui.widget.js');
	@unlink(JPATH_SITE.'/components/com_flexicontactplus/assets/jquery\ui\jquery.ui.datepicker.js');
	@unlink(JPATH_SITE.'/components/com_flexicontactplus/assets/js/fcp_mootools.js');
	@unlink(JPATH_SITE.'/components/com_flexicontactplus/assets/js/uncompressed-fcp_mootools.js');
	@rmdir (JPATH_SITE.'/components/com_flexicontactplus/assets/jquery\ui');

// create our database tables

	$this->_db = JFactory::getDBO();
	$this->create_tables();

// add new columns

	$this->add_column('#__flexicontact_plus_log', 'admin_email', "VARCHAR(60) NOT NULL DEFAULT '' AFTER `email`");
	$this->add_column('#__flexicontact_plus_log', 'imported',    "tinyint(4)  NOT NULL DEFAULT '0'");
	$this->add_column('#__flexicontact_plus_log', 'config_name', "VARCHAR(60) NOT NULL DEFAULT '' AFTER `admin_email`");
	$this->add_column('#__flexicontact_plus_log', 'config_lang', "VARCHAR(10) NOT NULL DEFAULT '' AFTER `config_name`");
	$this->add_column('#__flexicontact_plus_log', 'attached_file', "tinyint(4) NOT NULL DEFAULT '0' AFTER `imported`");

// Check that the default configuration exists

	if (!$this->checkDefaultConfig())
		{
		echo '<h3>Flexicontact Plus detected a problem while installing the default configuration data <br />'
			.'Please copy the error information above and contact support</h3>';
		return false;
		}
		
// map old css files to the new ones

	$this->upgradeCSS();
	
// Raise warning about deprecated field types

	$this->checkFields();

// we are done

	echo "<h3>Flexicontact Plus version $component_version installed.</h3>";
	return true;
}

//-------------------------------------------------------------------------------
// Create our database tables
//
function create_tables()
{
	$this->ladb_execute("CREATE TABLE IF NOT EXISTS `#__flexicontact_plus_config` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `name` varchar(60) NOT NULL,
			  `published` tinyint(4) NOT NULL DEFAULT '1',
			  `default_config` tinyint(1) NOT NULL DEFAULT '0',
			  `language` varchar(10) NOT NULL,
			  `description` varchar(255) NOT NULL,
			  `config_data` mediumtext NOT NULL,
			  PRIMARY KEY (`id`),
			  KEY `NAME_LANG` (`name`, `language`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");
			
	$this->ladb_execute("CREATE TABLE IF NOT EXISTS `#__flexicontact_plus_log` (
			 `id` int(11) NOT NULL AUTO_INCREMENT,
			 `datetime` datetime NOT NULL,
			 `name` varchar(60) NOT NULL DEFAULT '',
			 `email` varchar(60) NOT NULL DEFAULT '',
			 `admin_email` varchar(60) NOT NULL DEFAULT '',
			 `config_name` varchar(60) NOT NULL DEFAULT '',
			 `config_lang` varchar(10) NOT NULL DEFAULT '',
			 `subject` varchar(100) NOT NULL DEFAULT '',
			 `message` text NOT NULL,
			 `status_main` varchar(255) NOT NULL DEFAULT '',
			 `status_copy` varchar(255) NOT NULL DEFAULT '',
			 `ip` varchar(40) NOT NULL DEFAULT '',
			 `browser_id` tinyint(4) NOT NULL DEFAULT '0',
			 `browser_string` varchar(20) NOT NULL DEFAULT '',
			 `imported` tinyint(4) NOT NULL DEFAULT '0',
			 `attached_file` tinyint(4) NOT NULL DEFAULT '0',
			 PRIMARY KEY (`id`),
			 KEY `DATETIME` (`datetime`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
}

//-------------------------------------------------------------------------------
// Migrate any old css file names to their new equivalents
//
function upgradeCSS()
{
	$css_map = array(
		'com_fcp.css' => 'fcp.css',
		'com_fcp_black_gold.css' => 'fcp_black_gold.css',
		'com_fcp_grey_black.css' => 'fcp_grey_black.css',
		'com_fcp_holiday.css' => 'fcp_holiday.css',
		'com_fcp_large.css' => 'fcp_large.css',
		'com_fcp_responsive.css' => 'fcp_simple.css',
		'com_fcp_responsive_black_gold.css' => 'fcp_black_gold.css',
		'com_fcp_rl_grey.css' => 'fcp_rl_grey.css',
		'com_fcp_silver.css' => 'fcp_silver.css',
		'com_fcp_simple.css' => 'fcp_simple.css',
		'com_fcp_simple_2.css' => 'fcp_simple_2.css',
		'com_fcp_small_1.css' => 'fcp_small_1.css',
		'com_fcp_small_2.css' => 'fcp_small_2.css');

	$site_assets_path = JPATH_ROOT.'/components/com_flexicontactplus/assets';
		
// we only want to do any of this if any of the old css files exist in the assets directory

	$old_files_exist = false;
	foreach ($css_map as $old_css_name => $new_css_name)
		if (file_exists($site_assets_path.'/'.$old_css_name))
			$old_files_exist = true;
	if (!$old_files_exist)
		return;
		
	$query = "SELECT * FROM `#__flexicontact_plus_config`;";
	$rows = $this->ladb_loadObjectList($query);
	if ($rows === false)
		return;

	$css_changed = false;
	foreach ($rows as $row) 
		{
		$config_data = unserialize($row->config_data);
		if (isset($config_data->css_file))					// does this config have a css filename?
			{
			$old_css_name = $config_data->css_file;
			if (isset($css_map[$old_css_name]))				// do we have a mapping for it?
				{
				$new_css_name = $css_map[$old_css_name];
				echo '['.$row->name.' config] '.$old_css_name.' -> '.$new_css_name.'<br />';
				$config_data->css_file = $new_css_name;
				$serialized_config_data = serialize($config_data);
				$query = "UPDATE `#__flexicontact_plus_config` SET 
							`config_data` = ".$this->_db->Quote($serialized_config_data)."
							WHERE `id` = ".$row->id;
				$result = $this->ladb_execute($query);
				if ($result === false)
					return;
				$css_changed = true;
				}
			}
		}
		
	@mkdir($site_assets_path.'/old_css_files');
	foreach ($css_map as $old_css_name => $new_css_name)
		@rename($site_assets_path.'/'.$old_css_name,$site_assets_path.'/old_css_files/'.$old_css_name);

	if ($css_changed)
		echo '<br /><strong>Some CSS files were upgraded. Your old CSS files are stored in '.$site_assets_path.'/old_css_files</strong>';
}

//-------------------------------------------------------------------------------
// Make sure that the default configuration exists
//
function checkDefaultConfig()
{
// Do we have a default configuration already?

	$query = "SELECT COUNT(*) FROM `#__flexicontact_plus_config` WHERE `default_config`=1";
	$count = $this->ladb_loadResult($query);
	if ($count === false)
		return false;
		
	if ($count == 0)
		{
		$ret =  $this->makeDefaultConfig();
		return $ret;
		}
		
// Make sure there is no more than one default config
// (this can only happen when manually importing configs from other databases)

	$query = "SELECT * FROM `#__flexicontact_plus_config` WHERE `default_config` = 1 ORDER BY `id`";
	$rows = $this->ladb_loadObjectList($query);
	if ($rows === false)
		return false;

	if (count($rows) == 1)
		return true;
		
// if more than one, change all except the first one to non-default

	$first_default_id = $rows[0]->id;
	$query = "UPDATE `#__flexicontact_plus_config` SET `default_config` = 0 WHERE `id` != ".$first_default_id;
	$result = $this->ladb_execute($query);
	return $result;
}

//-------------------------------------------------------------------------------
// Create the default configuration
//
function makeDefaultConfig()
{
	$app = JFactory::getApplication();
	
	$default = new stdClass();
	$default->published = 1;
	$default->default_config = 1;
	$default->name = 'Default';
	$default->language = 'en-GB';
	$default->description = 'Default Configuration';
	$default->config_data = new stdClass();
		$default->config_data->email_html = 1;
		$default->config_data->num_images = 0;
		$default->config_data->email_to = $app->getCfg('mailfrom');
		$default->config_data->all_fields = array();
			$default->config_data->all_fields[0] = new stdClass();
				$default->config_data->all_fields[0]->field_type = 2;
				$default->config_data->all_fields[0]->prompt = 'Your name';
				$default->config_data->all_fields[0]->width = '';
				$default->config_data->all_fields[0]->height = 4;
				$default->config_data->all_fields[0]->list_list = '';
				$default->config_data->all_fields[0]->mandatory = 1;
				$default->config_data->all_fields[0]->visible = 1;
				$default->config_data->all_fields[0]->default_value = '';
			$default->config_data->all_fields[1] = new stdClass();
				$default->config_data->all_fields[1]->field_type = 1;
				$default->config_data->all_fields[1]->prompt = 'Your E-mail address';
				$default->config_data->all_fields[1]->width = '';
				$default->config_data->all_fields[1]->height = 4;
				$default->config_data->all_fields[1]->list_list = '';
				$default->config_data->all_fields[1]->mandatory = 1;
				$default->config_data->all_fields[1]->visible = 1;
				$default->config_data->all_fields[1]->default_value = '';
			$default->config_data->all_fields[2] = new stdClass();
				$default->config_data->all_fields[2]->field_type = 3;
				$default->config_data->all_fields[2]->prompt = 'Subject';
				$default->config_data->all_fields[2]->width = '';
				$default->config_data->all_fields[2]->height = 4;
				$default->config_data->all_fields[2]->list_list = '';
				$default->config_data->all_fields[2]->mandatory = 1;
				$default->config_data->all_fields[2]->visible = 1;
				$default->config_data->all_fields[2]->default_value = '';
			$default->config_data->all_fields[3] = new stdClass();
				$default->config_data->all_fields[3]->field_type = 6;
				$default->config_data->all_fields[3]->prompt = 'Message';
				$default->config_data->all_fields[3]->width = '';
				$default->config_data->all_fields[3]->height = 5;
				$default->config_data->all_fields[3]->list_list = '';
				$default->config_data->all_fields[3]->mandatory = 0;
				$default->config_data->all_fields[3]->visible = 1;
				$default->config_data->all_fields[3]->default_value = '';
		$default->config_data->confirm_text = '<p>Your message has been sent</p>';
		$default->config_data->user_template = '<p>%V_ALL_DATA%</p>';
		$default->config_data->admin_template = '<p>From %V_FROM_NAME% at %V_FROM_EMAIL%</p><p>%V_OTHER_DATA%</p>';
		
	$serialized_config_data = serialize($default->config_data);
	
	$query = "INSERT INTO `#__flexicontact_plus_config`	(`published`, `default_config`, `name`, `language`, `description`, `config_data`) VALUES (".
		$default->published.','.
		$default->default_config.','.
		$this->_db->Quote($default->name).','.
		$this->_db->Quote($default->language).','.
		$this->_db->Quote($default->description).','.
		$this->_db->Quote($serialized_config_data).')';

	$result = $this->ladb_execute($query);
	return $result;
}

//-------------------------------------------------------------------------------
// Check whether a table exists in the database. Returns TRUE if exists, FALSE if it doesn't
//
function table_exists($table)
{
	$tables = $this->_db->getTableList();
	$table = self::replaceDbPrefix($table);
	if (self::in_arrayi($table,$tables))
		return true;
	else
		return false;
}

//-------------------------------------------------------------------------------
// Check whether a column exists in a table. Returns TRUE if exists, FALSE if it doesn't
//
function column_exists($table, $column)
{
	$fields = $this->_db->getTableColumns($table);
		
	if ($fields === null)
		return false;
		
	if (array_key_exists($column,$fields))
		return true;
	else
		return false;
}

//-------------------------------------------------------------------------------
// Add a column if it doesn't exist (the table must exist)
//
function add_column($table, $column, $details)
{
	if ($this->column_exists($table, $column))
		return;
	$query = 'ALTER TABLE `'.$table.'` ADD `'.$column.'` '.$details;;
	return $this->ladb_execute($query);
}

//-------------------------------------------------------------------------------
// Change a column if it exists 
//
function change_column($table, $column, $details)
{
	if (!$this->column_exists($table, $column))
		return;
	$query = 'ALTER IGNORE TABLE `'.$table.'` CHANGE `'.$column.'` '.$details;
	return $this->ladb_execute($query);
}

//-------------------------------------------------------------------------------
// Replace the generic database prefix #__ with the real one
//
static function replaceDbPrefix($sql)
{
	$app = JFactory::getApplication();
	$dbprefix = $app->getCfg('dbprefix');
	return str_replace('#__',$dbprefix,$sql);
}

//-------------------------------------------------------------------------------
// Case insensitive in_array()
//
static function in_arrayi($needle, $haystack)
{
    return in_array(strtolower($needle), array_map('strtolower', $haystack));
}

//-------------------------------------------------------------------------------
// Execute a SQL query and return true if it worked, false if it failed
//
function ladb_execute($query)
{
	if (version_compare(JVERSION,"3.0.0","<"))	// if < 3.0
		{
		$this->_db->setQuery($query);
		$this->_db->execute();
		if ($this->_db->getErrorNum())
			{
			echo '<div style="color:red">'.$this->_db->stderr().'</div>';
			return false;
			}
		return true;
		}
		
// for Joomla 3.0 use try/catch error handling

	try
		{
		$this->_db->setQuery($query);
		$this->_db->execute();
		}
	catch (RuntimeException $e)
		{
	    echo '<div style="color:red">'.$e->getMessage().'</div>';
		return false;
		}
	return true;
}

//-------------------------------------------------------------------------------
// Get a single value from the database as an object and return it, or false if it failed
//
function ladb_loadResult($query)
{
	if (version_compare(JVERSION,"3.0.0","<"))	// if < 3.0
		{
		$this->_db->setQuery($query);
		$result = $this->_db->loadResult();
		if ($this->_db->getErrorNum())
			{
			echo '<div style="color:red">'.$this->_db->stderr().'</div>';
			return false;
			}
		return $result;
		}

// for Joomla 3.0 use try/catch error handling

	try
		{
		$this->_db->setQuery($query);
		$result = $this->_db->loadResult();
		}
	catch (RuntimeException $e)
		{
	    echo '<div style="color:red">'.$e->getMessage().'</div>';
		return false;
		}
	return $result;
}

//-------------------------------------------------------------------------------
// Get an array of rows from the database and return it, or false if it failed
//
function ladb_loadObjectList($query)
{
	if (version_compare(JVERSION,"3.0.0","<"))	// if < 3.0
		{
		$this->_db->setQuery($query);
		$result = $this->_db->loadObjectList();
		if ($this->_db->getErrorNum())
			{
			echo '<div style="color:red">'.$this->_db->stderr().'</div>';
			return false;
			}
		return $result;
		}

// for Joomla 3.0 use try/catch error handling

	try
		{
		$this->_db->setQuery($query);
		$result = $this->_db->loadObjectList();
		}
	catch (RuntimeException $e)
		{
	    echo '<div style="color:red">'.$e->getMessage().'</div>';
		return false;
		}
	return $result;
}

//-------------------------------------------------------------------------------
// Check for any deprecated field types and raise a warning message if necessary
//

function checkFields()
{
	$found = false;
	
	// Get all the configs
	$query = "SELECT * FROM `#__flexicontact_plus_config`;";
	$rows = $this->ladb_loadObjectList($query);
	if ($rows === false)
		return;

	foreach ($rows as $row) 
		{
		$config_data = unserialize($row->config_data);
		foreach ($config_data->all_fields as $field)
			{
			if ($field->field_type == 11)		// Horizontal checkboxes
				{
				$found = true;
				break;
				}
			}
		if ($found === true)
			break;
		}
		
	if ($found === true)
		echo '<div style="color:red">Field type [Checkbox (horizontal)] has been deprecated. Please change to the new Checkbox (multiple horizontal) field type.</div>';
		
	return;


}

} // end of class definition
