<?php
$site_logo = UPLOADS_DIR . SB_DS . sb_get_parameter('site_logo');
$header_image = sb_get_parameter('header_image', null);
$site_logo_url = null;
$header_image_url = null;
if( file_exists($site_logo) )
{
	$site_logo_url = UPLOADS_URL . '/' . basename($site_logo);
}
$bg_image = sb_get_parameter('bg_image');
$bg_image_url = null;
if( is_object($bg_image) )
{
	$bg_image_url = $bg_image->url;
}
elseif( $bg_image && file_exists(UPLOADS_DIR . SB_DS . $bg_image) )
{
	$bg_image_url = UPLOADS_URL . '/' . basename($bg_image);
}
//##check header image
if( $header_image && file_exists(UPLOADS_DIR . SB_DS . $header_image) )
{
	$header_image_url = UPLOADS_URL . '/' . $header_image;
}
$logo_align = defined('LOGO_ALIGN') ? LOGO_ALIGN : 'left';
?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title><?php lt_title(); ?></title>
	<link rel="stylesheet" href="<?php print BASEURL; ?>/js/bootstrap-3.3.5/css/bootstrap.min.css" />
	<!-- <link rel="stylesheet" href="<?php print BASEURL; ?>/js/bootstrap-3.3.5/css/bootstrap-theme.min.css" />  -->
	<link rel="stylesheet" href="<?php print sb_get_template_url(); ?>/style.css?v=<?php print rand(); ?>" />
	<link rel="stylesheet" href="<?php print BASEURL; ?>/js/bootstrap-datepicker-1.4.0/css/bootstrap-datepicker3.min.css" />
	<script src="<?php print BASEURL; ?>/js/jquery.min.js"></script>
	<script src="<?php print BASEURL; ?>/js/bootstrap-3.3.5/js/bootstrap.min.js"></script>
	<script src="<?php print BASEURL; ?>/js/bootstrap-datepicker-1.4.0/js/bootstrap-datepicker.min.js"></script>
	<script src="<?php print TEMPLATE_URL; ?>/js/common.js"></script>
	<style>
	body{
	<?php if( defined('BG_COLOR') /*&& !empty(BG_COLOR)*/ ) : ?>
	background-color: <?php print BG_COLOR;?>;
	<?php endif; ?>
	}
	<?php if( defined('HEADER_BG_COLOR') ) : ?>
	#header
	{
		<?php /*if( $header_image_url ): ?>
		background-image: url('<?php print $header_image_url; ?>');
		background-position: top center;
		background-size: 100% auto;
		<?php endif;*/ ?>
		position:relative;
		background-color: <?php print HEADER_BG_COLOR;?>;
	}
	<?php endif; ?>
	<?php $title_color = defined('SITE_TITLE_COLOR') ? SITE_TITLE_COLOR : null; ?>
	<?php if( !empty($title_color) ) : ?>
	#site-title, .site-title-color{color:<?php print $title_color;?>;}
	<?php endif; ?>
	#logo{text-align:<?php print $logo_align; ?>}
	<?php if( defined('BTN_WIDTH') ) : ?>
	.section-list li a, .articles-list li a{min-width:<?php print BTN_WIDTH; ?>px;}
	<?php endif; ?>
	</style>
	<?php lt_scripts(); ?>
</head>
<body>
<?php if( $bg_image_url ): ?>
<style>
body{background: url('<?php print $bg_image_url?>') no-repeat fixed center;
<?php if( defined('BG_DESIGN') && BG_DESIGN == 'expand' ): ?>
background-size: cover;
<?php endif; ?>
<?php endif; ?>}
</style>
<?php /*
<div id="bg-image" style="width:100%;height:100%;position:absolute;top:0;right:0;bottom:0;left:0;z-index:1;">
	<img src="<?php print $bg_image_url?>" alt="" style="max-width:100%;max-height:100%;width:100%;height:100%;" />
</div>
<?php endif; ?>
*/?>
<div id="container">
	<header id="header">
		<?php if( $header_image_url ): ?>
		<div class="bg_img"><img src="<?php print $header_image_url?>" alt="" /></div>
		<?php endif; ?>
		<div id="logo">
			<?php if($site_logo_url): ?>
			<img src="<?php print $site_logo_url; ?>" alt="<?php print lt_site_title(); ?>" title="<?php print lt_site_title(); ?>" />
			<?php else: ?>
			<div class="text">
				<span class="l1">Little</span> <span class="l2">CMS</span>
			</div>
			<?php endif; ?>
			<p id="site-title"><?php print lt_site_title(); ?></p>
		</div>
		<?php /*
		<nav id="navigation">
			<ul>
				<li><a href="<?php print SB_Route::_('index.php'); ?>">Inicio</a></li>
				<li>
					<a href="javascript:;">Secciones</a>
				</li>
				<li>
					<?php if( !sb_is_user_logged_in() ): ?>
					<a href="<?php print SB_Route::_('index.php?mod=users'); ?>">Iniciar Sesion</a>
					<?php else: ?>
					<a href="<?php print SB_Route::_('index.php?mod=users'); ?>">Mi Cuenta</a>
					<?php endif; ?>
				</li>
				<?php if( sb_is_user_logged_in() ): ?>
				<li><a href="<?php print SB_Route::_('index.php?mod=users&task=logout'); ?>">Cerrar Sesion</a></li>
				<?php endif; ?>
			</ul>
		</nav>
		*/?>
		<div class="clear"></div>
	</header><!-- end id="header" -->