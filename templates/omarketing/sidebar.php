<div id="sidebar" class="col-xs-12 col-sm-3 col-md-3">
	<?php 
	sb_show_widget('SB_MBWidgetLatestContent', 
					array(
						'title' => __('&Uacute;ltimas publicaciones', 'om'),
						'type'	=> 'post'
					)
	);
	sb_show_widget('LMS_WidgetTeachers', 
					array(
						'title' => __('Instructores', 'om')
					)
	); 
	?>
</div><!-- end id="sidebar" -->