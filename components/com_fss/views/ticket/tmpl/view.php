<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

?>
<div class="detail-ticket">
<?php echo FSS_Helper::PageStyle(); ?>
<?php echo FSS_Helper::PageTitle("SUPPORT","VIEW_SUPPORT_TICKET"); ?>
<?php $st = FSS_Ticket_Helper::GetStatusByID($this->ticket['ticket_status_id']); ?>

<?php 
FSS_Translate_Helper::Tr($this->statuss);

$table_cols = FSS_Settings::get('support_info_cols_user');

$table_classes = "table table-borderless table-valign table-condensed table-narrow table-div";
if ($table_cols > 1)
	$table_classes = "table table-borderless table-valign table-condensed table-div";
?>

<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_tabbar.php'); ?>

<?php FSS_Helper::HelpText("support_user_view_header"); ?>

<?php if (!FSS_Settings::get('user_hide_print')): ?>
	
	<?php 
	$prints = Support_Print::getPrintList(true, $this->ticket_obj); 
	if (count($prints) > 0): ?>
		<div class="pull-right btn-group" style="z-index: 10;display: none;">
			<a class="btn btn-default dropdown-toggle" data-toggle="dropdown" href="#">
				<i class="icon-print"></i> <?php echo JText::_("Print"); ?> 
				<span class="caret"></span>
			</a>
			<ul class="dropdown-menu">
				<li>
					<a href='<?php echo FSSRoute::_('index.php?option=com_fss&view=ticket&what=print&tmpl=component&ticketid=' . $this->ticket['id']); ?>' target='_new' onclick="return doPrint(this);">
						<?php echo JText::_("Ticket"); ?> 
					</a>
				</li>
				<?php foreach ($prints as $name => $title): ?>
					<li>
						<a href='<?php echo FSSRoute::_('index.php?option=com_fss&view=ticket&what=print&tmpl=component&ticketid=' . $this->ticket['id']. "&print=" . $name); ?>' target='_new' onclick="return doPrint(this);">
							<?php echo JText::_($title); ?>
						</a>
					</li>	
				<?php endforeach; ?>
			</ul>
		</div>
	<?php else: ?>	
		<div class="pull-right">
			<a class="btn btn-default" href='<?php echo FSSRoute::_('index.php?option=com_fss&view=ticket&what=print&tmpl=component&ticketid=' . $this->ticket['id']); ?>' target='_new' onclick="return doPrint(this);">
				<i class="icon-print"></i>
				<?php echo JText::_("USER_PRINT"); ?> 
			</a>
		</div>
	<?php endif; ?>
<?php endif; ?>



<?php if (FSS_Settings::get("messages_at_top") == 1 || FSS_Settings::get("messages_at_top") == 3)
	include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_messages_cont.php'); ?>

<?php if (is_array($this->merged) && count($this->merged) > 0): ?>
	<div class="alert alert-info" style="margin-right: 120px;">
		<p><?php echo JText::_('TICKET_MERGED_NOTICE'); ?></p>
		<ul>
			<?php foreach ($this->merged as $mt): ?>
				<li><?php echo $mt->reference; ?> - <?php echo $mt->title; ?></li>	
			<?php endforeach; ?>
		</ul>
	</div>
<?php endif; ?>

<?php if ($this->ticket['merged'] > 0): ?>
	<div class="alert alert-error">
		<p><?php echo JText::_('TICKET_MERGED_NOTICE_INTO'); ?></p>
		<ul>
			<?php 
			$db = JFactory::getDBO();
			$qry = "SELECT * FROM #__fss_ticket_ticket WHERE id = " . $db->escape($this->ticket['merged']);
			$db->setQuery($qry);
			$merged = $db->loadObject();
			?>
				<li>	
					<?php if (JFactory::getUser()->id > 0): ?>
						<a href="<?php echo FSSRoute::_('index.php?option=com_fss&view=ticket&ticketid=' . $merged->id . "&Itemid=" . FSS_Input::getInt('Itemid'), false); ?>">
							<?php echo $merged->reference; ?> - <?php echo $merged->title; ?>
						</a>
					<?php else: ?>
						<?php echo $merged->reference; ?> - <?php echo $merged->title; ?>
					<?php endif; ?>
				</li>	
		</ul>
	</div>
<?php endif; ?>

<?php if (FSS_Settings::get('user_hide_all_details')): ?>
	
	<?php echo FSS_Helper::PageSubTitle(JText::sprintf("TICKET_TITLE_SHORT", $this->ticket['title'])); ?>
	
