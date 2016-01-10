<?php
/**
 * @version        1.6.7
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die;
error_reporting(0);
if (file_exists(JPATH_ROOT . '/components/com_osmembership/osmembership.php'))
{
	require_once JPATH_ROOT . '/components/com_osmembership/libraries/rad/bootstrap.php';
	//OS Framework
	require_once JPATH_ROOT . '/administrator/components/com_osmembership/libraries/defines.php';
	require_once JPATH_ROOT . '/administrator/components/com_osmembership/libraries/inflector.php';
	require_once JPATH_ROOT . '/administrator/components/com_osmembership/libraries/autoload.php';
	$document = JFactory::getDocument();
	$user     = JFactory::getUser();
	$db       = & JFactory::getDBO();
	OSMembershipHelper::loadLanguage();
	$styleUrl = JURI::base(true) . '/components/com_osmembership/assets/css/style.css';
	$document->addStylesheet($styleUrl, 'text/css', null, null);
	$config = OSMembershipHelper::getConfig();
	if (@$config->load_jquery !== '0')
	{
		OSMembershipHelper::loadJQuery();
	}
	OSMembershipHelper::loadBootstrap(true);

	JHtml::_('script', OSMembershipHelper::getSiteUrl() . 'components/com_osmembership/assets/js/jquery-noconflict.js', false, false);

	$Itemid      = JRequest::getInt('Itemid');
	$planIds     = $params->get('plan_ids', '*');
	$layout      = $params->get('layout_type', 'default');
	$fieldSuffix = OSMembershipHelper::getFieldSuffix();
	if (!$planIds)
	{
		$planIds = '*';
	}
	if ($planIds == '*')
	{
		$sql = 'SELECT a.*, a.title' . $fieldSuffix . ' AS title, a.description' . $fieldSuffix . ' AS description, a.short_description' . $fieldSuffix . ' AS short_description  FROM #__osmembership_plans AS a WHERE a.published = 1 ORDER BY a.ordering';
	}
	elseif (strpos($planIds, 'cat-') !== false)
	{
		$catId = (int) substr($planIds, 4);
		$sql   = 'SELECT a.*, a.title' . $fieldSuffix . ' AS title, a.description' . $fieldSuffix . ' AS description, a.short_description' . $fieldSuffix . ' AS short_description  FROM #__osmembership_plans AS a WHERE a.published = 1 AND a.category_id=' . $catId . ' ORDER BY a.ordering';
	}
	else
	{
		$sql = 'SELECT a.*, a.title' . $fieldSuffix . ' AS title, a.description' . $fieldSuffix . ' AS description, a.short_description' . $fieldSuffix . ' AS short_description  FROM #__osmembership_plans AS a WHERE a.published = 1 AND a.id IN (' . $planIds . ') ORDER BY a.ordering';
	}
	$db->setQuery($sql);
	$items = $db->loadObjectList();

	if ($user->id)
	{
		for ($i = 0, $n = count($items); $i < $n; $i++)
		{
			$item = $items[$i];
			$taxRate = OSMembershipHelper::calculateTaxRate($item->id);
			if ($config->show_price_including_tax && $taxRate > 0)
			{
				$item->price        = $item->price * (1 + $taxRate / 100);
				$item->trial_amount = $item->trial_amount * (1 + $taxRate / 100);
			}
			if (!$item->enable_renewal)
			{
				$sql = 'SELECT COUNT(*) FROM #__osmembership_subscribers WHERE (email="' . $user->email . '" OR user_id=' . $user->id . ') AND plan_id=' . $item->id . ' AND published != 0 ';
				$db->setQuery($sql);
				$total = (int) $db->loadResult();
				if ($total)
				{
					$item->disable_subscribe = 1;
				}
			}
		}
	}
	elseif ($config->show_price_including_tax)
	{
		for ($i = 0, $n = count($items); $i < $n; $i++)
		{
			$item = $items[$i];
			$taxRate = OSMembershipHelper::getPlanTaxRate($item->id);
			if ($taxRate > 0)
			{
				$item->price        = $item->price * (1 + $taxRate / 100);
				$item->trial_amount = $item->trial_amount * (1 + $taxRate / 100);
			}
		}
	}

	require(JModuleHelper::getLayoutPath('mod_membershipplans', $layout));
}