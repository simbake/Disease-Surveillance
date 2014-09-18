<?php
// Wright v.3 Override: Joomla 2.5.14
/**
 * @package		Joomla.Site
 * @subpackage	com_weblinks
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
$class = ' class="first"';
if (count($this->items[$this->parent->id]) > 0 && $this->maxLevelcat != 0) :
?>
<ul>
<?php foreach($this->items[$this->parent->id] as $id => $item) : ?>
	<?php
	if($this->params->get('show_empty_categories_cat') || $item->numitems || count($item->getChildren())) :
	if(!isset($this->items[$this->parent->id][$id + 1]))
	{
		$class = ' class="last"';
	}
	?>
	<li<?php echo $class; ?>>
	<?php $class = ''; ?>
		<h3>  <?php // Wright v.3: Added h3 ?>
			<span class="item-title"><a href="<?php echo JRoute::_(WeblinksHelperRoute::getCategoryRoute($item->id));?>">
				<i class="icon-folder-open"></i>  <?php // Wright v.3: Added icon ?>
				<?php echo $this->escape($item->title); ?></a>
			</span>
		</h3>  <?php // Wright v.3: Added h3 ?>
		<?php if ($this->params->get('show_subcat_desc_cat') == 1) :?>
		<?php if ($item->description) : ?>
			<div class="category-desc">
				<?php echo JHtml::_('content.prepare', $item->description, '', 'com_weblinks.categories'); ?>
			</div>
		<?php endif; ?>
        <?php endif; ?>
		<?php if ($this->params->get('show_cat_num_links_cat') == 1) :?>
			<span class="label label-info"><?php echo JText::_('COM_WEBLINKS_NUM') . ' ' . $item->numitems ?></span>  <?php // Wright v.3: Changed links # format ?>
			<?php /*  Wright v.3: Commented out classic links # format
			<dl class="weblink-count"><dt>
				<?php echo JText::_('COM_WEBLINKS_NUM'); ?></dt>
				<dd><?php echo $item->numitems; ?></dd>
			</dl>
			*/ ?>
		<?php endif; ?>

		<?php if(count($item->getChildren()) > 0) :
			$this->items[$item->id] = $item->getChildren();
			$this->parent = $item;
			$this->maxLevelcat--;
			echo $this->loadTemplate('items');
			$this->parent = $item->getParent();
			$this->maxLevelcat++;
		endif; ?>

	</li>
	<?php endif; ?>
<?php endforeach; ?>
</ul>
<?php endif; ?>