<?php else: ?>

	<?php echo FSS_Helper::PageSubTitle("TICKET_DETAILS"); ?>

	<?php if ($this->ticket['user_id'] == 0 && FSS_Settings::get('support_unreg_password_highlight') == 1): ?>
		<div class="alert alert-info">
		<h4><?php echo JText::_('YOUR_TICKET_ACCESS_DETAILS_ARE_'); ?></h4>
			<ul style="margin-top: 6px;">
			<?php if (FSS_Settings::get('support_unreg_type') == 0): ?>
				<li><?php echo JText::_('EMAIL'); ?> : <strong><?php echo $this->ticket['email']; ?></strong></li>
			<?php endif; ?>
			<?php if (in_array(FSS_Settings::get('support_unreg_type'), array(1,2)) ): ?>
				<li><?php echo JText::_('REFERENCE'); ?> : <strong><?php echo $this->ticket['reference']; ?></strong></li>
			<?php endif; ?>
			<?php if (in_array(FSS_Settings::get('support_unreg_type'), array(0,1)) ): ?>
				<li><?php echo JText::_('PASSWORD'); ?> : <strong><?php echo $this->ticket['password']; ?></strong></li>
			<?php endif; ?>
			</ul>
			<div><?php echo JText::_('UNREG_NOTICE'); ?></div>
		</div>
	<?php endif; ?>

	<?php FSS_Helper::HelpText("support_user_view_after_details"); ?>

	<?php 
	$mc = new FSS_Multi_Col();
	$mc->Init($table_cols, array('class' => $table_classes, 'rows_only' => 1, 'force_table' => 1));
	?>
		<?php if (!FSS_Settings::get('user_hide_title')): ?>
			<?php $mc->Item(); ?>
			<td><?php echo JText::_("TITLE"); ?></td>
			<td colspan="2"><?php echo $this->ticket['title']; ?></td>
		<?php endif; ?>

		<?php if (!FSS_Settings::get('user_hide_id')): ?>
			<?php $mc->Item(); ?>
			<td><?php echo JText::_("TICKET_ID"); ?></td>
			<?php if (FSS_Settings::get('support_unreg_password_highlight') == 2 && in_array(FSS_Settings::get('support_unreg_type'), array(1,2))): ?>
				<td><strong><?php echo $this->ticket['reference']; ?></strong></td>
				<td><div class="text text-info"><i class='icon-arrow-left-2'></i> <?php echo JText::_('TICKET_ACCESS_REFERENCE'); ?></div></td>
			<?php else: ?>
				<td colspan="2"><?php echo $this->ticket['reference']; ?></td>
			<?php endif; ?>
					
		<?php endif; ?>

		<?php if ($this->multiuser && !FSS_Settings::get('user_hide_user')) : ?>
			<?php $mc->Item(); ?>
			<td><?php echo JText::_("USER"); ?></td>
			<td colspan="2"><?php echo $this->user['name']; ?></td>
		<?php endif; ?>

		<?php if ($this->show_cc && !FSS_Settings::get('user_hide_cc')) : ?>
			<?php $mc->Item(); ?>
			<td><?php echo JText::_("CC_USERS"); ?></td>
			<td colspan="2">
				<?php if (JFactory::getUser()->id == $this->ticket['user_id']): ?>
					<a class="pull-right show_modal_iframe" data_modal_width="700" href="<?php echo FSSRoute::_('index.php?option=com_fss&view=ticket&tmpl=component&what=pickccuser&ticketid=' . $this->ticket['id']); ?>" id="fss_show_userlist">
						<i class="icon-new fssTip" title="<?php echo JText::_('CC_USER'); ?>"></i> 
					</a>
				<?php endif; ?>
		
				<div id="ccusers">
					<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_ccusers.php'); ?>
				</div>
			</td>
		<?php endif; ?>

		<?php if ($this->ticket['password']): ?>
			<?php if (in_array(FSS_Settings::get('support_unreg_type'), array(0,1))): ?>
				<?php $mc->Item(); ?>
				<td><?php echo JText::_("PASSWORD"); ?></td>
				<?php if (FSS_Settings::get('support_unreg_password_highlight') == 2): ?>
					<td><strong><?php echo $this->ticket['password']; ?></strong></td>
					<td><div class="text text-info"><i class='icon-arrow-left-2'></i> <?php echo JText::_('TICKET_ACCESS_PASSWORD'); ?></div></td>
				<?php else: ?>
					<td colspan="2"><?php echo $this->ticket['password']; ?></td>
				<?php endif; ?>
			<?php endif; ?>
			
			<?php $mc->Item(); ?>
			<td><?php echo JText::_("EMAIL"); ?></td>
			<?php if (FSS_Settings::get('support_unreg_password_highlight') == 2 && FSS_Settings::get('support_unreg_type') == 0): ?>
				<td><strong><?php echo $this->ticket['email']; ?></strong></td>
				<td><div class="text text-info"><i class='icon-arrow-left-2'></i> <?php echo JText::_('TICKET_ACCESS_EMAIL'); ?></div></td>
			<?php else: ?>
				<td colspan="2"><?php echo $this->ticket['email']; ?></td>
			<?php endif; ?>
		<?php endif; ?>

		<?php if ($this->ticket['product'] && !FSS_Settings::get('user_hide_product')): ?>
			<?php $mc->Item(); ?>
			<td><?php echo JText::_("PRODUCT"); ?></td>
			<td colspan="2"><?php echo FSS_Translate_Helper::TrF('title', $this->ticket['product'], $this->ticket['prtr']); ?></td>
		<?php endif; ?>

		<?php if ($this->ticket['dept'] && !FSS_Settings::get('user_hide_department')): ?>
			<?php $mc->Item(); ?>
			<td ><?php echo JText::_("DEPARTMENT"); ?></td>
			<td class="no-border" colspan="2"><?php echo FSS_Translate_Helper::TrF('title', $this->ticket['dept'], $this->ticket['dtr']); ?></td>
		<?php endif; ?>

		<?php if ($this->ticket['cat'] && !FSS_Settings::get('support_hide_category') && !FSS_Settings::get('user_hide_category')): ?>
			<?php $mc->Item(); ?>
			<td><?php echo JText::_("CATEGORY"); ?></td>
			<td colspan="2" class="no-border"><?php echo FSS_Translate_Helper::TrF('title', $this->ticket['cat'], $this->ticket['ctr']); ?></td>
		<?php endif; ?>

		<?php if (!FSS_Settings::get('user_hide_updated')): ?>
			<?php $mc->Item(); ?>
			<td><?php echo JText::_("LAST_UPDATE"); ?></td>
			<td colspan="2" class="no-border">
				<?php echo FSS_Helper::TicketTime($this->ticket['lastupdate'], FSS_DATETIME_MID); ?>
			</td>
	
			<?php if ($st->is_closed && strtotime($this->ticket['closed']) > 0) : ?>
				<?php $mc->Item(); ?>
				<td><?php echo JText::_("CLOSED"); ?></td>
				<td colspan="2" class="no-border">
					<?php echo FSS_Helper::TicketTime($this->ticket['closed'], FSS_DATETIME_MID); ?>
				</td>
			<?php endif; ?>
		<?php endif; ?>

		<?php if (!FSS_Settings::get('support_hide_handler') && !FSS_Settings::get('user_hide_handler')) : ?>
			<?php $mc->Item(); ?>
			<td><?php echo JText::_("HANDLER"); ?></td>
			<td colspan="2" class="no-border"><?php if ($this->ticket['assigned']) {echo $this->ticket['assigned'];} else {echo JText::_("UNASSIGNED");} ?></td>
		<?php endif; ?>

		<?php if (!FSS_Settings::get('user_hide_custom')): ?>
			<?php foreach ($this->fields as $field): ?>
				<?php 
					if ($field['grouping'] != "") continue;
					
					if ($field['reghide'] == 2 && $this->ticket['user_id'] > 0)
						continue;
		
					if ($field['reghide'] == 1 && $this->ticket['user_id'] < 1)
						continue;
					
					if ($field['permissions'] > 1 && $field['permissions'] != 5) 
						continue; 
				?>
				<?php $mc->Item(); ?>
				<td width='<?php echo FSS_Settings::get('ticket_label_width'); ?>'><?php echo FSSCF::FieldHeader($field, false, false); ?></td>
				<td colspan="2" class="no-border">
					<?php if ($field['permissions'] == 0 && !$st->is_closed && $this->CanEditField($field) && $this->ticket['merged'] == 0): ?>
						<a class='pull-right show_modal_iframe padding-left-small' href="<?php echo FSSRoute::_("&tmpl=component&what=editfield&ticketid=".$this->ticket['id']. "&editfield=" . $field['id'] );// FIX LINK ?>">
							<i class="icon-edit fssTip" title="<?php echo JText::_("EDIT_FIELD"); ?>"></i> 
						</a>
					<?php endif; ?>
					<?php echo FSSCF::FieldOutput($field, $this->fieldvalues, array('ticketid' => $this->ticket['id'], 'userid' => $this->ticket['user_id'], 'ticket' => $this->ticket_obj)); ?>
				</td>	
			<?php endforeach; ?>
		<?php endif; ?>
		
		<?php if (!FSS_Settings::get('user_hide_status')): ?>
			<?php $mc->Item(); ?>
			<td style="vertical-align: middle"><?php echo JText::_("STATUS"); ?></td>

			<td colspan="2" class="no-border">
				<?php if (!$st->is_closed && FSS_Settings::get('support_user_can_close') && $this->ticket['can_close'] && $this->ticket['merged'] == 0 && !$this->readonly) : ?>
					<form id='status_change' action="<?php echo FSSRoute::_( '' ); // FIX LINK ?>" method="post" style="margin: 0px;">
						<input type="hidden" name="what" value="statuschange">
						<input type="hidden" name="new_pri" value="<?php echo $this->ticket['pid']; ?>">

						<select id='new_status' name='new_status' class="input-medium" style="margin: 0px; color: <?php echo $this->ticket['scolor']; ?>" onchange="jQuery('#status_change').submit();">
							<?php foreach ($this->statuss as $status): ?>
								<?php if ($status['combine_with'] > 0) continue; ?>
								<?php if ( FSS_Settings::get('support_user_can_change_status') || ($status['is_closed'] > 0 && !$status['def_archive']) || $status['id'] == $this->ticket['sid']): ?>
									<option value='<?php echo $status['id']; ?>' style='color: <?php echo $status['color']; ?>' <?php if ($status['id'] == $this->ticket['sid']) echo "selected='selected'"; ?>><?php echo $status['userdisp'] ? $status['userdisp'] : $status['title']; ?></option>
								<?php endif; ?>
							<?php endforeach; ?>
						</select>
					
					</form>
				<?php else: ?>
					<span style='color: <?php echo $this->ticket['scolor']; ?>'><?php echo $this->ticket['status']; ?></span>
				<?php endif; ?>
			</td>
		<?php endif; ?>
		
		<?php if (!FSS_Settings::get('support_hide_priority') && !FSS_Settings::get('user_hide_priority')) : ?>
			<?php $mc->Item(); ?>
			<td style="vertical-align: middle"><?php echo JText::_("PRIORITY"); ?></td>
			<td colspan="2">
				<?php if (!$st->is_closed && $this->ticket['merged'] == 0 && !$this->readonly) : ?>
					<form id='pri_change' action="<?php echo FSSRoute::_( '' );// FIX LINK ?>" method="post" style="margin: 0px;">
						<input type="hidden" name="what" value="statuschange">
						<input type="hidden" name="new_status" value="<?php echo $this->ticket['sid']; ?>">

						<select id='new_pri' name='new_pri' class="input-medium" style="margin: 0px; color: <?php echo $this->ticket['pcolor']; ?>" onchange="jQuery('#pri_change').submit();">
							<?php FSS_Translate_Helper::Tr($this->pris); ?>
							<?php foreach ($this->pris as $pri): ?>
								<option value='<?php echo $pri['id']; ?>' style='color: <?php echo $pri['color']; ?>' <?php if ($pri['id'] == $this->ticket['pid']) echo "selected='selected'"; ?>><?php echo $pri['title']; ?></option>
							<?php endforeach; ?>
						</select>							
					</form>
				<?php else: ?>
					<span style='color:<?php echo $this->ticket['pcolor']; ?>'><?php echo FSS_Translate_Helper::TrF('title', $this->ticket['pri'], $this->ticket['ptr']); ?></span>
				<?php endif; ?>
			</td>
		<?php endif; ?>

	<?php $mc->End(); ?>

	<?php if (!FSS_Settings::get('user_hide_custom')) : ?>
		<?php $grouping = ""; $open = false; ?>

		<?php foreach ($this->fields as $field) : ?>

			<?php 
				if ($field['grouping'] == "")	
					continue;
			
				if ($field['reghide'] == 2 && $this->ticket['user_id'] > 0)
					continue;
		
				if ($field['reghide'] == 1 && $this->ticket['user_id'] < 1)
					continue;

				if ($field['permissions'] > 1 && $field['permissions'] != 5) 
					continue; 
			?>
		
			<?php if ($field['grouping'] != $grouping): ?>
				<?php if ($open) $mc->End();	?>
				<?php echo FSS_Helper::PageSubTitle($field['grouping']); ?>
				<?php
					$mc = new FSS_Multi_Col();
				$mc->Init($table_cols, array('class' => $table_classes, 'rows_only' => 1, 'force_table' => 1));
				?>
				<?php $open = true;	$grouping = $field['grouping']; ?>
			<?php endif; ?>

			<?php $mc->Item(); ?>
			<td width='<?php echo FSS_Settings::get('ticket_label_width'); ?>'><?php echo FSSCF::FieldHeader($field, false, false); ?></td>
			<td colspan="2">
				<?php if ($field['permissions'] == 0 && !$st->is_closed && $this->CanEditField($field) && !$this->readonly): ?>
					<a class='pull-right show_modal_iframe padding-left-small' href="<?php echo FSSRoute::_("&tmpl=component&what=editfield&ticketid=".$this->ticket['id']. "&editfield=" . $field['id'] );// FIX LINK ?>">
						<i class="icon-edit fssTip" title="<?php echo JText::_("EDIT_FIELD"); ?>"></i> 
					</a>
				<?php endif; ?>
				<?php echo FSSCF::FieldOutput($field,$this->fieldvalues, array('ticketid' => $this->ticket['id'], 'userid' => $this->ticket['user_id'], 'ticket' => $this->ticket_obj)); ?>
			</td>	
	
		<?php endforeach; ?>

		<?php if ($open) $mc->End(); ?>
	<?php endif; ?>
	
	<?php FSS_Helper::HelpText("support_user_view_end_details"); ?>

