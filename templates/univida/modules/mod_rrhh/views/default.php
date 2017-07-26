<?php
$view = SB_Request::getString('view', 'default');
?>
<div id="announcements-list" class="container">
	<div>
		<a href="<?php print SB_Route::_('index.php?mod=rrhh'); ?>" class="btn btn-default <?php print $view == 'default' ? 'active' : ''; ?>">
			<?php _e('Announcements', 'rrhh'); ?>
		</a>
		<a href="<?php print SB_Route::_('index.php?mod=rrhh&view=profile'); ?>" class="btn btn-default <?php print $view == 'profile' ? 'active' : ''; ?>">
			<?php _e('My Profile', 'rrhh'); ?>
		</a>
		<a href="<?php print SB_Route::_('index.php?mod=rrhh&task=logout'); ?>" class="btn btn-default">
			<?php _e('Cerrar Sesi&oacute;n', 'rrhh'); ?>
		</a>
	</div><br/>
	<h1><?php print $title; ?></h1>
	<?php print $table->Show(); ?>
</div>