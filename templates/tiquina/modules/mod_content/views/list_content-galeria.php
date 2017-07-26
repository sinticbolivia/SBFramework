<div id="galeria-container">
	<div class="container-fluid">
		<div class="row">
			<?php foreach($articles as $img): ?>
			<div class="col-xs-12 col-sm-4 col-md-3">
				<div class="gallery-image">
					<a href="<?php print $img->GetThumbnailUrl('full'); ?>">
						<?php print $img->TheThumbnail(); ?>
					</a>
					<div class="description" data-src="<?php print $img->GetThumbnailUrl('full'); ?>">
						<?php print $img->TheContent(); ?>
					</div>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</div><!-- end id="galeria-container" -->