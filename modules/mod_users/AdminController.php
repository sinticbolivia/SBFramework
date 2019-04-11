<?php
namespace SinticBolivia\SBFramework\Modules\Users;

use SinticBolivia\SBFramework\Classes\SB_Controller;
use SinticBolivia\SBFramework\Classes\SB_Session;
use SinticBolivia\SBFramework\Classes\SB_Request;
use SinticBolivia\SBFramework\Classes\SB_Factory;
use SinticBolivia\SBFramework\Classes\SB_Route;
use SinticBolivia\SBFramework\Classes\SB_Module;
use SinticBolivia\SBFramework\Modules\Users\Classes\SB_User;
use SinticBolivia\SBFramework\Modules\Users\Classes\SB_Role;
use SinticBolivia\SBFramework\Modules\Users\Models\UsersModel;
use SinticBolivia\SBFramework\Modules\Users\Models\RolesModel;
use SinticBolivia\SBFramework\Classes\SB_Text;
use SinticBolivia\SBFramework\Classes\SB_Text as SBText;
use SinticBolivia\SBFramework\Classes\SB_MessagesStack;
use SinticBolivia\SBFramework\Classes\SB_Image;
use SinticBolivia\SBFramework\Classes\SB_TableList;
use SinticBolivia\SBFramework\Modules\Users\Controllers\ControllerBase;
use Exception;

class AdminController extends ControllerBase
{
    /**
     * @namespace SinticBolivia\SBFramework\Modules\Users\Models
     * @var UsersModel
     */
    protected $usersModel;
    /**
     * @namespace SinticBolivia\SBFramework\Modules\Users\Models
     * @var RolesModel
     */
    protected $rolesModel;
    
