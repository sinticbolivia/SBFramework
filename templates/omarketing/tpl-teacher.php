<?php
lt_get_header();
?>
<div id="content-wrap" class="row">
	<?php /*
	<div id="page-banner" class="">
		<img src="<?php print TEMPLATE_URL; ?>/images/banner01.jpg" alt="" />
	</div>
	*/?>
	<?php SB_MessagesStack::ShowMessages(); ?>
	<div  class="col-md-12">
		<div class="row">
			<div id="content" class="col-md-9">
				<h1 id="content-title"><?php _e('Biografia del Instructor', 'om'); ?></h1>
				<section id="the-content">
					<?php sb_show_module(); ?>
				</section><!-- end id="the-content" -->
			</div><!-- end id="content" -->
			<?php lt_get_sidebar(); ?>
		</div>
	</div>
</div><!-- end id="content-wrap" -->
<?php
lt_get_footer();
?>