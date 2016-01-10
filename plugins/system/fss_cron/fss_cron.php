<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.plugin.plugin' );
if (!defined("DS")) define('DS', DIRECTORY_SEPARATOR);

if (file_exists(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'j3helper.php'))
{
	
	require_once( JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'j3helper.php' );

	class plgSystemFSS_Cron extends JPlugin
	{ 
		/**
		 * Constructor
		 *
		 * For php4 compatibility we must not use the __constructor as a constructor for plugins
		 * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
		 * This causes problems with cross-referencing necessary for the observer design pattern.
		 *
		 * @access	protected
		 * @param	object	$subject The object to observe
		 * @param 	array   $config  An array that holds the plugin configuration
		 * @since	1.0
		 */
		function plgSystemFSS_Cron( &$subject, $config )
		{
			parent::__construct( $subject, $config );
		}
		
		function onAfterInitialise()
		{
			$db = JFactory::getDBO();
			
			$qry = "SELECT * FROM #__fss_cron WHERE published = 1 AND ((UNIX_TIMESTAMP() - lastrun) - (`interval` * 60)) > 0 LIMIT 1";
			$db->setQuery($qry);
			$rows = $db->loadObjectList();
			if (!$rows)
				return;
			
			foreach ($rows as $row)
			{
				$db->setQuery("UPDATE #__fss_cron SET lastrun=UNIX_TIMESTAMP() WHERE id='{$row->id}' LIMIT 1");
				$db->query();

				$class = "FSSCron" . $row->class;
				$file = strtolower($row->class) . ".php";
				$path = JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'cron'.DS;
				if (file_exists($path.$file))
				{
					require_once($path.$file);
					$inst = new $class();
					$inst->Execute($this->ParseParams($row->params));

					$inst->SaveLog();
				} 
			}
		}

		function ParseParams(&$aparams)
		{
			if (substr($aparams,0,2) == "a:")
			{
				return unserialize($aparams);	
			}
			$out = array();
			$bits = explode(";",$aparams);
			foreach ($bits as $bit)
			{
				if (trim($bit) == "") continue;
				$res = explode(":",$bit,2);
				if (count($res) == 2)
				{
					$out[$res[0]] = $res[1];	
				}
			}
			return $out;	
		}
	}
}