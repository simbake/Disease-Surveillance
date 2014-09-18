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

/* Wright v.3: Item elements structure and extra elements */
	if (!isset($this->wrightElementsStructure)) $this->wrightElementsStructure = Array();
	if (!isset($this->wrightHasImageClass)) $this->wrightHasImageClass = "";
	if (!isset($this->wrightExtraClass)) $this->wrightExtraClass = "";
	
	if (empty($this->wrightElementsStructure)) $this->wrightElementsStructure = Array("title","icons","article-info","image","content");
	
/* End Wright v.3: Item elements structure and extra elements */

/* Wright v.3: Bootstrapped images */
	$app = JFactory::getApplication();
	$template = $app->getTemplate(true);
	$this->wrightBootstrapImages = $template->params->get('wright_bootstrap_images','');
/* End Wright v.3: Bootstrapped images */

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');

// Create shortcuts to some parameters.
$params		= $this->item->params;
$images = json_decode($this->item->images);
$urls = json_decode($this->item->urls);
$canEdit	= $this->item->params->get('access-edit');
$user		= JFactory::getUser();

?>
<div class="item-page<?php echo $this->pageclass_sfx?><?php echo ($this->wrightExtraClass != '' ? ' ' . $this->wrightExtraClass : ''); if ($this->wrightHasImageClass != '') { echo ((isset($images->image_intro) and !empty($images->image_intro)) ? ' ' . $this->wrightHasImageClass : ''); } // Wright v.3: Item elements extra elements
 ?>">
<?php if ($this->params->get('show_page_heading')) : ?>
	<?php echo '<div class="page-header">'; // Wright v.3: Page header ?>
	<h1>
	<?php echo $this->escape($this->params->get('page_heading')); ?>
	</h1>
	<?php echo '</div>'; // Wright v.3: Page header?>
<?php endif; ?>
<?php
if (!empty($this->item->pagination) AND $this->item->pagination && !$this->item->paginationposition && $this->item->paginationrelative)
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


<?php if ($params->get('show_title')) : ?>
	<?php
	if (!$this->params->get('show_page_heading')) : ?>
	<div class="page-header">
	<?php endif;
		/* End Wright v.3: Added page header */
	?>
		<h2>
		<?php if ($params->get('link_titles') && !empty($this->item->readmore_link)) : ?>
			<a href="<?php echo $this->item->readmore_link; ?>">
			<?php echo $this->escape($this->item->title); ?></a>
		<?php else : ?>
			<?php echo $this->escape($this->item->title); ?>
		<?php endif; ?>
		</h2>
	<?php
		/* Wright v.3: Added page header */
	if (!$this->params->get('show_page_heading')) : ?>
	</div>
	<?php endif;
		/* End Wright v.3: Added page header */
	?>
<?php endif; ?>

<?php
/* Wright v.3: Item elements structure */
				break;
			case "icons":
/* End Wright v.3: Item elements structure */
?>

<?php if ($canEdit ||  $params->get('show_print_icon') || $params->get('show_email_icon')) : ?>
	<?php
		/* Wright v.3: Icons dropdown */
	?>
		<div class="btn-group pull-right icons-actions">  <?php // Wright v.3: Added icons-actions class ?>
			<a class="btn dropdown-toggle" href="#" data-toggle="dropdown">
				<i class="icon-cog"></i>
				<span class="caret"></span>
			</a>
	<?php
		/* End Wright v.3: Icons dropdown */
	?>
	<ul class="actions<?php echo " dropdown-menu" // Wright v.3: Icons dropdown ?>">
	<?php if (!$this->print) : ?>
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

	<?php else : ?>
		<li>
		<?php echo JHtml::_('icon.print_screen',  $this->item, $params); ?>
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

<?php  if (!$params->get('show_intro')) :
	echo $this->item->event->afterDisplayTitle;
endif; ?>

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

<?php $useDefList = (($params->get('show_author')) or ($params->get('show_category')) or ($params->get('show_parent_category'))
	or ($params->get('show_create_date')) or ($params->get('show_modify_date')) or ($params->get('show_publish_date'))
	or ($params->get('show_hits'))); ?>

<?php if ($useDefList) : ?>
	<dl class="article-info<?php echo ' muted'; // Wright v.3: Muted style ?>">
	<dt class="article-info-term"><?php  echo JText::_('COM_CONTENT_ARTICLE_INFO'); ?></dt>