<?php endif; ?>

<?php if (FSS_Settings::get("messages_at_top") == 0 || FSS_Settings::get("messages_at_top") == 2)
	include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_messages_cont.php'); ?>


<?php if (count($this->attach) > 0) : ?>

	<?php echo FSS_Helper::PageSubTitle("ATTACHEMNTS"); ?>

	<?php FSS_Helper::HelpText("support_user_view_attach_header"); ?>

		<?php foreach ($this->attach as $attach) : ?>
			<?php if ($attach['inline']) continue; ?>

			<?php 
			$file_user_class = "warning";
			if (array_key_exists($attach['message_id'], FSS_Helper::$message_labels))
			$file_user_class = FSS_Helper::$message_labels[$attach['message_id']];
			?>
				
	
			<?php $image = in_array(strtolower(pathinfo($attach['filename'], PATHINFO_EXTENSION)), array('jpg','jpeg','png','gif')); ?>

			<div class="media padding-mini">
		
			<?php if ($image): ?>
				<div class="pull-left">
					<a class="show_modal_image" href="<?php echo JRoute::_('index.php?option=com_fss&view=ticket&fileid=' . $attach['id'] . "&ticketid=" . $this->ticket['id']); ?>">
						<img class="media-object" src="<?php echo JRoute::_('index.php?option=com_fss&view=ticket&what=attach_thumb&fileid=' . $attach['id'] . "&ticketid=" . $this->ticket['id']); ?>" width="48" height="48">
					</a>
				</div>
			<?php else: ?>
				<div class="pull-left large-dl-icon">
					<a href="<?php echo JRoute::_('index.php?option=com_fss&view=ticket&fileid=' . $attach['id'] . "&ticketid=" . $this->ticket['id']); ?>">
						<i class="icon-download"></i>
					</a>
				</div>
			<?php endif; ?>		
			
				<div class="media-body">

					<div class="pull-right" style="text-align: right;">
						<?php echo FSS_Helper::display_filesize($attach['size']); ?><br />
						<?php echo FSS_Helper::Date($attach['added'], FSS_DATETIME_MID); ?>
					</div>

					<h4 class="media-heading"><a href='<?php echo FSSRoute::_('index.php?option=com_fss&view=ticket&fileid=' . $attach['id'] . "&ticketid=" . $this->ticket['id']); ?>'><?php echo $attach['filename']; ?></a></h4>
					<?php echo JText::_('UPLOADED_BY'); ?> 
					<span class="label label-<?php echo $file_user_class; ?>">
						<?php echo $attach['name']; ?>
					</span>
			
				</div>
			</div>

		<?php endforeach; ?>

	<?php FSS_Helper::HelpText("support_user_view_attach_footer"); ?>

