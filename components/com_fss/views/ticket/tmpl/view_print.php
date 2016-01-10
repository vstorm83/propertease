<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

$template = FSS_Input::GetString("print");
$custom_print = Support_Print::loadPrint($template);

defined('_JEXEC') or die;
?>
<?php if (!$custom_print || (int)($custom_print->noheader) != 1): ?>
	<?php echo FSS_Helper::PageStyle(); ?>
	<?php echo FSS_Helper::PageSubTitle("TICKET_DETAILS"); ?>
<?php endif; ?>

<?php $this->print = true; ?>

<?php if ($custom_print): ?>

	<?php 
	// need to convert the ticket to a SupportTicket class for the print
	$this->ticket = $this->ticket_obj;
	?>
	
	<?php $file = Support_Print::outputPrint($template, $custom_print); ?>
	<?php include $file; ?>

<?php else: ?>
	
	<div class="fss_main">
	<table class='table table-borderless table-condensed table-narrow' style="min-width:300px" >

		<tr>
			<th><?php echo JText::_("TITLE"); ?></th>
			<td><?php echo $this->ticket['title']; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_("TICKET_ID"); ?></th>
			<td><?php echo $this->ticket['reference']; ?></td>
		</tr>

	<?php if ($this->multiuser) : ?>
		<tr>
			<th><?php echo JText::_("USER"); ?></th>
			<td><?php echo $this->user['name']; ?></td>
		</tr>
	<?php endif; ?>

	<?php if ($this->show_cc) : ?>
		<tr>
			<th><?php echo JText::_("CC_USERS"); ?></th>
			<td>
				<div id="ccusers">
					<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_ccusers.php'); ?>
				</div>
			</td>
		</tr>
	<?php endif; ?>

	<?php if ($this->ticket['password']): ?>
		<tr>
			<th><?php echo JText::_("PASSWORD"); ?></th>
			<td><?php echo $this->ticket['password']; ?></td>
		</tr>
	<?php endif; ?>

	<?php if ($this->ticket['product']): ?>
		<tr>
			<th><?php echo JText::_("PRODUCT"); ?></th>
			<td><?php echo FSS_Translate_Helper::TrF('title', $this->ticket['product'], $this->ticket['prtr']); ?></td>
		</tr>
	<?php endif; ?>

	<?php if ($this->ticket['dept']): ?>
		<tr>
			<th><?php echo JText::_("DEPARTMENT"); ?></th>
			<td><?php echo FSS_Translate_Helper::TrF('title', $this->ticket['dept'], $this->ticket['dtr']); ?></td>
		</tr>
	<?php endif; ?>

	<?php if ($this->ticket['cat'] && !FSS_Settings::get('support_hide_category')): ?>
		<tr>
			<th><?php echo JText::_("CATEGORY"); ?></th>
			<td><?php echo FSS_Translate_Helper::TrF('title', $this->ticket['cat'], $this->ticket['ctr']); ?></td>
		</tr>
	<?php endif; ?>

		<tr>
			<th><?php echo JText::_("LAST_UPDATE"); ?></th>
			<td><?php echo FSS_Helper::Date($this->ticket['lastupdate'], FSS_DATETIME_MID); ?></td>
		</tr>

	<?php $st = FSS_Ticket_Helper::GetStatusByID($this->ticket['ticket_status_id']); ?>

	<?php if ($st->is_closed) : ?>
		<tr>
			<th><?php echo JText::_("CLOSED"); ?></th>
			<td><?php echo FSS_Helper::Date($this->ticket['closed'], FSS_DATETIME_MID); ?></td>
		</tr>
	<?php endif; ?>

	<?php if (!FSS_Settings::get('support_hide_handler')) : ?>
		<tr>
			<th><?php echo JText::_("HANDLER"); ?></th>
			<td><?php if ($this->ticket['assigned']) {echo $this->ticket['assigned'];} else {echo JText::_("UNASSIGNED");} ?></td>
		</tr>
	<?php endif; ?>

	<?php foreach ($this->fields as $field): ?>
		<?php if ($field['grouping'] != "") continue; ?>
		<?php if ($field['permissions'] > 1 && $field['permissions'] != 5) continue; ?>

		<tr>
			<th width='<?php echo FSS_Settings::get('ticket_label_width'); ?>'><?php echo FSSCF::FieldHeader($field, false, false); ?></th>
