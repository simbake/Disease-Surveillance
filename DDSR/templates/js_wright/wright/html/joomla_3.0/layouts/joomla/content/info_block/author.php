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
			<dd class="createdby">
				<span class="icon-user"></span> <?php // Wright v.3: Added author icon ?>
				<?php $author = $displayData['item']->author; ?>
				<?php $author = ($displayData['item']->created_by_alias ? $displayData['item']->created_by_alias : $author); ?>
				<?php if (!empty($displayData['item']->contactid ) && $displayData['params']->get('link_author') == true) : ?>
					<?php
					echo '<span class="hidden-phone"> ' . JText::sprintf('COM_CONTENT_WRITTEN_BY',
						JHtml::_('link', JRoute::_('index.php?option=com_contact&view=contact&id='.$displayData['item']->contactid), $author)) . '</span>';  // Wright v.3: Non-mobile version
					echo '<span class="visible-phone"> ' . JText::sprintf(JHtml::_('link', JRoute::_('index.php?option=com_contact&view=contact&id='.$displayData['item']->contactid), $author)) . '</span>';  // Wright v.3: Mobile version
				 ?>
				<?php else :?>
					<?php echo '<span class="hidden-phone"> ' .  JText::sprintf('COM_CONTENT_WRITTEN_BY', $author) . '</span>';  // Wright v.3: Non-mobile version
						echo '<span class="visible-phone"> ' . JText::sprintf($author) . '</span>';  // Wright v.3: Mobile version ?>
				<?php endif; ?>
			</dd>
