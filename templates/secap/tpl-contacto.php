<?php 
/**
* Template: Contacto
* Fields: telephone,address,mail,latitud,longitud,api_key
*/
sb_add_script("//maps.google.com/maps/api/js?language=es&key={$article->_api_key}", 'gmap', 0, true);
//sb_add_script("//maps.google.com/maps/api/js?language=es", 'gmap', 0, true);
sb_add_js_global_var(null, 'template_url', TEMPLATE_URL);
lt_get_header(); 
?>
<div id="wrap-content" class="row">
	<div id="content" class="">
		<h1 id="content-title">Contactenos <span class="icon icon-calc"></span></h1>
		<div id="content-body">
			<div class="container">
				<?php SB_MessagesStack::ShowMessages(); ?>
				<div class="row">
					<div id="content-text" class="col-xs-12 col-sm-8 col-md-8">
						<?php print $article->TheContent(); ?>
						<div class="gmap-container">
							<div id="map1" class="gmap">&nbsp;</div>
							<?php if( $article->_latitud && $article->_longitud ): ?>
							<script>
							var map_location = {
								lat: <?php print $article->_latitud ? $article->_latitud : 0;?>,
								lng: <?php print $article->_longitud ? $article->_longitud : 0; ?>,
								address: '<?php print $article->_address; ?>'
							};
							</script>
							<?php endif; ?>
						</div>
					</div>
					<div id="" class="col-xs-12 col-sm-4 col-md-4">
						<div id="form-contact-wrap">
							<h2>Contacto rapido</h2>
							<form id="form-contact" action="" method="post" >
								<input type="hidden" name="task" value="contacto" />
								<input type="hidden" name="page_id" value="<?php print $article->content_id; ?>" />
								<p>
								Si tienes una inquietud, consulta o duda sobre nuestros servicios, por favor no dude en 
								llamar o escribirnos.
								</p>
								<div class="form-horizontal">
									<div class="form-group">
										<label class="control-label col-sm-3">Nombre:</label>
										<div class="col-sm-9">
											<input type="text" name="firstname" value="" class="form-control" required />
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-sm-3">Apellido:</label>
										<div class="col-sm-9">
											<input type="text" name="lastname" value="" class="form-control" required />
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-sm-3">Email:</label>
										<div class="col-sm-9">
											<input type="email" name="email" value="" class="form-control" required />
										</div>
									</div>
								</div>
								<div class="form-group">
									<label>Su requerimiento:</label>
									<textarea class="form-control" name="message"></textarea>
								</div>
								<div class="form-group">
									<button type="submit" id="btn-send-contact" class="btn btn-submit">Enviar</button>
								</div>
							</form>
						</div>
						
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php lt_get_footer(); ?>