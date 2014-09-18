<?php
// Wright v.3 Override: Joomla 3.1.5
/**
 * @package     Joomla.Site
 * @subpackage  mod_tags_popular
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

?>
<?php JLoader::register('TagsHelperRoute', JPATH_BASE . '/components/com_tags/helpers/route.php'); ?>
<div class="tagssimilar<?php echo $moduleclass_sfx; ?>">
<?php if ($list) : ?>
	<ul class="nav nav-list" > <?php // Wright v.3: Nav List ?>
	<?php foreach ($list as $i => $item) : ?>
		<li>
			<?php $item->route = new JHelperRoute; ?>
			<a href="<?php echo JRoute::_(TagsHelperRoute::getItemRoute($item->content_item_id, $item->core_alias, $item->core_catid, $item->core_language, $item->type_alias, $item->router)); ?>">
				<i class="icon-file icons-left"></i>  <?php // Wright v.3: Added icon ?>
				<?php if (!empty($item->core_title)) :
					echo htmlspecialchars($item->core_title);
				endif; ?>
			</a>
		</li>
	<?php endforeach; ?>
	</ul>
<?php else : ?>
	<span><?php echo JText::_('MOD_TAGS_SIMILAR_NO_MATCHING_TAGS'); ?></span>
<?php endif; ?>
</div>
