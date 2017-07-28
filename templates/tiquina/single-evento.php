<?php lt_get_header(); ?>
<div id="content-wrap" class="container-fluid">
	<div class="row">
		<div class="col-xs-12 col-sm-9 col-md-9">
			<div id="content" class="evento-content">
				<h1 id="page-title"><?php print $article->TheTitle(); ?></h1>
				<?php print $article->TheContent(); ?>
				<section id="social-buttons">
					<h4 class="title"><?php _e('Comp&aacute;rtelo:', 'tiquina'); ?></h4>
					<!-- Go to www.addthis.com/dashboard to customize your tools -->
					<div class="addthis_inline_share_toolbox"></div>
				</section>
			</div><!-- end id="content" -->
		</div>
		<div class="col-xs-12 col-sm-3 col-md-3">
			<?php lt_get_sidebar(); ?>
		</div>
	</div>
</div><!-- end id="content-wrap" -->
<?php lt_get_footer(); ?>