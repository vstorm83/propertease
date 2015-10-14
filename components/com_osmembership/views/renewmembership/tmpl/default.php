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
?>
<div id="osm-profile-page" class="row-fluid osm-container">
<h1 class="osm-page-title"><?php echo JText::_('OSM_RENREW_MEMBERSHIP'); ?></h1>
<p class="osm-description"><?php echo JText::_('OSM_RENREW_MEMBERSHIP_DESCRIPTION'); ?></p>
<form action="<?php echo JRoute::_('index.php?option=com_osmembership&task=process_renew_membership&Itemid='.$this->Itemid); ?>" method="post" name="osm_form_renew" id="osm_form_renew" autocomplete="off" class="form form-horizontal">			
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
</form>
<script type="text/javascript">
	OSM.jQuery(function($){
		$(document).ready(function()
		{
			OSMVALIDATEFORM("osm_form_renew");
		})
	});
</script>
</div>