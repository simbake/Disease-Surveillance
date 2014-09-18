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

/* Wright v.3: Item elements structure and extra elements */
	if (!isset($this->wrightElementsStructure)) $this->wrightElementsStructure = Array();
	if (!isset($this->wrightHasImageClass)) $this->wrightHasImageClass = "";
	if (!isset($this->wrightExtraClass)) $this->wrightExtraClass = "";
	
	if (empty($this->wrightElementsStructure)) $this->wrightElementsStructure = Array("title","icons","article-info","image","content");
	
	$wrightBeforeIcon = '<span class="hidden-phone">';
	$wrightAfterIcon = '</span>';
	$wrightBeforeIconM = '<span class="visible-phone">';
	$wrightAfterIconM = '</span>';

/* End Wright v.3: Item elements structure and extra elements */

/* Wright v.3: Bootstrapped images */
	$app = JFactory::getApplication();
	$template = $app->getTemplate(true);
	$this->wrightBootstrapImages = $template->params->get('wright_bootstrap_images','');
/* End Wright v.3: Bootstrapped images */

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');

// Create shortcuts to some parameters.
$params  = $this->item->params;
$images  = json_decode($this->item->images);
$urls    = json_decode($this->item->urls);
$canEdit = $params->get('access-edit');
$user    = JFactory::getUser();
$info    = $params->get('info_block_position', 0);
JHtml::_('behavior.caption');

?>
<div class="item-page<?php echo $this->pageclass_sfx?><?php echo ($this->wrightExtraClass != '' ? ' ' . $this->wrightExtraClass : ''); if ($this->wrightHasImageClass != '') { echo ((isset($images->image_intro) and !empty($images->image_intro)) ? ' ' . $this->wrightHasImageClass : ''); } // Wright v.3: Item elements extra elements
 ?>">
	<?php if ($this->params->get('show_page_heading') && $params->get('show_title')) : ?>
	<div class="page-header">
		<h1> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
	</div>
	<?php endif;
if (!empty($this->item->pagination) && $this->item->pagination && !$this->item->paginationposition && $this->item->paginationrelative)
{
 echo wrightTransformArticlePager($this->item->pagination);  // Wright v.3: Pager styles (using helper)
}
?>

<?php 
/* Wright v.3: Item elements structure */
	foreach ($this->wrightElementsStructure as $wrightElement) :
		switch ($wrightElement) :
			case "title":
/* End Wright v.3: Item elements structure */
?>

	<?php if ($params->get('show_title') || $params->get('show_author')) : ?>
		<?php /* Wright v.3: Adds page header if h1 is missing */
		if (!$params->get('show_page_heading')) : ?>
		<div class="page-header">
		<?php endif; /* End Wright v.3: Adds page header if h1 is missing */ ?>
		<h2>
			<?php if ($this->item->state == 0) : ?>
				<span class="label label-warning"><?php echo JText::_('JUNPUBLISHED'); ?></span>
			<?php endif; ?>
			<?php if ($params->get('show_title')) : ?>
				<?php if ($params->get('link_titles') && !empty($this->item->readmore_link)) : ?>
					<a href="<?php echo $this->item->readmore_link; ?>"> <?php echo $this->escape($this->item->title); ?></a>
				<?php else : ?>
					<?php echo $this->escape($this->item->title); ?>
				<?php endif; ?>
			<?php endif; ?>
		</h2>
		<?php /* Wright v.3: Adds page header if h1 is missing */
		if (!$params->get('show_page_heading')) : ?>
		</div>
		<?php endif; /* End Wright v.3: Adds page header if h1 is missing */ ?>
	<?php endif; ?>

<?php
/* Wright v.3: Item elements structure */
				break;
			case "icons":
/* End Wright v.3: Item elements structure */
?>

	<?php if (!$this->print) : ?>
		<?php if ($canEdit || $params->get('show_print_icon') || $params->get('show_email_icon')) : ?>
		<div class="btn-group pull-right icons-actions">   <?php // Wright v.3: Added icons-actions class ?>
			<a class="btn dropdown-toggle" data-toggle="dropdown" href="#"> <span class="icon-cog"></span> <span class="caret"></span> </a>
			<?php // Note the actions class is deprecated. Use dropdown-menu instead. ?>
			<ul class="dropdown-menu actions">
				<?php if ($params->get('show_print_icon')) : ?>
				<li class="print-icon"> <?php echo JHtml::_('icon.print_popup', $this->item, $params); ?> </li>
				<?php endif; ?>
				<?php if ($params->get('show_email_icon')) : ?>
				<li class="email-icon"> <?php echo JHtml::_('icon.email', $this->item, $params); ?> </li>
				<?php endif; ?>
				<?php if ($canEdit) : ?>
				<li class="edit-icon"> <?php echo JHtml::_('icon.edit', $this->item, $params); ?> </li>
				<?php endif; ?>
			</ul>
		</div>
		<?php endif; ?>
		<?php else : ?>
		<div class="pull-right">
		<?php echo JHtml::_('icon.print_screen', $this->item, $params); ?>
		</div>
	<?php endif; ?>


