<?php

?>
<h1><?php _e('User Access', 'users'); ?></h1>
<form action="" method="post">
	<input type="hidden" name="mod" value="users" />
	<input type="hidden" name="task" value="do_login" />
	<fieldset>
		<?php SB_Module::do_action('login_form_before_fields'); ?>
		<div class="control-group">
			<label><?php print SB_Text::_('Username:', 'users'); ?></label>
			<input type="text" name="username" value="" class="form-control" />
		</div>
		<div class="control-group">
			<label><?php print SB_Text::_('Password:', 'users'); ?></label>
			<input type="password" name="pwd" value="" class="form-control" />
		</div>
		<p style="text-center">
        	<img src="<?php print SB_Route::_('captcha.php?time='.time()); ?>" alt="">
        	<input type="text" name="captcha" value="" autocomplete="off">
        </p>
        <?php SB_Module::do_action('login_form_after_fields'); ?>
		<div class="control-group">
			<a href="<?php print SB_Route::_('index.php?mod=users&view=recover_pwd'); ?>"><?php print SB_Text::_('No recuerdas tu contrase&ntilde;a?', 'users');?></a>
		</div>
		<p class="control-group">
			<button type="submit" id="button-login" class="btn btn-success">
				<?php _e('Login', 'users'); ?>
			</button>
			<?php SB_Module::do_action('login_form_buttons'); ?>
		</p>
	</fieldset>
</form>