<?php endif; ?>
</div>
<script>

function doPrint(link)
{
	printWindow = window.open(jQuery(link).attr('href')); 
	return false;
}

var procform = false;
function FromDone()
{
	if (procform)
		return;
		
	procform = true;
	var result = jQuery('#form_results').contents();
	jQuery('.post_reply').css('display','inline');
	var html = result[0].body.innerHTML;
	jQuery('#ticket_messages').html(html);
	
	CreateEvents();
	
	procform = false;
}

function CreateEvents()
{
	jQuery('#addcomment').click(function(ev) {
		ev.stopPropagation();
		ev.preventDefault();
		jQuery('#messagereply').hide();
		jQuery('#messagepleasewait').show();
		
		jQuery('#inlinereply').submit();
		
		jQuery('#new_status').removeAttr('disabled');
		jQuery('#new_pri').removeAttr('disabled');

	});	
	
	jQuery('#replyclose').click(function(ev) {
		ev.stopPropagation();
		ev.preventDefault();
		
		jQuery('#should_close').val("1");
		jQuery('#messagereply').hide();
		jQuery('#messagepleasewait').show();
		
		jQuery('#inlinereply').submit();
		
		jQuery('#new_status').removeAttr('disabled');
		jQuery('#new_pri').removeAttr('disabled');
	});	
	
	jQuery('#replycancel').click(function(ev) {
		ev.stopPropagation();
		ev.preventDefault();
		jQuery('#messagereply').hide();
		jQuery('.post_reply').show();
		jQuery('#body').val("");

		jQuery('#new_status').removeAttr('disabled');
		jQuery('#new_pri').removeAttr('disabled');

	});
}

