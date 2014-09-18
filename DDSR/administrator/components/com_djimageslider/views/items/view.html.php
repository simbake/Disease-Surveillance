<?php
/**
 * @version $Id: view.html.php 11 2013-06-21 11:07:50Z szymon $
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

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.view');

class DJImageSliderViewItems extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;
	
	public function display($tpl = null)
	{
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		
		foreach($this->items as $item) {
			$item->thumb = 'components/com_djimageslider/assets/icon-image.png';						
			if(strcasecmp(substr($item->image, 0, 4), 'http') != 0 && !empty($item->image)) {
				$item->image = JURI::root(true).'/'.$item->image;
			}
			$item->preview = '<img src="'.$item->image.'" alt="'.$this->escape($item->title).'" width="300" />';
		}
		
		$this->addToolbar();		
		parent::display($tpl);
	}
	
	protected function addToolbar()
	{
		JToolBarHelper::title(JText::_('COM_DJIMAGESLIDER_SLIDES'), 'generic.png');

		JToolBarHelper::addNew('item.add','JTOOLBAR_NEW');
		JToolBarHelper::editList('item.edit','JTOOLBAR_EDIT');
		JToolBarHelper::deleteList('', 'items.delete','JTOOLBAR_DELETE');
		JToolBarHelper::divider();
		JToolBarHelper::custom('items.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
		JToolBarHelper::custom('items.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
		JToolBarHelper::divider();
		JToolBarHelper::preferences('com_djimageslider', 550, 875);
		
	}
}