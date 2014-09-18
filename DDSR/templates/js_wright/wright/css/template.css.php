<?php
// Set flag that this is a parent file.
define('_JEXEC', 1);
define('DS', DIRECTORY_SEPARATOR);

if (!defined('_JDEFINES')) {
	define('JPATH_BASE', dirname(__FILE__).'/../../../..');
	define('JPATH_WRIGHT_TEMPLATE', dirname(__FILE__).'/../..');
	require_once JPATH_BASE.'/includes/defines.php';
}

require_once JPATH_BASE.'/includes/framework.php';
require_once JPATH_WRIGHT_TEMPLATE . '/wrighttemplate.php';

$app = JFactory::getApplication('site');
$app->initialise();

$version = explode('.', JVERSION);
$mainversion = $version[0];
$subversion = $version[1];

$wrightTemplate = wrightTemplate::getInstance();
$template = $wrightTemplate->getTemplate();

$user = JFactory::getUser();
$style = JRequest::getVar('templateTheme',$user->getParam('theme',$template->params->get('style','generic')));

$version = "";

$fileFound = false;
$bootstrapOverride = JPATH_THEMES . '/' . $template->template . '/css/style-' . $style . '.bootstrap.min.css';

$file = '';
$fileext = '';

while (!$fileFound && $subversion >= 0) {
	$version = $mainversion . $subversion;
	if (file_exists(JPATH_THEMES . '/' . $template->template . '/css/joomla' . $version . '-' . $style . '-extended.css')) {
		$fileext = JPATH_THEMES . '/' . $template->template . '/css/joomla' . $version . '-' . $style . '-extended.css';
		$fileFound = true;
	}
	else
		$subversion--;
}

if (file_exists($bootstrapOverride)) {
	$file = $bootstrapOverride;
}
else {
	$file = JPATH_THEMES . '/' . $template->template . '/css/style-' . $style . '.css';
}

header("Content-Type: text/css");

echo '@import url("../fontawesome/css/font-awesome.min.css");' . "\n";

echo file_get_contents($file,'r');
if ($fileFound) {
	echo file_get_contents(JPATH_THEMES . '/' . $template->template . '/css/joomla' . $version . '-' . $style . '-extended.css','r');
}