	public function task_default()
	{
        
		$user = sb_get_current_user();
		if( !$user->can('manage_users') )
		{
			die('You dont have enough permissions');
		}
		$keyword = SB_Request::getString('keyword');
		$role_id	= $this->request->getInt('role_id');
		$role_key	= $this->request->getString('role_key');
		
		$columns = array();
		if( $this->dbh->db_type == 'mysql' )
		{
			$columns = array(
					'u.*, CONCAT(u.first_name, \' \', u.last_name) AS name', 
					'r.role_name',
					'r.role_key'
			);
		}
		elseif( $this->dbh->db_type == 'sqlite3' )
		{
			$columns = array(
					'u.*, (u.first_name || \' \' || u.last_name) AS name', 
					'r.role_name',
					'r.role_key'
			);
		}
		$tables = array(
				'users u LEFT JOIN user_roles r ON u.role_id = r.role_id',
				//'user_roles r'
		);
		$where = array(
				"(username <> 'root' OR username is null)",
				//'u.role_id = r.role_id'
		);
		if( $role_id > 0 )
		{
			$where[] = "u.role_id = $role_id ";
		}
		if( $role_key )
		{
			$where[] = "r.role_key = '$role_key'";
		}
		if( $keyword )
		{
			$where[] = "u.username LIKE '%$keyword%'";
		}
		
		//##if user is different than root, just get their users
		if( !$user->IsRoot() && !$user->can('see_all_users') )
		{
			$tables[] = 'user_meta um';
			$where[] = 'u.user_id = um.user_id';
			$where[] = 'um.meta_key = "_owner_id"';
			$where[] = 'um.meta_value = "'.sb_get_current_user()->user_id.'"';
		}
		$order      = SB_Request::getString('order', 'desc');
		$order_by   = SB_Request::getString('order_by', 'creation_date');
		$query      = sprintf("SELECT %s FROM %s WHERE %s ORDER BY %s $order", 
						implode(',', $columns), 
						implode(',', $tables), 
						implode(' AND ', $where),
						$order_by
				);
		$res    = $this->dbh->Query($query);
		$users  = $this->dbh->FetchResults();
		
		$new_order = $order == 'desc' ? 'asc' : 'desc';
		$username_order_link 	= 'index.php?mod=users&order_by=username&order='.$new_order;
		$name_order_link 		= 'index.php?mod=users&order_by=name&order='.$new_order;
		$email_order_link		= 'index.php?mod=users&order_by=email&order='.$new_order;
		$rol_order_link         = 'index.php?mod=users&order_by=role_name&order='.$new_order;
        $title = __('Users Management', 'users');
        $this->document->SetTitle($title);
		sb_set_view_var('questions', sb_get_security_questions());
		sb_set_view_var('username_order_link', SB_Route::_($username_order_link));
		sb_set_view_var('name_order_link', SB_Route::_($name_order_link));
		sb_set_view_var('email_order_link', SB_Route::_($email_order_link));
		sb_set_view_var('rol_order_link', SB_Route::_($rol_order_link));
		sb_set_view_var('users', $users);
	}
	public function task_do_login()
	{
		$captcha 		= SB_Session::getVar('login_captcha');
		$user_captcha	= SB_Request::getString('captcha', null);
		$username 		= SB_Request::getString('username');
		$pwd			= SB_Request::getString('pwd');
		$ajax			= SB_Request::getInt('ajax');
		$redirect		= SB_Request::getString('redirect');
		
        
		$username 		= $this->dbh->EscapeString($username);
		
		$query 			= "SELECT u.*,r.role_key ".
							"FROM users u ".
							"LEFT JOIN user_roles r ON r.role_id = u.role_id ".
							"WHERE u.username = '$username' LIMIT 1";
		$rows 			= $this->dbh->Query($query);
		
		$captcha		= substr($captcha, 3) . substr($captcha, 0, 3);
		$error_link 	= SB_Route::_('index.php');
		if($ajax) 
			sleep(1);
		//var_dump("if( $captcha && $user_captcha != $captcha  )");die(__FILE__);
		
		if( $captcha && $user_captcha != $captcha  )
		{
			$msg = __('Invalid text security.', 'users');
			if($ajax) 
				sb_response_json(array('status' => 'error', 'error' => $msg)); 
			SB_MessagesStack::AddMessage($msg, 'error');
			return false;
		}
		if( $rows <= 0 )
		{
			$msg = __('Invalid username or password', 'users'); 
			if($ajax) 
				sb_response_json(array('status' => 'error', 'error' => $msg));
			SB_MessagesStack::AddMessage($msg, 'error');
			return false;
		}
		$row = $this->dbh->FetchRow();
	
		if( $row->pwd != md5($pwd) )
		{
			$msg = SBText::_('Invalid username or password', 'users');
			if($ajax) 
				sb_response_json(array('status' => 'error', 'error' => $msg));
			SB_MessagesStack::AddMessage($msg, 'error');
			return false;
		}
		if( $row->role_id !== 0 )
		{
			$msg = SBText::_('You are not able to start a session, please contact with administrator.', 'users');
			if( $row->role_key == 'possible' || $row->role_key == 'bloqued' )
			{
				if($ajax)
					sb_response_json(array('status' => 'error', 'error' => $msg)); 
				SB_MessagesStack::AddMessage($msg, 'error');
				return false;
			}
			if( (int)sb_get_user_meta($row->user_id, '_no_login') == 1 )
			{
				if($ajax)
					sb_response_json(array('status' => 'error', 'error' => $msg)); 
				SB_MessagesStack::AddMessage($msg, 'info');
				sb_redirect(SB_Route::_('login.php'));
			}
		}
		$session = SB_Module::do_action('authenticate_session', $bool = true);
		if( !$session )
		{
			if( $ajax )
				sb_response_json(array('status' => 'error', 'error' => __('Authentication failed', 'users')));
			sb_redirect($error_link);
		}

        /*
		//var_dump(defined('LT_ADMIN'));print_r($row);die();
		$cookie_name = '';
		if( defined('LT_ADMIN') )
		{
			SB_Session::setVar('admin_user', $row);
			$cookie_name = 'lt_session_admin';
		}
		else
		{
			SB_Session::setVar('user', $row);
			$cookie_name = 'lt_session';
		}
		$cookie_value = md5(serialize($row) . ':' . session_id());
        */
		/*
		if( isset($_COOKIE[$cookie_name]) )
		{
			unset($_COOKIE[$cookie_name]);
			setcookie($cookie_name, '', time() - 3600);
		}
		*/
        /*
		SB_Session::setVar($cookie_name, $cookie_value);
		SB_Session::setVar('admin_timeout', time());
        sb_update_user_meta($row->user_id, '_timeout', time());
		//setcookie($cookie_name, $cookie_value, time() + (15 * 60), '/');
        */
        sb_user_start_session($row, $pwd, 'backend');
        
		$redirect = empty($redirect) ? SB_Route::_('index.php') : $redirect;
		
		if($ajax)  
		{
            $res = array('status' => 'ok', 'redirect' => $redirect);
            sb_response_json($res);
        }
		sb_redirect($redirect);
	}
	public function task_new_user()
	{
		$current_user = sb_get_current_user();
		if( !$current_user->can('create_user') )
		{
			die('You dont have enough permissions');
		}
		sb_include_module_helper('users');
		
		$roles = array();
		if( $current_user->IsRoot() || ($current_user->can('manage_roles') && $current_user->can('manage_users') )  )
		{
			foreach(LT_HelperUsers::GetUserRoles() as $r)
			{
				//if( !$current_user->can('create_role_'.$r->role_key) ) continue;
				$roles[] = $r;
			}
		}
		$countries 	= sb_include('countries.php', 'file');
		$title		= __('Create New User', 'users');
		sb_set_view('edit_user');
		sb_add_script(BASEURL . '/js/fineuploader/all.fine-uploader.min.js', 'fineuploader');
		sb_set_view_var('upload_endpoint', SB_Route::_('index.php?mod=users&task=upload_image'));
		sb_set_view_var('roles', $roles);
		sb_set_view_var('countries', $countries);
		sb_set_view_var('title', $title);
		$this->document->SetTitle($title);
		SB_Module::do_action('on_create_new_user');
	}
	public function task_edit_user()
	{
		$current_user	= sb_get_current_user();
		$user_id 		= SB_Request::getInt('id');
		if( !$user_id )
		{
			SB_MessagesStack::AddMessage(__('User identifier is invalid'), 'users');
			sb_redirect(SB_Route::_('index.php?mod=users'));
		}
		
		if( !$current_user->IsRoot() && $current_user->user_id != $user_id && !$current_user->can('edit_user') )
		{
			lt_die(__('You dont have enough permissions', 'users'));
		}
		sb_include_module_helper('users');
		$user 	= new SB_User($user_id);
		if( !$user->user_id )
		{
			SB_MessagesStack::AddMessage(__('The user does not exists'), 'users');
			sb_redirect(SB_Route::_('index.php?mod=users'));
		}
		
		//$c_user = sb_get_current_user();
		if( !$current_user->IsRoot() && !$current_user->can('see_all_users') )
		{
			$error = __('You can\'t edit the requested user', 'users');
			if( $current_user->user_id != $user_id && $current_user->user_id != $user->_owner_id )
			{
				SB_MessagesStack::AddMessage($error, 'error');
				sb_redirect(SB_Route::_('index.php?mod=users'));
			}
			
		}
	
		$roles = array();
		if( $current_user->IsRoot() || ($current_user->can('manage_roles') && $current_user->can('manage_users') )  )
		{
			foreach(LT_HelperUsers::GetUserRoles() as $r)
			{
				//if( !$user->can('create_role_'.$r->role_key) ) continue;
				$roles[] = $r;
			}
		}
		
		//##add just the current user role
		if( $current_user->user_id == $user_id || empty($roles) )
		{
			$roles[] = new SB_Role($user->role_id);
		}
		$countries	= sb_include('countries.php', 'file');
		$title		= __('Edit User', 'users');
		$this->document->SetTitle($title);
		sb_add_script(BASEURL . '/js/fineuploader/all.fine-uploader.min.js', 'fineuploader');
		sb_set_view_var('user_id', $user_id);
		sb_set_view_var('user', $user);
		sb_set_view_var('roles', $roles);
		sb_set_view_var('countries', $countries);
		sb_set_view_var('user_dir', UPLOADS_DIR . SB_DS . sb_build_slug($user->username));
		sb_set_view_var('user_url', UPLOADS_URL . '/' . sb_build_slug($user->username));
		sb_set_view_var('title', $title);
		sb_set_view_var('upload_endpoint', SB_Route::_('index.php?mod=users&task=upload_image&user_id='.$user_id));
	}
	public function task_save_user()
	{
		$user_id 		= $this->request->getInt('user_id');
		$role_id 		= $this->request->getInt('role_id');
		$first_name		= $this->request->getString('first_name');
		$last_name 		= $this->request->getString('last_name');
		$username		= $this->request->getString('username');
		$email			= $this->request->getString('email');
		$pwd			= $this->request->getString('pwd');
		$notes			= $this->request->getString('notes');
		$observations	= $this->request->getString('observations');
		$image_file		= $this->request->getString('imagefile');
        
		$user_dir		= UPLOADS_DIR . SB_DS . sb_build_slug($email);
		
		if( !sb_is_user_logged_in() )
		{
			sb_redirect($this->Route('index.php'));
		}
		$current_user = sb_get_current_user();
		//##check if current user is updated its profile
		if( !$current_user->IsRoot() && (int)$current_user->user_id !== (int)$user_id && !$current_user->can('manage_users') )
		{
			lt_die(__('You dont have enough permissions', 'users'));
		}
		
        try
        {
            if( empty($first_name) )
                throw new Exception(__('Your need enter the user firstname.', 'users'));
        	if( empty($last_name) )
                throw new Exception(__('You need to enter the user lastname.', 'users'));
            if( !$user_id && empty($username) )
            {
                throw new Exception(__('You need to enter a username.', 'users'));
            }
            if( !$current_user->IsRoot() && (!$role_id || $role_id <= 0) )
                throw new Exception(__('You cant use this for role for the user.', 'users'));
            //##check if user is editing another user
            if( (int)$current_user->user_id !== (int)$user_id )
            {
                if( !$user_id && !$current_user->can('create_user') )
                {
                    lt_die(__('You dont have enough permissions to create an user', 'users'));
                }
                if( $user_id && !$current_user->can('edit_user') )
                {
                    lt_die(__('You dont have enough permissions to update an user', 'users'));
                }
            }
            if( !$current_user->IsRoot() && $role_id == 0 )
                throw new Exception(__('You can\' assign this user role', 'users'));
            
            if( $role_id > 0 )
            {
                $role = $this->rolesModel->Get($role_id);
                if( !$role || !$role->role_id )
                    throw new Exception(__('The user role does not exists', 'users'));
                if( $current_user->user_id != $user_id && !$current_user->can('manage_roles') )
                {
                    throw new Exception(__('You are not authorized to assign this user role', 'users'));
                }
            }
            
            $data = array(
                    'user_id'       => $user_id,
                    'first_name'    => $first_name,
                    'last_name'		=> $last_name,
                    'username'      => $username,
                    'email'			=> $email,
                    'pwd'			=> $pwd,
                    'role_id'		=> $role_id,
                    'status'		=> 'enabled',
                    'meta'          => array()
            );
           
            //##assign user meta
            $data['meta'] = array(
                '_owner_id'         => $current_user->user_id,
                '_image'            => $image_file,
                '_observations'     => $observations,
                '_notes'            => $notes,
                '_birthday'         => $this->request->getString('birthday'),
                '_address'          => $this->request->getString('address'),
                '_city'             => $this->request->getString('city'),
                '_state'            => $this->request->getString('state'),
                '_country'          => $this->request->getString('country'),
                '_no_login'         => $this->request->getInt('no_login')
            );
            $user = $this->usersModel->Save($data);
            
            $user_dir   = $user->GetDirectory();
            if( $user_dir && !is_dir($user_dir) )
                mkdir($user_dir);
            if( is_file(TEMP_DIR . SB_DS . $image_file) )
            {
                @rename(TEMP_DIR . SB_DS . $image_file, $user_dir . SB_DS . $image_file);
            }
            $msg    = $user_id ? __('The user has been updated', 'users') : __('The user has been created successfully', 'users');
            $link   = $user_id ? $this->Route('index.php?mod=users&view=edit_user&id='.$user->user_id) : $this->Route('index.php?mod=users');
            
			SB_MessagesStack::AddMessage($msg, 'success');
			sb_redirect($link); 
        }
        catch(Exception $e)
        {
            sb_set_view('edit_user');
            sb_set_view_var('title', $user_id ? __('Edit User', 'users') : __('Create New User', 'users'));
            SB_MessagesStack::AddError($e->getMessage());
            if( $user_id )
            {
                $this->task_edit_user();
            }
            else
            {
                $this->task_new_user();
            }
        }
	}
	public function task_delete_user()
	{
		if( !sb_get_current_user()->can('manage_users') )
		{
			lt_die('You dont have enough permissions');
		}
		if( !sb_get_current_user()->can('delete_user') )
		{
			lt_die('You dont have enough permissions');
		}
		$user_id = $this->request->getInt('id');
		
        try
        {
            if( !$user_id )
            {
                throw new Exception($this->__('The user identifier is invalid', 'users'));
            }
            $this->usersModel->Delete($user_id);
            
            SB_MessagesStack::AddMessage(__('The user has been deleted.', 'users'), 'success');
            sb_redirect(SB_Route::_('index.php?mod=users'));
        }
        catch(Exception $e)
        {
            SB_MessagesStack::AddMessage($e->getMessage(), 'error');
			sb_redirect(SB_Route::_('index.php?mod=users'));
        }
	}
	public function task_upload_image()
	{
		require_once INCLUDE_DIR . SB_DS . 'qqFileUploader.php';
		$uh = new \qqFileUploader();
		$uh->allowedExtensions = array('jpg', 'jpeg', 'gif', 'png', 'bmp');
		// Specify max file size in bytes.
		//$uh->sizeLimit = 10 * 1024 * 1024; //10MB
		// Specify the input name set in the javascript.
		$uh->inputName = 'qqfile';
		// If you want to use resume feature for uploader, specify the folder to save parts.
		$uh->chunksFolder = 'chunks';
		$res = $uh->handleUpload(TEMP_DIR);
		$file_path = TEMP_DIR . SB_DS . $uh->getUploadName(); 
		sb_include('class.image.php');
		$img = new SB_Image($file_path);
		try 
		{
			//if( $img->getWidth() > 150 || $img->getHeight() > 150)
			//{
				$img->resizeImage(150, 150);
				$img->save($file_path);
			//}
			$res['uploadName'] = $uh->getUploadName();
			$res['image_url'] = BASEURL . '/temp/' . $res['uploadName'];
			
			if( $user_id = SB_Request::getInt('user_id') )
			{
				//sb_update_user_meta($user_id, '', $meta_value);
			}
		}
		catch(Exception $e)
		{
			$res['status']	= 'error';
			$res['error'] = $e->getMessage();
		}
		
		
		die(json_encode($res));
	}
	public function task_online_users()
	{
		$page 		= SB_Request::getInt('page', 1);
		$order_by 	= SB_Request::getString('order_by', 'last_login');
		$order 		= SB_Request::getString('order', 'desc');
		$dbh 		= SB_Factory::getDbh();
		$limit		= SB_Request::getInt('limit', defined('ITEMS_PER_PAGE') ? ITEMS_PER_PAGE : 25);
		
		$query = "SELECT {columns} ".
					"FROM users u, user_meta um, user_meta umt ".
					"WHERE 1 = 1 " . 
					"AND u.user_id = um.user_id ".
					"AND um.meta_key = '_logged_in' " . 
					"AND um.meta_value = 'yes' ".
					"AND u.user_id = umt.user_id ".
					"AND umt.meta_key = '_timeout' ".
					sprintf("AND ((%d - CAST(umt.meta_value AS UNSIGNED)) < %d) ", time(), SESSION_EXPIRE);
		
		$dbh = SB_Factory::getDbh();
		$dbh->Query(str_replace('{columns}', 'COUNT(u.user_id) AS total_rows', $query));
		$total_rows 	= $dbh->FetchRow()->total_rows;
		$total_pages 	= ceil($total_rows / $limit);
		$offset 		= $page <= 1 ? 0 : ($page - 1) * $limit;
		$sub_query0		= "SELECT um0.meta_value FROM user_meta um0 WHERE um0.meta_key = '_logged_in_time' AND um0.user_id = u.user_id";
		//$columns		= "u.*, ($sub_query0) AS last_login";
		$columns		= "u.*, umt.meta_value AS last_login";
		$query 			= str_replace('{columns}', $columns, $query);
		$query_order 	= "ORDER BY $order_by $order";
		$query_limit	= "LIMIT $offset, $limit";
		//print $query;
		$dbh->Query(str_replace('{columns}', $columns, $query) . "$query_order $query_limit");
		$users = $dbh->FetchResults();
		$new_order = $order == 'desc' ? 'asc' : 'desc';
		sb_set_view_var('id_order_link', SB_Route::_('index.php?mod=users&view=online_users&order_by=user_id&order='.$new_order));
		sb_set_view_var('username_order_link', SB_Route::_('index.php?mod=users&view=online_users&order_by=username&order='.$new_order));
		sb_set_view_var('order_email_link', SB_Route::_('index.php?mod=users&view=online_users&order_by=email&order='.$new_order));
		sb_set_view_var('order_first_name_link', SB_Route::_('index.php?mod=users&view=online_users&order_by=first_name&order='.$new_order));
		sb_set_view_var('order_last_name_link', SB_Route::_('index.php?mod=users&view=online_users&order_by=last_name&order='.$new_order));
		sb_set_view_var('order_last_login_link', SB_Route::_('index.php?mod=users&view=online_users&order_by=last_login&order='.$new_order));
		sb_set_view_var('users', $users);
	}
	public function task_export()
	{
		if( !sb_is_user_logged_in() )
		{
			lt_die(__('You need to start a session', 'users'));
		}
		$type = SB_Request::getInt('type', 'txt');
		$query = "SELECT * FROM users ORDER BY last_name ASC";
		$users = $this->dbh->FetchResults($query);
		if( $type == 'txt' )
		{
			$txt = '';
			foreach($users as $u)
			{
				$txt .= sprintf("%s;%s;%s\n", $u->first_name, $u->last_name, $u->email);
			}
			$filename = sprintf(__('users-%s.txt', 'users'), sb_format_date(time()));
			header('Content-Type: text/plain');
			header("Content-Transfer-Encoding: Binary"); 
			header("Content-disposition: attachment; filename=\"" . $filename . "\"");
			die($txt);
		}
	}
    public function task_sessions()
    {
        $user = sb_get_current_user();
        try
        {
            if( !$user->can('users_see_sessions') )
                throw new Exception(__('You dont have enough permissions', 'users'));
            $table = new SB_TableList('user_sessions', 'id', 'users');
            $table->SetColumns(array(
                'id'        => array('label' => 'ID'),
                'user_id'   => array('label' => 'User Id'),
                'agent'     => array('label' => 'Agent'),
                'ip_address'    => array('label' => __('IP Address', 'users')),
                'type'          => array('label' => __('Type', 'users')),
                'status'        => array(
					'label' 	=> __('Status', 'uers'),
					'callback'	=> function($item)
					{
						$class = 'label label-';
						if( $item->status == 'ACTIVE')
							$class.= 'success';
						if( $item->status == 'EXPIRED')
							$class.= 'warning';
						if( $item->status == 'CLOSED')
							$class.= 'danger';
						return "<span class=\"$class\">{$item->status}</span>";
					}
				),
                'expires'       => array(
                    'label' => __('Expires', 'users'), 
                    'callback' => function($item)
                    {
                        return sb_format_datetime($item->expires);
                    }
                ),
                'creation_date' => array('label' => __('Creation Date', 'users'))
            ));
            $table->Fill();
            $table->SetRowActions(array(
                'disconnect' => array('link' => '#', 'label' => __('Disconnect user', 'users'), 'icon' => 'glyphicon glyphicon-off')
            ));
            $title = __('User Sessions', 'users');
            $this->document->SetTitle($title);
            $this->SetVars(get_defined_vars());
        }
        catch(Exception $e)
        {
            SB_MessagesStack::AddError($e->getMessage());
            sb_redirect($this->Route('index.php?mod=users'));
        }
        
    }
}
