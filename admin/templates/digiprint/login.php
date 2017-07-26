<?php
defined('LT_ADMIN') or die();
?>
<!doctype html>
<html>
<head>
	<meta charset="UTF-8" />
	<title><?php print SITE_TITLE; ?> - Login</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes" />
	<link rel="stylesheet" href="<?php print BASEURL; ?>/js/bootstrap-3.3.5/css/bootstrap.min.css" />
	<link rel="stylesheet" href="<?php print TEMPLATE_URL ?>/css/login.css" />
	<script src="<?php print BASEURL; ?>/js/jquery.min.js"></script>
	<script src="<?php print TEMPLATE_URL ?>/js/login.js"></script>
</head>
<body>
<div id="container">
	<?php SB_MessagesStack::ShowMessages(); ?> 
	<div id="login-container">
		<img src="<?php print TEMPLATE_URL; ?>/images/logodigiprint_blanco.png" alt="DigiPrint" />
		<form id="form-login" action="<?php print SB_Route::_('login.php'); ?>" method="post" >
			<input type="hidden" name="mod" value="users" />
			<input type="hidden" name="task" value="do_login" />
			<?php if( isset($_SERVER['HTTP_REFERER']) ): ?>
			<input type="hidden" name="redirect" value="<?php print $_SERVER['HTTP_REFERER']; ?>" />
			<?php endif; ?>
			<div class="form-group">
				<input type="text" name="username" class="name" placeholder="<?php print SBText::_('Username', 'digiprint'); ?>" />
			</div>
			<div class="form-group">
				<input type="password" name="pwd" class="pass" placeholder="<?php print SBText::_('Password', 'digiprint'); ?>" />
			</div>
			<div class="checkbox">
				<label>
					<input type="checkbox" name="remember" value="1" />
					<?php _e('Remember password', 'digiprint'); ?>
				</label>
			</div>
			<div class="form-group">
				<button type="submit" id="btn-login" class="btn"><?php print SBText::_('Access to System', 'digiprint'); ?></button>
			</div>
			<div style="position:absolute;bottom:-20px;left:44%;width:0;height:0;border-top:20px solid #fff;border-right:20px solid transparent;border-left:20px solid transparent;"></div>
		</form>
	</div>
</div>
</body>
</html>