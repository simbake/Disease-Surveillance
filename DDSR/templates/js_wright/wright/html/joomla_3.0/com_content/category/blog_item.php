<?php
// Wright v.3 Override: Joomla 3.1.5
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/* Wright v.3: Helper */
	include_once(dirname(__FILE__) . '/../com_content.helper.php');
/* End Wright v.3: Helper */

?>
<?php
// Create a shortcut for params.
$params = $this->item->params;
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
$canEdit = $this->item->params->get('access-edit');
JHtml::_('behavior.framework');
?>
<?php if ($this->item->state == 0) : ?>
	<span class="label label-warning"><?php echo JText::_('JUNPUBLISHED'); ?></span>
<?php endif; ?>

<?php 
/* Wright v.3: Item elements structure */
	if (empty($this->item->wrightElementsStructure)) $this->item->wrightElementsStructure = Array("title","icons","article-info","image","content");
	$this->item->wrightBootstrapImages = $this->wrightBootstrapImages;

	// moved useDefList to the top, to set it throught the switch
	$useDefList = ($params->get('show_modify_date') || $params->get('show_publish_date') || $params->get('show_create_date')
	|| $params->get('show_hits') || $params->get('show_category') || $params->get('show_parent_category') || $params->get('show_author') );

	foreach ($this->item->wrightElementsStructure as $wrightElement) :
		switch ($wrightElement) :
			case "title":
/* End Wright v.3: Item elements structure */
?>

<?php echo JLayoutHelper::render('joomla.content.blog_style_default_item_title', $this->item); ?>

<?php
/* Wright v.3: Item elements structure */
				break;
			case "icons":
	/* End Wright v.3: Item elements structure */
?>

<?php echo JLayoutHelper::render('joomla.content.icons', array('params' => $params, 'item' => $this->item, 'print' => false)); ?>

<?php
/* Wright v.3: Item elements structure */
				break;
			case "article-info":
/* End Wright v.3: Item elements structure */
?>

<?php // Todo Not that elegant would be nice to group the params ?>
<?php // Wright v.3: Moved useDefList set to before the switch ?>

<?php if ($useDefList) : ?>
	<?php echo JLayoutHelper::render('joomla.content.info_block.block', array('item' => $this->item, 'params' => $params, 'position' => 'above')); ?>
<?php endif; ?>

<?php
/* Wright v.3: Item elements structure */
				break;
			case "image":
/* End Wright v.3: Item elements structure */
?>

<?php echo JLayoutHelper::render('joomla.content.intro_image', $this->item); ?>

<?php
/* Wright v.3: Item elements structure */
				break;
			case "content":
/* End Wright v.3: Item elements structure */
?>

<?php if (!$params->get('show_intro')) : ?>
	<?php echo $this->item->event->afterDisplayTitle; ?>
<?php endif; ?>
<?php echo $this->item->event->beforeDisplayContent; ?> <?php echo wrightTransformArticleContent($this->item->introtext);  // Wright v.3: Transform article content's plugins (using helper) ?>

<?php if ($useDefList) : ?>
	<?php echo JLayoutHelper::render('joomla.content.info_block.block', array('item' => $this->item, 'params' => $params, 'position' => 'below')); ?>
<?php  endif; ?>

<?php if ($params->get('show_readmore') && $this->item->readmore) :
	if ($params->get('access-view')) :
		$link = JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid));
	else :
		$menu = JFactory::getApplication()->getMenu();
		$active = $menu->getActive();
		$itemId = $active->id;
		$link1 = JRoute::_('index.php?option=com_users&view=login&Itemid=' . $itemId);
		$returnURL = JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid));
		$link = new JUri($link1);
		$link->setVar('return', base64_encode($returnURL));
	endif; ?>

	<p class="readmore"><a class="btn" href="<?php echo $link; ?>"> <span class="icon-chevron-right"></span>

	<?php if (!$params->get('access-view')) :
		echo JText::_('COM_CONTENT_REGISTER_TO_READ_MORE');
	elseif ($readmore = $this->item->alternative_readmore) :
		echo $readmore;
		if ($params->get('show_readmore_title', 0) != 0) :
		echo JHtml::_('string.truncate', ($this->item->title), $params->get('readmore_limit'));
		endif;
	elseif ($params->get('show_readmore_title', 0) == 0) :
		echo JText::sprintf('COM_CONTENT_READ_MORE_TITLE');
	else :
		echo JText::_('COM_CONTENT_READ_MORE');
		echo JHtml::_('string.truncate', ($this->item->title), $params->get('readmore_limit'));
	endif; ?>

	</a></p>

<?php endif; ?>

<?php 
/* Wright v.3: Item elements structure */
				break;
			default:
				
				if (preg_match("/^([\/]?)([a-z0-9-_]+?)([\#]?)([a-z0-9-_]*?)([\.]?)([a-z0-9-]*)$/iU", $wrightElement, $wrightDiv)) {
					echo '<' . $wrightDiv[1] . $wrightDiv[2] .
						($wrightDiv[1] != '' ? '' :
							($wrightDiv[3] != '' ? ' id="' . $wrightDiv[4] . '"' : '') .
							($wrightDiv[5] != '' ? ' class="' . $wrightDiv[6] . '"' : '')
						)
						. '>';
				}
				
		endswitch;
	endforeach;
/* End Wright v.3: Item elements structure */
?>

<?php echo $this->item->event->afterDisplayContent; ?>
