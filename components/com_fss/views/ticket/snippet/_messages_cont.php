<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

if (FSS_Settings::get('glossary_support')) require_once(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'glossary.php');
?>
<?php if (!FSS_Settings::get('user_hide_all_details')): ?>
	<?php echo FSS_Helper::PageSubTitle("MESSAGES"); ?>
<?php endif; ?>

<?php FSS_Helper::HelpText("support_user_view_mes_header"); ?>

<?php $st = FSS_Ticket_Helper::GetStatusByID($this->ticket['sid']); ?>

<?php if ((!$st->is_closed || FSS_Settings::get('support_user_can_reopen')) && $this->ticket['can_edit'] && $this->ticket['merged'] == 0 && !$this->readonly): ?>
	<p>
		<?php if (FSS_Settings::get('support_actions_as_buttons')) :?>

			<button class="btn btn-default ticketrefresh pull-right" href='<?php echo FSSRoute::_( '&view=ticket&what=messages&ticketid=' . $this->ticket['id'] );// FIX LINK ?>'>
				<i class="icon-refresh"></i> <?php echo JText::_("REFRESH"); ?>
			</button>
			
			<button class="btn btn-primary post_reply">
				<i class="icon-redo"></i> <?php echo JText::_("POST_REPLY_LINK"); ?>
			</button>
	

		<?php else: ?>

			<a class="pull-right ticketrefresh" href='<?php echo FSSRoute::_( '&view=ticket&what=messages&ticketid=' . $this->ticket['id'] );// FIX LINK ?>'>
				<i class="icon-refresh"></i> <?php echo JText::_("REFRESH"); ?>
			</a>

			<a class="post_reply" href='<?php echo FSSRoute::_( '&option=com_fss&view=ticket&layout=reply&ticketid=' . $this->ticket['id'] );// FIX LINK ?>'>
				<i class="icon-redo"></i> <?php echo JText::_("POST_REPLY_LINK"); ?>
			</a>

		<?php endif; ?>

		<?php echo FSS_GUIPlugins::output("userTicketReplyBar", array('ticket'=> $this->ticket, FSS_Settings::get('support_actions_as_buttons'))); ?>
	</p>
<?php endif; ?>

<div id="messagereply" style="display: none;">
	<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_reply.php'); ?>
</div>

<div id="messagepleasewait" style="display: none;clear: both" class="alert alert-info">
	<?php echo JText::_('PLEASE_WAIT'); ?>
</div>

<?php FSS_Helper::HelpText("support_user_view_mes_buttons"); ?>

<div id="ticket_messages">
	<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_messages.php'); ?>
</div>

<?php FSS_Helper::HelpText("support_user_view_mes_footer"); ?>

<?php if (FSS_Settings::get('glossary_support')) echo FSS_Glossary::Footer(); ?>
