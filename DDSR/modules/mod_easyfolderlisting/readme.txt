Easy Folder Listing v2.6

Copyright: Michael A. Gilkes
License: GNU/GPL v2


Requirements:
 > Joomla 1.5, 2.5 & 3.0+
 > PHP 5.3+
 

Description:
This is a simple-to-use module that is used to list the contents of a folder in either a table or a list. The folder listing can display the filename, with or without the extension, with or without the date modified and file size, as well as a icon representing the file type. It has the feature of allowing the user to specify whether the filename listed should be linked or not.


Main features:
 > List files in a specified sub-folder of the Joomla root directory
 > Show icons for file types
 > List files in either a table or a unordered list
 > Sorting in Acsending or Descening order, by filename, date modified or file size
 > Option to show/hide size, date, or date and time of the files
 > Option to link to the files or not
 > Option to specify a list of file types that should not be listed
 > Color scheme of the table rows and border can be customized
 > Module Manager colors available through custom Color Picker


Changes:
See CHANGELOG.php


Installation:
This module is designed for Joomla 1.5, 2.5 and 3.0. To install go to the install/uninstall extensions page of Joomla Administrator and upload the package file. Then go to the Module Manager page and activate the module.


Usage:
To use this module as content in an article, first ensure than the loadmodule plugin (Content - Load Module) is Enabled. Second, give an arbitrary Position to the Easy Folder Listing module, such as 'x_list', and configure the parameters. Thirdly, in the article, type '{loadposition x_list}'. Please ensure that the Menu Assignment is set to 'All' or to the specific article that it is to be displayed in. To control which types of users have access to this module, set the Access Level in the module.

Parameters:
Choose the Parent Folder - This is the parent folder where the files are stored. It is administered by a drop-down list. The default value is images.
Location within the Parent Folder - This is the actual folder, within the parent folder (above), where the files are kept. Leave blank to show the contents of the parent folder. The default value is blank.
Force encode UTF-8 - If your non-English filenames are not showing correctly, choose one of these methods to correct it. Default is set to none. This parameter can *ONLY* be specified in the Plugins Manager.
Source Encoding - Character Encoding of the filename text taken from the server file system. This parameter can *ONLY* be specified in the Plugins Manager.
Show Icons - If this option is set to Yes, each file will have an icon to describe the type of the file. The default value is Yes.
Show File Extensions - If this option is set to No, each file name will be displayed without the file extension. The default value is No.
Show File Size - If this option is set to Yes, the size of the file will  be shown. The default value is Yes.
Show Date - If this option is set to Yes, the date that each file was modified will be shown. The default value is Yes.
Show Time (with the Date above) - If this option is set to Yes, the time stamp that each file was modified will be shown. If it is set to No, then only the Date will be shown. Note that if Date (above) is not shown at all, then time will not show either. The default value is Yes.
Link To Files - If this option is set to Yes, each file will be hyperlinked for easy downloading. The default value is Yes.
Forbidden file types (separate by semi-colon) - This is a list of the file types that are forbidden to be listed (separated by semi-colon). The default value is htm;html;php.
Display Method - If Table is selected, the folder listing will be displayed in an HTML TABLE. If List is selected, it will be displayed using the Unordered List element. The default value is Table.
Sort Column - This specifies the column that the list is sorted by. The default value is Name.
Sort Direction - This specifies whether we are sorting by ascending or descending order. The default value is Ascending order.
Odd Table Row Background Color - The background color of the odd table row. The default value is #F0F0F6.
Even Table Row Background Color - The background color of the even table row. The default value is #FFFFFF.
Heading Row Background Color - The background color of the heading row. The default value is #E6EEEE.
Border Color - The color of the table's border. The default value is #CDCDCD.


Credits:
Silk icon set 1.3 by Mark James [ http://www.famfamfam.com/lab/icons/silk/ ]
MooRainbow 1.2b2 by Djamil Legato [ http://moorainbow.woolly-sheep.net/ ]
Hungarian Translation by Krisztián Feczkó [ http://www.madcat-studio.hu/ ]
German Translation by Dirk Vollmar
Spanish Translation by Sonja Gilkes
French Translation by John Yves

