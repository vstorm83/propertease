<?php
/**
 * @version		1.6.8
 * @package		Joomla
 * @subpackage	Membership Pro
 * @author  Tuan Pham Ngoc
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined( '_JEXEC' ) or die ;
$selectedState = '';
?>
<script type="text/javascript">
	Joomla.submitbutton = function(pressbutton)
	{
		var form = document.adminForm;
		if (pressbutton == 'subscriber.cancel') {
			Joomla.submitform(pressbutton, form);
			return;				
		} else {
			//Validate the entered data before submitting					
			Joomla.submitform(pressbutton, form);
		}								
	}	
</script>
<form action="index.php" method="post" name="adminForm" id="adminForm" autocomplete="off" enctype="multipart/form-data">
<div class="row-fluid" style="float:left">			
	<table class="admintable adminform">		
		<tr>
			<td class="key">
				<?php echo JText::_('OSM_PLAN'); ?>
			</td>
			<td>
				<?php echo $this->lists['plan_id'] ; ?>
			</td>
		</tr>	
		<?php 
			if (!$this->item->id) 
			{
			?>
				<tr>
					<td class="key">
						<?php echo JText::_('OSM_USERNAME'); ?>
					</td>
					<td>
						<input type="text" name="username" size="20" value="" />
						<?php echo JText::_('OSM_USERNAME_EXPLAIN'); ?>
					</td>
				</tr>
				<tr>
					<td class="key">
						<?php echo JText::_('OSM_PASSWORD'); ?>
					</td>
					<td>
						<input type="password" name="password" size="20" value="" />
					</td>
				</tr>
			<?php	
			}
		?>			
		<tr>
			<td class="key">
				<?php echo JText::_('OSM_USER'); ?>				
			</td>
			<td>
				<?php echo OSMembershipHelper::getUserInput($this->item->user_id, (int) $this->item->id) ; ?>
			</td>
		</tr>
		<?php 
			if ($this->config->auto_generate_membership_id) 
			{
			?>
			<tr>
				<td class="key">
					<?php echo JText::_('OSM_MEMBERSHIP_ID'); ?>				
				</td>
				<td>				
					<input type="text" name="membership_id" value="<?php echo $this->item->membership_id > 0 ? $this->item->membership_id : ''; ?>" class="inputbox" size="20" />
				</td>
			</tr>
			<?php	
			}
			$fields = $this->form->getFields();
			if (isset($fields['state']))
			{
				if ($fields['state']->type == 'State')
				{
					$stateType = 1;
				}
				else 
				{
					$stateType = 0;
				}
				$selectedState = $fields['state']->value;
			}
			foreach ($fields as $field)
			{
				switch (strtolower($field->type))
				{
					case 'heading' :
						?>
		    			<tr><td colspan="2"><h3 class="osm-heading"><?php echo JText::_($field->title) ; ?></h3></td></tr>						
		    			<?php	
		    			break ;
		    		case 'message' :
		    			?>
	    				<tr>
	    					<td colspan="2">
	    						<p class="osm-message">
	    							<?php echo $field->description ; ?>
	    						</p>
	    					</td>
	    				</tr>		    							    				
		    			<?php						
		    			break ;
		    		default:
		    			?>
		    				<tr id="field_<?php echo $field->name; ?>">
		    					<td class="key">
		    						<?php echo JText::_($field->title); ?>
		    					</td>
		    					<td class="controls">
		    						<?php echo $field->input; ?>
		    					</td>
		    				</tr>
		    			<?php
		    			break;			
		    	}									
		    }		
		?>		
		<tr>
			<td class="key">
				<?php echo  JText::_('OSM_CREATED_DATE'); ?>
			</td>
			<td>
				<?php echo JHtml::_('calendar', $this->item->created_date, 'created_date', 'created_date') ; ?>				
			</td>
		</tr>				
		<tr>
			<td class="key">
				<?php echo  JText::_('OSM_SUBSCRIPTION_START_DATE'); ?>
			</td>
			<td>
				<?php echo JHtml::_('calendar', $this->item->from_date, 'from_date', 'from_date') ; ?>				
			</td>
		</tr>
		<tr>
			<td class="key">
				<?php echo  JText::_('OSM_SUBSCRIPTION_END_DATE'); ?>
			</td>
			<td>
				<?php 
					if ($this->item->lifetime_membership)
					{
						echo JText::_('OSM_LIFETIME');	
					}
					else 
					{
						echo JHtml::_('calendar', $this->item->to_date, 'to_date', 'to_date') ;
					}					
				?>				
			</td>
		</tr>
		<tr>
			<td class="key">
				<?php echo  JText::_('OSM_NET_AMOUNT'); ?>
			</td>
			<td>
				<?php echo $this->config->currency_symbol ;  ?><input type="text" class="inputbox" name="amount" value="<?php echo $this->item->amount > 0 ? round($this->item->amount, 2) : ""; ?>" size="7" />				
			</td>
		</tr>
		<?php
            if ($this->item->discount_amount > 0 || !$this->item->id) {
            ?>
          		<tr>
        			<td class="key">
        				<?php echo  JText::_('OSM_DISCOUNT_AMOUNT'); ?>
        			</td>
        			<td>
        				<?php echo $this->config->currency_symbol ;  ?><input type="text" class="inputbox" name="discount_amount" value="<?php echo $this->item->discount_amount > 0 ? round($this->item->discount_amount, 2) : ""; ?>" size="7" />				
        			</td>
        		</tr>  	
            <?php    
            }
            
            if ($this->item->tax_amount > 0 || !$this->item->id) {
            ?>
                 <tr>
                    	<td class="key">
                    			<?php echo  JText::_('OSM_TAX_AMOUNT'); ?>
                    	</td>
                    	<td>
                    			<?php echo $this->config->currency_symbol ;  ?><input type="text" class="inputbox" name="tax_amount" value="<?php echo $this->item->tax_amount > 0 ? round($this->item->tax_amount, 2) : ""; ?>" size="7" />				
                    	</td>
                 </tr>  	
            <?php    
            }
			if ($this->item->payment_processing_fee > 0 || !$this->item->id)
			{
			?>
				<tr>
					<td class="key">
						<?php echo  JText::_('OSM_PAYMENT_FEE'); ?>
					</td>
					<td>
						<?php echo $this->config->currency_symbol ;  ?><input type="text" class="inputbox" name="payment_processing_fee" value="<?php echo $this->item->payment_processing_fee > 0 ? round($this->item->payment_processing_fee, 2) : ""; ?>" size="7" />
					</td>
				</tr>
			<?php
			}
            ?>
            <tr>
            	<td class="key">
                     <?php echo  JText::_('OSM_GROSS_AMOUNT'); ?>
                </td>
                <td>
                     <?php echo $this->config->currency_symbol ;  ?><input type="text" class="inputbox" name="gross_amount" value="<?php echo $this->item->gross_amount > 0 ? round($this->item->gross_amount, 2) : ""; ?>" size="7" />				
                </td>
            </tr>  	              	
		<tr>
			<td class="key">
				<?php echo JText::_('OSM_PAYMENT_METHOD') ?>					
			</td>
			<td>
				<?php echo $this->lists['payment_method'] ; ?>
			</td>
		</tr>		
		<tr>
			<td class="key">
				<?php echo JText::_('OSM_TRANSACTION_ID'); ?>
			</td>
			<td>
				<input type="text" class="inputbox" size="50" name="transaction_id" id="transaction_id" value="<?php echo $this->item->transaction_id ; ?>" />
			</td>
		</tr>
		<tr>
			<td class="key">
				<?php echo JText::_('OSM_SUBSCRIPTION_STATUS'); ?>
			</td>
			<td>
				<?php echo $this->lists['published'] ; ?>
			</td>
		</tr>			
		<?php			
			if ($this->item->payment_method == "os_creditcard") {
				$params = new JRegistry($this->item->params);
			?>
				<tr>
					<td class="key">
						<?php echo JText::_('OSM_FIRST_12_DIGITS_CREDITCARD_NUMBER'); ?>
					</td>
					<td>
						<?php echo $params->get('card_number'); ?>
					</td>
				</tr>	
				<tr>
					<td class="key">
						<?php echo JText::_('AUTH_CARD_EXPIRY_DATE'); ?>
					</td>
					<td>
						<?php echo $params->get('exp_date'); ?>
					</td>
				</tr>		
				<tr>
					<td class="key">
						<?php echo JText::_('AUTH_CVV_CODE'); ?>
					</td>
					<td>
						<?php echo $params->get('cvv'); ?>
					</td>
				</tr>		
			<?php	
			}
		?>
	</table>			
</div>		
<div class="clr"></div>
	<input type="hidden" name="option" value="com_osmembership" />
	<input type="hidden" name="cid[]" value="<?php echo $this->item->id; ?>" />
	<input type="hidden" name="task" value="" />			
	<?php echo JHtml::_( 'form.token' ); ?>			
	<script type="text/javascript">

	var siteUrl = "<?php echo JUri::root(); ?>";
	<?php
	if ($stateType) 
	{
	?>
		function buildStateField(stateFieldId, countryFieldId, defaultState)
		{
			(function($) {
				if($('#' + stateFieldId).length)
				{
					//set state
					if ($('#' + countryFieldId).length)
					{
						var countryName = $('#' + countryFieldId).val();
					}
					else 
					{
						var countryName = '';
					}			
					$.ajax({
						type: 'POST',
						url: siteUrl + 'index.php?option=com_osmembership&task=get_states&country_name='+ countryName+'&field_name='+stateFieldId + '&state_name=' + defaultState,
						success: function(data) {
							$('#field_' + stateFieldId + ' .controls').html(data);
						},
						error: function(jqXHR, textStatus, errorThrown) {						
							alert(textStatus);
						}
					});			
					//Bind onchange event to the country 
					if ($('#' + countryFieldId).length)
					{
						$('#' + countryFieldId).change(function(){
							$.ajax({
								type: 'POST',
								url: siteUrl + 'index.php?option=com_osmembership&task=get_states&country_name='+ $(this).val()+'&field_name=' + stateFieldId + '&state_name=' + defaultState,
								success: function(data) {
									$('#field_' + stateFieldId + ' .controls').html(data);
								},
								error: function(jqXHR, textStatus, errorThrown) {						
									alert(textStatus);
								}
							});
							
						});
					}						
				}//end check exits state
						
			})(jQuery);		
		}
		(function($){
			$(document).ready(function(){							
				buildStateField('state', 'country', '<?php echo $selectedState; ?>');										
			})
		})(jQuery);	
	<?php
	} 
	?>


	(function($){
		populateSubscriberData = (function(id, planId, title){
			$.ajax({
				type : 'POST',
				url : 'index.php?option=com_osmembership&task=get_profile_data&user_id=' + id + '&plan_id=' +planId,
				dataType: 'json',
				success : function(json){
					var selecteds = [];
					for (var field in json)
					{
						value = json[field];
						if ($("input[name='" + field + "[]']").length)
						{
							//This is a checkbox or multiple select
							if ($.isArray(value))
							{
								selecteds = value;
							}
							else
							{
								selecteds.push(value);
							}
							$("input[name='" + field + "[]']").val(selecteds);
						}
						else
						{
							$('#' + field).val(value);
						}
					}
					$('#user_id').val(id);
					$('#user_id_name').val(title);
				}
			})
		});		
	})(jQuery);
	</script>	
</form>