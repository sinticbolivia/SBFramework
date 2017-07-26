<?php 
//print_r($slider); 
?>
<div id="frontpage-slider" class="row">
	<div id="carousel" class="carousel slide" data-ride="carousel">
		<!-- Indicators -->
		<ol class="carousel-indicators">
			<?php $i = 0; foreach($slider->images as $s): ?>
			<li data-target="#carousel" data-slide-to="<?php print $i; ?>" class="<?php print $i == 0 ? 'active' : ''; ?>"></li>
			<?php $i++; endforeach; ?>
		</ol>
		<!-- Wrapper for slides -->
		<div class="carousel-inner" role="listbox">
			<?php $i = 0; foreach($slider->images as $img): ?>
			<div class="item <?php print $i == 0 ? 'active' : ''; ?>">
				<img src="<?php print MOD_SLIDER_UPLOADS_URL . '/' . $img->image; ?>" alt="<?php print $img->title; ?>">
				<div class="carousel-caption">
					<div class="content">
						<div class="title"><?php print $img->title; ?></div>
						<div class="text">
							<?php print $img->description; ?>
						</div>
					</div><br/>
					<p class="text-center">
						<a href="<?php print $img->link; ?>" class="btn-readmore">Leer mas</a>
					</p>
				</div>
			</div>
			<?php $i++; endforeach; ?>
		</div>
		<!-- Controls -->
		<a class="left carousel-control" href="#carousel" role="button" data-slide="prev">
			<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
			<span class="sr-only">Anterior</span>
		</a>
		<a class="right carousel-control" href="#carousel" role="button" data-slide="next">
			<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
			<span class="sr-only">Siguiente</span>
		</a>
	</div>
</div><!-- end id="frontpage-slider" -->