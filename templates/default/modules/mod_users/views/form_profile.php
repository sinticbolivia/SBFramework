<?php
if( !sb_is_user_logged_in() )
{
	sb_redirect(SB_Route::_('index.php'));
}
$user = sb_get_current_user();
?>
<div class="panel panel-primary" style="width:400px;margin:0 auto;">
	<div class="panel-heading">
		<h2><?php print SBText::_('Completa tu perfil'); ?></h2>
	</div>
	<div class="panel-body">
		<form action="" method="post">
			<input type="hidden" name="mod" value="users" />
			<input type="hidden" name="task" value="save_profile" />
			<div class="form-group">
				<label><?php print SBText::_('Nombre'); ?></label>
				<input type="text" name="first_name" value="<?php print @$user->first_name; ?>" class="form-control" />
			</div>
			<div class="form-group">
				<label><?php print SBText::_('Apellidos'); ?></label>
				<input type="text" name="last_name" value="<?php print @$user->last_name; ?>" class="form-control" />
			</div>
			<div class="form-group row">
				<div class="col-md-12"><label><?php print SB_Text::_('Fecha de Nacimiento:')?></label></div>
				<div class="col-md-5">
    				<input class="form-control datepicker" type="text" name="birthday" value="<?php print @$user->_birthday; ?>" />
    			</div>
		    </div>
		    <div class="form-group">
	    		<label><?php print SB_Text::_('Direccion:')?></label>
	    		<input class="form-control" type="text" name="address" value="<?php print @$user->_address; ?>" />
		    </div>
		    <div class="form-group">
	    		<label><?php print SB_Text::_('Ciudad:', 'users')?></label>
	    		<input class="form-control" type="text" name="city" value="<?php print @$user->_city; ?>" />
		    </div>
		    <div class="form-group">
		    	<label><?php print SB_Text::_('Provincia:', 'users')?></label>
		    	<input class="form-control" type="text" name="state" value="<?php print @$user->_state; ?>" />
		    </div>
		    <div class="form-group">
		    	<label><?php print SB_Text::_('Pais:', 'users')?></label>
		    	<select name="country" class="form-control">
  					<?php foreach(include INCLUDE_DIR . SB_DS . 'countries.php' as $code => $c): ?>
  					<option value="<?php print $code; ?>" <?php print @$user->_country == $code ? 'selected' : ''; ?>><?php print $c; ?></option>
  					<?php endforeach; ?>
  				</select>
		    </div>
			<div class="form-group text-center">
				<button class="btn btn-primary"><?php print SBText::_('Guardar'); ?></button>
			</div>
		</form>
	</div>
</div>