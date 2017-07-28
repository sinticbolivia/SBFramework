<?php
$tpl = TSPT();
?>
	<footer id="footer">
		<div class="container-fluid">
			<div class="row">
				<div class="col-xs-12 col-sm-4 col-md-4">
					<div class="widget">
						<h2 class="title"></h2>
						<div class="body">
							<section class="address">
								<b>Direcci&oacute;n:</b><br/>
								<address><?php print @$tpl->direccion ?></address>
							</section>
							<section class="phone">
								<b>Telefonos:</b><br/>
								<?php print @$tpl->telf; ?>
							</section>
							<!--
							<section class="address">
								<b>Direcci&oacute;n alcaldia tiquina (El Prado):</b><br/>
								<address>Av. xxxx #222</address>
							</section>
							-->
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-4 col-md-4">
					<div id="social-links">
						<a href="" target="_blank"><img src="<?php print TEMPLATE_URL; ?>/images/icons/facebook.png" alt="" /></a>
						<a href="" target="_blank"><img src="<?php print TEMPLATE_URL; ?>/images/icons/tweeter.png" alt="" /></a>
						<a href="" target="_blank"><img src="<?php print TEMPLATE_URL; ?>/images/icons/instagram.png" alt="" /></a>
					</div>
				</div>
				<div class="col-xs-12 col-sm-4 col-md-4">
					<div class="widget">
						<h2 class="title">Lista de Correo</h2>
						<div class="body">
							<p>
								Ingresa tu correo para obtener nuestras noticias y novedades
							</p>
							<form action="" method="post" class="form-newsletter">
								<input type="hidden" name="mod" value="newsletter" />
								<input type="hidden" name="task" value="subscribe" />
								<input type="hidden" name="list_id" value="1" />
								<input type="hidden" name="ajax" value="1" />
								<div class="form-group">
									<input type="text" name="email" value="" class="form-control" />
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</footer><!-- end id="footer" -->
</div><!-- end id="container" -->
<?php lt_footer(); ?>
<!-- Go to www.addthis.com/dashboard to customize your tools -->
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-594c35e4ae2b38ed"></script>
</body>
</html>