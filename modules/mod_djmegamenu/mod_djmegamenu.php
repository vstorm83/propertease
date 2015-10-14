<?php 
/**
 * @version $Id: mod_djmegamenu.php 17 2013-12-16 11:52:57Z szymon $
 * @package DJ-MegaMenu
 * @copyright Copyright (C) 2012 DJ-Extensions.com LTD, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email contact@dj-extensions.com
 * @developer Szymon Woronowski - szymon.woronowski@design-joomla.eu
 *
 * DJ-MegaMenu is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * DJ-MegaMenu is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with DJ-MegaMenu. If not, see <http://www.gnu.org/licenses/>.
 *
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
defined('DS') or define('DS', DIRECTORY_SEPARATOR);

// Include the syndicate functions only once
require_once (dirname(__FILE__) . DS . 'helper.php');

$params->def('menutype', $params->get('menu','mainmenu'));
$params->def('startLevel', 1);
$params->def('endLevel', 0);
$params->def('showAllChildren', 1);
$params->set('column_width', (int)$params->get('column_width',200));
$startLevel = $params->get('startLevel');
$endLevel = $params->get('endLevel');
$menu = $app->getMenu();

$list		= modDJMegaMenuHelper::getList($params);
$subwidth	= modDJMegaMenuHelper::getSubWidth($params);
$subcols	= modDJMegaMenuHelper::getSubCols($params);
$active		= modDJMegaMenuHelper::getActive($params);
$active_id 	= $active->id;
$path		= $active->tree;

$showAll	= $params->get('showAllChildren');
$class_sfx	= ($params->get('hasSubtitles') ? 'hasSubtitles ':'') . htmlspecialchars($params->get('class_sfx'));

if(!count($list)) return;

$app = JFactory::getApplication();
$document = JFactory::getDocument();

JHTML::_('behavior.framework');

if($params->get('select',0)) {
	$document->addScript('modules/mod_djmegamenu/assets/js/djselect.js');
	$document->addScriptDeclaration("window.addEvent('domready',function(){document.id('dj-megamenu$module->id').addClass('allowHide')});");
	$document->addStyleDeclaration("
		.dj-select {display: none;margin:10px;padding:5px;font-size:1.5em;max-width:95%;height:auto;}
		@media (max-width: ".$params->get('width',979)."px) {
  			#dj-megamenu$module->id.allowHide, #dj-megamenu$module->id"."sticky, #dj-megamenu$module->id"."placeholder { display: none; }
  			#dj-megamenu$module->id"."select { display: inline-block; }
		}
	");
}

if($params->get('theme')!='_override') {
	$css = 'modules/mod_djmegamenu/themes/'.$params->get('theme','default').'/css/djmegamenu.css';
} else {
	$css = 'templates/'.$app->getTemplate().'/css/djmegamenu.css';
}

$document->addStyleSheet($css);

if($params->get('moo',1)) {	
	
	$document->addScript('modules/mod_djmegamenu/assets/js/djmegamenu.js');
	
	$effect = $params->get('effect');
	if($effect!='linear') $effect.=':out';
	if(!is_numeric($duration = $params->get('duration'))) $duration = 200;
	if(!is_numeric($delay = $params->get('delay'))) $delay = 500;
	$height_fx = ($params->get('height_fx')) ? 'true' : 'false';
	$width_fx = ($params->get('width_fx')) ? 'true' : 'false';
	$opacity_fx = ($params->get('opacity_fx')) ? 'true' : 'false';
	$height_fx_sub = ($params->get('height_fx_sub')) ? 'true' : 'false';
	$width_fx_sub = ($params->get('width_fx_sub')) ? 'true' : 'false';
	$opacity_fx_sub = ($params->get('opacity_fx_sub')) ? 'true' : 'false';
	$wrapper_id = $params->get('wrapper');
	$open_event = $params->get('event','mouseenter');
	$fixed = $params->get('fixed',0);
	$fixed_offset = $params->get('fixed_offset',0);
	
	$options = "{wrap: document.id('$wrapper_id'), transition: '$effect', duration: $duration, delay: $delay, event: '$open_event',
		h: $height_fx, w: $width_fx, o: $opacity_fx, hs: $height_fx_sub, ws: $width_fx_sub, os: $opacity_fx_sub, fixed: $fixed, offset: $fixed_offset }";
	
	$js = "window.addEvent('domready',function(){ this.djmegamenu$module->id = new DJMegaMenus(document.id('dj-megamenu$module->id'), $options); });";
	
	$document->addScriptDeclaration($js);
	
}

require(JModuleHelper::getLayoutPath('mod_djmegamenu', $params->get('layout', 'default')));

?>