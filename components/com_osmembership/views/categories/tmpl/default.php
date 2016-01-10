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

if (count($this->items))
{
?>
<div id="osm-categories-list" class="row-fluid osm-container">
	<h1 class="osm-page-title"><?php echo JText::_('OSM_CATEGORIES') ;?></h1>
	<?php
	for ($i = 0 , $n = count($this->items) ; $i < $n ; $i++)
	{
		$item = $this->items[$i] ;
		$link = JRoute::_(OSMembershipHelperRoute::getCategoryRoute($item->id, $this->Itemid));
		?>
		<div class="osm-item-wrapper clearfix">
			<div class="osm-item-heading-box">
				<h2 class="osm-item-title">
					<a href="<?php echo $link; ?>" class="osm-item-title-link">
						<?php
							echo $item->title;
						?>												
					</a>
					<small>( <?php echo $item->total_plans ;?> <?php echo $item->total_plans > 1 ? JText::_('OSM_PLANS') :  JText::_('OSM_PLAN') ; ?> )</small>
				</h2>
			</div>
			<?php
				if($item->description)
				{
				?>
					<div class="osm-item-description clearfix">						
						<?php echo $item->description;?>
					</div>
				<?php
				}
			?>
		</div>		
	<?php
	} 
	if ($this->pagination->total > $this->pagination->limit)
	{
	?>
        <div class="pagination">
            <?php echo $this->pagination->getPagesLinks(); ?>
        </div>
    <?php
    }
	?>
</div>
<?php
}
?>    