<?php
/* Wright v.3: Item elements structure */
				break;
			case "article-info":
/* End Wright v.3: Item elements structure */
?>

<?php $useDefList = ($params->get('show_modify_date') || $params->get('show_publish_date') || $params->get('show_create_date')
	|| $params->get('show_hits') || $params->get('show_category') || $params->get('show_parent_category') || $params->get('show_author')); ?>
	<?php if ($useDefList && ($info == 0 || $info == 2)) : ?>
		<div class="article-info muted">
			<dl class="article-info">
			<dt class="article-info-term"><?php echo JText::_('COM_CONTENT_ARTICLE_INFO'); ?></dt>

			<?php if ($params->get('show_author') && !empty($this->item->author )) : ?>
				<dd class="createdby">
					<i class="icon-user"></i> <?php // Wright v.3: Icon ?>
					<?php $author = $this->item->created_by_alias ? $this->item->created_by_alias : $this->item->author; ?>
					<?php if (!empty($this->item->contactid) && $params->get('link_author') == true) : ?>
						<?php
						$needle = 'index.php?option=com_contact&view=contact&id=' . $this->item->contactid;
						$menu = JFactory::getApplication()->getMenu();
						$item = $menu->getItems('link', $needle, true);
						$cntlink = !empty($item) ? $needle . '&Itemid=' . $item->id : $needle;
						?>
						<?php echo $wrightBeforeIcon . JText::sprintf('COM_CONTENT_WRITTEN_BY', JHtml::_('link', JRoute::_($cntlink), $author)) . $wrightAfterIcon; // Wright v.3: Icon for non-mobile version ?>
						<?php echo $wrightBeforeIconM . JText::sprintf(JHtml::_('link', JRoute::_($cntlink), $author)) . $wrightAfterIconM; // Wright v.3: Icon for mobile version ?>
					<?php else: ?>
						<?php echo $wrightBeforeIcon . JText::sprintf('COM_CONTENT_WRITTEN_BY', $author) . $wrightAfterIcon; // Wright v.3: Icon for non-mobile version ?>
						<?php echo $wrightBeforeIconM . JText::sprintf($author) . $wrightAfterIconM; // Wright v.3: Icon for mobile version ?>
					<?php endif; ?>
				</dd>
			<?php endif; ?>
			<?php if ($params->get('show_parent_category') && !empty($this->item->parent_slug)) : ?>
				<dd class="parent-category-name">
					<i class="icon-circle-arrow-up"></i> <?php // Wright v.3: Icon ?>
					<?php $title = $this->escape($this->item->parent_title);
					$url = '<a href="'.JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->parent_slug)).'">'.$title.'</a>';?>
					<?php if ($params->get('link_parent_category') && !empty($this->item->parent_slug)) : ?>
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
					<i class="icon-folder-close"></i> <?php // Wright v.3: Category icon ?>
					<?php $title = $this->escape($this->item->category_title);
					$url = '<a href="' . JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->catslug)) . '">' . $title . '</a>';?>
					<?php if ($params->get('link_category') && $this->item->catslug) : ?>
						<?php echo $wrightBeforeIcon . JText::sprintf('COM_CONTENT_CATEGORY', $url) . $wrightAfterIcon; // Wright v.3: Icon for non-mobile version ?>
						<?php echo $wrightBeforeIconM . JText::sprintf($url) . $wrightAfterIconM; // Wright v.3: Icon for mobile version ?>
					<?php else : ?>
						<?php echo $wrightBeforeIcon . JText::sprintf('COM_CONTENT_CATEGORY', $title) . $wrightAfterIcon; // Wright v.3: Icon for non-mobile version ?>
						<?php echo $wrightBeforeIconM . JText::sprintf($title) . $wrightAfterIconM; // Wright v.3: Icon for mobile version ?>
					<?php endif; ?>
				</dd>
			<?php endif; ?>

			<?php if ($params->get('show_publish_date')) : ?>
				<dd class="published">
					<span class="icon-calendar"></span>
						<?php echo $wrightBeforeIcon . JText::sprintf('COM_CONTENT_PUBLISHED_DATE_ON', JHtml::_('date', $this->item->publish_up, JText::_('DATE_FORMAT_LC3'))) . $wrightAfterIcon; // Wright v.3: Icon for non-mobile version ?>
						<?php echo $wrightBeforeIconM . JText::sprintf(JHtml::_('date', $this->item->publish_up, JText::_('DATE_FORMAT_LC3'))) . $wrightAfterIconM; // Wright v.3: Icon for mobile version ?>
				</dd>
			<?php endif; ?>

			<?php if ($info == 0) : ?>
				<?php if ($params->get('show_modify_date')) : ?>
					<dd class="modified">
					<span class="icon-edit"></span> <?php // Wright v.3: Changed Icon ?>
					<?php echo $wrightBeforeIcon . JText::sprintf('COM_CONTENT_LAST_UPDATED', JHtml::_('date', $this->item->modified, JText::_('DATE_FORMAT_LC3'))) . $wrightAfterIcon; // Wright v.3: Icon for non-mobile version ?>
					<?php echo $wrightBeforeIconM . JText::sprintf(JHtml::_('date', $this->item->modified, JText::_('DATE_FORMAT_LC3'))) . $wrightAfterIconM; // Wright v.3: Icon for mobile version ?>
					</dd>
				<?php endif; ?>
				<?php if ($params->get('show_create_date')) : ?>
					<dd class="create">
						<span class="icon-pencil"></span> <?php // Wright v.3: Changed Icon ?>
						<?php echo $wrightBeforeIcon . JText::sprintf('COM_CONTENT_CREATED_DATE_ON', JHtml::_('date', $this->item->created, JText::_('DATE_FORMAT_LC3'))) . $wrightAfterIcon; // Wright v.3: Icon for non-mobile version ?>
						<?php echo $wrightBeforeIconM . JText::sprintf(JHtml::_('date', $this->item->created, JText::_('DATE_FORMAT_LC3'))) . $wrightAfterIconM; // Wright v.3: Icon for mobile version ?>
					</dd>
				<?php endif; ?>

				<?php if ($params->get('show_hits')) : ?>
					<dd class="hits">
						<span class="icon-eye-open"></span>
						<?php echo $wrightBeforeIcon . JText::sprintf('COM_CONTENT_ARTICLE_HITS', $this->item->hits) . $wrightAfterIcon; // Wright v.3: Icon for non-mobile version ?>
						<?php echo $wrightBeforeIconM . JText::sprintf($this->item->hits) . $wrightAfterIconM; // Wright v.3: Icon for mobile version ?>
					</dd>
				<?php endif; ?>
			<?php endif; ?>
			</dl>
		</div>
	<?php endif; ?>

	<?php if ($params->get('show_tags', 1) && !empty($this->item->tags)) : ?>
		<?php $this->item->tagLayout = new JLayoutFile('joomla.content.tags'); ?>

		<?php echo $this->item->tagLayout->render($this->item->tags->itemTags); ?>
	<?php endif; ?>

	<?php if (!$params->get('show_intro')) : echo $this->item->event->afterDisplayTitle; endif; ?>
	<?php echo $this->item->event->beforeDisplayContent; ?>

