<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
		<fieldset class="adminform">
			<legend><?php echo JText::_("SUPPORT_SETTINGS"); ?></legend>
			<table class="table table-bordered table-condensed table-striped table-settings">
				<tr>
					<td align="left" class="key" style="width:250px;">
					
							<?php echo JText::_("USE_ADVANCED_PRODUCT_SELECTION"); ?>:
					
					</td>
					<td style="width:250px;">
						<input type='checkbox' name='support_advanced' value='1' <?php if ($this->settings['support_advanced'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_advanced'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
						<?php echo JText::_("ticket_prod_per_page"); ?>:
					</td>
					<td>
						<?php $this->PerPage('ticket_prod_per_page'); ?>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_ticket_prod_per_page'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
					
							<?php echo JText::_("support_product_manual_category_order"); ?>:
					
					</td>
					<td style="width:250px;">
						<input type='checkbox' name='support_product_manual_category_order' value='1' <?php if ($this->settings['support_product_manual_category_order'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_product_manual_category_order'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("support_advanced_department"); ?>:
					</td>
					<td style="width:250px;">
						<input type='checkbox' name='support_advanced_department' value='1' <?php if ($this->settings['support_advanced_department'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_advanced_department'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
					
							<?php echo JText::_("support_advanced_search"); ?>:
					
					</td>
					<td style="width:250px;">
						<input type='checkbox' name='support_advanced_search' value='1' <?php if ($this->settings['support_advanced_search'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_advanced_search'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
					
							<?php echo JText::_("OPEN_NEXT_ON_CLICK"); ?>:
					
					</td>
					<td style="width:250px;">
						<select name="support_next_prod_click">
							<option value="0" <?php if ($this->settings['support_next_prod_click'] == 0) echo " SELECTED"; ?> ><?php echo JText::_('JNo'); ?></option>
							<option value="1" <?php if ($this->settings['support_next_prod_click'] == 1) echo " SELECTED"; ?> ><?php echo JText::_('JYES'); ?></option>
							<option value="2" <?php if ($this->settings['support_next_prod_click'] == 2) echo " SELECTED"; ?> ><?php echo JText::_('YES__SHOWING_RADIO_BUTTONS'); ?></option>
						</select>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_next_prod_click'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
					
							<?php echo JText::_("ALLOW_TICKETS_BY_UNREGISTERED_USERS"); ?>:
					
					</td>
					<td>
						<input type='checkbox' name='support_allow_unreg' value='1' <?php if ($this->settings['support_allow_unreg'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_allow_unreg'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
					
							<?php echo JText::_("support_unreg_type"); ?>:
					
					</td>
					<td>
						<select name="support_unreg_type">
							<option value="0" <?php if ($this->settings['support_unreg_type'] == 0) echo " SELECTED"; ?> ><?php echo JText::_('SUPPORT_UNREG_TYPE_0'); ?></option>
							<option value="1" <?php if ($this->settings['support_unreg_type'] == 1) echo " SELECTED"; ?> ><?php echo JText::_('SUPPORT_UNREG_TYPE_1'); ?></option>
							<option value="2" <?php if ($this->settings['support_unreg_type'] == 2) echo " SELECTED"; ?> ><?php echo JText::_('SUPPORT_UNREG_TYPE_2'); ?></option>
						</select>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_unreg_typeg'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
					
							<?php echo JText::_("NO_LOGIN_ON_OPEN"); ?>:
					
					</td>
					<td>
						<input type='checkbox' name='support_no_logon' value='1' <?php if ($this->settings['support_no_logon'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_no_logon'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
					
							<?php echo JText::_("support_no_register"); ?>:
					
					</td>
					<td>
						<select name="support_no_register">
							<option value="1" <?php if ($this->settings['support_no_register'] == 1) echo " SELECTED"; ?> ><?php echo JText::_('DONT_SHOW'); ?></option>
							<option value="0" <?php if ($this->settings['support_no_register'] == 0) echo " SELECTED"; ?> ><?php echo JText::_('SHOW_ON_OPEN_TICKET'); ?></option>
							<option value="2" <?php if ($this->settings['support_no_register'] == 2) echo " SELECTED"; ?> ><?php echo JText::_('SHOW_ON_OPEN_AND_VIEW_TICKET'); ?></option>
						</select>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_no_register'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("ALLOW_DELETING_OF_TICKETS_BY_HANDLERS"); ?>:
					
					</td>
					<td>
						<input type='checkbox' name='support_delete' value='1' <?php if ($this->settings['support_delete'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_delete'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("SHOW_ADVANCED_SEARCH_BY_DEFAULT"); ?>:
					
					</td>
					<td>
						<input type='checkbox' name='support_advanced_default' value='1' <?php if ($this->settings['support_advanced_default'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_advanced_default'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("ENTIRE_ROW_TICKET"); ?>:
					
					</td>
					<td>
						<input type='checkbox' name='support_entire_row' value='1' <?php if ($this->settings['support_entire_row'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_entire_row'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
					
							<?php echo JText::_("CUSTOM_REGISTER_LINK"); ?>:
					
					</td>
					<td>
						<input type='text' name='support_custom_register' value='<?php echo $this->settings['support_custom_register']; ?>'>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_custom_register'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
					
							<?php echo JText::_("support_custom_lost_username"); ?>:
					
					</td>
					<td>
						<input type='text' name='support_custom_lost_username' value='<?php echo $this->settings['support_custom_lost_username']; ?>'>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_custom_lost_username'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
					
							<?php echo JText::_("support_custom_lost_password"); ?>:
					
					</td>
					<td>
						<input type='text' name='support_custom_lost_password' value='<?php echo $this->settings['support_custom_lost_password']; ?>'>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_custom_lost_password'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
						<?php echo JText::_("RESTRICT_TO_GROUPS_PRODUCTS"); ?>:
					</td>
					<td>
						<input type='checkbox' name='support_restrict_prod' value='1' <?php if ($this->settings['support_restrict_prod'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_restrict_prod'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
						<?php echo JText::_("USER_REPLY_WIDTH"); ?>:
					</td>
					<td>
						<input type='text' name='support_user_reply_width' value='<?php echo $this->settings['support_user_reply_width']; ?>'>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_user_reply_width'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
						<?php echo JText::_("USER_REPLY_HEIGHT"); ?>:
					</td>
					<td>
						<input type='text' name='support_user_reply_height' value='<?php echo $this->settings['support_user_reply_height']; ?>'>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_user_reply_height'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
						<?php echo JText::_("ADMIN_REPLY_WIDTH"); ?>:
					</td>
					<td>
						<input type='text' name='support_admin_reply_width' value='<?php echo $this->settings['support_admin_reply_width']; ?>'>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_admin_reply_width'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
						<?php echo JText::_("ADMIN_REPLY_HEIGHT"); ?>:
					</td>
					<td>
						<input type='text' name='support_admin_reply_height' value='<?php echo $this->settings['support_admin_reply_height']; ?>'>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_admin_reply_height'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
						<?php echo JText::_("SUBJECT_INPUT_SIZE"); ?>:
					</td>
					<td>
						<input type='text' name='support_subject_size' value='<?php echo $this->settings['support_subject_size']; ?>'>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_subject_size'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
						<?php echo JText::_("TICKET_LABEL_WIDTH"); ?>:
					</td>
					<td>
						<input type='text' name='ticket_label_width' value='<?php echo $this->settings['ticket_label_width']; ?>'>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_TICKET_LABEL_WIDTH'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
						<?php echo JText::_("ACTION_LINKS_AS_BUTTONS"); ?>:
					</td>
					<td>
						<input type='checkbox' name='support_actions_as_buttons' value='1' <?php if ($this->settings['support_actions_as_buttons'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_actions_as_buttons'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
						<?php echo JText::_("DONT_CHECK_EMAIL_ON_UNREG"); ?>:
					</td>
					<td>
						<input type='checkbox' name='support_dont_check_dupe' value='1' <?php if ($this->settings['support_dont_check_dupe'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_dont_check_dupe'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
						<?php echo JText::_("SETTING_SUPPORT_SCEDITOR"); ?>:
					</td>
					<td>
						<input type='checkbox' name='support_sceditor' value='1' <?php if ($this->settings['support_sceditor'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_SUPPORT_SCEDITOR'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
						<?php echo JText::_("SETTING_SUPPORT_ALTCAT"); ?>:
					</td>
					<td>
						<input type='checkbox' name='support_altcat' value='1' <?php if ($this->settings['support_altcat'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_SUPPORT_ALTCAT'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
						<?php echo JText::_("SETTING_TICKET_LINK_TARGET"); ?>:
					</td>
					<td>
						<input type='checkbox' name='ticket_link_target' value='1' <?php if ($this->settings['ticket_link_target'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_TICKET_LINK_TARGET'); ?></div>
					</td>
				</tr>
			</table>
		</fieldset>
	
		<style>
	
		.sub_selects input {
			float: left;
			margin-right: 6px !important;
		}
		</style>
	
		<fieldset class="adminform">
			<legend><?php echo JText::_("SUPPORT_SIMPLE_USERLIST"); ?></legend>
			<table class="table table-bordered table-condensed table-striped table-settings">
				<tr>
					<td align="left" class="key">
						<?php echo JText::_("SUPPORT_SIMPLE_USERLIST_TABS"); ?>:
					</td>
					<td>
						<input type='checkbox' name='support_simple_userlist_tabs' value='1' <?php if ($this->settings['support_simple_userlist_tabs'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_SUPPORT_SIMPLE_USERLIST_TABS'); ?></div>
					</td>
				</tr>			
				<tr>
					<td align="left" class="key">
						<?php echo JText::_("SUPPORT_SIMPLE_USERLIST_SEARCH"); ?>:
					</td>
					<td>
						<input type='checkbox' name='support_simple_userlist_search' value='1' <?php if ($this->settings['support_simple_userlist_search'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_SUPPORT_SIMPLE_USERLIST_SEARCH'); ?></div>
					</td>
				</tr>			
				<tr>
					<td align="left" class="key">
						<?php echo JText::_("HIDE_ON_TICKET_DETAILS"); ?>:
					</td>
					<td class="sub_selects">
						<input type='checkbox' name='user_hide_all_details' value='1' <?php if ($this->settings['user_hide_all_details'] == 1) { echo " checked='yes' "; } ?>>
						<label for="test1"><?php echo JText::_('ALL_TICKET_DETAILS'); ?></label>
					
						<input type='checkbox' name='user_hide_title' value='1' <?php if ($this->settings['user_hide_title'] == 1) { echo " checked='yes' "; } ?>>
						<label for="test2"><?php echo JText::_('TITLE'); ?></label>
					
						<input type='checkbox' name='user_hide_id' value='1' <?php if ($this->settings['user_hide_id'] == 1) { echo " checked='yes' "; } ?>>
						<label for="test2"><?php echo JText::_('TICKET_REFERENCE'); ?></label>
					
						<input type='checkbox' name='user_hide_user' value='1' <?php if ($this->settings['user_hide_user'] == 1) { echo " checked='yes' "; } ?>>
						<label for="test2"><?php echo JText::_('USER'); ?></label>
					
						<input type='checkbox' name='user_hide_cc' value='1' <?php if ($this->settings['user_hide_cc'] == 1) { echo " checked='yes' "; } ?>>
						<label for="test2"><?php echo JText::_('INCLUDED_USERS'); ?></label>
					
						<input type='checkbox' name='user_hide_product' value='1' <?php if ($this->settings['user_hide_product'] == 1) { echo " checked='yes' "; } ?>>
						<label for="test2"><?php echo JText::_('PRODUCT'); ?></label>
					
						<input type='checkbox' name='user_hide_department' value='1' <?php if ($this->settings['user_hide_department'] == 1) { echo " checked='yes' "; } ?>>
						<label for="test2"><?php echo JText::_('DEPARTMENT'); ?></label>
					
						<input type='checkbox' name='user_hide_category' value='1' <?php if ($this->settings['user_hide_category'] == 1) { echo " checked='yes' "; } ?>>
						<label for="test2"><?php echo JText::_('CATEGORY'); ?></label>
					
						<input type='checkbox' name='user_hide_updated' value='1' <?php if ($this->settings['user_hide_updated'] == 1) { echo " checked='yes' "; } ?>>
						<label for="test2"><?php echo JText::_('LAST_UPDATED'); ?></label>
					
						<input type='checkbox' name='user_hide_handler' value='1' <?php if ($this->settings['user_hide_handler'] == 1) { echo " checked='yes' "; } ?>>
						<label for="test2"><?php echo JText::_('HANDLER'); ?></label>
					
						<input type='checkbox' name='user_hide_status' value='1' <?php if ($this->settings['user_hide_status'] == 1) { echo " checked='yes' "; } ?>>
						<label for="test2"><?php echo JText::_('STATUS'); ?></label>
					
						<input type='checkbox' name='user_hide_priority' value='1' <?php if ($this->settings['user_hide_priority'] == 1) { echo " checked='yes' "; } ?>>
						<label for="test2"><?php echo JText::_('PRIORITY'); ?></label>
					
						<input type='checkbox' name='user_hide_custom' value='1' <?php if ($this->settings['user_hide_custom'] == 1) { echo " checked='yes' "; } ?>>
						<label for="test2"><?php echo JText::_('CUSTOM_FIELDS'); ?></label>
					
						<input type='checkbox' name='user_hide_print' value='1' <?php if ($this->settings['user_hide_print'] == 1) { echo " checked='yes' "; } ?>>
						<label for="test2"><?php echo JText::_('PRINT_BUTTON'); ?></label>
					
						<input type='checkbox' name='user_hide_key' value='1' <?php if ($this->settings['user_hide_key'] == 1) { echo " checked='yes' "; } ?>>
						<label for="test2"><?php echo JText::_('MESSAGE_KEY'); ?></label>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_HIDE_ON_TICKET_DETAILS'); ?></div>
					</td>
				</tr>			
			</table>
		</fieldset>
	
		<fieldset class="adminform">
			<legend><?php echo JText::_("SUPPORT_TICKET_OWNERSHIP_SETTINGS"); ?></legend>
			<table class="table table-bordered table-condensed table-striped table-settings">
				<tr>
					<td align="left" class="key" style="width:250px;">
					
							<?php echo JText::_("AUTO_ASSIGN_TICKETS_TO_HANDLER"); ?>:
					
					</td>
					<td style="width:250px;">
						<select name="support_autoassign">
							<option value="0" <?php if ($this->settings['support_autoassign'] == 0) echo " SELECTED"; ?> ><?php echo JText::_('DONT_ASSIGN_TICKETS'); ?></option>
							<option value="1" <?php if ($this->settings['support_autoassign'] == 1) echo " SELECTED"; ?> ><?php echo JText::_('AUTO_ASSIGN_ON_CREATE'); ?></option>
							<option value="2" <?php if ($this->settings['support_autoassign'] == 2) echo " SELECTED"; ?> ><?php echo JText::_('ASSIGN_TICKET_ON_HANDLER_OPEN'); ?></option>
							<option value="3" <?php if ($this->settings['support_autoassign'] == 3) echo " SELECTED"; ?> ><?php echo JText::_('ASSIGN_TICKET_ON_HANDLER_REPLY'); ?></option>
						</select>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_autoassign'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
							<?php echo JText::_("support_handler_fallback"); ?>:
					</td>
					<td>
						<input type='text' name='support_handler_fallback' value='<?php echo $this->settings['support_handler_fallback']; ?>'>
					</td>
						<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_handler_fallback'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
							<?php echo JText::_("TAKE_OWNERSHIP_ON_HANDLER_REPLY"); ?>:
					</td>
					<td>
						<input type='checkbox' name='support_assign_reply' value='1' <?php if ($this->settings['support_assign_reply'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_assign_reply'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">				
							<?php echo JText::_("ALLOW_HANDLER_TO_BE_CHOSEN"); ?>:	
					</td>
					<td>
						<select name="support_choose_handler">
							<option value="none" <?php if ($this->settings['support_choose_handler'] == "none") echo " SELECTED"; ?> ><?php echo JText::_('DISABLED'); ?></option>
							<option value="admin" <?php if ($this->settings['support_choose_handler'] == "admin") echo " SELECTED"; ?> ><?php echo JText::_('CREATE_FOR_USER'); ?></option>
							<option value="user" <?php if ($this->settings['support_choose_handler'] == "user") echo " SELECTED"; ?> ><?php echo JText::_('ALL_USERS'); ?></option>
							<option value="handlers" <?php if ($this->settings['support_choose_handler'] == "handlers") echo " SELECTED"; ?> ><?php echo JText::_('ADMINS_ONLY'); ?></option>
						</select>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_SUPPOER_CHOOSE_HANDLER'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">	
							<?php echo JText::_("ASSIGN_TO_HANDLER_WHEN_OPENEING_FOR_USER"); ?>:
					</td>
					<td>
						<input type='checkbox' name='support_assign_for_user' value='1' <?php if ($this->settings['support_assign_for_user'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('WHEN_A_HANDLER_OPENS_A_TICKET_FOR_A_USER__SHOULD_THEY_BE_ASSIGNED_AS_THE_HANDLER'); ?></div>
					</td>
				</tr>
			</table>
		</fieldset>

		<fieldset class="adminform">
			<legend><?php echo JText::_("SUPPORT_AUTOCLOSE_SETTINGS"); ?></legend>
			<table class="table table-bordered table-condensed table-striped table-settings">
				<tr>
					<td align="left" class="key" style="width:250px;">
					
							<?php echo JText::_("AUTOMATICALLY_CLOSE"); ?>:
					
					</td>
					<td style="width:250px;">
						<input type='checkbox' name='support_autoclose' value='1' <?php if ($this->settings['support_autoclose'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td rowspan="3">
						<div class='fss_help'>
						<?php echo JText::sprintf('CRON_AUTOCLOSE_MSG', JText::_('AUTOCLOSE_MIDDLE'), JURI::root() . 'index.php?option=com_fss&view=cron', JURI::root() . 'index.php?option=com_fss&view=cron'); ?><br />
						<a href="<?php echo FSSRoute::_('index.php?option=com_fss&view=cronlog'); ?>">
							<img style="float:none;margin:0px;" src='<?php echo JURI::base(); ?>/components/com_fss/assets/log.png'>
							<span style="position:relative;top:-2px;"><?php echo JText::_('VIEW_LOG'); ?></span>
						</a>
						</div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
							<?php echo JText::_("AUTOCLOSE_DURATION"); ?>:
					</td>
					<td>
						<input type='text' name='support_autoclose_duration' value='<?php echo $this->settings['support_autoclose_duration']; ?>'>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("AUTOCLOSE_AUDITLOG"); ?>:
					</td>
					<td>
						<input type='checkbox' name='support_autoclose_audit' value='1' <?php if ($this->settings['support_autoclose_audit'] == 1) { echo " checked='yes' "; } ?>>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("AUTOCLOSE_EMAIL_USER"); ?>:
					</td>
					<td>
						<input type='checkbox' name='support_autoclose_email' value='1' <?php if ($this->settings['support_autoclose_email'] == 1) { echo " checked='yes' "; } ?>>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
							<?php echo JText::_("KEEP_LOG_FOR"); ?>:
					</td>
					<td>
						<input type='text' name='support_cronlog_keep' value='<?php echo $this->settings['support_cronlog_keep']; ?>'>
					</td>
				</tr>

			</table>
		</fieldset>
	
		<fieldset class="adminform">
			<legend><?php echo JText::_("SUPPORT_USER_SETTINGS"); ?></legend>
			<table class="table table-bordered table-condensed table-striped table-settings">
				<tr>
					<td align="left" class="key" style="width:250px;">
					
							<?php echo JText::_("USER_CAN_CHANGE_CLOSE_OPEN_TICKETS"); ?>:
					
					</td>
					<td style="width:250px;">
						<input type='checkbox' name='support_user_can_close' value='1' <?php if ($this->settings['support_user_can_close'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_user_can_close'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
							<?php echo JText::_("USER_CAN_REOPEN_CLOSED_TICKETS"); ?>:
					</td>
					<td>
						<input type='checkbox' name='support_user_can_reopen' value='1' <?php if ($this->settings['support_user_can_reopen'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_user_can_reopen'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
							<?php echo JText::_("support_user_can_change_status"); ?>:
					</td>
					<td>
						<input type='checkbox' name='support_user_can_change_status' value='1' <?php if ($this->settings['support_user_can_change_status'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_user_can_change_status'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
					
							<?php echo JText::_("support_user_show_close_reply"); ?>:
					
					</td>
					<td>
						<input type='checkbox' name='support_user_show_close_reply' value='1' <?php if ($this->settings['support_user_show_close_reply'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_user_show_close_reply'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
					
							<?php echo JText::_("SUPPORT_ONLY_ADMIN_OPEN"); ?>:
					
					</td>
					<td>
						<input type='checkbox' name='support_only_admin_open' value='1' <?php if ($this->settings['support_only_admin_open'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_only_admin_open'); ?></div>
					</td>
				</tr>
			
				<tr>
					<td align="left" class="key">				
							<?php echo JText::_("HIGHLIGHT_PASSWORD_INFO"); ?>:	
					</td>
					<td>
						<select name="support_unreg_password_highlight">
							<option value="0" <?php if ($this->settings['support_unreg_password_highlight'] == "0") echo " SELECTED"; ?> ><?php echo JText::_('JNO'); ?></option>
							<option value="1" <?php if ($this->settings['support_unreg_password_highlight'] == "1") echo " SELECTED"; ?> ><?php echo JText::_('ALERT_BOX'); ?></option>
							<option value="2" <?php if ($this->settings['support_unreg_password_highlight'] == "2") echo " SELECTED"; ?> ><?php echo JText::_('BY_FIELD'); ?></option>
						</select>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('FOR_UNREGISTERED_TICKETS__HIGHLIGHT_THE_PASSWORD_INFORMATION_NEEDED_TO_ACCESS_THE_TICKET_'); ?></div>
					</td>
				</tr>
			
			</table>
		</fieldset>
	
		<fieldset class="adminform">
			<legend><?php echo JText::_("SUPPORT_EMAIL_SETTINGS"); ?></legend>
			<table class="table table-bordered table-condensed table-striped table-settings">
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("EMAIL_ADDRESS_TO_EMAIL_FOR_UNASSIGNED_TICKETS_LEAVE_BLANK_FOR_NO_EMAIL"); ?>:
					
					</td>
					<td style="width:250px;">
						<input name='support_email_unassigned' type="text" size="40" value='<?php echo $this->settings['support_email_unassigned']; ?>' >
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_email_unassigned'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("CC_ALL_TICKET_HANLDER_EMAILS_ADDRESS"); ?>:
					
					</td>
					<td style="width:250px;">
						<input name='support_email_admincc' type="text" size="40" value='<?php echo $this->settings['support_email_admincc']; ?>' >
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_email_admincc'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("EMAIL_FROM_ADDRESS_LEAVE_BLANK_TO_USE_DEFAULT_JOOMLA_ONE"); ?>:
					
					</td>
					<td>
						<input name='support_email_from_address' type="text" size="40" value='<?php echo $this->settings['support_email_from_address']; ?>' >
						</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_email_from_address'); ?></div>
				</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("EMAIL_FROM_NAME_LEAVE_BLANK_TO_USE_DEFAULT_JOOMLA_ONE"); ?>:
					
					</td>
					<td>
						<input name='support_email_from_name' type="text" size="40" value='<?php echo $this->settings['support_email_from_name']; ?>' >
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_email_from_name'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("OVERRIDE_SITE_NAME_IN_EMAIL_LEAVE_BLANK_TO_USE_DEFAULT_JOOMLA_ONE"); ?>:
					
					</td>
					<td>
						<input name='support_email_site_name' type="text" size="40" value='<?php echo $this->settings['support_email_site_name']; ?>' >
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_email_site_name'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
					
							<?php echo JText::_("EMAIL_USER_ON_TICKET_CREATE"); ?>:
					
					</td>
					<td>
						<input type='checkbox' name='support_email_on_create' value='1' <?php if ($this->settings['support_email_on_create'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_email_on_create'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
					
							<?php echo JText::_("EMAIL_HANDLER_ON_CREATE_IF_ONE_IS_ASSIGNED"); ?>:
					
					</td>
					<td>
						<input type='checkbox' name='support_email_handler_on_create' value='1' <?php if ($this->settings['support_email_handler_on_create'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_email_handler_on_create'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
							<?php echo JText::_("EMAIL_HANDLER_ON_PENDING_IF_ONE_IS_ASSIGNED"); ?>:
					</td>
					<td>
						<input type='checkbox' name='support_email_handler_on_pending' value='1' <?php if ($this->settings['support_email_handler_on_pending'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_email_handler_on_create'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
					
							<?php echo JText::_("EMAIL_USER_ON_HANDLER_REPLY"); ?>:
					
					</td>
					<td>
						<input type='checkbox' name='support_email_on_reply' value='1' <?php if ($this->settings['support_email_on_reply'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_email_on_reply'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
					
							<?php echo JText::_("EMAIL_HANDER_ON_USER_REPLY"); ?>:
					
					</td>
					<td>
						<input type='checkbox' name='support_email_handler_on_reply' value='1' <?php if ($this->settings['support_email_handler_on_reply'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_email_handler_on_reply'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
					
							<?php echo JText::_("EMAIL_NEW_HANDLER_ON_FORWARD"); ?>:
					
					</td>
					<td>
						<input type='checkbox' name='support_email_handler_on_forward' value='1' <?php if ($this->settings['support_email_handler_on_forward'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_email_handler_on_forward'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
					
							<?php echo JText::_("EMAIL_USER_ON_CLOSE"); ?>:
					
					</td>
					<td>
						<input type='checkbox' name='support_email_on_close' value='1' <?php if ($this->settings['support_email_on_close'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_email_on_close'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="padding-left: 8px;">					
							<img src='<?php echo JURI::root(); ?>administrator/components/com_fss/assets/images/arrow_indent.gif'>
							<?php echo JText::_("support_email_on_close_no_dropdown"); ?>:					
					</td>
					<td>
						<input type='checkbox' name='support_email_on_close_no_dropdown' value='1' <?php if ($this->settings['support_email_on_close_no_dropdown'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_email_on_close_no_dropdown'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
					
							<?php echo JText::_("support_email_all_admins"); ?>:
					
					</td>
					<td>
						<input type='checkbox' name='support_email_all_admins' value='1' <?php if ($this->settings['support_email_all_admins'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_email_all_admins'); ?></div>
					</td>
				</tr>			
				<tr>
					<td align="left" class="key" style="padding-left: 8px;">
					
							<img src='<?php echo JURI::root(); ?>administrator/components/com_fss/assets/images/arrow_indent.gif'>
							<?php echo JText::_("support_email_all_admins_only_unassigned"); ?>:
					
					</td>
					<td>
						<input type='checkbox' name='support_email_all_admins_only_unassigned' value='1' <?php if ($this->settings['support_email_all_admins_only_unassigned'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_email_all_admins_only_unassigned'); ?></div>
					</td>
				</tr>			<tr>
					<td align="left" class="key" style="padding-left: 8px;">
					
							<img src='<?php echo JURI::root(); ?>administrator/components/com_fss/assets/images/arrow_indent.gif'>
							<?php echo JText::_("support_email_all_admins_ignore_auto"); ?>:
					
					</td>
					<td>
						<input type='checkbox' name='support_email_all_admins_ignore_auto' value='1' <?php if ($this->settings['support_email_all_admins_ignore_auto'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_email_all_admins_ignore_auto'); ?></div>
					</td>
				</tr>			<tr>
					<td align="left" class="key" style="padding-left: 8px;">
					
							<img src='<?php echo JURI::root(); ?>administrator/components/com_fss/assets/images/arrow_indent.gif'>
							<?php echo JText::_("support_email_all_admins_can_view"); ?>:
					
					</td>
					<td>
						<input type='checkbox' name='support_email_all_admins_can_view' value='1' <?php if ($this->settings['support_email_all_admins_can_view'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_email_all_admins_can_view'); ?></div>
					</td>
				</tr>	
							<tr>
					<td align="left" class="key">
					
							<?php echo JText::_("support_email_file_user"); ?>:
					
					</td>
					<td>
						<input type='checkbox' name='support_email_file_user' value='1' <?php if ($this->settings['support_email_file_user'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_email_file_user'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">	
							<?php echo JText::_("support_email_file_handler"); ?>:
					</td>
					<td>
						<input type='checkbox' name='support_email_file_handler' value='1' <?php if ($this->settings['support_email_file_handler'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_email_file_handler'); ?></div>
					</td>
				</tr>	
				<tr>
					<td align="left" class="key">	
							<?php echo JText::_("support_email_bcc_handler"); ?>:
					</td>
					<td>
						<input type='checkbox' name='support_email_bcc_handler' value='1' <?php if ($this->settings['support_email_bcc_handler'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_email_bcc_handler'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">	
							<?php echo JText::_("support_email_send_empty_handler"); ?>:
					</td>
					<td>
						<input type='checkbox' name='support_email_send_empty_handler' value='1' <?php if ($this->settings['support_email_send_empty_handler'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_email_send_empty_handler'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">	
							<?php echo JText::_("support_email_include_autologin"); ?>:
					</td>
					<td>
						<input type='checkbox' name='support_email_include_autologin' value='1' <?php if ($this->settings['support_email_include_autologin'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_email_include_autologin'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style='font-weight: bold;'>
						<?php echo JText::_("support_email_link"); ?>:
					</td>
					<td>
						<?php echo JText::_("support_email_link_below"); ?></td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_email_link'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">	
							<img src='<?php echo JURI::root(); ?>administrator/components/com_fss/assets/images/arrow_indent.gif'>
							<?php echo JText::_("support_email_link_unreg"); ?>:
					</td>
					<td>
						<input name='support_email_link_unreg' type="text" size="40" value='<?php echo $this->settings['support_email_link_unreg']; ?>' >
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_email_link_unreg'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">	
							<img src='<?php echo JURI::root(); ?>administrator/components/com_fss/assets/images/arrow_indent.gif'>
							<?php echo JText::_("support_email_link_reg"); ?>:
					</td>
					<td>
						<input name='support_email_link_reg' type="text" size="40" value='<?php echo $this->settings['support_email_link_reg']; ?>' >
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_email_link_reg'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">	
							<img src='<?php echo JURI::root(); ?>administrator/components/com_fss/assets/images/arrow_indent.gif'>
							<?php echo JText::_("support_email_link_admin"); ?>:
					</td>
					<td>
						<input name='support_email_link_admin' type="text" size="40" value='<?php echo $this->settings['support_email_link_admin']; ?>' >
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_email_link_admin'); ?></div>
					</td>
				</tr>			
				<tr>
					<td align="left" class="key">	
							<img src='<?php echo JURI::root(); ?>administrator/components/com_fss/assets/images/arrow_indent.gif'>
							<?php echo JText::_("support_email_link_pending"); ?>:
					</td>
					<td>
						<input name='support_email_link_pending' type="text" size="40" value='<?php echo $this->settings['support_email_link_pending']; ?>' >
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_email_link_pending'); ?></div>
					</td>
				</tr>	
				<tr>
					<td align="left" class="key">		
						<?php echo JText::_("support_email_no_domain"); ?>:
					</td>
					<td>
						<input type='checkbox' name='support_email_no_domain' value='1' <?php if ($this->settings['support_email_no_domain'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_SUPPORT_EMAIL_NO_DOMAIN'); ?></div>
					</td>
				</tr>	
			</table>
		</fieldset>

		<fieldset class="adminform">
			<legend><?php echo JText::_("SUPPORT_GENERAL_SETTINGS"); ?></legend>
			<table class="table table-bordered table-condensed table-striped table-settings">
				<tr>
					<td align="left" class="key" style="width:250px;">
					
							<?php echo JText::_("HIDE_PRIORITY"); ?>:
					
					</td>
					<td style="width:250px;">
						<select name="support_hide_priority">
							<option value="0" <?php if ($this->settings['support_hide_priority'] == 0) echo " SELECTED"; ?> ><?php echo JText::_('PRI_SHOWN'); ?></option>
							<option value="1" <?php if ($this->settings['support_hide_priority'] == 1) echo " SELECTED"; ?> ><?php echo JText::_('PRI_HIDE'); ?></option>
							<option value="2" <?php if ($this->settings['support_hide_priority'] == 2) echo " SELECTED"; ?> ><?php echo JText::_('PRI_ONLY_FOR_ADMINS'); ?></option>
						</select>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_hide_priority'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
					
							<?php echo JText::_("support_default_priority"); ?>:
					
					</td>
					<td style="width:250px;">
						<select name="support_default_priority">
							<option value="" <?php if ($this->settings['support_default_priority'] == 0) echo " SELECTED"; ?> ><?php echo JText::_('support_default_priority_default'); ?></option>
							<?php
							$db = JFactory::getDBO();
							$qry = "SELECT * FROM #__fss_ticket_pri ORDER BY ordering";
							$db->setQuery($qry);
							$pris = $db->loadObjectList();
							FSS_Translate_Helper::Tr($pris);
							foreach ($pris as $pri): ?>
								<option value="<?php echo $pri->id; ?>" <?php if ($this->settings['support_default_priority'] == $pri->id) echo " SELECTED"; ?> ><?php echo $pri->title; ?></option>
							<?php endforeach; ?>
						</select>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_default_priority'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
					
							<?php echo JText::_("HIDE_HANDLER"); ?>:
					
					</td>
					<td>
						<select name="support_hide_handler">
							<option value="0" <?php if ($this->settings['support_hide_handler'] == 0) echo " SELECTED"; ?> ><?php echo JText::_('HANDLER_SHOWN'); ?></option>
							<option value="1" <?php if ($this->settings['support_hide_handler'] == 1) echo " SELECTED"; ?> ><?php echo JText::_('HANDLER_HIDE'); ?></option>
							<option value="2" <?php if ($this->settings['support_hide_handler'] == 2) echo " SELECTED"; ?> ><?php echo JText::_('HANDLER_ONLY_FOR_ADMINS'); ?></option>
						</select>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_hide_handler'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
					
							<?php echo JText::_("HIDE_CATEGORY"); ?>:
					
					</td>
					<td>
						<select name="support_hide_category">
							<option value="0" <?php if ($this->settings['support_hide_category'] == 0) echo " SELECTED"; ?> ><?php echo JText::_('HANDLER_SHOWN'); ?></option>
							<option value="1" <?php if ($this->settings['support_hide_category'] == 1) echo " SELECTED"; ?> ><?php echo JText::_('HANDLER_HIDE'); ?></option>
							<option value="2" <?php if ($this->settings['support_hide_category'] == 2) echo " SELECTED"; ?> ><?php echo JText::_('HANDLER_ONLY_FOR_ADMINS'); ?></option>
						</select>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_hide_category'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
					
							<?php echo JText::_("HIDE_USERS_OTHER_TICKET_SECTION"); ?>:
					
					</td>
					<td>
						<input type='checkbox' name='support_hide_users_tickets' value='1' <?php if ($this->settings['support_hide_users_tickets'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_hide_users_tickets'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
					
							<?php echo JText::_("HIDE_TICKET_TAGS"); ?>:
					
					</td>
					<td>
						<input type='checkbox' name='support_hide_tags' value='1' <?php if ($this->settings['support_hide_tags'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_hide_tags'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
					
							<?php echo JText::_("SHOW_MESSGAE_COUNTS"); ?>:
					
					</td>
					<td>
						<input type='checkbox' name='support_show_msg_counts' value='1' <?php if ($this->settings['support_show_msg_counts'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_show_msg_counts'); ?></div>
					</td>
				</tr>

				<tr>
					<td align="left" class="key">
						<?php echo JText::_("support_captcha_type"); ?>:
					</td>
					<td style="width:250px;">
						<select name="support_captcha_type">
							<option value="none" <?php if ($this->settings['support_captcha_type'] == "none") echo " SELECTED"; ?> ><?php echo JText::_('FNONE'); ?></option>
							<option value="fsj" <?php if ($this->settings['support_captcha_type'] == "fsj") echo " SELECTED"; ?> ><?php echo JText::_('BUILT_IN'); ?> - <?php echo JText::_('All Users'); ?></option>
							<option value="ur-fsj" <?php if ($this->settings['support_captcha_type'] == "ur-fsj") echo " SELECTED"; ?> ><?php echo JText::_('BUILT_IN'); ?> - <?php echo JText::_('Unregistered Only'); ?></option>
							<option value="recaptcha" <?php if ($this->settings['support_captcha_type'] == "recaptcha") echo " SELECTED"; ?> ><?php echo JText::_('RECAPTCHA'); ?> - <?php echo JText::_('All Users'); ?></option>
							<option value="ur-recaptcha" <?php if ($this->settings['support_captcha_type'] == "ur-recaptcha") echo " SELECTED"; ?> ><?php echo JText::_('RECAPTCHA'); ?> - <?php echo JText::_('Unregistered Only'); ?></option>
						</select>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_captcha_type'); ?></div>
					</td>
				</tr>

				<tr>
					<td align="left" class="key">
					
							<?php echo JText::_("NO_COLS_IN_TICKET_INFO"); ?>:
					
					</td>
					<td>
						<input name='support_info_cols' type="text" value='<?php echo $this->settings['support_info_cols']; ?>' />
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_info_cols'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
					
							<?php echo JText::_("NO_COLS_IN_TICKET_INFO_USER"); ?>:
					
					</td>
					<td>
						<input name='support_info_cols_user' type="text" value='<?php echo $this->settings['support_info_cols_user']; ?>' />
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_info_cols_user'); ?></div>
					</td>
				</tr>
			
				<tr>
					<td align="left" class="key">
					
							<?php echo JText::_("TICKET_LOCK_TIMEOUT"); ?>:
					
					</td>
					<td>
						<input name='support_lock_time' type="text" value='<?php echo $this->settings['support_lock_time']; ?>' />
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_lock_time'); ?></div>
					</td>
				</tr>
				<tr>
					<td valign="top" align="left" class="key">
					
							<?php echo JText::_("TICKET_REFERENCE"); ?>:
					
					</td>
					<td valign="top" width="200">
						<p><input name='support_reference' type="text" id='support_reference' value='<?php echo $this->settings['support_reference']; ?>' /></p>
						<p><button class="btn" onclick="testreference();return false;"><?php echo JText::_("TEST_REFERENCE_NO"); ?></button></p>
						<p><div id="testref"></div></p>
					</td>
					<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_support_reference'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">				
							<?php echo JText::_("HIDE_MESSAGE_SUBJECT"); ?>:
					</td>
					<td>
						<select name="support_subject_message_hide">
							<option value="none" <?php if ($this->settings['support_subject_message_hide'] != "subject" && $this->settings['support_subject_message_hide'] != "message") echo " SELECTED"; ?> ><?php echo JText::_('SHOW_BOTH'); ?></option>
							<option value="subject" <?php if ($this->settings['support_subject_message_hide'] == "subject") echo " SELECTED"; ?> ><?php echo JText::_('HIDE_SUBJECT'); ?></option>
							<option value="message" <?php if ($this->settings['support_subject_message_hide'] == "message") echo " SELECTED"; ?> ><?php echo JText::_('HIDE_MESSAGE'); ?></option>
							<option value="both" <?php if ($this->settings['support_subject_message_hide'] == "both") echo " SELECTED"; ?> ><?php echo JText::_('HIDE_BOTH'); ?></option>
						</select>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_SUPPORT_SUBJECT_MESSAGE_HIDE'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
							<?php echo JText::_("support_subject_format"); ?>:
					</td>
					<td>
						<input name='support_subject_format' type="text" value='<?php echo $this->settings['support_subject_format']; ?>' />
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_subject_format'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">	
							<?php echo JText::_("SUPPORT_SUBJECT_AT_TOP"); ?>:
					</td>
					<td>
						<input type='checkbox' name='support_subject_at_top' value='1' <?php if ($this->settings['support_subject_at_top'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_subject_at_top'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">	
							<?php echo JText::_("support_sel_prod_dept"); ?>:
					</td>
					<td>
						<input type='checkbox' name='support_sel_prod_dept' value='1' <?php if ($this->settings['support_sel_prod_dept'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_sel_prod_dept'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
						<?php echo JText::_("ticket_per_page"); ?>:
					</td>
					<td>
						<?php $this->PerPage('ticket_per_page'); ?>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_ticket_per_page'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
					
							<?php echo JText::_("MESSAGES_AT_TOP"); ?>:
					
					</td>
					<td>
						<select name="messages_at_top">
							<option value="0" <?php if ($this->settings['messages_at_top'] == 0) echo " SELECTED"; ?> ><?php echo JText::_('JNO'); ?></option>
							<option value="1" <?php if ($this->settings['messages_at_top'] == 1) echo " SELECTED"; ?> ><?php echo JText::_('MESSAGES_AT_TOP_USERS'); ?></option>
							<option value="2" <?php if ($this->settings['messages_at_top'] == 2) echo " SELECTED"; ?> ><?php echo JText::_('MESSAGES_AT_TOP_ADMINS'); ?></option>
							<option value="3" <?php if ($this->settings['messages_at_top'] == 3) echo " SELECTED"; ?> ><?php echo JText::_('JYES'); ?></option>
						</select>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_messages_at_top'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">				
							<?php echo JText::_("TIME_TRACKING"); ?>:
					</td>
					<td>
						<select name="time_tracking">
							<option value="" <?php if ($this->settings['time_tracking'] == "") echo " SELECTED"; ?> ><?php echo JText::_('JNO'); ?></option>
							<option value="manual" <?php if ($this->settings['time_tracking'] == "manual") echo " SELECTED"; ?> ><?php echo JText::_('TIME_TRACKING_MANUAL'); ?></option>
							<option value="auto" <?php if ($this->settings['time_tracking'] == "auto") echo " SELECTED"; ?> ><?php echo JText::_('TIME_TRACKING_AUTOMATIC'); ?></option>
						</select>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_time_tracking'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">	
							<?php echo JText::_("time_tracking_require_note"); ?>:
					</td>
					<td>
						<input type='checkbox' name='time_tracking_require_note' value='1' <?php if ($this->settings['time_tracking_require_note'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_time_tracking_require_note'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">	
							<?php echo JText::_("time_tracking_type"); ?>:
					</td>
					<td>
						<select name="time_tracking_type">
							<option value="" <?php if ($this->settings['time_tracking_type'] == "") echo " SELECTED"; ?> ><?php echo JText::_('TIME_TRACKING_TYPE_HM'); ?></option>
							<option value="se" <?php if ($this->settings['time_tracking_type'] == "se") echo " SELECTED"; ?> ><?php echo JText::_('TIME_TRACKING_TYPE_SE'); ?></option>
							<option value="tm" <?php if ($this->settings['time_tracking_type'] == "tm") echo " SELECTED"; ?> ><?php echo JText::_('TIME_TRACKING_TYPE_TM'); ?></option>
						</select>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_time_tracking_type'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">	
							<?php echo JText::_("absolute_last_open"); ?>:
					</td>
					<td>
						<select name="absolute_last_open">
							<option value="0" <?php if ($this->settings['absolute_last_open'] < 1 ) echo " SELECTED"; ?> >"XX days ago" with tooltip</option>
							<option value="1" <?php if ($this->settings['absolute_last_open'] == 1) echo " SELECTED"; ?> >"2015-03-05" with tooltip</option>
							<option value="2" <?php if ($this->settings['absolute_last_open'] == 2) echo " SELECTED"; ?> >"XX days ago"</option>
							<option value="3" <?php if ($this->settings['absolute_last_open'] == 3) echo " SELECTED"; ?> >"2015-03-05"</option>
						</select>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_absolute_last_open'); ?></div>
					</td>
				</tr>
			</table>
		</fieldset>

		<fieldset class="adminform">
			<legend><?php echo JText::_("SUPPORT_SEARCH_SETTINGS"); ?></legend>
			<table class="table table-bordered table-condensed table-striped table-settings">
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("SS_BASIC_NAME"); ?>:
					</td>
					<td style="width:250px;">
						<input type='checkbox' name='support_basic_name' value='1' <?php if ($this->settings['support_basic_name'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_basic_name'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("SS_BASIC_USERNAME"); ?>:
					</td>
					<td>
						<input type='checkbox' name='support_basic_username' value='1' <?php if ($this->settings['support_basic_username'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_basic_username'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("SS_BASIC_EMAIL"); ?>:
					</td>
					<td>
						<input type='checkbox' name='support_basic_email' value='1' <?php if ($this->settings['support_basic_email'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_basic_email'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("SS_BASIC_MESSAGES"); ?>:
					</td>
					<td>
						<input type='checkbox' name='support_basic_messages' value='1' <?php if ($this->settings['support_basic_messages'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_basic_messages'); ?></div>
					</td>
				</tr>
			</table>
		</fieldset>

		<fieldset class="adminform">
			<legend><?php echo JText::_("SUPPORT_ADMIN_SETTINGS"); ?></legend>
			<table class="table table-bordered table-condensed table-striped table-settings">
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("SHOW_ALL_CLOSED_TAB"); ?>:
					</td>
					<td style="width:250px;">
						<input type='checkbox' name='support_tabs_allclosed' value='1' <?php if ($this->settings['support_tabs_allclosed'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_SHOW_ALL_CLOSED_TAB'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("SHOW_ALL_OPEN_TAB"); ?>:
					</td>
					<td>
						<input type='checkbox' name='support_tabs_allopen' value='1' <?php if ($this->settings['support_tabs_allopen'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_SHOW_ALL_OPEN_TAB'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("SHOW_ALL_TICKETS_TAB"); ?>:
					</td>
					<td>
						<input type='checkbox' name='support_tabs_all' value='1' <?php if ($this->settings['support_tabs_all'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_SHOW_ALL_TICKETS_TAB'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
							<?php echo JText::_("support_admin_refresh"); ?>:
					</td>
					<td>
						<input type='text' name='support_admin_refresh' value='<?php echo $this->settings['support_admin_refresh']; ?>'>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_SUPPORT_ADMIN_REFRESH'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("support_hide_super_users"); ?>:
					</td>
					<td>
						<input type='checkbox' name='support_hide_super_users' value='1' <?php if ($this->settings['support_hide_super_users'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_hide_super_users'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("support_no_admin_for_user_open"); ?>:
					</td>
					<td>
						<input type='checkbox' name='support_no_admin_for_user_open' value='1' <?php if ($this->settings['support_no_admin_for_user_open'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_no_admin_for_user_open'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
						<?php echo JText::_("support_profile_itemid"); ?>:
					</td>
					<td>
						<input type='text' name='support_profile_itemid' value='<?php echo $this->settings['support_profile_itemid']; ?>'>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_profile_itemid'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("allow_edit_no_audit"); ?>:
					</td>
					<td>
						<input type='checkbox' name='allow_edit_no_audit' value='1' <?php if ($this->settings['allow_edit_no_audit'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_allow_edit_no_audit'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
							<?php echo JText::_("forward_product_handler"); ?>:
					</td>
					<td>
						<select name="forward_product_handler">
							<option value="unchanged" <?php if ($this->settings['forward_product_handler'] == "unchanged") echo " SELECTED"; ?> ><?php echo JText::_('Unchanged'); ?></option>
							<option value="auto" <?php if ($this->settings['forward_product_handler'] == "auto") echo " SELECTED"; ?> ><?php echo JText::_('AUTO_ASSIGN'); ?></option>
							<option value="unassigned" <?php if ($this->settings['forward_product_handler'] == "unassigned") echo " SELECTED"; ?> ><?php echo JText::_('UNASSIGNED'); ?></option>
						</select>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_forward_product_handler'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
							<?php echo JText::_("forward_handler_handler"); ?>:
					</td>
					<td>
						<select name="forward_handler_handler">
							<option value="unchanged" <?php if ($this->settings['forward_handler_handler'] == "unchanged") echo " SELECTED"; ?> ><?php echo JText::_('Unchanged'); ?></option>
							<option value="auto" <?php if ($this->settings['forward_handler_handler'] == "auto") echo " SELECTED"; ?> ><?php echo JText::_('AUTO_ASSIGN'); ?></option>
							<option value="unassigned" <?php if ($this->settings['forward_handler_handler'] == "unassigned") echo " SELECTED"; ?> ><?php echo JText::_('UNASSIGNED'); ?></option>
						</select>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_forward_handler_handler'); ?></div>
					</td>
				</tr>
			</table>
		</fieldset>

		<fieldset class="adminform">
			<legend><?php echo JText::_("SUPPORT_MISC_SETTINGS"); ?></legend>
			<table class="table table-bordered table-condensed table-striped table-settings">
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("allow_raw_html_messages"); ?>:
					</td>
					<td style="width:250px;">
						<input type='checkbox' name='allow_raw_html_messages' value='1' <?php if ($this->settings['allow_raw_html_messages'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'>
							<div style="color:red;font-weight:bold;">Enabling this can expose your site to many security issues, and Freestyle Joomla highly recommend
							NOT enabling this option. If you do enable this, Freestlye Joomla are not responsible for any problems this may cause.</div>
							You will also need to enable the "Import EMails as HTML" option within your ticket email account config.
						</div>
					</td>
				</tr>
			</table>
		</fieldset>
		
		<fieldset class="adminform">
			<legend><?php echo JText::_("Attachment_Settings"); ?></legend>
			<table class="table table-bordered table-condensed table-striped table-settings">
				<tr>
					<td align="left" class="key">
							<?php echo JText::_("USER_CAN_ATTACH_FILES"); ?>:
					</td>
					<td>
						<select name="support_user_attach">
							<option value="0" <?php if ($this->settings['support_user_attach'] == "0") echo " SELECTED"; ?> ><?php echo JText::_('JNO'); ?></option>
							<option value="1" <?php if ($this->settings['support_user_attach'] == "1") echo " SELECTED"; ?> ><?php echo JText::_('REGISTERED_USERS_ONLY'); ?></option>
							<option value="2" <?php if ($this->settings['support_user_attach'] == "2") echo " SELECTED"; ?> ><?php echo JText::_('ALL_USERS'); ?></option>
						</select>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_user_attach'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
							<?php echo JText::_("support_attach_max_size"); ?>:
					</td>
					<td>
						<input type='text' name='support_attach_max_size' value='<?php echo $this->settings['support_attach_max_size']; ?>'>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_attach_max_size'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
							<?php echo JText::_("support_attach_max_size_admins"); ?>:
					</td>
					<td>
						<input type='text' name='support_attach_max_size_admins' value='<?php echo $this->settings['support_attach_max_size_admins']; ?>'>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_attach_max_size_admins'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
							<?php echo JText::_("support_attach_types"); ?>:
					</td>
					<td>
						<input type='text' name='support_attach_types' value='<?php echo $this->settings['support_attach_types']; ?>'>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_attach_types'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
							<?php echo JText::_("support_attach_types_admins"); ?>:
					</td>
					<td>
						<input type='text' name='support_attach_types_admins' value='<?php echo $this->settings['support_attach_types_admins']; ?>'>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_attach_types_admins'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">				
							<?php echo JText::_("ATTACHMENT_FILENAME"); ?>:
					</td>
					<td>
						<select name="support_filename">
							<option value="0" <?php if ((int)$this->settings['support_filename'] == 0) echo " SELECTED"; ?> ><?php echo JText::_('AF_FILENAME'); ?></option>
							<option value="1" <?php if ($this->settings['support_filename'] == 1) echo " SELECTED"; ?> ><?php echo JText::_('AF_USER_FILENAME'); ?></option>
							<option value="2" <?php if ($this->settings['support_filename'] == 2) echo " SELECTED"; ?> ><?php echo JText::_('AF_USER_DATE_FILENAME'); ?></option>
							<option value="3" <?php if ($this->settings['support_filename'] == 3) echo " SELECTED"; ?> ><?php echo JText::_('AF_DATE_USER_FILENAME'); ?></option>
							<option value="4" <?php if ($this->settings['support_filename'] == 4) echo " SELECTED"; ?> ><?php echo JText::_('AF_DATE_FILENAME'); ?></option>
						</select>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_SUPPORT_ATTACHMENT_FILENAME'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">				
							<?php echo JText::_("attach_storage_filename"); ?>:
					</td>
					<td>
						<select name="attach_storage_filename">
							<option value="0" <?php if ((int)$this->settings['attach_storage_filename'] == 0) echo " SELECTED"; ?> ><?php echo JText::_('ASFN_FILENAME_UID_EXTENSION'); ?></option>
							<option value="1" <?php if ($this->settings['attach_storage_filename'] == 1) echo " SELECTED"; ?> ><?php echo JText::_('ASFN_TICKETID_FILENAME_UID_EXTENSION'); ?></option>
							<option value="2" <?php if ($this->settings['attach_storage_filename'] == 2) echo " SELECTED"; ?> ><?php echo JText::_('ASFN_YEAR_MONTH_FILENAME_UID_EXTENSION'); ?></option>
							<option value="3" <?php if ($this->settings['attach_storage_filename'] == 3) echo " SELECTED"; ?> ><?php echo JText::_('ASFN_YEAR_MONTH_DATE_FILENAME_UID_EXTENSION'); ?></option>
							<option value="4" <?php if ($this->settings['attach_storage_filename'] == 4) echo " SELECTED"; ?> ><?php echo JText::_('ASFN_USERNAME_FILENAME_UID_EXTENSION'); ?></option>
						</select>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_attach_storage_filename'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
							<?php echo JText::_("support_attach_use_old_system"); ?>:
					</td>
					<td style="width:250px;">
						<input type='checkbox' name='support_attach_use_old_system' value='1' <?php if ($this->settings['support_attach_use_old_system'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_support_attach_use_old_system'); ?></div>
					</td>
				</tr>
			</table>
		</fieldset>