<?php
/********************************************************************
Product		: FlexicontactPlus
Date		: 08 January 2014
Copyright	: Les Arbres Design 2010-2014
Contact		: http://www.lesarbresdesign.info
Licence		: GNU General Public License
*********************************************************************/
defined('_JEXEC') or die('Restricted Access');

class FlexicontactplusViewConfig_Images extends JViewLegacy
{
function display($tpl = null)
{
	FCP_Admin::make_title('COM_FLEXICONTACT_TOOLBAR_IMAGES');
	
	if (JFactory::getUser()->authorise('core.admin', 'com_flexicontactplus'))
		JToolBarHelper::deleteList('','delete_image');
		
// Get the list of themes

	$app = JFactory::getApplication();
	$filter_theme = $app->getUserStateFromRequest(LAFC_COMPONENT.'.filter_theme','filter_theme', THEME_ALL,'word');
	
	$theme_list = FCP_Admin::make_theme_list();
	$theme_list_html = FCP_Common::make_list('filter_theme', $filter_theme, $theme_list, 0, 'onchange="submitform( );"');
	
// load the front end Flexicontact language file for the current language

	$lang = JFactory::getLanguage();
	$language = $lang->get('tag');
	if (file_exists(JPATH_SITE.'/language/'.$language.'/'.$language.'.com_flexicontact_captcha.ini'))
		$lang->load('com_flexicontact_captcha', JPATH_SITE);	// 3rd parameter could specify language
	else
		$lang->load('com_flexicontactplus', JPATH_SITE);

// get an array of filenames
	
    $imageFiles = array();					// create array
    $handle = opendir(LAFC_SITE_IMAGES_PATH);
	if (!$handle)
		{
		echo JText::_('COM_FLEXICONTACT_NO_IMAGES_DIRECTORY');
		return;
		}
		
	while (($filename = readdir($handle)) != false)
		{
			if ($filename == '.' or $filename == '..')
				continue;
			$imageInfo = @getimagesize(LAFC_SITE_IMAGES_PATH.'/'.$filename);
			if ($imageInfo === false)
				continue;					// not an image
			if ($imageInfo[3] > 3)			// only support gif, jpg or png
				continue;
			if ($imageInfo[0] > 150)		// if X size > 150 pixels ..
				continue;					// .. it's too big so skip it
				
			// Do we display it?
			$prefix = substr($filename, 0, 2);
			
			switch ($filter_theme)
				{
				case THEME_ALL:
					$imageFiles[] = $filename;
					break;
				case THEME_STANDARD:
					if (substr($prefix, 0, 1) == '0')
						$imageFiles[] = $filename;
					break;
				case THEME_TOYS:
					if ($prefix == 'A_' or $prefix == 'B_')
						$imageFiles[] = $filename;
					break;
				case THEME_NEON:
					if ($prefix == 'C_')
						$imageFiles[] = $filename;
					break;
				case THEME_WHITE:
					if ($prefix == 'D_')
						$imageFiles[] = $filename;
					break;
				case THEME_BLACK:
					if ($prefix == 'E_')
						$imageFiles[] = $filename;
					break;
					
				}
   		}
    closedir($handle);
    if (empty($imageFiles) and $filter_theme == THEME_ALL)
		{
		echo JText::_('COM_FLEXICONTACT_NO_IMAGES');
		return;
		}
    $image_count = count($imageFiles);
	sort($imageFiles);

// start the form

	echo '<form action="index.php" method="post" name="adminForm" id="adminForm" >';
	echo '<input type="hidden" name="option" value="com_flexicontactplus" />';
	echo '<input type="hidden" name="controller" value="menu" />';
	echo '<input type="hidden" name="task" value="" />';
	echo '<input type="hidden" name="view" value="config_images" />';
	echo '<input type="hidden" name="boxchecked" value="0" />';
	echo '<input type="hidden" name="hidemainmenu" value="0" />';
	
// filters

	echo "\n".'<div>';
	echo $image_count.' '.JText::_('COM_FLEXICONTACT_IMAGES').' ';

// Joomla 3.0 took away the Javascript checkAll() function. Joomla.checkAll() first appeared in 1.7
	
	$check_all = 'onclick="Joomla.checkAll(this);"';

	echo '<input type="checkbox" name="toggle" value="" '.$check_all.' /> ';
	echo $theme_list_html;
	echo "\n</div>";

// draw the images
	
	$i = 0;
	foreach ($imageFiles as $filename)
		{
		$imageInfo = getimagesize(LAFC_SITE_IMAGES_PATH.'/'.$filename);
		if ($imageInfo !== false)
			{
			$imageX = $imageInfo[0];
			$imageY = $imageInfo[1];
			}
		
		$text_name = 'COM_FLEXICONTACT_IMAGE_'.strtoupper($filename);
		$description = JText::_($text_name);	// resolved by front end language file
		if ($text_name == $description)			// highlight if not resolved
			$description = '<span style="color: red">'.self::max_length($description, 17).'</span>';
		
		echo "\n".'<div style="display:inline-block;width:190px;border:1px solid gray;margin:1px;padding:5px;">';
		echo "\n".'  <img src="'.JURI::root().'components/com_flexicontactplus/images/'.$filename.'" alt="" style="float:left; border:none; margin:0 5px 0 0;"/>';
		echo "\n".'<b>'.utf8_encode($filename).'</b><br />';
		echo $description.'<br />';
		echo $imageX.'x'.$imageY.'<br />';
		echo "\n".JHTML::_('grid.id',   $i++, $filename);
		echo '</div>';
		}

	echo '</form>';
}

//-------------------------------------------------------------------------------
// Forcibly enable long strings to be wrapped by the browser
//
function max_length($text, $max_chars)
{
	if (strlen($text) <= $max_chars)
		return $text;
	return substr($text,0,$max_chars).' '.self::max_length(substr($text,$max_chars), $max_chars);
}

}