<?php
/* Wright v.3: Item elements structure */
				goto article_info_bottom;
				break;
			case "image":
/* End Wright v.3: Item elements structure */
?>
	<?php if (isset($urls) && ((!empty($urls->urls_position) && ($urls->urls_position == '0')) || ($params->get('urls_position') == '0' && empty($urls->urls_position)))
		|| (empty($urls->urls_position) && (!$params->get('urls_position')))) : ?>
	<?php echo $this->loadTemplate('links'); ?>
	<?php endif; ?>
	<?php if ($params->get('access-view')):?>
	<?php if (isset($images->image_fulltext) && !empty($images->image_fulltext)) : ?>
	<?php $imgfloat = (empty($images->float_fulltext)) ? $params->get('float_fulltext') : $images->float_fulltext; ?>
	<div class="pull-<?php echo htmlspecialchars($imgfloat); ?> item-image"> <img
	<?php if ($images->image_fulltext_caption):
		echo 'class="caption ' . $this->wrightBootstrapImages . '"'.' title="' .htmlspecialchars($images->image_fulltext_caption) . '"';  // Wright .v.3: Added image class
		/* Wright v.3: Image class when no caption present */
		else:
			echo 'class="' . $this->wrightBootstrapImages . '"';
		/* End Wright v.3: Image class when no caption present */
	endif; ?>
	src="<?php echo htmlspecialchars($images->image_fulltext); ?>" alt="<?php echo htmlspecialchars($images->image_fulltext_alt); ?>" />
	</div>
	<?php endif; ?>


