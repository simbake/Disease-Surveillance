<?php
// Wright v.3 Override: Joomla 2.5.14
/**
 * @package		Joomla.Site
 * @subpackage	mod_menu
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

// Note. It is important to remove spaces between elements.
$title = $item->anchor_title ? 'title="'.$item->anchor_title.'" ' : '';
if ($item->menu_image) {
		$item->params->get('menu_text', 1 ) ?
		$linktype = '<img src="'.$item->menu_image.'" alt="'.$item->title.'" /><span class="image-title">'.$item->title.'</span> ' :
		$linktype = '<img src="'.$item->menu_image.'" alt="'.$item->title.'" />';
}
else { $linktype = $item->title;
}

$class = ($item->deeper ? ' dropdown-toggle' : '');  // Wright v.3:  Added parent classes for Bootstrap


?><a href="#" class="separator<?php echo $class ?>" <?php echo $title; ?>><?php echo $linktype; ?><?php
// Wright v.3: Closing pseudo-link for sub-menus
if ($item->deeper) {
	// Opens a caret-right for levels 2 and above
	if ($item->level > 1)
		echo '<i class="icon-caret-right"></i>';
	else
		echo '<b class="caret"></b>';
}
?></a> <?php // Wright v.3 changed <span> for <a> for Bootstrap structure ?>
