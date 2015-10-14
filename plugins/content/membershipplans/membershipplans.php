<?php
/**
 * @version        1.6.3
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2013 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die();
error_reporting(0);

class plgContentMembershipPlans extends JPlugin
{

	function onContentPrepare($context, &$article, &$params, $limitstart)
	{
		if (file_exists(JPATH_ROOT . '/components/com_osmembership/osmembership.php'))
		{
			$app = JFactory::getApplication();
			if ($app->getName() != 'site')
			{
				return;
			}
			if (strpos($article->text, 'membershipplans') === false)
			{
				return true;
			}
			$regex         = '#{membershipplans ids="(.*?)"}#s';
			$article->text = preg_replace_callback($regex, array(&$this, 'displayPlans'), $article->text);
		}

		return true;
	}

	/**
	 * Replace callback function
	 *
	 * @param array $matches
	 */
	function displayPlans($matches)
	{
		require_once JPATH_ROOT . '/components/com_osmembership/libraries/rad/bootstrap.php';
		//OS Framework
		require_once JPATH_ROOT . '/administrator/components/com_osmembership/libraries/defines.php';
		require_once JPATH_ROOT . '/administrator/components/com_osmembership/libraries/inflector.php';
		require_once JPATH_ROOT . '/administrator/components/com_osmembership/libraries/autoload.php';
		$document = JFactory::getDocument();
		$user     = JFactory::getUser();
		$db       = JFactory::getDBO();
		$config = OSMembershipHelper::getConfig();
		if (@$config->load_jquery !== '0')
		{
			OSMembershipHelper::loadJQuery();
		}
		OSMembershipHelper::loadBootstrap(true);

		JHtml::_('script', OSMembershipHelper::getSiteUrl() . 'components/com_osmembership/assets/js/jquery-noconflict.js', false, false);

		OSMembershipHelper::loadLanguage();
		$styleUrl = JURI::base(true) . '/components/com_osmembership/assets/css/style.css';
		$document->addStylesheet($styleUrl, 'text/css', null, null);
		OSMembershipHelper::loadBootstrap(false);
		$Itemid      = OSMembershipHelper::getItemid();
		$planIds     = $matches[1];
		$fieldSuffix = OSMembershipHelper::getFieldSuffix();
		$layout      = $this->params->get('layout_type', 'default');
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
		$rows = $db->loadObjectList();
		if ($user->id)
		{
			for ($i = 0, $n = count($rows); $i < $n; $i++)
			{
				$row = $rows[$i];
				$taxRate = OSMembershipHelper::calculateTaxRate($row->id);
				if ($config->show_price_including_tax && $taxRate > 0)
				{
					$row->price        = $row->price * (1 + $taxRate / 100);
					$row->trial_amount = $row->trial_amount * (1 + $taxRate / 100);
				}
				if (!$row->enable_renewal)
				{
					$sql = 'SELECT COUNT(*) FROM #__osmembership_subscribers WHERE (email="' . $user->email . '" OR user_id=' . $user->id .
						') AND plan_id=' . $row->id . ' AND published != 0 ';
					$db->setQuery($sql);
					$total = (int) $db->loadResult();
					if ($total)
					{
						$row->disable_subscribe = 1;
					}
				}
			}
		}
		elseif ($config->show_price_including_tax)
		{
			for ($i = 0, $n = count($rows); $i < $n; $i++)
			{
				$row = $rows[$i];
				$taxRate = OSMembershipHelper::getPlanTaxRate($row->id);
				if ($taxRate > 0)
				{
					$row->price        = $row->price * (1 + $taxRate / 100);
					$row->trial_amount = $row->trial_amount * (1 + $taxRate / 100);
				}
			}
		}
		if ($layout == 'default')
		{

			return '<div class="osm-container row-fluid clearfix">' . OSMembershipHelperHtml::loadCommonLayout('common/default_plans.php', array('items' => $rows, 'config' => $config, 'Itemid' => $Itemid)) . '</div>';
		}
		else
		{
			return '<div class="osm-container row-fluid clearfix">' . OSMembershipHelperHtml::loadCommonLayout('common/columns_plans.php', array('items' => $rows, 'config' => $config, 'Itemid' => $Itemid)) . '</div>';
		}
	}
}