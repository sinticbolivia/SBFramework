<?php if( $args['type'] == 'libros' ): ?>
<div class="row">
	<?php foreach($articles as $c): ?>
	<div class="col-sx-1 col-sm-6 col-md-3">
		<div class="item-content <?php print $c->type?>">
			<div class="image">
				<a href="<?php print $c->link; ?>">
					<?php print $c->TheThumbnail();//$c->getThumbnail('150x150'); ?>
				</a>
			</div>
			<div class="title"><?php print $c->TheTitle(); ?></div>
		</div>
	</div>
	<?php endforeach; ?>
</div>
<?php else: ?>
<div class="row">
	<?php foreach($articles as $c): ?>
	<div class="col-sx-1 col-sm-6 col-md-3">
		<div class="item-content <?php print $c->type?>">
			<div class="image">
				<a href="<?php print $c->link; ?>">
					<?php print $c->TheThumbnail();//$c->getThumbnail('150x150'); ?>
				</a>
			</div>
			<div class="title"><?php print $c->TheTitle(); ?></div>
		</div>
	</div>
	<?php endforeach; ?>
</div>
<?php endif; ?>