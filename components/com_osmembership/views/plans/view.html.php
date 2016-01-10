<?php
/**
 * @version        1.6.8
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2014 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

/**
 * HTML View class for OS Membership component
 *
 * @static
 * @package        Joomla
 * @subpackage     Membership Pro
 */
class OSMembershipViewPlans extends JViewLegacy
{

	function display($tpl = null)
	{
		$config     = OSMembershipHelper::getConfig();
		$Itemid     = JRequest::getInt('Itemid');
		$items      = $this->get('Data');
		$pagination = $this->get('Pagination');

		for ($i = 0, $n = count($items); $i < $n; $i++)
		{
			$item    = $items[$i];
			$taxRate = OSMembershipHelper::calculateTaxRate($item->id);
			if ($config->show_price_including_tax && $taxRate > 0)
			{
				$item->price        = $item->price * (1 + $taxRate / 100);
				$item->trial_amount = $item->trial_amount * (1 + $taxRate / 100);
			}
			$item->short_description = JHtml::_('content.prepare', $item->short_description);
			$item->description       = JHtml::_('content.prepare', $item->description);
		}
		$this->pagination = $pagination;
		$this->items      = $items;
		$this->Itemid     = $Itemid;
		$this->config     = $config;
		$this->categoryId = JRequest::getInt('id');

		parent::display($tpl);
	}
}