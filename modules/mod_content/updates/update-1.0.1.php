<?php
$dbh = SB_Factory::GetDbh();
if( $dbh->db_type == 'mysql' )
{
	$column = $dbh->FetchRow("SHOW COLUMNS FROM section WHERE Field = 'for_object'");
	
	if( !$column )
	{
		$dbh->Query("ALTER TABLE section ADD COLUMN for_object varchar(64) default 'page' after lang_code");
	}
}
elseif( $dbh->db_type == 'sqlite3' )
{
	try
	{
		$dbh->Query('SELECT for_object FROM section');
	}
	catch(Exception $e)
	{
		$dbh->Query("ALTER TABLE section ADD COLUMN for_object varchar(64) default 'page'");
	}
	
}