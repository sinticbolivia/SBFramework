<?php
?>
<div id="dashboard" class="wrap">
	<h1><?php print SB_Text::_('Inicio'); ?></h1>
	<div class="row"><?php SB_Module::do_action('admin_dashboard'); ?></div>
</div>