<?php
$layout = SB_Request::getString('layout', 'list');
?>
<div class="lt-content <?php print $layout; ?>">
	<?php foreach($articles as $a): if( !$a->IsVisible() ) continue; ?>
	<div class="lt-article">
		<div class="row">
			<div class="col-md-2">
				<?php print $a->TheThumbnail(); ?>
			</div>
			<div class="col-md-10">
				<h2 class="title">
					<a href="<?php print SB_Route::_('index.php?mod=content&view=article&id='.$a->content_id); ?>">
						<?php print $a->title; ?>
					</a>
				</h2>
				<div class="content"><?php print $a->excerpt; ?></div>
			</div>
		</div>
	</div>
	<?php endforeach; ?>
</div>