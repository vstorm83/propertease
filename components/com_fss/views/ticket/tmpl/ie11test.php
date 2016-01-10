<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<div class="form-horizontal form-condensed">
				
				<div class="control-group  ">
				<label class="control-label">Combo Test</label>
				<div class="controls">
					<select name="custom_8" id="custom_8">
<option value="">Please Select...</option>
<option value="Tree">Tree</option>
<option value="Car">Car</option>
<option value="Shopping">Shopping</option>
<option value="News">News</option>
</select><span class="help-inline"></span>				</div>
			</div>
				
			</div>
			
<textarea name='body' id='body' class='sceditor' rows='<?php echo (int)FSS_Settings::get('support_user_reply_height'); ?>' cols='<?php echo (int)FSS_Settings::get('support_user_reply_width'); ?>' style='width:95%;height:<?php echo (int)((FSS_Settings::get('support_user_reply_height') * 15) + 80); ?>px'></textarea>
