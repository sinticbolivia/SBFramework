<?php 
class SB_DbTable extends SB_Object
{
	public $class		= __CLASS__;
	public $table;
	public $primary_key = null;
	public $foreign_key	= null;
	public $columns		= array();
	protected $dbh		= null;
	
	protected function __construct()
	{
		$this->dbh = SB_Factory::GetDbh();
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
	 * Get a database table ORM instance
	 * 
	 * @param <string> $table The database table name
	 * @param <bool> $build_class If true, it will build the database table class at runtime, otherwise the table class must exists
	 * @return  SB_DbTable
	 */
	public static function GetTable($table, $build_class = false)
	{
		$class = 'SB_DbTable'.ucfirst($table);
		if( !class_exists($class) )
		{
			if( !$build_class )
				return null;
			$dbh = SB_Factory::GetDbh();
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
				protected function __construct()
				{
					\$this->class = __CLASS__;
					\$this->table = '$table';
					\$this->primary_key = '$key';
					\$this->columns = array($cols);
					parent::__construct();
				}
			}";
			//die($code);
			eval($code);
		}
		return new $class();
	}
	protected function GetColumns()
	{
		$cols = $this->dbh->SanitizeColumns($this->columns);
		return implode(',', $cols);
	}
	public function GetRow($id, $class = null)
	{
		$query = "SELECT " . self::GetColumns() . " ".
					"FROM {$this->table} " .
					"WHERE 1 = 1 ".
					"AND {$this->primary_key} = ";
		if( is_numeric($id) )
		{
			$query .= $id;
		}
		else
		{
			$query .= " '$id'";
		}
		$query .= " LIMIT 1";
		if( !$class )
			return $this->dbh->FetchRow($query);
		$row = $this->dbh->FetchRow($query);
		if( !$row )
			return null;
		if( !class_exists($class) )
			return $row;
		$obj = new $class();
		$obj->SetDbData($row);
		
		return $obj;
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
	public function GetRows($limit = 100, $offset = 0, $conds = array(), $class = null)
	{
		$query = "SELECT " . $this->GetColumns() . 
					" FROM {$this->table} WHERE 1 = 1 ";
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
				$query .= "AND {$this->dbh->lcw}$col{$this->dbh->rcw} = $_val ";
			}
		}
		if( $order )
		{
			$query .= "ORDER BY {$order['orderby']} {$order['order']} ";
		}
		//$query = substr($query, 0, -4);
		if( $limit > 0 )
			$query .= "LIMIT $offset, $limit";
		if( !$class || !class_exists($class) )
			return $this->dbh->FetchResults($query);
		$items = array();
		foreach($this->dbh->FetchResults($query) as $row)
		{
			$item = new $class();
			$item->SetDbData($row);
			$items[] = $item;
		}
		return $items;
	}
	/**
	 * Search a keyword into database table based on columns
	 * 
	 * @param <string> $keyword 
	 * @param <Array> $columns 
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
	
}