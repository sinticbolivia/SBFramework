<?php
$site_logo = UPLOADS_DIR . SB_DS . sb_get_parameter('site_logo');
$site_logo_url = null;
if( is_file($site_logo) )
{
	$site_logo_url = UPLOADS_URL . '/' . basename($site_logo);
}
else
{
	$site_logo_url = TEMPLATE_URL . '/images/logo.png';
}
$site_logo_url = TEMPLATE_URL . '/images/logo.png';
?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8" />
	<title><?php lt_title(); ?></title>
	<link rel="stylesheet" href="<?php print BASEURL; ?>/js/bootstrap-3.3.5/css/bootstrap.min.css" />
	<!-- <link rel="stylesheet" href="<?php print BASEURL; ?>/js/bootstrap-3.3.5/css/bootstrap-theme.min.css" />  -->
	<script src="<?php print BASEURL; ?>/js/jquery.min.js"></script>
	<script src="<?php print BASEURL; ?>/js/bootstrap-3.3.5/js/bootstrap.min.js"></script>
	<?php lt_head(); ?>
	<link rel="stylesheet" href="<?php print sb_get_template_url(); ?>/style.css" />
</head>
<body <?php lt_body_id(); ?> <?php lt_body_class(); ?>>
<div id="container" class="gwrap">
	<header id="header">
		<div class="container-fluid">
			<div class="row">
				<div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
					<div id="logo">
						<?php if($site_logo_url): ?>
						<a href="index.php">
							<img src="<?php print $site_logo_url; ?>" alt="<?php print lt_site_title(); ?>" 
								title="<?php print lt_site_title(); ?>" />
						</a>
						<p><?php //print lt_site_title(); ?></p>
						<?php else: ?>
						<div class="text">
							<a href="index.php">
								<?php foreach(explode(' ', SITE_TITLE) as $index => $word): ?>
								<span class="w<?php print $index; ?>"><?php print trim($word); ?></span>
								<?php endforeach; ?>
							</a>
						</div>
						<?php endif; ?>
						
					</div>
				</div>
				<div class="hidden-xs col-sm-3 col-md-5"></div>
				<div class="hidden-xs col-sm-3 col-md-2">
					<?php if( !sb_widget_area('header_widgets') ): ?>
					<?php endif; ?>
					<div class="widget language-selector">
						<a href="<?php print SB_Route::_('index.php?lang=en_US'); ?>"><?php _e('English', 'edisemas'); ?></a> |
						<a href="<?php print SB_Route::_('index.php?lang=es_ES'); ?>"><?php _e('Spanish', 'edisemas'); ?></a>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<nav id="navigation">
						<?php if( !lt_show_content_menu('navegacion_'.LANGUAGE, array(
																		'class' => 'menu', 
																		'sub_menu_class' => 'submenu') ) ): ?>
						<ul>
							<li><a href="<?php print SB_Route::_('index.php'); ?>"><?php _e('Home', 'edisemas'); ?></a></li>
							<li>
								<a href="javascript:;"><?php _e('Sections', 'edisemas'); ?></a>
							</li>
							<li>
								<a href="<?php print SB_Route::_('index.php?mod=employees&view=attendance'); ?>"><?php _e('Attendance', 'edisemas'); ?></a>
							</li>
							<li>
								<?php if( !sb_is_user_logged_in() ): ?>
								<a href="<?php print SB_Route::_('index.php?mod=users'); ?>"><?php _e('Login', 'edisemas'); ?></a>
								<?php else: ?>
								<a href="<?php print SB_Route::_('index.php?mod=users'); ?>"><?php _e('My Account', 'edisemas'); ?></a>
								<?php endif; ?>
							</li>
							<?php if( sb_is_user_logged_in() ): ?>
							<li><a href="<?php print SB_Route::_('index.php?mod=users&task=logout'); ?>"><?php _e('Logout', 'edisemas'); ?></a></li>
							<?php endif; ?>
						</ul>
						<?php endif; ?>
						<div class="clearfix"></div>
					</nav><!-- end id="navigation" -->
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
	</header><!-- end id="header" -->