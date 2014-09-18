<?php
// Wright v.3 Override: Joomla 2.5.14
/**
 * @package		Joomla.Site
 * @subpackage	com_contact
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

if ('plain' == $this->params->get('presentation_style')) :
	echo '<h3>'.JText::_('COM_CONTACT_LINKS').'</h3>';
else :
    echo JHtml::_($this->params->get('presentation_style').'.panel', JText::_('COM_CONTACT_LINKS'), 'display-links');
endif;
?>

<div class="contact-links">
	<ul class="nav nav nav-tabs nav-stacked"> <?php // Wright v.3: Added nav nav-tabs nav-stacked classes ?>
		<?php
		    foreach(range('a', 'e') as $char) :// letters 'a' to 'e'
			    $link = $this->contact->params->get('link'.$char);
			    $label = $this->contact->params->get('link'.$char.'_name');

			    if( ! $link) :
			        continue;
			    endif;

			    // Add 'http://' if not present
			    $link = (0 === strpos($link, 'http')) ? $link : 'http://'.$link;

			    // If no label is present, take the link
			    $label = ($label) ? $label : $link;
			    ?>
			<li>
				<a href="<?php echo $link; ?>">
					<i class="icon-link"></i>  <?php // Wright v.3: Added icon ?>
				    <?php echo $label;  ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</div>
