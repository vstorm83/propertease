<?php 
/**
 * @version		1.6.8
 * @package		Joomla
 * @subpackage	Membership Pro
 * @author  Tuan Pham Ngoc
 * @copyright	Copyright (C) 2012 - 2014 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die ;
?>
<table class="os_table" width="100%">				
	<tr>			
		<td class="title_cell" width="35%">
			<?php echo  JText::_('OSM_PLAN') ?>
		</td>
		<td class="field_cell">
			<?php echo $planTitle;?>
		</td>
	</tr>
	<?php
		if (isset($username))
        {
		?>
			<tr>			
				<td class="title_cell" width="35%">
					<?php echo  JText::_('OSM_USERNAME') ?>
				</td>
				<td class="field_cell">
					<?php echo $username; ?>
				</td>
			</tr>	
		<?php
		}
        if (isset($password))
        {
        ?>
            <tr>
                <td class="title_cell" width="35%">
                    <?php echo  JText::_('OSM_PASSWORD') ?>
                </td>
                <td class="field_cell">
                    <?php echo $password; ?>
                </td>
            </tr>
        <?php
        }
	?>	
	<tr>
		<td class="title_cell">
			<?php echo JText::_('OSM_SUBSCRIPTION_START_DATE'); ?>
		</td>
		<td class="field_cell">
			<?php echo JHtml::_('date', $row->from_date, $config->date_format, null); ?>
		</td>
	</tr>				
	<tr>
		<td class="title_cell">
			<?php echo JText::_('OSM_SUBSCRIPTION_END_DATE'); ?>
		</td>
		<td class="field_cell">
			<?php 
				if ($lifetimeMembership)
				{
					echo JText::_('OSM_LIFETIME');	
				}
				else 
				{
					echo JHtml::_('date', $row->to_date, $config->date_format, null);
				}
			?>			
		</td>
	</tr>
	<?php	
	$fields = $form->getFields();
	foreach ($fields as $field)
	{
		switch (strtolower($field->type))
		{
			case 'heading' :
				?>
					<tr>
						<td colspan="2"><h3 class="osm-heading"><?php echo JText::_($field->title) ; ?></h3></td>
					</tr>
	    									
	    			<?php	
	    			break ;
	    		case 'message' :
	    			?>
	    				<tr>
	    					<td colspan="2">
	    						<p class="osm-message"><?php echo $field->description ; ?></p>
	    					</td>
	    				</tr>	    						    				    			
		    			<?php						
	    			break ;
	    		default:
	    			?>
	    			<tr>
	    				<td class="title_cell">
	    					<?php echo JText::_($field->title); ?>	
	    				</td>
	    				<td class="field_cell">
	    					<?php echo $field->value; ?>
	    				</td>
	    			</tr>						
	    			<?php			    			
	    			break;			
	    	}									
	    }							
		if ($row->gross_amount > 0) 
		{
		?>
			<tr>
				<td class="title_cell">
					<?php echo JText::_('OSM_PRICE'); ?>
				</td>
				<td>
					<?php echo OSMembershipHelper::formatCurrency($row->amount, $config); ?>
				</td>
			</tr>
			<?php
				if ($row->discount_amount > 0)
				{
				?>
					<tr>
						<td class="title_cell">
							<?php echo JText::_('OSM_DISCOUNT'); ?>
						</td>
						<td>
							<?php echo OSMembershipHelper::formatCurrency($row->discount_amount, $config); ?>
						</td>
					</tr>
				<?php
				}
				if ($row->tax_amount > 0)
				{
				?>
					<tr>
						<td class="title_cell">
							<?php echo JText::_('OSM_TAX'); ?>
						</td>
						<td>
							<?php echo OSMembershipHelper::formatCurrency($row->tax_amount, $config); ?>
						</td>
					</tr>
				<?php
				}
				if ($row->payment_processing_fee > 0)
				{
				?>
					<tr>
						<td class="title_cell">
							<?php echo JText::_('OSM_PAYMENT_FEE'); ?>
						</td>
						<td>
							<?php echo OSMembershipHelper::formatCurrency($row->payment_processing_fee, $config); ?>
						</td>
					</tr>
				<?php
				}
				if ($row->discount_amount > 0 || $row->tax_amount > 0 || $row->payment_processing_fee > 0)
				{
				?>
					<tr>
						<td class="title_cell">
							<?php echo JText::_('OSM_GROSS_AMOUNT'); ?>
						</td>
						<td>
							<?php echo OSMembershipHelper::formatCurrency($row->gross_amount, $config); ?>
						</td>
					</tr>
				<?php
				}
			?>
			<tr>
				<td class="title_cell">
					<?php echo JText::_('OSM_PAYMENT_OPTION'); ?>
				</td>					
				<td class="field_cell">
					<?php
						$method = os_payments::loadPaymentMethod($row->payment_method) ;
						if ($method)
						{
							echo JText::_($method->title);
						}							
					?>					
				</td>
			</tr>
			<tr>
				<td class="title_cell">
					<?php echo JText::_('OSM_TRANSACTION_ID'); ?>
				</td>
				<td class="field_cell">
					<?php echo $row->transaction_id ; ?>
				</td>
			</tr>
		<?php    
			if ($toAdmin && ($row->payment_method == 'os_creditcard')) 
			{
			?>
			<tr>
				<td class="title_cell">
					<?php echo JText::_('OSM_LAST_4DIGITS'); ?>
				</td>
				<td class="field_cell">
					<?php echo $last4Digits; ?>
				</td>
			</tr>
			<?php	
			}	
		}		
	?>														
</table>	