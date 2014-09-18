<?php
/**
 * @version 2.0.0.stable
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

// no direct access
defined('_JEXEC') or die ('Restricted access'); ?>
<div style="border: 0px !important;">
<div id="djslider-loader<?php echo $mid; ?>" class="djslider-loader">
    <div id="djslider<?php echo $mid; ?>" class="djslider">
        <div id="slider-container<?php echo $mid; ?>" class="slider-container">
        	<ul id="slider<?php echo $mid; ?>">
          		<?php foreach ($slides as $slide) { ?>
          			<li>
          				<?php if($slide->image) { ?>
	            			<?php if (($slide->link && $params->get('link_image',1)==1) || $params->get('link_image',1)==2) { ?>
								<a <?php echo ($params->get('link_image',1)==2 ? 'class="djmodal"' : ''); ?> href="<?php echo ($params->get('link_image',1)==2 ? $slide->image : $slide->link); ?>" target="<?php echo $slide->target; ?>">
							<?php } ?>
								<img src="<?php echo $slide->image; ?>" alt="<?php echo $slide->alt; ?>" />
							<?php if (($slide->link && $params->get('link_image',1)==1) || $params->get('link_image',1)==2) { ?>
								</a>
							<?php } ?>
						<?php } ?>
						<?php if($params->get('slider_source') && ($params->get('show_title') || ($params->get('show_desc') && !empty($slide->description)))) { ?>
						<!-- Slide description area: START -->
						<div class="slide-desc">
						  <div class="slide-desc-in">	
							<div class="slide-desc-bg"></div>
							<div class="slide-desc-text">
							<?php if($params->get('show_title')) { ?>
								<div class="slide-title">
									<?php if($params->get('link_title') && $slide->link) { ?><a href="<?php echo $slide->link; ?>" target="<?php echo $slide->target; ?>"><?php } ?>
										<?php echo $slide->title; ?>
									<?php if($params->get('link_title') && $slide->link) { ?></a><?php } ?>
								</div>
							<?php } ?>
							
							<?php if($params->get('show_desc')) { ?>
								<div class="slide-text">
									<?php if($params->get('link_desc') && $slide->link) { ?>
									<a href="<?php echo $slide->link; ?>" target="<?php echo $slide->target; ?>">
										<?php echo strip_tags($slide->description,"<br><span><em><i><b><strong><small><big>"); ?>
									</a>
									<?php } else { ?>
										<?php echo $slide->description; ?>
									<?php } ?>
								</div>
							<?php } ?>
							
							<?php if($params->get('show_readmore') && $slide->link) { ?>
								<a href="<?php echo $slide->link; ?>" target="<?php echo $slide->target; ?>" class="readmore"><?php echo ($params->get('readmore_text',0) ? $params->get('readmore_text') : JText::_('MOD_DJIMAGESLIDER_READMORE')); ?></a>
							<?php } ?>
							<div style="clear: both"></div>
							</div>
						  </div>
						</div>
						<!-- Slide description area: END -->
						<?php } ?>						
						
					</li>
                <?php } ?>
        	</ul>
        </div>
        <?php if($params->get('show_arrows') || $params->get('show_buttons')) { ?>
        <div id="navigation<?php echo $mid; ?>" class="navigation-container">
        	<?php if($params->get('show_arrows')) { ?>
        	<img id="prev<?php echo $mid; ?>" class="prev-button" src="<?php echo $navigation->prev; ?>" alt="<?php echo JText::_('MOD_DJIMAGESLIDER_PREVIOUS'); ?>" />
			<img id="next<?php echo $mid; ?>" class="next-button" src="<?php echo $navigation->next; ?>" alt="<?php echo JText::_('MOD_DJIMAGESLIDER_NEXT'); ?>" />
			<?php } ?>
			<?php if($params->get('show_buttons')) { ?>
			<img id="play<?php echo $mid; ?>" class="play-button" src="<?php echo $navigation->play; ?>" alt="<?php echo JText::_('MOD_DJIMAGESLIDER_PLAY'); ?>" />
			<img id="pause<?php echo $mid; ?>" class="pause-button" src="<?php echo $navigation->pause; ?>" alt="<?php echo JText::_('MOD_DJIMAGESLIDER_PAUSE'); ?>" />
			<?php } ?>
        </div>
        <?php } ?>
        <?php if($params->get('show_custom_nav')) { ?>
		<div id="cust-navigation<?php echo $mid; ?>" class="navigation-container-custom">
			<?php $i = 0; foreach ($slides as $slide) { 
				?><span class="load-button<?php if ($i == 0) echo ' load-button-active'; ?>"><?php //echo ($i+1) ?></span><?php 
				if(count($slides) == $i + $count) break; else $i++; } ?>
        </div>
        <?php } ?>
    </div>
</div>
</div>
<div style="clear: both"></div>