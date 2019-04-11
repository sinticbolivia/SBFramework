<?php

require_once 'header.php';
?>
	<div id="content">
		<div class="container"><?php sb_show_messages(); ?></div>
		<?php sb_show_module(isset($_html_content) ? $_html_content : null); ?>
	</div><!-- end id="content" -->
<?php require_once 'footer.php';  ?>