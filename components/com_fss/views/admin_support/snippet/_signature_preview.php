<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<?php echo FSS_Helper::PageStylePopup(true); ?>
<?php echo FSS_Helper::PageTitlePopup('Signature Preview'); ?>

<?php echo FSS_Helper::ParseBBCode(trim($this->signature)); ?>

<?php echo FSS_Helper::PageStylePopupEnd(); ?>