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
 * OS Membership Accounts cleaner Plugin
 *
 * @package		Joomla
 * @subpackage	OS Membership
 */
class plgSystemOSMembershipCleaner extends JPlugin
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
			$currentTime = time();
			$numberMinutes = ($currentTime - $lastRun) / 60;
			if ($numberMinutes >= 30)
			{
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $now = JFactory::getDate()->toSql();
                $query->select('id, user_id')
                    ->from('#__osmembership_subscribers')
                    ->where('published=0')
                    ->where('payment_method NOT LIKE "os_offline%"')
                    ->where("TIMESTAMPDIFF(HOUR, created_date, '$now') >= 5");
                $db->setQuery($query);
                $rowPendingSubscribers = $db->loadObjectList();
                if (count($rowPendingSubscribers))
                {
                    $subscriberIds =  array();
                    foreach($rowPendingSubscribers as $subscriber)
                    {
                        if ($subscriber->user_id > 0)
                        {
                            $user =  JFactory::getUser($subscriber->user_id);
                            if ($user->id && !$user->authorise('core.admin'))
                            {
                                $user->delete();
                            }
                        }
                        $subscriberIds[] = $subscriber->id;
                    }
                    $query->clear();
                    $query->delete('#__osmembership_subscribers')
                        ->where('id IN ('.implode(',', $subscriberIds).')');
                    $db->setQuery($query);
                    $db->execute();
                }
				//Store last run time
				$this->params->set('last_run', $currentTime);
				$params = $this->params->toString();
                $query->clear();
                $query->update('#__extensions')
                    ->set('params='.$db->quote($params))
                    ->where('`element`="osmembershipcleaner" AND `folder`="system"');
                $db->setQuery($query);
                $db->execute();
			}
		}

		return true;
	}
}
