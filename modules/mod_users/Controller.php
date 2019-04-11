<?php
namespace SinticBolivia\SBFramework\Modules\Users;
use SinticBolivia\SBFramework\Modules\Users\Controllers\ControllerBase;
use SinticBolivia\SBFramework\Modules\Users\Classes\SB_User;
use SinticBolivia\SBFramework\Classes\SB_Request;
use SinticBolivia\SBFramework\Classes\SB_MessagesStack;
use SinticBolivia\SBFramework\Classes\SB_Route;
use SinticBolivia\SBFramework\Classes\SB_Module;
use SinticBolivia\SBFramework\Classes\SB_Session;
use Exception;
use SinticBolivia\SBFramework\Modules\Users\Entities\SB_Role;
use SinticBolivia\SBFramework\Modules\Users\Models\UsersModel;

//require_once MOD_USERS_DIR . SB_DS . 'controllers' . SB_DS . 'base.controller.php';
class Controller extends ControllerBase
{
	/**
	 * 
	 * @var \SinticBolivia\SBFramework\Modules\Users\Models\UsersModel
	 */
	protected	$usersModel;
	
	public function task_do_login()
	{
		$username 	= SB_Request::getString('username');
		$pwd		= SB_Request::getString('pwd');
		$captcha	= SB_Request::getString('captcha');
		$r 			= SB_Request::getString('redirect');
		$rf 		= SB_Request::getString('redirect_error');
		
		$user = $this->usersModel->GetBy('username', $username);
		$error_link = $rf ? $rf : SB_Route::_('index.php?mod=users');
		if( !$user )
		{
			SB_Module::do_action('authenticate_error', null, $username, $pwd);
			SB_MessagesStack::AddMessage(__('Username or password invalid', 'users'), 'error');
			sb_redirect($error_link);
		}
		if( $user->pwd != md5($pwd) )
		{
			SB_Module::do_action('authenticate_error', $user, $username, $pwd);
			SB_MessagesStack::AddMessage(__('Username or password invalid', 'users'), 'error');
			sb_redirect($error_link);
		}
		$form_captcha 	= SB_Session::getVar('login_captcha');
		if( SB_Session::getVar('inverse_captcha') )
			$form_captcha	= substr($form_captcha, 3) . substr($form_captcha, 0, 3);
	
		if( $captcha != $form_captcha )
		{
			//var_dump("$captcha != $form_captcha");die();
			//##remove captcha
			SB_Session::unsetVar('login_captcha');
			SB_Module::do_action('authenticate_error', $user, $username, $pwd);
			SB_MessagesStack::AddMessage(__('The security text is invalid', 'users'), 'error');
			sb_redirect($error_link);
		}
		//print_r($row);die();
		if( $user->role_id !== 0 )
		{
			if( $user->role_key == 'possible' || $user->role->role_key == 'bloqued' )
			{
				SB_MessagesStack::AddMessage(__('Your username can\'t start session.', 'users'), 'error');
				sb_redirect($error_link);
				return false;
			}
			if( (int)sb_get_user_meta($user->user_id, '_no_login') == 1 )
			{
				sb_redirect(SB_Route::_('login-error.html'));
			}
		}
		$session = SB_Module::do_action('authenticate_session', true);
		if( !$session )
		{
			sb_redirect($error_link);
		}
		//remove captcha value from session
		SB_Session::unsetVar('login_captcha');
		sb_user_start_session($user);
		$r = SB_Module::do_action('users_login_redirect_link', $r);
		if( $r )
		{
			sb_redirect($r);
			die();
		}
		sb_redirect(SB_Route::_('index.php?mod=users'));
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
		
		$query = "SELECT user_id from user_meta WHERE meta_key = '_recover_pwd_hash' AND meta_value = '$hash' LIMIT 1";
		if( !$this->dbh->Query($query) )
		{
			SB_MessagesStack::AddMessage(__('Error trying to recover the password.', 'users'), 'error');
			sb_redirect(SB_Route::_('index.php?mod=users&view=recover_pwd'));
			return false;
		}
		$row = $this->dbh->FetchRow();
		$pwd = SB_Request::getString('pwd');
		$rpwd = SB_Request::getString('rpwd');
		if( $pwd != $rpwd )
		{
			SB_MessagesStack::AddMessage(__('The passwords does not match.', 'users'), 'error');
			sb_redirect(SB_Route::_('index.php?mod=users&view=recover_pwd&hash='.$hash));
		}
		sb_update_user_meta($row->user_id, '_recover_pwd_hash', null);
		$this->dbh->Update('users', array('pwd' => md5($pwd)), array('user_id' => $row->user_id));
		SB_MessagesStack::AddMessage(__('Your password has been updated.', 'users'), 'success');
		SB_Module::do_action('users_password_updated', $row, $pwd);
		sb_redirect(SB_Route::_('index.php?mod=users'));
	}
	public function task_default()
	{
		if( !sb_is_user_logged_in() )
		{
			//$this->GetDocument()->templateFile = 'login.php';
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
		$countries 	= sb_include('countries.php', 'file');
		$upload_url = SB_Route::_('index.php?mod=users&task=upload_image&update=1');
		sb_add_script(BASEURL . '/js/fineuploader/all.fine-uploader.min.js', 'fineuploader');
		sb_add_script(MOD_USERS_URL . '/js/frontend.js', 'user-frontend-js');
		sb_add_js_global_var('users', 'upload_pic_endpoint', $upload_url);
		$this->SetVars(get_defined_vars());
		
		$title = __('My Account', 'users');
		$this->document->SetTitle($title);
	}
	public function task_login()
	{
		//if( lt_is_admin() )
			//$this->GetDocument()->templateFile = 'login.php';
		sb_set_view('form-login');
	}
	public function task_profile()
	{
		$this->task_default();
		
	}
	public function task_save_profile()
	{
		if( !sb_is_user_logged_in() )
		{
			sb_redirect(SB_Route::_('index.php?mod=users'));
		}
		$user			= sb_get_current_user();
		$first_name		= SB_Request::getString('first_name');
		$last_name 		= SB_Request::getString('last_name');
		$email			= SB_Request::getString('email');
		$pwd			= SB_Request::getString('pwd');
		$image_file		= SB_Request::getString('imagefile');
		$error_url 		= SB_Request::getString('error_url');
		$meta			= (array)$this->request->getVar('meta', array());
		try
		{
			$oldUser	= \SinticBolivia\SBFramework\Modules\Users\Entities\SB_User::Get($user->user_id);
			if( !$oldUser)
				throw new Exception(__('The user does not exists', 'users'));
			if( empty($first_name) )
				throw new Exception(__('You must enter your firstname.', 'users'));
			if( empty($last_name) )
				throw new Exception(__('You must enter your lastname.', 'users'));
			
			
			$oldUser->first_name 	= $first_name;
			$oldUser->last_name		= $last_name;
			if( empty($oldUser->email) && !empty($email) )
			{
				$oldUser->email = $email;
			}
			if( !empty($pwd) && md5($pwd) != $oldUser->pwd )
			{
				$oldUser->pwd = md5($pwd);
			}
			
			/*
			$user_dir	= UPLOADS_DIR . SB_DS . sb_build_slug($user->email);
			if( !is_dir($user_dir) )
				mkdir($user_dir);
			*/
			$oldUser = $oldUser->Save();
			
			sb_update_user_meta($oldUser->user_id, '_birthday', $this->request->getDate('birthday'));
			sb_update_user_meta($oldUser->user_id, '_address', $this->request->getString('address'));
			sb_update_user_meta($oldUser->user_id, '_city', $this->request->getString('city'));
			sb_update_user_meta($oldUser->user_id, '_state', $this->request->getString('state'));
			sb_update_user_meta($oldUser->user_id, '_country', $this->request->getString('country'));
			
			foreach($meta as $meta_key => $meta_value)
			{
				sb_update_user_meta($oldUser->user_id, $meta_key, $meta_value);
			}
			//sb_update_user_meta($user_id, '_image', $image_file);
			SB_Module::do_action('save_user', $oldUser->user_id);
			//##update user
			SB_MessagesStack::AddMessage(__('Datos actualizados correctamente', 'users'), 'success');
			sb_redirect($this->Route('index.php?mod=users'));
		}
		catch(Exception $e)
		{
			SB_MessagesStack::AddError($e->getMessage());
			$error_url ? sb_redirect($error_url) : $this->task_default();
			return false;
		}
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
		$role = SB_Role::GetBy('role_key', 'user');
		$data = (object)array(
				'username'					=> $username,
				'pwd'						=> $pwd,
				'email'						=> $email,
				'status'					=> 'enabled',
				'role_id'					=> $role->role_id,
				'last_modification_date'	=> date('Y-m-d H:i:s'),
				'creation_date'				=> date('Y-m-d H:i:s')
		);
		
		//$id = sb_insert_user($data);
		$user = $this->usersModel->Create($data);
		SB_MessagesStack::AddMessage(__('Your user was registered correctly, please review your email for details', 'users'), 'success');
		sb_redirect($redirect ? $redirect : SB_Route::_('index.php?mod=users'));
	}
}
