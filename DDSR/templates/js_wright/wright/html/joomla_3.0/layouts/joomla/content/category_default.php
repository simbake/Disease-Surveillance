<?php
// Wright v.3 Override: Joomla 3.1.5
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

// Note that this layout opens a div with the page class suffix. If you do not use the category children
// layout you need to close this div either by overriding this file or in your main layout.
$params  = $displayData->params;
$extension = $displayData->get('category')->extension;
$canEdit = $params->get('access-edit');
$className = substr($extension, 4);
// This will work for the core components but not necessarily for other components
// that may have different pluralisation rules.
if (substr($className, -1) == 's')
{
	$className = rtrim($className, 's');
}
$tagsData  = $displayData->get('category')->tags->itemTags;
?>
<div>
	<div class="<?php echo $className .'-category' . $displayData->pageclass_sfx;?>">
		<?php if ($params->get('show_page_heading')) : ?>
			<div class="page-header">  <?php // Wright v.3: Added page header ?>
				<h1>
					<?php echo $displayData->escape($params->get('page_heading')); ?>
				</h1>  <?php // Wright v.3: Added page header ?>
			</div>
		<?php endif; ?>
		<?php if($params->get('show_category_title', 1)) : ?>
			<?php /* Wright v.3: Adds page header if h1 is missing */
			if (!$params->get('show_page_heading')) : ?>
			<div class="page-header">
			<?php endif; /* End Wright v.3: Adds page header if h1 is missing */ ?>
			<h2>
				<?php echo JHtml::_('content.prepare', $displayData->get('category')->title, '', $extension.'.category'); ?>
			</h2>
			<?php /* Wright v.3: Adds page header if h1 is missing */
			if (!$params->get('show_page_heading')) : ?>
			</div>
			<?php endif; /* End Wright v.3: Adds page header if h1 is missing */ ?>
		<?php endif; ?>
		<?php if ($displayData->get('show_tags', 1)) : ?>
			<?php echo JLayoutHelper::render('joomla.content.tags', $tagsData); ?>
		<?php endif; ?>
		<?php if ($params->get('show_description', 1) || $params->def('show_description_image', 1)) : ?>
			<div class="category-desc">
				<?php if ($params->get('show_description_image') && $displayData->get('category')->getParams()->get('image')) : ?>
					<img src="<?php echo $displayData->get('category')->getParams()->get('image'); ?>"/>
				<?php endif; ?>
				<?php if ($params->get('show_description') && $displayData->get('category')->description) : ?>
					<?php echo JHtml::_('content.prepare', $displayData->get('category')->description, '', $extension .'.category'); ?>
				<?php endif; ?>
				<div class="clr"></div>
			</div>
		<?php endif; ?>
		<?php echo $displayData->loadTemplate($displayData->subtemplatename); ?>

		<?php if ($displayData->get('children') && $displayData->maxLevel != 0) : ?>
			<div class="cat-children">
				<h3>
					<?php echo JTEXT::_('JGLOBAL_SUBCATEGORIES'); ?>
				</h3>

				<?php echo $displayData->loadTemplate('children'); ?>
			</div>
		<?php endif; ?>
	</div>
</div>

