<?php 
class LT_TemplateGrabadoSosa
{
	public function __construct()
	{
		$this->AddActions();
	}
	public function AddActions()
	{
		SB_Module::add_action('init', array($this, 'action_init'));
	}
	public function action_init()
	{
		$task = SB_Request::getTask();
		if( $task == 'contact_form' )
		{
			$this->SendContactForm();
		}
	}
	protected function SendContactForm()
	{
		$email 		= 'gerencia@grabadososa.com,alepelaez_6@hotmail.com';
		//$email		.= ',marce_nickya@yahoo.es';
		$firstname 	= SB_Request::getString('firstname');
		$lastname 	= SB_Request::getString('lastname');
		$useremail	= SB_Request::getString('email');
		$message 	= SB_Request::getString('message');
		$headers	= array(
			'Content-type: text/html',
			sprintf("From: %s %s <%s>", $firstname, $lastname, $useremail),
			"Reply-To: $firstname $lastname <$useremail>"
		);
		$subject = "Nueva Solicitud de Contacto - " . SITE_TITLE;
		$body = "Hola Administrador.<br/><br/>".
				"Tienes una nueva solicitud de contacto, puedes ver los detalles a continuacion:<br/><br/>".
				"Nombre: $firstname<br/>".
				"Apellido: $lastname<br/>".
				"Email: $useremail<br/>".
				"Mensaje:<br/><br/>$message<br/><br/>".
				"Saludos.";
		lt_mail($email, $subject, $body, $headers);
		$msg = __('Tu solicitud de contacto fue recibida correctamente, nos podremos en contacto lo mas antes posible.');
		sb_response_json(array('status' => 'ok', 'mensaje' => $msg, 'data' => $body));
	}
	
}
new LT_TemplateGrabadoSosa();