jQuery(document).ready(function () {
	jQuery('.post_reply').click(function(ev) {
		ev.stopPropagation();
		ev.preventDefault();
		jQuery('#messagereply').show();
		jQuery('.post_reply').hide();
		jQuery('#new_status').attr('disabled', 'disabled');
		jQuery('#new_pri').attr('disabled', 'disabled');
		
<?php if (FSS_Settings::Get('support_sceditor')): ?>		
		if (typeof sceditor_emoticons_root != 'undefined')
		{
			var rows = parseInt(jQuery("textarea.sceditor_hidden").attr('rows'));
			jQuery("textarea.sceditor_hidden").attr('rows', rows + 8);

			jQuery("textarea.sceditor_hidden").sceditor({
				plugins: "bbcode",
				style: sceditor_style_root + "jquery.sceditor.default.css",
				emoticonsRoot: sceditor_emoticons_root
			});
		
			jQuery("textarea.sceditor_hidden").removeClass('sceditor_hidden');
		}
<?php endif; ?>
	});

	jQuery('.ticketrefresh').click(function(ev) {
		ev.stopPropagation();
		ev.preventDefault();
		
		jQuery('#messagepleasewait').show();
		jQuery('#ticket_messages').html("");
		
		jQuery('#ticket_messages').load(jQuery(this).attr('href'), function () {
			jQuery('#messagepleasewait').hide();
		});
	});	

	CreateEvents();	
});

