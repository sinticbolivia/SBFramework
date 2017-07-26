<div id="sidebar" class="col-xs-12 col-sm-3 col-md-3">
	<div class="widget">
		<h2 class="title"><?php _e('Categorias', 'elite'); ?></h2>
		<div class="body">
			<?php print mb_dropdown_categories(array('show_link' => true)); ?>
		</div>
	</div>
	<?php sb_show_widget('SB_MBWidgetMostViewed', array('limit' => 5, 
															'title' => __('Tambien te puede interesar', 'elite'))); ?>
</div><!-- end id="sidebar" -->