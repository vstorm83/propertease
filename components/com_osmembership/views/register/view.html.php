<?php
/**
 * @version        1.6.8
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2014 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
class OSMembershipViewRegister extends JViewLegacy
{

	function display($tpl = null)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);				
		$user = JFactory::getUser();
		$config = OSMembershipHelper::getConfig();
		$messageObj = OSMembershipHelper::getMessages();
		$fieldSuffix = OSMembershipHelper::getFieldSuffix();				
		JFactory::getDocument()->addScript(JUri::base(true) . '/components/com_osmembership/assets/js/paymentmethods.js');		
		$userId = $user->get('id');
		$Itemid = JRequest::getInt('Itemid', 0);
		if (!$Itemid)
		{
			$Itemid = OSMembershipHelper::getItemid();
		}			
		$planId = JRequest::getInt('id', 0);

		// Check to see whether this is a valid form or not
		if (!$planId)
		{			
			JFactory::getApplication()->redirect('index.php', JText::_('OSM_INVALID_MEMBERSHIP_PLAN'));
		}
		$query->select('*, title'.$fieldSuffix.' AS title')
			->from('#__osmembership_plans')
			->where('id='.$planId);				
		$db->setQuery($query);
		$plan = $db->loadObject();		
		if ($plan->published == 0 || !$plan)
		{			
			JFactory::getApplication()->redirect('index.php', JText::_('OSM_CANNOT_ACCESS_UNPUBLISHED_PLAN'));
			return;
		}		
		if (!in_array($plan->access, $user->getAuthorisedViewLevels()))
		{
			JFactory::getApplication()->redirect('index.php', JText::_('OSM_NOT_ALLOWED_PLAN'));
		}	

		// Check if user can subscribe to the plan
		if (!OSMembershipHelper::canSubscribe($plan))
		{
			if ($config->number_days_before_renewal)
			{
				// Redirect to memberhsip profile page
				$app = JFactory::getApplication();
				$app->enqueueMessage(JText::sprintf('OSM_COULD_NOT_RENEWAL', $config->number_days_before_renewal), 'message');
				$profileItemId = OSMembershipHelperRoute::findView('profile', $Itemid);
				$app->redirect(JRoute::_('index.php?option=com_osmembership&view=profile&Itemid='.$profileItemId));
			}
			else 
			{
				$app = JFactory::getApplication();
				$app->enqueueMessage(JText::_('OSM_YOU_ARE_NOT_ALLOWED_TO_SIGNUP'), 'message');				
				$app->redirect('index.php');									
			}						
		}		
		$paymentMethod = JRequest::getVar('payment_method', os_payments::getDefautPaymentMethod(), 'post');
		if (!$paymentMethod)
		{
			$paymentMethod = os_payments::getDefautPaymentMethod();
		}					
		$renewOptionId = JRequest::getInt('renew_option_id', 0);
		$upgradeOptionId = JRequest::getInt('upgrade_option_id', 0);		
		if ($renewOptionId)
		{
			$action = 'renew';											
		}
		elseif ($upgradeOptionId)
		{
			$action = 'upgrade';										
		}
		else
		{
			$action = 'subscribe';										
		}
		###############Payment Methods parameters###############################									
		$lists['exp_month'] = JHtml::_('select.integerlist', 1, 12, 1, 'exp_month', ' id="exp_month" class="input-small" ', JRequest::getVar('exp_month', date('m'), 'post'), '%02d');
		$currentYear = date('Y');
		$lists['exp_year'] = JHtml::_('select.integerlist', $currentYear, $currentYear + 10, 1, 'exp_year', ' id="exp_year" class="input-small" ', JRequest::getVar('exp_year', date('Y'), 'post'));
		$options = array();
		$cardTypes = explode(',', $config->enable_cardtypes);
		if (in_array('Visa', $cardTypes))
		{
			$options[] = JHtml::_('select.option', 'Visa', JText::_('OSM_VISA_CARD'));
		}
		if (in_array('MasterCard', $cardTypes))
		{
			$options[] = JHtml::_('select.option', 'MasterCard', JText::_('OSM_MASTER_CARD'));
		}
		
		if (in_array('Discover', $cardTypes))
		{
			$options[] = JHtml::_('select.option', 'Discover', JText::_('OSM_DISCOVER'));
		}
		if (in_array('Amex', $cardTypes))
		{
			$options[] = JHtml::_('select.option', 'Amex', JText::_('OSM_AMEX'));
		}
		$lists['card_type'] = JHtml::_('select.genericlist', $options, 'card_type', ' class="inputbox" ', 'value', 'text', JRequest::getVar('card_type', null, 'post'));		
		$options = array();
		$options[] = JHtml::_('select.option', 'CHECKING', JText::_('OSM_BANK_TYPE_CHECKING'));
		$options[] = JHtml::_('select.option', 'BUSINESSCHECKING', JText::_('OSM_BANK_TYPE_BUSINESSCHECKING'));
		$options[] = JHtml::_('select.option', 'SAVINGS', JText::_('OSM_BANK_TYPE_SAVINGS'));
		$lists['x_bank_acct_type'] = JHtml::_('select.genericlist', $options, 'x_bank_acct_type', ' class="inputbox" ', 'value', 'text', JRequest::getVar('x_bank_acct_type', null, 'post'));		

		// IDEAL Mollie payment plugin support
		$idealEnabled = OSMembershipHelper::idealEnabled();		
		if ($idealEnabled)
		{
			$bankLists = OSMembershipHelper::getBankLists();
			$options = array();
			foreach ($bankLists as $bankId => $bankName)
			{
				$options[] = JHtml::_('select.option', $bankId, $bankName);
			}
			$lists['bank_id'] = JHtml::_('select.genericlist', $options, 'bank_id', ' class="inputbox" ', 'value', 'text', JRequest::getInt('bank_id'));
		}		
		if ($plan->recurring_subscription)
		{
			$onlyRecurring = 1;
		}
		else
		{
			$onlyRecurring = 0;
		}
		if ($action == 'renew' || $action == 'upgrade')
		{
			$methods = os_payments::getPaymentMethods(true, $onlyRecurring);
		}
		else
		{
			$methods = os_payments::getPaymentMethods(true, $onlyRecurring);
		}	
		if (count($methods) == 0) 
		{
			JFactory::getApplication()->redirect('index.php', JText::_('OSM_NEED_TO_PUBLISH_PLUGIN'));
		}

		// Check to see if there is payment processing fee or not
		$showPaymentFee = false;
		foreach($methods as $method)
		{
			if ($method->paymentFee)
			{
				$showPaymentFee = true;
				break;
			}
		}
		$this->showPaymentFee = $showPaymentFee;

		$rowFields = OSMembershipHelper::getProfileFields($planId, true, null, $action);
		if (isset($_POST['first_name']))
		{
			$data = JRequest::get('post', JREQUEST_ALLOWHTML);
		}
		else
		{
			$data = array();
			if ($userId)
			{
				// Check to see if this user has profile data already
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
				elseif (JPluginHelper::isEnabled('user', 'profile'))
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
		}
		
		if ($userId && !isset($data['first_name']))
		{
			// Load the name from Joomla default name
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
			$data['email'] = $user->email;
		}
		if (!isset($data['country']) || !$data['country'])
		{
			$data['country'] = $config->default_country;
		}

		$countryCode = OSMembershipHelper::getCountryCode($data['country']);
		// Get data
		$form = new RADForm($rowFields);
		$form->setData($data)->bindData(true);
		$form->prepareFormFields('calculateSubscriptionFee();');		
		$data['renew_option_id'] = $renewOptionId;
		$data['upgrade_option_id'] = $upgradeOptionId;
		$data['act'] = $action;
		$fees = OSMembershipHelper::calculateSubscriptionFee($plan, $form, $data, $config, $paymentMethod);
		$amount = $fees['amount']; 									
		if ($action == 'renew')
		{			
			if (strlen(strip_tags($messageObj->{'subscription_renew_form_msg'.$fieldSuffix})))
			{
				$message = $messageObj->{'subscription_renew_form_msg'.$fieldSuffix};
			}
			else
			{
				$message = $messageObj->subscription_renew_form_msg;
			}
			if ($renewOptionId == OSM_DEFAULT_RENEW_OPTION_ID)
			{
				switch ($plan->subscription_length_unit) 
				{
					case 'D':
						$text = $plan->subscription_length > 1 ? JText::_('OSM_DAYS') : JText::_('OSM_DAY');
						break ;
					case 'W' :
						$text = $plan->subscription_length > 1 ? JText::_('OSM_WEEKS') : JText::_('OSM_WEEK');
						break ;
					case 'M' :
						$text = $plan->subscription_length > 1 ? JText::_('OSM_MONTHS') : JText::_('OSM_MONTH');
						break ;
					case 'Y' :
						$text = $plan->subscription_length > 1 ? JText::_('OSM_YEARS') : JText::_('OSM_YEAR');
						break ;
				}																
				$message = str_replace('[NUMBER_DAYS] days', $plan->subscription_length.' '. $text, $message);								
			}
			else
			{
				$query->clear();
				$query->select('number_days')
					->from('#__osmembership_renewrates')
					->where('id='.$renewOptionId);				
				$db->setQuery($query);											
				$numberDays = $db->loadResult();
				$message = str_replace('[NUMBER_DAYS]', $numberDays, $message);
			}						
			$message = str_replace('[PLAN_TITLE]', $plan->title, $message);
			$message = str_replace('[AMOUNT]', OSMembershipHelper::formatCurrency($amount, $config), $message);		
		}
		elseif ($action == 'upgrade')
		{					
			if (strlen(strip_tags($messageObj->{'subscription_upgrade_form_msg'.$fieldSuffix})))
			{
				$message = $messageObj->{'subscription_upgrade_form_msg'.$fieldSuffix};
			}
			else
			{
				$message = $messageObj->subscription_upgrade_form_msg;
			}		
			$query->clear();
			$query->select('b.title')
				->from('#__osmembership_upgraderules AS a')
				->innerJoin('#__osmembership_plans AS b ON a.from_plan_id=b.id')
				->where('a.id='.$upgradeOptionId);			
			$db->setQuery($query);
			$fromPlan = $db->loadResult();
			$message = str_replace('[PLAN_TITLE]', $plan->title, $message);
			$message = str_replace('[AMOUNT]', OSMembershipHelper::formatCurrency($amount, $config), $message);
			$message = str_replace('[FROM_PLAN_TITLE]', $fromPlan, $message);		
		}
		else
		{
			if (strlen(strip_tags($plan->{'subscription_form_message'.$fieldSuffix})) || strlen(strip_tags($plan->subscription_form_message)))
			{
				if (strlen(strip_tags($plan->{'subscription_form_message'.$fieldSuffix})))
				{
					$message = $plan->{'subscription_form_message'.$fieldSuffix};
				}
				else
				{
					$message = $plan->subscription_form_message;
				}
					
			}
			else
			{
				if (strlen(strip_tags($messageObj->{'subscription_form_msg'.$fieldSuffix})))
				{
					$message = $messageObj->{'subscription_form_msg'.$fieldSuffix};
				}
				else
				{
					$message = $messageObj->subscription_form_msg;
				}
			}
			if ($plan->recurring_subscription)
			{
				//We will first need to detect regular duration								
				if ($plan->trial_duration)
				{
					$trialPeriorText = JText::_('OSM_TRIAL_RECURRING_SUBSCRIPTION_PERIOR');					
					$trialPeriorText = str_replace('[TRIAL_DURATION]', $plan->trial_duration, $trialPeriorText);					
					switch ($plan->trial_duration_unit)
					{
						case 'D':
							$trialPeriorText = str_replace('[TRIAL_DURATION_UNIT]', $plan->trial_duration > 1 ? JText::_('OSM_DAYS') : JText::_('OSM_DAY'), $trialPeriorText);
							break;
						case 'W':
							$trialPeriorText = str_replace('[TRIAL_DURATION_UNIT]', $plan->trial_duration > 1 ? JText::_('OSM_WEEKS') : JText::_('OSM_WEEK'), $trialPeriorText);
							break;
						case 'M':
							$trialPeriorText = str_replace('[TRIAL_DURATION_UNIT]', $plan->trial_duration > 1 ? JText::_('OSM_MONTHS') : JText::_('OSM_MONTH'), $trialPeriorText);
							break;
						case 'Y':
							$trialPeriorText = str_replace('[TRIAL_DURATION_UNIT]', $plan->trial_duration > 1 ? JText::_('OSM_YEARS') : JText::_('OSM_YEAR'), $trialPeriorText);
							break;
						default:
							$trialPeriorText = str_replace('[TRIAL_DURATION_UNIT]', $plan->trial_duration > 1 ? JText::_('OSM_DAYS') : JText::_('OSM_DAY'), $trialPeriorText);
							break;
					}
					$this->trialPeriorText = $trialPeriorText;										
				}				
				$length = $plan->subscription_length;
				$regularPeriorText = JText::_('OSM_REGULAR_SUBSCRIPTION_PERIOR');								
				$regularPeriorText = str_replace('[REGULAR_DURATION]', $length, $regularPeriorText);
				switch ($plan->subscription_length_unit) 
				{
					case 'D':
						$regularPeriorText = str_replace('[REGULAR_DURATION_UNIT]', $length > 1 ? JText::_('OSM_DAYS') : JText::_('OSM_DAY'), $regularPeriorText);
						break;
					case 'W':
						$regularPeriorText = str_replace('[REGULAR_DURATION_UNIT]', $length > 1 ? JText::_('OSM_WEEKS') : JText::_('OSM_WEEK'), $regularPeriorText);
						break;
					case 'M':
						$regularPeriorText = str_replace('[REGULAR_DURATION_UNIT]', $length > 1 ? JText::_('OSM_MONTHS') : JText::_('OSM_MONTH'), $regularPeriorText);
						break;
					case 'Y':
						$regularPeriorText = str_replace('[REGULAR_DURATION_UNIT]', $length > 1 ? JText::_('OSM_YEARS') : JText::_('OSM_YEAR'), $regularPeriorText);
						break;
					default:
						$regularPeriorText = str_replace('[REGULAR_DURATION_UNIT]', $length > 1 ? JText::_('OSM_DAYS') : JText::_('OSM_DAY'), $regularPeriorText);
						break;
				}
				$this->regularPeriorText = $regularPeriorText;										
				$message = str_replace('[PLAN_TITLE]', $plan->title, $message);				
				$message = str_replace('[AMOUNT]', OSMembershipHelper::formatCurrency($amount, $config), $message);				
			}
			else
			{				
				$message = str_replace('[PLAN_TITLE]', $plan->title, $message);
				$message = str_replace('[AMOUNT]', OSMembershipHelper::formatCurrency($amount, $config), $message);
			}
		}	
		// Implement Joomla core recpatcha
		$showCaptcha = 0;
		if ($config->enable_captcha)
		{
			$captchaPlugin = JFactory::getApplication()->getParams()->get('captcha', JFactory::getConfig()->get('captcha'));
			if ($captchaPlugin)
			{
				$showCaptcha = 1;
				$this->captcha = JCaptcha::getInstance($captchaPlugin)->display('dynamic_recaptcha_1', 'dynamic_recaptcha_1', 'required');
			}
			else
			{
				JFactory::getApplication()->enqueueMessage(JText::_('OSM_CAPTCHA_NOT_ACTIVATED_IN_YOUR_SITE'), 'error');
			}
		}
		// Assign variables to template
		$this->userId = $userId;
		$this->paymentMethod = $paymentMethod;		
		$this->lists = $lists;
		$this->Itemid = $Itemid;
		$this->config = $config;						
		$this->plan = $plan;
		$this->methods = $methods;
		$this->idealEnabled = $idealEnabled;												
		$this->action = $action;		
		$this->renewOptionId = $renewOptionId;
		$this->upgradeOptionId = $upgradeOptionId;
		$this->message = $message;				
		$this->form = $form;				
		$this->fees = $fees;
		$this->showCaptcha = $showCaptcha;
		$this->countryBaseTax = (int) OSMembershipHelper::isCountryBaseTax();
		$this->taxRate = OSMembershipHelper::calculateTaxRate($planId);
		$this->countryCode = $countryCode;

		parent::display($tpl);
	}	
}