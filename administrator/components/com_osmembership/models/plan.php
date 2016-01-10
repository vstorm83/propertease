<?php
/**
 * @version		1.6.8
 * @package		Joomla
 * @subpackage	Membership Pro
 * @author  Tuan Pham Ngoc
 * @copyright	Copyright (C) 2012 - 2014 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

/**
 * OSemmbership Component Plan Model
 *
 * @package		Joomla
 * @subpackage	Membership Pro
 */
class OSMembershipModelPlan extends OSModel
{

	function _initData()
	{
		parent::_initData();
		$this->_data->enable_renewal = 1;	
	}
	
	function store(&$data)
	{
		$app = JFactory::getApplication();
		if (!$data['id'])
		{
			$isNew = true;
		}			
		else
		{
			$isNew = false;
			//Delete the old thumb if admin decides to do that		
			$row = $this->getTable('Osmembership', 'Plan');
			$row->load($data['id']);
			if (isset($data['del_thumb']) && $row->thumb)
			{
				if (JFile::exists(JPATH_ROOT . '/media/com_osmembership/' . $row->thumb))
				{
					JFile::delete(JPATH_ROOT . '/media/com_osmembership/' . $row->thumb);
				}
				$data['thumb'] = '';				
			}						
		}					
		$thumbImage = $app->input->files->get('thumb_image');
		if ($thumbImage['name'])
		{
			$fileExt = JString::strtoupper(JFile::getExt($thumbImage['name']));
			$supportedTypes = array('JPG', 'PNG', 'GIF');
			if (in_array($fileExt, $supportedTypes))
			{
				if (JFile::exists(JPATH_ROOT . '/media/com_osmembership/' . JString::strtolower($thumbImage['name'])))
				{
					$fileName = time() . '_' . JString::strtolower($thumbImage['name']);
				}
				else
				{
					$fileName = JString::strtolower($thumbImage['name']);
				}
				$imagePath = JPATH_ROOT . '/media/com_osmembership/' . $fileName;
				JFile::upload($_FILES['thumb_image']['tmp_name'], $imagePath);
				$data['thumb'] = $fileName;
			}
		}
		$ret = parent::store($data);
		if ($ret)
		{					
			$row = $this->getTable('Osmembership', 'Plan');
			$row->load($data['id']);
			//Trigger plugin
			JPluginHelper::importPlugin('osmembership');
			$dispatcher = JDispatcher::getInstance();
			//Trigger plugins
			$dispatcher->trigger('onAfterSaveSubscriptionPlan', array($row, $data, $isNew));
		}
		
		$db = JFactory::getDbo();
		if (!$isNew)
		{
			$sql = 'DELETE FROM #__osmembership_renewrates WHERE plan_id=' . $data['id'];
			$db->setQuery($sql);
			$db->execute();
		}
		//Store the renewal options		
		if (isset($data['number_days']))
		{
			for ($i = 0, $n = count($data['number_days']); $i < $n; $i++)
			{
				$numberDays = (int) $data['number_days'][$i];
				$price = $data['renew_price'][$i];
				if ($numberDays > 0 && $price > 0)
				{
					$sql = "INSERT INTO #__osmembership_renewrates(plan_id, number_days, price) VALUES(" . $data['id'] . ", $numberDays, $price)";
					$db->setQuery($sql);
					$db->execute();
				}
			}
		}
		//Store the upgrade option		
		if (!$isNew)
		{
			//Delete old data
			$sql = 'DELETE FROM #__osmembership_upgraderules WHERE from_plan_id='.(int)$data['id'];
			$db->setQuery($sql);
			$db->execute();
		}
		if (isset($data['to_plan_id']))
		{
			for ($i = 0; $i < count($data['to_plan_id']); $i++)
			{
				$toPlan = $data['to_plan_id'][$i];
				$price = floatval($data['upgrade_price'][$i]);
				$publishedRule = $data['rule_published'][$i];
				$sql = "INSERT INTO #__osmembership_upgraderules(from_plan_id, to_plan_id, price, published) VALUE (" . $data['id'] .
					 ", $toPlan, $price, $publishedRule)";
				$db->setQuery($sql);
				$db->execute();
			}
		}
		
		return $ret;
	}

	/**
	 * Copy a subscription plan
	 * @see OSModel::copy()
	 */
	function copy($id)
	{
		$copiedPlanId = parent::copy($id);
		//Insert data into renew options table
		$sql = 'INSERT INTO #__osmembership_renewrates(plan_id, number_days, price)' .
			 " SELECT $copiedPlanId, number_days, price FROM #__osmembership_renewrates WHERE plan_id=" . $id;
		
		$this->_db->setQuery($sql);
		$this->_db->query();
		
		return $copiedPlanId;
	}
	
	
	/**
	 * Delete related data
	 * @see OSModel::delete()
	 */
	function delete($cid = array())
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->delete('#__osmembership_articles')
			->where('plan_id IN ('.implode(',', $cid).')');
		$db->setQuery($query);
		$db->execute();
		//Delete from URL tables as well
		if (JPluginHelper::isEnabled('osmembership', 'urls'))
		{
			$query->clear();
			$query->delete('#__osmembership_urls')
				->where('plan_id IN ('.implode(',', $cid).')');
			$db->setQuery($query);
			$db->execute();
		}

		return parent::delete($cid);
	}
}