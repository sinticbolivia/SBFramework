<!doctype html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title><?php lt_title(); ?></title>
	<?php lt_head(); ?>
	<link rel="stylesheet" href="<?php print BASEURL ?>/js/bootstrap-3.3.5/css/bootstrap.min.css" />
	<script src="<?php print BASEURL ?>/js/jquery.min.js"></script>
	<script src="<?php print BASEURL ?>/js/bootstrap-3.3.5/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="<?php print TEMPLATE_URL; ?>/style.css" />
	<script src="<?php print TEMPLATE_URL; ?>/js/common.js"></script>
	<!-- 
	<script id="gmap3" type="text/javascript" src="http://www.univida.bo/templates/univida/js/gmap3.min.js"></script>
	-->
</head>
<body>
<div id="container" class="container-fluid">
	<div class="row">
		<div id="header" data-spy="affix" data-offset-top="60">
			<nav id="navigation">
				<div class="container">
					<div class="row">
						<div class="col-sm-12 col-md-6">
							<div id="logo">
								<img src="<?php print TEMPLATE_URL; ?>/images/logo_02.png" alt="" />
							</div>
							<div id="logo-text">Contadores Publicos<br/>Consultores</div>
						</div>
						<div class="col-sm-12 col-md-6">
							<?php if( !lt_show_content_menu('navegacion_'.LANGUAGE, array(
														'class' => 'menu', 
														'sub_menu_class' => 'submenu')) ): ?>
							<ul class="menu">
								<li><a href="">Inicio</a></li>
								<li><a href="">Servicios</a></li>
								<li><a href="">Clientes</a></li>
								<li><a href="">Blog</a></li>
								<li><a href="">Contacto</a></li>
							</ul>
							<?php endif; ?>
						</div>
					</div>
				</div>
				
				<div class="clearfix"></div>
			</nav>
		</div><!-- end id="header" -->