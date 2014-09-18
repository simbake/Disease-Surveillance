<?php
// Wright v.3 Override: Joomla 3.1.5
/**
 * @package     Joomla.Site
 * @subpackage  mod_users_latest
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<?php if (!empty($names)) : ?>
	<ul class="latestusers<?php echo $moduleclass_sfx ?> nav nav-list">  <?php // Wright v.3: Added nav nav-list classes ?>
	<?php foreach ($names as $name) : ?>
		<li>
			<i class="icon-user"></i>  <?php // Wright v.3: Added icon ?>
			<?php echo $name->username; ?>
		</li>
	<?php endforeach;  ?>
	</ul>
<?php endif; ?>
