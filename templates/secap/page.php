<?php 
lt_get_header();
?>
	<div id="wrap-content" class="row">
		<div id="content" class="">
			<h1 id="content-title"><?php print $article->TheTitle(); ?> <span class="icon icon-calc"></span></h1>
			<div id="content-body">
				<div class="container">
					<?php SB_MessagesStack::ShowMessages(); ?>
					<div class="row">
						<div id="content-text" class="col-xs-12 col-sm-6 col-md-7">
							<?php print stripslashes($article->TheContent()); ?>
						</div>
						<div id="content-image" class="col-xs-12 col-sm-6 col-md-5">
							<?php if( $article->_featured_image_id ): ?>
								<img src="<?php print $article->GetThumbnailUrl('full'); ?>" alt="" />
							<?php endif; ?>
						</div>
					</div>
					<div>
						<div class="col-md-12">
							<!-- Go to www.addthis.com/dashboard to customize your tools -->
							<div class="addthis_inline_share_toolbox"></div>
						</div>
					</div>
				</div>
			</div><!-- end id="content-body" -->
		</div><!-- end id="content" -->
	</div>
<?php
lt_get_footer();
?>