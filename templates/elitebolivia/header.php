<!doctype html>
<html lang="<?php print LANGUAGE; ?>">
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
<body <?php lt_body_class(); ?>>
<div id="container" class="">
	<header id="header">
		<div class="container-fluid">
			<div class="row">
				<div id="logo-wrap" class="col-xs-12 col-sm-4 col-md-4">
					<figure id="logo">
						<a href="<?php print SB_Route::_('/'); ?>">
							<img src="<?php print TEMPLATE_URL; ?>/images/logo.png" alt="<?php print SITE_TITLE; ?>" class="img-resp" />
						</a>
					</figure>
					<div id="site-title"><?php print SITE_TITLE; ?></div>
					<div id="logo-bg">&nbsp;</div>
				</div>
				<div class="col-xs-12 col-sm-8 col-md-8">
					<div class="row">
						<div class="col-xs-12 col-sm-9 col-md-9">
							<form id="header-form-search" action="" method="get">
								<input type="hidden" name="mod" value="emono" />
								<input type="hidden" name="task" value="search" />
								<div class="form-group">
									<input type="text" name="keyword" value="" 
										placeholder="<?php _e('Buscar...', 'elite'); ?>" class="form-control" />
								</div>
							</form>
						</div>
						<div class="col-xs-12 col-sm-3 col-md-3">
							<?php sb_show_widget('SB_MBWidgetCart') ?>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<nav id="navigation">
								<?php if( !lt_show_content_menu('navegacion_'.LANGUAGE, array(
														'class' => 'menu horizontal', 
														'sub_menu_class' => 'submenu')) ): ?>
								<ul>
									<li><a href="">Inicio</a></li>
									<li><a href="">Productos</a></li>
									<li><a href="">Quienes Somos</a></li>
									<li><a href="">Terminos y Condiciones</a></li>
									<li><a href="">Donde Estamos</a></li>
								</ul>
								<?php endif; ?>
								<div class="clearfix"></div>
							</nav>
						</div>
					</div>
				</div>
			</div>
		</div>
	</header><!-- end id="header" -->