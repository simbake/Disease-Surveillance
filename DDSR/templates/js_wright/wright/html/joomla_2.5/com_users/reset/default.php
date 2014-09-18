<?php
// Wright v.3 Override: Joomla 2.5.14
/**
 * @package		Joomla.Site
 * @subpackage	com_users
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @since		1.5
 */

defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>
<div class="reset<?php echo $this->pageclass_sfx?>">
	<?php if ($this->params->get('show_page_heading')) : ?>
	<div class="page-header">  <?php // Wright v.3: Added page header ?>
		<h1>
			<?php echo $this->escape($this->params->get('page_heading')); ?>
		</h1>
	</div>  <?php // Wright v.3: Added page header ?>
	<?php endif; ?>

	<form id="user-registration" action="<?php echo JRoute::_('index.php?option=com_users&task=reset.request'); ?>" method="post" class="form-validate form-horizontal">  <?php // Wright v.3: Added form-horizontal class ?>

		<?php foreach ($this->form->getFieldsets() as $fieldset): ?>
		<p><?php echo JText::_($fieldset->label); ?></p>		<fieldset>
			<dl>
			<?php foreach ($this->form->getFieldset($fieldset->name) as $name => $field): ?>
				<dt><?php echo $field->label; ?></dt>
				<dd><?php echo $field->input; ?></dd>
			<?php endforeach; ?>
			</dl>
		</fieldset>
		<?php endforeach; ?>

		<div class="form-actions">  <?php // Wright v.3: Added form-actions class ?>
			<button type="submit" class="validate btn button btn-primary"><?php echo JText::_('JSUBMIT'); ?></button>  <?php // Wright v.3: Added btn button btn-primary classes ?>
			<?php echo JHtml::_('form.token'); ?>
		</div>
	</form>
</div>
