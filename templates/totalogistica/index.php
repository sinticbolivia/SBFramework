<?php
$template_url = '';
?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title><?php lt_title(); ?></title>
	<link rel="stylesheet" href="<?php print BASEURL ?>/js/bootstrap-3.3.5/css/bootstrap.min.css" />
	<link rel="stylesheet" href="<?php print TEMPLATE_URL; ?>/style.css" />
	<script src="<?php print BASEURL ?>/js/jquery.min.js"></script>
	<script src="<?php print BASEURL ?>/js/bootstrap-3.3.5/js/bootstrap.min.js"></script>
	<script src="<?php print $template_url; ?>/js/common.js"></script>
	<?php lt_head(); ?>
</head>
<body <?php lt_body_class(); ?>>
<div id="container" class="container-fluid">
	<?php sb_show_module(); ?>
</div>
</body>
</html>