<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<div class="ticket-login">
<?php echo FSS_Helper::PageStyle(); ?>
<?php echo FSS_Helper::PageTitle("SUPPORT","VIEW_SUPPORT_TICKET"); ?>

<?php if ($this->type == 0) : ?>
	<div class="alert alert-info"><?php echo JText::_("YOU_MUST_BE_LOGGED_IN_TO_VIEW_A_SUPPORT_TICKET"); ?></div>
<?php elseif ($this->type == 2) : ?>
	<div class="alert alert-error"><?php echo JText::_("UNABLE_TO_FIND_A_SUPPORT_TICKET_WITH_THE_PROVIDED_EMAIL_AND_PASSWORD"); ?></div>
<?php endif; ?>

<?php FSS_Helper::HelpText("support_view_login_header"); ?>

<?php if (FSS_Settings::get('support_no_logon') == 0): ?>
	<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_login_form.php'); ?>
<?php endif; ?>

<?php if (FSS_Settings::get('support_no_register') == 2): ?>
	<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_register_form.php'); ?>
<?php endif; ?>

<?php if (FSS_Settings::get('support_allow_unreg') == 1): ?>
	<?php echo FSS_Helper::PageSubTitle("VIEW_TICKET_CREATED_WITHOUT_ACCOUNT"); ?>

	<?php if (FSS_Settings::get('support_unreg_type') == 0): ?>
		<p><?php echo JText::_("PLEASE_ENTER_YOUR_EMAIL_ADDRESS_AND_PASSWORD_PROVIDED_WITH_YOUR_TICKET"); ?></p>
	<?php elseif (FSS_Settings::get('support_unreg_type') == 1): ?>
		<p><?php echo JText::_("PLEASE_ENTER_YOUR_TICKET_REFERENCE_AND_PASSWORD_PROVIDED_WITH_YOUR_TICKET"); ?></p>
	<?php elseif (FSS_Settings::get('support_unreg_type') == 2): ?>
		<p><?php echo JText::_("PLEASE_ENTER_YOUR_TICKET_REFERENCE_PROVIDED_WITH_YOUR_TICKET"); ?></p>
	<?php endif; ?>

	<form action="<?php echo FSSRoute::_("&what=");// FIX LINK ?>"  method="post" name="uregform" id="uregform" class="form-horizontal">
		<input id="unreg_what" type="hidden" name="what" value="" />

		<?php if (FSS_Settings::get('support_unreg_type') == 0): ?>
		<div class="control-group">
			<label class="control-label" for="username"><?php echo JText::_("EMAIL_ADDRESS"); ?></label>
				<div class="controls">
					<div class="input-prepend">
					<span class="add-on">
						<span class="icon-user" title="<?php echo JText::_("EMAIL_ADDRESS"); ?>"></span>
					</span>
					<input type="text" name="email" id="email" class="input-medium" placeholder="<?php echo JText::_("EMAIL_ADDRESS"); ?>">
				</div>
			</div>
		</div>
		<?php endif; ?>

		<?php if (in_array(FSS_Settings::get('support_unreg_type'), array(1,2)) ): ?>
		<div class="control-group">
			<label class="control-label" for="username"><?php echo JText::_("TICKET_REFERENCE"); ?></label>
				<div class="controls">
					<div class="input-prepend">
					<span class="add-on">
						<span class="icon-user" title="<?php echo JText::_("TICKET_REFERENCE"); ?>"></span>
					</span>
					<input type="text" name="reference" id="reference" class="input-medium" placeholder="<?php echo JText::_("TICKET_REFERENCE"); ?>">
				</div>
			</div>
		</div>
		<?php endif; ?>

		<?php if (in_array(FSS_Settings::get('support_unreg_type'), array(0,1)) ): ?>
		<div class="control-group">
			<label class="control-label" for="password"><?php echo JText::_("TICKET_PASSWORD"); ?></label>
			<div class="controls">
				<div class="input-prepend">
					<span class="add-on">
						<span class="icon-lock" title="<?php echo JText::_("TICKET_PASSWORD"); ?>"></span>
					</span>
					<input type="password" name="password" id="password" class="input-medium" placeholder="<?php echo JText::_("TICKET_PASSWORD"); ?>">
				</div>
			</div>
		</div>
		<?php endif; ?>

		<div class="control-group">
			<div class="controls">
				<button type="submit" class="btn btn-primary"><?php echo JText::_("FIND_TICKET"); ?></button>
				<button onclick="jQuery('#unreg_what').val('unreg_passlist');" type="submit" class="btn btn-default fssTip" title="<?php echo JText::_('FORGOTTEN_YOUR_PASSWORD__ENTER_YOUR_EMAIL_ABOVE_AND_USE_THIS_BUTTON_TO_EMAIL_YOURSELF_A_LIST_OF_YOUR_SUPPORT_TICKETS'); ?>"><?php echo JText::_("EMAIL_TICKET_LIST"); ?></a>
			</div>
		</div>

	</form>
<?php endif; ?>

<?php echo FSS_Helper::PageStyleEnd(); ?>
</div>