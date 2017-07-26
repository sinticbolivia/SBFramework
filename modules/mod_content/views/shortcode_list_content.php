<div class="row">
	<?php foreach($articles as $c): ?>
	<div class="col-sx-1 col-sm-6 col-md-3">
		<div class="item-content <?php print $c->type?>">
			<div class="image">
				<a href="<?php print $c->link; ?>">
					<?php print $c->TheThumbnail('500x500'); ?>
				</a>
			</div>
			<div class="title">
				<a href="<?php print $c->link; ?>"><?php print $c->TheTitle(); ?></a>
			</div>
		</div>
	</div>
	<?php endforeach; ?>
</div>