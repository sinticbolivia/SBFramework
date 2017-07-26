<?php lt_get_header(); ?>
<div id="content-wrap" class="container-fluid">
	<div class="row">
		<div class="col-xs-12 col-sm-9 col-md-9">
			<div id="content" class="page-content">
				<h1 id="page-title"><?php print $article->TheTitle(); ?></h1>
				<?php print $article->TheContent(); ?>
			</div><!-- end id="content" -->
		</div>
		<div class="col-xs-12 col-sm-3 col-md-3">
			<?php lt_get_sidebar(); ?>
		</div>
	</div>
</div><!-- end id="content-wrap" -->
<?php lt_get_footer(); ?>