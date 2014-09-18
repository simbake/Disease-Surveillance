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
<div class="tagspopular<?php echo $moduleclass_sfx; ?>">
<ul class="nav nav-list" > <?php // Wright v.3: nav-list ?>
<?php foreach ($list as $item) :	?>
<li><?php $route = new TagsHelperRoute; ?>
	<a href="<?php echo JRoute::_(TagsHelperRoute::getTagRoute($item->tag_id . ':' . $item->alias)); ?>">
		<i class="icon-tag icons-left"></i>  <?php // Wright v.3: Added icon ?>
		<?php echo htmlspecialchars($item->title); ?></a>
</li>
<?php endforeach; ?>
</ul>
</div>
