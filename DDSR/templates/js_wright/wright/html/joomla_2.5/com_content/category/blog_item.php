<?php
// Wright v.3 Override: Joomla 2.5.14
/**
 * @package		Joomla.Site
 * @subpackage	com_content
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

/* Wright v.3: Helper */
	include_once(dirname(__FILE__) . '/../com_content.helper.php');
/* End Wright v.3: Helper */

// Create a shortcut for params.
$params = &$this->item->params;
$images = json_decode($this->item->images);
$canEdit	= $this->item->params->get('access-edit');
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.framework');

?>

<?php if ($this->item->state == 0) : ?>
<div class="system-unpublished">
<?php endif; ?>


<?php 
/* Wright v.3: Item elements structure */
	if (empty($this->item->wrightElementsStructure)) $this->item->wrightElementsStructure = Array("title","icons","article-info","image","content");
	
	foreach ($this->item->wrightElementsStructure as $wrightElement) :
		switch ($wrightElement) :
			case "title":
/* End Wright v.3: Item elements structure */
?>


<?php if ($params->get('show_title')) : ?>
	<h2>
		<?php if ($params->get('link_titles') && $params->get('access-view')) : ?>
			<a href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid)); ?>">
			<?php echo $this->escape($this->item->title); ?></a>
		<?php else : ?>
			<?php echo $this->escape($this->item->title); ?>
		<?php endif; ?>
	</h2>
<?php endif; ?>

<?php
/* Wright v.3: Item elements structure */
				break;
			case "icons":
/* End Wright v.3: Item elements structure */
?>

<?php if ($params->get('show_print_icon') || $params->get('show_email_icon') || $canEdit) : ?>
	<?php
		/* Wright v.3: Icons dropdown */
	?>
		<div class="btn-group pull-right icons-actions">   <?php // Wright v.3: Added icons-actions class ?>
			<a class="btn dropdown-toggle" href="#" data-toggle="dropdown">
				<i class="icon-cog"></i>
				<span class="caret"></span>
			</a>
	<?php
		/* End Wright v.3: Icons dropdown */
	?>
	<ul class="actions<?php echo " dropdown-menu" // Wright v.3: Icons dropdown ?>">
		<?php if ($params->get('show_print_icon')) : ?>
		<li class="print-icon">
			<?php echo preg_replace("/<img([^>]*)>/i", "<i class=\"icon-print\"></i>", JHtml::_('icon.print_popup', $this->item, $params));  // Wright v.3: Print icon ?>
		</li>
		<?php endif; ?>
		<?php if ($params->get('show_email_icon')) : ?>
		<li class="email-icon">
			<?php echo preg_replace("/<img([^>]*)>/i", "<i class=\"icon-envelope\"></i>", JHtml::_('icon.email', $this->item, $params));  // Wright v.3: Email icon ?>
		</li>
		<?php endif; ?>
		<?php if ($canEdit) : ?>
		<li class="edit-icon">
			<?php echo preg_replace("/<span([^>]*)title=\"([^\"]*)\"([^>]*)>(.*)<img([^>]*)>(.*)<\/span>/sUi", "$4<i class=\"icon-pencil\"></i>$6", JHtml::_('icon.edit', $this->item, $params));  // Wright v.3: Edit icon ?>
		</li>
		<?php endif; ?>
	</ul>
	<?php
		/* Wright v.3: Icons dropdown */
	?>
		</div>
	<?php
		/* End Wright v.3: Icons dropdown */
	?>
<?php endif; ?>

<?php if (!$params->get('show_intro')) : ?>
	<?php echo $this->item->event->afterDisplayTitle; ?>
<?php endif; ?>

<?php echo $this->item->event->beforeDisplayContent; ?>

<?php
/* Wright v.3: Item elements structure */
				break;
			case "article-info":
				$wrightBeforeIcon = '<span class="hidden-phone">';
				$wrightAfterIcon = '</span>';
				$wrightBeforeIconM = '<span class="visible-phone">';
				$wrightAfterIconM = '</span>';
/* End Wright v.3: Item elements structure */
?>

<?php // to do not that elegant would be nice to group the params ?>

<?php if (($params->get('show_author')) or ($params->get('show_category')) or ($params->get('show_create_date')) or ($params->get('show_modify_date')) or ($params->get('show_publish_date')) or ($params->get('show_parent_category')) or ($params->get('show_hits'))) : ?>
 <dl class="article-info<?php echo ' muted'; // Wright v.3: Muted style ?>">
 <dt class="article-info-term"><?php echo JText::_('COM_CONTENT_ARTICLE_INFO'); ?></dt>
