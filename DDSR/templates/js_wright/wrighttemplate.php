<?php
/**
 * @package     Wright
 * @subpackage  TemplateBase
 *
 * @copyright   Copyright (C) 2005 - 2013 Joomlashack. Meritage Assets.  All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// Restrict Access to within Joomla
defined('_JEXEC') or die('Restricted access');

require_once(dirname(__FILE__).'/wright/template/wrighttemplatebase.php');

// WrightTemplate class, for special settings on Wright
class WrightTemplate extends WrightTemplateBase {
	public $templateName = 'js_wright';
}
