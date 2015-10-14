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
require_once JPATH_ROOT . '/components/com_osmembership/helper/route.php';
function OSMembershipBuildRoute(&$query)
{
	$segments = array();
	$db       = JFactory::getDbo();
	$queryArr = $query;
	if (isset($queryArr['option']))
	{
		unset($queryArr['option']);
	}
	if (isset($queryArr['Itemid']))
	{
		unset($queryArr['Itemid']);
	}
	//Store the query string to use in the parseRouter method
	$queryString = http_build_query($queryArr);
	$app         = JFactory::getApplication();
	$menu        = $app->getMenu();
	//We need a menu item.  Either the one specified in the query, or the current active one if none specified
	if (empty($query['Itemid']))
	{
		$menuItem = $menu->getActive();
	}
	else
	{
		$menuItem = $menu->getItem($query['Itemid']);
	}
	if (empty($menuItem->query['view']))
	{
		$menuItem->query['view'] = '';
	}
	//Are we dealing with the current view which is attached to a menu item?
	if (($menuItem instanceof stdClass) && isset($query['view']) && isset($query['id']) && $menuItem->query['view'] == $query['view'] && isset($query['id']) && $menuItem->query['id'] == intval($query['id']))
	{
		unset($query['view']);
		if (isset($query['catid']))
		{
			unset($query['catid']);
		}
		unset($query['id']);
	}
	//Dealing with the catid parameter in the link to plan
	if (($menuItem instanceof stdClass) && ($menuItem->query['view'] == 'plans') && isset($query['catid']) && $menuItem->query['id'] == intval($query['catid']))
	{
		if (isset($query['catid']))
		{
			unset($query['catid']);
		}
	}
	$view      = isset($query['view']) ? $query['view'] : '';
	$task      = isset($query['task']) ? $query['task'] : '';
	$id        = isset($query['id']) ? (int) $query['id'] : 0;
	$catId     = isset($query['catid']) ? (int) $query['catid'] : 0;
	$unsetView = false;
	switch ($view)
	{
		case 'plans':
			$segments[] = 'Membership Plans';
			$unsetView  = true;
			break;
		case 'plan' :
			if ($catId)
			{
				$segments[] = OSMembershipHelperRoute::getCategoryTitle($catId);
			}
			if ($id)
			{
				$segments[] = OSMembershipHelperRoute::getPlanTitle($id);
			}
			unset($query['id']);
			$unsetView = true;
			break;
		case 'register':
			if ($id)
			{
				$segments[] = OSMembershipHelperRoute::getPlanTitle($id);
			}
			$segments[] = 'Sign up';
			unset($query['id']);
			$unsetView = true;
			break;
		case 'failure':
			$segments[] = 'Subscription Failure';
			$unsetView  = true;
			break;
		case 'cancel':
			$segments[] = 'Subscription cancel';
			$unsetView  = true;
			break;
		case 'complete':
			$segments[] = 'Subscription Complete';
			$unsetView  = true;
			break;
		case 'subscription':
			$segments[] = 'Subscription Detail';
			$unsetView  = true;
			break;
	}

	switch ($task)
	{
		case 'renew_membership':
			$segments[] = 'Renew Membership';
			unset($query['task']);
			break;
	}

	if (isset($query['start']) || isset($query['limitstart']))
	{
		$limit      = (int) JFactory::getApplication()->getCfg('list_limit');
		$limitStart = isset($query['limitstart']) ? (int) $query['limitstart'] : (int) $query['start'];
		$page       = ceil(($limitStart + 1) / $limit);
		$segments[] = 'page' . '-' . $page;
	}
	if (isset($query['view']) && $unsetView)
	{
		unset($query['view']);
	}

	if (isset($query['catid']))
		unset($query['catid']);

	if (isset($query['start']))
		unset($query['start']);

	if (isset($query['limitstart']))
		unset($query['limitstart']);

	if (count($segments))
	{
		$segments = array_map('JApplication::stringURLSafe', $segments);
		$key      = md5(implode('/', $segments));
		$q        = $db->getQuery(true);
		$q->select('COUNT(*)')
			->from('#__osmembership_sefurls')
			->where('md5_key="' . $key . '"');
		$db->setQuery($q);
		$total = $db->loadResult();
		if (!$total)
		{
			$q->clear();
			$q->insert('#__osmembership_sefurls')
				->columns('md5_key, `query`')
				->values("'$key', '$queryString'");
			$db->setQuery($q);
			$db->execute();
		}
	}

	return $segments;
}

/**
 *
 * Parse the segments of a URL.
 *
 * @param    array    The segments of the URL to parse.
 *
 * @return    array    The URL attributes to be used by the application.
 */
function OSMembershipParseRoute($segments)
{
	$vars = array();
	if (count($segments))
	{
		$db    = JFactory::getDbo();
		$key   = md5(str_replace(':', '-', implode('/', $segments)));
		$query = $db->getQuery(true);
		$query->select('`query`')
			->from('#__osmembership_sefurls')
			->where('md5_key="' . $key . '"');
		$db->setQuery($query);
		$queryString = $db->loadResult();
		if ($queryString)
		{
			parse_str(html_entity_decode($queryString), $vars);
		}
	}
	$app  = JFactory::getApplication();
	$menu = $app->getMenu();
	if ($item = $menu->getActive())
	{
		foreach ($item->query as $key => $value)
		{
			if ($key != 'option' && $key != 'Itemid' && !isset($vars[$key]))
				$vars[$key] = $value;
		}
	}

	return $vars;
}