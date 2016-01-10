<?php
/********************************************************************
Product		: FlexicontactPlus
Date		: 12 November 2014
Copyright	: Les Arbres Design 2010-2014
Contact		: http://www.lesarbresdesign.info
Licence		: GNU General Public License
*********************************************************************/
defined('_JEXEC') or die('Restricted Access');

class FlexicontactplusViewConfig_Css extends JViewLegacy
{
function display($tpl = null)
{
	FCP_Admin::make_title('COM_FLEXICONTACT_CONFIG_CSS_NAME');
	JToolBarHelper::apply('apply_css');
	JToolBarHelper::save('save_css');
	JToolBarHelper::cancel();
		
	$css_files = FCP_Admin::get_css_list();
	
	if ($css_files == false)		// No CSS files?
		{
		$app = JFactory::getApplication();
		$app->redirect(LAFC_COMPONENT_LINK.'&task=display',
			JText::sprintf('COM_FLEXICONTACT_NO_CSS', LAFC_SITE_ASSETS_PATH), 'error');
		return;
		}
		
	$fail = false;
	$path = LAFC_SITE_ASSETS_PATH.'/';
	$css_file_name = $this->config_data->config_data->css_file;		// set the css name to be used by the view. Assume that the specified one is ok
	
// Check the current CSS file

	if (!file_exists($path.$css_file_name)) 
		{
		$app = JFactory::getApplication();
		$app->enqueueMessage(JText::_('COM_FLEXICONTACT_CSS_MISSING').' ('.$path.$css_file_name.')', 'error');
		FCP_trace::trace("Config_CSS View: ".$path.$css_file_name." missing");
		$fail = true;
		}
	else
		{
		if (!is_readable($path.$css_file_name)) 
			{ 
			$app = JFactory::getApplication();
			$app->enqueueMessage(JText::_('COM_FLEXICONTACT_CSS_NOT_READABLE').' ('.$path.$css_file_name.')', 'error');
			FCP_trace::trace("Config_CSS View: ".$path.$css_file_name." not readable");
			$fail = true;
			}

		if (!is_writable($path.$css_file_name)) 
			{ 
			$app = JFactory::getApplication();
			$app->enqueueMessage(JText::_('COM_FLEXICONTACT_CSS_NOT_WRITEABLE').' ('.$path.$css_file_name.')', 'error'); 
			FCP_trace::trace("Config_CSS View: ".$path.$css_file_name." not writeable");
			$fail = true;
			}
		}
		
// Do we have a problem with the selected CSS file? If so, try the default

	if ($fail)
		{
		if ($css_file_name !== LAFC_FRONT_CSS_NAME)				// No point doing this if the config css file was the default!
			{
			$fail = false;
			$css_file_name = LAFC_FRONT_CSS_NAME;
			FCP_trace::trace("-------------------->: Attempting to use default CSS file");
			if (!file_exists($path.$css_file_name)) 
				{ 
				FCP_trace::trace("-------------------->: ".$path.$css_file_name." missing");
				$fail = true;
				}
			else
				{
				if (!is_readable($path.$css_file_name)) 
					{ 
					FCP_trace::trace("-------------------->: ".$path.$css_file_name." not readable");
					$fail = true;
					}

				if (!is_writable($path.$css_file_name)) 
					{ 
					FCP_trace::trace("-------------------->: ".$path.$css_file_name." not writeable");
					$fail = true;
					}
				}
			}
		}
		
// If we still have a problem, find the first valid css file in the files list

	if ($fail)
		{
		foreach($css_files as $key => $value)
			{
			$css_file_name = $key;
			$fail = false;
			
			if (!file_exists($path.$css_file_name))
				$fail = true;
			if ((!$fail) and (!is_readable($path.$css_file_name))) 
				$fail = true;
			if ((!$fail) and (!is_writable($path.$css_file_name)))
				$fail = true;	
				
			if (!$fail)			// Found a functioning css file
				break;
			}
		}

// Still got a problem?

	if ($fail)
		{
		$app = JFactory::getApplication();
		$app->redirect(LAFC_COMPONENT_LINK.'&task=display',
			JText::sprintf('COM_FLEXICONTACT_NO_VALID_CSS', LAFC_SITE_ASSETS_PATH), 'error');
		return;
		}

	$css_contents = @file_get_contents($path.$css_file_name);
	
	?>
	<form action="index.php" method="post" name="adminForm" id="adminForm" >
	<input type="hidden" name="option" value="<?php echo LAFC_COMPONENT; ?>" />
	<input type="hidden" name="controller" value="menu" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="view" value="config_css" />
	
	<?php 
	echo '<table>';
	echo '<tr><td>'.FCP_Common::make_list('css_file_name', $css_file_name, $css_files, 0, 'onchange="submitform( );"').'</td></tr>';
	echo '<tr><td class="css_file_path">'.JText::_('COM_FLEXICONTACT_CSS_FILE'),': ('.$path.$css_file_name.')';
	echo '<tr><td>';
	echo '<textarea name="css_contents" rows="25" cols="125" style="width:auto;">'.$css_contents .'</textarea>';
	echo '</td><td valign="top">';
	echo FCP_Admin::make_info('www.w3schools.com/css','http://www.w3schools.com/css/default.asp');
	echo '</td></tr></table>';
	?>
	</form>
	<?php 
}

}