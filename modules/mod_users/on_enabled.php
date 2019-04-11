<?php
use SinticBolivia\SBFramework\Classes\SB_Language;
use SinticBolivia\SBFramework\Classes\SB_Factory;
use SinticBolivia\SBFramework\Classes\SB_Module;

SB_Language::loadLanguage(LANGUAGE, 'users', dirname(__FILE__) . SB_DS . 'locale');
$dbh = SB_Factory::getDbh();
SB_Module::RunSQL('users');
$permissions = array(
		array('group' => 'users', 'permission' => 'users_backend_access', 'label'	=> __('Backend Access', 'users')),
		array('group' => 'users', 'permission' => 'users_menu', 'label'	=> __('Users menu', 'users')),
		//array('group' => 'users', 'permission' => 'manage_settings', 'label'	=> __('Settings Management', 'users')),
		//array('group' => 'users', 'permission' => 'manage_general_settings', 'label'	=> __('General Settings', 'users')),
		//array('permission' => 'manage_design_settings', 'label'	=> SB_Text::_('Configuraci&oacute;n de Est&eacute;tica', 'users')),
		array(
				'group' => 'users',
				'permission' 	=> 'manage_limit_settings', 
				'label'			=> __('Configuraci&oacute;n de Aplicaci&oacute;n', 'users'),
				'attributes'	=> json_encode(array('only_root' => 'yes')),
		),
		array('group' => 'users','permission' => 'manage_roles', 'label'	=> __('Roles Management', 'users')),
		array('group' => 'users','permission' => 'create_role', 'label'	=> __('Create Role', 'users')),
		array('group' => 'users','permission' => 'edit_role', 'label'		=> __('Edit Role', 'users')),
		array('group' => 'users','permission' => 'delete_role', 'label'	=> __('Delete Role', 'users')),
		array('group' => 'users','permission' => 'manage_users', 'label'	=> __('Users Management', 'users')),
		array('group' => 'users','permission' => 'create_user', 'label'	=> __('Create User', 'users')),
		array('group' => 'users','permission' => 'edit_user', 'label'		=> __('Edit User', 'users')),
		array('group' => 'users','permission' => 'delete_user', 'label'	=> __('Delete User', 'users')),
		array('group' => 'users', 'permission' => 'see_all_users', 'label'	=> __('See all users', 'users')),
		array('group' => 'users', 'permission' => 'users_see_sessions', 'label'	=> __('See user sessions', 'users')),
);
sb_add_permissions($permissions);
//##insert roles
$roles = array(
		
);
$roles = array(
		array(
				'role_name'					=> __('Posible', 'users'),
				'role_key'					=> 'possible',
				'last_modification_date'	=> date('Y-m-d H:i:s'),
				'creation_date'				=> date('Y-m-d H:i:s')
		),
		array(
				'role_name'					=> __('Bloqued', 'users'),
				'role_key'					=> 'bloqued',
				'last_modification_date'	=> date('Y-m-d H:i:s'),
				'creation_date'				=> date('Y-m-d H:i:s')
		),
		array(
				'role_name'					=> __('User', 'users'),
				'role_key'					=> 'user',
				'last_modification_date'	=> date('Y-m-d H:i:s'),
				'creation_date'				=> date('Y-m-d H:i:s')
		),
		array( 'role_name' => __('Administrator', 'users'), 'role_key' => 'admin', 'last_modification_date' => date('Y-m-d H:i:s'), 'creation_date' => date('Y-m-d H:i:s')),
		array( 'role_name' => __('Guest', 'users'), 'role_key' => 'guest', 'last_modification_date' => date('Y-m-d H:i:s'), 'creation_date' => date('Y-m-d H:i:s')),
		array( 'role_name' => __('SuperAdmin', 'users'), 'role_key' => 'superadmin', 'last_modification_date' => date('Y-m-d H:i:s'), 'creation_date' => date('Y-m-d H:i:s')),
);
foreach( $roles as $role )
{
	$query = "SELECT role_id FROM user_roles WHERE role_key = '{$role['role_key']}' LIMIT 1";
	if( !$dbh->Query($query) )
	{
		$dbh->Insert('user_roles', $role);
	}
}
//##insert whole permissions to superadmin role
$role_id = $dbh->GetVar("SELECT role_id FROM user_roles WHERE role_key = 'superadmin' LIMIT 1");
$dbh->Query("DELETE FROM role2permission WHERE role_id = $role_id");
$insert = "INSERT INTO role2permission(role_id,permission_id) select $role_id,permission_id from permissions";
$dbh->Query($insert);