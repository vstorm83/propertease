<?php
/**
 * @version        1.6.7
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2014 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die();
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

class com_osmembershipInstallerScript
{

	public static $languageFiles = array('en-GB.com_osmembership.ini');

	/**
	 * Method to run before installing the component. Using to backup language file in this case
	 */
	function preflight($type, $parent)
	{
		//Backup the old language file
		foreach (self::$languageFiles as $languageFile)
		{
			if (JFile::exists(JPATH_ROOT . '/language/en-GB/' . $languageFile))
			{
				JFile::copy(JPATH_ROOT . '/language/en-GB/' . $languageFile, JPATH_ROOT . '/language/en-GB/bak.' . $languageFile);
			}
		}
		if (JFile::exists(JPATH_ROOT . '/components/com_osmembership/assets/css/custom.css'))
		{
			JFile::copy(JPATH_ROOT . '/components/com_osmembership/assets/css/custom.css',
				JPATH_ROOT . '/components/com_osmembership/assets/css/bak.custom.css');
		}
		if (JFile::exists(JPATH_ROOT . '/components/com_osmembership/helper/fields.php'))
		{
			JFile::delete(JPATH_ROOT . '/components/com_osmembership/helper/fields.php');
		}
		if (JFolder::exists(JPATH_ROOT . '/components/com_osmembership/assets/validate'))
		{
			JFolder::delete(JPATH_ROOT . '/components/com_osmembership/assets/validate');
		}
	}

	/**
	 * method to install the component
	 *
	 * @return void
	 */
	function install($parent)
	{
		com_install();
	}

	function update($parent)
	{
		com_install();
	}

	/**
	 * Method to run after installing the component
	 */
	function postflight($type, $parent)
	{
		//Restore the modified language strings by merging to language files
		$registry = new JRegistry();
		foreach (self::$languageFiles as $languageFile)
		{
			$backupFile  = JPATH_ROOT . '/language/en-GB/bak.' . $languageFile;
			$currentFile = JPATH_ROOT . '/language/en-GB/' . $languageFile;
			if (JFile::exists($currentFile) && JFile::exists($backupFile))
			{
				$registry->loadFile($currentFile, 'INI');
				$currentItems = $registry->toArray();
				$registry->loadFile($backupFile, 'INI');
				$backupItems = $registry->toArray();
				$items       = array_merge($currentItems, $backupItems);
				$content     = "";
				foreach ($items as $key => $value)
				{
					$content .= "$key=\"$value\"\n";
				}
				JFile::write($currentFile, $content);
			}
		}

		// Restore custom modified css file
		if (JFile::exists(JPATH_ROOT . '/components/com_osmembership/assets/css/bak.custom.css'))
		{
			JFile::copy(JPATH_ROOT . '/components/com_osmembership/assets/css/bak.custom.css',
				JPATH_ROOT . '/components/com_osmembership/assets/css/custom.css');
			JFile::delete(JPATH_ROOT . '/components/com_osmembership/assets/css/bak.custom.css');
		}
	}
}

/**
 * Change the db structure of the previous version
 *
 */
