<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'settings.php');

class FSSAdminHelper
{
	static function PageSubTitle2($title,$usejtext = true)
	{
		if ($usejtext)
			$title = JText::_($title);
		
		return str_replace("$1",$title,FSS_Settings::get('display_h3'));
	}
	
	static function IsFAQs()
	{
		if (JRequest::getVar('option') == "com_fsf")
			return true;
		return false;	
	}
	
	static function IsTests()
	{
		if (JRequest::getVar('option') == "com_fst")
			return true;
		return false;	
	}
	
	static function GetVersion($path = "")
	{
		
		global $fsj_version;
		if (empty($fsj_version))
		{
			if ($path == "") $path = JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_fss';
			$file = $path.DS.'fss.xml';
			
			if (!file_exists($file))
				return FSS_Settings::get('version');
			
			$xml = simplexml_load_file($file);
			
			$fsj_version = $xml->version;
		}

		if ($fsj_version == "[VERSION]")
			return FSS_Settings::get('version');
			
		return $fsj_version;
	}	

	static function GetInstalledVersion()
	{
		return FSS_Settings::get('version');
	}
	
	static function DoSubToolbar($bare = false)
	{
		if (!$bare)
		{
			if (JFactory::getUser()->authorise('core.admin', 'com_fss'))    
			{        
				JToolBarHelper::preferences('com_fss');
			}
			JToolBarHelper::divider();
			JToolBarHelper::help("",false,"http://www.freestyle-joomla.com/comhelp/fss/admin-view-" . JRequest::getVar('view'));
		}
		
		$vName = JRequest::getCmd('view', 'fsss');
			
		JSubMenuHelper::addEntry(
			JText::_('COM_FSS_OVERVIEW'),
			'index.php?option=com_fss&view=fsss',
			$vName == 'fsss' || $vName == ""
			);
			
		JSubMenuHelper::addEntry(
			JText::_('COM_FSS_SETTINGS'),
			'index.php?option=com_fss&view=settings',
			$vName == 'settings'
			);

		JSubMenuHelper::addEntry(
			JText::_('COM_FSS_TEMPLATES'),
			'index.php?option=com_fss&view=templates',
			$vName == 'templates'
			);

		JSubMenuHelper::addEntry(
			JText::_('COM_FSS_VIEW_SETTINGS'),
			'index.php?option=com_fss&view=settingsview',
			$vName == 'settingsview'
			);

		JSubMenuHelper::addEntry(
			JText::_('COM_FSS_MAIN_MENU'),
			'index.php?option=com_fss&view=mainmenus',
			$vName == 'mainmenus'
			);

		JSubMenuHelper::addEntry(
			JText::_('COM_FSS_FAQS'),
			'index.php?option=com_fss&view=faqs',
			$vName == 'faqs'
			);

		JSubMenuHelper::addEntry(
			JText::_('COM_FSS_FAQ_CATEGORIES'),
			'index.php?option=com_fss&view=faqcats',
			$vName == 'faqcats'
			);

		JSubMenuHelper::addEntry(
			JText::_('COM_FSS_PRODUCTS'),
			'index.php?option=com_fss&view=prods',
			$vName == 'prods'
			);

		JSubMenuHelper::addEntry(
			JText::_('COM_FSS_MODERATION'),
			'index.php?option=com_fss&view=tests',
			$vName == 'tests'
			);

		JSubMenuHelper::addEntry(
			JText::_('COM_FSS_KB_CATS'),
			'index.php?option=com_fss&view=kbcats',
			$vName == 'kbcats'
			);

		JSubMenuHelper::addEntry(
			JText::_('COM_FSS_KB_ARTICLES'),
			'index.php?option=com_fss&view=kbarts',
			$vName == 'kbarts'
			);

		JSubMenuHelper::addEntry(
			JText::_('COM_FSS_CUSTOM_FIELDS'),
			'index.php?option=com_fss&view=fields',
			$vName == 'fields'
			);
	
		JSubMenuHelper::addEntry(
			JText::_('COM_FSS_GLOSSARY'),
			'index.php?option=com_fss&view=glossarys',
			$vName == 'glossarys'
			);


		JSubMenuHelper::addEntry(
			JText::_('COM_FSS_TICKET_CATEGORIES'),
			'index.php?option=com_fss&view=ticketcats',
			$vName == 'ticketcats'
			);

		JSubMenuHelper::addEntry(
			JText::_('COM_FSS_TICKET_DEPARTMENTS'),
			'index.php?option=com_fss&view=ticketdepts',
			$vName == 'ticketdepts'
			);

		JSubMenuHelper::addEntry(
			JText::_('COM_FSS_TICKET_PRIORITIES'),
			'index.php?option=com_fss&view=ticketpris',
			$vName == 'ticketpris'
			);

		JSubMenuHelper::addEntry(
			JText::_('COM_FSS_TICKET_GROUPS'),
			'index.php?option=com_fss&view=ticketgroups',
			$vName == 'ticketgroups'
			);

		JSubMenuHelper::addEntry(
			JText::_('COM_FSS_TICKET_STATUS'),
			'index.php?option=com_fss&view=ticketstatuss',
			$vName == 'ticketstatus'
			);

		JSubMenuHelper::addEntry(
			JText::_('COM_FSS_TICKET_EMAIL_ACCOUNTS'),
			'index.php?option=com_fss&view=ticketemails',
			$vName == 'ticketemails'
			);

		JSubMenuHelper::addEntry(
			JText::_('COM_FSS_ANNOUNCEMENTS'),
			'index.php?option=com_fss&view=announces',
			$vName == 'announces'
			);

		JSubMenuHelper::addEntry(
			JText::_('COM_FSS_EMAIL_TEMPLATES'),
			'index.php?option=com_fss&view=emails',
			$vName == 'emails'
			);

		JSubMenuHelper::addEntry(
			JText::_('Permissions'),
			'index.php?option=com_fss&view=fusers',
			$vName == 'fusers' || $vName == 'permission'
			);

			JSubMenuHelper::addEntry(
			JText::_('COM_FSS_ADMIN'),
			'index.php?option=com_fss&view=backup',
			$vName == 'backup'
			);

		JSubMenuHelper::addEntry(
			JText::_('LOG'),
			'index.php?option=com_fss&view=cronlog',
			$vName == 'cronlog'
			);

		JSubMenuHelper::addEntry(
			JText::_('EMAIL_LOG'),
			'index.php?option=com_fss&view=emaillog',
			$vName == 'emaillog'
			);

		JSubMenuHelper::addEntry(
			JText::_('HELP_TEXT'),
			'index.php?option=com_fss&view=helptexts',
			$vName == 'helptexts'
			);
	}	
	
	
	static function IncludeHelp($file)
	{
		$lang = JFactory::getLanguage();
		$tag = $lang->getTag();
		
		$path = JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_fss'.DS.'help'.DS.$tag.DS.$file;
		if (file_exists($path))
			return file_get_contents($path);
		
		$path = JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_fss'.DS.'help'.DS.'en-GB'.DS.$file;
		
		return file_get_contents($path);
	}
	
