<?php 
require_once 'class.row.php';
class SB_DbTable extends SB_Object
{
	public 	$class			= __CLASS__;
	public 	$table;
	public	$tableAlias;
	public 	$primary_key 	= null;
	public 	$foreign_keys	= array();
	public	$columns		= array();
	public	$joins			= array();
	public	$leftJoins		= array();
	public	$constraints	= array();
	public	$hasOne			= null;
	public	$hasMany		= array();
	/**
	 * @var SB_Database
	 */
	protected $dbh		= null;
	
	protected function __construct($dbh = null)
	{
		$this->dbh = $dbh ? $dbh : SB_Factory::GetDbh();
		if( !$this->tableAlias )
			$this->tableAlias = $this->table;
		
	}
	public static function Create()
	{
	}
	public static function Update()
	{
	}
	public static function Drop()
	{
	}
	/**
	 * Add column to table schema definition
	 * 
	 * @param string $name 
	 * @param string $datatype 
	 * @param string $default 
	 * @param bool $pk 
	 * @param bool $fk 
	 * @param string $after 
	 * @return  
	 */
	public function AddColumn($name, $datatype, $default = null, $pk = false, $fk = false, $after = null)
	{
		$DDL = "ALTER TABLE {$this->table} ADD COLUMN $name $datatype ";
		if( $pk )
		{
			if( $this->dbh->db_type == 'mysql' )
				$DDL .= "NOT NULL PRIMARY KEY AUTO_INCREMENT ";
			elseif( $this->dbh->db_type == 'sqlite3' )
				$DDL .= "NOT NULL PRIMARY KEY AUTOINCREMENT ";
		}
		if( $default )
			$DDL .= "DEFAULT $default ";
		if( $after && isset($this->columns[$after] ) )
			$DDL .= "AFTER $after ";
		$this->dbh->Query($DDL);
	}
	/**
	 * Get a database table ORM instance
	 * 
	 * @param <string> $table The database table name
	 * @param <bool> $build_class If true, it will build the database table class at runtime, otherwise the table class must exists
	 * @return  SB_DbTable
	 */
	public static function GetTable($table, $build_class = false, $dbh = null)
	{
		$dbh = $dbh ? $dbh : SB_Factory::getDbh();
		$class = 'SB_DbTable';
		if( $dbh->db_type == 'mysql' ) 
			$class .= ucfirst(strtolower($dbh->databaseName)) . ucfirst($table);
		elseif( $dbh->db_type == 'sqlite3' )
		{
			$dbname = basename($dbh->databaseName);
			$dbname = substr($dbname, 0, strrpos($dbname, '.'));
			$class .= ucfirst($dbname) . ucfirst($table);
		}
		if( !class_exists($class) && $build_class )
		{			
			$cols = '';
			$key = null;
			if( $dbh->db_type == 'mysql' )
			{
				$exists = $dbh->FetchRow("show tables LIKE '{$table}'");
				if( $exists )
				{
					$_cols = $dbh->FetchResults("SHOW COLUMNS FROM {$table}");
					foreach($_cols as $col)
					{
						$cols .= "'{$col->Field}',";
						if( $col->Key == 'PRI' )
							$key = $col->Field;
					}
					$cols = rtrim($cols, ',');
				}
			}
			elseif( $dbh->db_type == 'sqlite3' )
			{
				$query = "PRAGMA table_info('$table')";
				$_cols = $dbh->FetchResults($query);
				
				foreach($_cols as $col)
				{
					$cols .= "'{$col->name}',";
					if( (int)$col->pk )
					{
						$key = $col->name;
					}
				}
				$cols = rtrim($cols, ',');
			}
			$code = "class $class extends SB_DbTable
			{
				protected function __construct(\$dbh = null)
				{
					\$this->class = __CLASS__;
					\$this->table = '$table';
					\$this->primary_key = '$key';
					\$this->columns = array($cols);
					parent::__construct(\$dbh);
				}
			}";
			//die($code);
			eval($code);
		}
		return new $class($dbh);
	}
	public static function GetTableColumns($table, $names_only = false, $dbh = null)
	{
		$dbh = $dbh ? $dbh : SB_Factory::getDbh();
		$cols = array();
		
		if( $dbh->db_type == 'mysql' )
		{
			$exists = $dbh->FetchRow("show tables LIKE '{$table}'");
			if( $exists )
			{
				$_cols = $this->dbh->FetchResults("SHOW COLUMNS FROM {$table}");
				foreach($_cols as $col)
				{
					$cols[$col->Field] = $col;
				}
			}
		}
		elseif( $dbh->db_type == 'sqlite3' )
		{
			$query = "PRAGMA table_info('$table')";
			$_cols = $dbh->FetchResults($query);
			foreach($_cols as $col)
			{
				$cols[$col->name] = $col;
				
			}
		}
		
		return $cols;
	}
	public function GetColumns()
	{
		$cols = $this->dbh->SanitizeColumns($this->columns, $this->tableAlias);
		//var_dump(implode(',', $cols));
		return implode(',', $cols);
	}
	public function BuildQueryParts($many_rows = false)
	{
		$cols 	= $this->GetColumns();
		$tables = "{$this->table} AS {$this->tableAlias}";
		$where 	= !$many_rows ? "AND {$this->tableAlias}.{$this->primary_key} = {primary_key} " : '';
		if( is_array($this->joins) )
		{
			$cols 	.= ',';
			$tables .= ',';
			foreach($this->joins as $jt)
			{
				$cols	.= $jt['table']->GetColumns() . ',';
				$tables .= "{$jt['table']->table} AS {$jt['table']->tableAlias},";
				$where	.= "AND {$this->tableAlias}.{$jt['fk']} = {$jt['table']->tableAlias}.{$jt['table']->primary_key} ";
			}
			$cols	= rtrim($cols, ',');
			$tables = rtrim($tables, ',');
		}
		if( is_array($this->leftJoins) )
		{
			$cols 	.= ',';
			$tables .= ' ';
			foreach($this->leftJoins as $ljt)
			{
				$cols	.= $ljt['table']->GetColumns() . ',';
				$tables .= "LEFT JOIN {$ljt['table']->table} AS {$ljt['table']->tableAlias} ".
								"ON {$ljt['table']->tableAlias}.{$ljt['table']->primary_key} = {$this->tableAlias}.{$ljt['fk']} ";
			}
			$cols	= rtrim($cols, ',');
		}
		return array($cols, $tables, $where);
	}
	/**
	 * Get a table row. If $class exists return an instance of SB_ORMObject base class
	 * @param int $id 
	 * @param string $class 
	 * @return SB_DbRow
	 */
	public function GetRow($id, $class = 'SB_DbRow')
	{
		list($cols, $tables, $where) = $this->BuildQueryParts();
		
		if( is_numeric($id) )
		{
			$where = str_replace(array('{primary_key}'), array($id), $where);
		}
		else
		{
			$where = str_replace(array("'{primary_key}'"), array($id), $where);
		}
		$query = "SELECT $cols ".
					"FROM $tables " .
					"WHERE 1 = 1 ".
					"$where " . 
					"LIMIT 1";
					
		$row = $this->dbh->FetchRow($query, $class);
		$row->table = $this;
		return $row;
		/*
		if( $class == null )
			return $row;
		$obj = new SB_DbRow($this);
		$obj->SetDbData($row);
		return $obj;
		*/
	}
	/**
	 * Fetch table records
	 * 
	 * @param mixed $limit 
	 * @param mixed $offset 
	 * @param mixed $conds 
	 * @param mixed $class 
	 * @return  
	 */
	public function GetRows($limit = 100, $offset = 0, $conds = array(), $class = 'SB_DbRow')
	{
		list($cols, $tables, $where) = $this->BuildQueryParts(true);
		
		$query = "SELECT $cols FROM $tables WHERE 1 = 1 $where ";
		$order = null;
		if( isset($conds['order']) )
		{
			$order = $conds['order'];
			unset($conds['order']);
		}
		if( is_array($conds) )
		{
			foreach($conds as $col => $val)
			{
				$_val = stristr($val, 'JOIN[') ? str_ireplace(array('JOIN[', ']'), '', $val) : 
												"'".$this->dbh->EscapeString($val)."'";
				$query .= "AND {$this->dbh->lcw}{$this->tableAlias}{$this->dbh->rcw}.{$this->dbh->lcw}$col{$this->dbh->rcw} = $_val ";
			}
		}
		if( $order )
		{
			$query .= "ORDER BY {$order['orderby']} {$order['order']} ";
		}
		//$query = substr($query, 0, -4);
		if( $limit > 0 )
			$query .= "LIMIT $offset, $limit";
		$items = $this->dbh->FetchResults($query, $class, array('table' => $this));
		
		return $items;
	}
	public function GetRowsIn($column, $values, $limit = 100, $offset = 0, $conds = array())
	{
		list($cols, $tables, $where) = $this->BuildQueryParts(true);
		
		$query = "SELECT $cols FROM $tables WHERE 1 = 1 $where ";
		$order = null;
		if( isset($conds['order']) )
		{
			$order = $conds['order'];
			unset($conds['order']);
		}
		$query .= "AND {$this->dbh->lcw}{$this->tableAlias}{$this->dbh->rcw}.{$this->dbh->lcw}$column{$this->dbh->rcw} IN(". implode(',', $values) .") ";
		if( is_array($conds) )
		{
			foreach($conds as $col => $val)
			{
				$_val = stristr($val, 'JOIN[') ? str_ireplace(array('JOIN[', ']'), '', $val) :
													"'".$this->dbh->EscapeString($val)."'";
				$query .= "AND {$this->dbh->lcw}{$this->tableAlias}{$this->dbh->rcw}.{$this->dbh->lcw}$col{$this->dbh->rcw} = $_val ";
			}
		}
		if( $order )
		{
			$query .= "ORDER BY {$order['orderby']} {$order['order']} ";
		}
		//$query = substr($query, 0, -4);
		if( $limit > 0 )
			$query .= "LIMIT $offset, $limit";
		$items = $this->dbh->FetchResults($query, 'SB_DbRow');
		
		return $items;
	}
	/**
	 * Search a keyword into database table based on columns
	 * 
	 * @param string $keyword 
	 * @param Array $columns 
	 * @return Array
	 */
	public function Search($keyword, $columns = array(), $conds = array())
	{
		$query = "SELECT " . $this->GetColumns() . " ".
					"FROM {$this->table} ".
					"WHERE 1 = 1 ";
		if( count($columns) )
		{
			$query .= "AND (";
			foreach($columns as $col)
			{
				$query .= "$col LIKE '%$keyword%' OR ";
			}
			$query = substr($query, 0, -3) . ") ";
		}
		
		if( is_array($conds) && count($conds) )
		{
			foreach((array)$conds as $col => $val)
			{
				$query .= "AND {$col} = '$val' ";
			}
			//$query = substr($query, 0, -3) . ")";
		}
		
		//die($query);
		return $this->dbh->FetchResults($query);
	}
	/**
	 * Insert a new record into table and return the new id
	 * 
	 * @param array|object $row_data 
	 * @return  integer The new record id
	 */
	public function Insert($row_data, $is_bulk = false)
	{
		return $is_bulk ? $this->dbh->InsertBulk($this->table, $row_data) : $this->dbh->Insert($this->table, $row_data);
	}
	public function UpdateRow($id, $data)
	{
		$this->dbh->Update($this->table, $data, array($this->primary_key => $id));
	}
	/**
	 * Delete a record from table
	 * 
	 * @param mixed $id The primary key value
	 */
	public function Delete($id)
	{
		$this->dbh->Delete($this->table, array($this->primary_key => $id));
	}
	/**
	 * 
	 * @param string 	$table 
	 * @param string 	$fk 
	 * @param bool		$build_class 
	 * @return SB_DbTable
	 */
	public function &AddJoin($table, $fk, $build_class = false)
	{
		$_join_table = SB_DbTable::GetTable($table, $build_class, $this->dbh);
		
		$this->joins[] 	= array('table' => $_join_table, 'fk' => $fk);
		$index 			= count($this->joins) - 1;
		return $this->joins[$index];
	}
	/**
	 * 
	 * @param string 	$table 
	 * @param string 	$fk 
	 * @param bool		$build_class 
	 * @return SB_DbTable
	 */
	public function &AddLeftJoin($table, $fk, $build_class = false)
	{
		$_join_table = SB_DbTable::GetTable($table, $build_class, $this->dbh);
		
		$this->leftJoins[] 	= array('table' => $_join_table, 'fk' => $fk);
		$index 			= count($this->leftJoins) - 1;
		return $this->leftJoins[$index]['table'];
	}
	/**
	 * Get a table row based on conditions
	 * 
	 * @param array $conds 
	 * @return  SB_DbRow
	 */
	public function QueryRow($conds = array())
	{
		$query = "SELECT " . $this->GetColumns() . 
					" FROM {$this->table} {$this->tableAlias} ".
					"WHERE 1 = 1 ";
		if( is_array($conds) && count($conds) )
		{
			foreach((array)$conds as $_col => $val)
			{
				$cols = $this->dbh->SanitizeColumns($_col);
				list(,$col) = each($cols);
				$query .= "AND {$col} = '$val' ";
			}
		}
		$query .= "LIMIT 1";
		return $this->dbh->FetchRow($query, 'SB_DbRow');
	}
	/**
	 * Get a table row by column and value
	 * 
	 * @param string $column
	 * @param mixed $value
	 * @return NULL|object
	 */
	public function GetBy($column, $value)
	{
		if( !$column || !in_array($column, $this->columns) )
			return null;
		return $this->QueryRow(array($column => $value));
	}
	public function __call($method, $args)
	{
		/**
		 * Overload GetBy method to get based on table columns
		 */
		if( strstr($method, 'GetBy') && count($args) )
		{
			$column = strtolower(str_replace('GetBy', '', $method));
			if( $column && in_array($column, $this->columns) )
				return $this->QueryRow(array($column => $args[0]));		
		}
	}
	public function ValidateData($data)
	{
		
	}
	/**
	 * Count records into table
	 * 
	 * @return int The total rows
	 */
	public function CountRows()
	{
		$query = "SELECT COUNT(*) FROM {$this->table}";
		return (int)$this->dbh->GetVar($query);
	}
}