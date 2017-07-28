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
			<div id="content" class="col-xs-12 col-sm-9 col-md-9">
				<h1 id="content-title"><?php print $article->TheTitle(); ?></h1>
				<section id="the-content">
					<?php print $article->TheContent(); ?>
				</section><!-- end id="the-content" -->
			</div><!-- end id="content" -->
			<?php lt_get_sidebar(); ?>
		</div>
	</div>
</div><!-- end id="content-wrap" -->
<?php
lt_get_footer();
?>