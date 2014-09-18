<?php
/**
* @version		2.6
* @author		Michael A. Gilkes (jaido7@yahoo.com)
* @copyright	Michael Albert Gilkes
* @license		GNU/GPLv2
*/

/*

Easy Folder Listing Module for Joomla!
Copyright (C) 2010-2013 Michael Albert Gilkes

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

// no direct access
defined('_JEXEC') or die('Restricted access');

// Include the syndicate functions only once
require_once (dirname(__FILE__).DIRECTORY_SEPARATOR.'helper.php');

//get the module class designation
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

//specify the folder
$folder = modEasyFolderListingHelper::folderPath($params);

$app = JFactory::getApplication();
if($app->isAdmin())
{
	//set the working directory to the Joomla root
	chdir("..");
}
//check to see if the upload process has started
$rows = modEasyFolderListingHelper::getFormattedListing($params, $folder);
if($app->isAdmin())
{
	//return the working directory to what it was before
	chdir("administrator");
}

//use the html table
$layout = 'default';

//format the display
if ($params->get('efl_method') == "list")
{
	//use the unordered list
	$layout = 'list';
}
		
require(JModuleHelper::getLayoutPath('mod_easyfolderlisting', $layout));
