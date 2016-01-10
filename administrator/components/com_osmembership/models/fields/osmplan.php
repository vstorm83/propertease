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
    class JFormFieldOSMPlan extends JFormField
    {
    	/**
    	 * Element name
    	 *
    	 * @access	protected
    	 * @var		string
    	 */
    	var	$_name = 'osmplan';
    	
    	function getInput()
    	{
    		$db = JFactory::getDbo();
    		$sql = "SELECT id, title  FROM #__osmembership_plans WHERE published = 1 ORDER BY ordering ";			
    		$db->setQuery($sql);								
    		$options 	= array();
    		$options[] 	= JHtml::_('select.option',  '0', JText::_( 'Select Plan' ), 'id', 'title');
    		$options = array_merge($options, $db->loadObjectList()) ;												
    		return JHtml::_('select.genericlist', $options, $this->name, ' class="inputbox" ', 'id', 'title', $this->value) ;
    	}
    }
} else {
    class JElementOSMPlan extends JElement
    {
    	/**
    	 * Element name
    	 *
    	 * @access	protected
    	 * @var		string
    	 */
    	var	$_name = 'osmplan';
    	
    	function fetchElement($name, $value, &$node, $control_name)
    	{
    		$db = JFactory::getDbo();
    		$sql = "SELECT id, title  FROM #__osmembership_plans WHERE published = 1 ORDER BY ordering ";			
    		$db->setQuery($sql);								
    		$options 	= array();
    		$options[] 	= JHtml::_('select.option',  '0', JText::_( 'Select Plan' ), 'id', 'title');
    		$options = array_merge($options, $db->loadObjectList()) ;													
    		return JHtml::_('select.genericlist', $options, $control_name.'['.$name.']', ' class="inputbox" ', 'id', 'title', $value) ;
    	}
    }    
}