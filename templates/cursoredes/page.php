<?php lt_get_header(); ?>
	<div id="content-wrap" class="row">
		<div class="col-md-12">
			<div id="content">
				<?php if( $article->_featured_image_id ): ?>
				<div id="page-image">
					<figure><img src="<?php print $article->GetThumbnailUrl('full'); ?>" alt="<?php print $article->TheTitle(); ?>" /></figure>
				</div><!-- end id="page-image" -->
				<?php endif; ?>
				<h1 id="page-title"><?php print $article->TheTitle(); ?></h1>
				<div id="page-content"><?php print $article->TheContent(); ?></div>
			</div>
		</div>
	</div><!-- end id="content-wrap" -->
<?php lt_get_footer(); ?>