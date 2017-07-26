<?php
$about_us = LT_HelperContent::GetPageBySlug('acerca-de-nosotros');
?>
	<div class="purchase">
		<div class="container content">
			<div class="row">
				<p>&quot;Para saber como protegerte llamanos al 800-10-9119&quot;</p>
			</div>
		</div>
	</div>
	<!-- Footer -->
	<div class="footer">
		<div class="container">
			<div class="row">
				<div class="col-md-4">
					<h6>Nuestra <span style="font-weight:bold;">Calificaci&oacute;n de Riesgo</span></h6>
					<div style="text-align:center;width:62%;">
						<p style="font-weight:bold;font-size:30px;">AA<span style="font-size:18px;">3</span></p>
						<p >Otorgada por <span style="font-weight:bold;">Moody's LA</span></p>
					</div>
				</div>
				<?php /*
				<div class="col-md-4 blog">
					<h6>Este operador esta bajo la fiscalización y control de la Autoridad de Fiscalización y Control de Pensiones y Seguros -APS</h6>
					<img src="<?php print TEMPLATE_URL; ?>/images/logo-aps.jpg" alt="" style="max-width:100%;" />
				</div>
				*/?>
				<div class="col-md-4">
					<h6><?php _e('P&aacute;ginas', 'uv'); ?></h6>
					<?php if( !lt_show_content_menu('menu_pie_'.LANGUAGE) ): ?>
					<ul>
						<li><a href="index.php" title="">Inicio</a></li>
						<li>
							<a href="<?php $page = LT_HelperContent::GetPageBySlug('acerca-de-nosotros');print $page->link; ?>" title="">Empresa</a>
						</li>
						<li><a href="#" title="">Servicios</a></li>
						<li><a href="#" title="">Preguntas Frecuentes</a></li>
						<li><a href="#" title="">Contacto</a></li>
					</ul>
					<?php endif; ?>
				</div>
				<div class="col-md-4 contact-info">
					<h6><?php _e('Contacto', 'uv'); ?></h6>
					<p>Calle Loayza Nro. 255, Edificio De Ugarte Ingenieria, Piso 10, of. 1001, entre Camacho y Mercado.</p>
					<?php /*
					<p class="social">
						<a href="#" class="facebook"></a> <a href="#" class="pinterest"></a> <a href="#" class="twitter"></a>
					</p>
					*/?>
					<p class="c-details">
						<span>Email:</span> <a href="mailto:info@univida.bo" title="">info@univida.bo</a><br >
						<span>Telf:</span> ++591-2-2151000
					</p>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12 text-center">
					<h6>Este operador esta bajo la fiscalización y control de la Autoridad de Fiscalización y Control de Pensiones y Seguros -APS</h6>
				</div>
				<div class="col-md-12 copyright">
					<p class="pull-left">&copy; <a href="http://sinticbolivia.net" target="_blank">Sintic Bolivia</a> <?php print date('Y')?>. Todos los derechos reservados.</p>
					<p class="pull-right"><a href="<?php print SB_Route::_('index.php'); ?>" title="Univida Template"><?php print SITE_TITLE; ?></a></p>
				</div>
			</div>
		</div>
	</div>
	<!-- Footer end -->	
	<!-- Javascript plugins -->
	<script src="<?php print TEMPLATE_URL; ?>/js/carouFredSel.js"></script>
	<script src="<?php print TEMPLATE_URL; ?>/js/jquery.stellar.min.js"></script>
	<script src="<?php print TEMPLATE_URL; ?>/js/ekkoLightbox.js"></script>
	<script src="<?php print TEMPLATE_URL; ?>/js/custom.js"></script>
</body>
</html>