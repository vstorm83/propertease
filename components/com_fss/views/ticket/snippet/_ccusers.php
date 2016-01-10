<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>

<?php if (count($this->ticket['cc']) == 0): ?>
	<?php echo JText::_('NONE_') ?>
<?php else: ?>
	<?php if (JFactory::getUser()->id == $this->ticket['user_id']): ?>
		<?php foreach($this->ticket['cc'] as $cc): ?>
			<div class="fss_tag label label-small-close fssTip <?php echo $cc['readonly'] || $cc['email'] ? 'label-warning' : 'label-success'; ?>"
				 title="<?php echo $cc['readonly'] ? JText::_('READ_ONLY') : JText::_('FULL_ACCESS'); ?>" id="tag_<?php echo $cc['id']; ?>">
				<button class="close" onclick="removecc('<?php echo $cc['id']; ?>');return false;">&times;</button>
				<?php echo $cc['name'] ? $cc['name'] : $cc['email']; ?>
			</div>
		<?php endforeach; ?>
	<?php else: ?>
		<?php foreach($this->ticket['cc'] as $cc): ?>
			<div class="fss_tag label label-small-close fssTip <?php echo $cc['readonly'] || $cc['email'] ? 'label-warning' : 'label-success'; ?>"
				 title="<?php echo $cc['readonly'] ? JText::_('READ_ONLY') : JText::_('FULL_ACCESS'); ?>" id="tag_<?php echo $cc['id']; ?>">
				<?php echo $cc['name'] ? $cc['name'] : $cc['email']; ?>
			</div>
		<?php endforeach; ?>
	<?php endif; ?>
<?php endif; ?>

