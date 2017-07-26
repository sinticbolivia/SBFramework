<?php
/**
 * Template: Contacto
 * Fields: telephone,address,mail,latitud,longitud, api_key
 */
if( SB_Request::getString('send_form') )
{
	$email 		= $article->_mail;
	$name 		= SB_Request::getString('fullname');
	$cemail 	= SB_Request::getString('email');
	$message 	= SB_Request::getString('message');
	$body 		= "Nombre: $name<br/>".
					"Email: $cemail<br/>".
					"Mensaje:<br/>$message<br/><br/><br/>".
					sprintf("%s", SITE_TITLE);
			
	$headers = array(
			'Content-type:text/html',
			sprintf("From: %s <info@univida.bo>", SITE_TITLE),
			"Reply-To: $cemail"
	);
	//lt_mail($email, 'Formulario de Contacto - ' . SITE_TITLE, $body, $headers);
	mail($email, 'Formulario de Contacto - ' . SITE_TITLE, $body, implode("\r\n", $headers));
	SB_MessagesStack::AddMessage(__('Su mensaje fue enviado correctamente, le responderemos dentro del plazo de 24 horas.'), 'success');
}
sb_add_script(TEMPLATE_URL . '/js/gmap3.min.js', 'gmap3');
lt_get_header();
?>
<script type="text/javascript" src="//maps.google.com/maps/api/js?language=es&key=<?php print $article->_api_key; ?>"></script>
<script>
var map_location = {lat:<?php print $article->_latitud;?>,lng:<?php print $article->_longitud; ?>,address: '<?php print $article->_address; ?>'};
</script>
<div class="container">
	<div class="row">
		<div class="col-md-12 centered">
			<?php SB_MessagesStack::ShowMessages(); ?>
			<h3><span><?php print $article->title; ?></span></h3>
			<div><?php print stripslashes($article->TheContent()); ?></div>
		</div>
	</div>
</div>
<div class="container">
	<div id="map" data-stellar-background-ratio=".3"></div>
</div>
<div class="container content">
	<div class="row">
		<div class="col-md-9">
			<form role="form" id="contact_form" action="<?php print $article->link ?>" method="post">
				<input type="hidden" name="send_form" value="1" />
				<div class="form-group">
					<label for="InputName"><?php _e('Your name', 'uv'); ?></label>
					<input type="text" class="form-control" id="fullname" name="fullname" placeholder="<?php _e('Your name', 'uv'); ?>">
				</div>
				<div class="form-group">
					<label for="InputEmail"><?php _e('Your email', 'uv'); ?></label>
					<input type="email" class="form-control" id="email" name="email" placeholder="<?php _e('Your email', 'uv'); ?>">
				</div>
				<div class="form-group">
					<label for="InputMesaagel"><?php _e('Your messsage', 'uv'); ?></label>
					<textarea class="form-control" id="message" name="message" placeholder="<?php _e('Your message', 'uv'); ?>" rows="8"></textarea>
				</div>
				<button type="submit" class="btn btn-default btn-green"><?php _e('Send message', 'uv'); ?></button>
			</form>
			<script>
			jQuery('#contact_form').submit(function()
			{
				if( this.fullname.value.trim().length <= 0 )
				{
					alert('Debe ingresar su nombre');
					this.fullname.focus();
					return false;
				}
				if( this.email.value.trim().length <= 0 )
				{
					alert('Debe ingresar su email');
					this.email.focus();
					return false;
				}
				if( this.message.value.trim().length <= 0 )
				{
					alert('Debe ingresar un mensaje');
					this.message.focus();
					return false;
				}
				return true;
			});
			</script>
		</div>
		<div class="col-md-3">
			<ul class="contact-info">
				<li class="telephone">
					<?php print $article->_telephone; ?>
				</li>
				<li class="address">
					<?php print $article->_address; ?>
				</li>
				<li class="mail">
					<?php print $article->_mail; ?>
				</li>
			</ul>
		</div>
	</div>
</div>
<?php lt_get_footer(); ?>