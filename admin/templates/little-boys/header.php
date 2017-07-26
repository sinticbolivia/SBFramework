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
	<script src="<?php print BASEURL; ?>/js/common.js"></script>
	<?php lt_head(); ?>
	<script src="<?php print TEMPLATE_URL; ?>/js/common.js"></script>
</head>
<body>
<div id="container">
	<?php if( !defined('MOD_TEMPLATE') ): ?>
	<div id="menu" class="col-xs-4 col-md-2 hidden-print">
		<?php require_once 'navigation.php'; ?>
	</div>
	<div id="mobile-menu" class="hidden-print">
		<ul class="pull-left">
			<li><a href="javascript:;" id="menu-home" class="mobile-menu-item"><span class="glyphicon glyphicon-th-large"></span></a></li>
		</ul>
		<ul id="mobile-menu-right" class="pull-right">
			<li>
				<a href="javascript:;" style="width:45px;height:45px;">
					<img src="<?php print sb_get_user_image_url(sb_get_current_user()->user_id);?>" alt="" class="img-responsive thumbnail" />
				</a>
				<ul>
					<li>
						<a href="<?php print SB_Route::_('profile.php'); ?>"><?php print SB_Text::_('My profile', 'lb'); ?></a>
					</li>
					<li>
						<a href="<?php print SB_Route::_('index.php?mod=users&task=logout'); ?>"><?php _e('Close session', 'lb'); ?></a>
					</li>
				</ul>
			</li>
		</ul>
	</div>
	<?php endif; ?>
