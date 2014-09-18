<?php

class WrightAdapterJoomlaNav
{
	public function render($args)
	{
		// Set module name
		if (!isset($args['name'])) $args['name'] = 'menu';
		// Set module name
		if (!isset($args['style'])) $args['style'] = 'raw';

		if (!isset($args['containerClass'])) $args['containerClass'] = '';

		// Set module name
		if (!isset($args['wrapClass'])) $args['wrapClass'] = '';
		if (!isset($args['wrapper'])) $args['wrapper'] = 'wrapper-' . $args['name'];
		
		if (!isset($args['type'])) $args['type'] = 'menu';

		if ($args['type'] == 'toolbar') {
			$nav =
			'<div class="'.$args['wrapper'].'">
				<nav id="'.$args['name'].'">
					<div class="navbar ' . $args['wrapClass'] . '">
						<div class="navbar-inner">
							<div class="' . $args['containerClass'] . '">
					            <a class="btn btn-navbar collapsed" data-toggle="collapse" data-target="#nav-'.$args['name'].'">
						            <span class="icon-bar"></span>
						            <span class="icon-bar"></span>
						            <span class="icon-bar"></span>
					            </a>
					            <div class="nav-collapse" id="nav-'.$args['name'].'">
									 <jdoc:include type="modules" name="'.$args['name'].'" style="'.$args['style'].'" />
								</div>
							</div>
						</div>
					</div>
				</nav>
			</div>';		
		}
		else {
			$nav =
			'<div class="'.$args['wrapper'].'">
				<div class="' . $args['containerClass'] . '">
					<nav id="'.$args['name'].'">
						<div class="navbar ' . $args['wrapClass'] . '">
							<div class="navbar-inner">
					            <a class="btn btn-navbar collapsed" data-toggle="collapse" data-target="#nav-'.$args['name'].'">
						            <span class="icon-bar"></span>
						            <span class="icon-bar"></span>
						            <span class="icon-bar"></span>
					            </a>
					            <div class="nav-collapse" id="nav-'.$args['name'].'">
									 <jdoc:include type="modules" name="'.$args['name'].'" style="'.$args['style'].'" />
								</div>
							</div>
						</div>
					</nav>
				</div>
			</div>';			
		}

		return $nav;
	}
}
