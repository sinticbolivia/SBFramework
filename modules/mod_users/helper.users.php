<?php
class LT_HelperUsers
{
	/**
	 * Get user roles
	 * @return array
	 */
	public static function GetUserRoles()
	{
		$dbh = SB_Factory::getDbh();
		$query = "SELECT * FROM user_roles ORDER BY role_name ASC";
		$dbh->Query($query);
		return $dbh->FetchResults();
	}
	public static function GetUser($user_id)
	{
		$user_id 	= (int)$user_id;
		$dbh 		= SB_Factory::getDbh();
		$query 		= "SELECT * FROM users WHERE user_id = $user_id LIMIT 1";
		$dbh->Query($query);
		return $dbh->FetchRow();
	}
}