<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<li id='dept_cont_<?php echo $dept['id']; ?>'
	class="media pointer highlight department"
	onclick="setCheckedValue(document.forms['deptselect'].elements['deptid'],'<?php echo $dept['id']; ?>');"
	  >
	<div class="pull-left" <?php if (FSS_Settings::get('support_next_prod_click') == 1) echo "style='display:none'"; ?>>
		<label for="deptid_<?php echo $dept['id']; ?>">
			<input type="radio" name="deptid" id="deptid_<?php echo $dept['id']; ?>" value="<?php echo $dept['id']; ?>" ></input>
		</label>
	</div>
	
	<div class="pull-left">
		<?php if ($dept['image']) : ?>
			<img class="media-object" src="<?php echo JURI::root( true ); ?>/images/fss/departments/<?php echo FSS_Helper::escape($dept['image']); ?>">
		<?php endif; ?>
	</div>
	<div class="media-body">
		<h4 class="media-heading"><?php echo $dept['title'] ?></h4>
		<?php echo $dept['description']; ?>
	</div>
</li>
