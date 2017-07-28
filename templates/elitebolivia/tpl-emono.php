<?php
?>
<?php lt_get_header(); ?>
	<div id="content" class="col-xs-12 col-md-9">
		<?php SB_MessagesStack::ShowMessages(); ?>
		<?php sb_show_module(); ?>
	</div><!-- end id="content" --> 
	<?php lt_get_sidebar('store'); ?>
<?php lt_get_footer(); ?>