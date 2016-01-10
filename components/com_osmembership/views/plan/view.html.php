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
 * @since          1.5
 */
class OSMembershipViewPlan extends JViewLegacy
{

	function display($tpl = null)
	{
		$config = OSMembershipHelper::getConfig();
		$Itemid = JRequest::getInt('Itemid', 0);
		$item   = $this->get('Data');
		if (!in_array($item->access, JFactory::getUser()->getAuthorisedViewLevels()))
		{
			JFactory::getApplication()->redirect('index.php', JText::_('OSM_NOT_ALLOWED_PLAN'));
		}
		$taxRate = OSMembershipHelper::calculateTaxRate($item->id);
		if ($config->show_price_including_tax && $taxRate > 0)
		{
			$item->price        = $item->price * (1 + $taxRate / 100);
			$item->trial_amount = $item->trial_amount * (1 + $taxRate / 100);
		}
		$item->short_description = JHtml::_('content.prepare', $item->short_description);
		$item->description       = JHtml::_('content.prepare', $item->description);
		$this->item              = $item;
		$this->Itemid            = $Itemid;
		$this->config            = $config;

		parent::display($tpl);
	}
}