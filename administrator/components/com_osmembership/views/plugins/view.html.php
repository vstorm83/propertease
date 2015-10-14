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
defined('_JEXEC') or die();

/**
 * HTML View class for Quick Gallery component
 *
 * @static
 * @package		Joomla
 * @subpackage	Membership Pro
 */
class OSMembershipViewPlugins extends OSViewList
{

	/**
	 * Override Build Toolbar function, only need Publish, Unpublish and Delete button
	 */
	function _buildToolbar()
	{
		$viewName = $this->getName();
		$controller = OSInflector::singularize($this->getName());
		JToolBarHelper::title(JText::_($this->lang_prefix . '_' . strtoupper($viewName) . '_MANAGEMENT'));
		JToolBarHelper::deleteList(JText::_($this->lang_prefix . '_DELETE_' . strtoupper($this->getName()) . '_CONFIRM'), $controller . '.remove');
		JToolBarHelper::publishList($controller . '.publish');
		JToolBarHelper::unpublishList($controller . '.unpublish');
	}
}