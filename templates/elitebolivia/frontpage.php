<?php 
$banners = array(
	(object)array(
		'url'	=> TEMPLATE_URL . '/images/banners/banner-carrusel1.jpg',
		'link'	=> 'javascript:;',
		'text'	=> ''
	),
	(object)array(
		'url'	=> TEMPLATE_URL . '/images/banners/banner-carrusel2.jpg',
		'link'	=> 'javascript:;',
		'text'	=> ''
	),
	(object)array(
		'url'	=> TEMPLATE_URL . '/images/banners/banner-carrusel3.jpg',
		'link'	=> 'javascript:;',
		'text'	=> ''
	),
	(object)array(
		'url'	=> TEMPLATE_URL . '/images/banners/banner-carrusel4.jpg',
		'link'	=> 'javascript:;',
		'text'	=> ''
	),
);
lt_get_header(); 
?>
	<div id="front-slider">
		<div id="home_carousel" class="carousel slide" data-ride="carousel">
			<div class="carousel-inner" role="listbox">
				<?php foreach($banners as $i => $b): ?>
				<div class="item <?php print $i == 0 ? 'active' : ''; ?>">
					<img src="<?php print $b->url; ?>" alt="" class="img-resp" />
					<?php if( $b->text ): ?>
					<div class="carousel-caption"><?php print $b->text; ?></div>
					<?php endif; ?>
				</div>
				<?php endforeach; ?>
			</div>
			<!-- Controls -->
			<a class="left carousel-control" href="#home_carousel" data-slide="prev">
				<span class="glyphicon glyphicon-chevron-left"></span>
			</a>
			<a class="right carousel-control" href="#home_carousel" data-slide="next">
				<span class="glyphicon glyphicon-chevron-right"></span>
			</a>
			<ol class="carousel-indicators">
				<?php foreach($banners as $i => $b): ?>
				<li data-target="#home_carousel" data-slide-to="<?php print $i; ?>" class="<?php print $i == 0 ? 'active' : ''; ?>"></li>
				<?php endforeach; ?>
			</ol>
		</div>
	</div>
	<div id="front-content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-xs-12 col-sm-6 col-md-6">
					<section id="front-aboutus">
						<h2 class="title">Acerca de nosotros</h2>
						<article class="content">
							<p>
								ELITE ES UNA EMPRESA QUE SE DEDICA AL DESAROLLO E IMPLEMENTACION DE SISTEMAS 
								ELECTRONICOS DE SEGURIDAD CONTROL Y PARAMETRIZACION, 
								DESARROLLO DE SOFTWARE Y APLICACIONES WEB, INSTALACIONES ELECTRICAS INDUSTRIALES Y 
								RESIDENCIALES, REALIZAMOS EL MONITOREO LECTRONICO DE BIENES.
							</p>
						</article>
					</section>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6">
					<ul id="front-quality">
						<li class="one">Calidad...!!</li>
						<li class="two">Garantia...!!</li>
						<li class="three">Seguridad...!!</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<section id="front-latest-products">
		<?php sb_show_widget('SB_MBWidgetLatestProducts', array('limit' => 4)); ?>
	</section><!-- end id="front-latest-products" -->
<?php lt_get_footer(); ?>