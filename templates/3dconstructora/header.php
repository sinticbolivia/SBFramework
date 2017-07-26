<!doctype html>
<html lang="es">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title><?php lt_title(); ?></title>
	<link rel="stylesheet" href="<?php print BASEURL ?>/js/bootstrap-3.3.5/css/bootstrap.min.css" />
	<link rel="stylesheet" href="<?php print TEMPLATE_URL; ?>/style.css" />
	<script src="<?php print BASEURL ?>/js/jquery.min.js"></script>
	<script src="<?php print BASEURL ?>/js/bootstrap-3.3.5/js/bootstrap.min.js"></script>
	<script src="<?php print BASEURL ?>/js/common.js"></script>
	<script src="<?php print TEMPLATE_URL ?>/js/common.js"></script>
	<script src="<?php print BASEURL; ?>/js/isotope.min.js"></script>
	<?php lt_head(); ?>
</head>
<body <?php lt_body_class(); ?>>
<div id="container">
	<header id="header">
		<div class="container-fluid">
			<div class="row">
				<div class="col-xs-6 col-sm-2 col-md-3">
					<div id="logo">
						<a href="<?php print SB_Route::_(''); ?>">
							<img src="<?php print TEMPLATE_URL; ?>/images/logo-250x.png" alt="<?php print SITE_TITLE; ?>" />
						</a>
					</div>
				</div>
				<div class="col-xs-6 col-sm-10 col-md-9">
					<nav id="navigation">
						<a href="javascript:;" id="btn-menu-mobile" class="hidden-sm hidden-md hidden-lg">
							<span class="glyphicon glyphicon-menu-hamburger"></span>
						</a>
						<?php if( !lt_show_content_menu('navegacion_'.LANGUAGE, array(
														'class' 			=> 'menu', 
														'sub_menu_class' 	=> 'submenu')) ): ?>
						<ul>
							<li><a href="">Inicio</a></li>
							<li><a href="">Portfolio</a></li>
							<li><a href="">Servicios</a></li>
							<li><a href="">Quienes Somos</a></li>
							<li><a href="">Contactos</a></li>
						</ul>
						<?php endif; ?>
						<div class="clearfix"></div>
					</nav><!-- end id="navigation" -->
				</div>
			</div>
		</div>
	</header><!-- end id="header" -->