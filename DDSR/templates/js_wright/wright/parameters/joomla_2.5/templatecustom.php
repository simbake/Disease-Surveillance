<?php
/**
 * @package     Wright
 * @subpackage  Parameters
 *
 * @copyright   Copyright (C) 2005 - 2013 Joomlashack. Meritage Assets.  All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

jimport('joomla.form.formfield');

defined('_JEXEC') or die('You are not allowed to directly access this file');

if (file_exists(realpath(dirname(__FILE__).'/../../../parameters/templatecustom.php')))
	require_once(realpath(dirname(__FILE__).'/../../../parameters/templatecustom.php'));
else {
	
	class JFormFieldTemplateCustom extends JFormField
	{
		protected $type = 'templatecustom';

		protected function getInput()
		{
			$html = '';
			$html .= '<input type="text" name="'.$this->name.'" id="'.$this->name.'" value="'.$this->value.'" />';
			return $html;
		}
	}

}