<div class="row">
	<?php foreach($articles as $c): ?>
	<div class="col-sx-12 col-sm-6 col-md-6">
		<div class="item-content <?php print $c->type?>">
			<div class="row">
				<div class="col-md-5">
					<div class="image">
						<a href="<?php print $c->link; ?>">
							<?php print $c->TheThumbnail('500x500'); ?>
						</a>
					</div>
				</div>
				<div class="col-md-7">
					<h4 class="title"><?php print $c->TheTitle(); ?></h4>
					<div class="excerpt"><?php print $c->TheExcerpt(); ?></div>
					<div class="text-center">
						<a href="<?php print $c->link; ?>" class="btn btn-warning"><?php _e('Ver mas', 'om'); ?></a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php endforeach; ?>
</div>