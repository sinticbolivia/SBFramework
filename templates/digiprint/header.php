<?php
?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title><?php lt_title(); ?></title>
	<link rel="stylesheet" href="<?php print BASEURL; ?>/js/bootstrap-3.3.5/css/bootstrap.min.css" />
	<link rel="stylesheet" href="<?php print BASEURL; ?>/js/bootstrap-datepicker-1.4.0/css/bootstrap-datepicker.min.css" />
	<link rel="stylesheet" href="<?php print TEMPLATE_URL; ?>/style.css" />
	<script src="<?php print BASEURL; ?>/js/jquery.min.js"></script>
	<script src="<?php print BASEURL; ?>/js/bootstrap-3.3.5/js/bootstrap.min.js"></script>
	<script src="<?php print BASEURL; ?>/js/bootstrap-datepicker-1.4.0/js/bootstrap-datepicker.min.js"></script>
	<script src="<?php print BASEURL; ?>/js/bootstrap-datepicker-1.4.0/locales/bootstrap-datepicker.es.min.js"></script>
	<script src="<?php print BASEURL; ?>/js/common.js"></script>
	<?php lt_head(); ?>
	<script src="<?php print TEMPLATE_URL; ?>/js/common.js"></script>
</head>
<body>
<div id="container">
	<?php if( !defined('MOD_TEMPLATE') ): ?>
	<div id="top-bar" class="">
		<div class="row">
			<div class="col-xs-2 col-md-2">
				<div class="text-center"><img src="<?php print TEMPLATE_URL; ?>/images/logodigiprint_top.png" alt="" /></div>
			</div>
			<div class="col-xs-8 col-md-7">
				<div class="row">
					<div class="col-xs-12 col-md-6">
						<form action="" method="get" id="form-main-search">
							<input type="text" name="main_keyword" placeholder="<?php _e('Search in system', 'digiprint'); ?>" />
						</form>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-md-3">
				<div id="top-options">
					<a href="<?php print SB_Route::_('index.php?mod=mb&view=settings'); ?>" title="<?php _e('System Settings', 'digiprint'); ?>">
						<img src="<?php print TEMPLATE_URL; ?>/images/icons/50x50_blancos/icon-44.png" alt="" width="25" />
					</a>
				</div>
			</div>
		</div>
	</div><!-- end id="top-bar" -->
	<div id="menu" class="col-xs-2 col-md-2">
		<?php require_once 'navigation.php'; ?>
	</div>
	<?php endif; ?>