<?php
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

	return $content;
}

function wrightTransformArticleTOC($content) {
	return $content;
}

function wrightTransformArticlePagination($content) {
	$content = preg_replace("/<li([^>]*)class=\"([^\"]*)\"([^>]*)>([^<]*)<span([^>]*)>([^<]*)<\/span>([^<]*)<\/li>/iUs", "<li$1class=\"$2 disabled\"$3>$4<a$5 href=\"#\">$6</a>$7</li>", $content);
	$content = preg_replace("/<li([^>]*)>([^<]*)<span([^>]*)>([0-9]+)<\/span>([^<]*)<\/li>/iUs", "<li$1 class=\"active\">$2<a$3 href=\"#\">$4</a>$5</li>", $content);
	return $content;
}

function wrightTransformArticlePager($content) {
	return $content;
}


?>
