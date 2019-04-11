<?php
class SB_ModUsersAPI
{
	protected $dbh;
	
	public function __construct()
	{
		$this->dbh = SB_Factory::GetDbh();
	}
	public function get_users()
	{
		$table = SB_DbTable::GetTable('users', 1);
		//print_r($table);die();
		$users = $table->GetRows(-1);
		$json = json_encode($users);
		return $json;
	}
}