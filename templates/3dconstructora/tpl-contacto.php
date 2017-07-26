<?php
/**
 * Template: Contacto 
 * Fields: telephone,address,mail,latitud,longitud,api_key
 */
lt_get_header();
sb_add_script("//maps.google.com/maps/api/js?language=es&key={$article->_api_key}", 'gmap', 0, true);
?>
<div id="content">
	<div class="map-container">
		<div id="map" class="map"></div>
	</div>
	<div id="form-contact-wrap">
		<form id="form-contact" action="" method="post">
			<input type="hidden" name="ajax" value="1" />
			<input type="hidden" name="task" value="send_contact" />
			<input type="hidden" name="page_id" value="<?php print $article->content_id; ?>" />
			<h2><?php _e('Contacto Rapido', '3dc'); ?></h2>
			<div class="form-horizontal">
				<div class="form-group">
					<label class="control-label col-sm-4"><?php _e('Nombre', '3dc'); ?></label>
					<div class="col-sm-8">
						<input type="text" name="nombres" value="" class="form-control" required />
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-4"><?php _e('Apellido', '3dc'); ?></label>
					<div class="col-sm-8">
						<input type="text" name="apellidos" value="" class="form-control" required />
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-4"><?php _e('Email', '3dc'); ?></label>
					<div class="col-sm-8">
						<input type="email" name="email" value="" class="form-control" required />
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label"><?php _e('Mensaje', '3dc'); ?></label>
				<textarea name="mensaje" class="form-control" required></textarea>
			</div>
			<div class="form-group">
				<button type="submit" class="btn btn-blue"><?php _e('Enviar', '3dc'); ?></button>
			</div>
		</form>
	</div><!-- end id="form-contact-wrap" -->
	<script>
	jQuery(function()
	{
		jQuery('#map').css('height', window.innerHeight + 'px');
		var map_location = {
			lat: <?php print $article->_latitud; ?>, 
			lng: <?php print $article->_longitud; ?>, 
			address: '<?php print $article->_address; ?>'
		};
		var map = new google.maps.Map(document.getElementById('map'), {
		    zoom: 16,
		    center: map_location
		});
		var marker = new google.maps.Marker({
		    position: map_location,
		    map: map,
		    title: '',
		    //icon: "templates/univida/images/marker-2.1.png"
		});
		var infowindow = new google.maps.InfoWindow({
		    content: map_location.address || 'No hay informacion disponible'
		});
		infowindow.open(map, marker);
		jQuery('#form-contact').submit(function()
		{
			var form	= this;
			var params = jQuery(this).serialize();
			var btn		= jQuery(this).find('button[type=submit]:first');
			btn.prop('disabled', true);
			btn.html('Enviando...');
			jQuery.post('index.php', params, function(res)
			{
				btn.prop('disabled', false);
				btn.html('Enviar');
				if( res.status && res.status == 'ok' )
				{
					alert(res.message);
					form.nombres.value = '';
					form.apellidos.value = '';
					form.email.value = '';
					form.mensaje.value = '';
				}
				else if( res.error )
				{
					alert(res.error);
				}
				else
				{
					alert('Ocurrio un error al tratar de enviar tu mensaje, porfavor intentalo mas tarde');
				}
			});
			return false;
		});
	});
	</script>
</div><!-- end id="content" -->
<?php
lt_get_footer();
?>