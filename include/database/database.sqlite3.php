<?php
class SB_Sqlite3 extends SB_Database
{
	protected $_rows = 0;
	protected $result = null;
	public function __construct($db_name)	
	{
		$this->db_type = 'sqlite3';
		$this->lcw = '[';
		$this->rcw = ']';
		$this->dbh = new SQLite3($db_name);
	}
	public function Query($query)
	{
		
		if( $query == null )
			$query =  $this->builtQuery ? $this->builtQuery : $query;
		if( !$query || empty(trim($query)) )	
			throw new Exception("SQLITE3 ERROR: Invalid query, it is empty or null");
		$this->_rows = 0;
		$this->lastQuery = $query;
		
		if( preg_match('/insert|update|delete\s+from/isU', $query) )
		{
			//die('query:'.$query);
			$res = $this->dbh->exec($query);
			if( !$res )
			{
				throw new Exception('SQLite3 ERROR: ' . $this->dbh->lastErrorMsg() . " QUERY WAS: $query");
			}
			if( stristr($query, 'insert') )
			{
				$this->lastId = $this->dbh->lastInsertRowID();
				return $this->lastId;
			}
			
			return $res;
		}
		
		
		//##execute query
		$this->result = $this->dbh->query($query);
		if( !$this->result )
			throw new Exception("SQLite3 ERROR: " . $this->dbh->lastErrorMsg() . " QUERY WAS: " . $query);
		
		$this->result->reset();
		while( $_r = $this->result->fetchArray(SQLITE3_ASSOC) )
		{
			$this->_rows++;
		}
		$this->result->reset();
	
		return $this->_rows;
	}
	public function FetchResults($query = null)
	{
		if( $query )
			$this->Query($query);
		
		$res = array();
		if( !$this->result )
			return $res;
	
		/*
		if ( !$this->_result->numColumns() || $this->_result->columnType(0) == SQLITE3_NULL) 
		{
			return $res;
		}
		*/
		while( $row = $this->result->fetchArray(SQLITE3_ASSOC) )
		{
			$res[] = (object)$row;
		}
		$this->result->finalize();
		$this->result = null;
		return $res;
	}
	public function FetchRow($query = null)
	{
		$res = null;
		if( $query )
		{
			$this->lastQuery = $query;
			$res = $this->dbh->querySingle($query, true);
			if( !$res || (is_array($res) && !count($res)) )
			{
				return null;
			}
		}
		else
		{
			$res = $this->result->fetchArray(SQLITE3_ASSOC);
			$this->result->finalize();
			$this->result = null;
		}
		
		
		return (object)$res;
	}
	public function GetVar($query = null, $varname = null)
	{
		$row = $this->FetchRow($query);
		if( !$row )
			return null;
		
		if( $varname && isset($row->$varname))
			return $row->$varname;
		$array = (array)$row;
		return array_shift($array);
	}
	public function NumRows()
	{
		return $this->_rows;
	}
	public function EscapeString($str)
	{
		return $this->dbh->escapeString($str);
	}
	public function Close()
	{
		$this->dbh->close();
	}
	/**
	 * (non-PHPdoc)
	 * @see SB_Database::Select()
	 * @return SB_Database
	 */
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
	/**
	 *
	 * @param string $column
	 * @return SB_Database
	 */
	public function OrderBy($column, $order = 'desc')
	{
		$this->builtQuery .= "ORDER BY $column $order ";
		return $this;
	}
	public function Limit($limit, $offset)
	{
		$this->builtQuery .= "LIMIT $offset,$limit";
		return $this;
	}
}