<?php endif; ?>
<?php if ($params->get('show_parent_category') && $this->item->parent_id != 1) : ?>
		<dd class="parent-category-name">
			<i class="icon-circle-arrow-up"></i> <?php // Wright v.3: Icon ?>
			<?php $title = $this->escape($this->item->parent_title);
				$url = '<a href="' . JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->parent_id)) . '">' . $title . '</a>'; ?>
			<?php if ($params->get('link_parent_category')) : ?>
				<?php echo $wrightBeforeIcon . JText::sprintf('COM_CONTENT_PARENT', $url) . $wrightAfterIcon; // Wright v.3: Icon for non-mobile version ?>
				<?php echo $wrightBeforeIconM . JText::sprintf($url) . $wrightAfterIconM; // Wright v.3: Icon for mobile version ?>
				<?php else : ?>
				<?php echo $wrightBeforeIcon . JText::sprintf('COM_CONTENT_PARENT', $title) . $wrightAfterIcon; // Wright v.3: Icon for non-mobile version ?>
				<?php echo $wrightBeforeIconM . JText::sprintf($title) . $wrightAfterIconM; // Wright v.3: Icon for mobile version ?>
			<?php endif; ?>
		</dd>
<?php endif; ?>
<?php if ($params->get('show_category')) : ?>
		<dd class="category-name">
			<i class="icon-folder-open"></i> <?php // Wright v.3: Icon ?>
			<?php $title = $this->escape($this->item->category_title);
					$url = '<a href="' . JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->catid)) . '">' . $title . '</a>'; ?>
			<?php if ($params->get('link_category')) : ?>
				<?php echo $wrightBeforeIcon . JText::sprintf('COM_CONTENT_CATEGORY', $url) . $wrightAfterIcon;  // Wright v.3: Icon for non-mobile version ?>
				<?php echo $wrightBeforeIconM . JText::sprintf($url) . $wrightAfterIconM; // Wright v.3: Icon for mobile version ?>
				<?php else : ?>
				<?php echo $wrightBeforeIcon . JText::sprintf('COM_CONTENT_CATEGORY', $title) . $wrightAfterIcon;  // Wright v.3: Icon for non-mobile version ?>
				<?php echo $wrightBeforeIconM . JText::sprintf($title) . $wrightAfterIconM; // Wright v.3: Icon for mobile version ?>
			<?php endif; ?>
		</dd>
<?php endif; ?>
<?php if ($params->get('show_create_date')) : ?>
		<dd class="create">
			<i class="icon-pencil"></i> <?php // Wright v.3: Icon ?>
			<?php echo $wrightBeforeIcon . JText::sprintf('COM_CONTENT_CREATED_DATE_ON', JHtml::_('date', $this->item->created, JText::_('DATE_FORMAT_LC2'))) . $wrightAfterIcon;  // Wright v.3: Icon for non-mobile version ?>
			<?php echo $wrightBeforeIconM . JText::sprintf(JHtml::_('date', $this->item->created, JText::_('DATE_FORMAT_LC3'))) . $wrightAfterIconM; // Wright v.3: Icon for mobile version ?>
		</dd>
<?php endif; ?>
<?php if ($params->get('show_modify_date')) : ?>
		<dd class="modified">
			<i class="icon-edit"></i> <?php // Wright v.3: Icon ?>
			<?php echo $wrightBeforeIcon .  JText::sprintf('COM_CONTENT_LAST_UPDATED', JHtml::_('date', $this->item->modified, JText::_('DATE_FORMAT_LC2'))) . $wrightAfterIcon;  // Wright v.3: Icon for non-mobile version ?>
			<?php echo $wrightBeforeIconM . JText::sprintf(JHtml::_('date', $this->item->modified, JText::_('DATE_FORMAT_LC3'))) . $wrightAfterIconM; // Wright v.3: Icon for mobile version ?>
		</dd>
<?php endif; ?>
<?php if ($params->get('show_publish_date')) : ?>
		<dd class="published">
			<i class="icon-calendar"></i> <?php // Wright v.3: Icon ?>
			<?php echo $wrightBeforeIcon .  JText::sprintf('COM_CONTENT_PUBLISHED_DATE_ON', JHtml::_('date', $this->item->publish_up, JText::_('DATE_FORMAT_LC2'))) . $wrightAfterIcon;  // Wright v.3: Icon for non-mobile version ?>
			<?php echo $wrightBeforeIconM . JText::sprintf(JHtml::_('date', $this->item->publish_up, JText::_('DATE_FORMAT_LC3'))) . $wrightAfterIconM; // Wright v.3: Icon for mobile version ?>
		</dd>
