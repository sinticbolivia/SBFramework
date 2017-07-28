<div id="sidebar" class="col-md-3">
	<div class="widget dark">
		<h2 class="title"><?php _e('Informacion', 'om'); ?></h2>
		<div class="body">
			<div class="precio-curso">
				<span class="precio"><?php print sb_number_format($course->course_cost); ?> Bs.</span>
				<span class="texto">Precio del curso</span>
			</div>
			<div class="formas-pago">
				<div class="title"><?php _e('Formas de Pago'); ?></div>
				<ul>
					<li><a href="">Deposito en cuenta</a></li>
					<li><a href="">Transferencia Bancaria</a></li>
					<li><a href="">Tarjeta de Credito</a></li>
					<li><a href="">Efectivo</a></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="widget">
		<h2 class="title"><?php _e('Instructor', 'om'); ?></h2>
		<div class="body">
			<div class="instructor-curso">
				<div class="row">
					<div class="col-xs-12 col-sm-5 col-md-5">
						<div class="imagen">
							<?php if( !$course->teacher ): ?>
							<img src="<?php print BASEURL; ?>/images/nobody.png" alt="" title="" />
							<?php else: ?>
							<img src="<?php print $course->teacher->GetAvatar(); ?>" alt="<?php printf("%s %s", $course->teacher->first_name, $course->teacher->last_name); ?>" />
							<?php endif; ?>
						</div>
					</div>
					<div class="col-xs-12 col-sm-7 col-md-7">
						<div class="datos">
							<!-- <div class="titulo"><?php _e('Docente', 'om'); ?></div> -->
							<div class="nombre">
								<?php $course->teacher ? printf("%s %s", $course->teacher->first_name, $course->teacher->last_name) : ''; ?>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="biblio">
							<?php print $course->teacher ? str_replace("\n", '<br/>', substr($course->teacher->_bio, 0, 150)) : ''; ?>
						</div>
						<div class="text-right">
							<a href="<?php print $course->teacher ? $course->teacher->link : 'javascript:;';?>"
								target="_blank"
								class=""><?php _e('Ver biografia', 'om'); ?> &rarr;
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="widget">
		<h2 class="title"><?php _e('Inscr&iacute;bete Ahora', 'om'); ?></h2>
		<div class="body">
			
			<form id="form-enroll" action="" method="post">
				<input type="hidden" name="mod" value="lms" />
				<input type="hidden" name="task" value="enroll" />
				<input type="hidden" name="ajax" value="1" />
				<input type="hidden" name="id" value="<?php print $course->id; ?>" />
				<?php if( !sb_is_user_logged_in() ): ?>
				<div class="form-group">
					<input type="text" name="firstname" value="" class="form-control required" required 
						placeholder="<?php _e('Tu nombre', 'om'); ?>" />
				</div>
				<div class="form-group">
					<input type="text" name="lastname" value="" class="form-control required" required
						placeholder="<?php _e('Tu apellido', 'om'); ?>" />
				</div>
				<div class="form-group">
					<input type="email" name="email" value="" class="form-control required" required
						placeholder="<?php _e('Tu email', 'om'); ?>" />
				</div>
				<div class="form-group">
					<input type="text" name="phone" value="" class="form-control required" required
						placeholder="<?php _e('Tu telefono', 'om'); ?>" />
				</div>
				<div class="form-group">
					<div class="text-right">
						<button type="submit" class="btn btn-primary"><?php _e('Enviar', 'om'); ?></button>
					</div>
				</div>
				<?php else: ?>
				<div class="form-group">
					<div class="text-right">
						<button type="submit" class="btn btn-primary"><?php _e('Registrarme ahora!!!', 'om'); ?></button>
					</div>
				</div>
				<?php endif; ?>
			</form>

			<script>
			jQuery(function()
			{
				jQuery('#form-enroll').submit(function()
				{
					var params = jQuery(this).serialize();
					jQuery.post('index.php', params, function(res)
					{
						if( res.status == 'ok' )
						{
							alert(res.message, function(){window.location.reload();});
						}
						else
						{
							alert(res.error);
						}
					});
					return false;
				});
			});
			</script>
		</div><!-- end class="body" -->
	</div>
</div><!-- end id="sidebar" -->