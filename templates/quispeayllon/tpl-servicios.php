<?php
/**
 * Template: Servicios
 */
lt_get_header();
?>
	<div ><?php SB_MessagesStack::ShowMessages(); ?></div>
	<div id="wrap-content">
		<div class="container">
			<div id="content" class="col-sm-12 col-md-9">
				<?php
				$res = LT_HelperContent::GetArticles(array('type' => 'servicios', 
												'publish_date' => false, 
												'end_date' => false, 
												'rows_per_page' => 25));
				?>
				<h1><?php print $article->TheTitle(); ?></h1>
				<div class="row">
					<?php foreach($res['articles'] as $post): ?>
					<div class="service col-sm-12 col-md-4">
						<div class="image">
							<a href="<?php print $post->link; ?>"><?php print $post->TheThumbnail(); ?></a>
						</div>
						<div class="info">
							<h3 class="title"><a href="<?php print $post->link ?>"><?php print $post->TheTitle(); ?></a></h3>
							<p><?php print $post->TheExcerpt(); ?></p>
						</div>
					</div>
					<?php endforeach; ?>
				</div>
			</div>
			<?php lt_get_sidebar('blog'); ?>
		</div>
	</div>
<?php lt_get_footer(); ?>