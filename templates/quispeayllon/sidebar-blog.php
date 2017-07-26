<?php
$pages = LT_HelperContent::GetArticles(array(
			'type'			=> 'post',
			'rows_per_page' => 6
		));
?>
<div id="sidebar" class="col-sm-12 col-md-3">
	<div class="widget">
		<h2 class="title"><?php print _e('&Uacute;ltimas publicaciones', 'qa'); ?></h2>
		<div class="body">
			<ul class="latest-blog-entries">
				<?php foreach($pages['articles'] as $e): ?>
				<li class="blog-entry">
					<div class="image">
						<a href="<?php print $e->link; ?>">
							<?php print $e->TheThumbnail(); ?>
						</a>
					</div>
					<div class="title">
						<a href="<?php print $e->link; ?>"><?php print $e->title; ?></a>
					</div>
				</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</div><!-- end id="sidebar" -->