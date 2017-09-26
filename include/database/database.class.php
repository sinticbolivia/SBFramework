<?php
abstract class SB_Database
{
	public	$debug = false;
	public 	$db_type;
	public	$databaseName;
	public 	$lastId;
	public 	$lastQuery;
	public	$lcw = '';
	public	$rcw = '';
	public	$dbh = null;
	public	$builtQuery;
	
	/**
	 * 
	 * @param string| array $columns
	 * @return SB_Database
	 */
	public function Select($columns){}
	/**
	 * 
	 * @param string | array $tables
	 * @return SB_Database
	 */
	public function From($tables){}
	/**
	 *
	 * @param string| array $wheres
	 * @return SB_Database
	 */
	public function Where($wheres)
	{
		$this->builtQuery .= "WHERE 1 = 1 ";
		if( is_array($wheres) )
		{
			$this->SqlAND($wheres);
		}
		return $this;
	}
	/**
	 * Append an AND sql operator
	 * 
	 * @param array $and The value key params
	 * @return SB_Database
	 */
	public function SqlAND($and, $operator = '=', $prepend_val = '', $append_val = '')
	{
		if( is_array($and) )
		{
			foreach($and as $col => $val)
			{
				if( strstr($col, '.') )
				{
					list($alias, $_col) = explode('.', $col);
					$this->builtQuery .= "AND {$this->lcw}$alias{$this->rcw}.{$this->lcw}$_col{$this->rcw} $operator '$prepend_val$val$append_val' ";
				}
				else
				{
					$this->builtQuery .= "AND {$this->lcw}$col{$this->rcw} $operator '$prepend_val$val$append_val' ";
				}
			}
		}
		return $this;
	}
	public function SqlOR($ors, $operator = '=', $prepend_val = '', $append_val = '')
	{
		if( is_array($ors) )
		{
			foreach($ors as $col => $val)
			{
				if( strstr($col, '.') )
				{
					list($alias, $_col) = explode('.', $col);
					$this->builtQuery .= "OR {$this->lcw}$alias{$this->rcw}.{$this->lcw}$_col{$this->rcw} $operator '$prepend_val$val$append_val' ";
				}
				else
				{
					$this->builtQuery .= "OR {$this->lcw}$col{$this->rcw} $operator '$prepend_val$val$append_val' ";
				}
			}
		}
		return $this;
	}
	public function Join($join)
	{
		if( is_string($join) )
		{
			$this->builtQuery .= "AND $join ";
		}
		elseif( is_array($join) )
		{
			foreach($join as $col => $val)
			{
				$vals1 = $this->SanitizeColumns($col);
				$vals2 = $this->SanitizeColumns($val);
				$this->builtQuery .= "AND {$vals1[0]} = {$vals2[0]} ";
			}
		}
		return $this;
	}
	public function LeftJoin($table, $on)
	{
		$this->builtQuery .= "LEFT JOIN ";
		$this->builtQuery .= "$table ON $on ";
	
		return $this;
	}
	public function GroupBy($col)
	{
		if( strstr($col, '.') )
		{
			list($alias, $_col) = explode('.', $col);
			$this->builtQuery .= "GROUP BY {$this->lcw}$alias{$this->rcw}.{$this->lcw}$_col{$this->rcw} ";
		}
		else
		{
			$this->builtQuery .= "GROUP BY {$this->lcw}$col{$this->rcw} ";
		}
		return $this;
	}
	/**
	 *
	 * @param string $column
	 * @return SB_Database
	 */
	public function OrderBy($column, $order = 'desc'){}
	/**
	 *
	 * @param int $limit
	 * @param int $offset
	 * @return SB_Database
	 */
	public function Limit($limit, $offset){}
	public function AppendSQL($sql)
	{
		$this->builtQuery .= $sql . ' ';
	}
	public function SanitizeColumns($cols, $table_alias = null)
	{
		if( !is_array($cols) )
		{
			$cols = array_map('trim', explode(',', $cols));
		}
		$scols = array();
		foreach($cols as $col)
		{
			if( strstr($col, '(') || strstr($col, '*') )
			{
				$scols[] = $col;
			}
			else 
			{
				if( $pos = stripos($col, ' as ') )
				{
					$_col = substr($col, 0, $pos);
					$col_alias = substr($col, $pos);
					//list($_col, $col_alias) = explode(' AS ', strtoupper($col));
					list($alias, $__col) = explode('.', strtolower($_col));
					$scols[] = "{$this->lcw}$alias{$this->rcw}.{$this->lcw}$__col{$this->rcw} $col_alias";
				}
				elseif( strstr($col, '.') )
				{
					list($table_alias, $_col) = explode('.', $col);
					$scols[] = "{$this->lcw}$table_alias{$this->rcw}.{$this->lcw}$_col{$this->rcw}";
				}
				else
				{
					$scols[] = $table_alias ? "{$this->lcw}$table_alias{$this->rcw}.{$this->lcw}$col{$this->rcw}" : 
												"{$this->lcw}$col{$this->rcw}";
				}
				
			}
		}
		/*
		$code = 'return "' . ( $this->db_type == 'postgres' ? '\\'.$this->lcw : $this->lcw) . '$col' . 
											($this->db_type == 'postgres' ? '\\' . $this->rcw : $this->rcw).'";';
		$func = create_function('$col', $code);
		$cols = array_map($func, $cols);
		*/
		return $scols;
	}
	abstract public function Close();
	abstract public function Query($query);
	abstract public function FetchResults($query = null, $class = null, $vars = null);
	abstract public function FetchRow($query = null, $class = null);
	abstract public function GetVar($query = null, $varname = null);
	abstract public function NumRows();
	abstract public function EscapeString($str);
	public function Insert($table, array $data)
	{
		$columns = array_keys($data);
		$vals = array_values($data);
		$columns = $this->SanitizeColumns($columns);
		$query = "INSERT INTO $table (".implode(',', $columns).") VALUES(";
		foreach($vals as $index => $v)
		{
			$_v = trim($v);
			//if( is_numeric($v) && (strstr($v, '.') || strstr($v, ',')) && !strstr($v, '@') )
			if( sb_is_float($_v) )
			{
				$query .= sb_float_db(trim($_v)) . ',';
			}
			elseif( sb_is_datetime($_v) )
			{
				$_v = $this->EscapeString($_v);
				$query .= "'$_v',";
			}
			elseif( sb_is_int($_v) )
			{
				$query .= "'$_v',";
			}
			elseif( strstr($_v, 'OP[') !== false )
			{
				$query .= str_replace(array('OP[', ']'), '', $_v) . ',';
			}
			else
			{
				$_v = $this->EscapeString($_v);
				$query .= "'$_v',";
			}
		}
		$query = substr($query, 0, -1) . ");";
		$this->Query($query);
		
		return $this->lastId;
	}
	/**
	 * Insert using bulk method
	 * 
	 * @param string $table
	 * @param array $data
	 * @return boolean
	 */
	public function InsertBulk($table, $data)
	{
		if( !is_array($data) || count($data) <= 0 )
			return false;
		$columns = array_keys($data[0]);
		//##wrap the columns
		$cols = array_map(create_function('$col', 'return "'.$this->lcw.'$col'.$this->rcw.'";'), $columns);
		$query = "INSERT INTO $table (".implode(',', $cols).") VALUES";
		foreach($data as $d)
		{
			$row_data = array_map('trim', $d);
			$query .= "(";
			foreach($columns as $col)
			{
				$col_data = trim($row_data[$col]);
				//if( is_numeric($col_data) && (strstr($col_data, '.') || strstr($col_data, ',')) && !strstr($col_data, '@') )
				if( sb_is_float($col_data) )
				{
					//$query .= number_format($col_data, 2, '.', '') . ',';
					$query .= sb_float_db($col_data) . ',';
				}
				elseif( sb_is_datetime($col_data) )
				{
					$_v = $this->EscapeString($col_data);
					$query .= "'$_v',";
				}
				elseif( sb_is_int($col_data) )
				{
					$query .= "'$col_data',";
				}
				elseif( strstr($col_data, 'OP[') !== false )
				{
					$query .= str_replace(array('OP[', ']'), '', $col_data) . ',';
				}
				else
				{
					$query .= sprintf("'%s',", $this->EscapeString($col_data));
				}
				
			}
			$query = rtrim($query, ',') . "),";
		}
		$query = rtrim($query, ',');
		$this->Query($query);
		
		return true;
	}
	public function Update($table, array $data, array $where)
	{
		$query = "UPDATE $table SET ";
		foreach($data as $col => $val)
		{
			$_val = trim($val);
			//if( is_numeric($val) && (strstr($val, '.') || strstr($val, ',')) && !strstr($val, '@') )
			if( sb_is_float($_val) )
			{
				//$query .= sprintf("{$this->lcw}$col{$this->rcw} = %s,", number_format($val, 2, '.', ''));
				$query .= sprintf("{$this->lcw}$col{$this->rcw} = %s,", sb_float_db($_val));
			}
			elseif( sb_is_datetime($_val) )
			{
				$_val = $this->EscapeString($_val);
				$query .= "{$this->lcw}$col{$this->rcw} = '$_val',";
			}
			elseif( sb_is_int($_val) )
			{
				$query .=  "{$this->lcw}$col{$this->rcw} = '$_val',";
			}
			elseif( strstr($_val, 'OP[') !== false )
			{
				$query .= sprintf("{$this->lcw}$col{$this->rcw} = %s,", str_replace(array('OP[', ']'), '', $_val));
			}
			else 
			{
				$_val = $this->EscapeString($val);
				$query .= "{$this->lcw}$col{$this->rcw} = '$_val',";
			}
			/*
			if( is_numeric($_val) )
				$query .= "$col = $_val,";
			elseif( is_string($_val) )
			*/
		}
		$query = substr($query, 0, -1);
		$query .= " WHERE ";
		foreach($where as $col => $val)
		{
			$_val = $this->EscapeString($val);
			//if( is_numeric($_val) )
				//$query .= "$col = $_val AND ";
			//elseif( is_string($_val) )
			$query .= "{$this->lcw}$col{$this->rcw} = '$_val' AND ";
		}
		$query = substr($query, 0, -4);
		//print $query;die();
		$this->Query($query);
	}
	public function Delete($table, array $where)
	{
		$cols = array_keys($where);
		$vals = array_values($where);
		$query = "DELETE FROM $table WHERE ";
		foreach($where as $col => $val)
		{
			$_val = $this->EscapeString($val);
			if( is_numeric($_val) )
				$query .= "{$this->lcw}$col{$this->rcw} = $_val AND ";
			else
				$query .= "{$this->lcw}$col{$this->rcw} = '$_val' AND ";
		}
		$query = substr($query, 0, -4);
		return $this->Query($query);
	}
	public function BeginTransaction(){}
	public function EndTransaction(){}
}