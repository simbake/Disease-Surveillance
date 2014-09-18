<?php

class WrightAdapterJoomlaLogo
{
	// checks the existance of a logo
	public static function isThereALogo() {
		$dochtml = JFactory::getDocument();

		// if is set as a module position 'logo', checks if there is any module in that position
		if ($dochtml->params->get('logo', 'template') == 'module') {
			if ($dochtml->countModules('logo'))
				return true;
			return false;
		}

		// in any other case, there is always a logo (at least as Wright's default image logo.png)
		return true;
	}
	
	public function renderCompanion($name, $args, $width, $alt = false) {
		$doc = Wright::getInstance();

		if ($name == 'menu') {
			return '
				<nav id="'.$name.($alt ? '_alt' : '_primary') .'" class="clearfix">
					<div class="navbar ' . $args['menuWrapClass'] . '">
						<div class="navbar-inner">
				            <a class="btn btn-navbar collapsed" data-toggle="collapse" data-target="#nav-'.$name.'">
					            <span class="icon-bar"></span>
					            <span class="icon-bar"></span>
					            <span class="icon-bar"></span>
				            </a>
				            <div class="nav-collapse" id="nav-'.$name.'">
								 <jdoc:include type="modules" name="'.$name.'" style="raw" />
							</div>
						</div>
					</div>
				</nav>
			';
		}
		elseif ($name == 'toolbar') {
			return '
				<nav id="'.$name.($alt ? '_alt' : '_primary') .'" class="clearfix">
		            <a class="btn btn-navbar collapsed" data-toggle="collapse" data-target="#nav-'.$name.'">
			            <span class="icon-bar"></span>
			            <span class="icon-bar"></span>
			            <span class="icon-bar"></span>
		            </a>
		            <div class="nav-collapse" id="nav-'.$name.'">
						 <jdoc:include type="modules" name="'.$name.'" style="raw" />
					</div>
				</nav>
			';
		}
		else {
			if ($doc->document->params->get('logowidth') !== '12' && $doc->countModules($name)) {
				return '<div id="'.$name.($alt ? '_alt' : '_primary') . '" class="clearfix"> <jdoc:include type="modules" name="'.$name.'" style="'.$args['style'].'" /> </div>';			}
		}
	
		return '';
	
	}

