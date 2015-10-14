<?php
/**
 * @version        1.6.8
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
// no direct access
defined( '_JEXEC' ) or die ;
JHtml::_('behavior.tooltip');
?>
<form action="index.php?option=com_osmembership&view=taxes" method="post" name="adminForm" id="adminForm">
<table width="100%">
<tr>
	<td align="left">
		<?php echo JText::_( 'OSM_FILTER' ); ?>:
		<input type="text" name="search" id="search" value="<?php echo $this->state->search;?>" class="text_area search-query" onchange="document.adminForm.submit();" />		
		<button onclick="this.form.submit();" class="btn"><?php echo JText::_( 'OSM_GO' ); ?></button>
		<button onclick="document.getElementById('search').value='';this.form.submit();" class="btn"><?php echo JText::_( 'OSM_RESET' ); ?></button>		
	</td>	
	<td style="text-align: right ;">
		<?php
			echo $this->lists['plan_id'];
			if ($this->showVies)
			{
				echo $this->lists['vies'];
			}
		?>
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
				<?php echo JHtml::_('grid.sort',  JText::_('OSM_PLAN'), 'b.title', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th width="10%" class="title" nowrap="nowrap">
				<?php echo JHtml::_('grid.sort',  JText::_('OSM_COUNTRY'), 'a.country', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th width="10%" class="title" nowrap="nowrap">
				<?php echo JHtml::_('grid.sort',  JText::_('OSM_TAX_RATE'), 'a.rate', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<?php
				if ($this->showVies)
				{
				?>
					<th width="10%" class="title" nowrap="nowrap">
						<?php echo JHtml::_('grid.sort',  JText::_('OSM_VIES'), 'a.rate', $this->lists['order_Dir'], $this->lists['order'] ); ?>
					</th>
				<?php
				}
			?>
			<th width="5%" class="title" nowrap="nowrap">
				<?php echo JHtml::_('grid.sort',  JText::_('OSM_PUBLISHED'), 'a.published', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>																
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="5">
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
		$link 	= JRoute::_( 'index.php?option=com_osmembership&task=tax.edit&cid[]='. $row->id );
		$checked 	= JHtml::_('grid.id',   $i, $row->id );				
		$published 	= JHtml::_('grid.published', $row, $i, 'tick.png', 'publish_x.png', 'tax.' );
		?>
		<tr class="<?php echo "row$k"; ?>">
			<td>
				<?php echo $checked; ?>
			</td>	
			<td>
				<a href="<?php echo $link; ?>">
					<?php echo $row->title ? $row->title : JText::_('OSM_ALL_PLANS'); ?>
				</a>
			</td>
			<td>
				<?php echo $row->country ? $row->country : JText::_('OSM_ALL_COUNTRIES');?>
			</td>
			<td class="text_center">
				<?php echo $row->rate; ?>
			</td>
			<?php
				if ($this->showVies)
				{
				?>
					<td>
						<?php echo $row->vies ? JText::_('OSM_YES') : JText::_('OSM_No');?>
					</td>
				<?php
				}
			?>
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