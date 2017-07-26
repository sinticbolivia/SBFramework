<?php
$dbh = SB_Factory::getDbh();
SB_Module::RunSQL('users');
$permissions = array(
		array('group' => 'users', 'permission' => 'manage_settings', 'label'	=> SB_Text::_('Gestionar configuracion', 'users')),
		array('group' => 'users', 'permission' => 'manage_general_settings', 'label'	=> SB_Text::_('Configuraci&oacute;n General', 'users')),
		//array('permission' => 'manage_design_settings', 'label'	=> SB_Text::_('Configuraci&oacute;n de Est&eacute;tica', 'users')),
		array(
				'group' => 'users',
				'permission' 	=> 'manage_limit_settings', 
				'label'			=> __('Configuraci&oacute;n de Aplicaci&oacute;n', 'users'),
				'attributes'	=> json_encode(array('only_root' => 'yes')),
		),
		array('group' => 'users','permission' => 'manage_roles', 'label'	=> SB_Text::_('Gestionar roles', 'users')),
		array('group' => 'users','permission' => 'create_role', 'label'	=> SB_Text::_('Crear rol', 'users')),
		array('group' => 'users','permission' => 'edit_role', 'label'		=> SB_Text::_('Editar rol', 'users')),
		array('group' => 'users','permission' => 'delete_role', 'label'	=> SB_Text::_('Borrar rol', 'users')),
		array('group' => 'users','permission' => 'manage_users', 'label'	=> SB_Text::_('Gestionar usuarios', 'users')),
		array('group' => 'users','permission' => 'create_user', 'label'	=> SB_Text::_('Crear usuario', 'users')),
		array('group' => 'users','permission' => 'edit_user', 'label'		=> SB_Text::_('Editar usuario', 'users')),
		array('group' => 'users','permission' => 'delete_user', 'label'	=> SB_Text::_('Borrar usuario', 'users')),
);
sb_add_permissions($permissions);
//##insert roles
$roles = array(
		array(
				'role_name'					=> SBText::_('Posible', 'users'),
				'role_key'					=> 'possible',
				'last_modification_date'	=> date('Y-m-d H:i:s'),
				'creation_date'				=> date('Y-m-d H:i:s')
		),
		array(
				'role_name'					=> SBText::_('Bloqueado', 'users'),
				'role_key'					=> 'bloqued',
				'last_modification_date'	=> date('Y-m-d H:i:s'),
				'creation_date'				=> date('Y-m-d H:i:s')
		),
);
foreach( $roles as $role )
{
	$query = "SELECT role_id FROM user_roles WHERE role_key = '{$role['role_key']}' LIMIT 1";
	if( !$dbh->Query($query) )
	{
		$dbh->Insert('user_roles', $role);
	}
}