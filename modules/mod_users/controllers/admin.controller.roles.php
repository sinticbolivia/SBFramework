<?php
class LT_AdminControllerUsersRoles extends SB_Controller
{
	public function task_default()
	{
		if( !sb_get_current_user()->can('manage_roles') )
		{
			die('You dont have enough permissions');
		}
		
	}
	public function task_new()
	{
		if( !sb_get_current_user()->can('create_role') )
		{
			die('You dont have enough permissions');
		}
		sb_set_view('roles.edit');
		sb_set_view_var('title', SB_Text::_('Nuevo Rol', 'users'));
	}
	public function task_edit()
	{
		if( !sb_get_current_user()->can('edit_role') )
		{
			die('You dont have enough permissions');
		}
		$role_id = SB_Request::getInt('id');
		if( !$role_id )
		{
			SB_MessagesStack::AddMessage(SB_Text::_('Identificador de rol no valido'), 'error');
			sb_redirect(SB_Route::_('index.php?mod=users&view=roles'));
		}
		$role = new SB_Role($role_id);
		if( !$role->role_id )
		{
			SB_MessagesStack::AddMessage(SB_Text::_('El rol no existe'), 'error');
			sb_redirect(SB_Route::_('index.php?mod=users&view=roles'));
		}
		sb_set_view_var('title', SB_Text::_('Editar Rol', 'users'));
		sb_set_view_var('role', $role);
	}
	public function task_save()
	{
		sb_set_view('roles.edit');
		$role_id 	= SB_Request::getInt('role_id');
		$role_name 	= SB_Request::getString('role_name');
		$desc		= SB_Request::getString('description');
		$perms		= SB_Request::getVar('permissions', array());
		$role_id ? sb_set_view_var('title', SB_Text::_('Editar Rol', 'users')) : sb_set_view_var('title', SB_Text::_('Nuevo Rol', 'users'));
		if( $role_id && !sb_get_current_user()->can('edit_role') )
		{
			die('You dont have enough permission');
		}
		if( !$role_id && !sb_get_current_user()->can('create_role') )
		{
			die('You dont have enough permission');
		}
		if( empty($role_name) )
		{
			SB_MessagesStack::AddMessage(SB_Text::_('Debe ingresar un nombre para el rol de usuario'), 'error');
			return false;
		}
		$dbh = SB_Factory::getDbh();
		if( !$role_id )
		{
			//##check for duplicated role name
			$query = "SELECT role_id, role_name FROM user_roles WHERE role_name = '$role_name'";
			if( $dbh->Query($query) )
			{
				SB_MessagesStack::AddMessage(SB_Text::_('El nombre de rol ya existe.'), 'error');
				return false;
			}
		}
		$cdate = date('Y-m-d H:i:s');
		$data = array(
				'role_name' => $role_name,
				'role_description' => $desc,
				'role_key'			=> sb_build_slug($role_name),
				'last_modification_date'	=> $cdate
		);
		
		if( !$role_id )
		{
			//##create a new user role
			$data['creation_date'] = $cdate;
			$role_id = $dbh->Insert('user_roles', $data);
			if( count($perms) )
			{
				$query = "INSERT INTO role2permission(role_id,permission_id) VALUES";
				foreach($perms as $perm_id)
				{
					$query .= "($role_id, $perm_id),";
				}
				$dbh->Query(rtrim($query, ','));
			}
			
			$data = array(
					'permission' 				=> 'create_role_' . sb_build_slug($role_name),
					'label'						=> sprintf(SB_Text::_('Asignar Rol %s'), $role_name),
					'last_modification_date'	=> $cdate,
					'creation_date'				=> $cdate
			);
			$dbh->Insert('permissions', $data);
			SB_MessagesStack::AddMessage(SB_Text::_('Se ha creado un nuevo rol de usuario.'), 'success');
			sb_redirect(SB_Route::_('index.php?mod=users&view=roles'));
		}
		else
		{
			//$the_role = new SB_Role($role_id);
			//##update the user role
			$dbh->Update('user_roles', $data, array('role_id' => $role_id));
			$dbh->Query("DELETE FROM role2permission WHERE role_id = $role_id");
			if( count($perms) )
			{
				$query = "INSERT INTO role2permission(role_id,permission_id) VALUES";
				foreach($perms as $perm_id)
				{
					$query .= "($role_id, $perm_id),";
				}
				$dbh->Query(rtrim($query, ','));
			}
			
			SB_MessagesStack::AddMessage(SB_Text::_('El rol se ha actualizado.'), 'success');
			sb_redirect(SB_Route::_('index.php?mod=users&view=roles.edit&id='.$role_id));
		}
	}
	public function task_delete()
	{
		$role_id = SB_Request::getInt('id');
		if( !$role_id )
		{
			SB_MessagesStack::AddMessage(SB_Text::_('Identificador de rol no valido', 'users'), 'error');
			sb_redirect(SB_Route::_('index.php?mod=users&view=roles'));
		}
		$dbh = SB_Factory::getDbh();
		$query = "SELECT * FROM user_roles WHERE role_id = $role_id LIMIT 1";
		if( !$dbh->Query($query) )
		{
			SB_MessagesStack::AddMessage(SB_Text::_('El rol no existe', 'users'), 'error');
			sb_redirect(SB_Route::_('index.php?mod=users&view=roles'));
		}
		$role = $dbh->FetchRow();
		$slug = sb_build_slug($role->role_name);
		$dbh->Query("DELETE FROM role2permission WHERE role_id = {$role->role_id}");
		$dbh->Query("DELETE FROM permissions WHERE (permission = 'create_role_$slug' OR permission = '$slug') LIMIT 1");
		$dbh->Query("DELETE FROM user_roles WHERE role_id = {$role->role_id} LIMIT 1");
		SB_MessagesStack::AddMessage(sprintf(SB_Text::_('El rol %s ha sido borrado', 'users'), $role->role_name), 'error');
		sb_redirect(SB_Route::_('index.php?mod=users&view=roles'));
	}
}