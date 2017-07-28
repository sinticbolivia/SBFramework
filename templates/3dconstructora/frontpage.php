<?php 
$items = LT_HelperContent::GetArticles(array('type' => 'page', 
												'rows_per_page' => 3, 
												'meta' => array(
													array('meta_key' => '_in_frontpage', 'meta_value' => '1')
												)));
lt_get_header(); 
?>
	<section class="front-section fondo-1">
		<div class="front-section-content">
			<div class="container">
				<?php foreach($items['articles'] as $item): ?>
				<div class="row">
					<div class="col-xs-12 col-sm-6 col-md-6">
						<img src="<?php print $item->GetThumbnailUrl('full'); ?>" alt="<?php print $item->TheTitle(); ?>" class="img-resp" />
					</div>
					<div class="col-xs-12 col-sm-6 col-md-6">
						<div class="slide-text">
							<?php print $item->TheContent(); ?>
						</div>
					</div>
				</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section><!-- end class="front-section" -->
	<section class="front-section fondo-2">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<h2 class="title">Creamos tu Espacio</h2>
					<div class="subtitle">Obras que perduran</div>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12 col-sm-4 col-md-4">
					<div class="frontbox">
						<figure class="image"></figure>
						<h4 class="title">Pre-Construccion</h4>
						<div class="text">
							Nuestro equipo realiza un exhaustivo estudio para entregar una propuesta competitiva a 
							nivel mercado pero sobretodo, aterrizada y honesta tomando en cuenta la realidad de la 
							industria y sus vaivenes. Este sin duda es uno de los aspectos mas relevantes que nos ha 
							permitido ganar la confianza de nuestros clientes.
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-4 col-md-4">
					<div class="frontbox">
						<figure class="image"></figure>
						<h4 class="title">Fase de Construccion</h4>
						<div class="text">
							Velamos por la modelacion de coordinacion del proyecto para optimizar los trazados y 
							solucionar a tiempo conflictos e interferencias en el proyecto mediante la tecnologia
							BIM (Building Information Modeling), lo que nos permite disminuir costos por imprevistos,
							mayor certeza en los plazos y una mejor relacion contractual.
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-4 col-md-4">
					<div class="frontbox">
						<figure class="image"></figure>
						<h4 class="title">Post-Construccion</h4>
						<div class="text">
							Nuestro equipo realiza un exhaustivo estudio para entregar una propuesta competitiva a 
							nivel mercado pero sobretodo, aterrizada y honesta tomando en cuenta la realidad de la 
							industria y sus vaivenes. Este sin duda es uno de los aspectos mas relevantes que nos ha 
							permitido ganar la confianza de nuestros clientes.
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
<?php lt_get_footer(); ?>