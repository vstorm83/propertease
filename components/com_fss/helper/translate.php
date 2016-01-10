<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class FSS_Translate_Helper
{
	static function TrF($field, $current, $trdata)
	{
		$data = json_decode($trdata, true);
			
		if (!is_array($data))
			return $current;
		
		if (!array_key_exists($field, $data))
			return $current;
		
		$curlang = str_replace("-","",JFactory::getLanguage()->getTag());
		
		if (!array_key_exists($curlang, $data[$field]))
			return $current;
		
		return $data[$field][$curlang];	
	}
	
	static function Tr(&$data)
	{
		foreach ($data as &$item)
		{
			if (is_array($item))
				self::TrA($item);		
			if (is_object($item))
				self::TrO($item);	
		}
		return;	
	}
	
	static function TrSingle(&$data)
	{
		if (is_array($data))
			self::TrA($data);		
		if (is_object($data))
			self::TrO($data);	
		return;	
	}
	
	static function TrA(&$data)
	{
		// translate all fields in data that are found in the translation field
		$curlang = str_replace("-","",JFactory::getLanguage()->getTag());
		
		if (!array_key_exists("translation", $data))
			return;
		
		$translation = json_decode($data['translation'], true);
		if (!$translation)
			return;
		
		foreach ($translation as $field => $langs)
		{
			foreach ($langs as $lang => $text)
			{
				if ($lang == $curlang)
					$data[$field] = $text;
			}
		}
	}	
	
	static function TrO(&$data)
	{
		// translate all fields in data that are found in the translation field
		$curlang = str_replace("-","",JFactory::getLanguage()->getTag());
		
		if (!property_exists($data, "translation"))
			return;
		
		$translation = json_decode($data->translation, true);
		if (!$translation)
			return;
		
		foreach ($translation as $field => $langs)
		{
			foreach ($langs as $lang => $text)
			{
				if ($lang == $curlang)
					$data->$field = $text;
			}
		}
	}
	
	static function CalenderLocale() 
	{
		$js = '
			dhtmlXCalendarObject.prototype.langData["' . self::CalenderLocaleCode() . '"] = {
			dateformat: \'%d.%m.%Y\',
			monthesFNames: ["' . JText::_('JANUARY') . '","' . JText::_('FEBRUARY') . '","' . JText::_('MARCH') . '","' . JText::_('APRIL') . '",
							"' . JText::_('MAY') . '","' . JText::_('JUNE') . '","' . JText::_('JULY') . '","' . JText::_('AUGUST') . '",
							"' . JText::_('SEPTEMBER') . '","' . JText::_('OCTOBER') . '","' . JText::_('NOVEMBER') . '","' . JText::_('DECEMBER') . '"],
			monthesSNames: ["' . JText::_('JANUARY_SHORT') . '","' . JText::_('FEBRUARY_SHORT') . '","' . JText::_('MARCH_SHORT') . '","' . JText::_('APRIL_SHORT') . '",
							"' . JText::_('MAY_SHORT') . '","' . JText::_('JUNE_SHORT') . '","' . JText::_('JULY_SHORT') . '","' . JText::_('AUGUST_SHORT') . '",
							"' . JText::_('SEPTEMBER_SHORT') . '","' . JText::_('OCTOBER_SHORT') . '","' . JText::_('NOVEMBER_SHORT') . '","' . JText::_('DECEMBER_SHORT') . '"],
			daysFNames:	   ["' . JText::_('SUNDAY') . '","' . JText::_('MONDAY') . '","' . JText::_('TUESDAY') . '","' . JText::_('WEDNESDAY') . '",
							"' . JText::_('THURSDAY') . '","' . JText::_('FRIDAY') . '","' . JText::_('SATURDAY') . '"],
			daysSNames:    ["' . JText::_('SUN') . '","' . JText::_('MON') . '","' . JText::_('TUE') . '","' . JText::_('WED') . '",
							"' . JText::_('THU') . '","' . JText::_('FRI') . '","' . JText::_('SAT') . '"],
			weekstart: 1,
			weekname: "W" 
			};
			var fss_calendar_locale = "' . self::CalenderLocaleCode() . '"; 
		';
		$document = JFactory::getDocument();
		$document->addScriptDeclaration($js);
	}
	
	static function CalenderLocaleCode() 
	{
		$curlang = str_replace("-","",JFactory::getLanguage()->getTag());

		return $curlang;
	}	
}