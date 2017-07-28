<!doctype html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title><?php lt_title(); ?></title>
	<link rel="stylesheet" href="<?php print BASEURL; ?>/js/bootstrap-3.3.5/css/bootstrap.min.css" />
	<link rel="stylesheet" href="<?php print TEMPLATE_URL ?>/style.css?v=<?php print time(); ?>" />
	<script src="<?php print BASEURL; ?>/js/jquery.min.js"></script>
	<script src="<?php print BASEURL; ?>/js/bootstrap-3.3.5/js/bootstrap.min.js"></script>
	<script src="<?php print TEMPLATE_URL; ?>/js/common.js"></script>
	<?php lt_head() ?>
</head>
<body <?php lt_body_class(); ?>>
<div id="container" class="container-fluid">
	<?php if( !defined('MOD_TEMPLATE_FILE') ): ?>
	<div id="header" class="row">
		<div class="wrap">
			<div class="col-xs-12 col-md-4">
			
			</div>
			<div class="col-xs-12 col-md-4">
				<div id="logo" class="text-center">
					<a href="<?php print SB_Route::_('index.php'); ?>">
						<img src="<?php print TEMPLATE_URL; ?>/images/logo.png" alt="" />
					</a>
				</div>
			</div>
			<div class="col-xs-12 col-md-4">
				<?php /*
				<div id="top-navigation">
					<ul class="menu horizontal">
						<li><a href="">Login</a></li>
						<li><a href="">Register</a></li>
					</ul>
				</div>
				*/?>
			</div>
		</div>
		<nav id="navigation">
			<?php if( !lt_show_content_menu('navigation_'.LANGUAGE, array(
														'class' => 'menu horizontal', 
														'sub_menu_class' => 'submenu')) ): ?>
			<ul class="menu horizontal">
				<li><a href="">News</a></li>
				<li><a href="index.php?mod=emono">Products</a></li>
				<li><a href="">Literature</a></li>
				<li><a href="index.php?mod=content&view=article&id=1">About</a></li>
				<li><a href="index.php?mod=content&view=article&id=4">Contact Us</a></li>
				<li><a href="index.php?mod=content&view=article&id=2">Find a Rep</a></li>
			</ul>
			<?php endif; ?>
			<div class="clearfix"></div>
		</nav>
	</div><!-- end id="header" -->
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<form action="" method="get" style="margin:10px 0 0 0;">
					<input type="hidden" name="mod" value="emono" />
					<div class="input-group">
						<input type="text" name="keyword" value="" class="form-control" placeholder="<?php _e('Search product by name/code', 'ps'); ?>" />
						<span class="input-group-btn">
							<button class="btn btn-primary"><?php _e('Search', 'ps'); ?></button>
						</span>
					</div>
				</form>
			</div>
		</div>
	</div>
	<?php endif; ?>