<?php endif; ?>
<?php if ($params->get('show_parent_category') && $this->item->parent_slug != '1:root') : ?>
	<dd class="parent-category-name">
		<i class="icon-circle-arrow-up"></i> <?php // Wright v.3: Icon ?>
	<?php	$title = $this->escape($this->item->parent_title);
	$url = '<a href="'.JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->parent_slug)).'">'.$title.'</a>';?>
	<?php if ($params->get('link_parent_category') and $this->item->parent_slug) : ?>
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
	<?php 	$title = $this->escape($this->item->category_title);
	$url = '<a href="'.JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->catslug)).'">'.$title.'</a>';?>
	<?php if ($params->get('link_category') and $this->item->catslug) : ?>
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
	<?php $author = $this->item->created_by_alias ? $this->item->created_by_alias : $this->item->author; ?>
	<?php if (!empty($this->item->contactid) && $params->get('link_author') == true): ?>
	<?php
		$needle = 'index.php?option=com_contact&view=contact&id=' . $this->item->contactid;
		$menu = JFactory::getApplication()->getMenu();
		$item = $menu->getItems('link', $needle, true);
		$cntlink = !empty($item) ? $needle . '&Itemid=' . $item->id : $needle;
	?>
		<?php echo $wrightBeforeIcon . JText::sprintf('COM_CONTENT_WRITTEN_BY', JHtml::_('link', JRoute::_($cntlink), $author)) . $wrightAfterIcon; // Wright v.3: Icon for non-mobile version ?>
		<?php echo $wrightBeforeIconM . JText::sprintf(JHtml::_('link', JRoute::_($cntlink), $author)) . $wrightAfterIconM; // Wright v.3: Icon for mobile version ?>
	<?php else: ?>
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
<?php if ($useDefList) : ?>
	</dl>
<?php endif; ?>

<?php if (isset ($this->item->toc)) : ?>
	<?php echo wrightTransformArticleTOC($this->item->toc);  // Wright v.3: TOC transformation (using helper) ?>
<?php endif; ?>

<?php if (isset($urls) AND ((!empty($urls->urls_position) AND ($urls->urls_position=='0')) OR  ($params->get('urls_position')=='0' AND empty($urls->urls_position) ))
		OR (empty($urls->urls_position) AND (!$params->get('urls_position')))): ?>
<?php echo $this->loadTemplate('links'); ?>
<?php endif; ?>

<?php
/* Wright v.3: Item elements structure */
				break;
			case "image":
/* End Wright v.3: Item elements structure */
?>

<?php if ($params->get('access-view')):?>
<?php  if (isset($images->image_fulltext) and !empty($images->image_fulltext)) : ?>
<?php $imgfloat = (empty($images->float_fulltext)) ? $params->get('float_fulltext') : $images->float_fulltext; ?>
<div class="img-fulltext-<?php echo htmlspecialchars($imgfloat); ?>">
<img
	<?php if ($images->image_fulltext_caption):
		echo 'class="caption ' . $this->wrightBootstrapImages . '"'.' title="' .htmlspecialchars($images->image_fulltext_caption) .'"';  // Wright .v.3: Added image class
	/* Wright v.3: Image class when no caption present */
	else:
		echo 'class="' . $this->wrightBootstrapImages . '"';
	/* End Wright v.3: Image class when no caption present */
	endif; ?>
	src="<?php echo htmlspecialchars($images->image_fulltext); ?>" alt="<?php echo htmlspecialchars($images->image_fulltext_alt); ?>" />
</div>
<?php endif; ?>
<?php
if (!empty($this->item->pagination) AND $this->item->pagination AND !$this->item->paginationposition AND !$this->item->paginationrelative):
	echo wrightTransformArticlePager($this->item->pagination);  // Wright v.3: Pager styles (using helper)
 endif;
?>

<?php
/* Wright v.3: Item elements structure */
		endif; // access-view
				break;
			case "content":
		if ($params->get('access-view')):   // access-view
/* End Wright v.3: Item elements structure */
?>


<?php echo wrightTransformArticleContent($this->item->text);  // Wright v.3: Transform article content's plugins (using helper)
 ?>
<?php
if (!empty($this->item->pagination) AND $this->item->pagination AND $this->item->paginationposition AND!$this->item->paginationrelative):
	 echo wrightTransformArticlePager($this->item->pagination);  // Wright v.3: Pager styles (using helper) ?>
<?php endif; ?>

<?php if (isset($urls) AND ((!empty($urls->urls_position)  AND ($urls->urls_position=='1')) OR ( $params->get('urls_position')=='1') )): ?>
<?php echo $this->loadTemplate('links'); ?>
<?php endif; ?>
	<?php //optional teaser intro text for guests ?>
<?php elseif ($params->get('show_noauth') == true and  $user->get('guest') ) : ?>
	<?php echo $this->item->introtext; ?>
	<?php //Optional link to let them register to see the whole article. ?>
	<?php if ($params->get('show_readmore') && $this->item->fulltext != null) :
		$link1 = JRoute::_('index.php?option=com_users&view=login');
		$link = new JURI($link1);?>
		<p class="readmore">
		<a href="<?php echo $link; ?>">
		<?php $attribs = json_decode($this->item->attribs);  ?>
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
		endif; ?></a>
		</p>
	<?php endif; ?>
<?php endif; ?>
<?php
if (!empty($this->item->pagination) AND $this->item->pagination AND $this->item->paginationposition AND $this->item->paginationrelative):
	 echo wrightTransformArticlePager($this->item->pagination);  // Wright v.3: Pager styles (using helper) ?>
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
</div>
