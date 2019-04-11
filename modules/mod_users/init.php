<?php
namespace SinticBolivia\SBFramework\Modules\Users;

use SinticBolivia\SBFramework\Classes\SB_Module;
use SinticBolivia\SBFramework\Classes\SB_Language;
use SinticBolivia\SBFramework\Classes\SB_Request;
use SinticBolivia\SBFramework\Classes\SB_Menu;
use SinticBolivia\SBFramework\Classes\SB_Route;
use SinticBolivia\SBFramework\Classes\SB_Factory;
use SinticBolivia\SBFramework\Classes\SB_Session;
use SinticBolivia\SBFramework\Classes\SB_MessagesStack;

define('MOD_USERS_DIR', MODULES_DIR . SB_DS . 'mod_users');
define('MOD_USERS_URL', MODULES_URL . '/mod_users');
require_once dirname(__FILE__) . SB_DS . 'functions.php';
//require_once dirname(__FILE__) . SB_DS . 'classes' . SB_DS . 'class.role.php';
//require_once dirname(__FILE__) . SB_DS . 'classes' . SB_DS . 'class.user.php';
class SB_UsersHooks
{
	public function __construct()
	{
		SB_Language::loadLanguage(LANGUAGE, 'users', MOD_USERS_DIR . SB_DS . 'locale');
		$this->AddActions();
	}
	protected function AddActions()
	{
        SB_Module::add_action('sb_check_session_timout', array($this, 'action_validate_session'));
		SB_Module::add_action('init', array($this, 'action_init'));
		SB_Module::add_action('admin_menu', array($this, 'action_admin_menu'));
		SB_Module::add_action('admin_dashboard', array($this, 'action_admin_dashboard'));
		SB_Module::add_action('modules_loaded', array($this, 'action_modules_loaded'));
		if( !lt_is_admin() )
		{
			SB_Module::add_action('user_menu', array($this, 'action_user_menu'));
		}
	}
    public function action_validate_session($user, $session)
    {
        if( !isset($_COOKIE[$session]) )
        {
            SB_MessagesStack::AddMessage(__('Your session has expired', 'users'), 'info');
            $ctrl = SB_Module::GetControllerInstance('users');
            $ctrl->task_logout();
            return false;
        }
        /*
        $last_timeout   = (int)sb_get_user_meta($user->user_id, '_timeout');
        $time           = time();
        //##check session expiration
        $time_diff = $time - $last_timeout;
        SB_Factory::getApplication()->Log(
            "SESSION_EXPIRE: ".SESSION_EXPIRE."\n".
            "last_timeout: $last_timeout ". date('Y-m-d H:i:s:m', $last_timeout) ."\n".
            "time: $time ". date('Y-m-d H:i:s:m', $time) ."\n".
            "diff: $time_diff\n");
        
        if( $time_diff > SESSION_EXPIRE )
        {
            SB_MessagesStack::AddMessage(__('Your session has expired', 'users'), 'info');
            $ctrl = SB_Module::GetControllerInstance('users');
            $ctrl->task_logout();
            return false;
        }
        */
        //##renew the timeout
        $timeout = time();
        //require_once MODULES_DIR . SB_DS . 'mod_users' . SB_DS . 'functions.php';
        sb_update_user_meta($user->user_id, '_timeout', $timeout);
    }
	public function action_init()
	{
		$is_api = SB_Request::getString('api');
		if( $is_api )
		{
			$method = SB_Request::getString('method');
			require_once MOD_USERS_DIR . SB_DS . 'classes' . SB_DS . 'class.api.php';
			$api = new SB_ModUsersAPI();
			
			if( $method && method_exists($api, $method) )
			{
				$res = call_user_func(array($api, $method));
				header('Access-Control-Allow-Origin: *');
				header('Content-type: application/json');
				die($res);
			}
			die();
		}
	}
	public function action_admin_menu()
	{
		SB_Menu::addMenuChild('menu-management', 
					'<span class="glyphicon glyphicon-user"></span> ' . __('Users', 'users'), 
					SB_Route::_('index.php?mod=users'), 'menu-users', 'manage_users');
		SB_Menu::addMenuChild('menu-management', 
					'<span class="glyphicon glyphicon-th-list"></span> ' . __('User Roles', 'users'), 
					SB_Route::_('index.php?mod=users&view=roles.default'), 'menu-user-roles', 'manage_roles');
		SB_Menu::addMenuChild('menu-management', 
					'<span class="glyphicon glyphicon-th-list"></span> ' . __('User Sessions', 'users'), 
					SB_Route::_('index.php?mod=users&view=sessions'), 'menu-user-sessions', 'users_see_sessions');
	}
	public function action_admin_dashboard()
	{
		$dbh = SB_Factory::getDbh();
		$current_user = sb_get_current_user();
		$query = "SELECT COUNT(u.user_id) AS total FROM users u";
		
		if( $current_user->role_id === 0 )
		{
			
		}
		else 
		{
			$query .= ", user_meta um WHERE u.user_id = um.user_id AND um.meta_key = '_owner_id' AND um.meta_value = '$current_user->user_id'";
		}
		$dbh->Query($query);
		$users = (int)$dbh->FetchRow()->total;
		/*
		$query = "SELECT u.user_id FROM users u, user_meta um WHERE u.user_id = um.user_id AND um.meta_key = '_logged_in' AND um.meta_value = 'yes'";
		$online_users = $dbh->Query($query);
		*/
		$query = "SELECT COUNT(u.user_id) AS online_users ".
				"FROM users u, user_meta um, user_meta umt ".
				"WHERE 1 = 1 " .
				"AND u.user_id = um.user_id ".
				"AND um.meta_key = '_logged_in' " .
				"AND um.meta_value = 'yes' ".
				"AND u.user_id = umt.user_id ".
				"AND umt.meta_key = '_timeout' ".
				sprintf("AND ((%d - CAST(umt.meta_value AS UNSIGNED)) < %d) ", time(), SESSION_EXPIRE);
		$dbh->Query($query);
		$online_users = $dbh->FetchRow()->online_users;
		?>
		<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
			<div class="panel panel-default widget">
				<div class="panel-heading widget-header">
					<h3 class="panel-title"><?php _e('Users Statistics', 'users')?></h3>
				</div>
				<div class="panel-body widget-content">
					<div id="big_stats" class="container-fluid">
						<div class="row">
							<div class="col-xs-12 col-md-6">
								<div class="value"><?php print $users; ?></div>
								<div class="text"><?php _e('Users', 'users'); ?></div>
								<div class="text-center">
									<a href="<?php print b_route('index.php?mod=users'); ?>" class="btn btn-default">
										<?php print __('View list', 'users'); ?>
									</a>
								</div>
							</div>
							<div class="col-xs-12 col-md-6">
								<div class="value"><?php print $online_users; ?></div>
								<div class="text"><?php print __('Usuarios Conectados', 'users'); ?></div>
								<div class="text-center">
									<a href="<?php print b_route('index.php?mod=users&view=online_users'); ?>" class="btn btn-default">
										<?php print __('Ver listado', 'users'); ?>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php 
	}
	public function action_user_menu($menu)
	{
		$menu[] = array('link'	=> b_route('index.php?mod=users&view=profile'), 'text'	=> __('My Profile', 'users'));
		return $menu;
	}
	public function action_modules_loaded()
	{
		$this->StartApiRest();
	}
	protected function StartApiRest()
	{
		//##set endpoints
		SB_Module::add_action('rewrite_routes', function($routes)
		{
			$routes['/^\/api\/v1.0.0\/users\/?$/'] = 'mod=users&task=api.default';
			$routes['/^\/api\/v1.0.0\/users\/([a-zA-Z-_]+)\/?$/'] = 'mod=users&task=api.$1';
			$routes['/^\/api\/v1.0.0\/users\/([0-9]+)\/?$/'] = 'mod=users&task=api.get&id=$1';
			$routes['/^\/api\/v1.0.0\/users\/([a-zA-Z-_]+)\/([0-9]+)\/?$/'] = 'mod=users&task=api.$1&id=$2';
			
			return $routes;
		});
		//die(__METHOD__);
	}
}
new SB_UsersHooks();
