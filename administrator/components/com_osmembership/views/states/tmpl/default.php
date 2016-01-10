<?php
/**
 * @version        	1.6.6
 * @package        	Joomla
 * @subpackage		Event Booking
 * @author  		Tuan Pham Ngoc
 * @copyright    	Copyright (C) 2010 - 2014 Ossolution Team
 * @license        	GNU/GPL, see LICENSE.php
 */
// no direct access
defined( '_JEXEC' ) or die ;		
?>
<form action="index.php?option=com_osmembership&view=states" method="post" name="adminForm" id="adminForm">
	<table width="100%">
		<tr>
			<td align="left">
				<?php echo JText::_( 'Filter' ); ?>:
				<input type="text" name="search" id="search" value="<?php echo $this->state->search;?>" class="text_area search-query" onchange="document.adminForm.submit();" />
				<button onclick="this.form.submit();" class="btn"><?php echo JText::_( 'Go' ); ?></button>
				<button onclick="document.getElementById('search').value='';this.form.submit();" class="btn"><?php echo JText::_( 'Reset' ); ?></button>
			</td>
			<td style="text-align: right;">
				<?php echo $this->lists['filter_state'] ; ?>
				<?php echo $this->lists['filter_country_id']?>				
			</td>
		</tr>
	</table>
	<div id="editcell">
		<table class="adminlist table table-striped">
			<thead>
				<tr>
					<th width="20">
						<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this)" />
					</th>
					<th class="title" style="text-align: left;">
						<?php echo JHtml::_('grid.sort',  JText::_('OSM_STATE_NAME'), 'a.state_name', $this->state->filter_order_Dir, $this->state->filter_order ); ?>
					</th>
					<th class="title" style="text-align: left;">
						<?php echo JHtml::_('grid.sort',  JText::_('OSM_COUNTRY_NAME'), 'b.name', $this->state->filter_order_Dir, $this->state->filter_order ); ?>
					</th>											
					<th class="center title" width="15%">
						<?php echo JHtml::_('grid.sort',  JText::_('OSM_STATE_CODE_3'), 'a.state_3_code', $this->state->filter_order_Dir, $this->state->filter_order ); ?>
					</th>			
					<th class="center title" width="15%">
						<?php echo JHtml::_('grid.sort',  JText::_('OSM_STATE_CODE_2'), 'a.state_2_code', $this->state->filter_order_Dir, $this->state->filter_order ); ?>
					</th>
					<th class="center" width="10%">
						<?php echo JHtml::_('grid.sort',  JText::_('OSM_PUBLISHED'), 'a.published', $this->state->filter_order_Dir, $this->state->filter_order ); ?>
					</th>
					<th class="center" width="5%">
						<?php echo JHtml::_('grid.sort',  JText::_('OSM_ID'), 'a.id', $this->state->filter_order_Dir, $this->state->filter_order ); ?>
					</th>													
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="7">
						<?php echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
			<tbody>
			<?php
			$k = 0;
			for ($i=0, $n=count( $this->items ); $i < $n; $i++)
			{
				$row 		= &$this->items[$i];
				$link 		= JRoute::_( 'index.php?option=com_osmembership&view=state&cid[]='. $row->id );
				$checked 	= JHtml::_('grid.id',   $i, $row->id );
				$published 	= JHtml::_('grid.published', $row, $i, 'tick.png', 'publish_x.png', 'state.' );
				?>
				<tr class="<?php echo "row$k"; ?>">
					<td>
						<?php echo $checked; ?>
					</td>
					<td>
						<a href="<?php echo $link; ?>">
							<?php echo $row->state_name; ?>
						</a>
					</td>	
					<td>
						<?php echo $row->country_name; ?>
					</td>								
					<td class="center">
						<?php echo $row->state_3_code; ?>
					</td>	
					<td class="center">
						<?php echo $row->state_2_code; ?>
					</td>											
					<td class="center">
						<?php echo $published; ?>
					</td>
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
	<input type="hidden" name="filter_order" value="<?php echo $this->state->filter_order; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->state->filter_order_Dir; ?>" />	
	<?php echo JHtml::_( 'form.token' ); ?>
</form>