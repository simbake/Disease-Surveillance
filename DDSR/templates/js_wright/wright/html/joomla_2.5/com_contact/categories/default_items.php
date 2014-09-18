<?php
// Wright v.3 Override: Joomla 2.5.14
/**
 * @package		Joomla.Site
 * @subpackage	com_contact
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
$class = ' class="first"';
if (count($this->items[$this->parent->id]) > 0 && $this->maxLevelcat != 0) :
?>
<?php // <ul> Wright v.3: commented out ul ?>
<?php foreach($this->items[$this->parent->id] as $id => $item) : ?>
	<?php
	if($this->params->get('show_empty_categories_cat') || $item->numitems || count($item->getChildren())) :
	if(!isset($this->items[$this->parent->id][$id + 1]))
	{
		$class = ' class="last"';
	}
	?>
	<div<?php echo $class; ?>>  <?php // Wright v.3: changed li for div ?>
	<?php $class = ''; ?>
		<h3 class="item-title">  <?php // Wright v.3: Changed span to h3 ?>
			<a href="<?php echo JRoute::_(ContactHelperRoute::getCategoryRoute($item->id));?>">
			<i class="icon-folder-open"> </i>  <?php // Wright v.3: Icon ?>
			<?php echo $this->escape($item->title); ?></a>
		</h3>  <?php // Wright v.3: Changed span to h3?>

		<?php if ($this->params->get('show_subcat_desc_cat') == 1) :?>
		<?php if ($item->description) : ?>
			<div class="category-desc">
				<?php echo JHtml::_('content.prepare', $item->description, '', 'com_contact.categories'); ?>
			</div>
		<?php endif; ?>
        <?php endif; ?>

		<?php if ($this->params->get('show_cat_items_cat') == 1) :?>
			<dl class="label label-info"><dt>  <?php // Wright v.3: Added label label-info classes ?>
				<?php echo JText::_('COM_CONTACT_COUNT'); ?></dt>
				<dd><?php echo $item->numitems; ?></dd>
			</dl>
		<?php endif; ?>

		<?php if(count($item->getChildren()) > 0) :
			$this->items[$item->id] = $item->getChildren();
			$this->parent = $item;
			$this->maxLevelcat--;
			echo $this->loadTemplate('items');
			$this->parent = $item->getParent();
			$this->maxLevelcat++;
		endif; ?>

	</div> <?php // Wright v.3: changed li for div ?>
	<?php endif; ?>
<?php endforeach; ?>
<?php // </ul> Wright v.3: commented out ul ?>
<?php endif; ?>
