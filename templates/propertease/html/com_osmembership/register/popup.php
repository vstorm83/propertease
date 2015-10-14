<?php
defined('_JEXEC') or die ;
JHtml::_('behavior.modal', 'a.osm-modal');
$selectedState = '';

$config = $this->config;
$decimals      = isset($config->decimals) ? $config->decimals : 2;
$dec_point     = isset($config->dec_point) ? $config->dec_point : '.';
$thousands_sep = isset($config->thousands_sep) ? $config->thousands_sep : ',';
?>
<script type="text/javascript">
	var siteUrl = '<?php echo OSMembershipHelper::getSiteUrl();  ?>';
</script>
<?php
OSMembershipHelperJquery::validateForm();

if (!$this->userId && $this->config->registration_integration && $this->config->show_login_box_on_subscribe_page) {
// 	$actionUrl = JRoute::_('index.php?option=com_users&task=user.login');
	$actionUrl = 'index.php?option=com_users&task=user.login';	
	$returnUrl = JUri::getInstance()->toString();
?>
<div id="login-popup" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<div class="normal-logo"> </div>
				<button class="btn btn-register">
					Don`t have an <b>account?</b>
				</button>
			</div>
			<div class="modal-body">
				<h3>
					<span class="green blk">Log in</span> to your account
				</h3>											
					<form method="post" action="<?php echo $actionUrl ; ?>" name="osm_login_form" id="osm_login_form" autocomplete="off" class="">
						<div class="form-group">
							<input type="text" name="username" id="username" required class="form-control validate[required]" value="" placeholder=" Enter your Username "/>
						</div>
						<div class="form-group">
							<input type="password" id="password" name="password" required class="form-control validate[required" value="" placeholder="Enter your password"/>
						</div>
						<div class="form-group">
							<div class="radio">
								<label> <input type="radio" /> Remember me
								</label><a href="#">Forgot?</a>
							</div>
					   </div>
					   <div class="form-group">
							<p>
								By logging in, you agree to our <a href="#">Privacy Policy</a>
								and <a href="#">Terms of Use.</a>
							</p>
						</div>
						<div class="form-group last-group">
							<input type="submit" value="LOGIN" class="btn btn-sbm" />
						</div>
						<input type="hidden" name="remember" value="1" />
						<input type="hidden" name="return" value="<?php echo base64_encode($returnUrl) ; ?>" />
						<input type="hidden" name="registration_integration" value="<?php echo $this->config->registration_integration; ?>">
						<?php echo JHtml::_( 'form.token' ); ?>		                	
					</form>
			</div>
		</div>
	</div>
</div>
<?php	
}
?>			
<?php //JRoute::_('index.php?option=com_osmembership&task=process_subscription&Itemid='.$this->Itemid, false, $this->config->use_https ? 1 : 0); ?>
<form method="post" name="os_form" id="os_form" action="<?php echo 'index.php?option=com_osmembership&task=process_subscription&Itemid='.$this->Itemid?>" enctype="multipart/form-data" autocomplete="off">
	<?php
	if (!$this->userId && $this->config->registration_integration)
	{
		$params = JComponentHelper::getParams('com_users');
		$minimumLength = $params->get('minimum_length', 4);
	    ($minimumLength) ? $minSize = ",minSize[$minimumLength]" : $minSize = "";
	    if(version_compare(JVERSION, '3.1.2', 'ge'))
	    {
			$passwordValidation = ',ajax[ajaxValidatePassword]';
		}
		else 
		{
			$passwordValidation = '';
		}
	?>
	<div id="plan-step-1" class="modal fade" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<a class="back" href="#">back</a>
					<ul class="process">
						<li class="visited"><a href="#">1</a></li>
						<li><a href="#">2</a></li>
						<li><a href="#">3</a></li>
						<li><a href="#">4</a></li>
					</ul>
				</div>
				<div class="modal-body">
					<h3>
						Create a <span class="green blk">free</span> a account
					</h3>
					<div class="form-group">	
						<input type="text" name="username" id="username1" class="validate[required,ajax[ajaxUserCall]] form-control user" placeholder=" Enter your Username "
						value="<?php echo JRequest::getVar('username', null,'post'); ?>" size="15" autocomplete="off"/>
					</div>
                    <div class="form-group">	
						<input type="email" name="email" id="email1" class="validate[required,ajax[ajaxUserCall]] form-control mail" placeholder=" Enter your Mail "
						value="<?php echo JRequest::getVar('email', null,'post'); ?>" size="15" autocomplete="off"/>
					</div>
					<div class="form-group">
						<input value="" class="validate[required<?php echo $minSize.$passwordValidation;?>] form-control " type="password" placeholder="Enter your password"
						name="password1" id="password1" autocomplete="off"/>
					</div>
					<div class="form-group">
						<input value="" class="validate[required,equals[password1]] form-control" 
						placeholder="Re-Type your Password" type="password" name="password2" id="password2" />
					</div>
					<div class="form-group last-group">
						<button class="btn btn-sbm" type="submit">NEXT STEP</button>
					</div>
				</div>
			</div>
		</div>
	</div>							
<?php	
    }
    
    $fields = $this->form->getFields();
    if (isset($fields['state']))
    {
    	$selectedState = $fields['state']->value;
    }
?>

<div class="temporary" style="display: none">
	<?php
	    foreach ($fields as $field)
	    {
	    	echo $field->getControlGroup();    						    										
	    }    
	?>
</div>

	<div id="plan-step-2" class="modal fade" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<a class="back" href="#">back</a>
					<ul class="process">
						<li class="visited"><a href="#">1</a></li>
						<li class="visited"><a href="#">2</a></li>
						<li><a href="#">3</a></li>
						<li><a href="#">4</a></li>
					</ul>
				</div>
				<div class="modal-body">
					<h3>
						<span class="green">Fill in</span> your details
					</h3>
					<div class="step2Details">
					</div>						
					<div class="form-group last-group">
						<button class="btn btn-sbm" type="submit">NEXT STEP</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div id="plan-step-3" class="modal fade" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<a class="back" href="#">back</a>
					<ul class="process">
						<li class="visited"><a href="#">1</a></li>
						<li class="visited"><a href="#">2</a></li>
						<li class="visited"><a href="#">3</a></li>
						<li><a href="#">4</a></li>
					</ul>
				</div>
				<div class="modal-body">
					<h3>
						<span class="green">Fill in</span> your details
					</h3>
						<div class="step3Details">							
						</div>						
						<div class="form-group last-group">
							<button class="btn btn-sbm" type="submit">NEXT STEP</button>
						</div>
				</div>
			</div>
		</div>
	</div>

	<div id="plan-step-4" class="modal fade" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<a class="back" href="#">back</a>
					<ul class="process">
						<li class="visited"><a href="#">1</a></li>
						<li class="visited"><a href="#">2</a></li>
						<li class="visited"><a href="#">3</a></li>
						<li class="visited"><a href="#">4</a></li>
					</ul>
				</div>
				<div class="modal-body">					
					<h3>
						<span class="green blk">Review</span> your Order
					</h3>
					<p>
						<span class="blk"><?php echo $this->plan->title?></span> Plan
					</p>
<?php 
		if ($this->plan->recurring_subscription) {
			if ($this->plan->recurring_subscription) {
				if ($this->plan->trial_duration > 0) {
				?>
					<p>
				<?php if ($this->config->currency_position == 0) { ?>
						<span class="light"><?php echo $this->config->currency_symbol;?></span>
						<?php echo number_format($this->plan->trial_amount, $decimals, $dec_point, $thousands_sep)?>
				<?php } else { ?>
						<?php echo number_format($this->plan->trial_amount, $decimals, $dec_point, $thousands_sep)?>
						<span class="light"><?php echo $this->config->currency_symbol;?></span>
				<?php } ?>
					</p>
				<?php if ($this->plan->trial_duration_unit == 'M') { ?>
					<p class="small">One Month</p>				
				<?php 
				} else {
				?>
					<p class="small">One Year</p>
				<?php 
				}
				echo $this->plan->short_description;
					if ($this->config->enable_coupon) {
					?>
					<div class="form-group">
						<label class="control-label">
							<?php echo JText::_('OSM_TRIAL_DURATION_DISCOUNT'); ?>		
						</label>
						<div class="controls">
							<?php 
								if ($this->config->currency_position == 0) 
								{
								?>
									<div class="input-prepend">
										<span class="add-on"><?php echo $this->config->currency_symbol;?></span>
										<?php echo OSMembershipHelper::formatAmount($this->fees['trial_discount_amount'], $this->config); ?>
									</div>
								<?php		
								} 
								else 
								{
								?>
									<div class="input-append">										
										<?php echo OSMembershipHelper::formatAmount($this->fees['trial_discount_amount'], $this->config); ?>
										<span class="add-on"><?php echo $this->config->currency_symbol;?></span>
									</div>
								<?php										
								}
							?>							
						</div>
					</div>								
					<?php	
					}
					if ($this->taxRate > 0) {
						$trialTaxAmount = $this->fees['trial_tax_amount'];
					?>
					<div class="form-group">
						<label class="control-label">
							<?php echo JText::_('OSM_TRIAL_TAX_AMOUNT'); ?>		
						</label>
						<div class="controls">
							<?php
								if ($this->config->currency_position == 0) {
								?>
									<div class="input-prepend">
										<span class="add-on"><?php echo $this->config->currency_symbol;?></span>
										<?php echo OSMembershipHelper::formatAmount($trialTaxAmount, $this->config); ?>
									</div>
								<?php
								} else {
								?>
									<div class="input-append">										
										<?php echo OSMembershipHelper::formatAmount($trialTaxAmount, $this->config); ?>
										<span class="add-on"><?php echo $this->config->currency_symbol;?></span>
									</div>
								<?php										
								}
							?>
						</div>
					</div>									
					<?php	
					} else {
						$trialTaxAmount = 0;
					}
					if ($this->showPaymentFee) {
					?>
					<div class="form-group">
						<label class="control-label">
							<?php echo JText::_('OSM_TRIAL_PAYMENT_FEE'); ?>
						</label>
						<div class="controls">
							<?php
							if ($this->config->currency_position == 0)
							{
							?>
								<div class="input-prepend inline-display">
									<span class="add-on"><?php echo $this->config->currency_symbol;?></span>
									<?php echo OSMembershipHelper::formatAmount($this->fees['trial_payment_processing_fee'], $this->config); ?>
								</div>
							<?php
							}
							else
							{
							?>
								<div class="input-append inline-display">
									<?php echo OSMembershipHelper::formatAmount($this->fees['trial_payment_processing_fee'], $this->config); ?>
									<span class="add-on"><?php echo $this->config->currency_symbol;?></span>
								</div>
							<?php
							}
							?>
						</div>
					</div>
					<?php
					}
					if ($this->config->enable_coupon || $trialTaxAmount > 0 || $this->showPaymentFee) {
					?>
					<div class="form-group">
						<label class="control-label">
							<?php echo JText::_('OSM_GROSS_TRIAL_AMOUNT'); ?>		
						</label>
						<div class="controls">
							<?php 
								if ($this->config->currency_position == 0) {
								?>
									<div class="input-prepend inline-display">
										<span class="add-on"><?php echo $this->config->currency_symbol;?></span>
										<?php echo OSMembershipHelper::formatAmount($this->fees['trial_gross_amount'], $this->config); ?>
									</div>
								<?php		
								} else {
								?>
									<div class="input-append inline-display">										
										<?php echo OSMembershipHelper::formatAmount($this->fees['trial_gross_amount'], $this->config); ?>
										<span class="add-on"><?php echo $this->config->currency_symbol;?></span>
									</div>
								<?php										
								}
							?>
							<div class="explain-text inline-display"><?php echo $this->trialPeriorText; ?></div>
						</div>
					</div>								
					<?php		
					}
				}
    		}
		} else {			
?>
					<p>
<?php 
				if ($this->config->currency_position == 0) { ?>
						<span class="light"><?php echo $this->config->currency_symbol;?></span>
						<?php echo number_format($this->plan->price, $decimals, $dec_point, $thousands_sep)?>
				<?php } else { ?>
						<?php echo number_format($this->plan->price, $decimals, $dec_point, $thousands_sep)?>
						<span class="light"><?php echo $this->config->currency_symbol;?></span>
				<?php } ?>
					</p>
				<?php if ($this->plan->trial_duration_unit == 'M') { ?>
					<p class="small">One Month</p>				
				<?php 
				} else {
				?>
					<p class="small">One Year</p>
				<?php 
				}
				echo $this->plan->short_description;
		}
?>				
					<a class="pay" href="#">Pay via <span class="ico-paypal">paypal</span></a>
					<img id="ajax-loading-animation" src="<?php echo JUri::base();?>media/com_osmembership/ajax-loadding-animation.gif" style="display: none;"/>												
				</div>
			</div>
		</div>
	</div>
								
<?php
	if (count($this->methods) == 1) {
	?>
		<input type="hidden" name="payment_method" value="<?php echo $this->methods[0]->getName(); ?>" />
	<?php	
	}		
?>
	<input type="hidden" name="Itemid" value="<?php echo $this->Itemid; ?>" />
	<input type="hidden" name="plan_id" value="<?php echo $this->plan->id ; ?>" />
	<input type="hidden" name="option" value="com_osmembership" />
	<input type="hidden" name="act" value="<?php echo $this->action ; ?>" />		
	<input type="hidden" name="renew_option_id" value="<?php echo $this->renewOptionId ; ?>" />
	<input type="hidden" name="upgrade_option_id" value="<?php echo $this->upgradeOptionId ; ?>" />
	<input type="hidden" name="show_payment_fee" value="<?php echo (int)$this->showPaymentFee ; ?>" />
	<input type="hidden" name="vat_number_field" value="<?php echo $this->config->eu_vat_number_field ; ?>" />
	<input type="hidden" name="country_base_tax" value="<?php echo $this->countryBaseTax; ?>" />
	<script type="text/javascript">
		OSM.jQuery(function($){
			$(document).ready(function(){
				if($('input[name^=registration_integration]').length)
				{
					OSMVALIDATEFORM("#osm_login_form");
				}
				OSMVALIDATEFORM("#os_form");
				<?php
					if ($this->fees['amount'] == 0)
					{
					?>
						$('.payment_information').css('display', 'none');
					<?php
					}
					if ($this->config->eu_vat_number_field)
					{
					?>
						// Add css class for vat number field
						$('input[name^=<?php echo $this->config->eu_vat_number_field   ?>]').addClass('taxable');
						$('input[name^=<?php echo $this->config->eu_vat_number_field   ?>]').before('<div class="input-prepend"><span class="add-on" id="vat_country_code"><?php echo $this->countryCode; ?></span>');
						$('input[name^=<?php echo $this->config->eu_vat_number_field   ?>]').after('<span class="invalid" id="vatnumber_validate_msg" style="display: none;"><?php echo ' '.JText::_('OSM_INVALID_VATNUMBER'); ?></span></div>');
						$('input[name^=<?php echo $this->config->eu_vat_number_field   ?>]').change(function(){
							calculateSubscriptionFee();
						});
						<?php
						}
					?>
				buildStateField('state', 'country', '<?php echo $selectedState; ?>');
			})
		});
		<?php
			os_payments::writeJavascriptObjects();
		?>		
	</script>		
	<?php echo JHtml::_( 'form.token' ); ?>
</form>