	public function render($args)
	{
		$uniquePosition = false;

		if (!isset($args['name'])) $args['name'] = 'newsflash';
		if (!isset($args['style'])) $args['style'] = 'xhtml';
		if (!isset($args['addid'])) $args['addid'] = '';

		// class for the wrapper of the menu nav bar
		if (!isset($args['menuWrapClass'])) $args['menuWrapClass'] = '';

		$doc = Wright::getInstance();
		$app = JFactory::getApplication();

		$title = '<h2>'.(($doc->document->params->get('headline', '') !== '') ? $doc->document->params->get('headline') : $app->getCfg('sitename')).'</h2>';
		$title .= ($doc->document->params->get('tagline', '') !== '') ? '<h3>'.$doc->document->params->get('tagline').'</h3>' : '';


		// checks if one ore two modules will be around the logo, and calculates sizes
		$html = "";
		$modulewidth = 12 - $doc->document->params->get('logowidth', '6');
		$modulewidth2 = $modulewidth;
		$logowidth = $doc->document->params->get('logowidth', '6');
		
		$modules = 0;
		$modulename = (isset($args['name']) ? $args['name'] : "");
		$modulename2 = (isset($args['name']) ? $args['name'] . 2 : "");		

		$module2name = (isset($args['name2']) ? $args['name2'] : "");
		$module2name2 = (isset($args['name2']) ? $args['name2'] . 2 : "");		
		
		if ($logowidth !== '12' && ($doc->countModules($modulename) || $doc->countModules($module2name))) $modules++;
		if ($logowidth !== '12' && ($doc->countModules($modulename2) || $doc->countModules($module2name2))) $modules++;
		
		if ($modules == 2) {
			$modulewidth2 = floor($modulewidth/2);
			$modulewidth = ceil($modulewidth/2);
			$logowidth = 12 - $modulewidth - $modulewidth2;
			
			if ($doc->document->params->get('logowidth') !== '12' && ($doc->countModules($modulename) || $doc->countModules($module2name))) {
				$html .= '<div id="'.$modulename.'" class="span'.$modulewidth.'">';
				$html .= $this->renderCompanion($modulename,$args,$modulewidth);
				$html .= $this->renderCompanion($module2name,$args,$modulewidth,true);
				$html .= '</div>';
			}
		}
		else {
			$modulename2 = $modulename;
			$module2name2 = $module2name;
			$uniquePosition = true;
		}

		// Toolbar opening
		if ($uniquePosition && $modulename2 == "toolbar") {
			$html .= '		
				<div class="navbar ' . $args['menuWrapClass'] . '">
					<div class="navbar-inner">
						<div class="' . $args['containerClass'] . '">
							<div class="' . $args['rowClass'] . '">';
		}


		// If user wants a module, load it instead of image
		if ($doc->document->params->get('logo', 'template') == 'module') {
			$html .= '<div id="logo' . $args['addid'] . '" class="span'.$doc->document->params->get('logowidth', '6').'"><jdoc:include type="modules" name="logo" /></div>';


			if ($doc->document->params->get('logowidth') !== '12' && ($doc->countModules($modulename2) || $doc->countModules($module2name2))) {
				$html .= '<div id="'.$modulename2.'" class="span'.$modulewidth2.'">';
				$html .= $this->renderCompanion($modulename2,$args,$modulewidth2);
				$html .= $this->renderCompanion($module2name2,$args,$modulewidth2,true);
				$html .= '</div>';
			}

			return $html;
		}

		// If user wants just a title, print it out
		elseif ($doc->document->params->get('logo', 'template') == 'title') {
		
			$html .= '<div id="logo' . $args['addid'] . '" class="span'.$doc->document->params->get('logowidth', '6').'"><a href="'.JURI::root().'" class="title">'.$title.'</a></div>';

			if ($doc->document->params->get('logowidth') !== '12' && ($doc->countModules($modulename2) || $doc->countModules($module2name2))) {
				$html .= '<div id="'.$modulename2.'" class="span'.$modulewidth2.'">';
				$html .= $this->renderCompanion($modulename2,$args,$modulewidth2);
				$html .= $this->renderCompanion($module2name2,$args,$modulewidth2,true);
				$html .= '</div>';
			}

			return $html;
		}

		// If user wants an image, decide which image to load
		elseif ($doc->document->params->get('logo', 'template') == 'template') {
			// added support for logo tone (light = "", dark = "-Dark")
			$user = JFactory::getUser();
			$logotone = ($user->getParam('templateTone',''));
			if ($logotone == '') {
				$logotone =  $doc->document->params->get('Tone','' );
			}

			if (is_file(JPATH_ROOT.'/'.'templates'.'/'.$doc->document->template.'/'.'images'.'/'.$doc->document->params->get('style').'/'.'logo' . $logotone. '.png'))
				$logo = JURI::root().'templates/'.$doc->document->template.'/images/'.$doc->document->params->get('style').'/logo' . $logotone. '.png';
			elseif (is_file(JPATH_ROOT.'/'.'templates'.'/'.$doc->document->template.'/'.'images'.'/'.$doc->document->params->get('style').'/'.'logo.png'))
				$logo = JURI::root().'templates/'.$doc->document->template.'/images/'.$doc->document->params->get('style').'/logo.png';
			elseif (is_file(JPATH_ROOT.'/'.'templates/'.$doc->document->template.'/images/logo' . $logotone. '.png'))
				$logo = JURI::root().'templates/'.$doc->document->template.'/images/logo' . $logotone. '.png';
			elseif (is_file(JPATH_ROOT.'/'.'templates/'.$doc->document->template.'/images/logo.png'))
				$logo = JURI::root().'templates/'.$doc->document->template.'/images/logo.png';
			else {
				$logo = JURI::root().'templates/'.$doc->document->template.'/wright/images/logo.png';
			}
		}
		else {
			$logo = JURI::root().'images/'.$doc->document->params->get('logo', 'logo.png');
		}
		
		$html .= '<div id="logo' . $args['addid'] . '" class="span'.$logowidth.'"><a href="'.JURI::root().'" class="image">'.$title.'<img src="'.$logo.'" alt="" title="" /></a></div>';
		
		if ($doc->document->params->get('logowidth') !== '12' && ($doc->countModules($modulename2) || $doc->countModules($module2name2))) {
			$html .= '<div id="'.$modulename2.'" class="span'.$modulewidth2.'">';
			$html .= $this->renderCompanion($modulename2,$args,$modulewidth2);
			$html .= $this->renderCompanion($module2name2,$args,$modulewidth2,true);
			$html .= '</div>';
		}
		
		// Toolbar closure
		if ($uniquePosition && $modulename2 == "toolbar") {
			$html .= '
							</div>
						</div>
					</div>
				</div>';
		}


		
		return $html;
	}
}
