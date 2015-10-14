<?php
/**
 * @version        1.6.7
 * @package        Joomla
 * @subpackage     OS Membership
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2011 - 2013 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die ; 
if(count($items)) 
{ 
?>		
<div id="osm-plans-list-default" class="row-fluid osm-container">
	<h3 class="osm-page-heading"><?php echo JText::_('OSM_SUBSCRIPTION_PLANS'); ?></h3>	
	<?php
    	echo OSMembershipHelperHtml::loadCommonLayout('common/columns_plans.php', array('items' => $items, 'config' => $config, 'Itemid' => $Itemid, 'categoryId' => 0));
    ?>
</div>    
<?php
}                
?>
