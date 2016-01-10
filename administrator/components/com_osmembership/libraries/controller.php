<?php
/**
 * @version		1.0
 * @package		Joomla
 * @subpackage	OSFramework 
 * @author  Tuan Pham Ngoc
 * @copyright	Copyright (C) 2012 - 2014 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die();

class OSController extends JControllerLegacy
{

	var $component = '';

	var $entityName = '';

	var $langPrefix = '';

	var $viewListUrl = '';

	function __construct($config)
	{
		parent::__construct($config);
		
		$this->component = JRequest::getCmd('option');
		
		if (isset($config['entity_name']))
			$this->entityName = $config['entity_name'];
		else
			$this->entityName = $this->getEntityName();
		
		if (isset($config['language_prefix']))
		{
			$this->langPrefix = $config['language_prefix'];
		}
		else
		{
			$this->langPrefix = OSF_LANG_PREFIX;
		}
		
		if (isset($config['view_list_url']))
			$this->viewListUrl = $config['view_list_url'];
		else
			$this->viewListUrl = 'index.php?option=' . $this->component . '&view=' . OSInflector::pluralize($this->entityName);
		
		$this->registerTask('apply', 'save');
	}

	/**
	 * Basic add function
	 */
	function add()
	{
		JRequest::setVar('view', $this->entityName);
		JRequest::setVar('edit', false);
		$this->display();
	}

	/**
	 * Basic edit function
	 */
	function edit()
	{
		JRequest::setVar('view', $this->entityName);
		JRequest::setVar('edit', true);
		
		$this->display();
	}

	/**
	 * Implementing Generic save function
	 */
	function save()
	{
		$post = JRequest::get('post', JREQUEST_ALLOWRAW);
		$model = $this->getModel($this->entityName);
		$cid = $post['cid'];
		$post['id'] = (int) $cid[0];
		$ret = $model->store($post);
		if ($ret)
		{
			$msg = JText::_($this->langPrefix . '_' . strtoupper($this->entityName) . '_SAVED');
		}
		else
		{
			$msg = JText::_($this->langPrefix . '_' . strtoupper($this->entityName) . '_SAVING_ERROR');
		}
		$task = $this->getTask();
		
		if ($task == 'save')
		{
			$url = $this->viewListUrl;
		}
		else
		{
			$url = $this->getEditEntityUrl($post['id']);
		}
		
		$this->setRedirect($url, $msg);
	}

	/**
	 * Save ordering of the record
	 */
	function save_order()
	{
		$order = JRequest::getVar('order', array(), 'post');
		$cid = JRequest::getVar('cid', array(), 'post');
		JArrayHelper::toInteger($order);
		JArrayHelper::toInteger($cid);
		$model = & $this->getModel($this->entityName);
		$ret = $model->saveOrder($cid, $order);
		if ($ret)
		{
			$msg = JText::_($this->langPrefix . '_ORDERING_SAVED');
		}
		else
		{
			$msg = JText::_($this->langPrefix . '_ORDERING_SAVING_ERROR');
		}
		
		$this->setRedirect($this->viewListUrl, $msg);
	}

	/**
	 * Order up an entity from the list
	 *
	 */
	function orderup()
	{
		$model = $this->getModel($this->entityName);
		$model->move(-1);
		$msg = JText::_($this->langPrefix . '_ORDERING_UPDATED');
		
		$this->setRedirect($this->viewListUrl, $msg);
	}

	/**
	 * Order down an entity from the list
	 *
	 */
	function orderdown()
	{
		$model = $this->getModel($this->entityName);
		$model->move(1);
		$msg = JText::_($this->langPrefix . '_ORDERING_UPDATED');
		
		$this->setRedirect($this->viewListUrl, $msg);
	}

	/**
	 * Remove entities function
	 *
	 */
	function remove()
	{
		$model = $this->getModel($this->entityName);
		$cid = JRequest::getVar('cid', array());
		JArrayHelper::toInteger($cid);
		$model->delete($cid);
		$msg = JText::_($this->langPrefix . '_' . OSInflector::pluralize($this->entityName) . '_REMOVED');
		
		$this->setRedirect($this->viewListUrl, $msg);
	}

	/**
	 * Publish entities
	 *
	 */
	function publish()
	{
		$cid = JRequest::getVar('cid', array(), 'post');
		JArrayHelper::toInteger($cid);
		
		$model = & $this->getModel($this->entityName);
		$ret = $model->publish($cid, 1);
		if ($ret)
			$msg = JText::_($this->langPrefix . '_' . OSInflector::pluralize($this->entityName) . '_PUBLISHED');
		else
			$msg = JText::_($this->langPrefix . '_' . OSInflector::pluralize($this->entityName) . '_PUBLISH_ERROR');
		
		$this->setRedirect($this->viewListUrl, $msg);
	}

	/**
	 * Unpublish entities
	 *
	 */
	function unpublish()
	{
		$cid = JRequest::getVar('cid', array(), 'post');
		JArrayHelper::toInteger($cid);
		$model = & $this->getModel($this->entityName);
		$ret = $model->publish($cid, 0);
		if ($ret)
			$msg = JText::_($this->langPrefix . '_' . OSInflector::pluralize($this->entityName) . '_UNPUBLISHED');
		else
			$msg = JText::_($this->langPrefix . '_' . OSInflector::pluralize($this->entityName) . '_UNPUBLISH_ERROR');
		
		$this->setRedirect($this->viewListUrl, $msg);
	}

	/**
	 * Copy entity function
	 *
	 */
	function copy()
	{
		$cid = JRequest::getVar('cid', array(), 'post');
		JArrayHelper::toInteger($cid);
		$id = $cid[0];
		$model = $this->getModel($this->entityName);
		$newId = $model->copy($id);
		$msg = JText::_($this->langPrefix . '_' . $this->entityName . '_COPIED');
		
		if ($newId)
		{
			$url = $this->getEditEntityUrl($newId);
		}
		else
		{
			$url = $this->viewListUrl;
		}
		
		$this->setRedirect($url, $msg);
	}

	/**
	 * Cancel the entity . Redirect user to items list page
	 *
	 */
	function cancel()
	{
		
		$this->setRedirect($this->viewListUrl);
	}

	/**
	 * Get name of entity which we are working on
	 */
	public function getEntityName()
	{
		if (empty($this->entityName))
		{
			$r = null;
			if (preg_match('/(.*)Controller(.*)/i', get_class($this), $r))
			{
				$this->entityName = strtolower($r[2]);
			}
		}
		
		return $this->entityName;
	}

	function getEditEntityUrl($id = 0)
	{
		return 'index.php?option=' . $this->component . '&view=' . $this->entityName . '&cid[]=' . $id;
	}
}