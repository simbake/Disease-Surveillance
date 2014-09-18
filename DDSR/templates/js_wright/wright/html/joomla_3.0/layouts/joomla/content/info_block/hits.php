<?php
// Wright v.3 Override: Joomla 3.1.5
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

?>
			<dd class="hits">
					<span class="icon-eye-open"></span>
					<?php echo '<span class="hidden-phone"> ' . JText::sprintf('COM_CONTENT_ARTICLE_HITS', $displayData['item']->hits) . '</span>';  // Wright v.3: Non-mobile version
					echo '<span class="visible-phone"> ' . JText::sprintf($displayData['item']->hits) . '</span>';  // Wright v.3: Mobile version
					?>
			</dd>
