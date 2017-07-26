<?php
/**
 * Template: Contacto
 * Fields: telephone,address,mail,latitud,longitud
 */
 lt_get_header();
?>
	<div ><?php SB_MessagesStack::ShowMessages(); ?></div>
	<div id="content">
		<div class="container">
			<?php sb_show_module(); ?>
			<div class="row">
				<div class="col-sm-12 col-md-6">
					<form action="" method="post">
						<input type="hidden" name="task" value="qa_contacto" />
						<div class="form-group">
							<label>Tu Nombre</label>
							<input type="text" name="nombre" value="" class="form-control" />
						</div>
						<div class="form-group">
							<label>Tu Telefono</label>
							<input type="text" name="telefono" value="" class="form-control" />
						</div>
						<div class="form-group">
							<label>Tu Email</label>
							<input type="email" name="email" value="" class="form-control" required />
						</div>
						<div class="form-group">
							<label>Tu Mensaje</label>
							<textarea name="mensaje" class="form-control"></textarea>
						</div>
						<div class="form-group">
							<button type="submit" class="btn btn-default btn-red">Enviar</button>
						</div>
					</form>
				</div>
				<div class="col-sm-12 col-md-6">
					<script type="text/javascript" src="http://maps.google.com/maps/api/js?language=es&key=AIzaSyBahGrq4pSwKoMfUWHo5e0ng0s1sEAk_Gw"></script>
					<script>
					var map_location = {lat:-16.502886,lng:-68.167314,address: 'Z/ Ferropetrol, Av. del Arquitecto esq. Calle 3 Nro. 32, El Alto, La Paz, Bolivia'};
					</script>
					<div id="map" data-stellar-background-ratio=".3" style="width:100%;height:400px;"></div>
					<br/>
					<p>
						Z/ Ferropetrol, Av. del Arquitecto esq. Calle 3 Nro. 32, El Alto, La Paz, Bolivia<br/>
						Telefonos: 71540408 - 71540405<br/>
						Email: <a href="mailto:contacto@consultoraquispeayllon.com">contacto@consultoraquispeayllon.com</a><br/>
					</p>
				</div>
			</div>
		</div>
	</div>
<?php lt_get_footer(); ?>