function com_install()
{
	error_reporting(0);
	$db = JFactory::getDbo();
	require_once JPATH_ROOT . '/components/com_osmembership/helper/helper.php';
	//First, we will need to create additional database tables which was not available in old version
	$prefix = $db->getPrefix();
	$tables = $db->getTableList();
	if (!in_array($prefix . 'osmembership_categories', $tables))
	{
		//Create the categories table, added in version 1.1.1
		$sql = "CREATE TABLE IF NOT EXISTS `#__osmembership_categories` (
        `id` INT NOT NULL AUTO_INCREMENT,
        `title` VARCHAR(255) NULL,
        `description` TEXT NULL,
        `published` TINYINT UNSIGNED NULL,
        PRIMARY KEY(`id`)
        ) DEFAULT CHARSET=utf8 ;";
		$db->setQuery($sql);
		$db->execute();
	}

	if (!in_array($prefix . 'osmembership_field_plan', $tables))
	{
		//Create the categories table, added in version 1.1.1
		$sql = "CREATE TABLE IF NOT EXISTS `#__osmembership_field_plan` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `field_id` int(11) DEFAULT NULL,
            `plan_id` int(11) DEFAULT NULL,
            PRIMARY KEY (`id`)
          ) DEFAULT CHARSET=utf8;";
		$db->setQuery($sql);
		$db->execute();
		//Need to migrate data here
		$sql = 'INSERT INTO #__osmembership_field_plan(field_id, plan_id)
                SELECT id, plan_id FROM #__osmembership_fields WHERE plan_id > 0
                ';
		$db->setQuery($sql);
		$db->execute();

		$sql = 'UPDATE #__osmembership_fields SET plan_id=1 WHERE plan_id > 0';
		$db->setQuery($sql);
		$db->execute();
	}
	if (!in_array($prefix . 'osmembership_messages', $tables))
	{
		$sql = 'CREATE TABLE IF NOT EXISTS `#__osmembership_messages` (
		  `id` INT NOT NULL AUTO_INCREMENT,
		  `message_key` VARCHAR(50) NULL,
		  `message` TEXT NULL,
		  PRIMARY KEY(`id`)
		) CHARACTER SET `utf8`;';
		$db->setQuery($sql);
		$db->execute();
	}

	if (!in_array($prefix . 'osmembership_states', $tables))
	{
		$statesSql = JPATH_ADMINISTRATOR . '/components/com_osmembership/sql/states.osmembership.sql';
		$sql       = JFile::read($statesSql);
		$queries   = $db->splitSql($sql);
		if (count($queries))
		{
			foreach ($queries as $query)
			{
				$query = trim($query);
				if ($query != '' && $query{0} != '#')
				{
					$db->setQuery($query);
					$db->execute();
				}
			}
		}
	}

	$sql = 'SELECT COUNT(*) FROM #__osmembership_configs';
	$db->setQuery($sql);
	$total = $db->loadResult();
	if (!$total)
	{
		$configSql = JPATH_ADMINISTRATOR . '/components/com_osmembership/sql/config.osmembership.sql';
		$sql       = JFile::read($configSql);
		$queries   = $db->splitSql($sql);
		if (count($queries))
		{
			foreach ($queries as $query)
			{
				$query = trim($query);
				if ($query != '' && $query{0} != '#')
				{
					$db->setQuery($query);
					$db->execute();
				}
			}
		}
	}
	//Change coupon code data type
	$sql = 'ALTER TABLE  `#__osmembership_coupons` CHANGE  `valid_from`	`valid_from` datetime DEFAULT NULL;';
	$db->setQuery($sql);
	$db->execute();

	$sql = "ALTER TABLE  `#__osmembership_coupons` CHANGE  `valid_to`	`valid_to` datetime DEFAULT NULL;";
	$db->setQuery($sql);
	$db->execute();


	$sql = 'SELECT COUNT(*) FROM #__osmembership_plugins';
	$db->setQuery($sql);
	$total = $db->loadResult();
	if (!$total)
	{
		$pluginsSql = JPATH_ADMINISTRATOR . '/components/com_osmembership/sql/plugins.osmembership.sql';
		$sql        = JFile::read($pluginsSql);
		$queries    = $db->splitSql($sql);
		if (count($queries))
		{
			foreach ($queries as $query)
			{
				$query = trim($query);
				if ($query != '' && $query{0} != '#')
				{
					$db->setQuery($query);
					$db->execute();
				}
			}
		}
	}

	//Invoice data	
	$sql = 'SELECT COUNT(*) FROM #__osmembership_configs WHERE config_key="invoice_format"';
	$db->setQuery($sql);
	$total = $db->loadResult();
	if (!$total)
	{
		$configSql = JPATH_ADMINISTRATOR . '/components/com_osmembership/sql/config.invoice.sql';
		$sql       = JFile::read($configSql);
		$queries   = $db->splitSql($sql);
		if (count($queries))
		{
			foreach ($queries as $query)
			{
				$query = trim($query);
				if ($query != '' && $query{0} != '#')
				{
					$db->setQuery($query);
					$db->execute();
				}
			}
		}
	}

	$sql = "SELECT COUNT(*) FROM #__osmembership_currencies WHERE currency_code='RUB'";
	$db->setQuery($sql);
	$total = $db->loadResult();
	if (!$total)
	{
		$sql = "INSERT INTO #__osmembership_currencies(currency_code, currency_name) VALUES('RUB', 'Russian Rubles')";
		$db->setQuery($sql);
		$db->execute();
	}


	$fields = array_keys($db->getTableColumns('#__osmembership_countries'));
	if (!in_array('id', $fields))
	{
		//Change the name of the name of column from country_id to ID
		$sql = 'ALTER TABLE `#__osmembership_countries` CHANGE `country_id` `id` INT(11) NOT NULL AUTO_INCREMENT;';
		$db->setQuery($sql);
		$db->execute();

		//Add country ID column back for BC
		$sql = "ALTER TABLE  `#__osmembership_countries` ADD  `country_id` INT(11) NOT NULL DEFAULT '0';";
		$db->setQuery($sql);
		$db->execute();

		//Set country_id value the same with id
		$sql = 'UPDATE #__osmembership_countries SET country_id=id';
		$db->setQuery($sql);
		$db->execute();

	}

	$fields = array_keys($db->getTableColumns('#__osmembership_states'));

	if (!in_array('published', $fields))
	{
		$db->setQuery("ALTER TABLE `#__osmembership_states` ADD `published` TINYINT( 4 ) NOT NULL DEFAULT '1'");
		$db->execute();
		$db->setQuery("UPDATE `#__osmembership_states` SET `published` = 1");
		$db->execute();
	}
	if (!in_array('id', $fields))
	{
		//Change the name of the name of column from country_id to ID
		$sql = 'ALTER TABLE `#__osmembership_states` CHANGE `state_id` `id` INT(11) NOT NULL AUTO_INCREMENT;';
		$db->setQuery($sql);
		$db->execute();

		//Add country ID column back for BC
		$sql = "ALTER TABLE  `#__osmembership_states` ADD  `state_id` INT(11) NOT NULL DEFAULT '0';";
		$db->setQuery($sql);
		$db->execute();

		//Set country_id value the same with id
		$sql = 'UPDATE #__osmembership_states SET state_id=id';
		$db->setQuery($sql);
		$db->execute();
	}
	#Custom Fields table
	$fields = array_keys($db->getTableColumns('#__osmembership_fields'));
	if (!in_array('hide_on_membership_renewal', $fields))
	{
		$sql = "ALTER TABLE  `#__osmembership_fields` ADD  `hide_on_membership_renewal` TINYINT NOT NULL DEFAULT  '0';";
		$db->setQuery($sql);
		$db->execute();
	}


	if (!in_array('show_on_members_list', $fields))
	{
		$sql = "ALTER TABLE  `#__osmembership_fields` ADD  `show_on_members_list` TINYINT NOT NULL DEFAULT  '0';";
		$db->setQuery($sql);
		$db->execute();

		$defaultShowedFields = array("first_name", "last_name", "email", "organization");
		$sql                 = 'UPDATE #__osmembership_fields SET show_on_members_list = 1 WHERE name IN ("' . implode('","', $defaultShowedFields) . '")';
		$db->setQuery($sql);
		$db->execute();
	}

	if (!in_array('fee_field', $fields))
	{
		$sql = "ALTER TABLE  `#__osmembership_fields` ADD  `fee_field` TINYINT NOT NULL DEFAULT  '0';";
		$db->setQuery($sql);
		$db->execute();
	}
	if (!in_array('fee_values', $fields))
	{
		$sql = "ALTER TABLE  `#__osmembership_fields` ADD  `fee_values` TEXT NULL;";
		$db->setQuery($sql);
		$db->execute();
	}
	if (!in_array('fee_formula', $fields))
	{
		$sql = "ALTER TABLE  `#__osmembership_fields` ADD  `fee_formula` VARCHAR( 255 ) NULL;";
		$db->setQuery($sql);
		$db->execute();
	}

	if (!in_array('profile_field_mapping', $fields))
	{
		$sql = "ALTER TABLE  `#__osmembership_fields` ADD  `profile_field_mapping` VARCHAR( 50 ) NULL;";
		$db->setQuery($sql);
		$db->execute();
	}

	if (!in_array('depend_on_field_id', $fields))
	{
		$sql = "ALTER TABLE  `#__osmembership_fields` ADD  `depend_on_field_id` INT NOT NULL DEFAULT '0';";
		$db->setQuery($sql);
		$db->execute();
	}

	if (!in_array('depend_on_options', $fields))
	{
		$sql = "ALTER TABLE  `#__osmembership_fields` ADD  `depend_on_options` TEXT NULL;";
		$db->setQuery($sql);
		$db->execute();
	}

	if (!in_array('max_length', $fields))
	{
		$sql = "ALTER TABLE  `#__osmembership_fields` ADD  `max_length` INT NOT NULL DEFAULT  '0';";
		$db->setQuery($sql);
		$db->execute();
	}

	if (!in_array('place_holder', $fields))
	{
		$sql = "ALTER TABLE  `#__osmembership_fields` ADD   `place_holder` VARCHAR( 255 ) NULL;";
		$db->setQuery($sql);
		$db->execute();
	}
	if (!in_array('multiple', $fields))
	{
		$sql = "ALTER TABLE  `#__osmembership_fields` ADD  `multiple` TINYINT NOT NULL DEFAULT  '0';";
		$db->setQuery($sql);
		$db->execute();
	}

	if (!in_array('validation_rules', $fields))
	{
		$sql = "ALTER TABLE  `#__osmembership_fields` ADD  `validation_rules` VARCHAR( 255 ) NULL;";
		$db->setQuery($sql);
		$db->execute();
	}

	if (!in_array('validation_error_message', $fields))
	{
		$sql = "ALTER TABLE  `#__osmembership_fields` ADD  `validation_error_message` VARCHAR( 255 ) NULL;";
		$db->setQuery($sql);
		$db->execute();
	}
	$replace = false;
	if (!in_array('fieldtype', $fields))
	{
		$replace = true;
		$sql     = "ALTER TABLE  `#__osmembership_fields` ADD  `fieldtype` VARCHAR( 50 ) NULL;";
		$db->setQuery($sql);
		$db->execute();

		//Update field type , change it to something meaningful
		$typeMapping = array(
			0 => 'Text',
			1 => 'Textarea',
			2 => 'List',
			3 => 'Checkboxes',
			4 => 'Radio',
			5 => 'Date',
			6 => 'Heading',
			7 => 'Message',
			9 => 'File');

		foreach ($typeMapping as $key => $value)
		{
			$sql = "UPDATE #__osmembership_fields SET fieldtype='$value' WHERE field_type='$key'";
			$db->setQuery($sql);
			$db->execute();
		}

		$sql = "UPDATE #__osmembership_fields SET fieldtype='List', multiple=1 WHERE field_type='8'";
		$db->setQuery($sql);
		$db->execute();

		$sql = 'UPDATE #__osmembership_fields SET fieldtype="countries" WHERE name="country"';
		$db->setQuery($sql);
		$db->execute();
		//MySql, convert data to Json
		$sql = 'SELECT id, field_value FROM #__osmembership_field_value WHERE field_id IN (SELECT id FROM #__osmembership_fields WHERE field_type=3 OR field_type=8)';
		$db->setQuery($sql);
		$rowFieldValues = $db->loadObjectList();
		if (count($rowFieldValues))
		{
			foreach ($rowFieldValues as $rowFieldValue)
			{
				$fieldValue = $rowFieldValue->field_value;
				if (strpos($fieldValue, ',') !== false)
				{
					$fieldValue = explode(',', $fieldValue);
				}
				$fieldValue = json_encode($fieldValue);
				$sql        = 'UPDATE #__osmembership_field_value SET field_value=' . $db->quote($fieldValue) . ' WHERE id=' . $rowFieldValue->id;
				$db->setQuery($sql);
				$db->execute();
			}
		}
	}

	########1.6.3, migrate data to new fields API ###############################################
	$sql = 'SELECT COUNT(*) FROM #__osmembership_fields';
	$db->setQuery($sql);
	$total = $db->loadResult();
	if ($total)
	{

		$sql = 'SELECT name, published FROM #__osmembership_fields WHERE is_core=1';
		$db->setQuery($sql);
		$coreFields = $db->loadObjectList('name');
	}
	if (!$total || $replace)
	{
		$coreFieldsSql = JPATH_ADMINISTRATOR . '/components/com_osmembership/sql/fields.osmembership.sql';
		$sql           = JFile::read($coreFieldsSql);
		$queries       = $db->splitSql($sql);
		if (count($queries))
		{
			foreach ($queries as $query)
			{
				$query = trim($query);
				if ($query != '' && $query{0} != '#')
				{
					$db->setQuery($query);
					$db->execute();
				}
			}
		}
	}

	if ($replace && $total)
	{
		foreach ($coreFields as $name => $field)
		{
			$sql = 'UPDATE #__osmembership_fields SET published=' . (int) $field->published . ' WHERE name=' . $db->quote($name);
			$db->setQuery($sql);
			$db->execute();
		}
	}

	$sql = "SELECT id, validation_rules FROM #__osmembership_fields WHERE required = 1";
	$db->setQuery($sql);
	$fields = $db->loadObjectList();
	foreach ($fields as $field)
	{
		if (empty($field->validation_rules))
		{
			$sql = 'UPDATE #__osmembership_fields SET validation_rules = "validate[required]" WHERE id=' . $field->id;
			$db->setQuery($sql);
			$db->execute();
		}
	}

	// Allow access level for custom field
	$fields = array_keys($db->getTableColumns('#__osmembership_fields'));
	if (!in_array('access', $fields))
	{
		$sql = "ALTER TABLE  `#__osmembership_fields` ADD  `access` TINYINT NOT NULL DEFAULT  '0';";
		$db->setQuery($sql);
		$db->execute();

		$sql = 'UPDATE  #__osmembership_fields SET `access` = 1';
		$db->setQuery($sql);
		$db->execute();
	}

	####This code below is used for fixing the bugs in with not required fields in initial released of version 1.6.3##########
	$sql = "SELECT id, validation_rules FROM #__osmembership_fields WHERE required = 0";
	$db->setQuery($sql);
	$fields = $db->loadObjectList();
	foreach ($fields as $field)
	{
		if ($field->validation_rules == 'validate[required]')
		{
			$sql = 'UPDATE #__osmembership_fields SET validation_rules = "" WHERE id=' . $field->id;
			$db->setQuery($sql);
			$db->execute();
		}
	}

	$fields = array_keys($db->getTableColumns('#__osmembership_categories'));
	if (!in_array('access', $fields))
	{
		$sql = "ALTER TABLE  `#__osmembership_categories` ADD  `access` TINYINT NOT NULL DEFAULT  '1';";
		$db->setQuery($sql);
		$db->execute();

		$sql = 'UPDATE #__osmembership_categories SET `access`=1';
		$db->setQuery($sql);
		$db->execute();
	}
	if (!in_array('ordering', $fields))
	{
		$sql = "ALTER TABLE  `#__osmembership_categories` ADD  `ordering` INT NOT NULL DEFAULT  '0';";
		$db->setQuery($sql);
		$db->execute();

		$sql = 'UPDATE #__osmembership_categories SET `ordering`=id';
		$db->setQuery($sql);
		$db->execute();
	}
	if (!in_array('alias', $fields))
	{
		$sql = "ALTER TABLE  `#__osmembership_categories` ADD  `alias` varchar(255) NOT NULL DEFAULT '';";
		$db->setQuery($sql);
		$db->execute();

		$sql = 'SELECT id, title FROM #__osmembership_categories';
		$db->setQuery($sql);
		$rows = $db->loadObjectList();
		if (count($rows))
		{
			foreach ($rows as $row)
			{
				$alias = JApplication::stringURLSafe($row->title);
				$sql   = 'UPDATE #__osmembership_categories SET `alias`="' . $alias . '" WHERE id=' . $row->id;
				$db->setQuery($sql);
				$db->execute();
			}
		}
	}
	#Subscription plans table		
	$fields = array_keys($db->getTableColumns('#__osmembership_plans'));
	if (!in_array('subscription_length_unit', $fields))
	{
		$sql = "ALTER TABLE  `#__osmembership_plans` ADD  `subscription_length_unit` CHAR(1) NULL;";
		$db->setQuery($sql);
		$db->execute();


		//Need to update the length to reflect new unit
		$sql = 'SELECT id, subscription_length FROM #__osmembership_plans';
		$db->setQuery($sql);
		$rowPlans = $db->loadObjectList();
		for ($i = 0, $n = count($rowPlans); $i < $n; $i++)
		{
			$rowPlan = $rowPlans[$i];
			list($frequency, $length) = OSMembershipHelper::getRecurringSettingOfPlan($rowPlan->subscription_length);
			$sql = 'UPDATE #__osmembership_plans SET subscription_length=' . (int) $length . ', subscription_length_unit="' . $frequency . '" WHERE id=' . $rowPlan->id;
			$db->setQuery($sql);
			$db->execute();
		}
	}
	if (!in_array('access', $fields))
	{
		$sql = "ALTER TABLE  `#__osmembership_plans` ADD  `access` TINYINT NOT NULL DEFAULT  '1';";
		$db->setQuery($sql);
		$db->execute();

		$sql = 'UPDATE #__osmembership_plans SET `access`=1';
		$db->setQuery($sql);
		$db->execute();
	}

	if (!in_array('lifetime_membership', $fields))
	{
		$sql = "ALTER TABLE  `#__osmembership_plans` ADD  `lifetime_membership` TINYINT NOT NULL DEFAULT  '0';";
		$db->setQuery($sql);
		$db->execute();
	}

	if (!in_array('expired_date', $fields))
	{
		$sql = "ALTER TABLE  `#__osmembership_plans` ADD  `expired_date` DATETIME NULL AFTER  `price` ;";
		$db->setQuery($sql);
		$db->execute();
	}

	if (!in_array('recurring_subscription', $fields))
	{
		$sql = "ALTER TABLE  `#__osmembership_plans` ADD  `recurring_subscription` TINYINT NOT NULL DEFAULT  '0';";
		$db->setQuery($sql);
		$db->execute();
	}

	if (!in_array('enable_renewal', $fields))
	{
		$sql = "ALTER TABLE  `#__osmembership_plans` ADD  `enable_renewal` TINYINT NOT NULL DEFAULT  '1';";
		$db->setQuery($sql);
		$db->execute();

		$sql = 'UPDATE `#__osmembership_plans` SET `enable_renewal`=1 ';
		$db->setQuery($sql);
		$db->execute();
	}

	if (!in_array('trial_amount', $fields))
	{
		$sql = "ALTER TABLE  `#__osmembership_plans` ADD  `trial_amount` DECIMAL( 10, 2 ) NULL DEFAULT  '0';";
		$db->setQuery($sql);
		$db->execute();
	}

	if (!in_array('trial_duration', $fields))
	{
		$sql = "ALTER TABLE  `#__osmembership_plans` ADD  `trial_duration` INT NOT NULL DEFAULT  '0';";
		$db->setQuery($sql);
		$db->execute();
	}

	if (!in_array('trial_duration_unit', $fields))
	{
		$sql = "ALTER TABLE  `#__osmembership_plans` ADD  `trial_duration_unit` CHAR(1) NULL;";
		$db->setQuery($sql);
		$db->execute();
	}

	if (!in_array('number_payments', $fields))
	{
		$sql = "ALTER TABLE  `#__osmembership_plans` ADD  `number_payments` INT NOT NULL DEFAULT  '0';";
		$db->setQuery($sql);
		$db->execute();
	}

	if (!in_array('subscription_complete_url', $fields))
	{
		$sql = "ALTER TABLE  `#__osmembership_plans` ADD  `subscription_complete_url` TEXT NULL ;";
		$db->setQuery($sql);
		$db->execute();
	}

	if (!in_array('subscription_form_message', $fields))
	{
		$sql = "ALTER TABLE  `#__osmembership_plans` ADD  `subscription_form_message` TEXT NULL ;";
		$db->setQuery($sql);
		$db->execute();
	}


	if (!in_array('category_id', $fields))
	{
		$sql = "ALTER TABLE  `#__osmembership_plans` ADD  `category_id` INT NOT NULL DEFAULT  '0';";
		$db->setQuery($sql);
		$db->execute();
	}
	if (!in_array('alias', $fields))
	{
		$sql = "ALTER TABLE  `#__osmembership_plans` ADD  `alias` varchar(255) NOT NULL DEFAULT '';";
		$db->setQuery($sql);
		$db->execute();

		$sql = 'SELECT id, title FROM #__osmembership_plans';
		$db->setQuery($sql);
		$rows = $db->loadObjectList();
		if (count($rows))
		{
			foreach ($rows as $row)
			{
				$alias = JApplication::stringURLSafe($row->title);
				$sql   = 'UPDATE #__osmembership_plans SET `alias`="' . $alias . '" WHERE id=' . $row->id;
				$db->setQuery($sql);
				$db->execute();
			}
		}
	}
	if (!in_array('tax_rate', $fields))
	{
		$sql = "ALTER TABLE  `#__osmembership_plans` ADD  `tax_rate` DECIMAL( 10, 2 ) NULL DEFAULT  '0';";
		$db->setQuery($sql);
		$db->execute();
		//Set tax rate for the plan from configuration
		$taxRate = (float) OSMembershipHelper::getConfigValue('tax_rate');
		if ($taxRate > 0)
		{
			$sql = 'UPDATE #__osmembership_plans SET tax_rate=' . $taxRate;
			$db->setQuery($sql);
			$db->execute();
		}
	}

	if (!in_array('notification_emails', $fields))
	{
		$sql = "ALTER TABLE  `#__osmembership_plans` ADD  `notification_emails` varchar(255) NOT NULL DEFAULT '';";
		$db->setQuery($sql);
		$db->execute();
	}

	if (!in_array('paypal_email', $fields))
	{
		$sql = "ALTER TABLE  `#__osmembership_plans` ADD  `paypal_email` varchar(255) NOT NULL DEFAULT '';";
		$db->setQuery($sql);
		$db->execute();
	}
	//Change data type of short description to text, avoid support

	$sql = 'ALTER TABLE  `#__osmembership_plans` CHANGE  `short_description`  `short_description` MEDIUMTEXT  NULL DEFAULT NULL';
	$db->setQuery($sql);
	$db->execute();

	$sql = 'ALTER TABLE  `#__osmembership_fields` CHANGE  `description`  `description` MEDIUMTEXT  NULL DEFAULT NULL';
	$db->setQuery($sql);
	$db->execute();

	#Subscribers table
	$fields = array_keys($db->getTableColumns('#__osmembership_subscribers'));
	if (!in_array('payment_made', $fields))
	{
		$sql = "ALTER TABLE  `#__osmembership_subscribers` ADD  `payment_made` INT NOT NULL DEFAULT  '0';";
		$db->setQuery($sql);
		$db->execute();
	}

	if (!in_array('params', $fields))
	{
		$sql = "ALTER TABLE  `#__osmembership_subscribers` ADD  `params` TEXT NULL;";
		$db->setQuery($sql);
		$db->execute();
	}
	
	if (!in_array('recurring_profile_id', $fields))
	{
		$sql = "ALTER TABLE  `#__osmembership_subscribers` ADD  `recurring_profile_id` varchar(255) NOT NULL DEFAULT '';";
		$db->setQuery($sql);
		$db->execute();
	}
		
	if (!in_array('membership_id', $fields))
	{
		$sql = "ALTER TABLE  `#__osmembership_subscribers` ADD  `membership_id` INT NOT NULL DEFAULT  '0';";
		$db->setQuery($sql);
		$db->execute();

		//Update membership Id field
		$sql = 'SELECT id FROM #__osmembership_subscribers ORDER BY id';
		$db->setQuery($sql);
		$rows = $db->loadObjectList();
		if (count($rows))
		{
			$start = 1000;
			foreach ($rows as $row)
			{
				$sql = 'UPDATE #__osmembership_subscribers SET membership_id=' . $start . ' WHERE id=' . $row->id;
				$db->setQuery($sql);
				$db->execute();
				$start++;
			}
		}
	}

	if (!in_array('invoice_year', $fields))
	{
		$sql = "ALTER TABLE  `#__osmembership_subscribers` ADD  `invoice_year` INT NOT NULL DEFAULT  '0';";
		$db->setQuery($sql);
		$db->execute();

		$sql = 'UPDATE #__osmembership_subscribers SET `invoice_year` = YEAR(`created_date`)';
		$db->setQuery($sql);
		$db->execute();
	}
	if (!in_array('is_profile', $fields))
	{
		$sql = "ALTER TABLE  `#__osmembership_subscribers` ADD  `is_profile` TINYINT NOT NULL DEFAULT  '0';";
		$db->setQuery($sql);
		$db->execute();

		$sql = 'SELECT MIN(id) AS id FROM #__osmembership_subscribers WHERE user_id > 0 GROUP BY user_id';
		$db->setQuery($sql);
		$profileIds = $db->loadColumn();
		if (count($profileIds))
		{
			$sql = 'UPDATE #__osmembership_subscribers SET is_profile=1 WHERE id IN (' . implode(',', $profileIds) . ')';
			$db->setQuery($sql);
			$db->execute();
		}

		$sql = 'SELECT MIN(id) AS id FROM #__osmembership_subscribers WHERE user_id = 0 AND is_profile=0 GROUP BY email';
		$db->setQuery($sql);
		$profileIds = $db->loadColumn();
		if (count($profileIds))
		{
			$sql = 'UPDATE #__osmembership_subscribers SET is_profile=1 WHERE id IN (' . implode(',', $profileIds) . ')';
			$db->setQuery($sql);
			$db->execute();
		}
	}

	if (!in_array('invoice_number', $fields))
	{
		$sql = "ALTER TABLE  `#__osmembership_subscribers` ADD  `invoice_number` INT NOT NULL DEFAULT  '0';";
		$db->setQuery($sql);
		$db->execute();

		//Update membership Id field
		$sql = 'SELECT id FROM #__osmembership_subscribers ORDER BY id';
		$db->setQuery($sql);
		$rows = $db->loadObjectList();
		if (count($rows))
		{
			$start = 1;
			foreach ($rows as $row)
			{
				$sql = 'UPDATE #__osmembership_subscribers SET invoice_number=' . $start . ' WHERE id=' . $row->id;
				$db->setQuery($sql);
				$db->execute();
				$start++;
			}
		}
	}


	if (!in_array('profile_id', $fields))
	{
		$sql = "ALTER TABLE  `#__osmembership_subscribers` ADD  `profile_id` INT NOT NULL DEFAULT  '0';";
		$db->setQuery($sql);
		$db->execute();

		$sql = 'SELECT id, user_id, email FROM #__osmembership_subscribers WHERE is_profile=1';
		$db->setQuery($sql);
		$rowSubscribers = $db->loadObjectList();
		if (count($rowSubscribers))
		{
			foreach ($rowSubscribers as $rowSubscriber)
			{
				if ($rowSubscriber->user_id > 0)
				{
					$sql = 'UPDATE #__osmembership_subscribers SET profile_id=' . $rowSubscriber->id . ' WHERE email=' . $db->quote($rowSubscriber->email) . ' OR user_id=' . $rowSubscriber->user_id;
				}
				else
				{
					$sql = 'UPDATE #__osmembership_subscribers SET profile_id=' . $rowSubscriber->id . ' WHERE email=' . $db->quote($rowSubscriber->email);
				}
				$db->setQuery($sql);
				$db->execute();
			}
		}
	}

	if (!in_array('language', $fields))
	{
		$sql = "ALTER TABLE  `#__osmembership_subscribers` ADD  `language` VARCHAR( 10 ) NULL;";
		$db->setQuery($sql);
		$db->execute();
	}

	if (!in_array('username', $fields))
	{
		$sql = "ALTER TABLE  `#__osmembership_subscribers` ADD  `username` VARCHAR( 50 ) NULL;";
		$db->setQuery($sql);
		$db->execute();
	}

	if (!in_array('user_password', $fields))
	{
		$sql = "ALTER TABLE  `#__osmembership_subscribers` ADD  `user_password` VARCHAR(255) NULL;";
		$db->setQuery($sql);
		$db->execute();
	}

	if (!in_array('payment_processing_fee', $fields))
	{
		$sql = "ALTER TABLE  `#__osmembership_subscribers` ADD  `payment_processing_fee` DECIMAL( 10, 2 ) NULL DEFAULT  '0';";
		$db->setQuery($sql);
		$db->execute();
	}


	#Payment Plugins table	
	$fields = array_keys($db->getTableColumns('#__osmembership_plugins'));
	if (!in_array('support_recurring_subscription', $fields))
	{
		$sql = "ALTER TABLE  `#__osmembership_plugins` ADD  `support_recurring_subscription` TINYINT NOT NULL DEFAULT  '0';";
		$db->setQuery($sql);
		$db->execute();
	}

	$recurringSupportedPlugins = array('os_paypal', 'os_authnet');
	$sql                       = 'UPDATE #__osmembership_plugins SET support_recurring_subscription=1 WHERE name IN ("' . implode('","', $recurringSupportedPlugins) . '")';
	$db->setQuery($sql);
	$db->execute();

	$sql = 'SELECT COUNT(*) FROM #__osmembership_messages';
	$db->setQuery($sql);
	$total = $db->loadResult();

	if (!$total)
	{
		$pluginsSql = JPATH_ADMINISTRATOR . '/components/com_osmembership/sql/install.messages.sql';
		$sql        = JFile::read($pluginsSql);
		$queries    = $db->splitSql($sql);
		if (count($queries))
		{
			foreach ($queries as $query)
			{
				$query = trim($query);
				if ($query != '' && $query{0} != '#')
				{
					$db->setQuery($query);
					$db->execute();
				}
			}
		}
	}
	//Delete some files
	if (JFolder::exists(JPATH_ROOT . '/administrator/components/com_osmembership/libraries/legacy'))
	{
		JFolder::delete(JPATH_ROOT . '/administrator/components/com_osmembership/libraries/legacy');
	}
	if (JFile::exists(JPATH_ROOT . '/administrator/components/com_osmembership/libraries/factory.php'))
	{
		JFile::delete(JPATH_ROOT . '/administrator/components/com_osmembership/libraries/factory.php');
	}

	$publishedItems = array(
		'osmembership' => array(
			'user',
			'invoice',
		),
		'system'       => array(
			'osmembershipreminder',
			'osmembershipupdatestatus'
		)
	);

	foreach ($publishedItems as $folder => $plugins)
	{
		foreach ($plugins as $plugin)
		{
			$query = "SELECT COUNT(*) FROM  #__extensions WHERE element=" . $db->Quote($plugin) . " AND folder=" . $db->Quote($folder);
			$db->setQuery($query);
			$count = $db->loadResult();
			if ($count)
			{
				$query = "UPDATE #__extensions SET enabled=1 WHERE element=" . $db->Quote($plugin) . " AND folder=" . $db->Quote($folder);
				$db->setQuery($query);
				$db->execute();
			}
		}
	}
	$sql = "CREATE TABLE IF NOT EXISTS `#__osmembership_sefurls` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `md5_key` text,
          `query` text,
          PRIMARY KEY (`id`)
        ) DEFAULT CHARSET=utf8;
        ";
	$db->setQuery($sql);
	$db->execute();
	$db->truncateTable('#__osmembership_sefurls');


	if (!in_array($prefix . 'osmembership_taxes', $tables))
	{
		// Tax rules table
		$sql = "CREATE TABLE IF NOT EXISTS `#__osmembership_taxes` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `plan_id` int(11) DEFAULT NULL,
		  `country` varchar(255) DEFAULT NULL,
		  `rate` decimal(10,2) DEFAULT NULL,
		  `vies` tinyint(3) unsigned DEFAULT 0,
		  `published` tinyint(3) unsigned DEFAULT 0,
		  PRIMARY KEY (`id`)
		) DEFAULT CHARSET=utf8;
		";
		$db->setQuery($sql);
		$db->execute();

		$sql = 'SELECT id, tax_rate FROM #__osmembership_plans WHERE tax_rate > 0';
		$db->setQuery($sql);
		$taxRates = $db->loadObjectList();
		if (count($taxRates) > 0)
		{
			foreach($taxRates as $taxRate)
			{
				$sql = "INSERT INTO #__osmembership_taxes(plan_id, country, rate, vies, published) VALUES($taxRate->id, '', $taxRate->tax_rate, 0, 1)";
				$db->setQuery($sql);
				$db->execute();
			}
		}
	}
	$fields = array_keys($db->getTableColumns('#__osmembership_taxes'));
	if (!in_array('vies', $fields))
	{
		$sql = "ALTER TABLE  `#__osmembership_taxes` ADD  `vies` TINYINT NOT NULL DEFAULT  '0';";
		$db->setQuery($sql);
		$db->execute();
	}
}
