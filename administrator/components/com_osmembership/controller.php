<?php
/**
 * @version		1.6.8
 * @package		Joomla
 * @subpackage	Membership Pro
 * @author  Tuan Pham Ngoc
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */

// no direct access
defined('_JEXEC') or die();

/**
 * Membership Pro controller
 *
 * @package		Joomla
 * @subpackage	Membership Pro
 */
class OSMembershipController extends JControllerLegacy
{

	/**
	 * Constructor function
	 *
	 * @param array $config
	 */
	function __construct($config = array())
	{
		parent::__construct($config);
	}

	/**
	 * Display information
	 *
	 */
	public function display($cachable = false, $urlparams = array())
	{
		$view = JRequest::getVar('view', '');
		if (!$view)
		{
			JRequest::setVar('view', 'dashboard');
		}
		parent::display($cachable, $urlparams);
		OSMembershipHelper::addSubmenus(JRequest::getVar('view', 'plans'));
		OSMembershipHelper::displayCopyRight();
	}

    /**
     * Check to see the installed version is up to date or not
     *
     * @return int 0 : error, 1 : Up to date, 2 : outof date
     */
    function check_update()
    {
        $installedVersion = OSMembershipHelper::getInstalledVersion();
        $result = array();
        $result['status'] = 0;
        if (function_exists('curl_init'))
        {
            $url = 'http://joomdonationdemo.com/versions/membershippro.txt';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $latestVersion = curl_exec($ch);
            curl_close($ch);
            if ($latestVersion)
            {
                if (version_compare($latestVersion, $installedVersion, 'gt'))
                {
                    $result['status'] = 2;
                    $result['message'] = JText::sprintf('OSM_UPDATE_CHECKING_UPDATEFOUND', $latestVersion);
                }
                else
                {
                    $result['status'] = 1;
                    $result['message'] = JText::_('OSM_UPDATE_CHECKING_UPTODATE');
                }
            }
        }
        echo json_encode($result);
        JFactory::getApplication()->close();
    }
	

	/**
	 * Upgrade database schema
	 */
	function upgrade()
	{
		require_once JPATH_COMPONENT . '/install.osmembership.php';
		com_install();
	}

	function download_invoice()
	{
		$id = JRequest::getInt('id');
		OSMembershipHelper::downloadInvoice($id);
	}

	function download_file()
	{
		$Itemid = JRequest::getInt('Itemid');
		$filePath = 'media/com_osmembership/upload';
		$fileName = JRequest::getVar('file_name', '');
		if (file_exists(JPATH_ROOT . '/' . $filePath . '/' . $fileName))
		{
			while (@ob_end_clean());
			OSMembershipHelper::processDownload(JPATH_ROOT . '/' . $filePath . '/' . $fileName, $fileName, true);
			exit();
		}
		else
		{
			$mainframe = JFactory::getApplication();
			$mainframe->redirect('index.php?option=com_osmembership&Itemid=' . $Itemid, JText::_('OSM_FILE_NOT_EXIST'));
		}
	}

	function import_subscribers()
	{
		$model = $this->getModel('import');
		$numberSubscribers = $model->store();
		if ($numberSubscribers === false)
		{
			$this->setRedirect('index.php?option=com_osmembership&view=import', JText::_('OSM_ERROR_IMPORT_SUBSCRIBERS'));
		}
		else
		{
			$this->setRedirect('index.php?option=com_osmembership&view=subscribers', 
				JText::sprintf('OSM_NUMNER_SUBSCRIBERS_IMPORTED', $numberSubscribers));
		}
	}

