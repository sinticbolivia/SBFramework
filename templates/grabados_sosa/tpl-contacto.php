<?php
/**
* Template: Contacto
* Fields: telephone1,address1,mail1,latitud1,longitud1,telephone2,address2,mail2,latitud2,longitud2,telephone3,address3,mail3,latitud3,longitud3,api_key
*/
// AIzaSyBLmebuLPHeVv8wDHApy_Q6uLRbtypkS0c 
sb_add_script("//maps.google.com/maps/api/js?language=es&key={$article->_api_key}", 'gmap', 0, true);
lt_get_header();
?>
	<div id="content">
		<div class="container">
			<div class="row"><h1 id="page-title"><?php print $article->TheTitle(); ?></h1></div>
			<div class="row">
				<div class="col-md-8">
					<script>
					var map_location = {
						lat: <?php print $article->_latitud1 ? $article->_latitud1 : 0;?>,
						lng: <?php print $article->_longitud1 ? $article->_longitud1 : 0; ?>,
						address: '<?php print $article->_address1; ?>'
					};
					var map_location2 = {
						lat:<?php print $article->_latitud2 ? $article->_latitud2 : 0;?>,
						lng:<?php print $article->_longitud2 ? $article->_longitud2 : 0; ?>,
						address: '<?php print $article->_address2; ?>'
					};
					var map_location3 = {
						lat:<?php print $article->_latitud3 ? $article->_latitud3 : 0;?>,
						lng:<?php print $article->_longitud3 ? $article->_longitud3 : 0; ?>,
						address: '<?php print $article->_address3; ?>'
					};
					</script>
					<div><?php SB_MessagesStack::ShowMessages(); ?></div>
					<div class="content-text">
						<?php print $article->TheContent(); ?>
					</div>
				</div>
				<div class="col-md-4">
					<form id="form-contact" action="" method="post">
						<input type="hidden" name="task" value="contact_form" />
						<h3 class="title"><?php _e('Contacto Rapido', 'gs'); ?></h3>
						<p>
							Si tiene alguna consulta o inquietud sobre nuestros servicios, porfavor no dude en 
							contactarnos.
						</p>
						<div class="form-horizontal">
							<div class="form-group">
								<label class="control-label col-sm-3"><?php _e('Nombre', 'gs'); ?></label>
								<div class="col-sm-8">
									<input type="text" name="firstname" value="" class="form-control" required />
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-3"><?php _e('Apellido', 'gs'); ?></label>
								<div class="col-sm-8">
									<input type="text" name="lastname" value="" class="form-control" required />
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-3"><?php _e('Email', 'gs'); ?></label>
								<div class="col-sm-8">
									<input type="email" name="email" value="" class="form-control" required />
								</div>
							</div>
						</div>
						<div class="form-group">
							<label><?php _e('Mensaje', 'gs'); ?></label>
							<textarea name="message" class="form-control"></textarea>
						</div>
						<div class="form-group">
							<button type="submit" id="btn-send-contact" class="btn btn-primary"><?php _e('Enviar', 'gs'); ?></button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
<?php lt_get_footer(); ?>
