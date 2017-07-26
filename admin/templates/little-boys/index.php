<?php
lt_get_header();
?>
	<div id="content" class="col-xs-12 col-md-10">
		<div class="messages hidden-print"><?php SB_MessagesStack::ShowMessages(); ?></div>
		<?php sb_show_module(isset($_html_content) ? $_html_content : null); ?>
		<div class="clearfix"></div>
	</div><!-- end id="content" -->
<?php lt_get_footer();  ?>
