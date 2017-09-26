<?php
function sb_get_user_meta($user_id, $meta_key)
{
	return SB_Meta::getMeta('user_meta', $meta_key, 'user_id', $user_id);
}
function sb_add_user_meta($user_id, $meta_key, $meta_value)
{
	return SB_Meta::addMeta('user_meta', $meta_key, $meta_value, 'user_id', $user_id);
}
function sb_update_user_meta($user_id, $meta_key, $meta_value)
{
	if( $meta_value === null )
	{
		sb_remove_user_meta($user_id, $meta_key);
		return true;
	}
	return SB_Meta::updateMeta('user_meta', $meta_key, $meta_value, 'user_id', $user_id);
}
function sb_remove_user_meta($user_id, $meta_key)
{
	$dbh = SB_Factory::getDbh();
	$query = "DELETE FROM user_meta WHERE user_id = $user_id AND meta_key = '$meta_key'";
	$dbh->Query($query);
}
/**
 * 
 * @return SB_User
 */
function sb_get_current_user()
{
	//static $user =  null;
	$var_name = defined('LT_ADMIN') ? 'admin_user' : 'user';
	//if( $user == null )
	//{
		$user = new SB_User();
		$user->SetDbData(SB_Session::getVar($var_name));
		$user->GetDbPermissions();
	//}
	return $user;
	sb_is_user_logged_in();
}
function sb_get_user_image_url($user_id)
{
	
	$placeholder = sb_get_module_url('users') . '/images/nobody.png';
	
	if( is_numeric($user_id) && (int)$user_id > 0 )
		$user = new SB_User($user_id);
	else 
		$user = $user_id;
	
	if( !$user->user_id )
		return $placeholder;
	
	$user_dir = UPLOADS_DIR . SB_DS . sb_build_slug($user->username);
	$user_url = UPLOADS_URL . '/' . sb_build_slug($user->username);
	$image_filename = sb_get_user_meta($user->user_id, '_image');
	if( empty($image_filename) || !file_exists($user_dir . SB_DS . $image_filename) )
	{
		return $placeholder;
	}
	return $user_url . '/' . $image_filename;
}
function sb_get_user_roles()
{
	$dbh = SB_Factory::getDbh();
	$query = "SELECT * FROM user_roles";
	$dbh->Query($query);
	$roles = array();
	foreach($dbh->FetchResults() as $row)
	{
		$role = new SB_Role();
		$role->SetDbData($row);
		$roles[] = $role;
	}
	return $roles;
}
/**
 * Build a random password
 * 
 * @param int $length
 * @param string $type the charactes to use to build the password, set null to use whole characteres
 * 						letter|number|special
 * @return string
 */
