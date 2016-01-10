<?php

/**
 * @version        1.6.8
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2002 - 2013 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
abstract class OSMembershipHelperHtml
{
	/**
	 * Function to render a common layout which is used in different views
	 *
	 * @param string $layout
	 * @param array  $data
	 *
	 * @return string
	 * @throws RuntimeException
	 */
	public static function loadCommonLayout($layout, $data = array())
	{
		$app       = JFactory::getApplication();
		$themeFile = str_replace('/tmpl', '', $layout);
		if (JFile::exists($layout))
		{
			$path = $layout;
		}
		elseif (JFile::exists(JPATH_THEMES . '/' . $app->getTemplate() . '/html/com_osmembership/' . $themeFile))
		{
			$path = JPATH_THEMES . '/' . $app->getTemplate() . '/html/com_osmembership/' . $themeFile;
		}
		elseif (JFile::exists(JPATH_ROOT . '/components/com_osmembership/views/' . $layout))
		{
			$path = JPATH_ROOT . '/components/com_osmembership/views/' . $layout;
		}
		else
		{
			throw new RuntimeException(JText::_('The given shared template path is not exist'));
		}
		// Start an output buffer.
		ob_start();
		extract($data);

		// Load the layout.
		include $path;

		// Get the layout contents.
		$output = ob_get_clean();

		return $output;
	}
}