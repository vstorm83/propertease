<?php
/**
 * @version		1.6.8
 * @package		Joomla
 * @subpackage	Membership Pro
 * @author  Tuan Pham Ngoc
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined( '_JEXEC' ) or die ;

if (version_compare(JVERSION, '1.6.0', 'ge')) {
    jimport('joomla.form.formfield');
    class JFormFieldOSMCategory extends JFormField
    {
    	/**
    	 * Element name
    	 *
    	 * @access	protected
    	 * @var		string
    	 */
    	var	$_name = 'osmcategory';
    	
    	function getInput()
    	{
    		$db = JFactory::getDbo();
    		$sql = "SELECT id, title  FROM #__osmembership_categories WHERE published = 1 ORDER BY title ";			
    		$db->setQuery($sql);								
    		$options 	= array();
    		$options[] 	= JHtml::_('select.option',  '0', JText::_( 'Select Category' ), 'id', 'title');
    		$options = array_merge($options, $db->loadObjectList()) ;												
    		return JHtml::_('select.genericlist', $options, $this->name, ' class="inputbox" ', 'id', 'title', $this->value) ;
    	}
    }
} else {
    class JElementOSMCategory extends JElement
    {
    	/**
    	 * Element name
    	 *
    	 * @access	protected
    	 * @var		string
    	 */
    	var	$_name = 'osmcategory';
    	
    	function fetchElement($name, $value, &$node, $control_name)
    	{
    		$db = JFactory::getDbo();
    		$sql = "SELECT id, title  FROM #__osmembership_categories WHERE published = 1 ORDER BY title ";			
    		$db->setQuery($sql);								
    		$options 	= array();
    		$options[] 	= JHtml::_('select.option',  '0', JText::_( 'Select Category' ), 'id', 'title');
    		$options = array_merge($options, $db->loadObjectList()) ;													
    		return JHtml::_('select.genericlist', $options, $control_name.'['.$name.']', ' class="inputbox" ', 'id', 'title', $value) ;
    	}
    }    
}