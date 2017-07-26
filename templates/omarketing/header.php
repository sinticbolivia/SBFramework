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
	<script src="<?php print TEMPLATE_URL; ?>/js/common.js"></script>
	<?php lt_head(); ?>
</head>
<body <?php lt_body_class(); ?>>
<div id="container" class="container-fluid">
	<header id="header" >
		<div id="header-fix" class="affix">
			<div class="">
				<div class="col-xs-2 hidden-sm hidden-md hidden-lg">
					<a href="javascript:;" id="btn-mobile-menu" class="btn btn-mobile">
						<span class="glyphicon glyphicon-menu-hamburger"></span>
					</a>
				</div>
				<div class="col-xs-2 hidden-sm hidden-md hidden-lg">
					<a href="javascript:;" id="btn-mobile-search" class="btn btn-mobile">
						<span class="glyphicon glyphicon-search"></span>
					</a>
				</div>
				<div class="col-xs-6 col-sm-3 col-md-3">
					<div id="logo">
						<a href="#"><img src="<?php print TEMPLATE_URL; ?>/images/logo.png" alt="" /></a>
					</div>
				</div>
				<div class="hidden-xs col-sm-9 col-md-9">
					<div class="row">
						<div class="hidden-sm col-md-3">
						</div>
						<div class="col-sm-7 col-md-7">
							<div id="header-search-form">
								<form id="search-form" action="" method="get">
									<input type="hidden" name="mod" value="content" />
									<input type="text" name="keyword" value="<?php print SB_Request::getString('keyword'); ?>" placeholder="Buscar..." 
										class="form-control" autocomplete="off" />
									<a href="javascript:;" id="btn-search">
										<span class="glyphicon glyphicon-search"></span>
									</a>
								</form>
							</div><!-- end id="header-search-form" -->
						</div>
						<div class="col-sm-5 col-md-2">
							<nav id="user-menu" class="row">
								<ul>
									<li>
										<a href="<?php print SB_Route::_('index.php?mod=users&view=register'); ?>">
											<?php _e('Registro', 'om'); ?>
										</a>
									</li>
									<li>
										<a href="<?php print SB_Route::_('index.php?mod=users'); ?>">
											<?php _e('Mi Cuenta', 'om'); ?>
										</a>
									</li>
								</ul>
							</nav>
						</div>
					</div>
					<nav id="navigation" class="row">
						<?php if( !lt_show_content_menu('navegacion_'.LANGUAGE, array(
																	'class' => 'menu', 
																	'sub_menu_class' => 'submenu')) ): ?>
						<ul>
							<li><a href="">Inicio</a></li>
							<li><a href="">Servicios</a></li>
							<li><a href="">Blog</a></li>
							<li><a href="">Cursos</a></li>
							<li><a href="">Biografias</a></li>
							<li><a href="">Contacto</a></li>
						</ul>
						<?php endif; ?>
					</nav>
				</div>
			</div>
		</div><!-- end id="header-fix" -->
		
		
		<div class="clearfix"></div>
	</header>
	