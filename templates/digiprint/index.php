<?php
lt_get_header();
?>
	<div id="content" class="col-xs-offset-2 col-md-offset-2 col-xs-10 col-md-10">
		<div class="messages"><?php SB_MessagesStack::ShowMessages(); ?></div>
		<?php if( SB_Request::getString('mod') == 'content' ): ?>
		<div id="dashboard">
			<div class="row">
				<div class="col-xs-12 col-md-4">
					<div class="widget">
						<div class="row">
							<div class="col-md-9">
								<h3 class="title"><?php _e('Compras', 'digiprint'); ?></h3>
								<span class="text"><?php _e('Realizar ordenes de impresion online', 'digiprint'); ?></span>
							</div>
							<div class="col-md-3">
								<h3 class="icon">
									<span class="glyphicon glyphicon-shopping-cart"></span>
								</h3>
							</div>
						</div>
					</div>		
				</div>
				<div class="col-xs-12 col-md-4">
					<div class="widget">
						<div class="row">
							<div class="col-md-9">
								<h3 class="title"><?php _e('Monedero', 'digiprint'); ?></h3>
								<span class="text"><?php _e('Manejo de cr&eacute;ditos y fondos de efectivo', 'digiprint'); ?></span>
							</div>
							<div class="col-md-3">
								<h3 class="icon">
									<span class="glyphicon glyphicon-shopping-cart"></span>
								</h3>
							</div>
						</div>
					</div>		
				</div>
				<div class="col-xs-12 col-md-4">
					<div class="widget">
						<div class="row">
							<div class="col-md-9">
								<h3 class="title"><?php _e('Pedidos', 'digiprint'); ?></h3>
								<span class="text"><?php _e('Estatus de su &uacute;ltima orden de servicio', 'digiprint'); ?></span>
							</div>
							<div class="col-md-3">
								<h3 class="icon">
									<span class="glyphicon glyphicon-time"></span>
								</h3>
							</div>
						</div>
					</div>		
				</div>
			</div>
		</div>
		<?php else: ?>
		<?php sb_show_module(isset($_html_content) ? $_html_content : null); ?>
		<?php endif; ?>
		<div class="clearfix"></div>
	</div><!-- end id="content" -->
<?php lt_get_footer();  ?>