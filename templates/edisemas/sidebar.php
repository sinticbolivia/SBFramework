<div id="sidebar">
	<?php if( !sb_widget_area('sidebar') ): ?>
	<div class="widget">
		<div class="widget-title"><?php print SB_Text::_('Secciones', 'ltcms'); ?></div>
		<div class="widget-body"><?php print sb_sections_html_list(array('show_checkbox' => 0, 'show_links' => 1)); ?></div>
	</div>
	<?php endif; ?>
</div><!-- end id="sidebar" -->