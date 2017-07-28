<div id="events">
	
	<div class="container-fluid">
		<?php foreach($articles as $evt): ?>
		<div class="row">
			<div class="col-xs-12 col-sm-3 col-md-3">
				<figure class="image">
					<a href="<?php print $evt->link; ?>">
						<?php print $evt->TheThumbnail('500x500'); ?>
					</a>
				</figure>
			</div>
			<div class="col-xs-12 col-sm-9 col-md-9">
				<h2><a href="<?php print $evt->link; ?>"><?php print $evt->TheTitle(); ?></a></h2>
				<p class="excerpt"><?php print $evt->TheExcerpt(); ?></p>
			</div>
		</div>
		<?php endforeach; ?>
	</div>
</div><!-- end id="events" -->