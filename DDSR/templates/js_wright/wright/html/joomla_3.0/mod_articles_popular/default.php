<?php
// Wright v.3 Override: Joomla 3.1.5
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_popular
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<ul class="mostread<?php echo $moduleclass_sfx; ?> nav nav-list">  <?php // Wright v.3: Added nav nav-list classes ?>
<?php foreach ($list as $item) : ?>
	<li>
		<a href="<?php echo $item->link; ?>">
			<i class="icon-file"></i>  <?php // Wright v.3: Added icon ?>
			<?php echo $item->title; ?></a>
	</li>
<?php endforeach; ?>
</ul>
