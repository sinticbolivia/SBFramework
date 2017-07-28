<?php lt_get_header(); ?>
	<div id="wrap-content" class="row">
		<div id="content" class="">
			<h1 id="content-title">Asesoria Contable <span class="icon icon-calc"></span></h1>
			<div id="content-body">
				<div class="container">
					<?php SB_MessagesStack::ShowMessages(); ?>
					<div class="row">
						<div class="col-md-12"><?php sb_show_module(); ?></div>
					</div>
				</div>
			</div><!-- end id="content-body" -->
		</div><!-- end id="content" -->
	</div>
<?php lt_get_footer(); ?>