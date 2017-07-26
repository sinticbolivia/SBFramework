	<footer id="footer" class="row">
		<div class="container-fluid">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-4">
					<div class="widget">
						<h2 class="title"><?php _e('Cont&aacute;ctenos', 'om'); ?></h2>
						<div class="body">
							Direccion: <address>Junín 411, ex peatonal. Galería Guadalupe Amb. 37, Sucre, Bolivia</address><br/>
							Telefono: (+591) 72883156<br/>
							Email: contacto@omarketing.com.bo<br/>
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-4">
					<div class="widget">
						<h2 class="title text-center"><?php _e('Siguenos', 'om'); ?></h2>
						<div class="body">
							<div id="social-links">
								<a href="https://www.facebook.com/omarketing/" target="_blank" class="social">
									<img src="<?php print TEMPLATE_URL; ?>/images/social/facebook.png" alt="" />
								</a>
								<a href="https://twitter.com/omarketing__" target="_blank" class="social">
									<img src="<?php print TEMPLATE_URL; ?>/images/social/tweeter.png" alt="" />
								</a>
								<a href="https://www.youtube.com/channel/UCh-P-wv9uikjHztV9_eWTYA" target="_blank" class="social">
									<img src="<?php print TEMPLATE_URL; ?>/images/social/youtube.png" alt="" />
								</a>
								<a href="https://www.instagram.com/omarketing_/" target="_blank" class="social">
									<img src="<?php print TEMPLATE_URL; ?>/images/social/instagram.png" alt="" />
								</a>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-4">
					<div class="widget">
						<h2 class="title text-right"><?php _e('Suscribete a nuestro boletin', 'om'); ?></h2>
						<div class="body text-right">
							<p>
								Dejanos tu direccion de correo y recibiras todas nuestras novedades en información y 
								cursos.
							</p>
							<form action="" method="post" class="form-newsletter">
								<input type="hidden" name="mod" value="newsletter" />
								<input type="hidden" name="task" value="subscribe" />
								<input type="hidden" name="list_id" value="1" />
								<input type="hidden" name="ajax" value="1" />
								<input type="hidden" name="source" value="website" />
								<div class="form-group">
									<input type="email" name="email" value="" class="form-control" required
										placeholder="<?php _e('Tu email', 'om'); ?>" />
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div id="copyright">
						Todos los derechos reservados, Diseño y Desarrollo por 
						<a href="http://sinticbolivia.net" target="_blank">Sintic Bolivia</a>
					</div>
				</div>
			</div>
		</div>
	</footer><!-- end id="footer" -->
</div><!-- id="container" -->
<div id="newsletter-popup" class="modal fade">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Suscribite a nuestro boletin electronico</h4>
			</div>
			<div class="modal-body">
				<p>Mantente al dia con todas las novedades y noticias de nuestro sitio web.</p>
				<div class="row">
					<div class="col-xs-12 col-sm-6 col-md-6">
						<img src="<?php print TEMPLATE_URL; ?>/images/e-newsletter.jpg" class="img-responsive" />
					</div>
					<div class="col-xs-12 col-sm-6 col-md-6">
						<form action="" method="post" class="form-newsletter">
							<input type="hidden" name="mod" value="newsletter" />
							<input type="hidden" name="task" value="subscribe" />
							<input type="hidden" name="list_id" value="1" />
							<input type="hidden" name="ajax" value="1" />
							<input type="hidden" name="source" value="website" />
							<div class="form-group">
								<label>Nombre</label>
								<input type="text" name="name" value="" class="form-control" />
							</div>
							<div class="form-group">
								<label>Email</label>
								<input type="email" name="email" value="" class="form-control" />
							</div>
							<div class="form-group">
								<button type="submit" class="btn btn-primary btn-subscribe">
									<?php _e('Suscribirme', 'om'); ?>
								</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>
<script>
jQuery(function()
{
	jQuery('#newsletter-popup').modal('show');
});
</script>
<?php lt_footer(); ?>
<!-- Go to www.addthis.com/dashboard to customize your tools -->
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-594462daccaa5ae2"></script>
</body>
</html>