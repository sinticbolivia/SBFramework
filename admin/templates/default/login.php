<?php
defined('LT_ADMIN') or die();
?>
<!doctype html>
<html>
<head>
	<title><?php print SITE_TITLE; ?> - Login</title>
	<link rel="stylesheet" href="<?php print TEMPLATE_URL ?>/css/login.css" />
	<script src="<?php print BASEURL; ?>/js/jquery.min.js"></script>
</head>
<body>
<div id="container">
	<section class="login">
		<div class="titulo"><?php _e('Backend Login'); ?></div>
		<form id="login_form" name="login_form" action="<?php print SB_Route::_('login.php'); ?>" method="post" enctype="application/x-www-form-urlencoded">
			<input type="hidden" name="mod" value="users" />
			<input type="hidden" name="task" value="do_login" />
			<?php SB_Module::do_action('login_form_before_fields'); ?>
	    	<input type="text" name="username" required title="Username required" placeholder="Usuario" data-icon="U" />
	        <input type="password" name="pwd" required title="Password required" placeholder="Password" data-icon="x" />
	        <p style="text-align:center;">
	        	<img src="<?php print BASEURL; ?>/captcha.php?inverse=1&time=<?php print time(); ?>" alt="" />
	        	<input type="text" name="captcha" value="" autocomplete="off" />
	        </p>
	        <?php SB_Module::do_action('login_form_after_fields'); ?>
	        <?php /*
	        <div class="olvido">
	        	<div class="col"><a href="#" title="<?php _e('Register'); ?>"><?php _e('Register'); ?></a></div>
	            <div class="col"><a href="#" title="<?php _e('Forgot your password'); ?>"><?php _e('Forgot your password?'); ?></a></div>
	        </div>
	        */?>
	        <button type="submit" style="display:none;">Login</button>
	        <a href="javascript:;" class="enviar" onclick="document.login_form.submit();"><?php _e('Login'); ?></a>
	        <?php SB_Module::do_action('login_form_buttons'); ?>
	    </form><br/>
	 	<?php SB_MessagesStack::ShowMessages(); ?>   
	</section>
</div>
<?php if( defined('BG_ANIMATED') && BG_ANIMATED == 1 ): ?>
<?php $vurl = BG_ANIMATED_URL; ?>
<?php if( !empty($vurl) ): ?>
<script src="<?php print BASEURL; ?>/js/jquery.tubular.1.0.js"></script>
<script>	
jQuery(function()
{
	 jQuery('#container').tubular({videoId: '<?php print $vurl; ?>', wrapperZIndex: 100}); // where idOfYourVideo is the YouTube ID.
});
</script>
<?php endif; ?>
<?php endif; ?>
</body>
</html>