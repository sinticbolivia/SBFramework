<div id="footer">
			<div class="container">
				<div class="row">
					<div class="col-sm-12 col-md-3">
						<div class="widget">
							<h2 class="title">Alianzas Estrategicas</h2>
							<div class="content">
								<?php if( !lt_show_content_menu('menu-pie-de-pagina_'.LANGUAGE, array(
														'class' => 'menu', 
														'sub_menu_class' => 'submenu')) ): ?>
								<ul>
									<li>Alianza 1</li>
									<li>Alianza 1</li>
									<li>Alianza 1</li>
								</ul>
								<?php endif; ?>
							</div>
						</div>
					</div>
					<div class="col-sm-12 col-md-3">
						<div class="widget">
							<h2 class="title">Nuestras Oficinas</h2>
							<div class="content">
								Z/ Ferropetrol, Av. del Arquitecto esq. Calle 3 Nro. 32, El Alto, La Paz, Bolivia<br/>
								Telefonos: 71540408 - 71540405<br/>
								Email: <a href="mailto:contacto@consultoraquispeayllon.com">contacto@consultoraquispeayllon.com</a><br/>
							</div>
						</div>
					</div>
					<div class="col-sm-12 col-md-3">
						<div class="widget">
							<h2 class="title">Redes Sociales</h2>
							<div class="content">
								<ul class="social-icons">
									<li>
										<a href="https://www.facebook.com/quispe.ayllon?fref=ts" target="_blank">
											<img src="<?php print TEMPLATE_URL; ?>/images/facebook-icon.svg" alt="" />
										</a>
									</li>
								</ul>
							</div>
						</div>
					</div>
					<div class="col-sm-12 col-md-3">
						<div class="widget">
							<h2 class="title">Contactenos</h2>
							<div class="content">
								<form action="" method="post" class="form-group-sm">
									<input type="hidden" name="task" value="qa_contacto" />
									<div class="form-group">
										<label>Nombre</label>
										<input type="text" name="nombre" value="" class="form-control" />
									</div>
									<div class="form-group">
										<label>Email</label>
										<input type="email" name="email" value="" class="form-control" />
									</div>
									<div class="form-group">
										<label>Mensaje</label>
										<textarea name="mensaje" class="form-control"></textarea>
									</div>
									<div class="form-group">
										<button type="submit" class="btn btn-default">Enviar</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="copyright">
			&copy;<a href="http://sinticbolivia.net" target="_blank">Sintic Bolivia</a> 2017 Todos los derechos reservados
		</div>
	</div>
</div>
<?php lt_footer(); ?>
<!-- Go to www.addthis.com/dashboard to customize your tools -->
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-4ff4bdd54571227f"></script>
</body>	
</html>