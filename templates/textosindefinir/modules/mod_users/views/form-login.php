<?php

?>
<h1><?php _e('User Access', 'users'); ?></h1>
<form action="" method="post">
	<input type="hidden" name="mod" value="users" />
	<input type="hidden" name="task" value="do_login" />
	<fieldset>
		<?php SB_Module::do_action('login_form_before_fields'); ?>
		<div class="form-group">
			<label><?php _e('Username:', 'users'); ?></label>
			<input type="text" name="username" value="" class="form-control" />
		</div>
		<div class="form-group">
			<label><?php _e('Password:', 'users'); ?></label>
			<input type="password" name="pwd" value="" class="form-control" />
		</div>
        <?php SB_Module::do_action('login_form_after_fields'); ?>
		<div class="form-group">
			<a href="<?php print SB_Route::_('index.php?mod=users&view=recover_pwd'); ?>"><?php print SB_Text::_('No recuerdas tu contrase&ntilde;a?', 'users');?></a>
		</div>
		<div class="form-group text-center">
			<button type="submit" id="button-login" class="btn btn-dark-blue">
				<?php _e('Entrar a mi cuenta', 'tsd'); ?>
			</button>
			<?php SB_Module::do_action('login_form_buttons'); ?>
		</div>
	</fieldset>
</form>