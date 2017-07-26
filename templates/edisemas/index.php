<?php
lt_get_header();
?>
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12 col-sm-9 col-md-9">
				<div id="content">
					<div class="content-wrap">
						<?php SB_MessagesStack::ShowMessages(); ?>
						<?php sb_show_module(); ?>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-3 col-md-3">
				<?php lt_get_sidebar(); ?>
			</div>
		</div>
	</div>
<?php lt_get_footer(); ?>