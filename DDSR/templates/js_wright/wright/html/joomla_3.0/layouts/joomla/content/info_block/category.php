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
			<dd class="category-name">
				<span class="icon-folder-close"></span>
				<?php $title = $this->escape($displayData['item']->category_title);
				$url = '<a href="'.JRoute::_(ContentHelperRoute::getCategoryRoute($displayData['item']->catslug)).'">'.$title.'</a>';?>
				<?php if ($displayData['params']->get('link_category') && $displayData['item']->catslug) : ?>
					<?php echo '<span class="hidden-phone"> ' . JText::sprintf('COM_CONTENT_CATEGORY', $url) . '</span>';  // Wright v.3: Non-mobile version
						echo '<span class="visible-phone"> ' . JText::sprintf($url) . '</span>';  // Wright v.3: Mobile version
				?>
				<?php else : ?>
					<?php echo '<span class="hidden-phone"> ' . JText::sprintf('COM_CONTENT_CATEGORY', $title) . '</span>';  // Wright v.3: Non-mobile version
						echo '<span class="visible-phone"> ' . JText::sprintf($title) . '</span>';  // Wright v.3: Mobile version
					?>
				<?php endif; ?>
			</dd>
