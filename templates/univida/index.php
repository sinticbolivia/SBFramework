<?php
$view = SB_Request::getString('view');
lt_get_header();
?>
<div class="container">
	<?php if( !in_array($view, array('page', 'post', 'article')) ): ?>
	<div class="row">
		<div class="col-md-12 centered">
			<h3><span><?php  ?></span></h3>
		</div>
	</div>
	<?php endif; ?>
	<div class="row">
		<?php SB_MessagesStack::ShowMessages(); ?>
		<?php sb_show_module(); ?>
	</div>
</div>
<?php lt_get_footer(); ?>