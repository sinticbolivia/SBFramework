<?php
namespace SinticBolivia\SBFramework\Modules\Users\Controllers;
use SinticBolivia\SBFramework\Classes\SB_Controller;
use SinticBolivia\SBFramework\Classes\SB_MessagesStack;
use SinticBolivia\SBFramework\Modules\Users\Classes\SB_Role;
use SinticBolivia\SBFramework\Modules\Users\Models\UsersModel;

class RolesAdminController extends SB_Controller
{
	/*
    protected $models = array(
        'RolesModel'
    );
    */
    /**
     * @var \SinticBolivia\SBFramework\Modules\Users\Models\UsersModel
     */
    protected $usersModel;
    /**
     * 
     * @var \SinticBolivia\SBFramework\Modules\Users\Models\RolesModel
     */
    protected $rolesModel;
	public function task_default()
	{
		
		if( !sb_get_current_user()->can('manage_roles') )
		{
			die('You dont have enough permissions');
		}
		$page 		= $this->request->getInt('page', 1);
		$order_by 	= $this->request->getString('order_by', 'creation_date');
		$order 		= $this->request->getString('order', 'desc');
		$dbh 		= $this->request->getDbh();
		$limit		= $this->request->getInt('limit', 25);
		if( defined('ITEMS_PER_PAGE') )
		{
			$limit = ITEMS_PER_PAGE;
		}
		
		$query = "SELECT {columns} FROM user_roles WHERE role_key <> 'possible' AND role_key <> 'bloqued' ORDER BY $order_by $order";
		$res = $this->dbh->Query(str_replace('{columns}', 'COUNT(*) AS total_rows', $query));
		$total_rows = $this->dbh->FetchRow()->total_rows;
		$pages = ceil($total_rows/$limit);
		$offset = $page <= 1 ? 0 : ($page - 1) * $limit;
		$roles = array();
		
		$this->dbh->Query(str_replace('{columns}', '*', $query . " LIMIT $offset, $limit"));
		foreach($this->dbh->FetchResults() as $row)
		{
			$roles[] = $row;
		}
		$new_order = ($order == 'desc') ? 'asc' : 'desc';
		$role_order_link		= 'index.php?mod=users&view=roles&order_by=role_name&order='.$new_order;
        $this->SetView('roles');
		sb_set_view_var('role_order_link', $role_order_link);
		sb_set_view_var('roles', $roles);
		
	}
	public function task_new()
	{
		$user = sb_get_current_user();
		if( !$user->can('create_role') )
		{
			die('You dont have enough permissions');
		}
		$title			= __('Create New User Role', 'users');
		$permissions 	= $this->usersModel->GetPermissions($user, true);
		sb_set_view('roles.edit');
		$this->SetVars(get_defined_vars());
		$this->document->SetTitle($title);
	}
	public function task_edit()
	{
		$user = sb_get_current_user();
		if( !$user->can('edit_role') )
		{
			die('You dont have enough permissions');
		}
		$role_id = $this->request->getInt('id');
		if( !$role_id )
		{
			SB_MessagesStack::AddMessage($this->__('Identificador de rol no valido'), 'error');
			sb_redirect($this->Route('index.php?mod=users&view=roles.default'));
		}
		$role = new SB_Role($role_id);
		if( !$role->role_id )
		{
			SB_MessagesStack::AddMessage($this->__('El rol no existe'), 'error');
			sb_redirect($this->Route('index.php?mod=users&view=roles.default'));
		}
		$permissions 	= $this->usersModel->GetPermissions($user, true);

		//$permissions 	= sb_get_permissions();
		$title 			= __('Edit Role', 'users');
		$this->SetVars(get_defined_vars());
		$this->document->SetTitle($title);
	}
	public function task_save()
	{
		sb_set_view('roles.edit');
		$role_id 	= $this->request->getInt('role_id');
		$role_name 	= $this->request->getString('role_name');
		$desc		= $this->request->getString('description');
		$perms		= $this->request->getVar('permissions', array());
		$role_id ? sb_set_view_var('title', $this->__('Editar Rol', 'users')) : sb_set_view_var('title', $this->__('Nuevo Rol', 'users'));
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
			SB_MessagesStack::AddMessage($this->__('Debe ingresar un nombre para el rol de usuario'), 'error');
			return false;
		}
		
		if( !$role_id )
		{
			
			if( $this->rolesModel->RoleNameExists($role_name) )
			{
				SB_MessagesStack::AddMessage($this->__('The role name already exists.'), 'error');
				return false;
			}
		}
		$cdate = date('Y-m-d H:i:s');
		$role = new SB_Role();
        $role->SetDbData(array(
                'role_id'                   => $role_id,
				'role_name'                 => $role_name,
				'role_description'          => $desc,
				'role_key'                  => sb_build_slug($role_name),
				'last_modification_date'	=> $cdate
		));
		$id = $this->rolesModel->Save($role);
        if( $id )
            $this->rolesModel->SetPermissions($id, $perms);
		if( !$role_id )
		{
			SB_MessagesStack::AddMessage(__('The new user role has been created.', 'users'), 'success');
			sb_redirect($this->Route('index.php?mod=users&view=roles.default'));
		}
		else
		{
			SB_MessagesStack::AddMessage(__('The user role has been updated.'), 'success');
			sb_redirect($this->Route('index.php?mod=users&view=roles.edit&id='.$role_id));
		}
	}
	public function task_delete()
	{
		$role_id = $this->request->getInt('id');
		
        try
        {
            if( !$role_id )
                throw new Exception($this->__('The role identifier is invalid', 'users'));

            $this->rolesModel->Delete($role_id);
            SB_MessagesStack::AddMessage($this->__('The user role has been deleted', 'users'), 'error');
            sb_redirect($this->Route('index.php?mod=users&view=roles.default'));
        }
        catch(Exception $e)
        {
            SB_MessagesStack::AddMessage($e->getMessage(), 'error');
			sb_redirect($this->Route('index.php?mod=users&view=roles.default'));
        }
		
	}
}