<?php
/* Wright v.3: Item elements structure */
		endif; // access-view
				break;
			case "content":
		if ($params->get('access-view')):   // access-view
/* End Wright v.3: Item elements structure */
?>
	<?php
	if (!empty($this->item->pagination) && $this->item->pagination && !$this->item->paginationposition && !$this->item->paginationrelative):
		echo wrightTransformArticlePager($this->item->pagination);  // Wright v.3: Pager styles (using helper)
	endif;
	?>
	<?php if (isset ($this->item->toc)) :
		echo wrightTransformArticleTOC($this->item->toc);  // Wright v.3: TOC transformation (using helper)
	endif; ?>
	<?php echo wrightTransformArticleContent($this->item->text);  // Wright v.3: Transform article content's plugins (using helper)
 ?>

<?php
/* Wright v.3: Item elements structure */
		endif; // access-view
				goto content_bottom;
				break;
article_info_bottom:
		// TODO: make sure that if the "below" or "split" config is selected for article info, it can go below the text
		if ($params->get('access-view')):   // access-view
/* End Wright v.3: Item elements structure */
?>
	<?php if ($useDefList && ($info == 1 || $info == 2)) : ?>
		<div class="article-info muted">
			<dl class="article-info">
			<dt class="article-info-term"><?php echo JText::_('COM_CONTENT_ARTICLE_INFO'); ?></dt>

			<?php if ($info == 1) : ?>
				<?php if ($params->get('show_author') && !empty($this->item->author )) : ?>
					<dd class="createdby">
						<i class="icon-user"></i> <?php // Wright v.3: Author icon ?>
						<?php $author = $this->item->created_by_alias ? $this->item->created_by_alias : $this->item->author; ?>
						<?php if (!empty($this->item->contactid) && $params->get('link_author') == true) : ?>
						<?php
						$needle = 'index.php?option=com_contact&view=contact&id=' . $this->item->contactid;
						$menu = JFactory::getApplication()->getMenu();
						$item = $menu->getItems('link', $needle, true);
						$cntlink = !empty($item) ? $needle . '&Itemid=' . $item->id : $needle;
						?>
						<?php echo $wrightBeforeIcon . JText::sprintf('COM_CONTENT_WRITTEN_BY', JHtml::_('link', JRoute::_($cntlink), $author)) . $wrightAfterIcon; // Wright v.3: Icon for non-mobile version ?>
						<?php echo $wrightBeforeIconM . JText::sprintf(JHtml::_('link', JRoute::_($cntlink), $author)) . $wrightAfterIconM; // Wright v.3: Icon for mobile version ?>
						<?php else: ?>
						<?php echo $wrightBeforeIcon . JText::sprintf('COM_CONTENT_WRITTEN_BY', $author) . $wrightAfterIcon; // Wright v.3: Icon for non-mobile version ?>
						<?php echo $wrightBeforeIconM . JText::sprintf($author) . $wrightAfterIconM; // Wright v.3: Icon for mobile version ?>
						<?php endif; ?>
					</dd>
				<?php endif; ?>
				<?php if ($params->get('show_parent_category') && !empty($this->item->parent_slug)) : ?>
					<dd class="parent-category-name">
						<i class="icon-circle-arrow-up"></i> <?php // Wright v.3: Icon ?>
						<?php	$title = $this->escape($this->item->parent_title);
						$url = '<a href="' . JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->parent_slug)) . '">' . $title . '</a>';?>
						<?php if ($params->get('link_parent_category') && !empty($this->item->parent_slug)) : ?>
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
						<i class="icon-folder-close"></i> <?php // Wright v.3: Category icon ?>
						<?php 	$title = $this->escape($this->item->category_title);
						$url = '<a href="' . JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->catslug)) . '">' . $title . '</a>';?>
						<?php if ($params->get('link_category') && $this->item->catslug) : ?>
							<?php echo $wrightBeforeIcon . JText::sprintf('COM_CONTENT_CATEGORY', $url) . $wrightAfterIcon; // Wright v.3: Icon for non-mobile version ?>
							<?php echo $wrightBeforeIconM . JText::sprintf($url) . $wrightAfterIconM; // Wright v.3: Icon for mobile version ?>
						<?php else : ?>
							<?php echo $wrightBeforeIcon . JText::sprintf('COM_CONTENT_CATEGORY', $title) . $wrightAfterIcon; // Wright v.3: Icon for non-mobile version ?>
							<?php echo $wrightBeforeIconM . JText::sprintf($title) . $wrightAfterIconM; // Wright v.3: Icon for mobile version ?>
						<?php endif; ?>
					</dd>
				<?php endif; ?>
				<?php if ($params->get('show_publish_date')) : ?>
					<dd class="published">
						<span class="icon-calendar"></span>
						<?php echo $wrightBeforeIcon . JText::sprintf('COM_CONTENT_PUBLISHED_DATE_ON', JHtml::_('date', $this->item->publish_up, JText::_('DATE_FORMAT_LC3'))) . $wrightAfterIcon; // Wright v.3: Icon for non-mobile version ?>
						<?php echo $wrightBeforeIconM . JText::sprintf(JHtml::_('date', $this->item->publish_up, JText::_('DATE_FORMAT_LC3'))) . $wrightAfterIconM; // Wright v.3: Icon for mobile version ?>
					</dd>
				<?php endif; ?>
			<?php endif; ?>

			<?php if ($params->get('show_create_date')) : ?>
				<dd class="create">
					<span class="icon-pencil"></span> <?php // Wright v.3: Changed Icon ?>
					<?php echo $wrightBeforeIcon . JText::sprintf('COM_CONTENT_CREATED_DATE_ON', JHtml::_('date', $this->item->created, JText::_('DATE_FORMAT_LC3'))) . $wrightAfterIcon; // Wright v.3: Icon for non-mobile version ?>
					<?php echo $wrightBeforeIconM . JText::sprintf(JHtml::_('date', $this->item->created, JText::_('DATE_FORMAT_LC3'))) . $wrightAfterIconM; // Wright v.3: Icon for mobile version ?>
				</dd>
			<?php endif; ?>
			<?php if ($params->get('show_modify_date')) : ?>
				<dd class="modified">
					<span class="icon-edit"></span> <?php // Wright v.3: Changed Icon ?>
					<?php echo $wrightBeforeIcon . JText::sprintf('COM_CONTENT_LAST_UPDATED', JHtml::_('date', $this->item->modified, JText::_('DATE_FORMAT_LC3'))) . $wrightAfterIcon; // Wright v.3: Icon for non-mobile version ?>
					<?php echo $wrightBeforeIconM . JText::sprintf(JHtml::_('date', $this->item->modified, JText::_('DATE_FORMAT_LC3'))) . $wrightAfterIconM; // Wright v.3: Icon for mobile version ?>
				</dd>
			<?php endif; ?>
			<?php if ($params->get('show_hits')) : ?>
				<dd class="hits">
					<span class="icon-eye-open"></span>
					<?php echo $wrightBeforeIcon . JText::sprintf('COM_CONTENT_ARTICLE_HITS', $this->item->hits) . $wrightAfterIcon; // Wright v.3: Icon for non-mobile version ?>
					<?php echo $wrightBeforeIconM . JText::sprintf($this->item->hits) . $wrightAfterIconM; // Wright v.3: Icon for mobile version ?>
				</dd>
			<?php endif; ?>
			</dl>
		</div>
	<?php endif; ?>

