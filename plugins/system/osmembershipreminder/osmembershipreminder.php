<?php
/**
 * @version		1.6.5
 * @package		Joomla
 * @subpackage	OS Membership
 * @author  Tuan Pham Ngoc
 * @copyright	Copyright (C) 2012 - 2014 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die();
error_reporting(0);
/**
 * OS Membership Reminder Plugin
 *
 * @package		Joomla
 * @subpackage	OS Membership
 */
class plgSystemOSMembershipReminder extends JPlugin
{

	function onAfterInitialise()
	{
		$secretCode = trim($this->params->get('secret_code'));
		if ($secretCode && (JFactory::getApplication()->input->getString('secret_code') != $secretCode))
		{
			return ;
		}
		if (file_exists(JPATH_ROOT . '/components/com_osmembership/osmembership.php'))
		{
			$lastRun = (int) $this->params->get('last_run', 0);
			$numberEmailSendEachTime = (int) $this->params->get('number_subscribers', 5);
			$currentTime = time();
			$numberMinutes = ($currentTime - $lastRun) / 60;
			if ($numberMinutes >= 30)
			{
				$bccEmail = $this->params->get('bcc_email', '');
				require_once JPATH_ROOT . '/components/com_osmembership/helper/helper.php';

				$db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('a.id, a.plan_id, a.user_id, a.first_name, a.last_name, a.email, a.to_date, DATEDIFF(to_date, NOW()) AS number_days, b.title AS plan_title')
                    ->from('#__osmembership_subscribers AS a')
                    ->innerJoin('#__osmembership_plans AS b  ON a.plan_id = b.id')
                    ->where('a.published = 1 AND a.first_reminder_sent = 0  AND b.lifetime_membership != 1 AND  (b.send_first_reminder > 0 AND b.send_first_reminder >= DATEDIFF(to_date, NOW()))')
                    ->order('a.to_date');
                $db->setQuery($query, 0, $numberEmailSendEachTime);
				$rows = $db->loadObjectList();
				OSMembershipHelper::sendFirstReminderEmails($rows, $bccEmail);

                $query->clear();
                $query->select('a.id, a.plan_id, a.user_id, a.first_name, a.last_name, a.email, a.to_date, DATEDIFF(to_date, NOW()) AS number_days, b.title AS plan_title')
                    ->from('#__osmembership_subscribers AS a')
                    ->innerJoin('#__osmembership_plans AS b ON a.plan_id = b.id')
                    ->where('a.published = 1 AND a.second_reminder_sent = 0 AND b.lifetime_membership != 1 AND (b.send_second_reminder > 0 AND b.send_second_reminder >= DATEDIFF(to_date, NOW()))')
                    ->order('a.to_date');
                $db->setQuery($query, 0, $numberEmailSendEachTime);
				$rows = $db->loadObjectList();
				OSMembershipHelper::sendSecondReminderEmails($rows, $bccEmail);

				//Store last run time
				$this->params->set('last_run', $currentTime);
				$params = $this->params->toString();
                $query->clear();
                $query->update('#__extensions')
                    ->set('params='.$db->quote($params))
                    ->where('`element`="osmembershipreminder" AND `folder`="system"');
                $db->setQuery($query);
                $db->execute();
			}
		}
		
		return true;
	}
}
