<?php
/**
 * Plugin Helper File
 *
 * @package         Modules Anywhere
 * @version         3.6.6
 *
 * @author          Peter van Westen <peter@nonumber.nl>
 * @link            http://www.nonumber.nl
 * @copyright       Copyright © 2015 NoNumber All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die;

require_once JPATH_PLUGINS . '/system/nnframework/helpers/functions.php';
require_once JPATH_PLUGINS . '/system/nnframework/helpers/protect.php';
require_once JPATH_PLUGINS . '/system/nnframework/helpers/text.php';

nnFrameworkFunctions::loadLanguage('plg_system_modulesanywhere');

/**
 * Plugin that places modules
 */
class plgSystemModulesAnywhereHelper
{
	public function __construct(&$params)
	{
		$this->option = JFactory::getApplication()->input->get('option');

		$this->params = $params;
		$this->params->comment_start = '<!-- START: Modules Anywhere -->';
		$this->params->comment_end = '<!-- END: Modules Anywhere -->';
		$this->params->message_start = '<!--  Modules Anywhere Message: ';
		$this->params->message_end = ' -->';
		$this->params->protect_start = '<!-- START: MA_PROTECT -->';
		$this->params->protect_end = '<!-- END: MA_PROTECT -->';

		$this->params->module_tag = trim($this->params->module_tag);
		$this->params->modulepos_tag = trim($this->params->modulepos_tag);

		$tags = array();
		$tags[] = preg_quote($this->params->module_tag, '#');
		$tags[] = preg_quote($this->params->modulepos_tag, '#');
		if ($this->params->handle_loadposition)
		{
			$tags[] = 'loadposition';
		}

		$this->params->start_tags = array_map(
			function ($tag)
			{
				return '{' . $tag;
			}, $tags
		);

		$this->params->tags = '(' . implode('|', $tags) . ')';

		$bts = '((?:<p(?: [^>]*)?>\s*)?)((?:\s*<br ?/?>\s*)?)';
		$bte = '((?:\s*<br ?/?>)?)((?:\s*</p>)?)';
		$regex = '((?:\{div(?: [^\}]*)\})?)(\s*)'
			. '\{(' . implode('|', $tags) . ')(?:\s|&nbsp;|&\#160;)+((?:[^\}]*?\{[^\}]*?\})*[^\}]*?)\}'
			. '(\s*)((?:\{/div\})?)';
		$this->params->regex = '#' . $bts . $regex . $bte . '#s';
		$this->params->regex2 = '#' . $regex . '#s';

		$this->params->protected_tags = $tags;

		$this->params->message = '';

		$this->aid = JFactory::getUser()->getAuthorisedViewLevels();

		$this->params->disabled_components = array('com_acymailing');
	}

	public function onContentPrepare(&$article, &$context)
	{
		$this->params->message = '';

		$area = isset($article->created_by) ? 'articles' : 'other';


		nnFrameworkHelper::processArticle($article, $context, $this, 'processModules', array($area));
	}

	public function onAfterDispatch()
	{
		// only in html
		if (JFactory::getDocument()->getType() !== 'html' && JFactory::getDocument()->getType() !== 'feed')
		{
			return;
		}

		$buffer = JFactory::getDocument()->getBuffer('component');

		if (empty($buffer) || is_array($buffer))
		{
			return;
		}

		$this->replaceTags($buffer, 'component');

		JFactory::getDocument()->setBuffer($buffer, 'component');
	}

	public function onAfterRender()
	{
		// only in html and feeds
		if (JFactory::getDocument()->getType() !== 'html' && JFactory::getDocument()->getType() !== 'feed')
		{
			return;
		}

		$html = JResponse::getBody();
		if ($html == '')
		{
			return;
		}

		if (JFactory::getDocument()->getType() != 'html')
		{
			$this->replaceTags($html);
		}
		else
		{
			// only do stuff in body
			list($pre, $body, $post) = nnText::getBody($html);
			$this->replaceTags($body);
			$html = $pre . $body . $post;
		}

		$this->cleanLeftoverJunk($html);

		JResponse::setBody($html);
	}

