<?php
sb_include_module_helper('content');
class LT_TemplateUnivida
{
	public function __construct()
	{
		SB_Language::loadLanguage(LANGUAGE, 'uv', dirname(__FILE__) . SB_DS . 'locale');
		$this->AddActions();
	}
	public function AddActions()
	{
		SB_Module::add_action('content_types', array($this, 'action_content_types'));
		SB_Shortcode::AddShortcode('faqs', array($this, 'shortcode_faqs'));
		SB_Module::add_action('init', array($this, 'action_init'));
	}
	public function action_content_types($types)
	{
		$types['faq'] = array(
					'labels'	=> array(
							'menu_label'	=> __('FAQ', 'uv'),
							'new_label'		=> __('New FAQ', 'uv'),
							'edit_label'	=> __('Edit FAQ', 'uv'),
							'listing_label'	=> __('FAQ\'s', 'uv')
					),
					'features'	=> array(
							'featured_image'	=> false,
							'use_dates'			=> false,
							'calculated_dates'	=> false
					)
			);
		return $types;
	}
	public function shortcode_faqs()
	{
		$items = LT_HelperContent::GetArticles(array('type' => 'faq', 'rows_per_page' => -1, 'publish_date' => false, 'end_date' => false));
		?>
		<div id="accordion" class="panel-group faqs">
			<?php $i = 1; foreach($items['articles'] as $faq): ?>
			<div class="panel panel-default">
				<div id="heading-<?php print $i; ?>" class="panel-heading">
					<h4>
						<a href="#collapse-<?php print $i; ?>" data-toggle="collapse" data-parent="#accordion">
							<?php print $faq->title; ?>
						</a>
					</h4>
				</div>
				<div id="collapse-<?php print $i; ?>" class="panel-collapse collapse">
					<?php print $faq->content; ?>
				</div>
			</div>
			<?php $i++; endforeach; ?>
		</div>
		<?php 
	}
	public function action_init()
	{
		$mod = SB_Request::getString('mod');
		if( SB_Request::getInt('do_login') && ($mod != 'users' || !$mod) )
		{
			$this->DoLogin();
		}
		elseif( SB_Request::getInt('do_register') )
		{
			$this->DoRegister();
		}
		elseif( SB_Request::getString('crp') )
		{
			$this->CompleteRegister();
		}
	}
	protected function DoLogin()
	{
		$dbh 	= SB_Factory::getDbh();
		$ci 	= SB_Request::getString('ci');
		$pass 	= SB_Request::getString('pass');
		$user_captcha = SB_Request::getString('captcha');
		$query 	= "SELECT u.* ".
					"FROM users u " .
					"WHERE 1 = 1 ".
					"AND u.username = '$ci' ".
					"LIMIT 1";
		$row = $dbh->FetchRow($query);
		$captcha = SB_Session::getVar('login_captcha');
		if( !$row )
		{
			SB_MessagesStack::AddMessage(__('El documento de identidad o contrase&ntilde;a es invalido', 'uv'), 'error');
			return false;
		}
		if( $row->pwd != md5($pass) )
		{
			SB_MessagesStack::AddMessage(__('El documento de identidad o contrase&ntilde;a es invalido', 'uv'), 'error');
			return false;
		}
		if( !$user_captcha )
		{
			SB_MessagesStack::AddMessage(__('Debe ingresar el texto de seguridad', 'uv'), 'error');
			return false;
		}
		if( $user_captcha != $captcha )
		{
			SB_MessagesStack::AddMessage(__('Texto de seguridad incorrecto.', 'uv'), 'error');
			return false;
		}
		if( $row->status != 'enabled' )
		{
			SB_MessagesStack::AddMessage(__('El usuario no se encuentra activo', 'uv'), 'error');
			return false;
		}
		SB_Session::setVar('user', $row);
		$cookie_value = md5(serialize($row) . ':' . session_id());
		SB_Session::setVar('lt_session', $cookie_value);
		SB_Session::setVar('timeout', time());
		//##mark user as logged in
		sb_update_user_meta($row->user_id, '_logged_in', 'yes');
		sb_update_user_meta($row->user_id, '_logged_in_time', time());
		SB_Module::do_action('authenticated', $row, $row->username, $row->pwd);
		sb_redirect(SB_Route::_('index.php?mod=users'));
	}	
	protected function DoRegister()
	{
		$ci 	= SB_Request::getString('ci');
		$email 	= SB_Request::getString('email');
		$pass	= SB_Request::getString('pass');
		$rpass	= SB_Request::getString('rpass');
		$user_captch = SB_Request::getString('captcha');
		$captcha		= SB_Session::getVar('reg_captcha');
		if( empty($ci) )
		{
			sb_response_json(array('status' => 'error', 'error' => __('Debe ingresar su documento de identidad', 'uv')));
		}
		if( empty($email) )
		{
			sb_response_json(array('status' => 'error', 'error' => __('Debe ingresar su direccion de correo', 'uv')));
		}
		if( empty($pass) )
		{
			sb_response_json(array('status' => 'error', 'error' => __('Debe ingresar una contrase&ntilde;a', 'uv')));
		}
		if( $pass != $rpass )
		{
			sb_response_json(array('status' => 'error', 'error' => __('Las contrase&ntilde;as no son iguales', 'uv')));
		}
		if( $user_captch != $captcha )
		{
			sb_response_json(array('status' => 'error', 'error' => __('El texto de seguridad es incorrecto', 'uv')));
		}
		$dbh = SB_Factory::getDbh();
		//##check is ci exists into tomador table
		if( !$dbh->Query("SELECT numero_poliza FROM uv_tomador WHERE numero_documento_identidad = '$ci' LIMIT 1") )
		{
			sb_response_json(array('status' => 'error', 'error' => __('Usted no tienen ningun registro de poliza en nuestra base de datos', 'uv')));
		}
		//##check if username already exists
		if( $dbh->Query("SELECT user_id FROM users WHERE username = '$ci' LIMIT 1") )
		{
			sb_response_json(array('status' => 'error', 'error' => __('El documento de identidad ya esta registrado', 'uv')));
		}
		
		$data = array(
				'username'					=> $ci,
				'pwd'						=> md5($pass),
				'email'						=> $email,
				'status'					=> 'disabled',
				'role_id'					=> 2, //user role
				'last_modification_date'	=> date('Y-m-d H:i:s'),
				'creation_date'				=> date('Y-m-d H:i:s')
		);
		
		$user_id = $dbh->Insert('users', $data);
		sb_add_user_meta($user_id, '_pass', base64_encode($pass));
		$key = base64_encode("$user_id:$ci:$email:uv");
		
		$link = SB_Route::_("index.php?crp=".$key);
		$message = "Su informaci&oacute;n fue enviada correctamente, porfavor revise su email para poder continuar con el proceso de registro";
		$subject = sprintf("Proceso de Registro - %s", SITE_TITLE);
		$body 		= "Estimado asegurado:<br/>
						Para concluir su registro de acceso a sus polizas solo presione click en el siguiente acceso.<br/><br/>
						<a href=\"$link\">Completar Registro</a><br/><br/>
						Agradecemos su preferencia<br/><br/>
						atte<br/>" . SITE_TITLE;
		$headers = array(
				'Content-type:text/html',
				sprintf("From: %s <no-reply@univida.bo>", SITE_TITLE)
		);
		lt_mail($email, $subject, $body, $headers);
		sb_response_json(array('status' => 'ok', 'message' => $message));
	}
	public function CompleteRegister()
	{
		$key = SB_Request::getString('crp');
		if( !$key )
		{
			sb_redirect(SB_Route::_('index.php'));
		}
		$key = base64_decode($key);
		list($user_id,$ci,$email,$uv) = explode(':', $key);
		$user = new SB_User($user_id);
		if( !$user->user_id )
		{
			SB_MessagesStack::AddMessage(__('Error de llave, imposible completar con el registro', 'uv'), 'error');
			sb_redirect(SB_Route::_('index.php'));
		}
		if( $user->username != $ci )
		{
			SB_MessagesStack::AddMessage(__('Error de llave, imposible completar con el registro', 'uv'), 'error');
			sb_redirect(SB_Route::_('index.php'));
		}
		if( $user->email != $email )
		{
			SB_MessagesStack::AddMessage(__('Error de llave, imposible completar con el registro', 'uv'), 'error');
			sb_redirect(SB_Route::_('index.php'));
		}
		if( $uv != 'uv' )
		{
			SB_MessagesStack::AddMessage(__('Error de llave, imposible completar con el registro', 'uv'), 'error');
			sb_redirect(SB_Route::_('index.php'));
		}
		$dbh = SB_Factory::getDbh();
		$dbh->Update('users', array('status' => 'enabled'), array('user_id' => $user_id));
		SB_MessagesStack::AddMessage(__("Proceso de registro completado!!!"), 'success');
		sb_redirect(SB_Route::_('index.php'));
	}
}
new LT_TemplateUnivida();
function uv_get_services($limit = 4)
{
	$section_id = 1;
	$services = LT_HelperContent::GetArticles(array(
			'section_id' => $section_id, 
			'type' => 'page', 
			'rows_per_page' => $limit, 
			'orderby' => 'show_order', 
			'order' => 'ASC')
	);
	//print_r($services);
	return $services['articles'];
}
