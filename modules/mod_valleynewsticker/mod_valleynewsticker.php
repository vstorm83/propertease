<?php
/**
* @package 	mod_valleynewsticker - Joomla News Ticker Module
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
  
	// require helper
	if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
	require_once(dirname(__FILE__).DS.'helper.php');		
	// base url
	$baseurl	=	JURI::base( true );
	// get parameters from the module's configuration
	$tickercontid			= 	$params->get('tickercontid', 'newsticker');
	$tickertitle			= 	$params->get('tickertitle', 'Latest News from PluginValley: ');
	$showtitle				= 	$params->get('showtitle', '1');
	$showcontrols			= 	$params->get('showcontrols', 'true');
	$speed					= 	$params->get('speed', '0.2');
	$displaytype			= 	$params->get('displaytype', 'reveal');
	$directiontype			= 	$params->get('directiontype', 'ltr');
	$pausetime				= 	$params->get('pausetime', '2000');
	$fins					= 	$params->get('fins', '600');
	$fous					= 	$params->get('fous', '300');
	$ticcArr				= 	explode('@@', $params->get('tickercontent', 'Latest Joomla, CSS, Php Tips & Tutorials@@Ads Manager Google Map Released@@We Update Extensions Regularly@@Optin Professional New version Released@@Vote Us on JED!'));
	$linkArr				= 	explode('@@', $params->get('contentlink', 'http://www.pluginvalley.com@@http://www.pluginvalley.com/extensions/adsmanager/google-map-for-adsmanager.html@@http://www.pluginvalley.com/blog.html@@http://www.pluginvalley.com/extensions/optin-professional-toolbar.html@@extensions.joomla.org/extensions/owner/PluginValley'));	
	$linktype				= 	$params->get('linktype', '_self');
	$addnofolllow			= 	$params->get('addnofolllow', '0');
	if ($addnofolllow){ $linkrel='rel="nofollow"'; } else { $linkrel='rel="dofollow"'; }
	//
	$showtitle	= 	$params->get('showtitle', '1');
	$document = JFactory::getDocument();
	// plugin
	$document->addScript( $baseurl.'/modules/mod_valleynewsticker/assets/jquery/jquery.ticker.js' );
	// css
	$document->addStyleSheet( $baseurl.'/modules/mod_valleynewsticker/assets/css/style.css' );	

	require(JModuleHelper::getLayoutPath('mod_valleynewsticker', 'default'));