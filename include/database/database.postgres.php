<?php
class SB_Postgres extends SB_Database 
{
	protected 	$link = false;
	//protected	$dbh;
	protected 	$errorCode;
	protected 	$error;
	protected 	$result;
	
	public function __construct($server, $username, $password, $database = null)
	{
		$this->db_type = 'postgres';
		$connection_string = "host=$server port=5432 dbname=$database user=$username password=$password";
		$this->link	= $this->dbh	= pg_connect($connection_string);
		if( !$this->dbh )
		{
			throw new Exception("Fatal error: cannot connect to database server<br/>Connection String: $connection_string");
		}
		$this->lcw = '"';
		$this->rcw = '"';
	}
	public function selectDB($database)
	{
	}
	public function getErrorCode()
	{
		return $this->errorCode;
	}
	public function Query($query)
	{
		$query = !$query ? $this->builtQuery : $query;
		$query = trim($query);
		$is_insert = preg_match('/^insert/isU', $query, $matches);
		if( $is_insert ) 
		{
			preg_match('/INTO\s+(.*)\(/iU', $query, $matches);
			$query = rtrim($query, ';') . 
					' RETURNING (SELECT column_name FROM information_schema.columns WHERE table_schema=\'public\' AND table_name=\''.trim($matches[1]).'\' LIMIT 1) AS id;';
		}
		
		$this->lastQuery = $query;
		$this->result = pg_query($this->dbh, $query);
		if( !$this->result )
		{
			$this->errorCode 	= null;
			$this->error		= pg_last_error($this->dbh);
			if( $this->debug )
				error_log("Postgres Error: $this->error\n Error code: $this->errorCode Query: $query\n", 3, LOG_FILE);
			throw new Exception( 'Error['.$this->errorCode.']: '. $this->error . ' Your query was: ' .$query );
			
		}
		$pattern = '/^[update|delete]/isU';
		$res = preg_match($pattern, $query, $matches);
		if( $res > 0)
			return pg_affected_rows($this->result);
			
		if( $is_insert )
		{
			//$this->lastId = pg_last_oid($this->result);
			$this->lastId = (int)pg_fetch_object($this->result)->id; 
			return $this->lastId;
		}
			
		$res = preg_match('/^select/isU', $query, $matches);
		if( $res > 0 )
			return pg_num_rows($this->result);
			
		return $this->result;
	}
	public function Close()
	{
		pg_close($this->link);
	}
	public function FetchArray( $type = PGSQL_ASSOC)
	{
		return pg_fetch_array($this->result, null, $type);
		//return mysql_fetch_array($this->result, MYSQL_ASSOC);
	}
	public function FetchRow($query = null)
	{
		if( $query != null )
			$this->Query($query);
		return pg_fetch_object($this->result);
	}
	public function GetVar($query = null, $varname = null)
	{
		if( $query != null )
		{
			if( !$this->Query($query))
				return null;
		}
		
		$row = $this->FetchArray(PGSQL_BOTH);
		
		if( $varname && isset($row[$varname]))
			return $row[$varname];
		
		return $row[0];
	}
	public function NumRows()
	{
		return pg_num_rows($this->result);
	}
	public function getVarFromQuery($query, $varname)
	{
		$this->Query($query);
		
		return $this->GetVar($varname);
	}
	public function FetchObject($class = null)
	{
		if( !$class )
			return pg_fetch_object($this->result);
		
		return pg_fetch_object($this->result);
	}
	public function FetchResults($query = null)
	{
		if( $query != null )
			$this->Query($query);
		
		$results = array();
		while( $row = $this->FetchObject() )
		{
			$results[] = $row;
		}
		return $results;
	}
	public function EscapeString($string)
	{
		return pg_escape_string($this->link, $string);
	}
	public function escapeArray(array $array)
	{
		if(is_array($array))
		{
			$escapedArray = array();
			foreach($array as $index => $value)
			{
				$escapedArray[$index] = $this->EscapeString($value);
			}
			return $escapedArray;
		}
	}
	public function AffectedRows($in_ResulSet)
	{
		return pg_affected_rows($this->result);
	}
	public function getNextId($table, $columnId)
	{
		$query = "SELECT MAX($columnId) id FROM $table";
		$res = $this->Query($query);
		$rows = $this->NumRows($res);
		if($rows <= 0 )
		{
			return 1;
		}
		$row = $this->FetchArray($res);
		return  $row["id"] + 1;
	}
	public function beginTransaction()
	{
		$this->Query("BEGIN");
	}
	public function commit()
	{
		$this->Query("COMMIT");
	}
	public function rollBack()
	{
		$this->Query("ROLLBACK");
	}
	public function getConnection()
	{
		return $this->link;
	}
	public function setResource($res)
	{
		$this->result = $res;
	}
	public function getResource()
	{
		return $this->result;
	}
	public function Select($columns)
	{
		$sql_cols = $this->SanitizeColumns($columns);
		$this->builtQuery = sprintf("SELECT %s ", implode(',', $sql_cols));
		return $this;
	}
	public function From($tables)
	{
		if( is_array($tables) )
		{
			$this->builtQuery .= "FROM " . implode(',', $tables) . " ";
		}
		else
		{
			$this->builtQuery .= "FROM $tables ";
		}
		return $this;
	}
	public function Limit($limit, $offset)
	{
		$this->builtQuery .= "LIMIT $limit OFFSET $offset";
		return $this;
	}
}