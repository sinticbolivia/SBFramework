<?php
/**
* Template: Contacto
* Fields: telephone,address,mail,latitud,longitud,api_key
*/
lt_get_header();
sb_add_script("//maps.google.com/maps/api/js?language=es&key={$article->_api_key}", 'gmap', 0, true);
sb_add_js_global_var(null, 'template_url', TEMPLATE_URL);
?>
<div id="content" class="">
	<?php SB_MessagesStack::ShowMessages(); ?>
	<div class="container-fluid">
		<div class="row"><h1 id="page-title"><?php print $article->TheTitle(); ?></h1></div>
		<div class="row">
			<div id="contact-content">
				<div class="map-container">
					<div id="map1" style="width:100%;height:400px;"></div>
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
				</div>
			</div>
		</div>
	</div>
</div><!-- end id="content" -->
<script>
jQuery(function()
{
	var map_location = {
		lat: <?php print $article->_latitud ?>, 
		lng: <?php print $article->_longitud ?>, 
		address:'<?php print $article->_address ?>'
	};
	var map = new google.maps.Map(document.getElementById('map1'), {
		zoom: 16,
		center: map_location
	});
	var marker = new google.maps.Marker({
		position: map_location,
		map: map,
		title: '<?php print SITE_TITLE; ?>',
		//icon: lt.template_url + "/images/marker.png"
	});
	var infowindow = new google.maps.InfoWindow({
		content: map_location.address
	});
	infowindow.open(map, marker);
});
</script>
<?php lt_get_footer(); ?>
