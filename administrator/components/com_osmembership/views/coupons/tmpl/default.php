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
JHtml::_('behavior.tooltip');
?>
<form action="index.php?option=com_osmembership&view=coupons" method="post" name="adminForm" id="adminForm">
<table width="100%">
<tr>
	<td align="left">
		<?php echo JText::_( 'OSM_FILTER' ); ?>:
		<input type="text" name="search" id="search" value="<?php echo $this->state->search;?>" class="text_area search-query" onchange="document.adminForm.submit();" />		
		<button onclick="this.form.submit();" class="btn"><?php echo JText::_( 'OSM_GO' ); ?></button>
		<button onclick="document.getElementById('search').value='';this.form.submit();" class="btn"><?php echo JText::_( 'OSM_RESET' ); ?></button>		
	</td>	
	<td style="text-align: right ;">
		<?php echo $this->lists['plan_id']; ?>
		<?php echo $this->lists['filter_state']; ?>		
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
				<?php echo JHtml::_('grid.sort',  JText::_('OSM_CODE'), 'a.code', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>								
			<th width="15%" class="title" nowrap="nowrap">
				<?php echo JText::_('Discount'); ?>
			</th>									
			<th width="10%" class="title" nowrap="nowrap">
				<?php echo JHtml::_('grid.sort',  JText::_('OSM_TIMES'), 'a.times', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th width="10%" class="title" nowrap="nowrap">
				<?php echo JHtml::_('grid.sort',  JText::_('OSM_USED'), 'a.used', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>	
			<th width="10%" class="title" nowrap="nowrap">
				<?php echo JHtml::_('grid.sort',  JText::_('OSM_VALID_FROM'), 'a.valid_from', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>					
			<th width="10%" class="title" nowrap="nowrap">
				<?php echo JHtml::_('grid.sort',  JText::_('OSM_VALID_TO'), 'a.valid_to', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th width="5%" class="title" nowrap="nowrap">
				<?php echo JHtml::_('grid.sort',  JText::_('OSM_PUBLISHED'), 'a.published', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>																
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="8">
				<?php echo $this->pagination->getListFooter(); ?>
			</td>
		</tr>
	</tfoot>
	<tbody>
	<?php
	$k = 0;	
	for ($i=0, $n=count( $this->items ); $i < $n; $i++)
	{
		$row = &$this->items[$i];
		$link 	= JRoute::_( 'index.php?option=com_osmembership&task=coupon.edit&cid[]='. $row->id );
		$checked 	= JHtml::_('grid.id',   $i, $row->id );				
		$published 	= JHtml::_('grid.published', $row, $i, 'tick.png', 'publish_x.png', 'coupon.' );			
		?>
		<tr class="<?php echo "row$k"; ?>">
			<td>
				<?php echo $checked; ?>
			</td>	
			<td>
				<a href="<?php echo $link; ?>">
					<?php echo $row->code; ?>
				</a>
			</td>
			<td style="text-align: center;">
				<?php echo number_format($row->discount, 2).$this->discountTypes[$row->coupon_type]  ;?>
			</td>										
			<td class="text_center">
				<?php echo $row->times; ?>
			</td>	
			<td class="text_center">
				<?php echo $row->used; ?>
			</td>
			<td class="text_center">
				<?php
					if ($row->valid_from != $this->nullDate && $row->valid_from) {
						echo JHtml::_('date', $row->valid_from, $this->dateFormat, null);
					}
				?>
			</td>	
			<td class="text_center">
				<?php
					if ($row->valid_to != $this->nullDate && $row->valid_to) {
						echo JHtml::_('date', $row->valid_to, $this->dateFormat, null);
					}
				?>
			</td>										
			<td class="text_center">
				<?php echo $published; ?>
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