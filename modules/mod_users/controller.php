<?php
require_once MOD_USERS_DIR . SB_DS . 'controllers' . SB_DS . 'base.controller.php';
class LT_ControllerUsers extends LT_ControllerUsersBase
{
	public function task_do_login()
	{
		$username 	= SB_Request::getString('username');
		$pwd		= SB_Request::getString('pwd');
		$captcha	= SB_Request::getString('captcha');
		$r 			= SB_Request::getString('redirect');
		$rf 		= SB_Request::getString('redirect_error');
		$dbh 		= SB_Factory::getDbh();
		$query 		= "SELECT u.*,r.role_key ".
						"FROM users u ".
						"LEFT JOIN user_roles r ON r.role_id = u.role_id ".
						"WHERE u.username = '$username' LIMIT 1";
		//sb_get_user_by('username', $username);
		$row = $dbh->FetchRow($query);
		$error_link = $rf ? $rf : SB_Route::_('index.php?mod=users');
		if( !$row )
		{
			SB_Module::do_action('authenticate_error', null, $username, $pwd);
			SB_MessagesStack::AddMessage('Usuario o contrase&ntilde;a invalida', 'error');
			sb_redirect($error_link);
		}
		if( $row->pwd != md5($pwd) )
		{
			SB_Module::do_action('authenticate_error', $row, $username, $pwd);
			SB_MessagesStack::AddMessage('Usuario o contrase&ntilde;a invalida', 'error');
			sb_redirect($error_link);
		}
		$form_captcha 	= SB_Session::getVar('login_captcha');
		if( SB_Session::getVar('inverse_captcha') )
			$form_captcha	= substr($form_captcha, 3) . substr($form_captcha, 0, 3);
		
		if( $captcha != $form_captcha )
		{
			SB_Module::do_action('authenticate_error', $row, $username, $pwd);
			SB_MessagesStack::AddMessage(SB_Text::_('El texto de seguridad es invalido'), 'error');
			sb_redirect($error_link);
		}
		//print_r($row);die();
		if( $row->role_id !== 0 )
		{
			if( $row->role_key == 'possible' || $row->role_key == 'bloqued' )
			{
				SB_MessagesStack::AddMessage('El usuario no puede iniciar sesion.', 'error');
				sb_redirect($error_link);
				return false;
			}
			if( (int)sb_get_user_meta($row->user_id, '_no_login') == 1 )
			{
				sb_redirect(SB_Route::_('login-error.html'));
			}
		}
		$session = SB_Module::do_action('authenticate_session', true);
		if( !$session )
		{
			sb_redirect($error_link);
		}
		sb_user_start_session($row);
		$r = SB_Module::do_action('users_login_redirect_link', $r);
		if( $r )
		{
			sb_redirect($r);
			die();
		}
		sb_redirect(SB_Route::_('index.php?mod=users'));
	}
	public function task_logout()
	{
		/*
		if( !sb_is_user_logged_in() )
		{
			sb_redirect(SB_Route::_('index.php'));
		}
		*/
		$user = sb_get_current_user();
		sb_user_close_session($user);
		SB_Module::do_action('users_logout_before_redirect', $user);
		sb_redirect(SB_Route::_('index.php'));
	}
	