	static $langs;
	static $lang_bykey;
	static function DisplayLanguage($language)
	{
		if (empty(FSSAdminHelper::$langs))
		{
			FSSAdminHelper::LoadLanguages();
		}
		
		if (array_key_exists($language, FSSAdminHelper::$lang_bykey))
			return FSSAdminHelper::$lang_bykey[$language]->text;
		
		return "";
	}
	
	static function LoadLanguages()
	{		
		$deflang = new stdClass();
		$deflang->value = "*";
		$deflang->text = JText::_('JALL');
		
		FSSAdminHelper::$langs = array_merge(array($deflang) ,JHtml::_('contentlanguage.existing'));
		
		foreach (FSSAdminHelper::$langs as $lang)
		{
			FSSAdminHelper::$lang_bykey[$lang->value] = $lang;	
		}		
	}
	
	static function GetLanguagesForm($value)
	{
		if (empty(FSSAdminHelper::$langs))
		{
			FSSAdminHelper::LoadLanguages();
		}
		
		return JHTML::_('select.genericlist',  FSSAdminHelper::$langs, 'language', 'class="inputbox" size="1" ', 'value', 'text', $value);
	}
	
	static $access_levels;
	static $access_levels_bykey;
	
	static function DisplayAccessLevel($access)
	{
		if (empty(FSSAdminHelper::$access_levels))
		{
			FSSAdminHelper::LoadAccessLevels();
		}
		
		if (array_key_exists($access, FSSAdminHelper::$access_levels_bykey))
			return FSSAdminHelper::$access_levels_bykey[$access];
		
		return "";
		
	}
	
	static function LoadAccessLevels()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('a.id AS value, a.title AS text');
		$query->from('#__viewlevels AS a');
		$query->group('a.id, a.title, a.ordering');
		$query->order('a.ordering ASC');
		$query->order($query->qn('title') . ' ASC');

