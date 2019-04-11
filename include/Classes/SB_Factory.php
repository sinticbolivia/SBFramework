<?php
namespace SinticBolivia\SBFramework\Classes;

class SB_Factory
{
	protected static $_vars = array();
	
	/**
	 * Get a database handler
	 * 
	 * @return \SinticBolivia\SBFramework\Database\SB_Database
	 */
	public static function getDbh()
	{
		if( !isset(self::$_vars['dbh']) )
		{
			$db_type = defined('DB_TYPE') ? DB_TYPE : 'mysql';
			if( $db_type == 'sqlite3' )
			{
				self::$_vars['dbh'] = new \SinticBolivia\SBFramework\Database\SB_Sqlite3(BASEPATH . SB_DS . 'db' . SB_DS . DB_NAME);
			}
			elseif( $db_type == 'mysql' )
			{
				self::$_vars['dbh'] = new \SinticBolivia\SBFramework\Database\SB_MySQL(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
				self::$_vars['dbh']->selectDB(DB_NAME);
			}
			elseif( $db_type == 'postgres' )
			{
				self::$_vars['dbh'] = new \SinticBolivia\SBFramework\Database\SB_Postgres(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
			}
		}
		
		return self::$_vars['dbh'];
	}
	/**
	 * Get an instance of application
	 * 
	 * @return SB_Application
	 */
	public static function getApplication($app = null)
	{
		if( !isset(self::$_vars['app']) )
		{
			self::$_vars['app'] = SB_Application::GetApplication($app);
		}
		return self::$_vars['app'];
	}
	/**
	 * @return \NilPortugues\Sql\QueryBuilder\Builder\GenericBuilder
	 */
	public static function getQueryBuilder()
	{
		$obj = null;
		
		switch(DB_TYPE)
		{
			case 'sqlite3' :
				$obj = new \NilPortugues\Sql\QueryBuilder\Builder\GenericBuilder();
			break;
			case 'mysql':
				$obj = new \NilPortugues\Sql\QueryBuilder\Builder\MySqlBuilder();
			break;
			case 'postgres':
				$obj = new \NilPortugues\Sql\QueryBuilder\Builder\GenericBuilder();
			break;
		}
		return $obj;
	}
}