<td>
				<?php echo FSSCF::FieldOutput($field, $this->fieldvalues, array('ticketid' => $this->ticket['id'], 'userid' => $this->ticket['user_id'], 'ticket' => $this->ticket_obj)); ?>
			</td>
		</tr>	
	<?php endforeach; ?>

		<tr>
			<th style="vertical-align: middle"><?php echo JText::_("STATUS"); ?></th>
	
			<td>
				<?php 
					$curstatus = $this->statuss[$this->ticket['sid']];
					if ($curstatus['combine_with'] > 0)
					{
						$new_status = $this->statuss[$curstatus['combine_with']];
						$this->ticket['sid'] = $new_status['combine_with'];	
						$this->ticket['scolor'] = $new_status['color'];
						$this->ticket['userdisp'] = $new_status['userdisp'];
						$this->ticket['str'] = $new_status['translation'];
						$this->ticket['status'] = $new_status['title'];
					}
				?>
			
				<?php $userstatus = FSS_Translate_Helper::TrF('userdisp', $this->ticket['userdisp'], $this->ticket['str']); ?>
				<?php $status = FSS_Translate_Helper::TrF('title', $this->ticket['status'], $this->ticket['str']); ?>
				<span style='color: <?php echo $this->ticket['scolor']; ?>'><?php echo $userstatus ? $userstatus : $status; ?></span>
			</td>
		</tr>

	<?php if (!FSS_Settings::get('support_hide_priority')) : ?>
		<tr>
			<th style="vertical-align: middle"><?php echo JText::_("PRIORITY"); ?></th>
			<td>
				<span style='color:<?php echo $this->ticket['pcolor']; ?>'><?php echo FSS_Translate_Helper::TrF('title', $this->ticket['pri'], $this->ticket['ptr']); ?></span>
			</td>
		</tr>
	<?php endif; ?>

	</table>

	<?php $grouping = ""; $open = false; ?>

	<?php foreach ($this->fields as $field) : ?>

		<?php if ($field['grouping'] == "")	continue; ?>
		<?php if ($field['permissions'] > 1 && $field['permissions'] != 5) continue; ?>
		
		<?php if ($field['grouping'] != $grouping): ?>
			<?php if ($open) echo "</table>";	?>
			<?php echo FSS_Helper::PageSubTitle($field['grouping']); ?>
			<table class='table table-borderless table-condensed table-narrow' style="min-width:300px">
			<?php $open = true;	$grouping = $field['grouping']; ?>
		<?php endif; ?>
	
		<tr>
			<th width='<?php echo FSS_Settings::get('ticket_label_width'); ?>'><?php echo FSSCF::FieldHeader($field, false, false); ?></th>
<td>
				<?php echo FSSCF::FieldOutput($field,$this->fieldvalues, array('ticketid' => $this->ticket['id'], 'userid' => $this->ticket['user_id'], 'ticket' => $this->ticket_obj)); ?>
			</td>
		</tr>	
	
	<?php endforeach; ?>

	<?php if ($open) echo "</table>"; ?>

	<?php echo FSS_Helper::PageSubTitle("MESSAGES"); ?>

	<div id="ticket_messages">
	<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_messages.php'); ?>
	</div>
	<?php if (count($this->attach) > 0) : ?>

	<?php echo FSS_Helper::PageSubTitle("ATTACHEMNTS"); ?>

			<?php foreach ($this->attach as $attach) : ?>
	
				<?php 
				$file_user_class = "warning";
				if (array_key_exists($attach['message_id'], FSS_Helper::$message_labels))
					$file_user_class = FSS_Helper::$message_labels[$attach['message_id']];
				?>
				
	
				<?php $image = in_array(strtolower(pathinfo($attach['filename'], PATHINFO_EXTENSION)), array('jpg','jpeg','png','gif')); ?>

				<div class="media padding-mini">
		
				<?php if ($image): ?>
					<div class="pull-left">
						<img class="media-object" src="<?php echo JRoute::_('index.php?option=com_fss&view=ticket&what=attach_thumb&fileid=' . $attach['id']); ?>" width="48" height="48">
					</div>
				<?php else: ?>
					<div class="pull-left large-dl-icon">
						<i class="icon-download"></i>
					</div>
				<?php endif; ?>		
			
					<div class="media-body">

						<div class="pull-right" style="text-align: right;">
							<?php echo FSS_Helper::display_filesize($attach['size']); ?><br />
							<?php echo FSS_Helper::Date($attach['added'], FSS_DATETIME_MID); ?>
						</div>

						<h4 class="media-heading"><?php echo $attach['filename']; ?></h4>
						<?php echo JText::_('UPLOADED_BY'); ?> 
						<span class="label label-<?php echo $file_user_class; ?>">
							<?php echo $attach['name']; ?>
						</span>
			
					</div>
				</div>

			<?php endforeach; ?>
	<?php endif; ?>
<?php endif; ?>

<?php if (!$custom_print || (int)($custom_print->noheader) != 1): ?>
	<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'_powered.php'; ?>
	<?php echo FSS_Helper::PageStyleEnd(); ?>
<?php endif; ?>

<script>
jQuery(document).ready( function () {
	//window.print();
});
</script>

</div>