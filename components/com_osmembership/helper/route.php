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

class OSMembershipHelperRoute
{

	protected static $config;

	protected static $lookup;

	protected static $plans;

	protected static $categories;

	public static function getPlanMenuId($id, &$catId = 0, $itemId = 0)
	{
		$needles = array('plan' => array((int) $id));
		if (!$catId)
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('category_id')
				->from('#__osmembership_plans')
				->where('id=' . (int) $id);
			$db->setQuery($query);
			$catId = (int) $db->loadResult();
		}
		if ($catId)
		{
			$needles['plans'] = array($catId);
		}

		if ($item = self::findItem($needles, $itemId))
		{
			return $item;
		}
		else
		{
			$itemId;
		}
	}

	/**
	 * Function to get Category Route
	 *
	 * @param  int $id
	 * @param int  $itemId
	 *
	 * @return string
	 */
	public static function getCategoryRoute($id, $itemId = 0)
	{
		if (!$id)
		{
			$link = '';
		}
		else
		{
			//Create the link
			$link    = 'index.php?option=com_osmembership&view=plans&id=' . $id;
			$needles = array('plans' => array($id));
			if ($item = self::findItem($needles, $itemId))
			{
				$link .= '&Itemid=' . $item;
			}
		}

		return $link;
	}

	/**
	 * Function to get sign up router
	 *
	 * @param int $id
	 * @param int $itemId
	 *
	 * @return string
	 */
	public static function getSignupRoute($id, $itemId = 0)
	{
		if (!$id)
		{
			$link = '';
		}
		else
		{
			//Create the link
			$link    = 'index.php?option=com_osmembership&view=register&id=' . $id;
			$needles = array('register' => array($id));
			if ($item = self::findItem($needles, $itemId))
			{
				$link .= '&Itemid=' . $item;
			}
		}

		return $link;
	}

	/**
	 * Get event title, used for building the router
	 *
	 * @param $id
	 *
	 * @return mixed
	 */
	public static function getPlanTitle($id)
	{
		if (!isset(self::$plans[$id]))
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('alias')
				->from('#__osmembership_plans')
				->where('id=' . (int) $id);
			$db->setQuery($query);

			self::$plans[$id] = $db->loadResult();
		}

		return self::$plans[$id];
	}

	public static function getCategoryTitle($id)
	{
		if (!isset(self::$categories[$id]))
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('alias')
				->from('#__osmembership_categories')
				->where('id=' . (int) $id);
			$db->setQuery($query);
			self::$categories[$id] = $db->loadResult();
		}

		return self::$categories[$id];
	}

	/**
	 * Find item id variable corresponding to the view
	 *
	 * @param $view
	 *
	 * @return int
	 */
	public static function findView($view, $itemId)
	{
		$needles = array($view => array(0));
		if ($item = self::findItem($needles, $itemId))
		{
			return $item;
		}
		else
		{
			return 0;
		}
	}

	/**
	 *
	 * Function to find Itemid
	 *
	 * @param string $needles
	 *
	 * @return int
	 */
	public static function findItem($needles = null, $itemId = 0)
	{
		$app   = JFactory::getApplication();
		$menus = $app->getMenu('site');

		// Prepare the reverse lookup array.
		if (self::$lookup === null)
		{
			self::$lookup = array();
			$component    = JComponentHelper::getComponent('com_osmembership');
			$items        = $menus->getItems('component_id', $component->id);
			foreach ($items as $item)
			{
				if (isset($item->query) && isset($item->query['view']))
				{
					$view = $item->query['view'];
					if (!isset(self::$lookup[$view]))
					{
						self::$lookup[$view] = array();
					}
					if (isset($item->query['id']))
					{
						self::$lookup[$view][$item->query['id']] = $item->id;
					}
					else
					{
						self::$lookup[$view][0] = $item->id;
					}
				}
			}
		}
		if ($needles)
		{
			foreach ($needles as $view => $ids)
			{
				if (isset(self::$lookup[$view]))
				{
					foreach ($ids as $id)
					{
						if (isset(self::$lookup[$view][(int) $id]))
						{
							return self::$lookup[$view][(int) $id];
						}
					}
				}
			}
		}

		//Return default item id
		return $itemId;
	}
}