	function replaceTags(&$string, $area = 'article')
	{
		if (!is_string($string) || $string == '')
		{
			return;
		}

		if (!preg_match('#\{' . $this->params->tags . '#', $string))
		{
			return;
		}

		// allow in component?
		if (
			$area == 'component'
			&& in_array(JFactory::getApplication()->input->get('option'), $this->params->disabled_components)
		)
		{

			$this->protect($string);

			$this->removeAll($string, $area);

			nnProtect::unprotect($string);

			return;
		}

		$this->protect($string);

		// COMPONENT
		if (JFactory::getDocument()->getType() == 'feed')
		{
			$s = '#(<item[^>]*>)#s';
			$string = preg_replace($s, '\1<!-- START: MODA_COMPONENT -->', $string);
			$string = str_replace('</item>', '<!-- END: MODA_COMPONENT --></item>', $string);
		}

		if (strpos($string, '<!-- START: MODA_COMPONENT -->') === false)
		{
			$this->tagArea($string, 'MODA', 'component');
		}

		$this->params->message = '';

		$components = $this->getTagArea($string, 'MODA', 'component');

		foreach ($components as $component)
		{
			$this->processModules($component['1'], 'components');
			$string = str_replace($component['0'], $component['1'], $string);
		}

		// EVERYWHERE
		$this->processModules($string, 'other');

		nnProtect::unprotect($string);
	}

	function tagArea(&$string, $ext = 'EXT', $area = '')
	{
		if (!$string || !$area)
		{
			return;
		}

		$string = '<!-- START: ' . strtoupper($ext) . '_' . strtoupper($area) . ' -->' . $string . '<!-- END: ' . strtoupper($ext) . '_' . strtoupper($area) . ' -->';

		if ($area == 'article_text')
		{
			$string = preg_replace('#(<hr class="system-pagebreak".*?/>)#si', '<!-- END: ' . strtoupper($ext) . '_' . strtoupper($area) . ' -->\1<!-- START: ' . strtoupper($ext) . '_' . strtoupper($area) . ' -->', $string);
		}
	}

	function getTagArea(&$string, $ext = 'EXT', $area = '')
	{
		if (!$string || !$area)
		{
			return array();
		}

		$start = '<!-- START: ' . strtoupper($ext) . '_' . strtoupper($area) . ' -->';
		$end = '<!-- END: ' . strtoupper($ext) . '_' . strtoupper($area) . ' -->';
		$matches = explode($start, $string);
		array_shift($matches);
		foreach ($matches as $i => $match)
		{
			list($text) = explode($end, $match, 2);
			$matches[$i] = array(
				$start . $text . $end,
				$text
			);
		}

		return $matches;
	}

	public function removeAll(&$string, $area = 'articles')
	{
		$this->params->message = JText::_('MA_OUTPUT_REMOVED_NOT_ENABLED');
		$this->processModules($string, $area);
	}

	function processModules(&$string, $area = 'articles')
	{

		if (preg_match('#\{' . $this->params->tags . '#', $string))
		{
			jimport('joomla.application.module.helper');
			JPluginHelper::importPlugin('content');

			$this->replace($string, $this->params->regex, $area);
			$this->replace($string, $this->params->regex2, $area);
		}
	}

	function replace(&$string, $regex, $area = 'articles')
	{
		list($pre_string, $string, $post_string) = nnText::getContentContainingSearches(
			$string,
			$this->params->start_tags,
			null, 200, 500
		);

		if ($string == '')
		{
			$string = $pre_string . $string . $post_string;

			return;
		}

		if (@preg_match($regex . 'u', $string))
		{
			$regex .= 'u';
		}

		$matches = array();
		$protects = array();

		if (
			!nnText::stringContains($string, $this->params->start_tags)
			|| !preg_match_all($regex, $string, $matches, PREG_SET_ORDER)
		)
		{
			$string = $pre_string . $string . $post_string;

			return;
		}

		foreach ($matches as $match)
		{
			if ($this->processMatch($string, $match, $area))
			{
				continue;
			}

			$protected = $this->params->protect_start . base64_encode($match['0']) . $this->params->protect_end;
			$string = str_replace($match['0'], $protected, $string);
			$protects[] = array($match['0'], $protected);
		}

		unset($matches);

		foreach ($protects as $protect)
		{
			$string = str_replace($protect['1'], $protect['0'], $string);
		}

		$string = $pre_string . $string . $post_string;
	}

