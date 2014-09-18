<?php
// Wright v.3 Override: Joomla 2.5.14
/**
 * @package		Joomla.Site
 * @subpackage	mod_articles_news
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
$item_heading = $params->get('item_heading', 'h4');
?>
<?php if ($params->get('item_title')) : ?>

	<<?php echo $item_heading; ?> class="newsflash-title<?php echo $params->get('moduleclass_sfx'); ?>">
	<?php if ($params->get('link_titles') && $item->link != '') : ?>
		<div class="page-header">  <?php // Wright v.3: Added page-header style ?>
			<a href="<?php echo $item->link;?>">
				<i class="icon-file"></i>  <?php // Wright v.3: Added icon ?>
				<?php echo $item->title;?>
			</a>
		</div>  <?php // Wright v.3: Added page-header style ?>
	<?php else : ?>
		<div class="page-header">  <?php // Wright v.3: Added page-header style ?>
			<i class="icon-file"></i>  <?php // Wright v.3: Added icon ?>
			<?php echo $item->title; ?>
		</div>  <?php // Wright v.3: Added page-header style ?>
	<?php endif; ?>
	</<?php echo $item_heading; ?>>

<?php endif; ?>

<?php
	/* Wright v.3: Added intro image */
	$images = json_decode($item->images);
	if ($params->get('image','1')) :
		if (isset($images->image_intro) and !empty($images->image_intro)) :
?>
	<div class="img-intro-left">
		<a href="<?php echo $item->link;?>">
			<img src="<?php echo $images->image_intro; ?>" class="" alt="<?php echo $images->image_intro_alt; ?>" />
		</a>
	</div>
<?php
		endif;
	endif;
	/* End Wright v.3: Added intro image */
?>

<?php if (!$params->get('intro_only')) :
	echo $item->afterDisplayTitle;
endif; ?>

<?php echo $item->beforeDisplayContent; ?>

<?php echo $item->introtext; ?>

<?php if (isset($item->link) && $item->readmore != 0 && $params->get('readmore')) :
	echo '<p class="readmore"><a class="readmore" href="'.$item->link.'">'.$item->linkText.'</a></p>';  // Wright v.3:  Added p.readmore
endif; ?>
