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
$ordering = ($this->lists['order'] == 'a.ordering');
JToolBarHelper::custom('subscribers.export', 'download', 'download', 'Export Subscribers', false);
$cols = 9 ;
?>
<form action="index.php?option=com_osmembership&view=subscribers" method="post" name="adminForm" id="adminForm">
<table width="100%">
<tr>
	<td align="left">
		<?php echo JText::_( 'OSM_FILTER' ); ?>:
		<input type="text" name="search" id="search" value="<?php echo $this->state->search;?>" class="text_area search-query" onchange="document.adminForm.submit();" />		
		<button onclick="this.form.submit();" class="btn"><?php echo JText::_( 'OSM_GO' ); ?></button>
		<button onclick="document.getElementById('search').value='';this.form.submit();" class="btn"><?php echo JText::_( 'OSM_RESET' ); ?></button>		
	</td>
	<td align="right">
		<?php echo $this->lists['plan_id']; ?>
		<?php echo $this->lists['subscription_type']; ?>
		<?php echo $this->lists['published']; ?>
	</td>	
</tr>
</table>
<div id="editcell">
	<table class="adminlist table table-striped">
	<thead>
		<tr>
			<th width="20">
				<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
			</th>
			<th class="title" style="text-align: left;">
				<?php echo JHtml::_('grid.sort',  JText::_('OSM_FIRSTNAME'), 'a.first_name', $this->lists['order_Dir'], $this->lists['order'] ); ?>				
			</th>											
			<th class="title" style="text-align: left;">
				<?php echo JHtml::_('grid.sort',  JText::_('OSM_LASTNAME'), 'a.last_name', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>										
			<th class="title" style="text-align: left;">
				<?php echo JHtml::_('grid.sort',  JText::_('OSM_PLAN'), 'b.title', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th class="title center">
				<?php echo JHtml::_('grid.sort',  JText::_('OSM_ACTIVATE_TIME'), 'a.from_date', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th class="title center">
				<?php echo JHtml::_('grid.sort',  JText::_('OSM_CREATED_DATE'), 'a.created_date', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th width="10%">
				<?php echo JHtml::_('grid.sort',  JText::_('OSM_GROSS_AMOUNT'), 'a.gross_amount', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th width="8%" class="center">
				<?php echo JHtml::_('grid.sort',  JText::_('OSM_SUBSCRIPTION_STATUS'), 'a.published', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<?php 
				if ($this->config->auto_generate_membership_id) {
					$cols++ ;
				?>
					<th width="8%" class="center">
						<?php echo JHtml::_('grid.sort',  JText::_('OSM_MEMBERSHIP_ID'), 'a.membership_id', $this->lists['order_Dir'], $this->lists['order'] ); ?>
					</th>
				<?php	
				}
				if ($this->config->activate_invoice_feature) {
					$cols++ ;
				?>
					<th width="8%">
						<?php echo JHtml::_('grid.sort',  JText::_('OSM_INVOICE_NUMBER'), 'a.invoice_number', $this->lists['order_Dir'], $this->lists['order'] ); ?>
					</th>
				<?php	
				}  
			?>
			
			<th width="2%">
				<?php echo JHtml::_('grid.sort',  JText::_('OSM_ID'), 'a.id', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>													
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="<?php echo $cols ; ?>">
				<?php echo $this->pagination->getListFooter(); ?>
			</td>
		</tr>
	</tfoot>
	<tbody>
	<?php
	$k = 0;
	for ($i=0, $n=count( $this->items ); $i < $n; $i++)
	{
		$row = $this->items[$i];		
		$link 	= JRoute::_( 'index.php?option=com_osmembership&task=subscriber.edit&cid[]='. $row->id);		
		$checked 	= JHtml::_('grid.id',   $i, $row->id );								
		$accountLink = 'index.php?option=com_users&task=user.edit&id='.$row->user_id ;
		?>
		<tr class="<?php echo "row$k"; ?>">
			<td>
				<?php echo $checked; ?>
			</td>	
			<td>																			
				<a href="<?php echo $link; ?>"><?php echo $row->first_name ; ?></a>				
			</td>
			<td>																			
				<?php echo $row->last_name ; ?>
				<?php
					if ($row->username) {
					?>
						<a href="<?php echo $accountLink; ?>" title="View Profile">&nbsp;(<strong><?php echo $row->username ; ?>)</strong></a>
					<?php	
					}
				?>				
			</td>
			<td>
				<a href="<?php echo JRoute::_('index.php?option=com_osmembership&task=plan.edit&cid[]='.$row->plan_id); ?>" target="_blank"><?php echo $row->plan_title ; ?></a>				
			</td>																						
			<td class="center">
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
			<td class="center">
				<?php echo JHtml::_('date', $row->created_date, $this->config->date_format.' H:i:s'); ?>
			</td>
			<td class="center">
				<?php echo $this->config->currency_symbol.number_format($row->gross_amount, 2) ; ?>
			</td>
			<td class="center">
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
				if ($this->config->auto_generate_membership_id) {
				?>
					<td class="center">
						<?php echo $row->membership_id ; ?>
					</td>
				<?php	
				}
				if ($this->config->activate_invoice_feature) {
				?>
					<td class="center">
						<?php 
							if ($row->invoice_number)
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
			<td class="center">
				<?php echo $row->id; ?>
			</td>
		</tr>		
		<?php
		$k = 1 - $k;
	}
	?>
	</tbody>
	</table>
	</div>
	<input type="hidden" name="option" value="com_osmembership" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />	
	<?php echo JHtml::_( 'form.token' ); ?>			
</form>