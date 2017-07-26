<?php
/**
 * Template: Login
 */
lt_get_header();
?>
<div class="container">
	<div class="row">
		<div class="col-md-12 centered">
			<?php SB_MessagesStack::ShowMessages(); ?>
			<h3><span><?php _e('Ingreso', 'uv'); ?></span></h3>
		</div>
	</div>
</div>
<div class="container content">
	<div id="login-container" class="row" style="">
		<div class="col-md-4">
			<h4><?php _e('Formulario de Acceso', 'uv'); ?></h4>
			<form action="<?php print $article->link ?>" method="post">
				<input type="hidden" name="do_login" value="1" />
				<div class="form-group">
					<label><?php _e('Documento de Identidad:', 'uv'); ?></label>
					<input type="text" name="ci" value="" class="form-control" />
				</div>
				<div class="form-group">
					<label><?php _e('Constrase&ntilde;a:', 'uv'); ?></label>
					<input type="password" name="pass" value="" class="form-control" />
				</div>
				<div class="form-group">
					<label><?php _e('Text de Seguridad:', 'uv'); ?></label>
					<div class="row">
						<div class="col-md-5"><img src="<?php print SB_Route::_('captcha.php'); ?>" alt="" /></div>
						<div class="col-md-4"><input type="text" name="captcha" value="" class="form-control" /></div>
					</div>
				</div>
				<div class="form-group">
					<a href="<?php print SB_Route::_('index.php?mod=users&view=recover_pwd'); ?>">
						<?php _e('No recuerdas tu contrase&ntilde;a', 'uv'); ?>
					</a>
				</div>
				<div class="form-group">
					<button class="btn btn-default btn-green" type="submit"><?php _e('Ingresar', 'uv'); ?></button>
				</div> 
			</form>
		</div>
		<div class="col-md-4"></div>
		<div class="col-md-4">
			<h4><?php _e('Registro', 'uv'); ?></h4>
			<p>
				Not tienes una cuenta?
			</p>
			<p>
				<a href="javascript:;" id="btn-show-register" class="btn btn-default btn-green"><?php _e('Registrate Ahora', 'uv'); ?></a>
			</p>
		</div>
	</div>
	<div id="register-container" class="row" style="display:none;">
		<div class="col-md-4"></div>
		<div class="col-md-4">
			<h4><?php _e('Formulario de Registro', 'uv'); ?></h4>
			<form id="form-register" action="" method="post">
				<input type="hidden" name="do_register" value="1" />
				<div class="form-group">
					<label><?php _e('Documento de Identidad', 'uv'); ?></label>
					<input type="text" name="ci" value="" class="form-control" required="required" />
				</div>
				<div class="form-group">
					<label><?php _e('Email', 'uv'); ?></label>
					<input type="email" name="email" value="" class="form-control" required="required" />
				</div>
				<div class="form-group">
					<label><?php _e('Contrase&ntilde;a', 'uv'); ?></label>
					<input type="password" name="pass" value="" class="form-control" required="required" />
				</div>
				<div class="form-group">
					<label><?php _e('Confirme su contrase&ntilde;a', 'uv'); ?></label>
					<input type="password" name="rpass" value="" class="form-control" required="required" />
				</div>
				<div class="form-group">
					<img src="<?php print SB_Route::_('captcha.php?var=reg_captcha'); ?>" alt="" />
					<input type="text" name="captcha" value="" />
				</div>
				<div class="form-group text-center">
					<button type="submit" class="btn btn-default btn-green"><?php _e('Enviar informaci&oacute;n', 'uv'); ?></button>
				</div>
			</form>
		</div>
		<div class="col-md-4">&nbsp;</div>
	</div>
</div>
<script>
jQuery(function()
{
	jQuery('#btn-show-register').click(function()
	{
		jQuery('#login-container').css('display', 'none');
		jQuery('#register-container').css('display', 'block');
		return false;
	});
	jQuery('#form-register').submit(function()
	{
		var form = this;
		jQuery(form).find('button').prop('disabled', true);
		if( jQuery('#reg-msg').length > 0 )
		{
			jQuery('#reg-msg').remove();
		} 
		var params = jQuery(form).serialize();
		jQuery.post('index.php', params, function(res)
		{
			jQuery(form).find('button').prop('disabled', false);
			if( res.status == 'ok' )
			{
				jQuery(form).append("<div id=\"reg-msg\" class=\"alert alert-success\">"+res.message+"</div>");
			}
			else
			{
				jQuery(form).append("<div id=\"reg-msg\" class=\"alert alert-danger\">"+res.error+"</div>");
			}
		});
		return false;
	});
});
</script>
<?php lt_get_footer(); ?>