	function processMatch(&$string, &$match, $area = 'articles')
	{
		$html = '';

		if (!empty($this->params->message))
		{
			if ($this->params->place_comments)
			{
				$html = $this->params->message_start . $this->params->message . $this->params->message_end;
			}

			$string = str_replace($match['0'], $html, $string);

			return true;
		}

		if (count($match) < 10)
		{
			array_unshift($match, $match['0'], '');
			$match['2'] = '';
			array_push($match, '', '');
		}

		$p_start = $match['1'];
		$br1a = $match['2'];
		$div_start = $match['3'];
		$br2a = $match['4'];
		$type = trim($match['5']);
		$id = trim($match['6']);
		$br2a = $match['7'];
		$div_end = $match['8'];
		$br2b = $match['9'];
		$p_end = $match['10'];

		$type = trim($type);
		$id = trim($id);

		$chrome = '';
		$forcetitle = 0;

		$ignores = array();
		$overrides = array();

		$vars = str_replace('\|', '[:MA_BAR:]', $id);
		$vars = explode('|', $vars);
		$id = array_shift($vars);

		foreach ($vars as $var)
		{
			$var = trim(str_replace('[:MA_BAR:]', '|', $var));

			if (!$var)
			{
				continue;
			}

			if (strpos($var, '=') === false)
			{
				if ($this->params->override_style)
				{
					$chrome = $var;
				}

				continue;
			}

			if ($type != $this->params->module_tag)
			{
				continue;
			}

			list($key, $val) = explode('=', $var, 2);
			$val = str_replace(array('\{', '\}'), array('{', '}'), $val);

			switch ($key)
			{
				case 'style':
					$chrome = $val;
					break;

				case 'ignore_access':
				case 'ignore_state':
				case 'ignore_assignments':
				case 'ignore_caching':
					$ignores[$key] = $val;
					break;

				case 'showtitle':
					$overrides['showtitle'] = $val;
					$forcetitle = $val;
					break;

				default:
					break;
			}
		}

		if (!$chrome)
		{
			$chrome = ($forcetitle) ? 'xhtml' : $this->params->style;
		}

		if ($type == $this->params->module_tag)
		{
			// module
			$html = $this->processModule($id, $chrome, $ignores, $overrides, $area);
			if ($html == 'MA_IGNORE')
			{
				return false;
			}
		}
		else
		{
			// module position
			$html = $this->processPosition($id, $chrome);
		}

		if ($p_start && $p_end)
		{
			$p_start = '';
			$p_end = '';
		}

		$html = $br1a . $br2a . $html . $br2a . $br2b;

		if ($div_start)
		{
			$extra = trim(preg_replace('#\{div(.*)\}#si', '\1', $div_start));
			$div = '';
			if ($extra)
			{
				$extra = explode('|', $extra);
				$extras = new stdClass;
				foreach ($extra as $e)
				{
					if (strpos($e, ':') !== false)
					{
						list($key, $val) = explode(':', $e, 2);
						$extras->$key = $val;
					}
				}
				if (isset($extras->class))
				{
					$div .= 'class="' . $extras->class . '"';
				}

				$style = array();
				if (isset($extras->width))
				{
					if (is_numeric($extras->width))
					{
						$extras->width .= 'px';
					}
					$style[] = 'width:' . $extras->width;
				}
				if (isset($extras->height))
				{
					if (is_numeric($extras->height))
					{
						$extras->height .= 'px';
					}
					$style[] = 'height:' . $extras->height;
				}

				if (isset($extras->align))
				{
					$style[] = 'float:' . $extras->align;
				}
				else if (isset($extras->float))
				{
					$style[] = 'float:' . $extras->float;
				}

				if (!empty($style))
				{
					$div .= ' style="' . implode(';', $style) . ';"';
				}
			}
			$html = trim('<div ' . trim($div)) . '>' . $html . '</div>';

			$html = $p_end . $html . $p_start;
		}
		else
		{
			$html = $p_start . $html . $p_end;
		}

		nnText::fixHtmlTagStructure($html);

		if ($this->params->place_comments)
		{
			$html = $this->params->comment_start . $html . $this->params->comment_end;
		}

		$string = str_replace($match['0'], $html, $string);
		unset($match);

		return $id;
	}

