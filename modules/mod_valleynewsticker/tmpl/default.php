<?php
/**
* @package 	mod_valleynewsticker - News Ticker by PluginValley Module
* @version		1.0.1
* @created		November 2013

* @author		PluginValley
* @email		support@pluginvalley.com
* @website		http://www.pluginvalley.com
* @support		Forum - http://www.pluginvalley.com/forum.html
* @copyright	Copyright (C) 2012 pluginvalley. All rights reserved.
* @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*
*/
// no direct access
defined('_JEXEC') or die('');
?>
	<script type="text/javascript">
	var nwt = jQuery.noConflict();
nwt(function () {
		nwt('#<?php echo $tickercontid;?>').ticker({
			speed: <?php echo $speed; ?>,           // The speed of the reveal
			ajaxFeed: false,       // Populate jQuery News Ticker via a feed
			feedUrl: false,        // The URL of the feed
			// feedType: 'xml',       // Currently only XML
			htmlFeed: true,        // Populate jQuery News Ticker via HTML
			controls: <?php echo $showcontrols; ?>, // Whether or not to show the jQuery News Ticker controls
			titleText: '<?php if($showtitle): echo $tickertitle; endif; ?>',   // To remove the title set this to an empty String
			displayType: '<?php echo $displaytype; ?>', // Animation type - current options are 'reveal' or 'fade'
			direction: '<?php echo $directiontype; ?>',     // Ticker direction - current options are 'ltr' or 'rtl'
			pauseOnItems: <?php echo $pausetime; ?>,    // The pause on a news item before being replaced
			fadeInSpeed: <?php echo $fins; ?>,      // Speed of fade in animation
			fadeOutSpeed: <?php echo $fous; ?>      // Speed of fade out animation
    });
});
    </script>
<ul id="<?php echo $tickercontid;?>" class="js-hidden">
<?php
$newsCount = count($ticcArr);
$j=0;
foreach ($ticcArr as $key=>$value){
    echo '<li class="news-item"><a '.$linkrel.' target="'.$linktype.'" href="'.$linkArr[''.$j.''].'">'.$value.'</a></li>';
	$j++;
}
?>
</ul>