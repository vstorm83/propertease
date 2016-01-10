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
$ordering = ($this->lists['order'] == 'a.ordering');
?>
<form action="index.php?option=com_osmembership&view=plans" method="post" name="adminForm" id="adminForm">
<table width="100%">
<tr>
	<td align="left">
		<?php echo JText::_( 'OSM_FILTER' ); ?>:
		<input type="text" name="search" id="search" value="<?php echo $this->state->search;?>" class="search-query" onchange="document.adminForm.submit();" />		
		<button onclick="this.form.submit();" class="btn"><?php echo JText::_( 'OSM_GO' ); ?></button>
		<button onclick="document.getElementById('search').value='';this.form.submit();" class="btn"><?php echo JText::_( 'OSM_RESET' ); ?></button>		
	</td>
	<td align="right">
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
				<?php echo JHtml::_('grid.sort',  JText::_('OSM_TITLE'), 'a.title', $this->lists['order_Dir'], $this->lists['order'] ); ?>				
			</th>											
			<th class="title" width="10%">
				<?php echo JText::_('OSM_THUMB'); ?>
			</th>			
			<th class="title" width="15%">
				<?php echo JText::_('OSM_TOTAL_SUBSCRIBERS'); ?>
			</th>		
			<th class="title" width="15%">
				<?php echo JText::_('OSM_ACTIVE_SUBSCRIBERS'); ?>
			</th>
			<th width="10%" nowrap="nowrap">
				<?php echo JHtml::_('grid.sort',  JText::_('OSM_ORDER'), 'a.ordering', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				<?php echo JHtml::_('grid.order',  $this->items , 'filesave.png', 'plan.save_order' ); ?>
			</th>			
			<th width="5%">
				<?php echo JHtml::_('grid.sort',  JText::_('OSM_PUBLISHED'), 'a.published', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th width="2%">
				<?php echo JHtml::_('grid.sort',  JText::_('OSM_ID'), 'a.id', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>													
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="9">
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
		$link 	= JRoute::_( 'index.php?option=com_osmembership&task=plan.edit&cid[]='. $row->id);
		$checked 	= JHtml::_('grid.id',   $i, $row->id );				
		$published 	= JHtml::_('grid.published', $row, $i, 'tick.png', 'publish_x.png', 'plan.' );			
		?>
		<tr class="<?php echo "row$k"; ?>">
			<td>
				<?php echo $checked; ?>
			</td>	
			<td>																			
				<a href="<?php echo $link; ?>"><?php echo $row->title ; ?></a>				
			</td>
			<td class="center">
				<?php
					if ($row->thumb) {
					?>
						<a href="<?php echo JUri::root().'media/com_osmembership/'.$row->thumb ; ?>" class="modal"><img src="<?php echo JUri::root().'/media/com_osmembership/'.$row->thumb ; ?>" /></a>
					<?php	
					}
				?>				
			</td>			
			<td class="center">
				<?php echo OSMembershipHelper::countSubscribers($row->id); ?>
			</td>																			
			<td class="center">
				<?php echo OSMembershipHelper::countSubscribers($row->id, 1); ?>
			</td>
			<td class="order">
				<span><?php echo $this->pagination->orderUpIcon( $i, true, 'plan.orderup', 'Move Up', $ordering ); ?></span>
				<span><?php echo $this->pagination->orderDownIcon( $i, $n, true, 'plan.orderdown', 'Move Down', $ordering ); ?></span>
				<?php $disabled = $ordering ?  '' : 'disabled="disabled"'; ?>				
				<input type="text" name="order[]" size="5" value="<?php echo $row->ordering;?>" class="input-mini" style="text-align: center" <?php echo $disabled; ?> />
			</td>			
			<td class="text_center">
				<?php echo $published; ?>
			</td>
			<td class="text_center">
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