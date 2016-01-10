<?php
/**
 * @version        1.6.8
 * @package        Joomla
 * @subpackage     OSMembership
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2014 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die();

class OSMembershipController extends JControllerLegacy
{

	/**
	 * Display information
	 *
	 */
	public function display($cachable = false, $urlparams = array())
	{
		JFactory::getDocument()->addStylesheet(JUri::base(true) . '/components/com_osmembership/assets/css/style.css', 'text/css', null, null);
		JFactory::getDocument()->addStylesheet(JUri::base(true) . '/components/com_osmembership/assets/css/custom.css', 'text/css', null, null);
		$view = JRequest::getVar('view', '');
		if (!$view)
		{
			JRequest::setVar('view', 'plans');
			JRequest::setVar('layout', 'default');
		}
		$config = OSMembershipHelper::getConfig();
		if (@$config->load_jquery !== '0')
		{
			OSMembershipHelper::loadJQuery();
		}
		OSMembershipHelper::loadBootstrap(true);

		JHtml::_('script', OSMembershipHelper::getSiteUrl() . 'components/com_osmembership/assets/js/jquery-noconflict.js', false, false);
		parent::display($cachable, $urlparams);
	}

	/**
	 * Initialize process for renewing membership
	 */
	public function process_renew_membership()
	{
		$renewOptionId = JRequest::getVar('renew_option_id');
		if (!$renewOptionId)
		{
			JFactory::getApplication()->redirect('index.php', JText::_('OSM_INVALID_RENEW_MEMBERSHIP_OPTION'));
		}
		if (strpos($renewOptionId, '|') !== false)
		{
			$renewOptionArray = explode('|', $renewOptionId);
			JRequest::setVar('id', (int) $renewOptionArray[0]);
			JRequest::setVar('renew_option_id', (int) $renewOptionArray[1]);
		}
		else
		{
			JRequest::setVar('id', (int) $renewOptionId);
			JRequest::setVar('renew_option_id', OSM_DEFAULT_RENEW_OPTION_ID);
		}
		JRequest::setVar('view', 'register');
		JRequest::setVar('layout', 'default');
		$this->display();
	}

	/**
	 * Initialize process for upgrading membership
	 */
	public function process_upgrade_membership()
	{
		$upgradeOptionId = JRequest::getInt('upgrade_option_id');
		$db              = JFactory::getDbo();
		$query           = $db->getQuery(true);
		$query->select('to_plan_id')
			->from('#__osmembership_upgraderules')
			->where('id=' . $upgradeOptionId);
		$db->setQuery($query);
		$upgradeRule = $db->loadObject();
		if ($upgradeRule)
		{
			//Set Plan ID
			JRequest::setVar('id', $upgradeRule->to_plan_id);
			JRequest::setVar('view', 'register');
			JRequest::setVar('layout', 'default');
			$this->display();
		}
		else
		{
			JFactory::getApplication()->redirect('index.php', JText::_('OSM_INVALID_UPGRADE_MEMBERSHIP_OPTION'));
		}
	}

	/**
	 * Process the subscription
	 */
	public function process_subscription()
	{
		JRequest::checkToken() or jexit('Invalid Token');
		$config = OSMembershipHelper::getConfig();
		if ($config->enable_captcha)
		{
			$input         = JFactory::getApplication()->input;
			$captchaPlugin = JFactory::getApplication()->getParams()->get('captcha', JFactory::getConfig()->get('captcha'));
			$res           = JCaptcha::getInstance($captchaPlugin)->checkAnswer($input->post->get('recaptcha_response_field', '', 'string'));
			if (!$res)
			{
				JError::raiseWarning('', JText::_('OSM_INVALID_CAPTCHA_ENTERED'));
				JRequest::setVar('view', 'register');
				JRequest::setVar('layout', 'default');
				JRequest::setVar('id', JRequest::getInt('plan_id'));
				$this->display();

				return;
			}
		}
		$post  = JRequest::get('post', JREQUEST_ALLOWHTML);
		$model = $this->getModel('Register');
		$model->processSubscription($post);
	}

	/**
	 * Update user profile data
	 */
	public function update_profile()
	{
		$Itemid     = JRequest::getInt('Itemid');
		$data       = JRequest::get('post', JREQUEST_ALLOWHTML);
		$model      = $this->getModel('profile');
		$data['id'] = (int) $data['cid'][0];
		$model->updateProfile($data);
		//Redirect to the profile page
		JFactory::getApplication()->redirect('index.php?option=com_osmembership&view=profile&Itemid=' . $Itemid,
			JText::_('OSM_YOUR_PROFILE_UPDATED'));
	}

	/**
	 * Varify the payment and further process. Called by payment gateway when a payment completed
	 *
	 */
	public function payment_confirm()
	{
		$model = $this->getModel('Register');
		$model->paymentConfirm();
	}

	/**
	 * Varify the payment and further process. Called by payment gateway when a recurring payment happened
	 *
	 */
	public function recurring_payment_confirm()
	{
		$model = $this->getModel('register');
		$model->recurringPaymentConfirm();
	}

	/**
	 * Process downloading invoice for a subscription record
	 */
	public function download_invoice()
	{
		$id = JRequest::getInt('id');
		OSMembershipHelper::downloadInvoice($id);
	}

	/**
	 * Process download a file
	 */
	public function download_file()
	{
		$Itemid   = JRequest::getInt('Itemid');
		$filePath = 'media/com_osmembership/upload';
		$fileName = JRequest::getVar('file_name', '');
		if (file_exists(JPATH_ROOT . '/' . $filePath . '/' . $fileName))
		{
			while (@ob_end_clean()) ;
			OSMembershipHelper::processDownload(JPATH_ROOT . '/' . $filePath . '/' . $fileName, $fileName, true);
			exit();
		}
		else
		{
			JFactory::getApplication()->redirect('index.php?option=com_osmembership&Itemid=' . $Itemid, JText::_('OSM_FILE_NOT_EXIST'));
		}
	}

	/**
	 * Re-calculate subscription fee when subscribers choose a fee option on subscription form
	 *
	 * Called by ajax request. After calculation, the system will update the fee displayed on end users on subscription sign up form
	 */
	public function calculate_subscription_fee()
	{
		$db     = JFactory::getDbo();
		$query  = $db->getQuery(true);
		$config = OSMembershipHelper::getConfig();
		$planId = JRequest::getInt('plan_id', 0);
		$query->select('*')
			->from('#__osmembership_plans')
			->where('id=' . $planId);
		$db->setQuery($query);
		$rowPlan   = $db->loadObject();
		$rowFields = OSMembershipHelper::getProfileFields($planId);
		$data      = JRequest::get('post', JREQUEST_ALLOWHTML);
		$form      = new RADForm($rowFields);
		$form->setData($data)->bindData(true);
		$fees = OSMembershipHelper::calculateSubscriptionFee($rowPlan, $form, $data, $config, JFactory::getApplication()->input->get('payment_method', '', 'none'));
		echo json_encode($fees);
		JFactory::getApplication()->close();
	}

	/**
	 * Validate username, make sure it is allowed. In Joomla, username must be unique for each user
	 */
	public function validate_username()
	{
		$db         = JFactory::getDbo();
		$query      = $db->getQuery(true);
		$username   = JRequest::getVar('fieldValue');
		$validateId = JRequest::getVar('fieldId');
		$query->select('COUNT(*)')
			->from('#__users')
			->where('username="' . $username . '"');
		$db->setQuery($query);
		$total        = $db->loadResult();
		$arrayToJs    = array();
		$arrayToJs[0] = $validateId;
		if ($total)
		{
			$arrayToJs[1] = false;
		}
		else
		{
			$arrayToJs[1] = true;
		}
		echo json_encode($arrayToJs);
		JFactory::getApplication()->close();
	}

	/**
	 * Validate email, make sure it is valid before continue processing subscription
	 * In Joomla, each user must have an unique email address for account registration
	 *
	 */
	public function validate_email()
	{
		$app        = JFactory::getApplication();
		$db         = JFactory::getDbo();
		$user       = JFactory::getUser();
		$config     = OSMembershipHelper::getConfig();
		$query      = $db->getQuery(true);
		$email      = $app->input->get('fieldValue', '', 'string');
		$validateId = JRequest::getVar('fieldId');
		$query->select('COUNT(*)')
			->from('#__users')
			->where('email="' . $email . '"');
		$db->setQuery($query);
		$total = $db->loadResult();

		$arrayToJs    = array();
		$arrayToJs[0] = $validateId;
		if (!$total || $user->id || !$config->registration_integration)
		{
			$arrayToJs[1] = true;
		}
		else
		{
			$arrayToJs[1] = false;
		}
		echo json_encode($arrayToJs);
		JFactory::getApplication()->close();
	}

	/**
	 * Validate password to ensure that password is trong
	 */
	public function validate_password()
	{
		//Load language from user component
		$lang = JFactory::getLanguage();
		$tag  = $lang->getTag();
		if (!$tag)
		{
			$tag = 'en-GB';
		}
		$lang->load('com_users', JPATH_ROOT, $tag);
		$value            = JRequest::getVar('fieldValue');
		$validateId       = JRequest::getVar('fieldId');
		$params           = JComponentHelper::getParams('com_users');
		$minimumIntegers  = $params->get('minimum_integers');
		$minimumSymbols   = $params->get('minimum_symbols');
		$minimumUppercase = $params->get('minimum_uppercase');
		$validPassword    = true;
		$errorMessage     = '';
		if (!empty($minimumIntegers))
		{
			$nInts = preg_match_all('/[0-9]/', $value, $imatch);

			if ($nInts < $minimumIntegers)
			{
				$errorMessage  = JText::plural('COM_USERS_MSG_NOT_ENOUGH_INTEGERS_N', $minimumIntegers);
				$validPassword = false;
			}
		}
		if ($validPassword && !empty($minimumSymbols))
		{
			$nsymbols = preg_match_all('[\W]', $value, $smatch);

			if ($nsymbols < $minimumSymbols)
			{
				$errorMessage  = JText::plural('COM_USERS_MSG_NOT_ENOUGH_SYMBOLS_N', $minimumSymbols);
				$validPassword = false;
			}
		}
		if ($validPassword && !empty($minimumUppercase))
		{
			$nUppercase = preg_match_all("/[A-Z]/", $value, $umatch);
			if ($nUppercase < $minimumUppercase)
			{
				$errorMessage  = JText::plural('COM_USERS_MSG_NOT_ENOUGH_UPPERCASE_LETTERS_N', $minimumUppercase);
				$validPassword = false;
			}
		}
		$arrayToJs    = array();
		$arrayToJs[0] = $validateId;
		if (!$validPassword)
		{
			$arrayToJs[1] = false;
			$arrayToJs[2] = $errorMessage;
		}
		else
		{
			$arrayToJs[1] = true;
		}
		echo json_encode($arrayToJs);
		JFactory::getApplication()->close();
	}

	/**
	 * Get list of states for the selected country, using in AJAX request
	 */
	public function get_states()
	{
		$app         = JFactory::getApplication();
		$countryName = $app->input->get('country_name', '', 'string');
		$fieldName   = $app->input->get('field_name', 'state', 'string');
		$stateName   = $app->input->get('state_name', '', 'string');
		if (!$countryName)
		{
			$countryName = OSMembershipHelper::getConfigValue('default_country');
		}
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->clear();
		$query->select('required')
			->from('#__osmembership_fields')
			->where('name=' . $db->quote('state'));
		$db->setQuery($query);
		$required = $db->loadResult();
		($required) ? $class = 'validate[required]' : $class = '';

		$query->clear();
		$query->select('country_id')
			->from('#__osmembership_countries')
			->where('name=' . $db->quote($countryName));
		$db->setQuery($query);
		$countryId = $db->loadResult();
		//get state
		$query->clear();
		$query->select('state_2_code AS value, state_name AS text')
			->from('#__osmembership_states')
			->where('country_id=' . (int) $countryId)
			->where('published=1');
		$db->setQuery($query);
		$states  = $db->loadObjectList();
		$options = array();
		if (count($states))
		{
			$options[] = JHtml::_('select.option', '', JText::_('OSM_SELECT_STATE'));
			$options   = array_merge($options, $states);
		}
		else
		{
			$options[] = JHtml::_('select.option', 'N/A', JText::_('OSM_NA'));
		}
		echo JHtml::_('select.genericlist', $options, $fieldName, ' class="input-large ' . $class . '" id="' . $fieldName . '"', 'value', 'text', $stateName);
		$app->close();
	}

	public function get_paypl_ipn_data()
	{
		$ipnMessage = 'transaction_subject=Payment for daily test subscription, txn_type=subscr_payment, subscr_id=I-G2HM5VHMH1AR, last_name=Anestos, residence_country=GR, item_name=Payment for daily test subscription, payment_gross=0.01, mc_currency=USD, business=actionspree@gmail.com, payment_type=instant, protection_eligibility=Ineligible, verify_sign=AmU0IjUgy4Lh0b6ra2SXQwwS6mMPAq3L8-llXmU-avnyhuDy4h2oywxN, payer_status=unverified, payer_email=n_anestos@yahoo.gr, txn_id=8S976553V62584016, receiver_email=actionspree@gmail.com, first_name=Nikos, payer_id=6JGRRZE4K9KX8, receiver_id=UBQVZC66THDUW, payment_status=Completed, payment_fee=0.01, mc_fee=0.01, mc_gross=0.01, custom=819, charset=windows-1252, notify_version=3.8, ipn_track_id=a19c5c4cb56c7, ';
		$pairs      = explode(", ", $ipnMessage);
		$data       = array();
		foreach ($pairs as $pair)
		{
			$keyValue = explode('=', $pair);
			if (count($keyValue) == 2 && $keyValue[1])
			{
				$data[$keyValue[0]] = $keyValue[1];
			}
		}

		return $data;
	}
}