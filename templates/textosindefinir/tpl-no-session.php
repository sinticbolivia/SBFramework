<?php

?>
<!doctype html>
<html>
<head>
	<title><?php lt_title(); ?></title>
	<link rel="stylesheet" href="<?php print BASEURL; ?>/js/bootstrap-3.3.5/css/bootstrap.min.css" />
	<link rel="stylesheet" href="<?php print TEMPLATE_URL; ?>/style.css" />
	<script src="<?php print BASEURL . '/js/jquery.min.js'; ?>"></script>
	<?php lt_head() ?>
	<script src="<?php print BASEURL; ?>/js/bootstrap-3.3.5/js/bootstrap.min.js"></script>
</head>
<body <?php lt_body_class() ?>>
<div id="container" class="container-fluid">
	<div class="row">
		<header id="header">
			<div class="col-md-3 col-sm-6 col-xs-12">
				<div id="logo"><?php print SITE_TITLE ?></div>
			</div>
			<div class="col-md-9 col-sm-6 col-xs-12">
				<ul id="navigation">
					<li><a href="<?php print SB_Route::_('index.php'); ?>">Inicio</a></li>
					<li><a href="http://500sitios.helpdeskdigital.com/" target="_blank">Soporte</a></li>
				</ul>
			</div>
			<div class="clearfix"></div>
		</header><!-- end id="header" -->
	</div>
	<div id="row-content" class="row">
		<div id="content" class="col-md-12">
			<div id="content-wrap">
				<div style="width:800px;margin:0 auto;">
				<?php SB_MessagesStack::ShowMessages(); ?>
				<?php sb_show_module(); ?>
				</div>
			</div>
		</div><!-- end id="content" -->
<?php lt_get_footer(); ?>