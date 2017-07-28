<?php
/**
Template: Contacto
Fields: direccion,telefono,latitud,longitud,api_key
*/
	lt_get_header(); ?>
	<div id="content-wrap" class="row">
		<div class="">
			<div id="content">
				<div id="contact-content">
					<div class="map-container">
						<div class="map"  
							data-address="<?php print $article->_direccion ?>"
							data-lat="<?php print $article->_latitud ?>"
							data-lng="<?php print $article->_langitud ?>"
							data-apikey="<?php print $article->_api_key ?>"></div>
					</div>
					<div id="form-wrap">
						<?php print $article->TheContent(); ?>
						<form action="" method="post">
							<input type="hidden" name="task" value="send_contact" />
							<input type="hidden" name="page_id" value="<?php print $article->content_id; ?>" />
							<h3>Contacto <span>Rapido</span></h3>
							<p>
							Si tiene una inquietud sobre nuestros productos o servicios, por favor, no dude en llamar o
							escribirnos.
							</p>
							<div class="form-horizontal">
								<div class="form-group">
									<label class="col-sm-4"><?php _e('Nombre', 'elite'); ?></label>
									<div class="col-sm-8">
										<input type="text" name="nombre" value="" class="form-control" required />
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4"><?php _e('Apellido', 'elite'); ?></label>
									<div class="col-sm-8">
										<input type="text" name="apellido" value="" class="form-control" required />
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4"><?php _e('Email', 'elite'); ?></label>
									<div class="col-sm-8">
										<input type="email" name="email" value="" class="form-control" required />
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4"><?php _e('Mensaje', 'elite'); ?></label>
								<textarea name="mensaje" class="form-control" required></textarea>
							</div>
							<div class="form-group">
								<button type="submit" class="btn btn-red"><?php _e('Enviar', 'elite'); ?></button>
							</div>
						</form>
					</div><!-- end id="form-wrap" -->
				</div>
			</div>
		</div>
	</div><!-- end id="content-wrap" -->
<?php lt_get_footer(); ?>