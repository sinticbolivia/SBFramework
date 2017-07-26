<?php 
?>
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
<body <?php print lt_body_class(); ?>>
<div id="container">
	<header id="header">
		<div class="container-fluid">
			<div class="row">
				<div id="top-header">
					<ul id="top-social-links">
						<li><a href="" class="link link-facebook"></a></li>
						<li><a href="" class="link link-gplus"></a></li>
						<li><a href="" class="link link-tw"></a></li>
						<li><a href="" class="link link-mail"></a></li>
						<li><a href="" class="link link-youtube"></a></li>
					</ul>
					
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12 col-sm-6 col-md-4">
					<div id="logo">
						<a href="<?php print BASEURL; ?>">
							<img src="<?php print TEMPLATE_URL; ?>/images/logo-01.png" alt="<?php print SITE_TITLE; ?>" />
							<br/>
							<span style="display:block;padding-left:108px;">
								<span style="display:block;font-family:'CoolVetica';text-transform:uppercase;color:#2d3e50;font-size:20px;text-align:center;">
									Microfundici&oacute;n Zamak
								</span>
							</span>
						</a>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-2">
					<div class="text-center">
						<?php if( !isset($args['show_bolivia']) || $args['show_bolivia'] ): ?>
						<img src="<?php print TEMPLATE_URL; ?>/images/escudo-01.png" 
							alt="<?php _e('Echo en Bolivia', 'gs'); ?>" style="width:80px;margin:20px 0 0 0;" />
						<?php endif; ?>
					</div>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-6">
					<a href="javascript:;" id="btn-mobile-menu" class="hidden-sm hidden-md hidden-lg">
						<span class="glyphicon glyphicon-menu-hamburger"></span>
						Menu
					</a>
					<nav id="navigation" class="">
						<?php if( !lt_show_content_menu('navegacion_'.LANGUAGE, array(
														'class' => 'menu', 
														'sub_menu_class' => 'submenu')) ): ?>
						<ul>
							<li><a href=""><?php _e('Inicio', 'gs'); ?></a></li>
							<li><a href=""><?php _e('Productos', 'gs'); ?></a></li>
							<li><a href=""><?php _e('Venta de Maquinas', 'gs'); ?></a></li>
							<li><a href=""><?php _e('Videos', 'gs'); ?></a></li>
							<li><a href=""><?php _e('Donde Estamos', 'gs'); ?></a></li>
						</ul>
						<?php endif; ?>
					</nav><!-- end id="navigation" -->
				</div>
			</div>
		</div>		
	</header><!-- end id="header" -->