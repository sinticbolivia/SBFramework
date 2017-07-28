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
<div id="container">
	<header id="header" class="affix">
		<div class="container-fluid">
			<div class="row">
				<div class="col-xs-3 hidden-sm hidden-md hidden-lg">
					<div id="mobile-menu-container">
						<a href="javascript:;" id="btn-hamburguer">
							<span class="glyphicon glyphicon-menu-hamburger"></span>
						</a>
						<nav id="mobile-navigation">
							<?php if( !lt_show_content_menu('navegacion_'.LANGUAGE, array(
															'class' => 'menu', 
															'sub_menu_class' => 'submenu')) ): ?>
							<ul class="menu">
								<li><a href="">Inicio</a></li>
								<li><a href="">Quienes Somos</a></li>
								<li><a href="">Eventos</a></li>
								<li><a href="">Galeria</a></li>
								<li><a href="">Donde Estamos</a></li>
								<li><a href="">Organigrama Intitucional</a></li>
							</ul>
							<?php endif; ?>
							<a href="javascript:;" id="btn-close-mobile-navigation">
								<span class="glyphicon glyphicon-circle-arrow-left"></span>
							</a>
						</nav><!-- end id="mobile-navigation" -->
					</div>
				</div>
				<div class="col-xs-9 col-sm-6 col-md-3">
					<figure id="logo">
						<a href="<?php print SB_Route::_(''); ?>">
							<img src="<?php print TEMPLATE_URL; ?>/images/logo.png" alt="<?php print SITE_TITLE; ?>" title="<?php print SITE_TITLE; ?>" />
						</a>
					</figure>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-9">
					<nav id="navigation" class="hidden-xs">
						<?php if( !lt_show_content_menu('navegacion_'.LANGUAGE, array(
														'class' => 'menu', 
														'sub_menu_class' => 'submenu')) ): ?>
						<ul class="menu">
							<li><a href="">Inicio</a></li>
							<li><a href="">Quienes Somos</a></li>
							<li><a href="">Eventos</a></li>
							<li><a href="">Galeria</a></li>
							<li><a href="">Donde Estamos</a></li>
							<li><a href="">Organigrama Intitucional</a></li>
						</ul>
						<?php endif; ?>
						<div class="clearfix"></div>
					</nav>
				</div>
			</div>
		</div>
	</header><!-- end id="header" -->