<?php
$tcr = TCR();
?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" /> 
	<title><?php lt_title(); ?></title>
	<link rel="stylesheet" href="<?php print BASEURL; ?>/js/bootstrap-3.3.5/css/bootstrap.min.css" />
	<link rel="stylesheet" href="<?php print TEMPLATE_URL; ?>/style.css" />
	<script src="<?php print BASEURL; ?>/js/jquery.min.js"></script>
	<script src="<?php print BASEURL; ?>/js/bootstrap-3.3.5/js/bootstrap.min.js"></script>	
	<script src="<?php print TEMPLATE_URL; ?>/js/common.js"></script>	
	<?php lt_head(); ?>
</head>
<body <?php lt_body_class() ?>>
<div id="container" class="container-fluid">
	<header id="header">
		<div id="header-social" class="row">
			<div class="hidden-xs hidden-sm col-md-4"></div>
			<div class="hidden-xs hidden-sm col-md-4"></div>
			<div class="col-xs-12 col-sm-12 col-md-4">
				<div class="social-icons">
					<a href="<?php print $tcr->yt; ?>" target="_blank"><img src="<?php print TEMPLATE_URL; ?>/images/icons/youtube.png" alt="" /></a>
					<a href="<?php print $tcr->tw; ?>" target="_blank"><img src="<?php print TEMPLATE_URL; ?>/images/icons/tweeter.png" alt="" /></a>
					<a href="<?php print $tcr->fb; ?>" target="_blank"><img src="<?php print TEMPLATE_URL; ?>/images/icons/facebook.png" alt="" /></a>
					<a href="<?php print $tcr->linkedin; ?>" target="_blank"><img src="<?php print TEMPLATE_URL; ?>/images/icons/linkedin.png" alt="" /></a>
					<a href="<?php print $tcr->googleplus; ?>" target="_blank"><img src="<?php print TEMPLATE_URL; ?>/images/icons/google+.png" alt="" /></a>
					<a href="mailto:<?php print $tcr->email; ?>"><img src="<?php print TEMPLATE_URL; ?>/images/icons/correo.png" alt="" /></a>
				</div>
			</div>
		</div>
		<div id="header-logo-menu-container" class="row" data-spy="affix" data-offset-top="75">
			<div class="col-xs-2 hidden-sm hidden-md hidden-lg">
				<a href="#" id="btn-mobile-menu"><span class="glyphicon glyphicon-menu-hamburger"></span></a>
			</div>
			<div class="col-xs-8 col-sm-3 col-md-3">
				<div id="logo">
					<a href="<?php print SB_Route::_('/'); ?>">
						<img src="<?php print TEMPLATE_URL; ?>/images/logo.png" alt="<?php print SITE_TITLE; ?>" title="<?php print SITE_TITLE; ?>" />
					</a>
				</div>
			</div>
			<div class="col-xs-12 col-sm-9 col-md-9">
				<nav id="navigation" class="hidden-xs">
					<?php if( !lt_show_content_menu('navegacion_'.LANGUAGE, array(
															'class' => 'menu', 
															'sub_menu_class' => 'submenu')) ): ?>
					<ul class="menu">
						<li><a href=""><?php _e('Inicio'); ?></a></li>
						<li><a href=""><?php _e('Cursos'); ?></a></li>
						<li><a href=""><?php _e('Inscripciones'); ?></a></li>
						<li><a href=""><?php _e('Formas de Pago'); ?></a></li>
						<li><a href=""><?php _e('Preguntas Frecuentes'); ?></a></li>
						<li><a href=""><?php _e('Contacto'); ?></a></li>
					</ul>
					<?php endif; ?>
					<div class="clearfix"></div>
				</nav>
			</div>
		</div>
	</header><!-- end id="header" -->