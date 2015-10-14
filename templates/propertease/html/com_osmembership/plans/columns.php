<?php
/**
 * @version        1.6.8
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2014 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die;
if(count($this->items))
{
?>
<div id="osm-plans-list-columns" class="row-fluid osm-container">
	<h1 class="osm-page-title"><?php echo JText::_('OSM_SUBSCRIPTION_PLANS'); ?></h1>
	<?php 
		echo OSMembershipHelperHtml::loadCommonLayout('common/columns_plans.php', array('items' => $this->items, 'config' => $this->config, 'Itemid' => $this->Itemid, 'categoryId' => $this->categoryId));
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