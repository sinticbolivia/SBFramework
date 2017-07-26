<div class="wrap">
	<h2><?php _e('Database Reset', 'dbreset'); ?></h2>
	<div class="center-block">
		<form action="" method="post">
			<input type="hidden" name="mod" value="dbreset" />
			<input type="hidden" name="task" value="getdb" />
			<div class="form-controll">
				<label><?php _e('Database Name', 'dbreset'); ?></label>
				<input type="text" name="dbname" value="" class="form-control" required />
			</div>
			<div class="form-controll">
				<label><?php _e('Username', 'dbreset'); ?></label>
				<input type="text" name="username" value="" class="form-control" required />
			</div>
			<div class="form-controll">
				<label><?php _e('Password', 'dbreset'); ?></label>
				<input type="password" name="pass" value="" class="form-control" required />
			</div>
			<div class="form-controll">
				<button type="submit" class="btn btn-primary"><?php _e('Connect', 'dbreset'); ?></button>
			</div>
		</form>
	</div>
</div>