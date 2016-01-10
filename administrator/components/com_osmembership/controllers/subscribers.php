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
 * OSMembership Plugin controller
 *
 * @package		Joomla
 * @subpackage	Membership Pro
 */
class OSMembershipControllerSubscribers extends OSController
{

	function export()
	{
		$config = OSMembershipHelper::getConfig();
		$db = JFactory::getDbo();
		$planId = JRequest::getInt('plan_id');
		$published = JRequest::getInt('published', -1);
		$where = array();
		if ($planId > 0)
		{
			$where[] = ' a.plan_id=' . $planId;
		}
		if ($published != -1)
		{
			$where[] = ' a.published=' . $published;
		}
		$sql = 'SELECT a.*, b.username, c.title FROM #__osmembership_subscribers AS a
				LEFT JOIN #__users AS b
				ON a.user_id = b.id
				LEFT JOIN #__osmembership_plans AS c
				ON a.plan_id = c.id 							
			';
		if (count($where))
		{
			$sql .= ' WHERE ' . implode(' AND ', $where);
		}
		$db->setQuery($sql);
		$rows = $db->loadObjectList();
		for ($i = 0, $n = count($rows); $i < $n; $i++)
		{
			$row = $rows[$i];
			switch ($row->published)
			{
				case 0:
					$row->subscription_status = JText::_('OSM_PENDING');
					break;
				case 1:
					$row->subscription_status = JText::_('OSM_ACTIVE');
					break;
				case 2:
					$row->subscription_status = JText::_('OSM_EXPIRED');
					break;
				case 3:
					$row->subscription_status = JText::_('OSM_CANCELLED_PENDING');
					break;
				case 4:
					$row->subscription_status = JText::_('OSM_CANCELLED_REFUNDED');
					break;
				default:
					$row->subscription_status = '';
					break;
			}
		}
		
		$sql = 'SELECT name, title FROM #__osmembership_plugins';
		$db->setQuery($sql);
		$plugins = $db->loadObjectList();
		$pluginTitles = array();
		foreach ($plugins as $plugin)
		{
			$pluginTitles[$plugin->name] = $plugin->title;
		}
		//Get list of custom fields
		$sql = 'SELECT id, name, title, is_core FROM #__osmembership_fields WHERE published=1 ORDER BY ordering';
		$db->setQuery($sql);
		$rowFields = $db->loadObjectList();
		
		$customFieldDatas = array();
		if (count($where))
		{
			$sql = 'SELECT * FROM #__osmembership_field_value WHERE subscriber_id IN (SELECT id FROM #__osmembership_subscribers AS a WHERE ' .
				 implode(' AND ', $where) . ')';
			}
			else
			{
				$sql = 'SELECT * FROM #__osmembership_field_value';
			}
			$db->setQuery($sql);
			$fieldDatas = $db->loadObjectList();
			if (count($fieldDatas))
			{
				foreach ($fieldDatas as $fieldData)
				{
					$customFieldDatas[$fieldData->subscriber_id][$fieldData->field_id] = $fieldData->field_value;
				}
			}
			if (count($rows))
			{
				$results_arr = array();
				$results_arr[] = JText::_('OSM_PLAN');
				$results_arr[] = JText::_('Username');
				foreach ($rowFields as $rowField)
				{
					$results_arr[] = $rowField->title;
				}
				$results_arr[] = JText::_('OSM_SUBSCRIPTION_START_DATE');
				$results_arr[] = JText::_('OSM_SUBSCRIPTION_END_DATE');
				$results_arr[] = JText::_('OSM_SUBSCRIPTION_STATUS');
				$results_arr[] = JText::_('OSM_DISCOUNT_AMOUNT');
				$results_arr[] = JText::_('OSM_TAX_AMOUNT');
				$results_arr[] = JText::_('OSM_GROSS_AMOUNT');
				$results_arr[] = JText::_('OSM_PAYMENT_METHOD');
				$results_arr[] = JText::_('OSM_TRANSACTION_ID');
				
				$csv_output = "\"" . implode("\",\"", $results_arr) . "\"";
				
				foreach ($rows as $r)
				{
					$results_arr = array();
					$results_arr[] = $r->title;
					$results_arr[] = $r->username;
					foreach ($rowFields as $rowField)
					{
						if ($rowField->is_core)
						{
							$fieldName = $rowField->name;
							$results_arr[] = $r->{$fieldName};
						}
						else
						{
							$fieldId = $rowField->id;
							$fieldValue = @$customFieldDatas[$r->id][$fieldId];
							if (is_string($fieldValue) && is_array(json_decode($fieldValue)))
							{
								$fieldValue = implode(', ', json_decode($fieldValue));
							}
							$results_arr[] = $fieldValue;
						}
					}
					$results_arr[] = JHtml::_('date', $r->from_date, $config->date_format);
					$results_arr[] = JHtml::_('date', $r->to_date, $config->date_format);
					$results_arr[] = $r->subscription_status;
					$results_arr[] = round($r->discount_amount, 2);
					$results_arr[] = round($r->tax_amount, 2);
					$results_arr[] = round($r->gross_amount, 2);
					$results_arr[] = $pluginTitles[$r->payment_method];
					$results_arr[] = $r->transaction_id;
					$csv_output .= "\n\"" . implode("\",\"", $results_arr) . "\"";
				}
				$csv_output .= "\n";
				if (ereg('Opera(/| )([0-9].[0-9]{1,2})', $_SERVER['HTTP_USER_AGENT']))
				{
					$UserBrowser = "Opera";
				}
				elseif (ereg('MSIE ([0-9].[0-9]{1,2})', $_SERVER['HTTP_USER_AGENT']))
				{
					$UserBrowser = "IE";
				}
				else
				{
					$UserBrowser = '';
				}
				$mime_type = ($UserBrowser == 'IE' || $UserBrowser == 'Opera') ? 'application/octetstream' : 'application/octet-stream';
				$filename = "subscribers_list";
				@ob_end_clean();
				ob_start();
				header('Content-Type: ' . $mime_type);
				header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');
				if ($UserBrowser == 'IE')
				{
					header('Content-Disposition: attachment; filename="' . $filename . '.csv"');
					header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
					header('Pragma: public');
				}
				else
				{
					header('Content-Disposition: attachment; filename="' . $filename . '.csv"');
					header('Pragma: no-cache');
				}
				print $csv_output;
				exit();
			}
		}
	}
