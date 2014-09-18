<?php
// Wright v.3 Override: Joomla 2.5.14
/**
 * @package		Joomla.Site
 * @subpackage	com_newsfeeds
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
?>
<?php

$lang = JFactory::getLanguage();
$myrtl = $this->newsfeed->rtl;
$direction = " ";

if ($lang->isRTL() && $myrtl == 0) {
	$direction = " redirect-rtl";
} elseif ($lang->isRTL() && $myrtl == 1) {
		$direction = " redirect-ltr";
	} elseif ($lang->isRTL() && $myrtl == 2) {
			$direction = " redirect-rtl";
		} elseif ($myrtl == 0) {
				$direction = " redirect-ltr";
			} elseif ($myrtl == 1) {
					$direction = " redirect-ltr";
				} elseif ($myrtl == 2) {
						$direction = " redirect-rtl";
					}
?>
<div class="newsfeed<?php echo $this->pageclass_sfx?><?php echo $direction; ?>">
<?php if ($this->params->get('show_page_heading')) : ?>
<div class="page-header">  <?php // Wright v.3: Added page header ?>
	<h1 class="<?php echo $direction; ?>">
		<?php echo $this->escape($this->params->get('page_heading')); ?>
	</h1>
</div>  <?php // Wright v.3: Added page header ?>
<?php endif; ?>
	<?php
	if (!$this->params->get('show_page_heading')) : ?>
	<div class="page-header">
	<?php endif;
		/* End Wright v.3: Added page header */
	?>
		<h2 class="<?php echo $direction; ?>">
			<a href="<?php echo $this->newsfeed->channel['link']; ?>" target="_blank">
				<?php echo str_replace('&apos;', "'", $this->newsfeed->channel['title']); ?></a>
		</h2>
	<?php
		/* Wright v.3: Added page header */
	if (!$this->params->get('show_page_heading')) : ?>
	</div>
	<?php endif;
		/* End Wright v.3: Added page header */
	?>
<!-- Show Description -->
<?php if ($this->params->get('show_feed_description')) : ?>
	<div class="feed-description">
		<?php echo str_replace('&apos;', "'", $this->newsfeed->channel['description']); ?>
	</div>
<?php endif; ?>

<!-- Show Image -->
<?php if (isset($this->newsfeed->image['url']) && isset($this->newsfeed->image['title']) && $this->params->get('show_feed_image')) : ?>
<div>
		<img src="<?php echo $this->newsfeed->image['url']; ?>" alt="<?php echo $this->newsfeed->image['title']; ?>" />
</div>
<?php endif; ?>

<!-- Show items -->
<ol class="list-striped">  <?php // Wright v.3: Added list-striped class ?>
	<?php foreach ($this->newsfeed->items as $item) :  ?>
		<li>
			<?php if (!is_null($item->get_link())) : ?>
				<h3>  <?php // Wright v.3: Added h3 ?>
					<a href="<?php echo $item->get_link(); ?>" target="_blank">
					<?php echo $item->get_title(); ?></a>
				</h3>  <?php // Wright v.3: Added h3 ?>
			<?php endif; ?>
			<?php if ($this->params->get('show_item_description') && $item->get_description()) : ?>
				<div class="feed-item-description">
				<?php $text = $item->get_description();
				if($this->params->get('show_feed_image', 0) == 0)
				{
					$text = JFilterOutput::stripImages($text);
				}
				$text = JHtml::_('string.truncate', $text, $this->params->get('feed_character_count'));
					echo str_replace('&apos;', "'", $text);
				?>

				</div>
			<?php endif; ?>
			</li>
		<?php endforeach; ?>
		</ol>
</div>
