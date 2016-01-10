<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<?php echo FSS_Helper::PageStyle(); ?>
<div class="current-ticket">
<?php echo FSS_Helper::PageTitle("SUPPORT","CURRENT_SUPPORT_TICKETS"); ?>
<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_tabbar.php'); ?>

<?php FSS_Helper::HelpText("support_user_list_header"); ?>

<form id="fssFormTS" action="<?php echo FSSRoute::_('index.php?option=com_fss&view=ticket&layout=view&tickets=' . FSS_Input::getCmd('tickets'));?>" method="post" name="fssForm" class="form-inline form-condensed">
	<input type="hidden" name="order_dir" id="order_dir" value="<?php echo FSS_Input::getCmd('order_dir', 'asc'); ?>" />

	<?php if (!FSS_Settings::get('support_simple_userlist_search')): ?>

        <div class="opt-search">
            <span>live search</span>
			<div class="pull-right opt-select-hidden">
				<select class="input-medium" name="order" onchange="jQuery('#fssFormTS').submit();">
					<?php
						$options = array(
							'' => JText::_('ORDERING_HEADER'),
							't.title' => JText::_('Title'),
							'status' => JText::_('Status'),
							'assigned' => JText::_('Handler'),
							'lastupdate' => JText::_('LAST_UPDATED'),
							'u.name' => JText::_('User')
							);
						$cur_order = FSS_Input::getCmd('order');
						foreach ($options as $key => $value)
						{
							echo "<option value='$key' ";
							if ($key == $cur_order)
								echo " selected";
							echo ">$value</option>\n";						
						}
					?>
				</select>
				<a class="btn btn-default fssTip" title="<?php echo JText::_('CHANGE_SORT_DIRECTION'); ?>" style="padding-left: 4px;padding-right: 6px;" onclick="toggleOrder();jQuery('#fssFormTS').submit();return false;"><i class="icon-menu-2"></i></a>
			</div>
		
			<div class="input-append">
				<input type="text" name="search" class='input-medium' id="basic_search" value="<?php echo FSS_Input::getString('search','') ?>" placeholder="<?php echo JText::_("SEARCH_TICKETS"); ?>">
				<a class='btn-search' onclick='fss_submit_search();return false;'>
					<i class="icon-search"></i>
					<?php echo JText::_("SEARCH") ?>
				</a>
					<a class='btn btn-default' type="submit" onclick="jQuery('#basic_search').val('');jQuery('#search_all').removeAttr('checked');jQuery('#fssFormTS').submit();return false;">
					<i class="icon-remove"></i>
					<?php echo JText::_("RESET") ?>
				</a>					
			</div>
			<label class="checkbox">
				<input type="checkbox" name="search_all" id="search_all" value="1" <?php if (FSS_Input::getString('search_all')) echo "checked"; ?>> <?php echo JText::_('SEARCH_ALL_MY_TICKETS'); ?>
			</label>
		</div>
	
	<?php endif; ?>
	<?php FSS_Helper::HelpText("support_user_list_after_search"); ?>

<?php if (count($this->tickets) < 1) { ?>

<?php echo JText::_("YOU_CURRENTLY_HAVE_NO_SUPPORT_TICKETS"); ?>

<?php } else { ?>

<table class='table table-bordered table-ticketborders table-condensed'>

<?php // $this->outputHeader(); ?>

<?php foreach ($this->tickets as $ticket): ?>
	
<?php $this->outputRow($ticket); ?>
	
<?php endforeach; ?>

</table>

	<?php echo $this->pagination->getListFooter(); ?>
<?php } ?>
</form>

<?php FSS_Helper::HelpText("support_user_list_after_footer"); ?>

<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'_powered.php'; ?>
<?php echo FSS_Helper::PageStyleEnd(); ?>
</div>
<script>

/*function highlightticket(ticketid)
{
	jQuery('.ticket_' + ticketid).each(function(){
		jQuery(this).attr('data-old_back',jQuery(this).css('background-color'));
		jQuery(this).css('background-color','<?php echo FSS_Settings::get('css_hl'); ?>');
	});
}

function unhighlightticket(ticketid)
{
	jQuery('.ticket_' + ticketid).each(function(){
		jQuery(this).css('background-color',jQuery(this).attr('data-old_back'));
	});
}*/

function toggleOrder()
{
	var order_dir = jQuery('#order_dir').val();
	if (order_dir == "asc")
	{
		jQuery('#order_dir').val('desc');	
	} else {
		jQuery('#order_dir').val('asc');	
	}	
	jQuery('#fssForm').submit();	
}

function fss_submit_search()
{
	jQuery('input[name="limitstart"]').val(0);
	jQuery("#fssFormTS").submit();
	return false;							
}
</script>