<?php
/**
 * @version $Id: helper.php 5 2013-01-11 10:22:28Z szymon $
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
defined('_JEXEC') or die ('Restricted access');

class modDJImageSliderHelper
{
    static function getImagesFromFolder(&$params) {
    	
    	if(!is_numeric($max = $params->get('max_images'))) $max = 20;
        $folder = $params->get('image_folder');
        if(!$dir = @opendir($folder)) return null;
        while (false !== ($file = readdir($dir)))
        {
            if (preg_match('/.+\.(jpg|jpeg|gif|png)$/i', $file)) {
            	// check with getimagesize() which attempts to return the image mime-type 
            	if(getimagesize(JPATH_ROOT.DS.$folder.DS.$file)!==FALSE) $files[] = $file;
			}
        }
        closedir($dir);
        if($params->get('sort_by')) natcasesort($files);
		else shuffle($files);

		$images = array_slice($files, 0, $max);
		
		$target = modDJImageSliderHelper::getSlideTarget($params->get('link'));
		
		foreach($images as $image) {
			$slides[] = (object) array('title'=>'', 'description'=>'', 'image'=>$folder.'/'.$image, 'link'=>$params->get('link'), 'alt'=>$image, 'target'=>$target);
		}
				
		return $slides;
    }
	
	static function getImagesFromDJImageSlider(&$params) {
		
		if(!is_numeric($max = $params->get('max_images'))) $max = 20;
        $catid = $params->get('category',0);
		
		// build query to get slides
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('a.*');
		$query->from('#__djimageslider AS a');
		
		if (is_numeric($catid)) {
			$query->where('a.catid = ' . (int) $catid);
		}
		
		// Filter by start and end dates.
		$nullDate	= $db->Quote($db->getNullDate());
		$nowDate	= $db->Quote(JFactory::getDate()->format($db->getDateFormat()));
		
		$query->where('a.published = 1');
		$query->where('(a.publish_up = '.$nullDate.' OR a.publish_up <= '.$nowDate.')');
		$query->where('(a.publish_down = '.$nullDate.' OR a.publish_down >= '.$nowDate.')');
		
		if($params->get('sort_by',1)) {
			$query->order('a.ordering ASC');
		} else {
			$query->order('RAND()');
		}

		$db->setQuery($query, 0 , $max);
		$slides = $db->loadObjectList();
		
		foreach($slides as $slide){
			$slide->params = new JRegistry($slide->params);
			$slide->link = modDJImageSliderHelper::getSlideLink($slide);
			$slide->description = modDJImageSliderHelper::getSlideDescription($slide, $params->get('limit_desc'));
			$slide->alt = $slide->title;
			$slide->target = $slide->params->get('link_target','');
			if(empty($slide->target)) $slide->target = modDJImageSliderHelper::getSlideTarget($slide->link);
		}
		
		return $slides;
    }
	
	static function getSlideLink(&$slide) {
		$link = '';
		$db = JFactory::getDBO();
		$app = JFactory::getApplication();
		
		switch($slide->params->get('link_type', '')) {
			case 'menu':
				if ($menuid = $slide->params->get('link_menu',0)) {
					
					$menu = $app->getMenu();
					$menuitem = $menu->getItem($menuid);
					if($menuitem) switch($menuitem->type) {
						case 'component': 
							$link = JRoute::_($menuitem->link.'&Itemid='.$menuid);
							break;
						case 'url':
						case 'alias':
							$link = JRoute::_($menuitem->link);
							break;
					}	
				}
				break;
			case 'url':
				if($itemurl = $slide->params->get('link_url',0)) {
					$link = JRoute::_($itemurl);
				}
				break;
			case 'article':
				if ($artid = $slide->params->get('id',$slide->params->get('link_article',0))) {
					jimport('joomla.application.component.model');
					require_once(JPATH_BASE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php');
					JModelLegacy::addIncludePath(JPATH_BASE.DS.'components'.DS.'com_content'.DS.'models');
					$model = JModelLegacy::getInstance('Articles', 'ContentModel', array('ignore_request'=>true));
					$model->setState('params', $app->getParams());
					$model->setState('filter.article_id', $artid);
					$model->setState('filter.article_id.include', true); // Include
					$items = $model->getItems();
					if($items && $item = $items[0]) {
						$item->slug = $item->alias ? ($item->id . ':' . $item->alias) : $item->id;
						$link = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catid));
						$slide->introtext = $item->introtext;
					}
				}
				break;
		}
		
		return $link;
	}
	
	static function getSlideDescription($slide, $limit) {
		$sparams = new JRegistry($slide->params);
		if($sparams->get('link_type','')=='article' && empty($slide->description)){ // if article and no description then get introtext as description
			if(isset($slide->introtext)) $slide->description = $slide->introtext;
		}
		
		$desc = strip_tags($slide->description);
		if($limit && $limit < strlen($desc)) {
			$limit = strpos($desc, ' ', $limit);
			$desc = substr($desc, 0, $limit);
			if(preg_match('/[A-Za-z0-9]$/', $desc)) $desc.=' ...';
			$desc = nl2br($desc);
		} else { // no limit or limit greater than description
			$desc = $slide->description;
		}

		return $desc;
	}

	static function getAnimationOptions(&$params) {
		$effect = $params->get('effect');
		$effect_type = $params->get('effect_type');
		if(!is_numeric($duration = $params->get('duration'))) $duration = 0;
		if(!is_numeric($delay = $params->get('delay'))) $delay = 3000;
		$autoplay = $params->get('autoplay');
		if($params->get('slider_type')==2 && !$duration) {
			$transition = 'linear';
			$duration = 600;
		} else switch($effect){
			case 'Linear':
				$transition = 'linear';
				if(!$duration) $duration = 600;
				break;
			case 'Circ':
			case 'Expo':
			case 'Back':
				if(!$effect_type) $transition = $effect.'.easeInOut';
				else $transition = $effect.'.'.$effect_type;
				if(!$duration) $duration = 1000;
				break;
			case 'Bounce':
				if(!$effect_type) $transition = $effect.'.easeOut';
				else $transition = $effect.'.'.$effect_type;
				if(!$duration) $duration = 1200;
				break;
			case 'Elastic':
				if(!$effect_type) $transition = $effect.'.easeOut';
				else $transition = $effect.'.'.$effect_type;
				if(!$duration) $duration = 1500;
				break;
			case 'Cubic':
			default: 
				if(!$effect_type) $transition = 'Cubic.easeInOut';
				else $transition = 'Cubic.'.$effect_type;
				if(!$duration) $duration = 800;
		}
		$delay = $delay + $duration;
		$options = "{auto: $autoplay, transition: Fx.Transitions.$transition, duration: $duration, delay: $delay}";
		return $options;
	}
	
	static function getSlideTarget($link) {
		
		if(preg_match("/^http/",$link) && !preg_match("/^".str_replace(array('/','.','-'), array('\/','\.','\-'),JURI::base())."/",$link)) {
			$target = '_blank';
		} else {
			$target = '_self';
		}
		
		return $target;
	}
	
	static function getNavigation(&$params, &$mid) {
		
		$prev = $params->get('left_arrow');
		$next = $params->get('right_arrow');
		$play = $params->get('play_button');
		$pause = $params->get('pause_button');
		
		if($params->get('slider_type')==1) {			
			if(empty($prev) || !file_exists(JPATH_ROOT.DS.$prev)) $prev = JURI::base().'/modules/mod_djimageslider/assets/up.png';			
			if(empty($next) || !file_exists(JPATH_ROOT.DS.$next)) $next = JURI::base().'/modules/mod_djimageslider/assets/down.png';
		} else {			
			if(empty($prev) || !file_exists(JPATH_ROOT.DS.$prev)) $prev = JURI::base().'/modules/mod_djimageslider/assets/prev.png';			
			if(empty($next) || !file_exists(JPATH_ROOT.DS.$next)) $next = JURI::base().'/modules/mod_djimageslider/assets/next.png';
		}
		if(empty($play) || !file_exists(JPATH_ROOT.DS.$play)) $play = JURI::base().'/modules/mod_djimageslider/assets/play.png';
		if(empty($pause) || !file_exists(JPATH_ROOT.DS.$pause)) $pause = JURI::base().'/modules/mod_djimageslider/assets/pause.png';
		
		$navi = (object) array('prev'=>$prev,'next'=>$next,'play'=>$play,'pause'=>$pause);
		
		return $navi;
	}
	
	static function getStyleSheet(&$params, &$mid) {
		if(!is_numeric($slide_width = $params->get('image_width'))) $slide_width = 240;
		if(!is_numeric($slide_height = $params->get('image_height'))) $slide_height = 160;
		if(!is_numeric($max = $params->get('max_images'))) $max = 20;
		if(!is_numeric($count = $params->get('visible_images'))) $count = 2;
		if(!is_numeric($spacing = $params->get('space_between_images'))) $spacing = 0;
		if($count<1) $count = 1;
		if($count>$max) $count = $max;
		if(!is_numeric($desc_width = $params->get('desc_width')) || $desc_width > $slide_width) $desc_width = $slide_width;
		if(!is_numeric($desc_bottom = $params->get('desc_bottom'))) $desc_bottom = 0;
		if(!is_numeric($desc_left = $params->get('desc_horizontal'))) $desc_left = 0;
		if(!is_numeric($arrows_top = $params->get('arrows_top'))) $arrows_top = 100;
		if(!is_numeric($arrows_horizontal = $params->get('arrows_horizontal'))) $arrows_horizontal = 5;
		
		switch($params->get('slider_type')){
			case 2:
				$slider_width = $slide_width;
				$slider_height = $slide_height;
				$image_width = 'width: 100%';
				$image_height = 'height: auto';
				$padding_right = 0;
				$padding_bottom = 0;
				break;
			case 1:
				$slider_width = $slide_width;
				$slider_height = $slide_height * $count + $spacing * ($count - 1);
				$image_width = 'width: auto';
				$image_height = 'height: 100%';
				$padding_right = 0;
				$padding_bottom = $spacing;
				break;
			case 0:
			default:
				$slider_width = $slide_width * $count + $spacing * ($count - 1);
				$slider_height = $slide_height;
				$image_width = 'width: 100%';
				$image_height = 'height: auto';
				$padding_right = $spacing;
				$padding_bottom = 0;
				break;
		}
		
		$desc_width = (($desc_width / $slide_width) * 100);
		$desc_left = (($desc_left / $slide_width) * 100);
		$desc_bottom = (($desc_bottom / $slide_height) * 100);
		$arrows_top = (($arrows_top / $slider_height) * 100);	
		
		if($params->get('fit_to')==1) {
			$image_width = 'width: 100%';
			$image_height = 'height: auto';
		} else if($params->get('fit_to')==2) {
			$image_width = 'width: auto';
			$image_height = 'height: 100%';
		}
				
		$css = '
		/* Styles for DJ Image Slider with module id '.$mid.' */
		#djslider-loader'.$mid.' {
			margin: 0 auto;
			position: relative;
		}
		#djslider'.$mid.' {
			margin: 0 auto;
			position: relative;
			height: '.$slider_height.'px; 
			width: '.$slider_width.'px;
			max-width: '.$slider_width.'px;
		}
		#slider-container'.$mid.' {
			position: absolute;
			overflow:hidden;
			left: 0; 
			top: 0;
			height: 100%;
			width: 100%;
		}
		#djslider'.$mid.' ul#slider'.$mid.' {
			margin: 0 !important;
			padding: 0 !important;
			border: 0 !important;
		}
		#djslider'.$mid.' ul#slider'.$mid.' li {
			list-style: none outside !important;
			float: left;
			margin: 0 !important;
			border: 0 !important;
			padding: 0 '.$padding_right.'px '.$padding_bottom.'px 0 !important;
			position: relative;
			height: '.$slide_height.'px;
			width: '.$slide_width.'px;
			background: none;
			overflow: hidden;
		}
		#slider'.$mid.' li img {
			'.$image_width.';
			'.$image_height.';
			border: 0 !important;
			margin: 0 !important;
		}
		#slider'.$mid.' li a img, #slider'.$mid.' li a:hover img {
			border: 0 !important;
		}
		';
		if($params->get('slider_source') && ($params->get('show_title') || ($params->get('show_desc')))) $css.= '
		/* Slide description area */
		#slider'.$mid.' .slide-desc {
			position: absolute;
			bottom: '.$desc_bottom.'%;
			left: '.$desc_left.'%;
			width: '.$desc_width.'%;
		}
		#slider'.$mid.' .slide-desc-in {
			position: relative;
			margin: 0 '.$padding_right.'px '.$padding_bottom.'px 0 !important;
		}
		#slider'.$mid.' .slide-desc-bg {
			position:absolute;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
		}
		#slider'.$mid.' .slide-desc-text {
			position: relative;
		}
		#slider'.$mid.' .slide-desc-text h3 {
			display: block !important;
		}
		';
		if($params->get('show_buttons') || $params->get('show_arrows')) $css .= '
		/* Navigation buttons */
		#navigation'.$mid.' {
			position: relative;
			top: '.$arrows_top.'%; 
			margin: 0 '.$arrows_horizontal.'px;
			text-align: center !important;
		}
		';
		if($params->get('show_arrows')) $css .= '
		#prev'.$mid.' {
			cursor: pointer;
			display: block;
			position: absolute;
			left: 0;
		}
		#next'.$mid.' {
			cursor: pointer;
			display: block;
			position: absolute;
			right: 0;
		}
		';
		if($params->get('show_buttons')) $css .= '
		#play'.$mid.', 
		#pause'.$mid.' {
			cursor: pointer;
			display: block;
			position: absolute;
			left: 50%;
		}
		';
		if($params->get('show_custom_nav')) $css .= '
		#cust-navigation'.$mid.' {
			position: absolute;
			top: 10px;
			right: 10px;
		}
		';
		
		return $css;
	}

}