<?php
/* Wright v.3: Item elements structure */
		endif; // access-view
				break;
content_bottom:
		if ($params->get('access-view')):   // access-view
/* End Wright v.3: Item elements structure */
?>

	<?php
if (!empty($this->item->pagination) && $this->item->pagination && $this->item->paginationposition && !$this->item->paginationrelative):
	echo wrightTransformArticlePager($this->item->pagination);  // Wright v.3: Pager styles (using helper)
?>
	<?php endif; ?>
	<?php if (isset($urls) && ((!empty($urls->urls_position) && ($urls->urls_position == '1')) || ($params->get('urls_position') == '1'))) : ?>
	<?php echo $this->loadTemplate('links'); ?>
	<?php endif; ?>
	<?php // Optional teaser intro text for guests ?>
	<?php elseif ($params->get('show_noauth') == true && $user->get('guest')) : ?>
	<?php echo $this->item->introtext; ?>
	<?php //Optional link to let them register to see the whole article. ?>
	<?php if ($params->get('show_readmore') && $this->item->fulltext != null) :
		$link1 = JRoute::_('index.php?option=com_users&view=login');
		$link = new JUri($link1);?>
	<p class="readmore">
		<a href="<?php echo $link; ?>">
		<?php $attribs = json_decode($this->item->attribs); ?>
		<?php
		if ($attribs->alternative_readmore == null) :
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
		</a>
	</p>
	<?php endif; ?>
	<?php endif; ?>
	<?php
if (!empty($this->item->pagination) && $this->item->pagination && $this->item->paginationposition && $this->item->paginationrelative) :
 echo wrightTransformArticlePager($this->item->pagination);  // Wright v.3: Pager styles (using helper)
?>
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

	<?php echo $this->item->event->afterDisplayContent; ?> </div>
