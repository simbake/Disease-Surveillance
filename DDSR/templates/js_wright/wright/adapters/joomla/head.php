<?php

class WrightAdapterJoomlaHead
{
	public function render($args)
	{
		$head = '';
		$dochtml = JFactory::getDocument();
		if ($dochtml->params->get('responsive',1)) {
		    // add viewport meta for tablets
		    $head = '  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">' . "\n";
		}
		$head .= '<jdoc:include type="head" />';
	    $head .= "\n";
		return $head;
	}
}
