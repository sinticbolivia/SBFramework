<?php
$view = SB_Request::getString('view');
lt_get_header();
?>
<div class="container">
	<?php SB_MessagesStack::ShowMessages(); ?>
</div>
<?php sb_show_module(); ?>
<?php lt_get_footer(); ?>