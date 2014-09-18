<?php
/**
 * @version $Id: mod_djimageslider.php 11 2013-06-21 11:07:50Z szymon $
 * @package DJ-ImageSlider
 * @subpackage DJ-ImageSlider Component
 * @copyright Copyright (C) 2012 DJ-Extensions.com, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email contact@dj-extensions.com
 * @developer Szymon Woronowski - szymon.woronowski@design-joomla.eu
 *
 *
 * DJ-ImageSlider is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * DJ-ImageSlider is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with DJ-ImageSlider. If not, see <http://www.gnu.org/licenses/>.
 *
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
defined('DS') or define('DS', DIRECTORY_SEPARATOR);

// Include the syndicate functions only once
require_once (dirname(__FILE__).DS.'helper.php');
$app = JFactory::getApplication();

// taking the slides from the source
if($params->get('slider_source')==1) {
	jimport('joomla.application.component.helper');
	if(!JComponentHelper::isEnabled('com_djimageslider', true)){
		$app->enqueueMessage(JText::_('MOD_DJIMAGESLIDER_NO_COMPONENT'),'notice');
		return;
	}
	$slides = modDJImageSliderHelper::getImagesFromDJImageSlider($params);
	if($slides==null) {
		$app->enqueueMessage(JText::_('MOD_DJIMAGESLIDER_NO_CATEGORY_OR_ITEMS'),'notice');
		return;
	}
} else {
	$slides = modDJImageSliderHelper::getImagesFromFolder($params);
	if($slides==null) {
		$app->enqueueMessage(JText::_('MOD_DJIMAGESLIDER_NO_CATALOG_OR_FILES'),'notice');
		return;
	}
}

$document = JFactory::getDocument();
JHTML::_('behavior.framework',true);
if($params->get('link_image',1)==2) {
	JHTML::_('behavior.modal','a.djmodal');
}
$document->addScript('modules/mod_djimageslider/assets/powertools-1.2.0.js');
$document->addScript('modules/mod_djimageslider/assets/slider.js');

if(!is_numeric($width = $params->get('image_width'))) $width = 240;
if(!is_numeric($height = $params->get('image_height'))) $height = 180;
if(!is_numeric($max = $params->get('max_images'))) $max = 20;
if(!is_numeric($count = $params->get('visible_images'))) $count = 3;
if(!is_numeric($spacing = $params->get('space_between_images'))) $spacing = 3;
if($count>count($slides)) $count = count($slides);
if($count<1) $count = 1;
if($count>$max) $count = $max;
$mid = $module->id;
$slider_type = $params->get('slider_type',0);
switch($slider_type){
	case 2:
		$slide_size = $width;
		$count = 1;
		break;
	case 1:
		$slide_size = $height + $spacing;
		break;
	case 0:
	default:
		$slide_size = $width + $spacing;
		break;
}

$animationOptions = modDJImageSliderHelper::getAnimationOptions($params);
$showB = $params->get('show_buttons',1);
$showA = $params->get('show_arrows',1);
if(!is_numeric($preload = $params->get('preload'))) $preload = 800;
$moduleSettings = "{id: '$mid', slider_type: $slider_type, slide_size: $slide_size, visible_slides: $count, show_buttons: $showB, show_arrows: $showA, preload: $preload}";
$js = "window.addEvent('domready',function(){this.Slider$mid = new DJImageSliderModule($moduleSettings,$animationOptions)});";
$js = "(function($){ ".$js." })(document.id);";
$document->addScriptDeclaration($js);

$css = JURI::base().'modules/mod_djimageslider/assets/style.css';
$document->addStyleSheet($css);

$css = modDJImageSliderHelper::getStyleSheet($params,$mid);
$document->addStyleDeclaration($css);

$navigation = modDJImageSliderHelper::getNavigation($params,$mid);

require JModuleHelper::getLayoutPath('mod_djimageslider', $params->get('layout','default'));
