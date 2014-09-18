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

// Wright v.3: Created additional structure for icons
$structIcons = '';
$span1 = '';
$span2 = '';
if (preg_match_all('/icon-([\S]+)/', $item->anchor_css, $matches)) {
	$item->anchor_css = preg_replace('/icon-([\S]+)/', '', $item->anchor_css);
	$icons = 'icon-' . implode(' icon-',$matches[1]);
	$structIcons = '<i class="' . $icons . '"></i>';
}
if (preg_match_all('/hide-text/', $item->anchor_css, $matches)) {
	$span1 = '<span class="hidden-text">';
	$span2 = '</span>';
}
// End Wright v.3: Created additional structure for icons

// Note. It is important to remove spaces between elements.
$class = ($item->anchor_css || $item->deeper) ? 'class="'.$item->anchor_css. ($item->deeper ? ' dropdown-toggle' : '') . '" ' : '';  // Wright v.3:  Added parent classes for Bootstrap (removed "disable")
$title = $item->anchor_title ? 'title="'.$item->anchor_title.'" ' : '';
$toggle = $item->deeper ? ' data-toggle="dropdown-menus"' : '';  // Wright v.3: Added data-toggle attribute to parents
$caret = $item->deeper ? ($item->level > 1 ? '<i class="icon-caret-right"></i>' : '<b class="caret"></b>') : '';  // Wright v.3: Added caret

if ($item->menu_image) {
		$item->params->get('menu_text', 1 ) ?
		$linktype = $span1 . '<img src="'.$item->menu_image.'" alt="'.$item->title.'" /><span class="image-title">'.$item->title.'</span> ' . $span2 : // Wright v.3: Added optional spans
		$linktype = $span1 . '<img src="'.$item->menu_image.'" alt="'.$item->title.'" />' . $span2; // Wright v.3: Added optional spans
}
else { $linktype = $span1 . $item->title . $span2; // Wright v.3: Added optional spans
}

$flink = $item->flink;
$flink = JFilterOutput::ampReplace(htmlspecialchars($flink));

switch ($item->browserNav) :
	default:
	case 0:
?><a <?php echo $class . $toggle; // Wright v.3: Added toggle for submenus ?> href="<?php echo $flink; ?>" <?php echo $title; ?>><?php echo $structIcons . $linktype; // Wright v.3: Added icons structure ?><?php echo $caret // Wright v.3: Added caret ?></a><?php
		break;
	case 1:
		// _blank
?><a <?php echo $class . $toggle; // Wright v.3: Added toggle for submenus ?> href="<?php echo $flink; ?>" target="_blank" <?php echo $title; ?>><?php  echo $structIcons . $linktype; // Wright v.3: Added icons structure ?><?php echo $caret // Wright v.3: Added caret ?></a><?php
		break;
	case 2:
		// window.open
		$options = 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,'.$params->get('window_open');
			?><a <?php echo $class . $toggle; // Wright v.3: Added toggle for submenus ?> href="<?php echo $flink; ?>" onclick="window.open(this.href,'targetWindow','<?php echo $options;?>');return false;" <?php echo $title; ?>><?php  echo $structIcons . $linktype; // Wright v.3: Added icons structure ?><?php echo $caret // Wright v.3: Added caret ?></a><?php
		break;
endswitch;