		// Get the options.
		$db->setQuery($query);
		FSSAdminHelper::$access_levels = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum())
		{
			JError::raiseWarning(500, $db->getErrorMsg());
			return null;
		}

		foreach (FSSAdminHelper::$access_levels as $al)
		{
			FSSAdminHelper::$access_levels_bykey[$al->value] = $al->text;
		}	
	}
	
	static function GetAccessForm($value)
	{
		return JHTML::_('access.level',	'access',  $value, 'class="inputbox" size="1"', false);
	}
	
	static $filter_lang;
	static $filter_access;
	static function LA_GetFilterState()
	{
		$mainframe = JFactory::getApplication();
		FSSAdminHelper::$filter_lang	= $mainframe->getUserStateFromRequest( 'la_filter.'.'fss_filter_language', 'fss_filter_language', '', 'string' );
		FSSAdminHelper::$filter_access	= $mainframe->getUserStateFromRequest( 'la_filter.'.'fss_filter_access', 'fss_filter_access', 0, 'int' );
	}
	
	static function LA_Filter($nolangs = false)
	{
		if (empty(FSSAdminHelper::$access_levels))
		{
			FSSAdminHelper::LoadAccessLevels();
		}
		
		if (!$nolangs && empty(FSSAdminHelper::$langs))
		{
			FSSAdminHelper::LoadLanguages();
		}
	
		if (empty(FSSAdminHelper::$filter_lang))
		{
			FSSAdminHelper::LA_GetFilterState();
		}
		
		$options = FSSAdminHelper::$access_levels;		
		array_unshift($options, JHtml::_('select.option', 0, JText::_('JOPTION_SELECT_ACCESS')));
		echo JHTML::_('select.genericlist',  $options, 'fss_filter_access', 'class="inputbox" size="1"  onchange="document.adminForm.submit( );"', 'value', 'text', FSSAdminHelper::$filter_access);
		
		if (!$nolangs)
		{
			$options = FSSAdminHelper::$langs;		
			array_unshift($options, JHtml::_('select.option', '', JText::_('JOPTION_SELECT_LANGUAGE')));
			echo JHTML::_('select.genericlist',  $options, 'fss_filter_language', 'class="inputbox" size="1"  onchange="document.adminForm.submit( );"', 'value', 'text', FSSAdminHelper::$filter_lang);
		}
	}
	
	static function LA_Header($obj, $nolangs = false)
	{
		if (!$nolangs)
		{
			?>
 			<th width="1%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',   'LANGUAGE', 'language', @$obj->lists['order_Dir'], @$obj->lists['order'] ); ?>
			</th>
			<?php
		}
			
		?>
 		<th width="1%" nowrap="nowrap">
			<?php echo JHTML::_('grid.sort',   'ACCESS_LEVEL', 'access', @$obj->lists['order_Dir'], @$obj->lists['order'] ); ?>
		</th>
		<?php
	}
	
	static function LA_Row($row, $nolangs = false)
	{
		if (!$nolangs)
		{
			?>
			<td>
				<?php echo FSSAdminHelper::DisplayLanguage($row->language); ?></a>
			</td>
			<?php
		}
			
		?>
		<td>
			<?php echo FSSAdminHelper::DisplayAccessLevel($row->access); ?></a>
		</td>
		<?php
	}
	
	static function LA_Form($item, $nolangs = false)
	{
		?>
		<tr>
			<td width="135" align="right" class="key">
				<label for="title">
					<?php echo JText::_("JFIELD_ACCESS_LABEL"); ?>:
				</label>
			</td>
			<td>
				<?php echo FSSAdminHelper::GetAccessForm($item->access); ?>
			</td>
		</tr>
			
		<?php
		if (!$nolangs)
		{
		?>

			<tr>
				<td width="135" align="right" class="key">
					<label for="title">
						<?php echo JText::_("JFIELD_LANGUAGE_LABEL"); ?>:
					</label>
				</td>
				<td>
					<?php echo FSSAdminHelper::GetLanguagesForm($item->language); ?>
				</td>
			</tr>
				
		<?php
		}
	}
	
	static function HTMLDisplay($text, $chars = 100)
	{
		$stripped = strip_tags($text);
		$output = substr($stripped, 0, $chars); 
		if (strlen($stripped) > $chars)	$output .= "&hellip;";	

		return $output;
	}
}