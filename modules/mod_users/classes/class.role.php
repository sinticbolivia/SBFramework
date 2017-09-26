<?php
class SB_Role extends SB_ORMObject
{
	protected $_permissions = array();
	
	public function __construct($role_id = null)
	{
		parent::__construct();
		if( $role_id )
			$this->GetDbData($role_id);
	}
	public function GetDbData($role_id)
	{
		$query = "SELECT * FROM user_roles WHERE role_id = $role_id LIMIT 1";
		if( !$this->dbh->Query($query) )
		{
			return false;
		}
		
		$this->_dbData = $this->dbh->FetchRow();
		$this->GetDbPermissions($role_id);
	}
	protected function GetDbPermissions($role_id)
	{
		if( !$role_id )
			return false;
		$query = "SELECT p.permission FROM permissions p, role2permission r2p WHERE p.permission_id = r2p.permission_id ".
					"AND r2p.role_id = $role_id";
		if( !$this->dbh->Query($query) )
		{
			return false;
		}
		foreach($this->dbh->FetchResults() as $row)
		{
			$this->_permissions[] = $row->permission;
		}
	}
	public function SetDbData($data)
	{
		$this->_dbData = (object)$data;
		//$this->_permissions = $role_permissions;
		$this->GetDbPermissions($this->role_id);
	}
	public function hasPermission($perm)
	{
		return in_array($perm, $this->_permissions);
	}
	public static function GetRoleByKey($key)
	{
		$dbh = SB_Factory::getDbh();
		$query = "SELECT * FROM user_roles WHERE role_key = '$key' LIMIT 1";
		$res = $dbh->Query($query);
		
		if( !$res )
			return null;
		
		$role = new SB_Role();
		$role->SetDbData($dbh->FetchRow());
		
		return $role;
	}
}