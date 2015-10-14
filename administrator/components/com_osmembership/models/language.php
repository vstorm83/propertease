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
 * Membership Pro Component Language Model
 *
 * @package		Joomla
 * @subpackage	Membership Pro
 */
class OSMembershipModelLanguage extends JModelLegacy
{	
	/**
	 * Get language items and store them in an array
	 *
	 */
	function getTrans($lang, $item)
	{
		$app = JFactory::getApplication();
		$option = 'com_osmembership';
		$registry = new JRegistry();
		$languages = array();
		if (strpos($item, 'admin.') !== false)
		{
			$isAdmin = true;
			$item = substr($item, 6);
		}
		else
		{
			$isAdmin = false;
		}
		if ($isAdmin)
		{
			$path = JPATH_ROOT . '/administrator/language/en-GB/en-GB.' . $item . '.ini';
		}
		else
		{
			$path = JPATH_ROOT . '/language/en-GB/en-GB.' . $item . '.ini';
		}
		$registry->loadFile($path, 'INI');
		$languages['en-GB'][$item] = $registry->toArray();
		
		if ($isAdmin)
		{
			$path = JPATH_ROOT . '/administrator/language/' . $lang . '/' . $lang . '.' . $item . '.ini';
		}
		else
		{
			$path = JPATH_ROOT . '/language/' . $lang . '/' . $lang . '.' . $item . '.ini';
		}
		$search = $app->getUserStateFromRequest($option . 'search', 'search', '', 'string');
		$search = JString::strtolower($search);
		if (JFile::exists($path))
		{
			$registry->loadFile($path, 'INI');
			$languages[$lang][$item] = $registry->toArray();
		}
		else
		{
			$languages[$lang][$item] = array();
		}
		
		return $languages;
	}

	/**
	 *  Get site languages
	 *
	 */
	function getSiteLanguages()
	{
		jimport('joomla.filesystem.folder');
		$path = JPATH_ROOT . '/language';
		$folders = JFolder::folders($path);
		$rets = array();
		foreach ($folders as $folder)
		{
			if ($folder != 'pdf_fonts')
			{
				$rets[] = $folder;
			}				
		}
			
		return $rets;
	}

	/**
	 * Save translation data
	 *
	 * @param array $data
	 */
	function save($data)
	{		
		$lang = $data['lang'];
		$item = $data['item'];
		if (strpos($item, 'admin.') !== false)
		{
			$item = substr($item, 6);
			$filePath = JPATH_ROOT . '/administrator/language/' . $lang . '/' . $lang . '.' . $item . '.ini';
		}
		else
		{
			$filePath = JPATH_ROOT . '/language/' . $lang . '/' . $lang . '.' . $item . '.ini';
		}
		$keys = $data['keys'];
		$content = "";
		foreach ($keys as $key)
		{
			$value = $data[$key];
			$content .= "$key=\"$value\"\n";
		}
		if (isset($data['extra_keys']))
		{
			$keys = $data['extra_keys'];
			$values = $data['extra_values'];
			for ($i = 0, $n = count($keys); $i < $n; $i++)
			{
				$key = $keys[$i];
				$value = $values[$i];
				$content .= "$key=\"$value\"\n";
			}
		}
		JFile::write($filePath, $content);
		
		return true;
	}
}