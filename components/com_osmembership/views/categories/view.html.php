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
defined('_JEXEC') or die;

/**
 * HTML View class for Membership Pro component
 *
 * @package        Joomla
 * @subpackage     Membership Pro
 */
class OSMembershipViewCategories extends JViewLegacy
{
	function display($tpl = null)
	{
		$items = $this->get('Data');
		//Process content plugin in the description
		for ($i = 0, $n = count($items); $i < $n; $i++)
		{
			$item              = $items[$i];
			$item->description = JHtml::_('content.prepare', $item->description);
		}
		$pagination = $this->get('Pagination');
		$Itemid     = JRequest::getInt('Itemid', 0);
		$config     = OSMembershipHelper::getConfig();
		$this->assignRef('config', $config);
		$this->assignRef('items', $items);
		$this->assignRef('pagination', $pagination);
		$this->assignRef('Itemid', $Itemid);
		parent::display($tpl);
	}
}