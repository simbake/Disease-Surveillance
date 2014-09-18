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

$blockPosition = $displayData['params']->get('info_block_position', 0);

?>
	<dl class="article-info  muted">

		<?php if ($displayData['position'] == 'above' && ($blockPosition == 0 || $blockPosition == 2)
				|| $displayData['position'] == 'below' && ($blockPosition == 1)
				) : ?>

			<dt class="article-info-term">
				<?php // TODO: implement info_block_show_title param to hide article info title ?>
				<?php if ($displayData['params']->get('info_block_show_title', 1)) : ?>
					<?php echo JText::_('COM_CONTENT_ARTICLE_INFO'); ?>
				<?php endif; ?>
			</dt>

			<?php if ($displayData['params']->get('show_author') && !empty($displayData['item']->author )) : ?>
				<?php echo JLayoutHelper::render('joomla.content.info_block.author', $displayData); ?>
			<?php endif; ?>

			<?php if ($displayData['params']->get('show_parent_category') && !empty($displayData['item']->parent_slug)) : ?>
				<?php echo JLayoutHelper::render('joomla.content.info_block.parent_category', $displayData); ?>
			<?php endif; ?>

			<?php if ($displayData['params']->get('show_category')) : ?>
				<?php echo JLayoutHelper::render('joomla.content.info_block.category', $displayData); ?>
			<?php endif; ?>

			<?php if ($displayData['params']->get('show_publish_date')) : ?>
				<?php echo JLayoutHelper::render('joomla.content.info_block.publish_date', $displayData); ?>
			<?php endif; ?>
		<?php endif; ?>

		<?php if ($displayData['position'] == 'above' && ($blockPosition == 0)
				|| $displayData['position'] == 'below' && ($blockPosition == 1 || $blockPosition == 2)
				) : ?>
			<?php if ($displayData['params']->get('show_create_date')) : ?>
				<?php echo JLayoutHelper::render('joomla.content.info_block.create_date', $displayData); ?>
			<?php endif; ?>

			<?php if ($displayData['params']->get('show_modify_date')) : ?>
				<?php echo JLayoutHelper::render('joomla.content.info_block.modify_date', $displayData); ?>
			<?php endif; ?>

			<?php if ($displayData['params']->get('show_hits')) : ?>
				<?php echo JLayoutHelper::render('joomla.content.info_block.hits', $displayData); ?>
			<?php endif; ?>
		<?php endif; ?>
	</dl>



	<?php

		/* Wright v.3: Added tags */
		if ($displayData['position'] == 'above' && ($blockPosition == 0 || $blockPosition == 2)
						|| $displayData['position'] == 'below' && ($blockPosition == 1)
						) :

			 if ($displayData['params']->get('show_tags', 1)) : ?>
				<?php $displayData['item']->tagLayout = new JLayoutFile('joomla.content.tags'); ?>
				<?php echo $displayData['item']->tagLayout->render($displayData['item']->tags->itemTags); ?>
			<?php endif;

		endif;
		/* End Wright v.3: Added tags */
	?>