function AddCCUser(userid, readonly)
{
	fss_modal_hide();
	
	jQuery('#ccusers').html('<?php echo JText::_('PLEASE_WAIT'); ?>');
	
	var url = '<?php echo FSSRoute::_('index.php?option=com_fss&view=ticket&tmpl=component&what=addccuser&userid=XXUIDXX&ticketid=' . $this->ticket['id']); ?>';
	url = url.replace('XXUIDXX', userid);
	url += "&readonly=" + readonly
	
	jQuery.ajax({
		url: url,
		context: document.body,
		success: function(result){
			jQuery('#ccusers').html(result);
		}
	});
}

function removecc(userid)
{
	jQuery('#ccusers').html('<?php echo JText::_('PLEASE_WAIT'); ?>');
	
	var url = '<?php echo FSSRoute::_('index.php?option=com_fss&view=ticket&tmpl=component&what=removeccuser&userid=XXUIDXX&ticketid=' . $this->ticket['id']); ?>';
	url = url.replace('XXUIDXX',userid);

	jQuery.ajax({
		url: url,
		context: document.body,
		success: function(result){
			jQuery('#ccusers').html(result);
		}
	});
}

</script>

<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'_powered.php'; ?>
<?php echo FSS_Helper::PageStyleEnd(); ?>
