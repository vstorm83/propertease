<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
	<?php $hasprodimages = false; $multitype = false; $hast0 = false; $hast1 = false; ?>
	
	<?php foreach ($this->products as $product): ?>
		<?php if ($product['image']) { $hasprodimages = true;} ?>
		<?php if ($product['maxtype'] == 0)	$hast0 = true; ?>
		<?php if ($product['maxtype'] == 1)	$hast1 = true; ?>
	<?php endforeach; ?>
		
	<?php if ($hast0 && $hast1) $multitype = true; ?>
	<?php $curtype = -1; ?>
		
	<?php 
		$has_cats = false;
		foreach ($this->products as $product)
		{
			if ($product['category'] != "") $has_cats = true;
		}

		if (JRequest::getVar('prodsearch') != "" && JRequest::getVar('prodsearch') != "__all__") $has_cats = false;
	?>
				
	<?php if ($multitype || !$has_cats): ?>
		<ul class="media-list">
		<?php foreach ($this->products as $product): ?>
			<?php if ($multitype && $curtype != $product['maxtype']) : ?>
				</ul>
				<div class="clearfix"></div>
			
				<?php echo $product['maxtype'] == 0 ? FSS_Helper::PageSubTitle2("OTHER_PRODUCTS") : FSS_Helper::PageSubTitle2("MY_PRODUCTS") ?>
			
				<ul class="media-list">
				<?php $curtype = $product['maxtype']; ?>
			
			<?php endif ;?>

			<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_prod.php'); ?>
		
		<?php endforeach; ?>
		</ul>
	<?php else: ?>

		<?php $category = "----------"; $subcat = "---------"; ?>
		<?php $in_cat = false; $in_subcat = false; ?>

		<?php $col = 0; ?>

		<?php foreach ($this->products as $product): ?>

			<?php if ($product['category'] != $category): ?>
				<?php if ($in_subcat) echo "</ul></div>"; ?>
				<?php if ($in_cat) echo "</div>";?>

				<?php if ($product['category'] == ""): ?>
					<div>
				<?php else: ?>
					<h4 class="product_category_header"><?php echo $product['category']; ?></h4>
					<div class="product_category_indent">
				<?php endif; ?>

				<?php $subcat = "--------"; $in_subcat = false; ?>
				<?php $category = $product['category']; $in_cat = true; ?>
			<?php endif; ?>

			<?php if ($product['subcat'] != $subcat): ?>
				<?php if ($in_subcat) echo "</ul></div>";?>

				<?php if ($product['subcat'] == ""): ?>
					<div>
				<?php else: ?>
					<h5 class="product_subcat_header"><?php echo $product['subcat']; ?></h5>
					<div class="product_subcat_indent">
				<?php endif; ?>

					<ul class="media-list products">
				<?php $subcat = ""; ?>
				<?php $subcat = $product['subcat']; $in_subcat = true; ?>
			<?php endif; ?>


			<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_prod.php'); ?>
			<?php $col = 1 - $col; ?>
			
		<?php endforeach; ?>

		<?php if ($in_subcat) echo "</ul></div>";?>
		<?php if ($in_cat) echo "</div>";?>
	<?php endif; ?>
		
	<div class="clearfix"></div>
		
	<?php if (count($this->products) == 0): ?>
	<div class="alert alert-info"><?php echo JText::_("NO_PRODUCTS_MATCH_YOUR_SEARCH_CRITERIA"); ?></div>
	<?php endif; ?>
	
	<?php if (FSS_Settings::get('support_advanced')) echo $this->pagination->getListFooter(); ?>