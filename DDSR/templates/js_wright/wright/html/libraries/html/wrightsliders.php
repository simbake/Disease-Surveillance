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

require_once(JPATH_PLATFORM . '/joomla/html/html/sliders.php');

/**
 * Utility class for Sliders elements
 *
 * @package     Joomla.Platform
 * @subpackage  HTML
 * @since       11.1
 */
abstract class JHtmlWrightSliders extends JHtmlSliders
{
	/**
	 * Begins the display of a new panel.
	 *
	 * @param   string  $text  Text to display.
	 * @param   string  $id    Identifier of the panel.
	 *
	 * @return  string  HTML to start a panel
	 *
	 * @since   11.1
	 */
	public static function panel($text, $id)
	{
		return '</div></div><div class="panel"><h3 class="pane-toggler title" id="' . $id . '"><a href="javascript:void(0);"><span>' . $text
			. '</span></a></h3><div class="pane-slider content">';
			// Wright v.3: Added Icon
	}

}
