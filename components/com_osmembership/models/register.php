<?php
/**
 * @version		1.6.8
 * @package		Joomla
 * @subpackage	Membership Pro
 * @author  Tuan Pham Ngoc
 * @copyright	Copyright (C) 2012 - 2014 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
class OSMembershipModelRegister extends JModelLegacy
{

	/**
	 * Process Subscription 
	 *
	 * @param array $data
	 */
	function processSubscription($data)
	{
		jimport('joomla.user.helper');
		$db = JFactory::getDbo();
		$row = JTable::getInstance('OsMembership', 'Subscriber');
		$query = $db->getQuery(true);
		$config = OSMembershipHelper::getConfig();
		$user = JFactory::getUser();
		$userId = $user->get('id');
		$nullDate = $db->getNullDate();
		$fieldSuffix = OSMembershipHelper::getFieldSuffix();
		if (!$userId && $config->registration_integration)
		{
			//Store user account into Joomla users database
            if ($config->create_account_when_membership_active !== '1')
            {
                $userId = OSMembershipHelper::saveRegistration($data);
            }
            else
            {
                //Encrypt the password and store into  #__osmembership_subscribers table and create the account layout
                $privateKey = md5(JFactory::getConfig()->get('secret'));
                $key = new JCryptKey('simple', $privateKey, $privateKey);
                $crypt = new JCrypt(new JCryptCipherSimple, $key);
                $data['user_password'] = $crypt->encrypt($data['password1']);
            }
		}
		$data['transaction_id'] = strtoupper(JUserHelper::genRandomPassword(16));
		$row->bind($data);
		$row->published = 0;
		$row->created_date = JFactory::getDate()->toSql();
		$row->user_id = $userId;
		while (true)
		{
			$subscriptionCode = JUserHelper::genRandomPassword(10);
			$query->select('COUNT(*)')
				->from('#__osmembership_subscribers')
				->where('subscription_code='.$db->quote($subscriptionCode));			
			$db->setQuery($query);
			$total = $db->loadResult();
			if (!$total)
			{
				break;
			}
		}
		$row->subscription_code = $subscriptionCode;
		$query->clear();
		$query->select('id')
			->from('#__osmembership_subscribers')
			->where("is_profile=1 AND ((user_id=$userId AND user_id>0) OR email='$row->email')");		
		$db->setQuery($query);
		$profileId = $db->loadResult();
		if ($profileId)
		{
			$row->is_profile = 0;
			$row->profile_id = $profileId;
		}
		else
		{
			$row->is_profile = 1;
		}
		$row->language = JFactory::getLanguage()->getTag();
		$query->clear();
		$query->select('*, title' . $fieldSuffix . ' AS title')
			->from('#__osmembership_plans')
			->where('id=' . (int) $data['plan_id']);				
		$db->setQuery($query);
		$rowPlan = $db->loadObject();		
		$rowFields = OSMembershipHelper::getProfileFields($row->plan_id, false);
		$form = new RADForm($rowFields);
		$form->setData($data)->bindData(true);
		$fees = OSMembershipHelper::calculateSubscriptionFee($rowPlan, $form, $data, $config, $row->payment_method);
		$action = $data['act'];
		if ($action == 'renew')
		{
			$renewOptionId = (int) $data['renew_option_id'];
			if ($renewOptionId == OSM_DEFAULT_RENEW_OPTION_ID)
			{
				$dateIntervalSpec = 'P' . $rowPlan->subscription_length . $rowPlan->subscription_length_unit;
			}
			else
			{
				$query->clear();
				$query->select('number_days')
					->from('#__osmembership_renewrates')
					->where('id=' . (int) $data['renew_option_id']);								
				$db->setQuery($query);
				$numberDays = (int)$db->loadResult();
				$dateIntervalSpec = 'P' . $numberDays . 'D';
			}
		}
		elseif ($action == 'upgrade')
		{
			$dateIntervalSpec = 'P' . $rowPlan->subscription_length . $rowPlan->subscription_length_unit;
		}
		else
		{
			if ($rowPlan->recurring_subscription && $rowPlan->trial_duration)
			{
				$dateIntervalSpec = 'P' . $rowPlan->trial_duration . $rowPlan->trial_duration_unit;
			}
			else
			{
				$dateIntervalSpec = 'P' . $rowPlan->subscription_length . $rowPlan->subscription_length_unit;
			}
		}
		$maxDate = null;
		if ($row->user_id > 0)
		{
			//Subscriber, user existed
			$query->clear();
			$query->select('MAX(to_date)')
				->from('#__osmembership_subscribers')
				->where('user_id=' . $row->user_id . ' AND plan_id=' . $row->plan_id .' AND (published=1 OR (published = 0 AND payment_method LIKE "os_offline%"))');			
			$db->setQuery($query);
			$maxDate = $db->loadResult();
		}
		if ($maxDate)
		{
			$date = JFactory::getDate($maxDate);
			$row->from_date = $date->add(new DateInterval('P1D'))->toSql();
		}
		else
		{
			$date = JFactory::getDate();
			$row->from_date = $date->toSql();
		}
		if ($rowPlan->expired_date && $rowPlan->expired_date != $nullDate)
		{
			$expiredDate = JFactory::getDate($rowPlan->expired_date);
			$expiredDate->setTime(0, 0, 0);
			$startDate = clone $date;
			$startDate->setTime(0, 0, 0);
			if ($startDate >= $expiredDate)
			{
				$date->setDate($date->year + 1, $expiredDate->month, $expiredDate->day);
				$row->to_date = $date->toSql();
			}
			else
			{
				$row->to_date = $rowPlan->expired_date;
			}
		}
		else
		{
			if ($rowPlan->lifetime_membership)
			{
				$row->to_date = '2099-12-31 23:59:59';
			}
			else
			{
				$row->to_date = $date->add(new DateInterval($dateIntervalSpec))->toSql();
			}
		}
		$couponCode = JRequest::getVar('coupon_code', '');
		$couponId = 0;
		if ($couponCode && $fees['coupon_valid'])
		{
			$query->clear();
			$query->select('id')
				->from('#__osmembership_coupons')
				->where('code='.$db->quote($couponCode));			
			$db->setQuery($query);
			$couponId = (int) $db->loadResult();			
			$query->clear();
			$query->update('#__osmembership_coupons')
				->set('used=used+1')
				->where('id='.$couponId);			
			$db->setQuery($query);
			$db->execute();
		}
		$row->amount = $fees['amount'];
		$row->discount_amount = $fees['discount_amount'];
		$row->tax_amount = $fees['tax_amount'];
		$row->payment_processing_fee = $fees['payment_processing_fee'];
		$row->coupon_id = $couponId;
		$row->gross_amount = $fees['gross_amount'];
		$row->store();
		if (!$row->profile_id)
		{
			$row->profile_id = $row->id;
			$row->store();
		}
		$data['amount'] = $fees['gross_amount'];
		//Store custom field data				
		$form->storeData($row->id, $data);
		//Syncronize profile data for other records				
		OSMembershipHelper::syncronizeProfileData($row, $data);
		JPluginHelper::importPlugin('osmembership');
		$dispatcher = JDispatcher::getInstance();
		$dispatcher->trigger('onAfterStoreSubscription', array($row));
		$data['regular_price'] = $fees['regular_gross_amount'];
		$data['trial_amount'] = $fees['trial_gross_amount'];
		if ($data['amount'] > 0 || $rowPlan->recurring_subscription)
		{
			switch ($action)
			{
				case 'renew':
					$itemName = JText::_('OSM_PAYMENT_FOR_RENEW_SUBSCRIPTION');
					$itemName = str_replace('[PLAN_TITLE]', $rowPlan->title, $itemName);
					break;
				case 'upgrade':
					$itemName = JText::_('OSM_PAYMENT_FOR_UPGRADE_SUBSCRIPTION');
					$itemName = str_replace('[PLAN_TITLE]', $rowPlan->title, $itemName);
					//Get from Plan Title
					$query->clear();
					$query->select('a.title')
						->from('#__osmembership_plans AS a')
						->innerJoin('#__osmembership_upgraderules AS b ON a.id=b.from_plan_id')
						->where('b.id=' . $row->upgrade_option_id);
					$db->setQuery($query);										
					$fromPlanTitle = $db->loadResult();
					$itemName = str_replace('[FROM_PLAN_TITLE]', $fromPlanTitle, $itemName);
					break;
				default:
					$itemName = JText::_('OSM_PAYMENT_FOR_SUBSCRIPTION');
					$itemName = str_replace('[PLAN_TITLE]', $rowPlan->title, $itemName);
					break;
			}
			$data['item_name'] = $itemName;
			$paymentMethod = $data['payment_method'];
			require_once JPATH_COMPONENT . '/plugins/' . $paymentMethod . '.php';
			$query->clear();
			$query->select('params, support_recurring_subscription')
				->from('#__osmembership_plugins')
				->where('name='.$db->quote($paymentMethod));
			$db->setQuery($query);			
			$plugin = $db->loadObject();
			$params = $plugin->params;
			$supportRecurring = $plugin->support_recurring_subscription;
			$params = new JRegistry($params);
			$paymentClass = new $paymentMethod($params);
			if ($rowPlan->recurring_subscription && $supportRecurring)
			{
				if ($paymentMethod == 'os_authnet')
				{
					$paymentMethod = 'os_authnet_arb';
					require_once JPATH_COMPONENT . '/plugins/' . $paymentMethod . '.php';
					$paymentClass = new $paymentMethod($params);
				}
				$paymentClass->processRecurringPayment($row, $data);
			}
			else
			{
				$paymentClass->processPayment($row, $data);
			}
		}
		else
		{
			$Itemid = JRequest::getInt('Itemid');
			$row->published = 1;
			$row->store();
			if ($row->act == 'upgrade')
			{
				OSMembershipHelper::processUpgradeMembership($row);
			}
			OSMembershipHelper::sendEmails($row, $config);
			JPluginHelper::importPlugin('osmembership');
			$dispatcher = JDispatcher::getInstance();
			$dispatcher->trigger('onMembershipActive', array($row));
			$query->clear();
			$query->select('subscription_complete_url')
				->from('#__osmembership_plans')
				->where('id=' . $row->plan_id);
			//Get subscription complete UR			
			$db->setQuery($query);
			$subscriptionCompleteURL = $db->loadResult();
			if ($subscriptionCompleteURL)
			{
				JFactory::getApplication()->redirect($subscriptionCompleteURL);
			}
			else
			{
				JFactory::getApplication()->redirect(
					JRoute::_(
						'index.php?option=com_osmembership&view=complete&act=' . $row->act . '&subscription_code=' . $row->subscription_code .
							 '&Itemid=' . $Itemid, false));
			}
		}
	}

	/**
	 * Verify payment
	 */
	function paymentConfirm()
	{
		$paymentMethod = JRequest::getVar('payment_method', '');
		$method = os_payments::getPaymentMethod($paymentMethod);
		if ($method)
		{
			$method->verifyPayment();
		}
	}

	/**
	 * Verify recurring payment
	 */
	function recurringPaymentConfirm()
	{
		$paymentMethod = JRequest::getVar('payment_method', '');
		$method = os_payments::getPaymentMethod($paymentMethod);
		if ($method)
		{
			$method->verifyRecurringPayment();
		}
	}
} 