<?php
/**
 * Template: Contact us
 */ 
?>
<?php lt_get_header(); ?>
	<div id="content">
		<?php SB_MessagesStack::ShowMessages(); ?>
		<div class="content-wrap">
			<?php lt_include_template('tpl-user-menu'); ?>
			<?php //sb_show_module(); ?>
			<br/>
			<div style="width:50%;margin:0 auto;">
			<div class="panel">
				<div class="panel-body">
					<?php if( !SB_Request::getInt('sent') ): ?>
					<h2>Formulario de Contacto</h2>
					<p>
						Utiliza este formulario de contacto para enviarnos tus Dudas, Consultas o Sugerencias.
						Para contactar, cubre todos los datos y detalla correctamente tu mensaje.
					</p>
					<form id="contact-form" action="" method="post">
						<input type="hidden" name="task" value="send_contact_form" />
						<div class="form-group">
							<label><?php print SBText::_('Asunto:'); ?></label>
							<input type="text" name="subject" value="" class="form-control" />
						</div>
						<div class="form-group">
							<label><?php print SBText::_('Mensaje:'); ?></label>
							<textarea name="message" class="form-control" style="height:200px;"></textarea>
						</div>
						<div class="form-group">
							<button type="submit" class="btn btn-primary"><?php print SBText::_('Enviar'); ?></button>
						</div>
					</form>
					<script>
					jQuery(function()
					{
						jQuery('#contact-form').submit(function()
						{
							if( this.email.value.trim().length <= 0 )
							{
								alert('Ingresa tu email para tu mensaje');
								return false;
							}
							if( this.subject.value.trim().length <= 0 )
							{
								alert('Ingresa en asunto para tu mensaje');
								return false;
							}
							if( this.message.value.trim().length <= 0 )
							{
								alert('Debes ingresar un mensaje');
								return false;
							}
							return true;
						});
					});
					</script>
					<?php else: ?>
					<h2>Su mensaje fue enviado correctamente.</h2>
					<?php endif; ?>
				</div>
			</div>
			</div>
		</div>
	</div>
<?php lt_get_footer(); ?>