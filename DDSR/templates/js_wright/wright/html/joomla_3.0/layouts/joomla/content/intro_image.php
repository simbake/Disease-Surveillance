<?php
// Wright v.3 Override: Joomla 3.1.5
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
$params  = $displayData->params; // Wright v.3: Added params (fixed in Joomla 3.1.2)

?>
<?php $images = json_decode($displayData->images); ?>
<?php if (isset($images->image_intro) && !empty($images->image_intro)) : ?>
	<?php $imgfloat = (empty($images->float_intro)) ? $params->get('float_intro') : $images->float_intro; ?>
	<div class="pull-<?php echo htmlspecialchars($imgfloat); ?> item-image">
	<?php
	/* Wright v.3: Added link to the image from the article */
		if ($params->get('access-view')) :
	?>
		<a href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($displayData->slug, $displayData->catid)); ?>">
	<?php
		endif;
	/* End Wright v.3: Added link to the image from the article */
	?>
	<img
	<?php if ($images->image_intro_caption):
		echo 'class="caption ' . $displayData->wrightBootstrapImages . '"'.' title="' .htmlspecialchars($images->image_intro_caption) .'"';  // Wright .v.3: Added image class
	/* Wright v.3: Image class when no caption present */
	else:
		echo 'class="' . $displayData->wrightBootstrapImages . '"';
	/* End Wright v.3: Image class when no caption present */
	endif; ?>
	src="<?php echo htmlspecialchars($images->image_intro); ?>" alt="<?php echo htmlspecialchars($images->image_intro_alt); ?>"/>
	<?php
	/* Wright v.3: Added link to the image from the article */
		if ($params->get('access-view')) :
	?>
		</a>
	<?php
		endif;
	/* End Wright v.3: Added link to the image from the article */
	?>
	 </div>
<?php endif; ?>
