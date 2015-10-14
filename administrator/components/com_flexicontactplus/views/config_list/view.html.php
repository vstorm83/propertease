<?php
/********************************************************************
Product		: FlexicontactPlus
Date		: 08 January 2014
Copyright	: Les Arbres Design 2010-2014
Contact		: http://www.lesarbresdesign.info
Licence		: GNU General Public License
*********************************************************************/
defined('_JEXEC') or die('Restricted access');

jimport('joomla.html.html');

Class FlexicontactplusViewConfig_List extends JViewLegacy
{
function display($tpl = null)
{

	FCP_Admin::make_title('COM_FLEXICONTACT_CONFIG_MANAGE');
	if ($this->count_unique < LAFC_MAX_CONFIGS)
		JToolBarHelper::custom('add', 'new.png', 'new.png', JText::_('COM_FLEXICONTACT_TOOLBAR_NEW_CONFIG'), false);
	JToolBarHelper::custom('copy', 'new.png', 'new.png', JText::_('COM_FLEXICONTACT_TOOLBAR_NEW_LANG'), true);
	JToolBarHelper::editList();
	JToolBarHelper::publishList();
	JToolBarHelper::unpublishList();
	JToolBarHelper::deleteList();
	
	JToolBarHelper::cancel();
	
	$lang_text = JText::_('JFIELD_LANGUAGE_LABEL');

	$check_all = 'onclick="Joomla.checkAll(this);"';

	?>
	<form action="index.php" method="post" name="adminForm" id="adminForm">
	<input type="hidden" name="option" value="<?php echo LAFC_COMPONENT ?>" />
	<input type="hidden" name="controller" value="multiconfig" />
	<input type="hidden" name="task" value="display" />
	<input type="hidden" name="view" value="config_list" />
	<input type="hidden" name="boxchecked" value="0" />
	
	<table class="adminlist table table-striped">
	<thead>
			<th width="20">
				<input type="checkbox" name="toggle" value="" <?php echo $check_all; ?> />
			</th>
			<th width="20">
				<?php echo JText::_('COM_FLEXICONTACT_PUBLISHED'); ?>
			</th>
			<th nowrap="nowrap">
				<?php echo JText::_('COM_FLEXICONTACT_CONFIG_NAME'); ?>
			</th>
			<th width="5%" nowrap="nowrap">
				<?php echo $lang_text; ?>
			</th>
			<th nowrap="nowrap">
				<?php echo JText::_('COM_FLEXICONTACT_CONFIG_DESC'); ?>
			</th>				
		</tr>
	</thead>

	<?php
	$k = 0;
	
	if (!empty($this->config_list))
		{
		$numrows = count($this->config_list);

		for ($i=0; $i < $numrows; $i++) 
			{
			$config = $this->config_list[$i];
			
			$checked = JHTML::_('grid.id', $i, $config->id);

			if ($config->default_config)
				$published = '<img src="'.LAFC_ADMIN_ASSETS_URL.'default-16.png" border="0" alt="" />';
			else
				$published = JHTML::_('grid.published', $config, $i);

			$link = JRoute::_(LAFC_COMPONENT_LINK.'&controller=multiconfig&task=edit&cid[]='.$config->id);
			
			echo "<tr class='row$k'>";
			echo '  <td align="center">'.$checked.'</td>';
			echo '  <td align="center">'.$published.'</td>';
			echo '  <td>'.JHTML::link($link, $config->name).'</td>';
			echo '  <td>'.JHTML::link($link, $config->language).'</td>';
			echo '  <td>'.$config->description.'</td>';
			echo '</tr>';
			$k = 1 - $k;
			}
		}
	?>
	</table>
	</form>
	<?php
	}
}