<?php
?>
<div id="dashboard" class="wrap">
	<h2 id="page-title"><?php _e('Home', 'dashboard'); ?></h2>
	<div class="container-fluid">
		<div class="row"><?php b_do_action('admin_dashboard'); //SB_Module::do_action('admin_dashboard'); ?></div>
	</div>
</div>