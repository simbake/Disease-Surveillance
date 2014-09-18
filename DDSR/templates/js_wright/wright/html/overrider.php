<?php
/**
 * @package     Wright
 * @subpackage  Overrider
 *
 * @copyright   Copyright (C) 2005 - 2013 Joomlashack. Meritage Assets.  All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
include_once(JPATH_THEMES.'/'.JFactory::getApplication()->getTemplate().'/wright/html/libraries/wrighthtml.php');
include_once(JPATH_THEMES.'/'.JFactory::getApplication()->getTemplate().'/wright/html/jlayouthelper.php');

class Overrider
{
	static $version;

	public static function getVersion()
	{
		if (!isset(self::$version)) {
			jimport('joomla.version');
			$version = new JVersion();
			self::$version = explode('.', $version->RELEASE);
		}

		return self::$version;
	}

	public static function getOverride($extension, $layout = 'default', $strictOverride = false)
	{
		$type = substr($extension, 0, 3);

		$file = '';

		$app = JFactory::getApplication();

        $version = self::getVersion();

		switch ($type)
		{
			case 'mod' :
				$fileFound = false;
				$subversion = $version[1];
				while (!$fileFound && $subversion >= 0) {
	                if (is_file(JPATH_THEMES.'/'.$app->getTemplate().'/'.'wright'.'/'.'html'.'/'.'joomla_'.$version[0].'.'.$subversion.'/'.$extension.'/'.$layout.'.php')) {
	                	$fileFound = true;
						$file = JPATH_THEMES.'/'.$app->getTemplate().'/'.'wright'.'/'.'html'.'/'.'joomla_'.$version[0].'.'.$subversion.'/'.$extension.'/'.$layout.'.php';
	                }
	                $subversion--;
				}
				if (!$fileFound) {
					if ($strictOverride) return false;
					$file = JPATH_SITE.'/modules/'.$extension.'/tmpl/'.$layout.'.php';
				}
				break;

			case 'com' :
				$fileFound = false;
				$subversion = $version[1];
				list($folder, $view) = explode('.', $extension);
				while (!$fileFound && $subversion >= 0) {
	                if (is_file(JPATH_THEMES.'/'.$app->getTemplate().'/'.'wright'.'/'.'html'.'/'.'joomla_'.$version[0].'.'.$subversion.'/'.$folder.'/'.$view.'/'.$layout.'.php')) {
	                	$fileFound = true;
						$file = JPATH_THEMES.'/'.$app->getTemplate().'/'.'wright'.'/'.'html'.'/'.'joomla_'.$version[0].'.'.$subversion.'/'.$folder.'/'.$view.'/'.$layout.'.php';
	                }
	                $subversion--;
				}
				if (!$fileFound) {
					if ($strictOverride) return false;
					$file = JPATH_SITE.'/components/'.$folder.'/views/'.$view.'/tmpl/'.$layout.'.php';		
				}
				break;

			case 'lyt' :
				// overriding layouts (Joomla 3.1+): lyt_xx.yy.zz (joomla/content/info_block)
				$fileFound = false;
				$override = str_replace('.', '/', substr($extension, 4));
				$subversion = $version[1];
				while (!$fileFound && $subversion >= 0) {
	                if (is_file(JPATH_THEMES.'/'.$app->getTemplate().'/'.'wright'.'/'.'html'.'/'.'joomla_'.$version[0].'.'.$subversion.'/layouts/'.$override.'.php')) {
	                	$fileFound = true;
						$file = JPATH_THEMES.'/'.$app->getTemplate().'/'.'wright'.'/'.'html'.'/'.'joomla_'.$version[0].'.'.$subversion.'/layouts/'.$override.'.php';
	                }
	                $subversion--;
				}
				if (!$fileFound) {
					if ($strictOverride) return false;
					$file = JPATH_SITE.'/layouts/'.$override.'.php';
				}
				break;
		}
		return $file;
	}
}
