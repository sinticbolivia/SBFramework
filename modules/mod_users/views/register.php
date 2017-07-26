<?php
?>
<div id="register-container">
	<h1><?php _e('Register an Account', 'users'); ?></h1>
	<form action="" method="post">
		<input type="hidden" name="mod" value="users" />
		<input type="hidden" name="task" value="do_register" />
		<div class="form-group">
			<label><?php _e('Username', 'users'); ?></label>
			<input type="text" name="username" value="" class="form-control" />
		</div>
		<div class="form-group">
			<label><?php _e('Email', 'users'); ?></label>
			<input type="text" name="email" value="" class="form-control" />
		</div>
		<div class="form-group">
			<label><?php _e('Password', 'users'); ?></label>
			<input type="password" name="pwd" value="" class="form-control" />
		</div>
		<div class="form-group">
			<label><?php _e('Re-Type Password', 'users'); ?></label>
			<input type="password" name="rpwd" value="" class="form-control" />
		</div>
		<div class="form-group">
			<button type="submit" class="btn btn-primary"><?php _e('Register', 'users'); ?></button>
		</div>
	</form>
</div>