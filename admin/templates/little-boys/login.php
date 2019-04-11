<?php
use SinticBolivia\SBFramework\Classes\SB_Route;
use SinticBolivia\SBFramework\Classes\SBText;
defined('LT_ADMIN') or die();
?>
<!doctype html>
<html>
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes" />
	<?php lt_favicon(); ?>
	<title><?php print SITE_TITLE; ?> - Login</title>
	<link rel="stylesheet" href="<?php print TEMPLATE_URL ?>/css/login.css" />
	<script src="<?php print BASEURL; ?>/js/jquery.min.js"></script>
	<script src="<?php print TEMPLATE_URL ?>/js/login.js"></script>
</head>
<body <?php lt_body_class(); ?>>
<div class="cont">
  	<div class="demo">
    	<div class="login">
    		<?php if( defined('SITE_LOGO')): ?>
    		<div><img src="<?php print UPLOADS_URL; ?>/<?php print SITE_LOGO; ?>" style="max-width:100%;" /></div>
    		<?php else: ?>
      		<div class="login__check"></div>
      		<?php endif; ?>
      		<div class="login__form">
      			<form id="form-login" action="<?php print SB_Route::_('login.php'); ?>" method="post">
      				<input type="hidden" name="mod" value="users" />
					<input type="hidden" name="task" value="do_login" />
					<?php if( isset($_SERVER['HTTP_REFERER']) ): ?>
					<input type="hidden" name="redirect" value="<?php print $_SERVER['HTTP_REFERER']; ?>" />
					<?php endif; ?>
	        		<div class="login__row">
			        	<svg class="login__icon name svg-icon" viewBox="0 0 20 20">
			            	<path d="M0,20 a10,8 0 0,1 20,0z M10,0 a4,4 0 0,1 0,8 a4,4 0 0,1 0,-8" />
			          	</svg>
	          			<input type="text" name="username" class="login__input name" placeholder="<?php print SBText::_('Username', 'lb'); ?>"/>
	        		</div>
	        		<div class="login__row">
	          			<svg class="login__icon pass svg-icon" viewBox="0 0 20 20">
	            			<path d="M0,20 20,20 20,8 0,8z M10,13 10,16z M4,8 a6,8 0 0,1 12,0" />
	          			</svg>
	          			<input type="password" name="pwd" class="login__input pass" placeholder="<?php print SBText::_('Password', 'lb'); ?>"/>
	        		</div>
	        		<button type="submit" class="login__submit"><?php print SBText::_('Sign in', 'lb'); ?></button>
	        		<p class="login__signup">
	        			<?php print SBText::_("Don't have an account?", 'lb'); ?> &nbsp;
	        			<a href="<?php print SB_Route::_('index.php?mod=users&view=register'); ?>"><?php print SBText::_('Sign up', 'lb'); ?></a>
	        		</p>
	        	</form>
      		</div>
    </div>
    <div class="app">
      <div class="app__top">
        <div class="app__menu-btn">
          <span></span>
        </div>
        <svg class="app__icon search svg-icon" viewBox="0 0 20 20">
          <!-- yeap, its purely hardcoded numbers straight from the head :D (same for svg above) -->
          <path d="M20,20 15.36,15.36 a9,9 0 0,1 -12.72,-12.72 a 9,9 0 0,1 12.72,12.72" />
        </svg>
        <p class="app__hello"><?php _e('Good Morning!', 'lb'); ?></p>
        <div class="app__user">
          <img src="<?php print TEMPLATE_URL; ?>/images/profile-512_5.jpg" alt="" class="app__user-photo" />
          <span class="app__user-notif">3</span>
        </div>
        <div class="app__month">
          <span class="app__month-btn left"></span>
          <p class="app__month-name">March</p>
          <span class="app__month-btn right"></span>
        </div>
      </div>
      <div class="app__bot">
        <div class="app__days">
          <div class="app__day weekday">Sun</div>
          <div class="app__day weekday">Mon</div>
          <div class="app__day weekday">Tue</div>
          <div class="app__day weekday">Wed</div>
          <div class="app__day weekday">Thu</div>
          <div class="app__day weekday">Fri</div>
          <div class="app__day weekday">Sad</div>
          <div class="app__day date">8</div>
          <div class="app__day date">9</div>
          <div class="app__day date">10</div>
          <div class="app__day date">11</div>
          <div class="app__day date">12</div>
          <div class="app__day date">13</div>
          <div class="app__day date">14</div>
        </div>
        <div class="app__meetings">
          <div class="app__meeting">
            <img src="<?php print TEMPLATE_URL; ?>/images/profile-80_5.jpg" alt="" class="app__meeting-photo" />
            <p class="app__meeting-name">Feed the cat</p>
            <p class="app__meeting-info">
              <span class="app__meeting-time">8 - 10am</span>
              <span class="app__meeting-place">Real-life</span>
            </p>
          </div>
          <div class="app__meeting">
            <img src="<?php print TEMPLATE_URL; ?>/images/profile-512_5.jpg" alt="" class="app__meeting-photo" />
            <p class="app__meeting-name">Feed the cat!</p>
            <p class="app__meeting-info">
              <span class="app__meeting-time">1 - 3pm</span>
              <span class="app__meeting-place">Real-life</span>
            </p>
          </div>
          <div class="app__meeting">
            <img src="<?php print TEMPLATE_URL; ?>/images/profile-512_5.jpg" alt="" class="app__meeting-photo" />
            <p class="app__meeting-name">FEED THIS CAT ALREADY!!!</p>
            <p class="app__meeting-info">
              <span class="app__meeting-time">This button is just for demo ></span>
            </p>
          </div>
        </div>
      </div>
      <div class="app__logout">
        <svg class="app__logout-icon svg-icon" viewBox="0 0 20 20">
          <path d="M6,3 a8,8 0 1,0 8,0 M10,0 10,12"/>
        </svg>
      </div>
    </div>
  </div>
</div>
<?php /*?>
<div id="container">
	<section class="login">
		<div class="titulo">Backend Login</div>
		<form id="login_form" name="login_form" action="<?php print SB_Route::_('login.php'); ?>" method="post" enctype="application/x-www-form-urlencoded">
			<input type="hidden" name="mod" value="users" />
			<input type="hidden" name="task" value="do_login" />
	    	<input type="text" name="username" required title="Username required" placeholder="Usuario" data-icon="U" />
	        <input type="password" name="pwd" required title="Password required" placeholder="Password" data-icon="x" />
	        <p style="text-align:center;">
	        	<img src="<?php print BASEURL; ?>/captcha.php?time=<?php print time(); ?>" alt="" />
	        	<input type="text" name="captcha" value="" autocomplete="off" />
	        </p>
	        <div class="olvido">
	        	<div class="col"><a href="#" title="Registro">Registro</a></div>
	            <div class="col"><a href="#" title="Recuperar Password">Olvido su Password?</a></div>
	        </div>
	        <button type="submit" style="display:none;">Login</button>
	        <a href="javascript:;" class="enviar" onclick="document.login_form.submit();">Login</a>
	    </form><br/>
	 	<?php SB_MessagesStack::ShowMessages(); ?>   
	</section>
</div>
*/?>
<?php lt_footer(); ?>
</body>
</html>