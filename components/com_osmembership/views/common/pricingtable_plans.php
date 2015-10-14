<?php
/**
 * @version        1.6.8
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012-2014 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
if (isset($config->number_columns))
{
    $numberColumns = $config->number_columns ;
}
else
{
    $numberColumns = 3 ;
}
$numberColumns = min($numberColumns, 4);
if (!isset($categoryId))
{
    $categoryId = 0;
}
$span = intval(12 / $numberColumns);
?>
<div class="row-fluid osm-pricing-table">
<?php
$i = 0;
$numberPlans = count($items);
$recommendedPlanId = (int) JFactory::getApplication()->getParams()->get('recommended_campaign_id');
foreach ($items as $item)
{
	$i++;
	if ($i > $numberColumns)
	{
		break;
	}
    $Itemid = OSMembershipHelperRoute::getPlanMenuId($item->id, $categoryId, $Itemid);
    if ($item->thumb)
    {
        $imgSrc = JUri::base().'media/com_osmembership/'.$item->thumb ;
    }
    $url = JRoute::_('index.php?option=com_osmembership&view=plan&catid='.$categoryId.'&id='.$item->id.'&Itemid='.$Itemid);
    if ($config->use_https)
    {
    	$signUpUrl = JRoute::_(OSMembershipHelperRoute::getSignupRoute($item->id, $Itemid), false, 1);        
    }
    else
    {
    	$signUpUrl = JRoute::_(OSMembershipHelperRoute::getSignupRoute($item->id, $Itemid));        
    }
	if (!$item->short_description)
	{
		$item->short_description = $item->description;
	}
	if ($item->id == $recommendedPlanId)
	{
		$recommended = true;
	}
	else
	{
		$recommended = false;
	}
    ?>
    <div class="osm-plan <?php if ($recommended) echo 'osm-plan-recommended'; ?> span<?php echo $span; ?>">
	    <?php
	        if ($recommended)
	        {
		    ?>
		        <p class="plan-recommended"><?php echo JText::_('OSM_RECOMMENDED'); ?></p>
	        <?php
	        }
	    ?>

	    <div class="osm-plan-header">
            <h2 class="osm-plan-title">
                <?php echo $item->title; ?>
            </h2>
	    </div>
        <div class="osm-plan-price">
        	<h2>
            	<p class="price">
                    <span>
					<?php
						if ($item->price > 0)
						{
							echo OSMembershipHelper::formatCurrency($item->price, $config);
						}
						else
						{
							echo JText::_('OSM_FREE');
						}
						?>
                  	</span>
                </p>				
            </h2>
        </div>
	    <div class="osm-plan-short-description">
			<?php echo $item->short_description; ?>
	    </div>
		    <?php
		    if (OSMembershipHelper::canSubscribe($item))
		    {
			?>
	             <ul class="osm-signup-container">
				    <li>
					    <a href="<?php echo $signUpUrl; ?>" class="btn btn-primary btn-singup">
						    <?php echo JText::_('OSM_SIGNUP'); ?>
					    </a>
				    </li>
				</ul>
		    <?php
		    }
		    ?>
	</div>
<?php
}
?>
</div>