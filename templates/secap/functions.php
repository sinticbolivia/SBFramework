<?php
class LT_TemplateSecap
{
	public function __construct()
	{
		$this->AddActions();
	}
	protected function AddActions()
	{
		SB_Module::add_action('content_types', array($this, 'action_content_types'));
		if( !lt_is_admin() )
		{
			SB_Module::add_action('init', array($this, 'action_init'));
		}
	}
	public function action_content_types($types)
	{
		//$types['page']['features']['calculated_dates'] = false;
		$types['cursos'] = array(
					'labels'	=> array(
							'menu_label'	=> __('Cursos', 'secap'),
							'new_label'		=> __('Nuevo Curso', 'secap'),
							'edit_label'	=> __('Editar Curso', 'secap'),
							'listing_label'	=> __('Cursos', 'secap')
					),
					'features'	=> array(
							'featured_image'	=> true,
							'use_dates'			=> false,
							'calculated_dates'	=> false
					)
		);
		return $types;
	}
	public function action_init()
	{
		$task = SB_Request::getTask();
		if( $task == 'contacto' )
		{
			$this->EnviarContacto();
		}
	}
	protected function EnviarContacto()
	{
		$data = SB_Request::getVars(array(
			'int:page_id',
			'firstname',
			'lastname',
			'email',
			'message'
		));
		$page = new LT_Article($data['page_id']);
		$body = "Hola Administrador<br/><br/>".
					"Tienes una nueva solicitud de contacto.<br/><br/>".
					"Nombre: {$data['firstname']}<br/>".
					"Apellidos: {$data['lastname']}<br/>".
					"Email: {$data['email']}<br/>".
					"Mensaje:<br/><br/>{$data['message']}<br/><br/>".
					"Saludos.";
		$msg = __("Gracias {$data['firstname']}!!!.<br/>Su mensaje fue recibido, nos pondremos en contacto con usted en un lapso de 24 horas.");
		$email = $page->_mail;
		$subject = 'Nueva Solicitud de Contact - ' . SITE_TITLE;
		lt_mail($email, $subject, $body, array(
													'type' 	=> 'Content-type: text/html',
													'from'	=> 'From: ' . $data['firstname'] . ' <'.$data['email'].'>'));
		SB_MessagesStack::AddMessage($msg, 'success');
		sb_redirect($_SERVER['HTTP_REFERER']);
	}
}
new LT_TemplateSecap();