	public function task_update_password()
	{
		$hash = SB_Request::getString('hash');
		if( empty($hash) )
		{
			SB_MessagesStack::AddMessage(SBText::_('Error al recuperar la contraseña.'), 'error');
			sb_redirect(SB_Route::_('index.php?mod=users&view=recover_pwd'));
			return false;
		}
		$dbh = SB_Factory::getDbh();
		$query = "SELECT user_id from user_meta WHERE meta_key = '_recover_pwd_hash' AND meta_value = '$hash' LIMIT 1";
		if( !$dbh->Query($query) )
		{
			SB_MessagesStack::AddMessage(SBText::_('Error al recuperar la contraseña.'), 'error');
			sb_redirect(SB_Route::_('index.php?mod=users&view=recover_pwd'));
			return false;
		}
		$row = $dbh->FetchRow();
		$pwd = SB_Request::getString('pwd');
		$rpwd = SB_Request::getString('rpwd');
		if( $pwd != $rpwd )
		{
			SB_MessagesStack::AddMessage(SBText::_('Las contrase&ntilde;as no coinciden.'), 'error');
			sb_redirect(SB_Route::_('index.php?mod=users&view=recover_pwd&hash='.$hash));
		}
		sb_update_user_meta($row->user_id, '_recover_pwd_hash', null);
		$dbh->Update('users', array('pwd' => md5($pwd)), array('user_id' => $row->user_id));
		SB_MessagesStack::AddMessage(SBText::_('Tu contrase&ntilde;a fue actualizada correctamente.'), 'success');
		SB_Module::do_action('users_password_updated', $row, $pwd);
		sb_redirect(SB_Route::_('index.php?mod=users'));
	}
	public function task_default()
	{
		if( !sb_is_user_logged_in() )
		{
			$this->GetDocument()->templateFile = 'login.php';
			sb_set_view('form-login');
			return false;
		}
		$user = sb_get_current_user();
		$image = sb_get_user_meta($user->user_id, '_image');
		$image_url = '';
		if( !$image )
		{
			$image_url = MODULE_URL . '/images/nobody.png';
		}
		else
		{
			$image_url = UPLOADS_URL . '/' . sb_build_slug($user->username) . '/' . $image;
		}
		$user = new SB_User($user->user_id);
		if( !defined('SKIP_SEC_QUESTIONS') )
		{
			if( !$user->_sec_quest_1 || !$user->_sec_quest_2 || !$user->_sec_quest_1_ans || !$user->_sec_quest_2_ans)
			{
				SB_MessagesStack::AddMessage(__('You have not setted up your securiry questions, please setup as they will need to recover your password.', 'users'), 'info');
			}
		}
		$upload_url = SB_Route::_('index.php?mod=users&task=upload_image&update=1');
		sb_add_script(BASEURL . '/js/fineuploader/all.fine-uploader.min.js', 'fineuploader');
		sb_add_script(MOD_USERS_URL . '/js/frontend.js', 'user-frontend-js');
		sb_add_js_global_var('users', 'upload_pic_endpoint', $upload_url);
		sb_set_view_var('upload_endpoint', $upload_url);
		sb_set_view_var('user', $user);
		sb_set_view_var('image_url', $image_url);
		sb_set_view_var('questions', sb_get_security_questions());
	}
	public function task_login()
	{
		$this->GetDocument()->templateFile = 'login.php';
		sb_set_view('form-login');
	}
	public function task_profile()
	{
		$upload_url = SB_Route::_('index.php?mod=users&task=upload_image&update=1');
		sb_add_script(BASEURL . '/js/fineuploader/all.fine-uploader.min.js', 'fineuploader');
		sb_add_script(MOD_USERS_URL . '/js/frontend.js', 'user-frontend-js');
		sb_add_js_global_var('users', 'upload_pic_endpoint', $upload_url);
		sb_set_view_var('upload_endpoint', SB_Route::_('index.php?mod=users&task=upload_image&update=1'));
		$this->task_default();
	}
	public function task_save_profile()
	{
		if( !sb_is_user_logged_in() )
		{
			sb_redirect(SB_Route::_('index.php?mod=users'));
		}
		$user_id 		= SB_Request::getInt('user_id');
		$role_id 		= SB_Request::getInt('role_id');
		$first_name		= SB_Request::getString('first_name');
		$last_name 		= SB_Request::getString('last_name');
		//$email			= SB_Request::getString('email');
		$pwd			= SB_Request::getString('pwd');
		$notes			= SB_Request::getString('notes');
		$observations	= SB_Request::getString('observations');
		$image_file		= SB_Request::getString('imagefile');
		$error_url 		= SB_Request::getString('error_url');
		$meta			= (array)SB_Request::getVar('meta', array());
		if( empty($first_name) )
		{
			SB_MessagesStack::AddMessage('Debe ingresar los nombres.', 'error');
			$error_url ? sb_redirect($error_url) : $this->task_default();
			return false;
		}
		if( empty($last_name) )
		{
			SB_MessagesStack::AddMessage('Debe ingresar los apellidos.', 'error');
			$error_url ? sb_redirect($error_url) : $this->task_default();
			return false;
		}
		/*
		if( empty($email) )
		{
			SB_MessagesStack::AddMessage('Debe ingresar el email.', 'error');
			return false;
		}
		*/
		$user 		= new SB_User(sb_get_current_user()->user_id);
		$user_id	= $user->user_id;
		$user_dir	= UPLOADS_DIR . SB_DS . sb_build_slug($user->email);
		$dbh = SB_Factory::getDbh();
		/*
		$query = "SELECT user_id FROM users WHERE username = '$email' LIMIT 1";
		if( !$user_id && $dbh->Query($query) )
		{
			SB_MessagesStack::AddMessage('El nombre de usuario ya existe, elija uno diferente.', 'error');
			return false;
		}
		*/
		$cdate = date('Y-m-d H:i:s');
		$data = array(
				'first_name'				=> $dbh->EscapeString($first_name),
				'last_name'					=> $dbh->EscapeString($last_name),
				//'email'						=> $dbh->EscapeString($email),
				//'username'					=> $dbh->EscapeString($email),
				//'pwd'						=> md5($pwd),
				//'role_id'					=> $role_id,
				//'status'					=> 'enabled',
				'last_modification_date'	=> $cdate
		);
		if( !is_dir($user_dir) )
			mkdir($user_dir);
		/*
		if( file_exists(TEMP_DIR . SB_DS . $image_file) )
		{
			rename(TEMP_DIR . SB_DS . $image_file, $user_dir . SB_DS . $image_file);
		}
		*/
		if( !empty($pwd) )
		{
			$data['pwd'] = md5($pwd);
		}
		$dbh->Update('users', $data, array('user_id' => $user->user_id));
		//sb_update_user_meta($user_id, '_observations', $observations);
		//sb_update_user_meta($user_id, '_notes', $notes);
		sb_update_user_meta($user_id, '_birthday', SB_Request::getString('birthday'));
		sb_update_user_meta($user_id, '_address', SB_Request::getString('address'));
		sb_update_user_meta($user_id, '_city', SB_Request::getString('city'));
		sb_update_user_meta($user_id, '_state', SB_Request::getString('state'));
		sb_update_user_meta($user_id, '_country', SB_Request::getString('country'));
		foreach($meta as $meta_key => $meta_value)
		{
			sb_update_user_meta($user_id, $meta_key, $meta_value);
		}
		
		//sb_update_user_meta($user_id, '_image', $image_file);
		SB_Module::do_action('save_user', $user->user_id);
		//##update user
		SB_MessagesStack::AddMessage(SB_Text::_('Datos actualizados correctamente', 'users'), 'success');
		sb_redirect(SB_Route::_('index.php?mod=users'));
	}
	public function task_do_register()
	{
		$username 		= SB_Request::getString('username');
		$email			= SB_Request::getString('email');
		$pwd			= SB_Request::getString('pwd');
		$rpwd			= SB_Request::getString('rpwd');
		$redirect		= SB_Request::getString('redirect');
		$redirect_error	= SB_Request::getString('redirect_error');
		if( empty($username) )
		{
			sb_set_view('register');
			SB_MessagesStack::AddMessage(__('The username is empty', 'users'), 'error');
			return false;
		}
		if( empty($email) )
		{
			sb_set_view('register');
			SB_MessagesStack::AddMessage(__('The email is empty', 'users'), 'error');
			return false;
		}
		if( empty($pwd) )
		{
			sb_set_view('register');
			SB_MessagesStack::AddMessage(__('The password is empty', 'users'), 'error');
			return false;
		}
		if( empty($rpwd) )
		{
			sb_set_view('register');
			SB_MessagesStack::AddMessage(__('You must to retype your password', 'users'), 'error');
			return false;
		}
		//##check if username exists
		if( sb_get_user_by('username', $username) )
		{
			sb_set_view('register');
			SB_MessagesStack::AddMessage(__('The username already exists, choose a new one', 'users'), 'error');
			return false;
		}
		//##check if email exists
		if( sb_get_user_by('email', $email) )
		{
			sb_set_view('register');
			SB_MessagesStack::AddMessage(__('The username already exists, choose a new one', 'users'), 'error');
			return false;
		}
		if( $pwd != $rpwd )
		{
			sb_set_view('register');
			SB_MessagesStack::AddMessage(__('The passwords does not match', 'users'), 'error');
			return false;
		}
		if( !SB_Module::do_action('register_validation', true) )
		{
			return false;
		}
		$role = sb_get_user_role_by_key('user');
		$data = array(
				'username'					=> $username,
				'pwd'						=> md5($pwd),
				'email'						=> $email,
				'status'					=> 'enabled',
				'role_id'					=> $role->role_id,
				'last_modification_date'	=> date('Y-m-d H:i:s'),
				'creation_date'				=> date('Y-m-d H:i:s')
		);
		
		$id = sb_insert_user($data);
		SB_MessagesStack::AddMessage(__('Your user was registered correctly, please review your email for details', 'users'), 'success');
		sb_redirect($redirect ? $redirect : SB_Route::_('index.php?mod=users'));
	}
}
