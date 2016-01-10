<?php
/**
 * @version		1.6.8
 * @package		Joomla
* @subpackage	Membership Pro
 * @author  Tuan Pham Ngoc
 * @copyright	Copyright (C) 2012 - 2014 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined( '_JEXEC' ) or die ;
JHtml::_('behavior.tooltip');
$editor = JFactory::getEditor(); 	
$translatable = JLanguageMultilang::isEnabled() && count($this->languages);
?>
<style>
fieldset.adminform {
	border: none ;
	margin: 0px;
	padding: 0px;	
}
</style>
<script type="text/javascript">	
	Joomla.submitbutton = function(pressbutton)
	{
		
		var form = document.adminForm;
		if (pressbutton == 'plan.cancel') 
		{
			Joomla.submitform(pressbutton, form);
			return;				
		} 
		else 
		{
			//Validate the entered data before submitting			
			if (form.title.value == '') {
				alert("<?php echo JText::_('OSM_ENTER_PLAN_TITLE'); ?>");
				form.title.focus();
				return ;
			}
			var lifetimeMembership = jQuery('input[name=\'lifetime_membership\']:checked').val();							
			if (!form.subscription_length.value  && lifetimeMembership == 0) {
				alert("<?php echo JText::_('OSM_ENTER_SUBSCRIPTION_LENGTH'); ?>");
				form.subscription_length.focus();
				return ;
			}
			var recurringSubscription = jQuery('input[name=\'recurring_subscription\']:checked').val();
			if (recurringSubscription == 1 && form.price.value <= 0) {
				alert("<?php echo JText::_('OSM_PRICE_REQUIRED'); ?>");
				form.price.focus();
				return ;
			}
																
			Joomla.submitform(pressbutton, form);
		}								
	}				
	function addRow() {
		var table = document.getElementById('price_list');
		var newRowIndex = table.rows.length - 1 ;
		var row = table.insertRow(newRowIndex);			
		var registrantNumber = row.insertCell(0);							
		var price = row.insertCell(1);						
		registrantNumber.innerHTML = '<input type="text" class="inputbox" name="number_days[]" size="10" />';			
		price.innerHTML = '<input type="text" class="inputbox" name="renew_price[]" size="10" />';		
		
	}
	function removeRow() {
		var table = document.getElementById('price_list');
		var deletedRowIndex = table.rows.length - 2 ;
		if (deletedRowIndex >= 1) {
			table.deleteRow(deletedRowIndex);
		} else {
			alert("<?php echo JText::_('OSM_NO_ROW_TO_DELETE'); ?>");
		}
	}
	
</script>

<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
	<div class="row-fluid">
		<ul class="nav nav-tabs">
			<li class="active"><a href="#general-page" data-toggle="tab"><?php echo JText::_('OSM_BASIC_INFORMATION');?></a></li>			
			<?php 
				if ($translatable)
				{
					?>						
						<li><a href="#translation-page" data-toggle="tab"><?php echo JText::_('OSM_TRANSLATION'); ?></a></li>																									
					<?php	
				}
				if (count($this->plugins)) {
					$count = 0 ;
					foreach ($this->plugins as $plugin) {
						$title  = $plugin['title'] ;
						$count++ ;
					?>
						<li><a href="#<?php echo 'tab_'.$count;  ?>" data-toggle="tab"><?php echo $title;?></a></li>
					<?php							
					}
				}
			?>					
		</ul>
		<div class="tab-content">			
			<div class="tab-pane active" id="general-page">
				<div class="span8 pull-left">
						<fieldset class="adminform">
							<legend><?php echo JText::_('OSM_PLAN_DETAIL');?></legend>
								<table class="admintable" style="width: 100%;">
									<tr>
										<td width="220" class="key">
											<?php echo  JText::_('OSM_TITLE'); ?>
										</td>
										<td>
											<input class="text_area" type="text" name="title" id="title" size="40" maxlength="250" value="<?php echo $this->item->title;?>" />
										</td>
										<td>
											&nbsp;
										</td>
									</tr>
                                    <tr>
                                        <td width="220" class="key">
                                            <?php echo  JText::_('OSM_ALIAS'); ?>
                                        </td>
                                        <td>
                                            <input class="text_area" type="text" name="alias" id="alias" size="40" maxlength="250" value="<?php echo $this->item->alias;?>" />
                                        </td>
                                        <td>
                                            &nbsp;
                                        </td>
                                    </tr>
                                    <tr>
										<td valign="top" class="key">
											<?php echo  JText::_('OSM_CATEGORY'); ?>
										</td>
										<td >
											<?php echo $this->lists['category_id']; ?>
										</td>
										<td>
											&nbsp;
										</td>
									</tr>				
									<tr>
										<td class="key">
											<?php echo  JText::_('OSM_PRICE'); ?>
										</td>
										<td>
											<input class="text_area" type="text" name="price" id="price" size="10" maxlength="250" value="<?php echo $this->item->price;?>" />
										</td>
										<td>
											&nbsp;
										</td>
									</tr>
									<tr>
										<td class="key">
											<?php echo  JText::_('OSM_SUBSCRIPTION_LENGTH'); ?>
										</td>
										<td>
											<input class="input-small" type="text" name="subscription_length" id="subscription_length" size="10" maxlength="250" value="<?php echo $this->item->subscription_length;?>" /><?php echo $this->lists['subscription_length_unit']; ?>
										</td>
										<td>
											&nbsp;
										</td>
									</tr>
									<tr>
										<td class="key">
											<?php echo JText::_('OSM_EXPIRED_DATE'); ?>
										</td>
										<td>						
											<?php echo JHtml::_('calendar', (($this->item->expired_date == $this->nullDate) ||  !$this->item->expired_date) ? '' : JHtml::_('date', $this->item->expired_date, 'Y-m-d', null), 'expired_date', 'expired_date') ; ?>						
										</td>
										<td>
											&nbsp;
										</td>
									</tr>
									<tr>
										<td class="key">
											<?php echo JText::_('OSM_LIFETIME_MEMBERSHIP');?>
										</td>
										<td>						
											<?php echo $this->lists['lifetime_membership'];?>						
										</td>
										<td>
											&nbsp;
										</td>
									</tr>												
									<tr>
										<td width="150" class="key" valign="top">
											<?php echo  JText::_('OSM_THUMB'); ?>
										</td>
										<td valign="top">
											<input type="file" class="inputbox" name="thumb_image" size="60" />
											<?php
												if ($this->item->thumb) {
												?>
													<img src="<?php echo JUri::root().'media/com_osmembership/'.$this->item->thumb; ?>" class="img_preview" />
													<input type="checkbox" name="del_thumb" value="1" /><?php echo JText::_('OSM_DELETE_CURRENT_THUMB'); ?>
												<?php	
												}
											?>
										</td>
										<td>
											&nbsp;
										</td>
									</tr>
									<tr>
										<td width="150" class="key">
											<?php echo  JText::_('OSM_SEND_FIRST_REMINDER'); ?>
										</td>
										<td>
											<input type="text" class="inputbox" name="send_first_reminder" value="<?php echo $this->item->send_first_reminder; ?>" size="5" />&nbsp;<?php echo JText::_('OSM_BEFORE_SUBSCRIPTION_EXPIRED'); ?>
										</td>
										<td>
											&nbsp;
										</td>
									</tr>
									<tr>
										<td width="150" class="key">
											<?php echo  JText::_('OSM_ENABLE_RENEWAL'); ?>
										</td>
										<td>
											<?php echo $this->lists['enable_renewal']; ?>
										</td>
										<td>
											&nbsp;
										</td>
									</tr>
									<tr>
										<td width="150" class="key">
											<?php echo  JText::_('OSM_SEND_SECOND_REMINDER'); ?>
										</td>
										<td>
											<input type="text" class="inputbox" name="send_second_reminder" value="<?php echo $this->item->send_second_reminder; ?>" size="5" />&nbsp;<?php echo JText::_('OSM_BEFORE_SUBSCRIPTION_EXPIRED'); ?>
										</td>
										<td>
											&nbsp;
										</td>
									</tr>
									<tr>
										<td width="150" class="key">
											<?php echo  JText::_('OSM_SUBSCRIPTION_COMPLETE_URL'); ?>
										</td>
										<td>
											<input type="text" class="inputbox" name="subscription_complete_url" value="<?php echo $this->item->subscription_complete_url; ?>" size="50" />
										</td>
										<td>
											&nbsp;
										</td>
									</tr>
									<tr>
										<td width="150" class="key">
											<label for="notification_emails" class="control-label hasTooltip" title="<?php echo JText::_('OSM_PLAN_NOTIFICATION_EMAILS'); ?>"><strong><?php echo  JText::_('OSM_NOTIFICATION_EMAILS'); ?></strong></label>
										</td>
										<td>
											<input type="text" class="input-xlarge" name="notification_emails" value="<?php echo $this->item->notification_emails; ?>" />
										</td>
									</tr>
									<tr>
										<td width="150" class="key">
											<label for="notification_emails" class="control-label hasTooltip" title="<?php echo JText::_('OSM_PAYPAL_EMAIL_EXPLAIN'); ?>"><strong><?php echo  JText::_('OSM_PAYPAL_EMAIL'); ?></strong></label>
										</td>
										<td>
											<input type="text" class="inputbox" name="paypal_email" value="<?php echo $this->item->paypal_email; ?>" />
										</td>
										<td>
											&nsbp;
										</td>
									</tr>
									<tr>
										<td class="key">
											<?php echo JText::_('OSM_ACCESS'); ?>
										</td>
										<td>
											<?php echo $this->lists['access']; ?>
										</td>
										<td>
											&nbsp;
										</td>
									</tr>					
									<tr>
										<td class="key">
											<?php echo JText::_('OSM_PUBLISHED'); ?>
										</td>
										<td>
											<?php echo $this->lists['published']; ?>
										</td>
										<td>
											&nbsp;
										</td>
									</tr>					
									<tr>
										<td class="key">
											<?php echo JText::_('OSM_SHORT_DESCRIPTION'); ?>
										</td>
										<td>
											<?php echo $editor->display( 'short_description',  $this->item->short_description , '100%', '250', '75', '10' ) ; ?>
										</td>
										<td>
											&nbsp;
										</td>
									</tr>												
									<tr>
										<td class="key">
											<?php echo JText::_('OSM_DESCRIPTION'); ?>
										</td>
										<td>
											<?php echo $editor->display( 'description',  $this->item->description , '100%', '250', '75', '10' ) ; ?>
										</td>
										<td>
											&nbsp;
										</td>
									</tr>	
									<tr>
										<td class="key">
											<label id="subscription_form_message-lbl" for="subscription_form_message" class="hasTip" title="<?php echo JText::_('OSM_PLAN_SUBSCRIPTION_FORM_MESSAGE_EXPLAIN'); ?>" aria-invalid="false">
	            								<strong><?php echo JText::_('OSM_PLAN_SUBSCRIPTION_FORM_MESSAGE'); ?></strong>
	            							</label>																						
										</td>
										<td>
											<?php echo $editor->display( 'subscription_form_message',  $this->item->subscription_form_message , '100%', '250', '75', '10' ) ; ?>
										</td>
										<td>
											
										</td>
									</tr>									
							</table>	
						</fieldset>
					</div>			
					<div class="span4 pull-left" style="display: inline;">
						<fieldset class="adminform">
							<legend class="adminform"><?php echo JText::_('OSM_RENEW_OPTIONS'); ?></legend>
							<table class="adminlist" id="price_list">
								<tr>
									<th width="30%">
										<?php echo JText::_('OSM_NUMBER_DAYS'); ?>
									</th>				
									<th>
										<?php echo JText::_('OSM_PRICE'); ?>
									</th>
								</tr>
								<?php				
									$n = max(count($this->prices), 3);
									for ($i = 0 ; $i < $n ; $i++) {
											if (isset($this->prices[$i])) {
												$price = $this->prices[$i] ;							
												$numberDays = $price->number_days ;
												$renewPrice = $price->price ;
											} else {
												$numberDays =  null ;
												$renewPrice =  null ;
											}
									?>
										<tr>
											<td>
												<input type="text" class="inputbox" name="number_days[]" size="10" value="<?php echo $numberDays; ?>" />
											</td>						
											<td>
												<input type="text" class="inputbox" name="renew_price[]" size="10" value="<?php echo $renewPrice; ?>" />
											</td>
										</tr>
									<?php				 									
									}
								?>
								<tr>
									<td colspan="3">
										<input type="button" class="button" value="<?php echo JText::_('OSM_ADD'); ?>" onclick="addRow();" />
										&nbsp;
										<input type="button" class="button" value="<?php echo JText::_('OSM_REMOVE'); ?>" onclick="removeRow();" />
									</td>
								</tr>
							</table>					
						</fieldset>
					</div>					
					<div class="span4 pull-left" style="display: inline;">
						<fieldset class="adminform">
							<legend class="adminform"><?php echo JText::_('OSM_UPGRADE_OPTIONS'); ?></legend>
							<table class="adminlist" style="width:100%;">
								<tr>
									<th width="60%">
										<?php echo JText::_('OSM_TO_PLAN'); ?>
									</th>
									<th width="10%">
										<?php echo JText::_('OSM_PRICE'); ?>
									</th>
									<th colspan="2">
										<?php echo JText::_('OSM_PUBLISHED'); ?>
									</th>
								</tr>
								<tbody id="upgrade-rule">
									<?php							
										$options = array();
										$options[] = JHtml::_('select.option', '1', Jtext::_('OSM_YES'));
										$options[] = JHtml::_('select.option', '0', Jtext::_('OSM_NO'));
										for ($i = 0, $n = count($this->upgradeRules); $i < $n; $i++)
										{
											$upgradeRule = $this->upgradeRules[$i];
											$optionPlans = array();
											$optionPlans[] = JHtml::_('select.option', 0, JText::_('OSM_TO_PLAN'), 'id', 'title');
											$optionPlans = array_merge($optionPlans, $this->plans);											
									?>
										<tr id="rule_<?php echo $i; ?>">
											<td>
												<?php echo JHtml::_('select.genericlist', $optionPlans, 'to_plan_id[]', ' class="inputbox input-large" ', 'id', 'title', $upgradeRule->to_plan_id);; ?>
											</td>
											<td>
												<input class="input-mini" type="text" name="upgrade_price[]" size="10" maxlength="250" value="<?php echo $upgradeRule->price?>" />
											</td>
											<td>
												<?php echo JHtml::_('select.genericlist', $options, 'rule_published[]', ' class="inputbox input-mini"', 'value', 'text', $upgradeRule->published); ?>
											</td>
											<td>
												<input type="button" style="margin-bottom: 10px" class="btn btn-small btn-danger" onclick="removeRule(<?php echo $i; ?>)" value="<?php echo JText::_('OSM_REMOVE'); ?>" />
											</td>
										</tr>
									<?php
										}
									?>
								</tbody>
								<tfoot>
									<tr>
										<td colspan="2">
											<button id="add-rule" type="button" class="btn btn-small btn-primary">
											<span class="icon-new icon-white"></span><?php echo JText::_('OSM_ADD'); ?>
											</button>
										</td>
									</tr>
								</tfoot>
							</table>					
						</fieldset>
					</div>
					<div class="span4 pull-left" style="display: inline;">
						<fieldset class="adminform">
							<legend class="adminform"><?php echo JText::_('OSM_RECURRING_SETTINGS'); ?></legend>
							<table class="admintable" id="price_list">
								<tr>
									<td width="30%" class="key">
										<?php echo JText::_('OSM_RECURRING_SUBSCRIPTION'); ?>
									</td>				
									<td>
										<?php echo $this->lists['recurring_subscription']; ?>
									</td>
								</tr>	
								<tr>
									<td colspan="2" class="osm-waring" style="color: #f00; text-align: center;">
										<?php echo JText::_('OSM_PRICE_REQUIRED'); ?>
									</td>
								</tr>	
								<tr>
									<td class="key">
										<?php echo JText::_('OSM_TRIAL_AMOUNT'); ?>
									</td>
									<td>
										<input type="text" class="inputbox" name="trial_amount" value="<?php echo $this->item->trial_amount; ?>" size="10" />					
									</td>				
								</tr>
								<tr>
									<td class="key">
										<?php echo JText::_('OSM_TRIAL_DURATION'); ?>
									</td>
									<td>
										<input type="text" class="input-mini" name="trial_duration" value="<?php echo $this->item->trial_duration > 0 ? $this->item->trial_duration : ''; ?>"/>
										<?php echo $this->lists['trial_duration_unit']; ?>					
									</td>				
								</tr>
								<tr>
									<td class="key">
										<?php echo JText::_('OSM_NUMBER_PAYMENTS'); ?>
									</td>
									<td>
										<input type="text" class="inputbox" name="number_payments" value="<?php echo $this->item->number_payments; ?>" size="10" />					
									</td>				
								</tr>						
							</table>					
						</fieldset>
					</div>
					<div class="clearfix"></div>	
			</div>								
			<?php 
				if ($translatable)
				{
				?>
					<div class="tab-pane" id="translation-page">
						<ul class="nav nav-tabs">
							<?php
								$i = 0;
								foreach ($this->languages as $language) {						
									$sef = $language->sef;
									?>
									<li <?php echo $i == 0 ? 'class="active"' : ''; ?>><a href="#translation-page-<?php echo $sef; ?>" data-toggle="tab"><?php echo $language->title; ?>
										<img src="<?php echo JUri::root(); ?>media/com_osmembership/flags/<?php echo $sef.'.png'; ?>" /></a></li>
									<?php
									$i++;	
								}
							?>			
						</ul>		
						<div class="tab-content">			
							<?php	
								$i = 0;
								foreach ($this->languages as $language)
								{												
									$sef = $language->sef;
								?>
									<div class="tab-pane<?php echo $i == 0 ? ' active' : ''; ?>" id="translation-page-<?php echo $sef; ?>">													
										<table class="admintable adminform" style="width: 100%;">
											<tr>
												<td class="key">										
													<?php echo  JText::_('OSM_TITLE'); ?>
												</td>
												<td>
													<input class="input-xlarge" type="text" name="title_<?php echo $sef; ?>" id="title_<?php echo $sef; ?>" size="" maxlength="250" value="<?php echo $this->item->{'title_'.$sef}; ?>" />
												</td>								
											</tr>
                                            <tr>
                                                <td class="key">
                                                    <?php echo  JText::_('OSM_ALIAS'); ?>
                                                </td>
                                                <td>
                                                    <input class="input-xlarge" type="text" name="alias_<?php echo $sef; ?>" id="title_<?php echo $sef; ?>" size="" maxlength="250" value="<?php echo $this->item->{'alias_'.$sef}; ?>" />
                                                </td>
                                            </tr>
                                            <tr>
												<td class="key">
													<?php echo JText::_('OSM_SHORT_DESCRIPTION'); ?>
												</td>
												<td>
													<?php echo $editor->display( 'short_description_'.$sef,  $this->item->{'short_description_'.$sef} , '100%', '250', '75', '10' ) ; ?>
												</td>
											</tr>
											<tr>
												<td class="key">
													<?php echo JText::_('OSM_DESCRIPTION'); ?>
												</td>
												<td>
													<?php echo $editor->display( 'description_'.$sef,  $this->item->{'description_'.$sef} , '100%', '250', '75', '10' ) ; ?>
												</td>
											</tr>
											<tr>
												<td class="key">													
			            							<?php echo JText::_('OSM_PLAN_SUBSCRIPTION_FORM_MESSAGE'); ?>			            																												
												</td>
												<td>
													<?php echo $editor->display( 'subscription_form_message_'.$sef,  $this->item->{'subscription_form_message_'.$sef} , '100%', '250', '75', '10' ) ; ?>
												</td>												
											</tr>				
										</table>
									</div>										
								<?php				
									$i++;		
								}
							?>
						</div>								
					</div>
				<?php		
				}
				if (count($this->plugins)) 
				{
					$count = 0 ;
					foreach ($this->plugins as $plugin) {						
						$form = $plugin['form'] ;
						$count++ ;
					?>
						<div class="tab-pane" id="tab_<?php echo $count; ?>">
							<?php 
								echo $form ;
							?>
						</div>
					<?php							
					}
				}
			?>
		</div>		
	</div>
	<div class="clearfix"></div>
	<?php echo JHtml::_( 'form.token' ); ?>
	<input type="hidden" name="option" value="com_osmembership" />
	<input type="hidden" name="cid[]" value="<?php echo $this->item->id; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" id="recurring" name="recurring" value="<?php echo (int)$this->item->recurring_subscription;?>" />
	
	<script type="text/javascript">
	(function($){
		$(document).ready(function(){
			$('.osm-waring').hide();
			if($('#recurring').val() == 1 && $('#price').val() <= 0)
				$('.osm-waring').slideDown();
			var countRuler = '<?php echo count($this->upgradeRules); ?>';
			$('#add-rule').click(function(){
				var html = '<tr id="rule_' + countRuler + '">';
				
					html += '<td><select class="inputbox input-medium" name="to_plan_id[]">';
					html += '<option value="0"><?php echo JText::_('OSM_TO_PLAN'); ?></option>';
					<?php
					for ($i = 0; $i < count($this->plans); $i++) 
					{
						$plan = $this->plans[$i];						
					?>
					html += '<option value="<?php echo $plan->id; ?>"><?php echo $plan->title; ?></option>';
					<?php
					 } 
					?>
				    html += '</select></td>';
				    html += '<td>';
					html += '<input type="text" value=" " maxlength="250" size="10" name="upgrade_price[]" class="input-mini">';
				    html += '</td>';
					html += '<td style="text-align: center; vertical-align: middle;"><select class="inputbox input-mini" name="rule_published[]">';
					html += '<option value="1"><?php echo JText::_('OSM_YES'); ?></option>';
					html += '<option value="0" selected="selected"><?php echo JText::_('OSM_NO'); ?></option>';
					html += '</select></td>';
				    html += '<td>';
					html += '<input type="button" style="margin-bottom: 10px" value="Remove" id="rule_' + countRuler + '" class="btn btn-small btn-danger" onclick="removeRule('+countRuler+')" >';
				    html += '</td>';
				    html += '</tr>';
				$('#upgrade-rule').append(html);
				countRuler ++;
			});
			//validate recuring
			$('[name^=recurring_subscription]').click(function(){
				var price = $('#price').val();
				var recuringValue = $(this).val();
				if(recuringValue == 1 && price <= 0){
					$('.osm-waring').slideDown();
					$('#price').focus();
				}else{
					$('.osm-waring').slideUp();
				}
			})
		})
	})(jQuery)
	//remove rule plan
	function removeRule(rowIndex) {
		jQuery('#rule_'+ rowIndex).remove();
	}
	</script>
</form>