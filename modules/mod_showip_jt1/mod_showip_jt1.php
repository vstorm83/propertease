<?php
/**
 * @package ShowIp_JT1 Module for Joomla! 2.5
 * @version $Id: 1.0 
 * @author muratyil
 * @Copyright (C) 2012- muratyil
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined( '_JEXEC' ) or die( 'Restricted access' );
//heading
$heading= $params->get( 'heading', "" );
//IP address
$ipadres = $_SERVER['REMOTE_ADDR'];
// Time
$browser=$_SERVER['HTTP_USER_AGENT'];
//
$bgcolor=$params->get( 'bgcolor', "#f0f0f0" );
//
$showbrowser=$params->get( 'showbrowser', "0" );
//
$bordercolor=$params->get( 'bordercolor', "#dedede" );
?>
<div id="container" style="width:auto; background:<?php echo $bgcolor; ?>;padding:10px; border:1px solid <?php echo $bordercolor; ?>; border-radius:5px;">
<div class="rows" style="clear:both; padding:4px 0; border-bottom:1PX solid <?php echo $bordercolor; ?>; font-weight:bold;"><?php echo $heading; ?></div>
	<div class="rows" style="clear:both;padding:4px 0;border-bottom:1PX solid <?php echo $bordercolor; ?>;"><?php  echo $ipadres; ?></div>
	<?php if($showbrowser==1): ?> <div class="rows" style="clear:both;padding:4px 0;border-bottom:1PX solid <?php echo $bordercolor; ?>;"><?php echo $browser; ?></div><?php endif; ?>
</div>