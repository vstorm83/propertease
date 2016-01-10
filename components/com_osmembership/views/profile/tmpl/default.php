<?php
/**
 * @version        1.6.8
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2014 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
// no direct access
defined( '_JEXEC' ) or die ;
$db = JFactory::getDbo();
OSMembershipHelperJquery::validateForm();
$selectedState = '';
$fields = $this->form->getFields();

?>

<script type="text/javascript">
	var siteUrl = '<?php echo OSMembershipHelper::getSiteUrl();  ?>';
	
jQuery(document).ready(function(){
		jQuery( "div#osm-profile-page input" ).addClass( "form-control" );
		
		
		var hash = document.location.hash;
		var prefix = "";
		if (hash) {
			jQuery('.nav-tabs a[href='+hash.replace(prefix,"")+']').tab('show');
		} 
		
		// Change hash for page-reload
		jQuery('.nav-tabs a').on('shown.bs.tab', function (e) {
			window.location.hash = e.target.hash.replace("#", "#" + prefix);
		});
		
		
	});
	Dropzone.options.myAwesomeDropzone = {
  	init: function() {
		this.on("complete", function(file) { 
			location.reload(true); 
		
		});
  	}
	
};

</script>
<!-- Modal -->
 <!-- pop up -->
                                    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                          <div class="modal-dialog" role="document" style="width: 400px;">
                                            <div class="modal-content" style="padding:20px;">
                                              
                                                <button type="button" class="close pull-left" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                              <div class="pull-right"><img src="./templates/propertease/images/snapshot.png"></div>
                                              <div class="modal-body" style="height: auto;">
                                               <div id="pop-up-avatar">
											   <?php
												if (isset($fields['osm_avatar']))
												{
													echo '<img style="max-width:50px;" src="'.JURI::base().'media/com_osmembership/upload/'.OSMembershipHelper::getOriginalFilename($fields['osm_avatar']->value).'"/>';
													
													
												}else{
												?>
												<div class="avatar-popup" style="max-height:100px;overflow:hidden;">
												<img src="./templates/propertease/images/profile.png" style="max-width:50px;">
												</div>
												<?php
												}
												?>
                                                <small>Current Avatar</small>
                                                </div>
                                               <form action="index.php" method="post" name="osm_form" id="myAwesomeDropzone" autocomplete="off" enctype="multipart/form-data" class="form form-horizontal dropzone">
                                              
                                                <input type="hidden" name="option" value="com_osmembership" />
                                                <input type="hidden" name="cid[]" value="<?php echo $this->item->id; ?>" />
                                                <input type="hidden" name="task" value="update_profile" />
                                                <input type="hidden" name="Itemid" value="<?php echo $this->Itemid; ?>" />
                                              		
                                                    
                                                </form>
                                                <div id="manual_upload_section">
                                                    <span>or</span>
                                                    <form action="index.php" method="post" id="osm_form_avatar" name="osm_form" autocomplete="off" enctype="multipart/form-data" class="form form-horizontal">
                                                  
                                                    <input type="hidden" name="option" value="com_osmembership" />
                                                    <input type="hidden" name="cid[]" value="<?php echo $this->item->id; ?>" />
                                                    <input type="hidden" name="task" value="update_profile" />
                                                    <input type="hidden" name="Itemid" value="<?php echo $this->Itemid; ?>" />
                                                    <div onclick="jQuery('#osm_avatar2').trigger('click');">
                                                    <img src="./templates/propertease/images/select-avatar.png" >
                                                    </div>
                                            <input onchange="jQuery('#osm_form_avatar').submit()" type="file" name="osm_avatar" id="osm_avatar2" value="" />
                                                   
                                                    
                                                </form>
                                                </div>
                                              </div>
                                              
                                            </div>
                                          </div>
                                        </div>
                                        <!-- pop up -->


<div id="osm-profile-page" class="row-fluid osm-container">
    <section>
    	<div class="container">
        
        
            <div class="tab-content">
           		<div class="tab-pane active" id="profile-page">
                <form action="index.php" method="post" name="osm_form" id="osm_form" autocomplete="off" enctype="multipart/form-data" class="form form-horizontal">
                
                    <div class="page-header">
                        <h1><span class="light">Account</span> Basics</h1>
                        <!--<p class="lead">Bootstrap 3 scaffolding has changed for improved display on mobile devices</p>-->
                    </div>
                    <div class="row row-eq-height">
                        <?php 
                        if ($this->item->user_id) 
                        {
                        ?> 
                        <div class="col-lg-3">
                            
                                
                                    <div class="inner-addon right-addon">
                                        <p>
                                        <input type="text" class="username" placeholder="Enter your Username" value="<?php echo $this->item->username; ?>" disabled="disabled" />
                                        </p>
                                    </div>
                                    <div class="inner-addon right-addon">
                                        <p>
                                        <input type="password" id="password" name="password"  class="password" placeholder="Password"  />
                                        </p>
                                    </div>
                                
                            
                        </div>
                        <div class="col-lg-1">
                                <div class="arrow_pointer"></div>
                        </div>
                        <?php	
                        }#end of user_id if condition	
                        
                        $fields = $this->form->getFields();
                        ?>
                        <div class="col-lg-3">
                                <div class="inner-addon right-addon">
                                <?php
                                if (isset($fields['first_name']))
                                {
                                    echo "<p>".$fields['first_name']->input."</p>";
                                }
                                ?>
                                </div>
                                <div class="inner-addon right-addon">
                                 <?php
                                if (isset($fields['first_name']))
                                {
                                    echo "<p>".$fields['last_name']->input."</p>";
                                }
                                ?>
                                </div>
                                
                        </div>
                        <div class="col-lg-1">
                                <div class="arrow_pointer"></div>
                        </div>
                        
                        
                        <div class="col-lg-4">
                                <div class="inner-addon right-addon">
                                    <h1 class="change-avatar"><span class="light">Change Avatar</span></h1>
                                    <?php
                                    if (isset($fields['osm_avatar']))
                                    {
                                        //echo $fields['osm_avatar']->input;
										$str='<div class="avatar" title="Click to Change" style="max-height:100px;overflow:hidden;" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal"><img style="max-width:100px;" src="'.JURI::base().'media/com_osmembership/upload/'.OSMembershipHelper::getOriginalFilename($fields['osm_avatar']->value).'"/></div>';
										echo $str;
										?>
                                       
                                        <?php
                                    }else{
										?>
                                         <div class="avatar" data-target="#myModal" data-toggle="modal" style="max-height:100px;overflow:hidden;">
                                        <img src="./templates/propertease/images/profile.png" style="max-width:100px;">
                                        </div>
                                        <?php
										
									}
                                    ?>
                                   
                                </div>
                        </div>
                    </div>
                    
                    
                    <div class="page-header">
                        <h1><span class="light">Here`s your</span> Profile</h1>
                        <!--<p class="lead">Bootstrap 3 scaffolding has changed for improved display on mobile devices</p>-->
                    </div>
                    
                    <div class="row row-eq-height">
                       <div class="col-lg-3">
                                <div class="inner-addon right-addon">
                                <?php
                                if (isset($fields['organization']))
                                {
                                    echo "<p>".$fields['organization']->input."</p>";
                                }
                                ?>
                                </div>
                                <div class="inner-addon right-addon">
                                 <?php
                                if (isset($fields['address']))
                                {
                                    echo "<p>".$fields['address']->input."</p>";
                                }
                                ?>
                                </div>
                                
                        </div>
                        <div class="col-lg-1">
                                <div class="arrow_pointer"></div>
                        </div>
                        <div class="col-lg-3">
                                <div class="inner-addon right-addon">
                                <?php
                                if (isset($fields['city']))
                                {
                                    echo "<p>".$fields['city']->input."</p>";
                                }
                                ?>
                                </div>
                                <div class="inner-addon right-addon">
                                 <?php
                                if (isset($fields['zip']))
                                {
                                    echo "<p>".$fields['zip']->input."</p>";
                                }
                                ?>
                                </div>
                                
                        </div>
                        <div class="col-lg-1">
                                <div class="arrow_pointer"></div>
                        </div>
                        <div class="col-lg-3">
                                <div class="inner-addon right-addon">
                                <?php
                                if (isset($fields['country']))
                                {
                                    echo "<p>".$fields['country']->input."</p>";
                                }
                                ?>
                                </div>
                                <div class="inner-addon right-addon">
                                 <?php
                                if (isset($fields['state']))
                                {
                                    $selectedState = $fields['state']->value;
                                    foreach ($fields as $field)
                                    {  
                                        if($field->type=="State")
                                        echo $field->getControlGroup();    						    										
                                    }
                                }
                                
                                
                                ?>
                                </div>
                                
                        </div>
                        <div class="col-lg-1"><p>&nbsp;</p></div>
                    </div>
                    
                    <div class="page-header">
                        <h1><span class="light">More</span> Details</h1>
                        <!--<p class="lead">Bootstrap 3 scaffolding has changed for improved display on mobile devices</p>-->
                    </div>
                    <div class="row row-eq-height">
                        <div class="col-lg-3">
                                <div class="inner-addon right-addon">
                                <?php
                                if (isset($fields['phone']))
                                {
                                    echo "<p>".$fields['phone']->input."</p>";
                                }
                                ?>
                                </div>
                                <div class="inner-addon right-addon">
                                 <?php
                                if (isset($fields['email']))
                                {
                                    echo "<p>".$fields['email']->input."</p>";
                                }
                                ?>
                                </div>
                                
                        </div>
                        <div class="col-lg-1">
                                <div class="arrow_pointer"></div>
                        </div>
                        <div class="col-lg-3">
                                <div class="inner-addon right-addon">
                                <?php
                                if (isset($fields['osm_referrer']))
                                {
                                    echo "<p>".$fields['osm_referrer']->input."</p>";
                                }
                                ?>
                                </div>
                                <div class="inner-addon right-addon">
                                 <?php
                                if (isset($fields['osm_industry']))
                                {
                                    echo "<p>".$fields['osm_industry']->input."</p>";
                                }
                                ?>
                                </div>
                                
                        </div>
                        <div class="col-lg-1">
                                <div class="arrow_pointer"></div>
                        </div>
                        <div class="col-lg-3">
                                <div class="inner-addon right-addon">
                                    <input type="submit" id="submit" value=""/>
                                </div>
                        </div>
                                    
                        <?php echo JHtml::_( 'form.token' ); ?>				
            
                    </div>
                <input type="hidden" name="option" value="com_osmembership" />
                <input type="hidden" name="cid[]" value="<?php echo $this->item->id; ?>" />
                <input type="hidden" name="task" value="update_profile" />
                <input type="hidden" name="Itemid" value="<?php echo $this->Itemid; ?>" />
              </form>
                </div><!-- end of profile-page -->
                <div class="tab-pane" id="my-subscriptions-page">
                    <table class="table table-bordered table-striped">
                        <thead>		
                            <tr>					
                                <th>
                                    <?php echo JText::_('OSM_PLAN') ?>
                                </th>							
                                <th width="25%" class="center">
                                    <?php echo JText::_('OSM_ACTIVATE_TIME') ; ?>
                                </th>						
                                <th width="10%" class="center">
                                    <?php echo JText::_('OSM_SUBSCRIPTION_STATUS'); ?>
                                </th>																								
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $plans = $this->plans;
                                foreach($plans as $plan)
                                {
                                ?>
                                <tr>
                                    <td>
                                        <?php echo $plan->title; ?>
                                    </td>
                                    <td class="center">
                                        <strong><?php echo JHtml::_('date', $plan->subscription_from_date, $this->config->date_format, null); ?></strong> <?php echo JText::_('OSM_TO'); ?>				
                                        <strong>
                                            <?php 
                                                if ($plan->lifetime_membership)
                                                {
                                                    echo JText::_('OSM_LIFETIME');	
                                                }
                                                else 
                                                {
                                                    echo JHtml::_('date', $plan->subscription_to_date, $this->config->date_format, null);
                                                }
                                            ?>					
                                        </strong>									
                                    </td>
                                    <td class="center">
                                        <?php
                                            switch ($plan->subscription_status)
                                            {
                                                 case 0 :
                                                    echo JText::_('OSM_PENDING');
                                                    break ;
                                                case 1 :
                                                    echo JText::_('OSM_ACTIVE');
                                                    break ;
                                                case 2 :
                                                    echo JText::_('OSM_EXPIRED');
                                                    break ;
                                                default:
                                                    echo JText::_('OSM_CANCELLED');
                                                    break;
                                                    
                                            }
                                        ?>
                                    </td>
                                </tr>							
                                <?php	
                                }						 
                            ?>
                        </tbody>
                    </table>	
                </div><!-- my-subscriptions-page -->
                <div class="tab-pane" id="subscription-history-page">
                    <table class="table table-bordered table-striped">
                        <thead>		
                            <tr>					
                                <th>
                                    <?php echo JText::_('OSM_PLAN') ?>
                                </th>		
                                <th width="20%">
                                    <?php echo JText::_('OSM_SUBSCRIPTION_DATE') ; ?>
                                </th>					
                                <th width="20%">
                                    <?php echo JText::_('OSM_ACTIVATE_TIME') ; ?>
                                </th>
                                <th width="14%" class="right">
                                    <?php echo JText::_('OSM_GROSS_AMOUNT') ; ?>
                                </th>
                                <th>
                                    <?php echo JText::_('OSM_SUBSCRIPTION_STATUS'); ?>
                                </th>						
                                <?php 
                                    if ($this->config->activate_invoice_feature) 
                                    {								
                                    ?>
                                        <th style="text-align: center;">
                                            <?php echo JText::_('OSM_INVOICE_NUMBER') ; ?>
                                        </th>
                                    <?php	
                                    }
                                ?>												
                            </tr>
                        </thead>
                        <tbody>
                        <?php										
                            for ($i = 0 , $n = count($this->items) ; $i < $n ; $i++) 
                            {
                                $row = $this->items[$i] ;													
                                $link = JRoute::_('index.php?option=com_osmembership&view=subscription&id='.$row->id.'&Itemid='.$this->Itemid);						
                            ?>
                                <tr>					
                                    <td>
                                        <a href="<?php echo $link; ?>"><?php echo $row->plan_title; ?></a>
                                    </td>		
                                    <td class="center">
                                        <?php echo JHtml::_('date', $row->created_date, $this->config->date_format); ?>
                                    </td>									
                                    <td align="center">
                                        <strong><?php echo JHtml::_('date', $row->from_date, $this->config->date_format); ?></strong> To
                                        <strong>
                                            <?php
                                            if ($row->lifetime_membership)
                                            {
                                                echo JText::_('OSM_LIFETIME');
                                            }
                                            else
                                            {
                                                echo JHtml::_('date', $row->to_date, $this->config->date_format);
                                            }
                                            ?>
                                        </strong>
                                    </td>
                                    <td class="right">
                                        <?php echo $this->config->currency_symbol.number_format($row->gross_amount, 2) ; ?>
                                    </td>
                                    <td>
                                        <?php                                
                                            switch ($row->published)
                                            {
                                                case 0 :
                                                    echo JText::_('OSM_PENDING');
                                                    break ;
                                                case 1 :
                                                    echo JText::_('OSM_ACTIVE');
                                                    break ;
                                                case 2 :
                                                    echo JText::_('OSM_EXPIRED');
                                                    break ;
                                                case 3 :
                                                    echo JText::_('OSM_CANCELLED_PENDING');
                                                    break ;
                                                case 4 :
                                                    echo JText::_('OSM_CANCELLED_REFUNDED');
                                                    break ;
                                            }                                
                                        ?>
                                    </td>							
                                    <?php
                                        if ($this->config->activate_invoice_feature) 
                                        {
                                        ?>
                                            <td class="center">
                                                <?php
                                                    if (OSMembershipHelper::needToCreateInvoice($row))
                                                    {
                                                    ?>
                                                        <a href="<?php echo JRoute::_('index.php?option=com_osmembership&task=download_invoice&id='.$row->id); ?>" title="<?php echo JText::_('OSM_DOWNLOAD'); ?>"><?php echo OSMembershipHelper::formatInvoiceNumber($row, $this->config) ; ?></a>
                                                    <?php	
                                                    }											 
                                                ?>										
                                            </td>							
                                        <?php	
                                        } 
                                    ?>
                                    
                                </tr>
                            <?php	
                            }
                            ?>
                            </tbody>
                            <?php			
                        ?>					
                    </table>			
                </div>	<!-- subscription-history-page -->
                <div class="tab-pane" id="upgrade-page">
                    <?php 
                        if ($this->canRenew)
                        {
                        ?>
                        <form action="<?php echo JText::_('index.php?option=com_osmembership&task=process_renew_membership&Itemid='.$this->Itemid); ?>" method="post" name="osm_form_renew" id="osm_form_renew" autocomplete="off" class="form form-horizontal">
                            <h2 class="osm-form-heading"><?php echo JText::_('OSM_RENEW_MEMBERSHIP'); ?></h2>			
                            <ul class="osm-renew-options">
                                <?php 
                                    $renewOptionCount = 0;			
                                    foreach ($this->planIds as $planId)
                                    {
                                        $sql = 'SELECT * FROM #__osmembership_plans WHERE id='.$planId;
                                        $db->setQuery($sql);
                                        $plan = $db->loadObject();
                                        if ($plan->recurring_subscription || !$plan->enable_renewal)
                                        {
                                            continue;
                                        }				
                                        $sql = 'SELECT * FROM #__osmembership_renewrates WHERE plan_id='.$planId;
                                        $db->setQuery($sql);
                                        $renewOptions = $db->loadObjectList();									
                                        if (count($renewOptions))
                                        {										
                                            foreach ($renewOptions as $renewOption)
                                            {
                                                $renewOptionCount++;
                                            ?>
                                                <li class="osm-renew-option">
                                                    <input type="radio" class="validate[required] inputbox" id="renew_option_id_<?php echo $renewOptionCount; ?>" name="renew_option_id" value="<?php echo $planId.'|'.$renewOption->id; ?>" />												
                                                    <label for="renew_option_id_<?php echo $renewOptionCount; ?>"><?php JText::printf('OSM_RENEW_OPTION_TEXT', $plan->title, $renewOption->number_days.' '. JText::_('OSM_DAYS'), OSMembershipHelper::formatCurrency($renewOption->price, $this->config)); ?></label>
                                                </li>
                                            <?php	
                                            }	
                                        }
                                        else 
                                        {
                                            $renewOptionCount++;
                                            $length = $plan->subscription_length;
                                            switch ($plan->subscription_length_unit) {
                                                case 'D':
                                                    $text = $length > 1 ? JText::_('OSM_DAYS') : JText::_('OSM_DAY');
                                                    break ;
                                                case 'W' :
                                                    $text = $length > 1 ? JText::_('OSM_WEEKS') : JText::_('OSM_WEEK');
                                                    break ;
                                                case 'M' :
                                                    $text = $length > 1 ? JText::_('OSM_MONTHS') : JText::_('OSM_MONTH');
                                                    break ;
                                                case 'Y' :
                                                    $text = $length > 1 ? JText::_('OSM_YEARS') : JText::_('OSM_YEAR');
                                                    break ;
                                            }					
                                        ?>
                                            <li class="osm-renew-option">
                                                <input type="radio" class="validate[required] inputbox" id="renew_option_id_<?php echo $renewOptionCount; ?>" name="renew_option_id" value="<?php echo $planId;?>" />												
                                                <label for="renew_option_id_<?php echo $renewOptionCount; ?>"><?php JText::printf('OSM_RENEW_OPTION_TEXT', $plan->title, $length.' '.$text, OSMembershipHelper::formatCurrency($plan->price, $this->config)); ?></label>
                                            </li>
                                        <?php	
                                        }
                                    }
                                ?>	
                            </ul>						
                            <div class="form-actions">
                                <input type="submit" class="btn btn-primary" value="<?php echo JText::_('OSM_PROCESS_RENEW'); ?>"/>
                            </div>
                            <input type="hidden" name="cid[]" value="<?php echo $this->item->id; ?>" />
                        </form>
                        <?php	
                        }
                        ?>
                        <form action="<?php echo JRoute::_('index.php?option=com_osmembership&task=process_upgrade_membership&Itemid='.$this->Itemid); ?>" method="post" name="osm_form_update_membership" id="osm_form_update_membership" autocomplete="off" class="form form-horizontal">
                            <?php
                                //We should only allow upgrading from active membership		
                                $sql = 'SELECT DISTINCT plan_id FROM #__osmembership_subscribers WHERE profile_id='.$this->item->id.' AND published=1';
                                $db->setQuery($sql);
                                $planIds = $db->loadColumn();
                                if (!count($planIds))
                                {
                                    $planIds = array(0);
                                }
                                $sql = 'SELECT * FROM #__osmembership_upgraderules WHERE from_plan_id IN ('.implode(',', $planIds).') ORDER BY from_plan_id';
                                $db->setQuery($sql);
                                $upgradeRules = $db->loadObjectList();
                                if (count($upgradeRules))
                                {
                                    $sql = 'SELECT * FROM #__osmembership_plans WHERE published = 1';
                                    $db->setQuery($sql);
                                    $plans = $db->loadObjectList('id');
                                ?>
                                <h2 class="osm-form-heading"><?php echo JText::_('OSM_UPGRADE_MEMBERSHIP'); ?></h2>						
                                    <ul class="osm-upgrade-options">
                                        <?php 
                                            $upgradeOptionCount = 0;
                                            foreach ($upgradeRules as $rule)
                                            {
                                                $upgradeOptionCount++;
                                            ?>
                                                <li class="osm-upgrade-option">
                                                    <input type="radio" class="validate[required]" id="upgrade_option_id_<?php echo $upgradeOptionCount; ?>" name="upgrade_option_id" value="<?php echo $rule->id; ?>" />												
                                                    <label for="upgrade_option_id_<?php echo $upgradeOptionCount; ?>"><?php JText::printf('OSM_UPGRADE_OPTION_TEXT', $plans[$rule->from_plan_id]->title, $plans[$rule->to_plan_id]->title, OSMembershipHelper::formatCurrency($rule->price, $this->config)); ?></label>
                                                </li>
                                            <?php											
                                            }
                                        ?>	
                                    </ul>												
                                    <div class="form-actions">
                                        <input type="submit" class="btn btn-primary" value="<?php echo JText::_('OSM_PROCESS_UPGRADE'); ?>"/>
                                    </div>							
                                <?php	
                                }
                            ?>
                            <input type="hidden" name="cid[]" value="<?php echo $this->item->id; ?>" />
                        </form>
                </div>
            </div><!-- end of tab-content-->
            
                
        </div><!-- end of container -->
    </section>		
</div>		
<div class="clearfix"></div>




<!-- old back up-->



<!-- old back-->
<script type="text/javascript">
	OSM.jQuery(function($){
		$(document).ready(function(){
			OSMVALIDATEFORM("#osm_form");
			OSMVALIDATEFORM("#osm_form_renew");
			OSMVALIDATEFORM("#osm_form_update_membership");
			buildStateField('state', 'country', '<?php echo $selectedState; ?>');
		})
	});
</script>

</div>