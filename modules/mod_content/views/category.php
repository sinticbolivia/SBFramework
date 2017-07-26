<?php
?>
<div class="lt-category">
	<h1 id="page-title"><?php print $category->name; ?></h1>
	<div class="lt-category-description"><?php print $category->description; ?></div>
	<div class="lt-category-posts">
		<?php foreach($posts as $p): ?>
		<div class="lt-post">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-3">
						<div class="image">
							<a href="<?php print $p->link; ?>">
								<?php print $p->TheThumbnail('500x500'); ?>
							</a>
						</div>
					</div>
					<div class="col-md-9">
						<h4 class="title"><a href="<?php print $p->link; ?>"><?php print $p->TheTitle(); ?></a></h4>
						<div class="excerpt"><?php print $p->TheExcerpt(); ?></div>
						<div class="text-center">
							<a href="<?php print $p->link; ?>" class="btn btn-warning"><?php _e('Ver mas', 'om'); ?></a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php endforeach; ?>
	</div>
</div>