<?php
sb_add_script(BASEURL . '/js/jquery.min.js', 'jquery');
?>
<!doctype html>
<html>
<head>
	<title><?php lt_title(); ?></title>
	<link rel="stylesheet" href="<?php print BASEURL; ?>/js/bootstrap-3.3.5/css/bootstrap.min.css" />
	<link rel="stylesheet" href="<?php print TEMPLATE_URL; ?>/style.css" />
	<?php lt_head() ?>
	<script src="<?php print BASEURL; ?>/js/bootstrap-3.3.5/js/bootstrap.min.js"></script>
</head>
<body <?php lt_body_class() ?>>
<div id="container" class="container-fluid">
	<div class="row">
		<div class="col-md-offset-4 col-md-4">
			<?php SB_MessagesStack::ShowMessages(); ?>
			<div id="login-wrap"><?php sb_show_module(); ?></div>
		</div>
	</div>
</div>
</body>
</html>