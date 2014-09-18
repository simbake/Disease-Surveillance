<?php

class WrightAdapterJoomlaContent
{
	public function render($args)
	{
		$content = '';

		$errorBox = false;
		if (version_compare(JVERSION, '3.0.0', 'lt')) {
			// Checks queue for messages
			$messages = JFactory::getApplication()->getMessageQueue();
			if (is_array($messages) && !empty($messages)) {
				$errorBox = true;
			}
		}

		if ($errorBox) {
			$content .= '<div class="alert">';
			$content .= '<a href="#" class="close" data-dismiss="alert">&times;</a>';
		}

		$content .= '<jdoc:include type="message" />';

		if ($errorBox) {
			$content .= '</div>';
		}

		$content .= '<jdoc:include type="component" />';
		
		return $content;
	}
}
