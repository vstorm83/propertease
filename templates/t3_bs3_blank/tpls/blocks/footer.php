<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>

<!-- FOOTER -->
<footer id="t3-footer" class="wrap t3-footer">

	<?php if ($this->checkSpotlight('footnav', 'footer-1, footer-2, footer-3, footer-4, footer-5, footer-6')) : ?>
		<!-- FOOT NAVIGATION -->
		<div class="container">
			<?php $this->spotlight('footnav', 'footer-1, footer-2, footer-3, footer-4, footer-5, footer-6') ?>
		</div>
		<!-- //FOOT NAVIGATION -->
	<?php endif ?>

	

</footer>

<?php 
$this->addScript('templates/'.$this->template.'/js/jquery-easing-1.3.js');
?>
<?php 
$this->addScript('templates/'.$this->template.'/js/jquery-transit-modified.js');
?>
<?php 
$this->addScript('templates/'.$this->template.'/js/layerslider.transitions.js');
?>
<?php 
$this->addScript('templates/'.$this->template.'/js/layerslider.kreaturamedia.jquery.js');
?>
<?php 
$this->addScript('templates/'.$this->template.'/js/wow.min.js');
?>
<?php 
$this->addScript('templates/'.$this->template.'/js/script.js');
?>
<script type="text/javascript">
	 jQuery(document).ready(function(){
	 	new WOW().init();
 	 jQuery('#layerslider').layerSlider({
					hoverPrevNext : false,
					//responsive              : true,
					  //responsiveUnder         : 768,
					slideDirection :'fade',
					
					loops : 1,
					forceLoopNum :true			});
 })

</script>

