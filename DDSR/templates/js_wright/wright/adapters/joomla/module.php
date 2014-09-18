<?php

class WrightAdapterJoomlaModule
{
	public function render($args)
	{
		// Set display type
		if (!isset($args['type'])) $args['type'] = 'single';
		// Set module name
		if (!isset($args['name'])) $args['name'] = 'left';
		// Set wrapper class value if not given
		if (!isset($args['wrapper'])) $args['wrapper'] = 'module';
		// Set style value if not given
		if (!isset($args['chrome'])) $args['chrome'] = 'xhtml';
		// Set extradivs value if not given
		if (!isset($args['extradivs'])) $args['extradivs'] = '';
		// Set extraclass value if not given
		if (!isset($args['extraclass'])) $args['extraclass'] = '';
		// Extra attribute
		if (!isset($args['extra'])) $args['extra'] = '';

		$html = '';

		switch ($args['type'])
		{
		    case 'none' :
    		    $html .= '<jdoc:include type="modules" name="'.$args['name'].'" style="'.$args['chrome'].'" extradivs="'.$args['extradivs'].'" extraclass="'.$args['extraclass'].'" extra="'.$args['extra'].'" />';
    		    break;
		    case 'row' :
		        $doc = JFactory::getDocument();
		        $html .= '<div class="'.$args['type'].'">';
		        $html .= '<jdoc:include type="modules" name="'.$args['name'].'" style="'.$args['chrome'].'" grid="'.$doc->countModules($args['name']).'" extradivs="'.$args['extradivs'].'" extraclass="'.$args['extraclass'].'" extra="'.$args['extra'].'" />';
		        $html .= '</div>';
		        break;
		    case 'row-fluid' :
		        $doc = JFactory::getDocument();
		        $html .= '<div class="'.$args['type'].'">';
		        $html .= '<jdoc:include type="modules" name="'.$args['name'].'" style="'.$args['chrome'].'" grid="'.$doc->countModules($args['name']).'" extradivs="'.$args['extradivs'].'" extraclass="'.$args['extraclass'].'" extra="'.$args['extra'].'" />';
		        $html .= '</div>';
		        break;
			case 'grid' :
				$doc = JFactory::getDocument();
				$html .= '<div class="'.$args['wrapper'].'">';
				$html .= '<jdoc:include type="modules" name="'.$args['name'].'" style="'.$args['chrome'].'" grid="'.$doc->countModules($args['name']).'" extradivs="'.$args['extradivs'].'" extraclass="'.$args['extraclass'].'" extra="'.$args['extra'].'" />';
				$html .= '</div>';
				break;
			case 'fixedgrid' :
				$doc = JFactory::getDocument();
				$html .= '<div class="'.$args['wrapper'].'">';
				$html .= '<jdoc:include type="modules" name="'.$args['name'].'" style="'.$args['chrome'].'" grid="'.$args['grid'].'" extradivs="'.$args['extradivs'].'" extraclass="'.$args['extraclass'].'"extra="'.$args['extra'].'" />';
				$html .= '</div>';
				break;
			default :
				$html .= '<div class="'.$args['wrapper'].'">';
				$html .= '<jdoc:include type="modules" name="'.$args['name'].'" style="'.$args['chrome'].'" extradivs="'.$args['extradivs'].'" extraclass="'.$args['extraclass'].'" extra="'.$args['extra'].'" />';
				$html .= '</div>';
				break;
		}

		return $html;
	}
}