	function processPosition($position, $chrome = 'none')
	{
		$renderer = JFactory::getDocument()->loadRenderer('module');

		$html = array();
		foreach (JModuleHelper::getModules($position) as $module)
		{
			$module_html = $renderer->render($module, array('style' => $chrome));


			$html[] = $module_html;
		}

		return implode('', $html);
	}

	function processModule($id, $chrome = '', $ignores = array(), $overrides = array(), $area = 'articles')
	{
		$ignore_access = isset($ignores['ignore_access']) ? $ignores['ignore_access'] : $this->params->ignore_access;
		$ignore_state = isset($ignores['ignore_state']) ? $ignores['ignore_state'] : $this->params->ignore_state;
		$ignore_assignments = isset($ignores['ignore_assignments']) ? $ignores['ignore_assignments'] : $this->params->ignore_assignments;
		$ignore_caching = isset($ignores['ignore_caching']) ? $ignores['ignore_caching'] : $this->params->ignore_caching;

		$db = JFactory::getDBO();
		$query = $db->getQuery(true)
			->select('m.*')
			->from('#__modules AS m')
			->where('m.client_id = 0');
		if (is_numeric($id))
		{
			$query->where('m.id = ' . (int) $id);
		}
		else
		{
			$query->where('m.title = ' . $db->quote(nnText::html_entity_decoder($id)));
		}
		if (!$ignore_access)
		{
			$query->where('m.access IN (' . implode(',', $this->aid) . ')');
		}
		if (!$ignore_state)
		{
			$query->where('m.published = 1')
				->join('LEFT', '#__extensions AS e ON e.element = m.module AND e.client_id = m.client_id')
				->where('e.enabled = 1');
		}
		if (!$ignore_assignments)
		{
			$date = JFactory::getDate();
			$now = $date->toSql();
			$nullDate = $db->getNullDate();
			$query->where('(m.publish_up = ' . $db->quote($nullDate) . ' OR m.publish_up <= ' . $db->quote($now) . ')')
				->where('(m.publish_down = ' . $db->quote($nullDate) . ' OR m.publish_down >= ' . $db->quote($now) . ')');
		}
		$query->order('m.ordering');
		$db->setQuery($query);
		$module = $db->loadObject();

		if ($module && !$ignore_assignments)
		{
			$this->applyAssignments($module);
		}

		if (empty($module))
		{
			if ($this->params->place_comments)
			{
				return $this->params->message_start . JText::_('MA_OUTPUT_REMOVED_NOT_PUBLISHED') . $this->params->message_end;
			}

			return '';
		}

		//determine if this is a custom module
		$module->user = (substr($module->module, 0, 4) == 'mod_') ? 0 : 1;

		// set style
		$module->style = $chrome ?: 'none';

		if (($area == 'articles' && !$ignore_caching) || !empty($overrides))
		{
			$json = ($module->params && substr(trim($module->params), 0, 1) == '{');
			if ($json)
			{
				$params = json_decode($module->params);
			}
			else
			{
				// Old ini style. Needed for crappy old style modules like swMenuPro
				$params = JRegistryFormat::getInstance('INI')->stringToObject($module->params);
			}

			// override module parameters
			if (!empty($overrides))
			{
				foreach ($overrides as $key => $val)
				{
					if (isset($module->{$key}))
					{
						$module->{$key} = $val;
					}
					else
					{
						if ($val && $val['0'] == '[' && $val[strlen($val) - 1] == ']')
						{
							$val = json_decode('{"val":' . $val . '}');
							$val = $val->val;
						}
						else if (isset($params->{$key}) && is_array($params->{$key}))
						{
							$val = explode(',', $val);
						}
						$params->{$key} = $val;
					}
				}
				if ($json)
				{
					$module->params = json_encode($params);
				}
				else
				{
					$registry = new JRegistry;
					$registry->loadObject($params);
					$module->params = $registry->toString('ini');
				}
			}
		}

		if (isset($module->access) && !in_array($module->access, $this->aid))
		{
			if ($this->params->place_comments)
			{
				return $this->params->message_start . JText::_('MA_OUTPUT_REMOVED_ACCESS') . $this->params->message_end;
			}

			return '';
		}

		// Set style in params to override the chrome override in module settings
		if ($chrome)
		{
			$params = json_decode($module->params);

			if (isset($params->style) && strpos($params->style, '-'))
			{
				$params->style = explode('-', $params->style, 2);
				$params->style = $params->style['0'] . '-';
			}

			$params->style = isset($params->style) ? $params->style . $chrome : $chrome;
			$module->params = json_encode($params);
		}

		$document = clone(JFactory::getDocument());
		$document->_type = 'html';
		$renderer = $document->loadRenderer('module');
		$html = $renderer->render($module, array('style' => $module->style, 'name' => ''));


		// don't return html on article level when caching is set
		if (
			$area == 'articles'
			&& !$ignore_caching
			&& (
				(isset($params->cache) && !$params->cache)
				|| (isset($params->owncache) && !$params->owncache) // for stupid modules like RAXO that mess about with default params
			)
		)
		{
			return 'MA_IGNORE';
		}

		return $html;
	}


