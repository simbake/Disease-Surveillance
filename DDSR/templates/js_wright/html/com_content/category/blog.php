<?php
/**
 * @package     Wright
 * @subpackage  Overrider
 *
 * @copyright   Copyright (C) 2005 - 2013 Joomlashack. Meritage Assets.  All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

$app = JFactory::getApplication();

$this->wrightLeadingIntroItemsClass = ''; //  Class added to the leading and intro articles (adds an extra wrapper)
$this->wrightLeadingItemsClass = '';  // Class added to the leading items container (all the leading articles)
$this->wrightLeadingExtraClass = ''; // Class added to each leading article
$this->wrightIntroItemsClass = '';  // Class added to the intro articles (adds an extra wrapper)
$this->wrightIntroRowsClass = ''; // Class added to each row of intro articles
$this->wrightIntroExtraClass = '';  // Class added to each intro article

$this->wrightComplementOuterClass = ''; // Class added to the complements (links, subcategories and pagination) - adds an extra wrapper for all of them
$this->wrightComplementExtraClass = ''; // Class added to each complement (links, subcategories and pagination - as blocks).  Adds an extra wrapper before the "Inner" div
$this->wrightComplementInnerClass = ''; // Class added to each complement (links, subcategories and pagination - as blocks).  Adds an extra wrapper when needed, or uses the existing one if found

// Optional structures to alter regular order or Joomla structure for leading and/or intro articles.  Possible items: title, icons, article-info, image, content.  Optional divs with IDs (#) and classes (.) can be added.  Divs can be closed with /div.  Every structure must be part of the array.
$this->wrightLeadingItemElementsStructure = Array();
$this->wrightIntroItemElementsStructure = Array();

require_once(JPATH_THEMES.'/'.$app->getTemplate().'/'.'wright'.'/'.'html'.'/'.'overrider.php');
include(Overrider::getOverride('com_content.category','blog'));
?>
