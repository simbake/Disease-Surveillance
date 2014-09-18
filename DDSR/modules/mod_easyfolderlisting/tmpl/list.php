<?php
/**
* @version		1.1 (J16)
* @author		Michael A. Gilkes (jaido7@yahoo.com)
* @copyright	Michael Albert Gilkes
* @license		GNU/GPLv2
*/

/*

Easy Folder Listing Module for Joomla! 1.6+
Copyright (C) 2010  Michael Albert Gilkes

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
<ul class="easyfolderlisting <?php echo $params->get('moduleclass_sfx'); ?>" style="list-style:none;">
<?php for ($i = 0; $i < $total; $i++) : ?>
	<li>
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
	
	//show size?
	if ($params->get('efl_size') == "yes")
	{
		echo ' ['.$rows[$i]['size'].']';
	}
	
	//show date?
	if ($params->get('efl_date') == "yes")
	{
		echo ' ['.modEasyFolderListingHelper::formatDate($params, $rows[$i]['date']).']';
	}
	?>
	</li>
<?php endfor; ?>
</ul>

