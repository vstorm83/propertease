<?php
/**
 * @version		1.6.8
 * @package		Joomla
 * @subpackage	Membership Pro
 * @author  Tuan Pham Ngoc
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

/**
 * HTML View class for Membership Pro component
 *
 * @static
 * @package		Joomla
 * @subpackage	Membership Pro
 */
class OSMembershipViewLanguage extends JViewLegacy
{

	function display($tpl = null)
	{
		$app = JFactory::getApplication();
		$option = 'com_osmembership';		
		$lang = $app->getUserStateFromRequest($option . 'lang', 'lang', 'en-GB', 'string');
		if (!$lang)
		{
			$lang = 'en-GB';
		}			
		$search = $app->getUserStateFromRequest($option . 'search', 'search', '', 'string');
		$search = JString::strtolower($search);
		$lists['search'] = $search;
		$item = JRequest::getVar('item', '');
		if (!$item)
		{
			$item = 'com_osmembership';
		}			
		$model = $this->getModel('language');
		$trans = $model->getTrans($lang, $item);
		$languages = $model->getSiteLanguages();
		$options = array();
		$options[] = JHtml::_('select.option', '', JText::_('Select Language'));
		foreach ($languages as $language)
		{
			$options[] = JHtml::_('select.option', $language, $language);
		}
		$lists['lang'] = JHtml::_('select.genericlist', $options, 'lang', ' class="inputbox"  onchange="submit();" ', 'value', 'text', $lang);
		$options = array();
		$options[] = JHtml::_('select.option', '', JText::_('--Select Item--'));
		$options[] = JHtml::_('select.option', 'com_osmembership', JText::_('OS Membership'));
		$options[] = JHtml::_('select.option', 'admin.com_osmembership', JText::_('OS Membership - Backend'));
		$lists['item'] = JHtml::_('select.genericlist', $options, 'item', ' class="inputbox"  onchange="submit();" ', 'value', 'text', $item);
		$this->trans = $trans;
		$this->lists = $lists;
		$this->lang = $lang;
		$this->item = $item;
		parent::display($tpl);
	}
}