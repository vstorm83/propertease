<?php
/**
 * @version		1.6.8
 * @package		Joomla
 * @subpackage	Membership Pro
 * @author  Tuan Pham Ngoc
 * @copyright	Copyright (C) 2012 - 2014 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die();

class os_paypal extends os_payment
{

	/**
	 * Paypal mode 
	 *
	 * @var boolean live mode : true, test mode : false
	 */
	var $_mode = 0;

	/**
	 * Paypal url
	 *
	 * @var string
	 */
	var $_url = null;

	/**
	 * Array of params will be posted to server
	 *
	 * @var string
	 */
	var $_params = array();

	/**
	 * Array containing data posted from paypal to our server
	 *
	 * @var array
	 */
	var $_data = array();

	/**
	 * Constructor functions, init some parameter
	 *
	 * @param object $config
	 */
	function os_paypal($params)
	{
		parent::setName('os_paypal');
		parent::os_payment();
		parent::setCreditCard(false);
		parent::setCardType(false);
		parent::setCardCvv(false);
		parent::setCardHolderName(false);
		$this->ipn_log = true;
		$this->ipn_log_file = JPATH_COMPONENT . '/ipn_logs.txt';
		$this->_mode = $params->get('paypal_mode');
		if ($this->_mode)
		{
			$this->_url = 'https://www.paypal.com/cgi-bin/webscr';
		}
		else
		{
			$this->_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
		}
		$this->setParam('business', $params->get('paypal_id'));
		$this->setParam('rm', 2);
		$this->setParam('cmd', '_xclick');
		$this->setParam('no_shipping', 1);
		$this->setParam('no_note', 1);
		$this->setParam('lc', 'US');
		$this->setParam('currency_code', $params->get('paypal_currency', 'USD'));
		$this->setParam('charset', 'utf-8');
	}

	/**
	 * Set param value
	 *
	 * @param string $name
	 * @param string $val
	 */
	function setParam($name, $val)
	{
		$this->_params[$name] = $val;
	}

	/**
	 * Setup payment parameter
	 *
	 * @param array $params
	 */
	function setParams($params)
	{
		foreach ($params as $key => $value)
		{
			$this->_params[$key] = $value;
		}
	}

	/**
	 * Process Payment
	 *
	 * @param object $row
	 * @param array $params
	 */
	function processPayment($row, $data)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$Itemid = JRequest::getInt('Itemid');
		$siteUrl = JUri::base();
		$this->setParam('item_name', $data['item_name']);
		$this->setParam('amount', round($data['amount'], 2));
		$this->setParam('custom', $row->id);
		$query->select('*')
			->from('#__osmembership_plans')
			->where('id = '. $row->plan_id);
		$db->setQuery($query);
		$rowPlan = $db->loadObject();

		// Override Paypal email
		if ($rowPlan->paypal_email)
		{
			$this->setParam('business', $rowPlan->paypal_email);
		}

		// Override return page if needed
		if ($rowPlan->subscription_complete_url)
		{
			$this->setParam('return', $rowPlan->subscription_complete_url);
		}
		else
		{
			$this->setParam('return', 
				$siteUrl . 'index.php?option=com_osmembership&view=complete&subscription_code=' . $row->subscription_code . '&Itemid=' . $Itemid);
		}
		$this->setParam('return', 
			$siteUrl . 'index.php?option=com_osmembership&view=complete&subscription_code=' . $row->subscription_code . '&Itemid=' . $Itemid);
		$this->setParam('cancel_return', $siteUrl . 'index.php?option=com_osmembership&view=cancel&id=' . $row->id . '&Itemid=' . $Itemid);
		$this->setParam('notify_url', $siteUrl . 'index.php?option=com_osmembership&task=payment_confirm&payment_method=os_paypal');
		$this->setParam('address1', $row->address);
		$this->setParam('address2', $row->address2);
		$this->setParam('city', $row->city);
		$this->setParam('country', OSMembershipHelper::getCountryCode($row->country));
		$this->setParam('first_name', $row->first_name);
		$this->setParam('last_name', $row->last_name);
		$this->setParam('state', $row->state);
		$this->setParam('zip', $row->zip);
		$this->setParam('email', $row->email);
		$this->submitPost();
	}

	/**
	 * Submit post to paypal server
	 *
	 */
	function submitPost() 
	{
	?>
		<div class="contentheading"><?php echo  JText::_('OSM_WAIT_PAYPAL'); ?></div>
		<form method="post" action="<?php echo $this->_url; ?>" name="osm_form" id="osm_form">
			<?php
				foreach ($this->_params as $key=>$val) 
				{
					echo '<input type="hidden" name="'.$key.'" value="'.$val.'" />';
					echo "\n";	
				}
			?>
			<script type="text/javascript">
				function redirect() 
				{
					document.osm_form.submit();
				}				
				setTimeout('redirect()', 3000);
			</script>
		</form>
	<?php	
	}
	/**
	 * Validate the post data from paypal to our server
	 *
	 * @return string
	 */
	function _validate()
	{
		$errNum = "";
		$errStr = "";
		$urlParsed = parse_url($this->_url);
		$host = $urlParsed['host'];
		$path = $urlParsed['path'];
		$postString = '';
		$response = '';
		foreach ($_POST as $key => $value)
		{
			$this->_data[$key] = $value;
			$postString .= $key . '=' . urlencode(stripslashes($value)) . '&';
		}
		$postString .= 'cmd=_notify-validate';
		$fp = fsockopen($host, '80', $errNum, $errStr, 30);
		if (!$fp)
		{
			return false;
		}
		else
		{
			fputs($fp, "POST $path HTTP/1.1\r\n");
			fputs($fp, "Host: $host\r\n");
			fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
			fputs($fp, "Content-length: " . strlen($postString) . "\r\n");
			fputs($fp, "Connection: close\r\n\r\n");
			fputs($fp, $postString . "\r\n\r\n");
			while (!feof($fp))
			{
				$response .= fgets($fp, 1024);
			}
			fclose($fp);
		}
		$this->ipn_response = $response;
		$this->log_ipn_results(true);
		if ($this->_mode)
		{
			if (eregi("VERIFIED", $response))
				return true;
			else
				return false;
		}
		else
		{
			//Always return true for test mode, prevent unnecessary support requests	     	
			return true;
		}
	}

	/**
	 * Log IPN result
	 *
	 * @param string $success
	 */
	function log_ipn_results($success)
	{
		if (!$this->ipn_log)
			return;
		$text = '[' . date('m/d/Y g:i A') . '] - ';
		if ($success)
			$text .= "SUCCESS!\n";
		else
			$text .= 'FAIL: ' . $this->last_error . "\n";
		$text .= "IPN POST Vars from Paypal:\n";
		foreach ($this->_data as $key => $value)
		{
			$text .= "$key=$value, ";
		}
		$text .= "\nIPN Response from Paypal Server:\n " . $this->ipn_response;
		$fp = fopen($this->ipn_log_file, 'a');
		fwrite($fp, $text . "\n\n");
		fclose($fp); // close file
	}

	/**
	 * Process payment 
	 *
	 */
	function verifyPayment()
	{
		$ret = $this->_validate();
		if ($ret)
		{
			$config = OSMembershipHelper::getConfig();
			$row = JTable::getInstance('OsMembership', 'Subscriber');
			$id = $this->_data['custom'];
			$transactionId = $this->_data['txn_id'];
			$amount = $this->_data['mc_gross'];
			if ($amount < 0)
				return false;
			$row->load($id);
			if ($row->published)
				return false;
			if ($row->gross_amount > $amount)
				return false;
			$row->payment_date = date('Y-m-d H:i:s');
			$row->transaction_id = $transactionId;
			$row->published = 1;
			$row->store();
			if ($row->act == 'upgrade')
			{
				OSMembershipHelper::processUpgradeMembership($row);
			}
			JPluginHelper::importPlugin('osmembership');
			$dispatcher = JDispatcher::getInstance();
			$dispatcher->trigger('onMembershipActive', array($row));
			OSMembershipHelper::sendEmails($row, $config);
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Process recurring payment
	 *
	 * @param object $row
	 * @param object $data
	 */
	function processRecurringPayment($row, $data)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$siteUrl = JUri::base();
		$Itemid = JRequest::getInt('Itemid');
		$query->select('*')
			->from('#__osmembership_plans')
			->where('id = '. $row->plan_id);
		$db->setQuery($query);
		$rowPlan = $db->loadObject();

		$this->setParam('item_name', $data['item_name']);
		$this->setParam('custom', $row->id);

		// Override Paypal email if needed
		if ($rowPlan->paypal_email)
		{
			$this->setParam('business', $rowPlan->paypal_email);
		}

		// Override subscription complete URL
		if ($rowPlan->subscription_complete_url)
		{
			$this->setParam('return', $rowPlan->subscription_complete_url);
		}
		else
		{
			$this->setParam('return', 
				$siteUrl . 'index.php?option=com_osmembership&view=complete&subscription_code=' . $row->subscription_code . '&Itemid=' . $Itemid);
		}
		$this->setParam('cancel_return', $siteUrl . 'index.php?option=com_osmembership&view=cancel&id=' . $row->id . '&Itemid=' . $Itemid);
		$this->setParam('notify_url', $siteUrl . 'index.php?option=com_osmembership&task=recurring_payment_confirm&payment_method=os_paypal');
		$this->setParam('cmd', '_xclick-subscriptions');
		$this->setParam('src', 1);
		$this->setParam('sra', 1);
		$this->setParam('a3', $data['regular_price']);
		$this->setParam('address1', $row->address);
		$this->setParam('address2', $row->address2);
		$this->setParam('city', $row->city);
		$this->setParam('country', $row->country);
		$this->setParam('first_name', $row->first_name);
		$this->setParam('last_name', $row->last_name);
		$this->setParam('state', $row->state);
		$this->setParam('zip', $row->zip);
		$this->setParam('p3', $rowPlan->subscription_length);
		$this->setParam('t3', $rowPlan->subscription_length_unit);
		$this->setParam('lc', 'US');
		if ($rowPlan->number_payments > 1)
		{
			$this->setParam('srt', $rowPlan->number_payments);
		}
		if ($rowPlan->trial_duration)
		{
			$this->setParam('a1', $data['trial_amount']);
			$this->setParam('p1', $rowPlan->trial_duration);
			$this->setParam('t1', $rowPlan->trial_duration_unit);
		}
		//Redirect users to Paypal for processing payment
		$this->submitPost();
	}

	/**
	 * Verify recrrung payment
	 *
	 */
	function verifyRecurringPayment()
	{
		$ret = $this->_validate();
		if ($ret)
		{
			$config = OSMembershipHelper::getConfig();
			$id = $this->_data['custom'];
			$transactionId = $this->_data['txn_id'];
			$amount = $this->_data['mc_gross'];
			$txnType = $this->_data['txn_type'];
			$subscrId = $this->_data['subscr_id'];
			if ($amount < 0)
				return false;
			$row = JTable::getInstance('OsMembership', 'Subscriber');
			switch ($txnType)
			{
				case 'subscr_signup':
					$row->load($id);
					if (!$row->id)
						return false;
					$published = $row->published;
					if (!$published)
					{
						$row->transaction_id = $transactionId;
						$row->payment_date = date('Y-m-d H:i:s');
						$row->published = 1;
						$row->store();
						OSMembershipHelper::sendEmails($row, $config);
						JPluginHelper::importPlugin('osmembership');
						$dispatcher = JDispatcher::getInstance();
						$dispatcher->trigger('onMembershipActive', array($row));
					}
					break;
				case 'subscr_payment':
					$row->load($id);
					if (!$row->id)
					{
						return false;
					}
					$db = JFactory::getDbo();
					$sql = 'SELECT * FROM #__osmembership_plans WHERE id=' . $row->plan_id;
					$db->setQuery($sql);
					$rowPlan = $db->loadObject();
					$row->payment_made = $row->payment_made + 1;
					$row->store();
					if (($rowPlan->trial_duration && $rowPlan->trial_amount == 0) || ($row->payment_made > 1))
					{
						$process = true;
					}
					else
					{
						$process = false;
					}
					if ($process)
					{						
						$row->id = 0;
						$row ->created_date = JFactory::getDate()->toSql();
						$row->invoice_number = 0;						
						$price = $rowPlan->price;
						$maxDate = null;
						if ($row->user_id > 0)
						{
							$sql = 'SELECT MAX(to_date) FROM #__osmembership_subscribers WHERE user_id=' . $row->user_id . ' AND plan_id=' .
								 $row->plan_id . ' AND published=1';
							$db->setQuery($sql);
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
						$row->to_date = $date->add(new DateInterval('P' . $rowPlan->subscription_length . $rowPlan->subscription_length_unit))->toSql();
						$row->act = 'renew';
						$row->amount = $price;
						//Calculate coupon discount
						if ($row->coupon_id)
						{
							$sql = 'SELECT * FROM #__osmembership_coupons WHERE id=' . $row->coupon_id;
							$db->setQuery($sql);
							$coupon = $db->loadObject();
							if ($coupon)
							{
								if ($coupon->coupon_type == 0)
								{
									$row->discount_amount = $price * $coupon->discount / 100;
								}
								else
								{
									$row->discount_amount = min($coupon->discount, $price);
								}
							}
						}
						else
						{
							$row->discount_amount = 0;
						}
						
						if ($rowPlan->tax_rate > 0)
						{
							$row->tax_amount = round(($price - $row->discount_amount)* $rowPlan->tax_rate / 100, 2);
						}
						else
						{
							$row->tax_amount = 0;
						}
						$row->gross_amount = $row->amount - $row->discount_amount + $row->tax_amount;
						$row->published = 1;
						$row->store();
						
						$sql = "INSERT INTO #__osmembership_field_value(field_id, field_value, subscriber_id)"
							. " SELECT  field_id,field_value, $row->id"
							. " FROM #__osmembership_field_value WHERE subscriber_id=$id";
						$db->setQuery($sql);
						$db->execute(); 
						
						JPluginHelper::importPlugin('osmembership');
						$dispatcher = JDispatcher::getInstance();
						$dispatcher->trigger('onMembershipActive', array($row));
						OSMembershipHelper::sendEmails($row, $config);
					}
					break;
			}
			return true;
		}
		else
		{
			return false;
		}
	}
}