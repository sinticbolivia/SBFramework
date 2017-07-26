<?php
ini_set('display_errors', 1);error_reporting(E_ALL);
class SB_SqlServer
{
	protected	$dbh;
	protected	$result;
	
	public function __construct($server, $username, $password, $database = null)
	{
		/*
		$driver1 = 'SQL Server Native Client 10.0';
		$driver2 = 'ODBC Driver 11 for SQL Server';
		$driver3 = 'SQL Server';
		$driver4 = 'TDS';
		$dsn = "Driver={".$driver4."};Server=$server;Port=1433;Database=$database;";
		//$dsn = "DRIVER={".$driver1."};SERVER=$server,1433;DATABASE=$database;Data Source=$database;";
		//$dsn = "Driver={$driver};Data Source=$server";
		//print $dsn;
		//$this->dbh = mssql_connect($server, $username, $password);
		*/
		$this->dbh = odbc_connect($server, $username, $password) or die('ODBC Error:: '.odbc_error().' :: '.odbc_errormsg() . $server);
	}
	public function Close()
	{
		//mssql_close($this->dbh);
		odbc_close($this->dbh);
	}
	public function Query($query)
	{
		//$this->result = mssql_query($query, $this->dbh);
		$this->result = odbc_exec($this->dbh, $query);
	}
	public function FetchResults()
	{
		$rows = array();
		//while( $row = mssql_fetch_object($this->result) )
		while( $row = odbc_fetch_object($this->result) )
		{
			$rows[] = $row;
		}
		return $rows;
	}
}
$server		= '205.164.21.130';
$database 	= 'storm';
$username 	= 'veera';
$password 	= 'password';
/*
$server		= 'mssql.multacom.com';
$database = 'stormwe_kingston';
$username = 'stormwe_kingsmg'; //veera'
$password = 'king1234';
*/
$dbh = new SB_SqlServer('STORM', $username, $password);
$query = "SELECT * FROM information_schema.tables";
$query = "SELECT * FROM Product_Master ";
$query = "SELECT * FROM Model_Master WHERE ProductID = 1 ";
$dbh->Query($query);
$rows = $dbh->FetchResults();
$dbh->Close();
print_r($rows);

