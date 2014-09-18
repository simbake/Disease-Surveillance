<?php
/**
* @version		2.5
* @author		Michael A. Gilkes (jaido7@yahoo.com)
* @copyright	Michael Albert Gilkes
* @license		GNU/GPLv2
*/

/*

Easy Folder Listing Module for Joomla!
Copyright (C) 2010-2012  Michael Albert Gilkes

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

*/

//no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

//get the total number of files
$total = count($rows); ?>

<table class="easyfolderlisting <?php echo $params->get('moduleclass_sfx');?>" style="width:100%; border-collapse:separate; border-spacing:1px; background-color:<?php echo $params->get('efl_bordercolor');?>; text-align:left;">
	<tr style="background-color:<?php echo $params->get('efl_headcolor');?>;">
		<th style="border: 1px solid #FFFFFF; padding: 4px;"><?php echo JText::_('MOD_EFL_FILENAME');?></th>
		<?php if ($params->get('efl_size') == "yes") : ?>
		<th style="border: 1px solid #FFFFFF; padding: 4px;"><?php echo JText::_('MOD_EFL_SIZE');?></th>
		<?php endif; ?>
		<?php if ($params->get('efl_date') == "yes") : ?>
		<th style="border: 1px solid #FFFFFF; padding: 4px;"><?php echo (($params->get('efl_time') == "yes") ? JText::_('MOD_EFL_DATETIME') : JText::_('MOD_EFL_DATE')); ?></th>
		<?php endif; ?>
	</tr>
	<?php for ($i = 0; $i < $total; $i++) :
		//set the colour for the odd row
		$color = $params->get('efl_oddcolor');
		//is the row even
		if (($i+1)%2 == 0)
		{
			//set the colour for the even row
			$color = $params->get('efl_evencolor');
		}
	?>
	<tr style="background-color:<?php echo $color; ?>;">
		<td style="padding:1px;">
		<?php
		//show icons?
		if ($params->get('efl_icons') == "yes")
		{
			echo modEasyFolderListingHelper::attachIcon($rows[$i]['ext']);
		}
		
		//fix the name
		$fixedName = modEasyFolderListingHelper::fixLang($params, $rows[$i]['name']);
		
		//link it?
		if ($params->get('efl_linktofiles') == "yes")
		{
			echo '<a href="'.JURI::base().$folder.'/'.$fixedName.'.'.$rows[$i]['ext'].'">';
		}
		
		//show the file's name
		echo $fixedName;
		
		//show extension?
		if ($params->get('efl_extensions') == "yes")
		{
			echo '.'.$rows[$i]['ext'];
		}
		
		//close the tag, if we are linking it
		if ($params->get('efl_linktofiles') == "yes")
		{
			echo '</a>';
		}
		?>
		</td>
		<?php
		//show size?
		if ($params->get('efl_size') == "yes") : ?>
		<td style="padding:1px;"><?php echo $rows[$i]['size']; ?></td>
		<?php endif; 
		//show date?
		if ($params->get('efl_date') == "yes") : ?>
		<td style="padding:1px;"><?php echo modEasyFolderListingHelper::formatDate($params, $rows[$i]['date']); ?></td>
		<?php endif; ?>
	</tr>
	<?php endfor; ?>
</table>
