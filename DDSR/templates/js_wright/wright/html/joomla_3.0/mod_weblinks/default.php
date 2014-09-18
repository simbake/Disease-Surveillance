<?php
// Wright v.3 Override: Joomla 3.1.5
/**
 * @package     Joomla.Site
 * @subpackage  mod_weblinks
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<ul class="weblinks<?php echo $moduleclass_sfx; ?> list-striped">  <?php // Wright v.3: Added list-striped class ?>
<?php foreach ($list as $item) :	?>
<li>
	<?php
	$link = $item->link;
	switch ($params->get('target', 3))
	{
		case 1:
			// open in a new window
			echo '<a href="'. $link .'" target="_blank" rel="'.$params->get('follow', 'nofollow').'">'.
			'<i class="icon-link"></i>' .  // Wright v.3: Added icon
			htmlspecialchars($item->title, ENT_COMPAT, 'UTF-8') .'</a>';
			break;

		case 2:
			// open in a popup window
			echo "<a href=\"#\" onclick=\"window.open('". $link ."', '', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=780,height=550'); return false\">".
			'<i class="icon-link"></i>' .  // Wright v.3: Added icon
				htmlspecialchars($item->title, ENT_COMPAT, 'UTF-8') .'</a>';
			break;

		default:
			// open in parent window
			echo '<a href="'. $link .'" rel="'.$params->get('follow', 'nofollow').'">'.
			'<i class="icon-link"></i>' .  // Wright v.3: Added icon
				htmlspecialchars($item->title, ENT_COMPAT, 'UTF-8') .'</a>';
			break;
	}
	?>
	<?php if ($params->get('description', 0)) : ?>
		<?php echo nl2br($item->description); ?>
	<?php endif; ?>

	<?php if ($params->get('hits', 0)) : ?>
		<span class="label label-info"> <?php // Wright v.3: Added label label-info classes ?>
			<?php echo /*'(' .*/ $item->hits . ' ' . JText::_('MOD_WEBLINKS_HITS') /*. ')'*/; // Wright v.3: removed parenthesis ?>
		</span>
	<?php endif; ?>
</li>
<?php endforeach; ?>
</ul>
