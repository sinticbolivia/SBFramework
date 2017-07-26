<?php
?>
			<form id="form-login" action="" method="post">
				<input type="hidden" name="mod" value="users" />
				<input type="hidden" name="task" value="do_login" />
				<fieldset>
					<?php SB_Module::do_action('login_form_before_fields'); ?>
					<div class="form-group">
						<label class="site-title-color"><?php print SB_Text::_('Usuario:', 'users'); ?></label>
						<input type="text" name="username" value="" class="form-control" />
					</div>
					<div class="form-group">
						<label class="site-title-color"><?php print SB_Text::_('Contrase&ntilde;a:', 'users'); ?></label>
						<input type="password" name="pwd" value="" class="form-control" />
					</div>
					<p class="form-group text-center">
			        	<img src="<?php print SB_Route::_('captcha.php?inverse=1&time='.time()); ?>" alt="" />
			        	<input type="text" name="captcha" value="" autocomplete="off" style="width:90px;">
			        </p>
			        <?php SB_Module::do_action('login_form_after_fields'); ?>
					<div class="form-group text-center">
						<a href="<?php print SB_Route::_('index.php?mod=users&view=recover_pwd'); ?>"><?php print SB_Text::_('No recuerdas tu contrase&ntilde;a?', 'users');?></a>
					</div>
					<p class="form-group text-center">
						<button type="submit" id="button-login" class="btn btn-success col-md-12"><?php print SB_Text::_('Entrar a mi cuenta', 'users'); ?></button>
						<?php SB_Module::do_action('login_form_buttons'); ?>
					</p>
				</fieldset>
			</form>
<style>
#sidebar{display:none;}
</style>