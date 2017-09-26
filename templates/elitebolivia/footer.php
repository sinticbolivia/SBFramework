<?php
$call = isMobile() ? 'callto' : 'skype'; 
?>
	<footer id="footer">
		<div class="container-fluid">
			<div class="row">
				<div class="col-xs-12 col-sm-4 col-md-4">
					<div class="widget">
						<h2 class="title">Contactenos</h2>
						<div class="body">
							<div class="phone">Telefono: <a href="<?php print $call; ?>://76155082">76155082</a></div>
							<div class="mobile">Celular: <a href="<?php print $call; ?>://75427047">75427047</a></div>
							<address>S. Galvarro, Bolivar y Sucre Ed. Ismael oF 6, Oruro, Bolivia</address>
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-4 col-md-4">
					<div class="widget">
						<h2 class="title text-center">Siguenos</h2>
						<div class="body">
							<div class="social-icons text-center">
								<a href="https://www.facebook.com/eliteoruro" target="_blank" class="icon">
									<img src="<?php print TEMPLATE_URL; ?>/images/icons/FACEBOOK.png" alt="" />
								</a>
								<a href="#" class="icon"><img src="<?php print TEMPLATE_URL; ?>/images/icons/TWEETER.png" alt="" /></a>
								<a href="#" class="icon"><img src="<?php print TEMPLATE_URL; ?>/images/icons/INSTAGRAM.png" alt="" /></a>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-4 col-md-4">
					<div class="widget">
						<h2 class="title">Lista de Correo</h2>
						<div class="body">
							<p>Ingresa tu correo para obtener nuestras noticias y novedades.</p>
							<form action="" method="">
								<input type="email" name="email" value="" placeholder="Email" class="form-control" />
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="copyright">
			&copy;Derechos Reservados, Dise&ntilde;o y Desarrollo por <a href="http://sinticbolivia.net" target="_blank">Sintic Bolivia</a>
		</div>
	</footer><!-- end id="footer" -->
</div><!-- end id="container" -->
<?php lt_footer(); ?>
<!-- Go to www.addthis.com/dashboard to customize your tools -->
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-591df0c086ddb511"></script>
</body>
</html>