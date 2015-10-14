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

	<?php foreach ($this->tickets as $this->ticket): ?>
	<?php echo FSS_Helper::PageTitle('SUPPORT_TICKETS',$this->ticket->title); ?>

		<?php if (FSS_Settings::get("messages_at_top") == 2 || FSS_Settings::get("messages_at_top") == 3)
		include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin_support'.DS.'snippet'.DS.'_messages_cont.php'); ?>

		<?php echo FSS_Helper::PageSubTitle("TICKET_DETAILS"); ?>

		<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin_support'.DS.'snippet'.DS.'_ticket_info.php'); ?>

		<?php if (FSS_Settings::get("messages_at_top") == 0 || FSS_Settings::get("messages_at_top") == 1)
			include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin_support'.DS.'snippet'.DS.'_messages_cont.php'); ?>

		<div style="page-break-after: always;">
		</div>

	<?php endforeach; ?>

<?php echo FSS_Helper::PageStyleEnd(); ?>

<script>
jQuery(document).ready( function () {
	window.print();
});
</script>