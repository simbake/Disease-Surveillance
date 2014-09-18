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
			<dd class="parent-category-name">
				<span class="icon-circle-arrow-up"></span> <?php // Wright v.3: Changed icon ?>
				<?php $title = $this->escape($displayData['item']->parent_title);
				$url = '<a href="'.JRoute::_(ContentHelperRoute::getCategoryRoute($displayData['item']->parent_slug)).'">'.$title.'</a>';?>
				<?php if ($displayData['params']->get('link_parent_category') && !empty($displayData['item']->parent_slug)) : ?>
					<?php echo '<span class="hidden-phone"> ' . JText::sprintf('COM_CONTENT_PARENT', $url) . '</span>';  // Wright v.3: Non-mobile version
						echo '<span class="visible-phone"> ' . JText::sprintf($url) . '</span>';  // Wright v.3: Mobile version
					?>
				<?php else : ?>
					<?php echo '<span class="hidden-phone"> ' . JText::sprintf('COM_CONTENT_PARENT', $title) . '</span>';  // Wright v.3: Non-mobile version
						echo '<span class="visible-phone"> ' . JText::sprintf($title) . '</span>';  // Wright v.3: Mobile version
					?>
				<?php endif; ?>
			</dd>