<?php endif; ?>
<?php if ($params->get('show_author') && !empty($this->item->author )) : ?>
	<dd class="createdby">
		<i class="icon-user"></i> <?php // Wright v.3: Icon ?>
		<?php $author =  $this->item->author; ?>
		<?php $author = ($this->item->created_by_alias ? $this->item->created_by_alias : $author);?>

			<?php if (!empty($this->item->contactid ) &&  $params->get('link_author') == true):?>
				<?php  echo $wrightBeforeIcon . JText::sprintf('COM_CONTENT_WRITTEN_BY' ,
				 JHtml::_('link', JRoute::_('index.php?option=com_contact&view=contact&id='.$this->item->contactid), $author)) . $wrightAfterIcon;  // Wright v.3: Icon for non-mobile version ?>
				<?php echo $wrightBeforeIconM . JText::sprintf(JHtml::_('link', JRoute::_('index.php?option=com_contact&view=contact&id='.$this->item->contactid), $author)) . $wrightAfterIconM; // Wright v.3: Icon for mobile version ?>
			<?php else :?>
				<?php echo $wrightBeforeIcon . JText::sprintf('COM_CONTENT_WRITTEN_BY', $author) . $wrightAfterIcon;  // Wright v.3: Icon for non-mobile version ?>
				<?php echo $wrightBeforeIconM . JText::sprintf($author) . $wrightAfterIconM; // Wright v.3: Icon for mobile version ?>
			<?php endif; ?>
	</dd>
<?php endif; ?>
<?php if ($params->get('show_hits')) : ?>
		<dd class="hits">
			<i class="icon-eye-open"></i> <?php // Wright v.3: Icon ?>
			<?php echo $wrightBeforeIcon . JText::sprintf('COM_CONTENT_ARTICLE_HITS', $this->item->hits) . $wrightAfterIcon;  // Wright v.3: Icon for non-mobile version ?>
			<?php echo $wrightBeforeIconM . JText::sprintf($this->item->hits) . $wrightAfterIconM;  // Wright v.3: Icon for mobile version ?>
		</dd>
<?php endif; ?>
<?php if (($params->get('show_author')) or ($params->get('show_category')) or ($params->get('show_create_date')) or ($params->get('show_modify_date')) or ($params->get('show_publish_date')) or ($params->get('show_parent_category')) or ($params->get('show_hits'))) :?>
 	</dl>
<?php endif; ?>

<?php
/* Wright v.3: Item elements structure */
				break;
			case "image":
/* End Wright v.3: Item elements structure */
?>

<?php  if (isset($images->image_intro) and !empty($images->image_intro)) : ?>
	<?php $imgfloat = (empty($images->float_intro)) ? $params->get('float_intro') : $images->float_intro; ?>
	<div class="img-intro-<?php echo htmlspecialchars($imgfloat); ?>">
		<?php
		/* Wright v.3: Added link to the image from the article */
			if ($params->get('access-view')) :
		?>
			<a href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid)); ?>">
		<?php
			endif;
		/* End Wright v.3: Added link to the image from the article */
		?>
			<img
				<?php if ($images->image_intro_caption):
					echo 'class="caption ' . $this->wrightBootstrapImages . '"'.' title="' .htmlspecialchars($images->image_intro_caption) .'"';  // Wright .v.3: Added image class
				/* Wright v.3: Image class when no caption present */
				else:
					echo 'class="' . $this->wrightBootstrapImages . '"';
				/* End Wright v.3: Image class when no caption present */
				endif; ?>
				src="<?php echo htmlspecialchars($images->image_intro); ?>" alt="<?php echo htmlspecialchars($images->image_intro_alt); ?>" />
		<?php
		/* Wright v.3: Added link to the image from the article */
			if ($params->get('access-view')) :
		?>
			</a>
		<?php
			endif;
		/* End Wright v.3: Added link to the image from the article */
		?>
	</div>
<?php endif; ?>

<?php
/* Wright v.3: Item elements structure */
				break;
			case "content":
/* End Wright v.3: Item elements structure */
?>

<?php echo wrightTransformArticleContent($this->item->introtext);  // Wright v.3: Transform article content's plugins (using helper) ?>

<?php if ($params->get('show_readmore') && $this->item->readmore) :
	if ($params->get('access-view')) :
		$link = JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid));
	else :
		$menu = JFactory::getApplication()->getMenu();
		$active = $menu->getActive();
		$itemId = $active->id;
		$link1 = JRoute::_('index.php?option=com_users&view=login&Itemid=' . $itemId);
		$returnURL = JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid));
		$link = new JURI($link1);
		$link->setVar('return', base64_encode(urlencode($returnURL)));
	endif;
?>
		<p class="readmore">
				<a href="<?php echo $link; ?>"<?php echo ' class="btn"' // Wright v.3: Readmore ?>>
					<span class="icon-chevron-right"></span>  <?php // Wright v.3: Added icon ?>
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
					endif; ?></a>
		</p>
<?php endif; ?>

<?php 
/* Wright v.3: Item elements structure */
				break;
			default:
				// accept any other div or HTML content in tag#id.class form, or /tag for closure
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


<?php if ($this->item->state == 0) : ?>
</div>
<?php endif; ?>

<div class="item-separator"></div>
<?php echo $this->item->event->afterDisplayContent; ?>
