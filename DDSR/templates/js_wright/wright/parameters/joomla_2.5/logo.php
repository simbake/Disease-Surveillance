<?php

// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();

jimport('joomla.html.html');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldLogo extends JFormFieldList
{
	public $type = 'Logo';

	protected function getOptions()
	{
		$options = array();

		$options[] = JHTML::_('select.option', 'template', '- '.JText::_('TPL_JS_WRIGHT_FIELD_LOGO_TEMPLATE').' -');
		$options[] = JHTML::_('select.option', 'module', '- '.JText::_('TPL_JS_WRIGHT_FIELD_LOGO_MODULE').' -');
		$options[] = JHTML::_('select.option', 'title', '- '.JText::_('TPL_JS_WRIGHT_FIELD_LOGO_TITLE').' -');

		$files = JFolder::files(JPATH_ROOT.'/images', '\.png$|\.gif$|\.jpg$|\.bmp$|\.ico$');

		foreach ($files as $file)
		{
			$options[] = JHTML::_('select.option', $file, $file);
		}

		return $options;
	}
}