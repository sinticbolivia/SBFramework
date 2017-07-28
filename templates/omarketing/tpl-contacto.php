<?php
/**
 * Template: Contacto
 * Fields: telefono,celular,email,direccion,latitud,longitud,api_key
 */
lt_get_header();
sb_add_script("//maps.google.com/maps/api/js?language=es&key={$article->_api_key}", 'gmap', 0, true);
?>
<div id="content-wrap" class="row">
	<?php /*
	<div id="page-banner" class="">
		<img src="<?php print TEMPLATE_URL; ?>/images/banner01.jpg" alt="" />
	</div>
	*/?>
	<div  class="col-md-12">
		<div class="row">
			<div id="content" class="col-md-12">
				<h1 id="content-title"><?php print $article->TheTitle(); ?></h1>
				<section id="the-content">
					<div class="row">
						<div class="col-xs-12 col-sm-6 col-md-7">
							<?php print $article->TheContent(); ?>
							<div class="gmap-container">
								<div id="map1" class="gmap">&nbsp;</div>
							</div>
							<script>
							var map_location = {
								lat: <?php print $article->_latitud ? $article->_latitud : 0;?>,
								lng: <?php print $article->_longitud ? $article->_longitud : 0; ?>,
								address: '<?php print $article->_direccion; ?>'
							};
							</script>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-5">
							<form id="form-contacto" action="" method="post" class="form-horizontal">
								<input type="hidden" name="task" value="send_contact" />
								<input type="hidden" name="ajax" value="1" />
								<input type="hidden" name="id" value="<?php print $article->content_id; ?>" />
								<h2><?php _e('Formulario de Contacto', 'om'); ?></h2>
								<p>
									Envienos sus consultas o inquietudes y nos pondremos en contacto con usted.
								</p>
								<div class="form-group">
									<label class="col-sm-4"><?php _e('Nombre:'); ?></label>
									<div class="col-sm-8">
										<input type="text" name="firstname" value="" class="form-control" required />
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4"><?php _e('Apellido:'); ?></label>
									<div class="col-sm-8">
										<input type="text" name="lastname" value="" class="form-control" required />
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4"><?php _e('Email:'); ?></label>
									<div class="col-sm-8">
										<input type="email" name="email" value="" class="form-control" required />
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-12"><?php _e('Mensaje:'); ?></label>
									<div class="col-sm-12">
										<textarea name="message" class="form-control"></textarea>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-12">
										<button type="submit" class="btn btn-primary"><?php _e('Enviar', 'om'); ?></button>
									</div>
								</div>
							</form>
							<div id="form-mensaje" class="alert fade out"></div>
						</div>
					</div>
					
				</section><!-- end id="the-content" -->
			</div><!-- end id="content" -->
		</div>
	</div>
</div><!-- end id="content-wrap" -->
<?php
lt_get_footer();
?>