<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>

<li id='prod_cont_<?php echo $product['id']; ?>' 
	class="media pointer highlight product" 
	onclick="setCheckedValue(document.forms['prodselect'].elements['prodid'],'<?php echo $product['id']; ?>');"
	>
	<div class="pull-left" <?php if (FSS_Settings::get('support_next_prod_click') == 1) echo "style='display:none'"; ?>>
		<label for="prodid_<?php echo $product['id']; ?>">
			<input type="radio" name="prodid" id="prodid_<?php echo $product['id']; ?>" value="<?php echo $product['id']; ?>" ></input>
		</label>
	</div>
	
	<div class="pull-left <?php if ($hasprodimages) echo 'product-image'; ?>">
		<?php if ($product['image']) : ?>
			<img class="media-object" src="<?php echo JURI::root( true ); ?>/images/fss/products/<?php echo FSS_Helper::escape($product['image']); ?>">
		<?php endif; ?>
	</div>
	<div class="media-body">
		<h4 class="media-heading"><?php echo $product['title'] ?></h4>
		<?php echo $product['description']; ?>
	</div>
</li>
