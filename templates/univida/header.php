<?php

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/jpeg" href="<?php print TEMPLATE_URL; ?>/favicon.jpg">
	<title><?php lt_title(); ?></title>
	<link href="<?php print BASEURL; ?>/js/bootstrap-3.3.5/css/bootstrap.min.css" rel="stylesheet">
	<link href="<?php print TEMPLATE_URL; ?>/css/style.css" rel="stylesheet">
	<link href="<?php print TEMPLATE_URL; ?>/style.css?var=<?php print rand();?>" rel="stylesheet">
	<link href='http://fonts.googleapis.com/css?family=Cabin:400,500,600,700,400italic,500italic,600italic,700italic' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Lato:300,400,700,900,400italic,700italic,900italic' rel='stylesheet' type='text/css'>
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->
	<!--[if IE 8]><link rel="stylesheet" type="text/css" href="css/ie8.css" /><![endif]-->
	<script src="<?php print BASEURL; ?>/js/jquery.min.js"></script>
	<script src="<?php print BASEURL; ?>/js/bootstrap-3.3.5/js/bootstrap.min.js"></script>
	<?php lt_head(); ?>
</head>
<body <?php print lt_body_class(lt_is_frontpage() ? 'homepage' : 'contentpage'); ?>>
	<!-- Navigation -->
	<div class="navbar navbar-default navbar-fixed-top affix" role="navigation">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<h1>
					<a href="<?php print SB_Route::_('index.php')?>">
						<img src="<?php print TEMPLATE_URL; ?>/images/logo-univida.png" alt="" class="img-responsive" />
					</a>
				</h1>
			</div>	
			<div class="navbar-collapse collapse">
				<ul class="nav navbar-nav pull-right">
					<li>
						<?php if( !sb_is_user_logged_in() ): ?>
						<a href="<?php print SB_Route::_('index.php?mod=users&view=login'); ?>">
							<span data-hover="<?php _e('Ingreso', 'uv'); ?>"><?php _e('Ingreso', 'uv'); ?></span>
						</a>
						<?php else: ?>
						<a href="<?php print SB_Route::_('index.php?mod=users&task=logout'); ?>">
							<span data-hover="<?php _e('Cerrar sesion', 'uv'); ?>"><?php _e('Cerrar sesion', 'uv'); ?></span>
						</a>
						<?php endif; ?>
					</li>
				</ul>
				<?php lt_show_content_menu('navegacion_'.LANGUAGE, array('class' => 'nav navbar-nav', 'sub_menu_class' => 'dropdown-menu')); ?>
			</div>
		</div>
	</div>
	<!-- Navigation end -->
