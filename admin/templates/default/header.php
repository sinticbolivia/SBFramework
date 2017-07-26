<?php
?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title><?php lt_title(); ?></title>
	<link rel="stylesheet" href="<?php print BASEURL; ?>/js/bootstrap-3.3.5/css/bootstrap.min.css" />
	<link rel="stylesheet" href="<?php print BASEURL; ?>/js/bootstrap-3.3.5/css/bootstrap-theme.min.css" />
	<link rel="stylesheet" href="<?php print BASEURL; ?>/js/bootstrap-datepicker-1.4.0/css/bootstrap-datepicker.min.css" />
	<link rel="stylesheet" href="<?php print TEMPLATE_URL; ?>/style.css" />
	<script src="<?php print BASEURL; ?>/js/jquery.min.js"></script>
	<script src="<?php print BASEURL; ?>/js/bootstrap-3.3.5/js/bootstrap.min.js"></script>
	<script src="<?php print BASEURL; ?>/js/bootstrap-datepicker-1.4.0/js/bootstrap-datepicker.min.js"></script>
	<script src="<?php print BASEURL; ?>/js/bootstrap-datepicker-1.4.0/locales/bootstrap-datepicker.es.min.js"></script>
	<?php lt_head(); ?>
	<script src="<?php print TEMPLATE_URL; ?>/js/common.js"></script>
</head>
<body>
<div id="container">
	<?php if( !defined('MOD_TEMPLATE') ): ?>
	<header id="header">
		<?php require_once 'navigation.php'; ?>
	</header>
	<?php endif; ?>
