<?php
/**
 * @version        1.6.7
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

/**
 * Membership Pro Component Plugin Model
 *
 * @package        Joomla
 * @subpackage     Membership Pro
 */
class OSMembershipModelPlugin extends OSModel
{

	/**
	 * Save plugin parameter
	 * @see OSModel::store()
	 */
	function store(&$data)
	{
		$row = & $this->getTable('Osmembership', 'Plugin');
		if ($data['id'])
			$row->load($data['id']);
		if (!$row->bind($data))
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}
		//Save parameters
		$params = JRequest::getVar('params', null, 'post', 'array');
		if (is_array($params))
		{
			$txt = array();
			foreach ($params as $k => $v)
			{
				if (is_array($v))
				{
					$v = implode(',', $v);
				}
				$v     = str_replace("\r\n", '@@', $v);
				$txt[] = "$k=\"$v\"";
			}
			$row->params = implode("\n", $txt);
		}
		if (!$row->store())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}
		$data['id'] = $row->id;

		return true;

	}

	/**
	 * Install a plugin
	 * @return boolean
	 */
	function install()
	{
		jimport('joomla.filesystem.folder');
		jimport('joomla.filesystem.archive');
		$db     = JFactory::getDbo();
		$plugin = JRequest::getVar('plugin_package', null, 'files');
		if ($plugin['error'] || $plugin['size'] < 1)
		{
			JRequest::setVar('msg', JText::_('Upload plugin package error'));

			return false;
		}
		$dest     = JFactory::getConfig()->get('tmp_path') . '/' . $plugin['name'];
		$uploaded = JFile::upload($plugin['tmp_name'], $dest);
		if (!$uploaded)
		{
			JRequest::setVar('msg', JText::_('OSM_PLUGIN_UPLOAD_FAILED'));

			return false;
		}
		// Temporary folder to extract the archive into
		$tmpdir     = uniqid('install_');
		$extractdir = JPath::clean(dirname($dest) . '/' . $tmpdir);
		$result     = JArchive::extract($dest, $extractdir);
		if (!$result)
		{
			JRequest::setVar('msg', JText::_('OSM_EXTRACT_PLUGIN_ERROR'));

			return false;
		}
		$dirList = array_merge(JFolder::files($extractdir, ''), JFolder::folders($extractdir, ''));
		if (count($dirList) == 1)
		{
			if (JFolder::exists($extractdir . '/' . $dirList[0]))
			{
				$extractdir = JPath::clean($extractdir . '/' . $dirList[0]);
			}
		}
		//Now, search for xml file
		$xmlfiles = JFolder::files($extractdir, '.xml$', 1, true);
		if (empty($xmlfiles))
		{
			JRequest::setVar('msg', JText::_('OSM_COULD_NOT_FIND_XML_FILE'));

			return false;
		}
		$file       = $xmlfiles[0];
		$root       = JFactory::getXML($file, true);
		$pluginType = $root->attributes()->type;
		if ($root->getName() !== 'install')
		{
			JRequest::setVar('msg', JText::_('OSM_INVALID_XML_FILE'));

			return false;
		}
		if ($pluginType != 'osmplugin')
		{
			JRequest::setVar('msg', JText::_('OSM_INVALID_OSM_PLUGIN'));

			return false;
		}
		$name         = (string) $root->name;
		$title        = (string) $root->title;
		$author       = (string) $root->author;
		$creationDate = (string) $root->creationDate;
		$copyright    = (string) $root->copyright;
		$license      = (string) $root->license;
		$authorEmail  = (string) $root->authorEmail;
		$authorUrl    = (string) $root->authorUrl;
		$version      = (string) $root->version;
		$description  = (string) $root->description;
		$row          = JTable::getInstance('OSMembership', 'Plugin');
		$sql          = 'SELECT id FROM #__osmembership_plugins WHERE name="' . $name . '"';
		$db->setQuery($sql);
		$pluginId = (int) $db->loadResult();
		if ($pluginId)
		{
			$row->load($pluginId);
			$row->name          = $name;
			$row->title         = $title;
			$row->author        = $author;
			$row->creation_date = $creationDate;
			$row->copyright     = $copyright;
			$row->license       = $license;
			$row->author_email  = $authorEmail;
			$row->author_url    = $authorUrl;
			$row->version       = $version;
			$row->description   = $description;
		}
		else
		{
			$row->name          = $name;
			$row->title         = $title;
			$row->author        = $author;
			$row->creation_date = $creationDate;
			$row->copyright     = $copyright;
			$row->license       = $license;
			$row->author_email  = $authorEmail;
			$row->author_url    = $authorUrl;
			$row->version       = $version;
			$row->description   = $description;
			$row->published     = 0;
			$row->ordering      = $row->getNextOrder('published=1');
		}
		$row->store();

		// Update plugins which support recurring payments
		$recurringPlugins = array(
			'os_paypal',
			'os_authnet',
			'os_paypal_pro',
			'os_stripe'
		);

		if (in_array($row->name, $recurringPlugins))
		{
			$sql = 'UPDATE #__osmembership_plugins SET support_recurring_subscription = 1 WHERE name IN ("' . implode('","', $recurringPlugins) . '")';
			$db->setQuery($sql);
			$db->execute();
		}

		$pluginDir = JPATH_ROOT . '/components/com_osmembership/plugins';
		JFile::move($file, $pluginDir . '/' . basename($file));
		$files = $root->files->children();
		for ($i = 0, $n = count($files); $i < $n; $i++)
		{
			$file = $files[$i];
			if ($file->getName() == 'filename')
			{
				$fileName = $file;
				if (!JFile::exists($pluginDir . '/' . $fileName))
				{
					JFile::copy($extractdir . '/' . $fileName, $pluginDir . '/' . $fileName);
				}
			}
			elseif ($file->getName() == 'folder')
			{
				$folderName = $file;
				if (JFolder::exists($extractdir . '/' . $folderName))
				{
					JFolder::move($extractdir . '/' . $folderName, $pluginDir . '/' . $folderName);
				}
			}
		}

		JFolder::delete($extractdir);

		return true;
	}

	/**
	 * Remove the selected plugin
	 * @see OSModel::delete()
	 */
	function delete($cid = array())
	{
		$row       = JTable::getInstance('OSMembership', 'Plugin');
		$pluginDir = JPATH_ROOT . '/components/com_osmembership/plugins';
		foreach ($cid as $id)
		{
			$row->load($id);
			$name = $row->name;
			$file = $pluginDir . '/' . $name . '.xml';
			if (!JFile::exists($file))
			{
				//Simply delete the record
				$row->delete();

				return true;
			}
			else
			{
				$root  = JFactory::getXML($file);
				$files = $root->files->children();
				for ($i = 0, $n = count($files); $i < $n; $i++)
				{
					$file = $files[$i];
					if ($file->getName() == 'filename')
					{
						$fileName = $file;
						if (JFile::exists($pluginDir . '/' . $fileName))
						{
							JFile::delete($pluginDir . '/' . $fileName);
						}
					}
					elseif ($file->getName() == 'folder')
					{
						$folderName = $file;
						if ($folderName)
						{
							if (JFolder::exists($pluginDir . '/' . $folderName))
							{
								JFolder::delete($pluginDir . '/' . $folderName);
							}
						}
					}
				}
				JFile::delete($pluginDir . '/' . $name . '.xml');
				$row->delete();
			}
		}

		return true;
	}
}