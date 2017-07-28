<?php
lt_get_header();
?>
<div id="content" class="col-xs-12 col-sm-9 col-md-9">
	<?php SB_MessagesStack::ShowMessages(); ?>
	<div class="container-fluid">
		<?php sb_show_module(); ?>
	</div>
</div><!-- end id="content" -->
<div id="sidebar" class="col-xs-12 col-sm-3 col-md-3">
	<div class="widget">
		<h2 class="title">Tambien te puede interesar</h2>
		<div class="body"></div>
	</div>
</div><!-- end id="sidebar" -->
<?php
lt_get_footer();
?>