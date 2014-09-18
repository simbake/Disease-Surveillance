<?php
/**
* @version		2.0
* @author		Michael A. Gilkes (jaido7@yahoo.com)
* @copyright	Michael Albert Gilkes
* @license		GNU/GPLv2
*/

/*

Easy Folder Listing Module for Joomla!
Copyright (C) 2010-2012  Michael Albert Gilkes

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

*/

/**
* I have decided to change the color picker to a MooTools alternative. I have taken
* a liking to Mootools over jQuery recently. (Michael Gilkes)
*
* Official Name:	MooRainbow
* Author:			Djamil Legato
* Website:			http://moorainbow.woolly-sheep.net/#download
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

if(!class_exists('JPseudoElementBase'))
{
	if(version_compare(JVERSION,'1.6.0','ge'))
	{
		//get joomla form related functions
		jimport('joomla.form.formfield');
		
		class JPseudoElementBase extends JFormField
		{
			// This line is required to keep Joomla! 1.6/1.7 from complaining
			public function getInput() {}
		}               
	}
	else
	{
		class JPseudoElementBase extends JElement {}
	}
}

class JPseudoEasyMooRainbow extends JPseudoElementBase
{
	//define location of files
	const SEGMENT = 'media/mod_easyfolderlisting/';
	
	//specify the custom field type
	protected $type = 'easymoorainbow';
	
	//setup the custom field's details
	function fetchElement($name, $value, &$node, $control_name)
	{
        $value = htmlspecialchars(html_entity_decode($value, ENT_QUOTES), ENT_QUOTES);
        
		$this->prepareForScripts();
		
		//get the custom javascript
		$javascript = $this->setupScript($control_name.$name, $value);
		
		//get the JDocument instance
		$document =& JFactory::getDocument();
		
		//add the javascript to the head of the html document
		$document->addScriptDeclaration($javascript);
		
		// Initialize some attributes.
		$size = ( $node->attributes('size') ? 'size="'.$node->attributes('size').'"' : '' );
		$class = ( $node->attributes('class') ? 'class="'.$node->attributes('class').'"' : 'class="text_area"' );
        /*
         * Required to avoid a cycle of encoding &
         * html_entity_decode was used in place of htmlspecialchars_decode because
         * htmlspecialchars_decode is not compatible with PHP 4
         */

		$html = '<input type="text" name="'.$control_name.'['.$name.']" id="'.$control_name.$name.'" value="'.$value.'" '.$class.' '.$size.' />';
		
		return $html;
	}
	
	//setup the custom field's details
	function getInput()
	{
		$value = htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8');
		
		$this->prepareForScripts();
		
		//get the custom javascript
		$javascript = $this->setupScript($this->id, $value);
		
		//get the JDocument instance
		$document =& JFactory::getDocument();
		
		//add the javascript to the head of the html document
		$document->addScriptDeclaration($javascript);
		
		// Initialize some field attributes.
		$size		= $this->element['size'] ? ' size="'.(int) $this->element['size'].'"' : '';
		$maxLength	= $this->element['maxlength'] ? ' maxlength="'.(int) $this->element['maxlength'].'"' : '';
		$class		= $this->element['class'] ? ' class="'.(string) $this->element['class'].'"' : '';
		$readonly	= ((string) $this->element['readonly'] == 'true') ? ' readonly="readonly"' : '';
		$disabled	= ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';

		// Initialize JavaScript field attributes.
		$onchange	= $this->element['onchange'] ? ' onchange="'.(string) $this->element['onchange'].'"' : '';

		$html = '<input type="text" name="'.$this->name.'" id="'.$this->id.'"' .
				' value="'.$value.'"' .
				$class.$size.$disabled.$readonly.$onchange.$maxLength.'/>';
		
		return $html;
	}
	
	protected function prepareForScripts()
	{
		//Add Mootools Core + Mootools More
		if (version_compare(JVERSION,'1.6.0','ge'))
		{
			JHtml::_('behavior.framework', true);
		}
		else
		{
			JHtml::_('behavior.mootools');
		}
		
		//get the hosts name
		$host = JURI::base();
		$host = str_ireplace("/administrator", "", $host);
		
		//get the JDocument instance
		$document =& JFactory::getDocument();
		
		//add reference to javascript and css specific to the color picker
		if (version_compare(JVERSION,'2.5.0','ge'))
		{
			$document->addScript($host.self::SEGMENT.'scripts/mooRainbow.1.3.js');
		}
		else
		{
			$document->addScript($host.self::SEGMENT.'scripts/mooRainbow.1.2b2.js');
		}
		$document->addStyleSheet($host.self::SEGMENT.'css/mooRainbow.css');
	}
	
	protected function setupScript($id, $hexcolor)
	{
		//get the hosts name
		$host = JURI::base();
		$host = str_ireplace("/administrator", "", $host);
		
		$js = '/* -- Start Easy MooRainbow Javascript -- */'."\n\n";
		$js.= "window.addEvent('domready', function() {\n";
		$js.= "\tnew MooRainbow('".$id."', {\n";
		$js.= "\t\t'id': '".$id."_mooRainbow',\n";
		$js.= "\t\t'imgPath': '".$host.self::SEGMENT."images/',\n";
		if ($hexcolor)
		{
			$rgbcolor = $this->hex2rgb($hexcolor);
			$js.= "\t\t'startColor': [".$rgbcolor[0].", ".$rgbcolor[1].", ".$rgbcolor[2]."],\n";
		}
		$js.= "\t\t'wheel': true,\n";
		$js.= "\t\t'onChange': function(color) {\n";
		$js.= "\t\t\t$('".$id."').setStyle('background-color', color.hex);\n";
		$js.= "\t\t\t$('".$id."').value = color.hex;\n";
		$js.= "\t\t},\n";
		$js.= "\t\t'onComplete': function(color) {\n";
		$js.= "\t\t\t$('".$id."').setStyle('background-color', color.hex);\n";
		$js.= "\t\t\t$('".$id."').value = color.hex;\n";
		$js.= "\t\t}\n";
		$js.= "\t});\n";
		$js.= "\t$('".$id."').setStyle('background-color', '".$hexcolor."');\n";
		$js.= "});\n";		
		$js.= '/*  -- End  Easy MooRainbow Javascript --  */'."\n";
		
		return $js;
	}
	
	protected function hex2rgb($hex)
	{
	   $hex = str_replace("#", "", $hex);
	 
	   if(strlen($hex) == 3)
	   {
		  $r = hexdec(substr($hex,0,1).substr($hex,0,1));
		  $g = hexdec(substr($hex,1,1).substr($hex,1,1));
		  $b = hexdec(substr($hex,2,1).substr($hex,2,1));
	   }
	   else
	   {
		  $r = hexdec(substr($hex,0,2));
		  $g = hexdec(substr($hex,2,2));
		  $b = hexdec(substr($hex,4,2));
	   }
	   $rgb = array($r, $g, $b);
	   
	   return $rgb;
	}
}


if (version_compare(JVERSION,'1.6.0','ge'))
{
	class JFormFieldEasyMooRainbow extends JPseudoEasyMooRainbow {}
}
else
{
	class JElementEasyMooRainbow extends JPseudoEasyMooRainbow {}                
}
