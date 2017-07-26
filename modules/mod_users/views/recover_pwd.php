<?php
?>
<?php if( !isset($recover_form) || !$recover_form ): ?>
<form id="form-login" action="" method="post" class="row">
	<input type="hidden" name="mod" value="users" />
	<input type="hidden" name="task" value="recover_pwd_now" />
	<h3><?php print SB_Text::_('Recuperaci&oacute;n de Contrase&ntilde;a', '')?></h3>
	<div class="row">
		<div class="col-md-4">
			<div class="control-group">
				<input type="email" name="email" value="" class="form-control" placeholder="<?php print SB_Text::_('Su direccion de correo', 'users'); ?>" />
			</div>
		</div>
		<div class="col-md-1">
			<button type="submit" id="button-login" class="btn btn-success"><?php print SB_Text::_('Recuperar contrase&ntilde;a', 'users'); ?></button>
		</div>
	</div>
</form>
<?php else: ?>
<form action="" method="post">
	<input type="hidden" name="mod" value="users" />
	<input type="hidden" name="task" value="update_password" />
	<input type="hidden" name="hash" value="<?php print $hash; ?>" />
	<div class="row">
		<div class="col-xs-12 col-md-6">
			<div class="form-group">
			<label><?php _e('New password', 'users'); ?></label>
			<input type="password" name="pwd" value="" required class="form-control" />
		</div>
		<div class="form-group">
			<label><?php _e('Repeat New password', 'users'); ?></label>
			<input type="password" name="rpwd" value="" required class="form-control" />
		</div>
		<div class="form-group">
			<button type="submit" class="btn btn-success"><?php _e('Save', 'users'); ?></button>
		</div>
		</div>
	</div>
</form>
<?php endif;?>
