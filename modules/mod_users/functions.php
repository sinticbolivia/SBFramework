<?php
use SinticBolivia\SBFramework\Classes\SB_Session;
use SinticBolivia\SBFramework\Classes\SB_Meta;
use SinticBolivia\SBFramework\Classes\SB_Module;
use SinticBolivia\SBFramework\Classes\SB_Factory;
use SinticBolivia\SBFramework\Classes\SB_Route;
use SinticBolivia\SBFramework\Modules\Users\Classes\SB_User;
use SinticBolivia\SBFramework\Classes\SB_MessagesStack;

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

function sb_insert_user($data, $send_email = true, $password_is_plain = true)
{
	
	
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
 * Check if current user has a session
 */
function sb_is_user_logged_in($cookie_name = null)
{
		
	$session_var = '';
	//$cookie_name = '';
	$timeout_var = '';
	if( $cookie_name === null )
	{
		if( defined('LT_ADMIN') )
		{
			$session_var = 'admin_user';
			$cookie_name = 'lt_session_admin';
			$timeout_var = 'admin_timeout';
		}
		else
		{
			$session_var = 'user';
			$cookie_name = 'lt_session';
			$timeout_var = 'timeout';
		}
	}
	else 
	{
		if( defined('LT_ADMIN') )
		{
			$session_var = 'admin_user';
			$timeout_var = 'admin_timeout';
		}
		else
		{
			$session_var = 'user';
			$timeout_var = 'timeout';
		}
	}
	$dbh 		= SB_Factory::getDbh();
    if( !isset($_COOKIE[$cookie_name]) )
    {
        //SinticBolivia\SBFramework\Classes\SB_MessagesStack::AddMessage(__('Your session has expired', 'users'), 'info');
        //##close expired sessions
        $dbh->Query("UPDATE user_sessions SET status = 'EXPIRED' WHERE expires < " . time());
        //$ctrl = SB_Module::GetControllerInstance('users');
        //$ctrl->task_logout();
        return false;
    }
    
    $session_id = $_COOKIE[$cookie_name];
    $session 	= $dbh->FetchRow("SELECT * FROM user_sessions WHERE id = '$session_id' ORDER BY creation_date DESC LIMIT 1");
    //print_r($session);die();
    if( !$session )
    {	
        return false;
    }
    //print_r($session);
    //die();
    if( $session->status == 'CLOSED' )
    {
    	return false;
    }
	if( $session->status == 'EXPIRED' )
    {
    	SinticBolivia\SBFramework\Classes\SB_MessagesStack::AddMessage(__('Your session has expired', 'users'), 'info');
    	return false;
    }
    //##check expiration 
    if( time() > (int)$session->expires )
    {
        //##mark session as expired
        $dbh->Update('user_sessions', array('status' => 'EXPIRED'), array('id' => $session_id));
        SinticBolivia\SBFramework\Classes\SB_MessagesStack::AddMessage(__('Your session has expired', 'users'), 'info');
        $ctrl = SB_Module::GetControllerInstance('users');
        $ctrl->task_logout();
        die();
    }
    ##check user is in session
    if( !SB_Session::getVar($session_var) )
    {
        $user = $dbh->FetchRow("SELECT * FROM users WHERE user_id = $session->user_id LIMIT 1");
        SB_Session::setVar($session_var, $user);
    }
    //##renew the session 
    $dbh->Update('user_sessions', array('expires' => time() + SESSION_EXPIRE), array('id' => $session_id));
    /*
    $user 		= SB_Session::getVar($session_var);
	$session 	= SB_Session::getVar($cookie_name);
	if( !$user || !$session )
	{
        SB_Factory::getApplication()->Log("No session found");
		return false;
	}
    
	##check session timeout
    SB_Module::do_action('sb_check_session_timout', $user, $session);
    */
	return true;
}
/**
 * Start the user session
 * 
 * @param object $user The user database record
 * @param string The user password (for logging purposes)
 */
function sb_user_start_session($user, $pwd = null, $type = 'frontend')
{
    $dbh = SB_Factory::getDbh();
    $sessionName    = $type == 'backend' ? 'lt_session_admin' : 'lt_session';
    $userVar        = $type == 'backend' ? 'admin_user' : 'user';
	//$cookie_value   = md5(serialize($user) . ':' . session_id());
    $session_id     = uniqid('sb-');
    setcookie($sessionName, $session_id, time() + SESSION_EXPIRE);
    //SB_Session::setVar($userVar, $user);
	//SB_Session::setVar($sessionName, $cookie_value);
	//SB_Session::setVar('timeout', time());
	//SB_Session::unsetVar('login_captcha');
	//SB_Session::unsetVar('inverse_captcha');
	//##mark user as logged in
	//sb_update_user_meta($user->user_id, '_logged_in', 'yes');
	//sb_update_user_meta($user->user_id, '_logged_in_time', time());
	//sb_update_user_meta($user->user_id, '_last_login', time());
    //sb_update_user_meta($user->user_id, '_timeout', time());
    //##create use session
    $session = array(
        'id'            => $session_id,
        'user_id'       => $user->user_id,
        'agent'         => $_SERVER['HTTP_USER_AGENT'],
        'ip_address'    => $_SERVER['REMOTE_ADDR'],
        'type'          => $type,
        'status'        => 'ACTIVE',
        'expires'       => time() + SESSION_EXPIRE,
        'creation_date' => date('Y-m-d H:i:s')
    );
    $dbh->Insert('user_sessions', $session);
    //##set user in session
    SB_Session::setVar($userVar, $user);
	SB_Module::do_action('authenticated', $user, $user->username, $pwd);
}
/**
 * Close the user session
 * @param SB_User $user The user object
 */
function sb_user_close_session($user)
{
    $dbh = SB_Factory::getDbh();
    $cookie_name    = null;
    $userVar        = null;
    if( defined('LT_ADMIN') )
    {
        $userVar        = 'admin_user';
        $cookie_name    = 'lt_session_admin';
    }
    else
    {
        $userVar        = 'user';
        $cookie_name    = 'lt_session';
    }
        
	if( $user && $user->user_id )
	{
		sb_update_user_meta($user->user_id, '_logged_in', 'no');
		sb_update_user_meta($user->user_id, '_logged_in_time', 0);
	}
    $session_id = $_COOKIE[$cookie_name];
    $dbh->Update('user_sessions', array('status' => 'CLOSED'), array('id' => $session_id));
    SB_Session::unsetVar($userVar);
    SB_Session::unsetVar($cookie_name);
	SB_Module::do_action('logout', $user);
	
}
