<?php lt_get_header(); lt_slider('slider_0'); ?>
	<?php /*
	<div id="frontpage-slider" class="row">
		<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
			<!-- Indicators -->
			<ol class="carousel-indicators">
				<li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
				<li data-target="#carousel-example-generic" data-slide-to="1"></li>
				<li data-target="#carousel-example-generic" data-slide-to="2"></li>
			</ol>
			<!-- Wrapper for slides -->
			<div class="carousel-inner" role="listbox">
				<div class="item active">
					<img src="<?php print TEMPLATE_URL; ?>/images/banner1.jpg" alt="...">
					<div class="carousel-caption">
						<div class="content">
							<div class="title">Asesoria Contable</div>
							<div class="text">
								Al encargarnos de la contabilidad de la empresa, registramos toda la informaci&oacute;n
								con base en los documentos que nos suministren y confeccionamos los estados financieros
								que se requieran firm&aacute;ndolos como contadores.
							</div>
						</div><br/>
						<p class="text-center">
							<a href="" class="btn-readmore">Leer mas</a>
						</p>
					</div>
				</div>
				<div class="item">
					<img src="<?php print TEMPLATE_URL; ?>/images/banner2.jpg" alt="...">
					<div class="carousel-caption">
					...
					</div>
				</div>
				<div class="item">
					<img src="<?php print TEMPLATE_URL; ?>/images/banner3.jpg" alt="...">
					<div class="carousel-caption">
					...
					</div>
				</div>
			</div>
			<!-- Controls -->
			<a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
				<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
				<span class="sr-only">Previous</span>
			</a>
			<a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
				<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
				<span class="sr-only">Next</span>
			</a>
		</div>
	</div><!-- end id="frontpage-slider" -->
	*/?>
	<div id="frontpage-services" class="row">
		<div class="col-md-12">
			<h2 id="services-title"><span>Nuestros Servicios</span></h2>
		</div>
		<div class="col-xs-12 col-sm-6 col-md-3">
			<a href="index.php?mod=content&view=article&id=1&slug=asesoria-contable" class="service">
				<span class="image"><img src="<?php print TEMPLATE_URL; ?>/images/logo1.png" alt="" /></span>
				<span class="title">Asesoria Contable</span>
			</a>
		</div>
		<div class="col-xs-12 col-sm-6 col-md-3">
			<a href="index.php?mod=content&view=article&id=2&slug=asesoria-laboral" class="service">
				<span class="image"><img src="<?php print TEMPLATE_URL; ?>/images/logo2.png" alt="" /></span>
				<span class="title">Asesoria Laboral</span>
			</a>
		</div>
		<div class="col-xs-12 col-sm-6 col-md-3">
			<a href="index.php?mod=content&view=article&id=7&slug=asesoria-financiera" class="service">
				<span class="image"><img src="<?php print TEMPLATE_URL; ?>/images/logo3.png" alt="" /></span>
				<span class="title">Asesoria Financiera</span>
			</a>
		</div>
		<div class="col-xs-12 col-sm-6 col-md-3">
			<a href="index.php?mod=content&view=article&id=3&slug=asesoria-tributaria" class="service">
				<span class="image"><img src="<?php print TEMPLATE_URL; ?>/images/logo4.png" alt="" /></span>
				<span class="title">Asesoria Tributaria</span>
			</a>
		</div>
	</div>
<?php lt_get_footer(); ?>