function sb_gen_random_password($length = 8, $type = null) 
{
	$numbers = '1234567890';
	$letters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$special = '*[]{}-_$';
	$alphabet = '';
	if( $type == 'letter' )
	{
		$alphabet = $letters;
	}
	elseif( $type == 'number' )
	{
		$alphabet = $numbers;
	}
	elseif( $type == 'letter|number' )
	{
		$alphabet = $letters . $numbers;
	}
	elseif( $type == 'special' )
	{
		$alphabet = $special;
	}
	else
	{
		$alphabet = $numbers . $letters . $special;
	}
	$pass = array(); //remember to declare $pass as an array
	$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
	for ($i = 0; $i < $length; $i++) 
	{
		$n = rand(0, $alphaLength);
		$pass[] = $alphabet[$n];
	}
	return implode($pass); //turn the array into a string
}
function sb_get_user_by($by, $value)
{
	$dbh =  SB_Factory::getDbh();
	$user = null;
	if( $by == 'username' )
	{
		$query = sprintf("SELECT * FROM users WHERE username = '%s' LIMIT 1", $dbh->EscapeString(trim($value)));
		$row = $dbh->FetchRow($query);
		if( $row )
		{
			$user = new SB_User();
			$user->SetDbData($row);
		}
	}
	elseif( $by == 'email' )
	{
		$query = sprintf("SELECT * FROM users WHERE email = '%s' LIMIT 1", $dbh->EscapeString(trim($value)));
		$row = $dbh->FetchRow($query);
		if( $row )
		{
			$user = new SB_User();
			$user->SetDbData($row);
		}
	}
	else 
	{
		$user = new SB_User((int)$value);
		if( !$user->user_id )
		{
			$user = null;
		}
	}
	return $user;
}
function sb_get_user_role_by_key($key)
{
	return SB_Factory::getDbh()->FetchRow(sprintf("SELECT * FROM user_roles WHERE role_key = '%s' LIMIT 1", $key));
}
function sb_insert_user($data, $send_email = true, $password_is_plain = true)
{
	if( !isset($data['user_id']) && (!isset($data['username']) || empty($data['username'])) )
		throw new Exception(__('The username is empty', 'users'));
	if( !isset($data['email']) || empty($data['email']) )
		throw new Exception(__('The user email is empty', 'users'));
	if( !isset($data['user_id']) && sb_get_user_by('username', $data['username']) )
		throw new Exception(__('The username already exists', 'users'));
	$user_by_email = sb_get_user_by('email', $data['email']);
	
	//##for new users
	if( (!isset($data['user_id']) || !$data['user_id']) && $user_by_email )
	{
		throw new Exception(__('The email already exists', 'users'));
	}
	elseif( isset($data['user_id']) && $data['user_id'] && $user_by_email && $user_by_email->email != $data['email']  )
	{
		throw new Exception(__('The email already exists', 'users'));
	}
	$def_args = array(
			'first_name'	=> '',
			'last_name'		=> '',
			'role_id'		=> sb_get_user_role_by_key('user')->role_id,
			'status'		=> 'enabled'
	);
	$data = array_merge($def_args, $data);
	$dbh = SB_Factory::getDbh();
	SB_Module::do_action('before_insert_user', $data);
	if( !isset($data['user_id']) || !$data['user_id'] )
	{
		$pass = '';
		if( !isset($data['pwd']) || empty($data['pwd'])  )
		{
			$pass = sb_gen_random_password();
			$data['pwd'] = md5($pass);
		}
		else
		{
			$pass = $data['pwd'];
			if( $password_is_plain )
				$data['pwd'] = md5($data['pwd']);
		}
		
		$data['last_modification_date']	= date('Y-m-d H:i:s');
		$data['creation_date']			= date('Y-m-d H:i:s');
		$id 		= $dbh->Insert('users', $data);
		$user 		= new SB_User($id);
		$user_dir	= UPLOADS_DIR . SB_DS . sb_build_slug($user->username);
		if( !is_dir($user_dir) )
			mkdir($user_dir);
		if( $send_email )
		{
			$url = parse_url(BASEURL);
			//##send user email
			$body = sprintf(__("Hello %s<br/><br/>", 'users'), $user->username) .
			sprintf(__('Thanks for register into our website, you account details are below.<br/><br/>', 'users')).
			sprintf(__('Username: %s<br/>', 'users'), $user->username).
			sprintf(__('Password: %s<br/>', 'users'), $pass).
			'<br/>'.
			__('Follow the next link in order to start a session.<br/><br/>', 'users').
			sprintf(__('<a href="%s">Login</a><br/><br/>'), SB_Route::_('index.php?mod=users')).
			sprintf(__('Greetings<br/><br/>%s', 'users'), SITE_TITLE);
			$body 		= SB_Module::do_action('register_user_email_body', $body, $user, $pass);
			$subject 	= SB_Module::do_action('register_user_email_subject', sprintf(__('%s - User Registration', 'users'), SITE_TITLE));
			$headers = array(
					'Content-Type: text/html; charset=utf-8',
					sprintf("From: %s <no-reply@%s>", SITE_TITLE, $url['host'])
			);
			$subject 	= SB_Module::do_action('users_new_email_subject', $subject);
			$body 		= SB_Module::do_action('users_new_email_body', $body, 
												$data['username'], $data['email'], $pass, $data);
			$headers	= SB_Module::do_action('users_new_email_headers', $headers);
			
			lt_mail($user->email, $subject, $body, $headers);
		}
	}
	else
	{
		$user 		= new SB_User($data['user_id']);
		unset($data['username']);
		//##try to update the user password
		if( isset($data['pwd']) && !empty($data['pwd']) )
		{
			$data['pwd'] = md5($data['pwd']);
		}
		$dbh->Update('users', $data, array('user_id' => $user->user_id));
		$id = $user->user_id;
	}
	SB_Module::do_action('after_insert_user', $id, $data);
	return $id;
}
function sb_get_security_questions()
{
	return SB_Module::do_action('security_questions', $questions = array(
				'first_pet'		 	=> __('Which is the name if your first pet?', 'users'),
				'first_school'		=> __('Which is the name if your first school?', 'users'),
				'best_friend' 		=> __('Which is the name if your best friend?', 'users'),
		));
}
/**
 * Start the user session
 * 
 * @param object $user The user database record
 * @param string The user password (for logging purposes)
 */
function sb_user_start_session($user, $pwd = null)
{
	SB_Session::setVar('user', $user);
	$cookie_value = md5(serialize($user) . ':' . session_id());
	SB_Session::setVar('lt_session', $cookie_value);
	SB_Session::setVar('timeout', time());
	SB_Session::unsetVar('login_captcha');
	SB_Session::unsetVar('inverse_captcha');
	//##mark user as logged in
	sb_update_user_meta($user->user_id, '_logged_in', 'yes');
	sb_update_user_meta($user->user_id, '_logged_in_time', time());
	sb_update_user_meta($user->user_id, '_last_login', time());
	SB_Module::do_action('authenticated', $user, $user->username, $pwd);
}
/**
 * Close the user session
 * @param SB_User $user The user object
 */
function sb_user_close_session($user)
{
	if( $user && $user->user_id )
	{
		sb_update_user_meta($user->user_id, '_logged_in', 'no');
		sb_update_user_meta($user->user_id, '_logged_in_time', 0);
	}
	SB_Module::do_action('logout', $user);
	SB_Session::unsetVar('user');
	SB_Session::unsetVar('lt_session');
	SB_Session::unsetVar('timeout');
}