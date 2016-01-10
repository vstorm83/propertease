<?php
/**
 * @version        1.6.8
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2014 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die();

/**
 * HTML View class for the Membership Pro component
 *
 * @static
 * @package		Joomla
 * @subpackage	Membership Pro
 */
class OsMembershipViewComplete extends JViewLegacy
{

	function display($tpl = null)
	{
		$this->setLayout('default');
		$db = JFactory::getDbo();		
		$subscriptionCode = JRequest::getVar('subscription_code');
		if ($subscriptionCode)
		{
			$sql = 'SELECT id FROM #__osmembership_subscribers WHERE subscription_code="' . $subscriptionCode . '"';
			$db->setQuery($sql);
			$id = (int) $db->loadResult();
			if (!$id)
			{
				JFactory::getApplication()->redirect('index.php', JText::_('Invalid subscription code'));
			}
		}
		else
		{
			$id = 0;
		}		
		$fieldSuffix = OSMembershipHelper::getFieldSuffix();
		$sql = 'SELECT a.id, a.title'.$fieldSuffix.' AS title, b.payment_method FROM #__osmembership_plans  AS a ' . ' INNER JOIN #__osmembership_subscribers AS b ' .
			 ' ON a.id = b.plan_id ' . ' WHERE b.id = ' . $id;
		
		$db->setQuery($sql);
		$subscriber = $db->loadObject();
		$config = OSMembershipHelper::getConfig();
		$messageObj = OSMembershipHelper::getMessages();		
		
		$sql = 'SELECT * FROM #__osmembership_subscribers WHERE id=' . $id;
		$db->setQuery($sql);
		$row = $db->loadObject();
		
		$act = JRequest::getVar('act', '');
		switch ($act)
		{
			case 'renew':				
				if (strlen(strip_tags($messageObj->{'renew_thanks_message'.$fieldSuffix})))					
					$message = $messageObj->{'renew_thanks_message'.$fieldSuffix};
				else 
					$message = $messageObj->renew_thanks_message;
				$sql = 'SELECT to_date FROM #__osmembership_subscribers  WHERE id=' . $id;
				$db->setQuery($sql);
				$toDate = $db->loadResult();
				if ($toDate)
					$toDate = JHtml::_('date', $toDate, $config->date_format);
				else
					$toDate = '';
				$message = str_replace('[END_DATE]', $toDate, $message);
				$message = str_replace('[PLAN_TITLE]', $subscriber->title, $message);
				break;
			case 'upgrade':
				if (strlen(strip_tags($messageObj->{'renew_thanks_message'.$fieldSuffix})))
					$message = $messageObj->{'upgrade_thanks_message'.$fieldSuffix};
				else
					$message = $messageObj->upgrade_thanks_message;				
				$sql = ' SELECT c.title FROM #__osmembership_subscribers AS a ' . ' INNER JOIN #__osmembership_upgraderules AS b ' .
					 ' ON a.upgrade_option_id=b.id ' . ' INNER JOIN #__osmembership_plans AS c ' . ' ON b.from_plan_id = c.id ' . ' WHERE a.id = ' .
					 $id;
				$db->setQuery($sql);
				$fromPlan = $db->loadResult();
				$message = str_replace('[PLAN_TITLE]', $fromPlan, $message);
				$message = str_replace('[TO_PLAN_TITLE]', $subscriber->title, $message);
				break;
			default:
				if ($subscriber->payment_method == 'os_offline')
				{
						if (strlen(strip_tags($messageObj->{'thanks_message_offline'.$fieldSuffix})))
							$message = $messageObj->{'thanks_message_offline'.$fieldSuffix};
						else
							$message = $messageObj->thanks_message_offline;							
				}
				else					
				{
					if (strlen(strip_tags($messageObj->{'thanks_message'.$fieldSuffix})))
						$message = $messageObj->{'thanks_message'.$fieldSuffix};
					else
						$message = $messageObj->thanks_message;					
				}
				
				$message = str_replace('[PLAN_TITLE]', $subscriber->title, $message);
				$registrationDetail = OSMembershipHelper::getEmailContent($config, $row);
				$message = str_replace('[SUBSCRIPTION_DETAIL]', $registrationDetail, $message);
				
				break;
		}
		
		$subscriptionDetail = OSMembershipHelper::getEmailContent($config, $row);
		$message = str_replace('[SUBSCRIPTION_DETAIL]', $subscriptionDetail, $message);
		$replaces = OSMembershipHelper::buildTags($row, $config);

		$replaces['plan_title'] = $subscriber->title;
		foreach ($replaces as $key => $value)
		{
			$key = strtoupper($key);
			$message = str_replace("[$key]", $value, $message);
		}
		$this->message = $message;
		
		parent::display($tpl);
	}
}