<?php
// Wright v.3 Override: Joomla 2.5.14
/**
 * @package		Joomla.Site
 * @subpackage	mod_breadcrumbs
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

$separator = '<span class="divider"><i class="icon-caret-right"></i></span>';  // Wright v.3: Joomla 3.x separator style

?>

<div class="breadcrumbs<?php echo $moduleclass_sfx; ?>">
	<ul class="breadcrumb">  <?php // Wright v.3: Added breadcrumb list ?>
		<?php if ($params->get('showHere', 1))
			{
		?>
			<?php // Wright v.3: New "show here" marker
			echo '<li><span class="divider icon-map-marker hasTip" title="' . JText::_('MOD_BREADCRUMBS_HERE') . '"></span></li>';
				//Wright v.3: Commented out original first marker.   echo '<span class="showHere">' .JText::_('MOD_BREADCRUMBS_HERE').'</span>';
			}

			// Get rid of duplicated entries on trail including home page when using multilanguage
			for ($i = 0; $i < $count; $i ++)
			{
				if ($i == 1 && !empty($list[$i]->link) && !empty($list[$i-1]->link) && $list[$i]->link == $list[$i-1]->link)
				{
					unset($list[$i]);
				}
			}

			// Find last and penultimate items in breadcrumbs list
			end($list);
			$last_item_key = key($list);
			prev($list);
			$penult_item_key = key($list);

			// Generate the trail
			foreach ($list as $key=>$item) :
			// Make a link if not the last item in the breadcrumbs
			$show_last = $params->get('showLast', 1);
			if ($key != $last_item_key)
			{
				// Render all but last item - along with separator
				echo '<li>';  // Wright v.3: Added <li> tag
				if (!empty($item->link))
				{
					echo '<a href="' . $item->link . '" class="pathway">' . $item->name . '</a>';
				}
				else
				{
					echo '<span>' . $item->name . '</span>';
				}

				if (($key != $penult_item_key) || $show_last)
				{
					echo ' '.$separator.' ';
				}
				echo '</li>';  // Wright v.3: Added <li> tag

			}
			elseif ($show_last)
			{
				// Render last item if read.
				echo '<li><span>' . $item->name . '</span></li>';  // Wright v.3: Added <li> tag
			}
			endforeach; ?>
	</ul>  <?php // Wright v.3: Added breadcrumb list ?>
</div>
