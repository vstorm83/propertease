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
 * Membership Pro controller
 *
 * @package		Joomla
 * @subpackage	Membership Pro
 */
class OSMembershipControllerLanguage extends JControllerLegacy
{

	function save()
	{
		$model = $this->getModel('language');
		$data = JRequest::get('post', JREQUEST_ALLOWHTML);
		$model->save($data);
		$lang = $data['lang'];
		$item = $data['item'];
		$url = 'index.php?option=com_osmembership&view=language&lang=' . $lang . '&item=' . $item;
		$msg = JText::_('Traslation saved');
		$this->setRedirect($url, $msg);
	}

	function cancel()
	{
		$this->setRedirect('index.php?option=com_osmembership&view=subscribers');
	}
}