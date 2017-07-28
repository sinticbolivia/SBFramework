<?php
lt_get_header();
?>
	<div ><?php SB_MessagesStack::ShowMessages(); ?></div>
	<div id="content">
		<div class="container">
			<?php sb_show_module(); ?>
		</div>
	</div>
<?php lt_get_footer(); ?>