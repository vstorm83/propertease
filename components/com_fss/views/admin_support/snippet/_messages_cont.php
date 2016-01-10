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

<?php echo FSS_Helper::PageSubTitle("MESSAGES"); ?>

<?php if (!$this->print): ?>
	<p>
		<?php if (FSS_Settings::get('support_actions_as_buttons')) : ?>
	
			<div class="pull-right">
				<a class="btn btn-default fssTip" href="#" onclick='jQuery(".fss_support_msg_audit").toggle();return false;' title="<?php echo JText::_("AUDIT_LOG"); ?>">
					<i class="icon-database"></i><span class='hidden-phone'><?php echo JText::_("AUDIT_LOG"); ?></span>
				</a>

				<a class="btn btn-default fssTip" href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=admin_support&layout=ticket&ticketid=' . $this->ticket->id ); ?>' title="<?php echo JText::_("REFRESH"); ?>">
					<i class="icon-refresh"></i><span class='hidden-phone'><?php echo JText::_("REFRESH"); ?></span>
				</a>

				<a class="btn btn-default fssTip" title="Reverse Message Order" href="<?php echo FSSRoute::_( 'index.php?option=com_fss&view=admin_support&layout=ticket&ticketid=' . $this->ticket->id . "&sort=" . (1-FSS_Input::getInt('sort'))  ); ?>">
					<i class="icon-calendar"></i>
				</a>
			</div>

			<?php if (!$this->ticket->isLocked() && $this->can_Reply()): ?>
				<a class="btn btn-primary" href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=admin_support&layout=reply&ticketid=' . $this->ticket->id ); ?>'>
					<i class="icon-redo"></i><span class='visible-phone'><?php echo JText::_("POST_REPLY_LINK_SHORT"); ?></span><span class='hidden-phone'><?php echo JText::_("POST_REPLY_LINK"); ?></span>
				</a>
			<?php endif; ?>

			<a class="btn btn-default" href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=admin_support&layout=reply&type=private&ticketid=' . $this->ticket->id ); ?>'>
				<i class="icon-key"></i><span class='visible-phone'><?php echo JText::_("ADD_PRIVATE_COMMENT_SHORT"); ?></span><span class='hidden-phone'><?php echo JText::_("ADD_PRIVATE_COMMENT"); ?></span>
			</a>


		<?php else: ?>

			<div class="pull-right">
				<a class="fssTip" title="<?php echo JText::_("AUDIT_LOG"); ?>" href='#' onclick='jQuery(".fss_support_msg_audit").toggle();return false;'>
					<i class="icon-database"></i><span class='hidden-phone'><?php echo JText::_("AUDIT_LOG"); ?></span></a><span class='hidden-phone'>&nbsp;&nbsp;</span>

				<a class="fssTip" title="<?php echo JText::_("REFRESH"); ?>" href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=admin_support&layout=ticket&ticketid=' . $this->ticket->id ); ?>'>
					<i class="icon-refresh"></i><span class='hidden-phone'><?php echo JText::_("REFRESH"); ?></span></a>
				
				<span class='hidden-phone'>&nbsp;&nbsp;</span>
				
				<a class="fssTip" title="Reverse Message Order" href="<?php echo FSSRoute::_( 'index.php?option=com_fss&view=admin_support&layout=ticket&ticketid=' . $this->ticket->id . "&sort=" . (1-FSS_Input::getInt('sort')) ); ?>">
					<i class="icon-calendar"></i><span class='hidden-phone'></span></a>
			</div>
		
			<?php if (!$this->ticket->isLocked() && $this->can_Reply()): ?>
				<a class="fssTip" title="<?php echo JText::_("POST_REPLY_LINK"); ?>" href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=admin_support&layout=reply&ticketid=' . $this->ticket->id ); ?>'>
					<i class="icon-redo"></i><?php echo JText::_("POST_REPLY_LINK"); ?></a>&nbsp;&nbsp;
			<?php endif; ?>

			<a class="fssTip" title="<?php echo JText::_("ADD_PRIVATE_COMMENT"); ?>" href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=admin_support&layout=reply&type=private&ticketid=' . $this->ticket->id ); ?>'>
				<i class="icon-key"></i><?php echo JText::_("ADD_PRIVATE_COMMENT"); ?></a>&nbsp;&nbsp;

		<?php endif; ?>

		<?php echo FSS_GUIPlugins::output("adminTicketReplyBar", array('ticket'=> $this->ticket, FSS_Settings::get('support_actions_as_buttons'))); ?>

	</p>
<?php endif; ?>

<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin_support'.DS.'snippet'.DS.'_messages.php'); ?>
<?php if (!$this->print) include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin_support'.DS.'snippet'.DS.'_messages_key.php'); ?>

<?php if (count($this->ticket->attach) > 0) : ?>
	<?php echo FSS_Helper::PageSubTitle("ATTACHEMNTS"); ?>
	<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin_support'.DS.'snippet'.DS.'_attachments.php'); ?>
<?php endif; ?>

<?php if (FSS_Settings::get('glossary_support')) echo FSS_Glossary::Footer(); ?>