	public function get_field_options()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$fieldId = JFactory::getApplication()->input->getInt('field_id');
		$query->select('`values`')
		->from('#__eb_fields')
		->where('id='.$fieldId);
		$db->setQuery($query);
		$options = explode("\r\n", $db->loadResult());		
	}		
	
	public function reset_urls()
	{
		$db = JFactory::getDbo();
		$db->truncateTable('#__osmembership_sefurls');
		$this->setRedirect('index.php?option=com_osmembership&view=dashboard', JText::_('SEF urls has successfully been reset'));
	}

	/**
	 * Get profile data of the subscriber, using for json format
	 *
	 */
	function get_profile_data()
	{
		$config = OSMembershipHelper::getConfig();
		$input = JFactory::getApplication()->input;
		$userId = $input->getInt('user_id', 0);
		$planId = $input->getInt('plan_id');
		$data = array();
		if ($userId && $planId)
		{
			$rowFields = OSMembershipHelper::getProfileFields($planId, true);
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->clear();
			$query->select('*')
				->from('#__osmembership_subscribers')
				->where('user_id=' . $userId.' AND is_profile=1');
			$db->setQuery($query);
			$rowProfile = $db->loadObject();
			if ($rowProfile)
			{
				$data = OSMembershipHelper::getProfileData($rowProfile, $planId, $rowFields);
			}
			elseif (JPluginHelper::isEnabled('user', 'profile') && !$config->cb_integration)
			{
				$syncronizer = new RADSynchronizerJoomla();
				$mappings = array();
				foreach ($rowFields as $rowField)
				{
					if ($rowField->profile_field_mapping)
					{
						$mappings[$rowField->name] = $rowField->profile_field_mapping;
					}
				}
				$data = $syncronizer->getData($userId, $mappings);
			}
			else
			{
				// Trigger plugin to get data
				$mappings = array();
				foreach ($rowFields as $rowField)
				{
					if ($rowField->field_mapping)
					{
						$mappings[$rowField->name] = $rowField->field_mapping;
					}
				}
				JPluginHelper::importPlugin( 'osmembership' );
				$dispatcher = JDispatcher::getInstance();
				$results = $dispatcher->trigger( 'onGetProfileData', array($userId, $mappings));
				if (count($results))
				{
					foreach($results as $res)
					{
						if (is_array($res) && count($res))
						{
							$data = $res;
							break;
						}
					}
				}
			}
		}

		if ($userId && !isset($data['first_name']))
		{
			//Load the name from Joomla default name
			$user = JFactory::getUser($userId);
			$name = $user->name;
			if ($name)
			{
				$pos = strpos($name, ' ');
				if ($pos !== false)
				{
					$data['first_name'] = substr($name, 0, $pos);
					$data['last_name'] =  substr($name, $pos + 1);
				}
				else
				{
					$data['first_name'] = $name;
					$data['last_name'] = '';
				}
			}
		}
		if ($userId && !isset($data['email']))
		{
			$user = JFactory::getUser($userId);
			$data['email'] = $user->email;
		}
		echo json_encode($data);
		JFactory::getApplication()->close();
	}

	/**
	 * Build EU tax rules
	 */
	public function build_eu_tax_rules()
	{
		$db = JFactory::getDbo();
		$db->truncateTable('#__osmembership_taxes');
		$defaultCountry     = OSmembershipHelper::getConfigValue('default_country');
		$defaultCountryCode = OSMembershipHelper::getCountryCode($defaultCountry);
		// Without VAT number, use local tax rate
		foreach (OSMembershipHelperEuvat::$europeanUnionVATInformation as $countryCode => $vatInfo)
		{
			$countryName    = $db->quote($vatInfo[0]);
			$countryTaxRate = OSMembershipHelperEuvat::getEUCountryTaxRate($countryCode);
			$sql            = "INSERT INTO #__osmembership_taxes(plan_id, country, rate, vies, published) VALUES(0, $countryName, $countryTaxRate, 0, 1)";
			$db->setQuery($sql);
			$db->execute();

			if ($countryCode == $defaultCountryCode)
			{
				$localTaxRate = OSMembershipHelperEuvat::getEUCountryTaxRate($defaultCountryCode);
				$sql          = "INSERT INTO #__osmembership_taxes(plan_id, country, rate, vies, published) VALUES(0, $countryName, $localTaxRate, 1, 1)";
				$db->setQuery($sql);
				$db->execute();
			}
		}

		$this->setRedirect('index.php?option=com_osmembership&view=taxes', JText::_('EU Tax Rules were successfully created'));
	}
}