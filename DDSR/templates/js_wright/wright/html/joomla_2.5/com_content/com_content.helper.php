<?php
// Wright v.3 Override Helper: Joomla 2.5.11
/**
 * @version
 * @package		Wright
 * @subpackage	Overrides
 * @copyright	Copyright (C) 2005 - 2013 Joomlashack / Meritage Assets. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
 
 
 // no direct access
 defined('_JEXEC') or die;
 
function wrightTransformArticleContent($content) {
	// Page Break Plugin
	$content = preg_replace("/dl([^>]*)class=\"tabs\"/Uis", 'dl$1class="tabs nav nav-tabs"', $content);  // Add tabs
 	$content = preg_replace("/<div class=\"pagination\">(.*)<li>([^>]*)<\/li>(.*)<\/div>/Uis", "<div class=\"pagination\">$1<li class=\"disabled\"><a href=\"#\" />$2</a></li>$3</div>", $content);  // Inside pagination

	return $content;
}

function wrightTransformArticleTOC($content) {
	$content = preg_replace("/<div id=\"article-index\">(.*)<ul>(.*)<\/div>/Uis", "<div id=\"article-index\">$1<ul class=\"nav nav-tabs nav-stacked\">$2</div>", $content);

	return $content;
}

function wrightTransformArticlePagination($content) {
	$content = preg_replace("/<li([^>]*)class=\"([^\"]*)\"([^>]*)>([^<]*)<span([^>]*)>([^<]*)<\/span>([^<]*)<\/li>/iUs", "<li$1class=\"$2 disabled\"$3>$4<a$5 href=\"#\">$6</a>$7</li>", $content);
	$content = preg_replace("/<li([^>]*)>([^<]*)<span([^>]*)>([0-9]+)<\/span>([^<]*)<\/li>/iUs", "<li$1 class=\"active\">$2<a$3 href=\"#\">$4</a>$5</li>", $content);
	return $content;
}

function wrightTransformArticlePager($content) {
	$content = preg_replace("/<ul class=\"pagenav\">/iUs", "<ul class=\"pagenav pager\">", $content);
	$content = preg_replace("/<li class=\"pagenav-next\">/iUs", "<li class=\"pagenav-next next\">", $content);
	$content = preg_replace("/<li class=\"pagenav-prev\">/iUs", "<li class=\"pagenav-prev previous\">", $content);
	return $content;
}


?>
