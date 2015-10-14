<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// get params
$sitename  = $this->params->get('sitename');
$slogan    = $this->params->get('slogan', '');
$logotype  = $this->params->get('logotype', 'text');
$logoimage = $logotype == 'image' ? $this->params->get('logoimage', T3Path::getUrl('images/logo.png', '', true)) : '';
$logoimgsm = ($logotype == 'image' && $this->params->get('enable_logoimage_sm', 0)) ? $this->params->get('logoimage_sm', T3Path::getUrl('images/logo-sm.png', '', true)) : false;

if (!$sitename) {
	$sitename = JFactory::getConfig()->get('sitename');
}

$logosize = '';
if ($headright = $this->countModules('head-search or languageswitcherload')) {
	$logosize = '';
}

?>


<!-- HEADER -->
<div id="t3-header" class=" t3-header">
	<div class="row ">
	<div class="top-left col-md-6 ">
		<!-- LOGO -->
		<div class="pull-left <?php echo $logosize ?> logo">
			<div class="logo-<?php echo $logotype, ($logoimgsm ? ' logo-control' : '') ?>">
				<a href="<?php echo JURI::base(true) ?>" title="<?php echo strip_tags($sitename) ?>">
					<?php if($logotype == 'image'): ?>
						<img class="logo-img" src="<?php echo JURI::base(true) . '/' . $logoimage ?>" alt="<?php echo strip_tags($sitename) ?>" />
					<?php endif ?>
					<?php if($logoimgsm) : ?>
						<img class="logo-img-sm" src="<?php echo JURI::base(true) . '/' . $logoimgsm ?>" alt="<?php echo strip_tags($sitename) ?>" />
					<?php endif ?>
					<span><?php echo $sitename ?></span>
				</a>
				<small class="site-slogan"><?php echo $slogan ?></small>
			</div>
		</div>
		<!-- //LOGO -->
		<?php if ($this->countModules('head-search')) : ?>
		<!-- HEAD SEARCH -->
		<div class="head-search <?php $this->_c('head-search') ?>">
			<jdoc:include type="modules" name="<?php $this->_p('head-search') ?>" style="raw" />
		</div>
		<!-- //HEAD SEARCH -->
		<?php endif ?>
		

	</div>
	<?php if ($this->countModules('top-right')) : ?>
		<!-- HEAD SEARCH -->
		<div class="top-right col-md-6  <?php $this->_c('top-right') ?>">
			<jdoc:include type="modules" name="<?php $this->_p('top-right') ?>" style="raw" />
                        <button>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
		</div>
		<!-- //HEAD SEARCH -->
		<?php endif ?>
</div></div>
<!-- //HEADER -->
