<?php
/**
 * @version $Id: djmegamenu.php 8 2013-08-19 11:50:39Z szymon $
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
defined('_JEXEC') or die;

class plgSystemDJMegaMenu extends JPlugin
{
	
	function onContentPrepareForm($form, $data)
	{
		// extra option for menu item
		if ($form->getName() == 'com_menus.item') {
			$this->loadLanguage();
			JForm::addFormPath(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'params');
			$form->loadFile('djmmitem', true);
		}
	}
	
	function djdebug($msg){
		echo "<pre>".print_r($msg, true)."</pre>";
	}
	
}
