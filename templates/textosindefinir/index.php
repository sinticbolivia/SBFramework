<?php lt_get_header(); ?>
		<?php lt_get_sidebar(); ?>
		<div id="content" class="col-md-9">
			<div id="content-wrap">
				<?php SB_MessagesStack::ShowMessages(); ?>
				<?php sb_show_module(); ?>
			</div>
		</div><!-- end id="content" -->
<?php lt_get_footer(); ?>