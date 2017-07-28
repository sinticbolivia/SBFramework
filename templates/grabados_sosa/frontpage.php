<?php lt_get_header(); ?>
	<h1 id="frontpage-title" class="text-center"><?php print SITE_TITLE; ?></h1>
	<div id="home-carrousel">
		<div class="container">
			<div id="home-carrousel-wrap">
				<div id="NAV-ID" class="crsl-nav">
					<a href="#" class="previous"><span class="glyphicon glyphicon-chevron-left"></span></a>
					<a href="#" class="next"><span class="glyphicon glyphicon-chevron-right"></span></a>
				</div>
				<div class="crsl-items" data-navigation="NAV-ID">
					<div class="crsl-wrap">
						<figure class="crsl-item">
							<img src="<?php print TEMPLATE_URL; ?>/images/carrusel/carrusel-1.png">
						</figure>
						<figure class="crsl-item">
							<img src="<?php print TEMPLATE_URL; ?>/images/carrusel/carrusel-2.png">
						</figure>
						<figure class="crsl-item">
							<img src="<?php print TEMPLATE_URL; ?>/images/carrusel/carrusel-3.png">
						</figure>
						<figure class="crsl-item">
							<img src="<?php print TEMPLATE_URL; ?>/images/carrusel/carrusel-4.png">
						</figure>
						<figure class="crsl-item">
							<img src="<?php print TEMPLATE_URL; ?>/images/carrusel/carrusel-5.png">
						</figure>
						<figure class="crsl-item">
							<img src="<?php print TEMPLATE_URL; ?>/images/carrusel/carrusel-6.png">
						</figure>
						<figure class="crsl-item">
							<img src="<?php print TEMPLATE_URL; ?>/images/carrusel/carrusel-7.png">
						</figure>
						<figure class="crsl-item">
							<img src="<?php print TEMPLATE_URL; ?>/images/carrusel/carrusel-8.png">
						</figure>
						<figure class="crsl-item">
							<img src="<?php print TEMPLATE_URL; ?>/images/carrusel/carrusel-9.png">
						</figure>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script src="<?php print TEMPLATE_URL; ?>/js/responsiveCarousel.min.js"></script>
	<script>
	jQuery(function()
	{
		jQuery('.crsl-items').carousel({
			overflow: false, 
			visible: 5, 
			itemMinWidth: 200, 
			itemMargin: 10,
			autoRotate: 3000
		});
	});
	</script>
	<div id="content">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="content-text">
						<p>GRABADOS SOSA fue fundado el año 2015 en la ciudad de Santa Cruz.</p>
						<p>
							La empresa se dedica a la fabricaci&oacute;n de cliches computarizados en bronce y
							aluminio para el rubro de la zapateria, marroquineria e imprenta.
						</p>
						<p>
							La fabrica de cliches se encuentra en la ciudad de cochabamba.
						</p>
						<p>
							Tambien fabricamos troqueles para zapateria, para la fabricaci&oacute;n de calzados, cinturones,
							guantes, billeteras, agendas, etc.
						</p>
						<p>
							La f&aacute;brica de troqueles se encuentra en la ciudad de Santa Cruz.
						</p>
					</div>
				</div>
			</div>
		</div>
	</div><!-- end id="content" -->
<?php lt_get_footer(); ?>