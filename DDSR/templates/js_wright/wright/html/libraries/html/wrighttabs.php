<?php
// Wright v.3 Override: Joomla 2.5.14
/**
 * @package     Joomla.Platform
 * @subpackage  HTML
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

require_once(JPATH_PLATFORM . '/joomla/html/html/tabs.php');

abstract class JHtmlWrightTabs extends JHtmlTabs
{
	/**
	 * Creates a panes and creates the JavaScript object for it.
	 *
	 * @param   string  $group   The pane identifier.
	 * @param   array   $params  An array of option.
	 *
	 * @return  string
	 *
	 * @since   11.1
	 */
	public static function start($group = 'tabs', $params = array())
	{
		self::_loadBehavior($group, $params);

		return '<dl class="tabs nav nav-tabs" id="' . $group . '"><dt style="display:none;"></dt><dd style="display:none;">';  // Wright v.3: Added nav nav-tabs classes
	}

	/**
	 * Begins the display of a new panel.
	 *
	 * @param   string  $text  Text to display.
	 * @param   string  $id    Identifier of the panel.
	 *
	 * @return  string  HTML to start a new panel
	 *
	 * @since   11.1
	 */
	public static function panel($text, $id)
	{
		return '</dd><dt class="tabs ' . $id . '"><span><p><a href="javascript:void(0);">' . $text . '</a></p></span></dt><dd class="tabs">';  // Wright v.3: Added nav class
	}

}
