<?php
$tcr = TCR(); 
?>
	<footer id="footer" class="row">
		<div class="col-xs-12 col-sm-4 col-md-4">
			<div class="widget">
				<h2 class="title"><?php _e('Contactanos', 'cr'); ?></h2>
				<div class="body">
					<p>
						<b><?php _e('Nuestra Direccion'); ?></b><br/>
						<address><?php print $tcr->direccion ?></address>
					</p>
					<p>
						<b><?php _e('Nuestros Telefonos'); ?></b><br/>
						<?php print $tcr->telf ?>
					</p>
				</div>
			</div>
		</div>
		<div class="col-xs-12 col-sm-4 col-md-4">
		
		</div>
		<div class="col-xs-12 col-sm-4 col-md-4">
			<div class="widget">
				<h2 class="title"><?php _e('Boletin Electronico', 'cr'); ?></h2>
				<div class="body">
					<p><?php _e('Registrate para obtener nuestras noticias y novedades'); ?></p>
					<form action="" method="post" class="form-group-sm">
						<div class="form-horizontal">
							<div class="form-group">
								<label class="col-sm-3 control-label"><?php _e('Nombres'); ?></label>
								<div class="col-sm-9">
									<input type="text" name="name" value="" class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label"><?php _e('Apellidos'); ?></label>
								<div class="col-sm-9">
									<input type="text" name="name" value="" class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label"><?php _e('Email'); ?></label>
								<div class="col-sm-9">
									<input type="text" name="name" value="" class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label"><?php _e('Telefono'); ?></label>
								<div class="col-sm-9">
									<input type="text" name="name" value="" class="form-control" />
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="col-md-12">
			<div id="copyright" style="text-align:center;padding:5px 0;font-weight:bold;font-size:12px;">
				Dise&ntilde;o y Desarrollo por 
				&copy;<a href="http://sinticbolivia.net" target="_blank" style="color:inherit;" id="company">
						<span>Sintic Bolivia<span>
					</a> <?php print date('Y'); ?>, 
				Todos los derechos reservados</div>
		</div>
	</footer>
</div><!-- end id="container" -->
<?php lt_footer(); ?>
</body>
</html>