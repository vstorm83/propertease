<?php
/********************************************************************
Product		: FlexicontactPlus
Date		: 16 January 2014
Copyright	: Les Arbres Design 2010-2014
Contact		: http://www.lesarbresdesign.info
Licence		: GNU General Public License
*********************************************************************/
defined('_JEXEC') or die('Restricted Access');

jimport('joomla.plugin.plugin');

class plgContentFlexicontactplus extends JPlugin 
{

//-------------------------------------------------------------------------------
// Joomla calls this whenever it renders an article
//
function onContentPrepare($context, &$article, &$params, $page = 0)
{
// determine as quickly as possible if there are any calls for us in this article

	if (strpos($article->text, '{flexicontactplus') === false)
		return;

// there is a call for us in this article so bring in the rest of the code

	if (!file_exists(JPATH_ADMINISTRATOR.'/components/com_flexicontactplus/helpers/plugin_helper.php'))
		{
		JPlugin::loadLanguage();			// load the plugin's own language file
		$html = JText::_('PLG_CONTENT_FLEXICONTACTPLUS_NEED_COMPONENT');
		$article->text = preg_replace('#{flexicontactplus.*}#', $html, $article->text, 1);
		return;
		}
		
	include_once JPATH_ADMINISTRATOR.'/components/com_flexicontactplus/helpers/plugin_helper.php';
	$rp_plugin = new LAFC_article_plugin_helper;
	$rp_plugin->article_plugin($this, $context, $article, $article_params);
}


}