	function applyAssignments(&$module)
	{
		$this->setModulePublishState($module);

		if (!$module->published)
		{
			$module = null;
		}
	}

	function setModulePublishState(&$module)
	{
		$module->published = true;

		// for old Advanced Module Manager versions
		if (function_exists('plgSystemAdvancedModulesPrepareModuleList'))
		{
			$modules = array($module->id => $module);
			plgSystemAdvancedModulesPrepareModuleList($modules);
			$module = array_shift($modules);

			return;
		}

		// for new Advanced Module Manager versions
		if (class_exists('plgSystemAdvancedModuleHelper'))
		{
			$modules = array($module->id => $module);
			$helper = new plgSystemAdvancedModuleHelper;
			$helper->onPrepareModuleList($modules);
			$module = array_shift($modules);

			return;
		}

		// for core Joomla
		$db = JFactory::getDBO();
		$query = $db->getQuery(true)
			->select('mm.moduleid')
			->from('#__modules_menu AS mm')
			->where('mm.moduleid = ' . (int) $module->id)
			->where('(mm.menuid = ' . ((int) JFactory::getApplication()->input->getInt('Itemid')) . ' OR mm.menuid <= 0)');
		$db->setQuery($query);
		$result = $db->loadResult();
		$module->published = !empty($result);
	}

	function protect(&$string)
	{
		nnProtect::protectFields($string);
		nnProtect::protectSourcerer($string);
	}

	function protectTags(&$string)
	{
		nnProtect::protectTags($string, $this->params->protected_tags);
	}

	function unprotectTags(&$string)
	{
		nnProtect::unprotectTags($string, $this->params->protected_tags);
	}

	/**
	 * Just in case you can't figure the method name out: this cleans the left-over junk
	 */
	function cleanLeftoverJunk(&$string)
	{
		$this->unprotectTags($string);

		$string = preg_replace('#<\!-- (START|END): MODA_[^>]* -->#', '', $string);

		if ($this->params->place_comments)
		{
			return;
		}

		$string = str_replace(
			array(
				$this->params->comment_start, $this->params->comment_end,
				htmlentities($this->params->comment_start), htmlentities($this->params->comment_end),
				urlencode($this->params->comment_start), urlencode($this->params->comment_end)
			), '', $string
		);
		$string = preg_replace('#' . preg_quote($this->params->message_start, '#') . '.*?' . preg_quote($this->params->message_end, '#') . '#', '', $string);
	}
}
