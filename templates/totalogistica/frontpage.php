<?php

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
	<script src="<?php print TEMPLATE_URL; ?>/js/common.js"></script>
	<?php lt_head(); ?>
</head>
<body>
<video id="front-video" autoplay style="position:absolute;z-index:1;display:block;object-fit: fill;">
	<source src="<?php print TEMPLATE_URL; ?>/videos/Cityscape_Chicago.mp4" type="video/mp4">
	Your browser does not support the video tag.
</video> 
<div id="container-wrap" class="container-flui">	
	<div style="position:absolute;left:0;top:0;bottom:0;width:50%;text-align:center;padding-top:50px;">
		<img src="<?php print TEMPLATE_URL; ?>/images/LOGO.png" alt="" style="height:400px;" />
	</div>
	<div id="bubbles">
		<a href="" class="bubble blue"><img src="<?php print TEMPLATE_URL; ?>/images/BURBUJA1.png" alt="" /></a>
		<a href="" class="bubble white"><img src="<?php print TEMPLATE_URL; ?>/images/BURBUJA2.png" alt="" /></a>
		<a href="" class="bubble red"><img src="<?php print TEMPLATE_URL; ?>/images/BURBUJA3.png" alt="" /></a>
	</div>
	<div id="logo-wrap">
		<img src="<?php print TEMPLATE_URL; ?>/images/LOGO-2.png" alt="" id="the-logo" />
	</div>
	
</div>
<script>
function resizeVideo()
{
	var video = document.getElementById('front-video');
	video.style.width = window.innerWidth + 'px';
	video.style.height = window.innerHeight + 'px';
}
window.onload = function()
{
	resizeVideo();
};
jQuery(function()
{
	jQuery(window).resize(resizeVideo);
});
</script>
</body>
</html>