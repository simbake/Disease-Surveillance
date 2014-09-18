<?php
/**
 * @package     Wright
 * @subpackage  Overrider
 *
 * @copyright   Copyright (C) 2005 - 2013 Joomlashack.  Meritage Assets.  All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// include only if Joomla is less than 3.1
if (version_compare(JVERSION, '3.1', 'lt')) {

	// JLayoutHelper class for using a similar class as that one included in Joomla 3.1 (only for Wright's overrides purposes)
	class JLayoutHelper {

		public function escape($output)
		{
			return htmlspecialchars($output, ENT_COMPAT, 'UTF-8');
		}

		// render a layout using the Wright Overrider
		public static function render($layout, $displayData) {
			if ($file = Overrider::getOverride('lyt.' . $layout,'',true)) {
				$layoutHelper = new JLayoutHelper();
				$layoutHelper->doRender($layout, $displayData, $file);
			}
		}

		public function doRender($layout, &$displayData, $file) {
			require($file);
		}

	}

}

