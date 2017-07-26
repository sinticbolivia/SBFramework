<?php
/**
* Template: Contacto
* Fields: telephone,address1,address2,mail,latitud1,longitud1,latitud2,longitud2,api_key
*/
sb_add_script('//maps.googleapis.com/maps/api/js?key='.$article->_api_key.'&callback=initMap', 'gmap');
lt_get_header();
?>
<div id="content-wrap" class="container-fluid">
	<div class="row">
		<div class="col-xs-12 col-sm-9 col-md-9">
			<div id="content" class="page-content contacto-content">
				<h1 id="page-title"><?php print $article->TheTitle(); ?></h1>
				<?php print $article->TheContent(); ?>
				<div class="map-container">
					<div id="map1" class="map" data-apikey="<?php print $article->_api_key ?>"
						data-address="<?php print $article->_address1 ?>"
						data-lat="<?php print $article->_latitud1 ?>"
						data-lng="<?php print $article->_longitud1 ?>"></div>
				</div>
				<div class="map-container">
					<div id="map2" class="map" data-apikey="<?php print $article->_api_key ?>"
						data-address="<?php print $article->_address1 ?>"
						data-lat="<?php print $article->_latitud1 ?>"
						data-lng="<?php print $article->_longitud1 ?>"></div>
				</div>
			</div><!-- end id="content" -->
		</div>
		<div class="col-xs-12 col-sm-3 col-md-3">
			<form id="form-contact" action="" method="post">
				<h2>Contacto <span>rapido</span></h2>
				<p>
					Tienes alguna consulta?<br/>
					Envianos tu consulta utilizando el siguiente formularios.<br/>
				</p>
				<div class="form-horizontal">
					<div class="form-group">
						<label class="control-label col-sm-3">Nombres</label>
						<div class="col-sm-9">
							<input type="text" name="firstname" value="" class="form-control" required />
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-3">Apellido</label>
						<div class="col-sm-9">
							<input type="text" name="lastname" value="" class="form-control" required />
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-3">Email</label>
						<div class="col-sm-9">
							<input type="email" name="email" value="" class="form-control" required />
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label">Su Consulta</label>
					<textarea name="message" class="form-control" required></textarea>
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-primary">Enviar</button>
				</div>
			</form>
		</div>
	</div>
</div><!-- end id="content-wrap" -->
<?php lt_get_footer(); ?>