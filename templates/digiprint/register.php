<?php
?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title><?php _e('DigiPrint - Nueva Cuenta', 'digiprint'); ?></title>
	<link rel="stylesheet" href="<?php print BASEURL; ?>/js/bootstrap-3.3.5/css/bootstrap.min.css" />
	<link rel="stylesheet" href="<?php print BASEURL; ?>/js/bootstrap-datepicker-1.4.0/css/bootstrap-datepicker.min.css" />
	<link rel="stylesheet" href="<?php print TEMPLATE_URL; ?>/style.css" />
	<script src="<?php print BASEURL; ?>/js/jquery.min.js"></script>
	<script src="<?php print BASEURL; ?>/js/bootstrap-3.3.5/js/bootstrap.min.js"></script>
	<script src="<?php print BASEURL; ?>/js/bootstrap-datepicker-1.4.0/js/bootstrap-datepicker.min.js"></script>
	<script src="<?php print BASEURL; ?>/js/bootstrap-datepicker-1.4.0/locales/bootstrap-datepicker.es.min.js"></script>
	<?php lt_head(); ?>
	<script src="<?php print TEMPLATE_URL; ?>/js/common.js"></script>
	<style>
	h1{color:#000066;font-weight: bold;margin:0 0 8px 0;text-align:center;text-transform: uppercase;}
	#form-register{background:#fff;padding:15px 35px;border-radius:8px;border:1px solid #bcbcbc;margin:0 auto 80px auto;}
	.important{background:#b3b3b3;border-radius:8px;margin:0 -15px;padding:10px 17px;}
	.important label{color:#fff;}
	.important input{background:#e1e1e1;}
	section{margin:0 0 15px 0;}
	section .title{color:#c1272d;font-weight:bold;text-align:center;margin:0 0 8px 0;text-transform: uppercase;font-size:18px;border-bottom:2px #bcbcbc dashed;}
	.privaci-policy label{text-transform: none;}
	label.required:after{content:'*';}
	</style>
	<script>
	jQuery(function()
	{
		jQuery('#username').keyup(function()
		{
			jQuery('#email').val(this.value);
		});
	});
	</script>
</head>
<body <?php lt_body_class(); ?>>
<div id="cont" class="container">
	<p class="text-center"><img src="<?php print TEMPLATE_URL; ?>/images/logodigiprint_color.png" alt="" /></p>
	<div class="messages"><?php SB_MessagesStack::ShowMessages(); ?></div>
	<form id="form-register" action="" method="post">
		<input type="hidden" name="mod" value="users" />
		<input type="hidden" name="task" value="do_register" />
		<input type="hidden" name="digiprint" value="1" />
		<input type="hidden" id="email" name="email" value="<?php print SB_Request::getString('username'); ?>" />
		<h1><?php _e('Nueva Cuenta', 'digiprint'); ?></h1>
		<p style="color:#000;">Registrate para poder acceder a toda la informacion sobre tus pedidos y realizar nuevas compras</p>
		<section>
			<h2 class="title"><?php _e('Informacion Personal', 'digiprint'); ?></h2>
			<div class="row">
				<div class="col-xs-12 col-md-4">
					<div class="form-group">
						<label class="required"><?php _e('Nombre', 'digiprint'); ?></label>
						<input type="text" name="first_name" value="<?php print SB_Request::getString('first_name'); ?>" class="form-control required" />
					</div>
				</div>
				<div class="col-xs-12 col-md-4">
					<div class="form-group">
						<label class="required"><?php _e('Apellidos', 'digiprint'); ?></label>
						<input type="text" name="last_name" value="<?php print SB_Request::getString('last_name'); ?>" class="form-control required" />
					</div>
				</div>
				<div class="col-xs-12 col-md-4">
					<div class="form-group">
						<label><?php _e('Tipo de Cliente'); ?></label>
						<input type="text" name="customer_type" class="form-control" />
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12 col-md-4">
					<div class="form-group">
						<label><?php _e('Telefono de Oficina'); ?></label>
						<input type="text" name="meta[_office_telephone]" value="<?php print SB_Request::getArrayVar('meta', '_office_telephone'); ?>" class="form-control" />
					</div>
				</div>
				<div class="col-xs-12 col-md-4">
					<div class="form-group">
						<label><?php _e('Telefono Movil', 'digiprint'); ?></label>
						<input type="text" name="mobile" value="<?php print SB_Request::getString('mobile'); ?>" class="form-control" />
					</div>
				</div>
				<div class="col-xs-12 col-md-4">
					<div class="form-group">
						<label class="required"><?php _e('Telefono', 'digiprint'); ?></label>
						<input type="text" name="phone" value="<?php print SB_Request::getString('phone'); ?>" class="form-control required" />
					</div>
				</div>
			</div>
			<div class="important">
				<div class="row">
					<div class="col-xs-12 col-md-4">
						<div class="form-group">
							<label class="required"><?php _e('Corre Electronico'); ?></label>
							<input type="text" id="username" name="username" value="<?php print SB_Request::getString('username'); ?>" class="form-control required" />
						</div>
					</div>
					<div class="col-xs-12 col-md-4">
						<div class="form-group">
							<label class="required"><?php _e('Contrase&ntilde;a', 'digiprint'); ?></label>
							<input type="password" name="pwd" class="form-control required" />
						</div>
					</div>
					<div class="col-xs-12 col-md-4">
						<div class="form-group">
							<label class="required"><?php _e('Confirmar Contrase&ntilde;a', 'digiprint'); ?></label>
							<input type="password" name="rpwd" class="form-control required" />
						</div>
					</div>
				</div>
			</div>
		</section>
		<section>
			<h2 class="title"><?php _e('Informacion de Negocio', 'digiprint'); ?></h2>
			<div class="row">
				<div class="col-xs-12 col-md-4">
					<div class="form-group">
						<label><?php _e('Razon Social / Nombre de negocio', 'digiprint'); ?></label>
						<input type="text" name="company" value="<?php print SB_Request::getString('company'); ?>" class="form-control" />
					</div>
				</div>
				<div class="col-xs-12 col-md-4">
					<div class="form-group">
						<label><?php _e('Ciudad', 'digiprint'); ?></label>
						<input type="text" name="city" value="<?php print SB_Request::getString('city'); ?>" class="form-control" />
					</div>
				</div>
				<div class="col-xs-12 col-md-4">
					<div class="form-group">
						<label><?php _e('Estado'); ?></label>
						<input type="text" name="state" class="form-control" />
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12 col-md-4">
					<div class="form-group">
						<label><?php _e('RFC'); ?></label>
						<input type="text" name="meta[_nit_ruc_nif]" value="<?php print SB_Request::getArrayVar('meta', '_nit_ruc_nif'); ?>" class="form-control" />
					</div>
				</div>
				<div class="col-xs-12 col-md-4">
					<div class="form-group">
						<label><?php _e('Calle', 'digiprint'); ?></label>
						<input type="text" name="address_1" value="<?php print SB_Request::getString('address_1'); ?>" class="form-control" />
					</div>
				</div>
				<div class="col-xs-12 col-md-4">
					<div class="form-group">
						<label><?php _e('Estado', 'digiprint'); ?></label>
						<input type="text" name="meta[_state_2]" value="<?php print SB_Request::getArrayVar('meta', '_state_2'); ?>" class="form-control" />
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12 col-md-4">
					<div class="form-group">
						<label><?php _e('Telefono de Oficina'); ?></label>
						<input type="text" name="meta[_office_telephone]" value="<?php print SB_Request::getArrayVar('meta', '_office_telephone'); ?>" class="form-control" />
					</div>
				</div>
				<div class="col-xs-12 col-md-4">
					<div class="form-group">
						<label><?php _e('No. Exterior + Interior', 'digiprint'); ?></label>
						<input type="text" name="meta[_additional_phone]" value="<?php print SB_Request::getArrayVar('meta', '_additional_phone'); ?>" class="form-control" />
					</div>
				</div>
				<div class="col-xs-12 col-md-4">
					<div class="form-group">
						<label><?php _e('Codigo Postal', 'digiprint'); ?></label>
						<input type="text" name="zip_code" value="<?php print SB_Request::getString('zip_code'); ?>" class="form-control" />
					</div>
				</div>
			</div>
		</section>
		<section>
			<div class="row">
				<div class="col-xs-12 col-md-6">
					<span class="label label-default">INFO:</span>
					La informacion de su NEGOCIO es obligatoria si desea realizar pagos o pedir facturas por medio de este sistema.
				</div>
				<div class="col-md-2"></div>
				<div class="col-xs-12 col-md-4">
					<div class="privaci-policy text-right">
						<label>
							Acepto que he leido nuestra potilica de privacidad y estoy en total acuerdo
							<input type="checkbox" name="i_agree" value="1" />
						</label>
					</div>
				</div>
			</div>
		</section>
		<div class="form-group text-center">
			<button class="btn btn-danger" type="submit"><?php _e('Registrarme Ahora', 'digiprint'); ?></button>
		</div>
	</form>
</div>
</body>
</html>