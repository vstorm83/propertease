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
class OSMembershipViewConfiguration extends JViewLegacy
{

	function display($tpl = null)
	{
		$db = JFactory::getDbo();
		$config = $this->get('Data');
		$options = array();
		$options[] = JHtml::_('select.option', 0, JText::_('OSM_NO_INTEGRATION'));
		$options[] = JHtml::_('select.option', 1, JText::_('OSM_COMMUNITY_BUILDER'));
		$options[] = JHtml::_('select.option', 2, JText::_('OSM_JOMSOCIAL'));
		$lists['cb_integration'] = JHtml::_('select.genericlist', $options, 'cb_integration', ' class="inputbox" ', 'value', 'text', $config->cb_integration);
		$lists['registration_integration'] = JHtml::_('select.booleanlist', 'registration_integration', ' class="inputbox" ', $config->registration_integration);		
		$lists['use_https'] = JHtml::_('select.booleanlist', 'use_https', '', $config->use_https);
		$lists['enable_captcha'] = JHtml::_('select.booleanlist', 'enable_captcha', '', $config->enable_captcha);
		$lists['enable_coupon'] = JHtml::_('select.booleanlist', 'enable_coupon', '', $config->enable_coupon);
		$lists['show_login_box_on_subscribe_page'] = JHtml::_('select.booleanlist', 'show_login_box_on_subscribe_page', '', $config->show_login_box_on_subscribe_page);
		$lists['auto_generate_membership_id'] = JHtml::_('select.booleanlist', 'auto_generate_membership_id', '', $config->auto_generate_membership_id);
		$lists['load_twitter_bootstrap_in_frontend'] = JHtml::_('select.booleanlist', 'load_twitter_bootstrap_in_frontend', '', 
			isset($config->load_twitter_bootstrap_in_frontend) ? $config->load_twitter_bootstrap_in_frontend : 1);
		$lists['load_jquery'] = JHtml::_('select.booleanlist', 'load_jquery', '',
			isset($config->load_jquery) ? $config->load_jquery : 1);
		$lists['show_price_including_tax'] = JHtml::_('select.booleanlist', 'show_price_including_tax', '', $config->show_price_including_tax);
		$options = array();
		$options[] = JHtml::_('select.option', 'Visa', JText::_('OSM_VISA_CARD'));
		$options[] = JHtml::_('select.option', 'MasterCard', JText::_('OSM_MASTER_CARD'));
		$options[] = JHtml::_('select.option', 'Discover', JText::_('OSM_DISCOVER'));
		$options[] = JHtml::_('select.option', 'Amex', JText::_('OSM_AMEX'));
		$lists['enable_cardtypes'] = JHtml::_('select.genericlist', $options, 'enable_cardtypes[]', ' class="inputbox" multiple="multiple"', 'value', 'text', explode(',', $config->enable_cardtypes));
		
		$sql = 'SELECT id, title FROM #__content ORDER BY title';
		$db->setQuery($sql);
		$rows = $db->loadObjectList();
		$options = array();
		$options[] = JHtml::_('select.option', 0, JText::_('OSM_SELECT_ARTICLE'), 'id', 'title');
		$options = array_merge($options, $rows);
		$lists['article_id'] = JHtml::_('select.genericlist', $options, 'article_id', ' class="inputbox" ', 'id', 'title', $config->article_id);
		$lists['active_term'] = JHtml::_('select.booleanlist', 'accept_term', '', $config->accept_term);
		$lists['fix_terms_and_conditions_popup'] = JHtml::_('select.booleanlist', 'fix_terms_and_conditions_popup', '', $config->fix_terms_and_conditions_popup);
		$lists['send_attachments_to_admin'] = JHtml::_('select.booleanlist', 'send_attachments_to_admin', '', $config->send_attachments_to_admin);
		
				
		$options = array();
		$options[] = JHtml::_('select.option', '', JText::_('OSM_SELECT_POSITION'));
		$options[] = JHtml::_('select.option', 0, JText::_('OSM_BEFORE_AMOUNT'));
		$options[] = JHtml::_('select.option', 1, JText::_('OSM_AFTER_AMOUNT'));
		
		$lists['currency_position'] = JHtml::_('select.genericlist', $options, 'currency_position', ' class="inputbox"', 'value', 'text', $config->currency_position);

		// EU VAT Number field selection
		$options = array();
		$options[] = JHtml::_('select.option', '', JText::_('OSM_SELECT'), 'name', 'title');
		$sql = 'SELECT name, title FROM #__osmembership_fields WHERE published = 1 ORDER BY ordering';
		$db->setQuery($sql);
		$options = array_merge($options, $db->loadObjectList());
		$lists['eu_vat_number_field'] = JHtml::_('select.genericlist', $options, 'eu_vat_number_field', ' class="inputbox"', 'name', 'title', $config->eu_vat_number_field);

		
		//Get list of country
		$sql = 'SELECT name AS value, name AS text FROM #__osmembership_countries WHERE published=1';
		$db->setQuery($sql);
		$rowCountries = $db->loadObjectList();
		$options = array();
		$options[] = JHtml::_('select.option', '', JText::_('OSM_SELECT_DEFAULT_COUNTRY'));
		$options = array_merge($options, $rowCountries);
		$lists['country_list'] = JHtml::_('select.genericlist', $options, 'default_country', '', 'value', 'text', $config->default_country);
		$lists['activate_invoice_feature'] = JHtml::_('select.booleanlist', 'activate_invoice_feature', '', $config->activate_invoice_feature);
		$lists['reset_invoice_number'] = JHtml::_('select.booleanlist', 'reset_invoice_number', '', $config->reset_invoice_number);
		$lists['send_invoice_to_customer'] = JHtml::_('select.booleanlist', 'send_invoice_to_customer', '', $config->send_invoice_to_customer);
		$lists['send_activation_email'] = JHtml::_('select.booleanlist', 'send_activation_email', '', $config->send_activation_email);
        $lists['create_account_when_membership_active'] = JHtml::_('select.booleanlist', 'create_account_when_membership_active', '', $config->create_account_when_membership_active);
		$this->lists = $lists;
		$this->config = $config;
		
		parent::display($tpl);
	}
}