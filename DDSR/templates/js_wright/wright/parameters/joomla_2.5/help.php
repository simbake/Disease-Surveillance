<?php // $Id: rebrand.php 8 2010-11-03 18:07:23Z jeremy $
defined('_JEXEC') or die('Restricted access');

jimport('joomla.filesystem.file');
jimport('joomla.form.formfield');

class JFormFieldHelp extends JFormField
{
	protected $type = 'Help';

	protected function getInput()
	{
		JHTML::_('behavior.modal');
		$doc = JFactory::getDocument();
		$template = $this->form->getValue('template');
		$html = '<a class="modal" href="'.JURI::root().'templates/'.$template.'/wright/help" rel="{\'handler\': \'iframe\', \'size\': {x: 800, y:600}}">'.JText::_('TPL_JS_WRIGHT_FIELD_DOCUMENTATION').'</a>';

		// Refresh CSS cache since we are editing params
		if (is_file(JPATH_ROOT.'/templates'.'/'.$template.'/css'.'/'.$template.'.css')) JFile::delete(JPATH_ROOT.'/templates'.'/'.$template.'/css'.'/'.$template.'.css');

		return $html;
	}
}