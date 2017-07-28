<!doctype html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title><?php lt_title(); ?></title>
	<link rel="stylesheet" href="<?php print BASEURL ?>/js/bootstrap-3.3.5/css/bootstrap.min.css" />
	<link rel="stylesheet" href="<?php print TEMPLATE_URL; ?>/style.css" />
	<script src="<?php print BASEURL ?>/js/jquery.min.js"></script>
	<script src="<?php print BASEURL ?>/js/bootstrap-3.3.5/js/bootstrap.min.js"></script>
	<script src="<?php print TEMPLATE_URL; ?>/js/common.js"></script>
	<?php lt_head(); ?>
</head>
<body <?php lt_body_class() ?>>
<div id="container" class="container-fluid">
	<header id="header" class="row">
		<div class="container">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-4">
					<div id="logo">
						<a href="<?php print SB_Route::_('/'); ?>">
							<img src="<?php print TEMPLATE_URL; ?>/images/secap-logo.png" 
								alt="<?php print SITE_TITLE; ?>" style="width:125px;" />
						</a>
					</div>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-4 hidden-xs hidden-sm"></div>
				<div class="col-xs-12 col-sm-12 col-md-4 hidden-xs hidden-sm last-col">
					<div class="row">
						<div class="col-md-4 bg">&nbsp;</div>
						<div class="col-md-4 bg">&nbsp;</div>
						<div class="col-md-4 bg">&nbsp;</div>
					</div>
				</div>
			</div>
		</div>
		<a href="javascript:;" id="btn-mobile-menu" class="hidden-sm hidden-md hidden-lg">
			<span class="glyphicon glyphicon-menu-hamburger"></span>
			Menu
		</a>
		<nav id="navigation">
			<?php if( !lt_show_content_menu('navigacion_'.LANGUAGE, array(
														'class' => 'menu', 
														'sub_menu_class' => 'submenu')) ): ?>
			<ul>
				<li class="active"><a href="">Inicio</a></li>
				<li><a href="">Servicios</a></li>
				<li><a href="">Acerca de nosotros</a></li>
				<li><a href="">Premios</a></li>
				<li><a href="">Capacitaciones</a></li>
				<li><a href="">Contactenos</a></li>
			</ul>
			<?php endif; ?>
			<div class="clearfix"></div>
		</nav><!-- end id="navigation" -->
	</header>