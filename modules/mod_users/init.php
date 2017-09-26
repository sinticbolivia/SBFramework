<?php
define('MOD_USERS_DIR', MODULES_DIR . SB_DS . 'mod_users');
define('MOD_USERS_URL', MODULES_URL . '/mod_users');
require_once dirname(__FILE__) . SB_DS . 'functions.php';
require_once dirname(__FILE__) . SB_DS . 'classes' . SB_DS . 'class.role.php';
require_once dirname(__FILE__) . SB_DS . 'classes' . SB_DS . 'class.user.php';
SB_Module::add_action('init', array('SB_UsersHooks', 'action_init'));
SB_Module::add_action('admin_menu', array('SB_UsersHooks', 'action_admin_menu'));
SB_Module::add_action('admin_dashboard', array('SB_UsersHooks', 'action_admin_dashboard'));
if( !defined('LT_ADMIN') )
{
	SB_Module::add_action('user_menu', array('SB_UsersHooks', 'action_user_menu'));
}
class SB_UsersHooks
{
	public static function action_init()
	{
		SB_Language::loadLanguage(LANGUAGE, 'users', MOD_USERS_DIR . SB_DS . 'locale');
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
	public static function action_admin_menu()
	{
		SB_Menu::addMenuChild('menu-management', 
					'<span class="glyphicon glyphicon-user"></span> ' . __('Users', 'users'), 
					SB_Route::_('index.php?mod=users'), 'menu-users', 'manage_users');
		SB_Menu::addMenuChild('menu-management', 
					'<span class="glyphicon glyphicon-th-list"></span> ' . __('User Roles', 'users'), 
					SB_Route::_('index.php?mod=users&view=roles'), 'menu-user-roles', 'manage_roles');
	}
	public static function action_admin_dashboard()
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
									<a href="<?php print SB_Route::_('index.php?mod=users'); ?>" class="btn btn-default">
										<?php print SBText::_('View list', 'users'); ?>
									</a>
								</div>
							</div>
							<div class="col-xs-12 col-md-6">
								<div class="value"><?php print $online_users; ?></div>
								<div class="text"><?php print SB_Text::_('Usuarios Conectados', 'users'); ?></div>
								<div class="text-center">
									<a href="<?php print SB_Route::_('index.php?mod=users&view=online_users'); ?>" class="btn btn-default">
										<?php print SBText::_('Ver listado', 'users'); ?>
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
	public static function action_user_menu($menu)
	{
		$menu[] = array('link'	=> SB_Route::_('index.php?mod=users&view=profile'), 'text'	=> SBText::_('Mi Perfil', 'users'));
		return $menu;
	}
}