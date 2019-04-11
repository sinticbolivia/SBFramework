<?php
namespace SinticBolivia\SBFramework\Database\Classes;
use SinticBolivia\SBFramework\Classes\SB_Object;
use SinticBolivia\SBFramework\Database\Classes\SB_DbTable;

class SB_DbRow extends SB_Object
{
	/**
	 * @var SB_DbTable
	 */
	protected $table;

	public function __construct($table = null)
	{
		if( $table )
			$this->table = $table;
	}
	protected function VerifyRelations()
	{
		if( $this->table->hasOne )
		{
			$this->{$this->table->hasOne['member']} = null;
		}
	}
	public function Save()
	{
		//var_dump($this->table);
		$index = array_search($this->table->primary_key, array_keys($this->table->columns));
		if( $index !== false )
		{
			$id = $this->{$this->table->primary_key};
			$this->table->UpdateRow($id, $this->GetRowData());

			return true;
		}

		return $this->table->Insert(get_object_vars($this));
	}
	/**
	 * Delete this record
	 * @return bool
	 */
	public function Delete()
	{
		return $this->table->Delete($this->{$this->table->primary_key});
	}
	/**
	 * Get the row data into an array
	 *
	 * @return array
	 */
	public function GetRowData()
	{
		//$self = new ReflectionObject();
		//$self->getProperties(ReflectionProperty::IS_PUBLIC);
		$data = array();
		if( !$this->table )
			return $data;
		
		foreach($this->table->columns as $col => $col_data)
		{
			if( $col_data['type'] == (int)SB_DbTable::TYPE_TINYINT || $col_data['type'] == (int)SB_DbTable::TYPE_INTEGER )
				$data[$col] = (int)$this->$col;
			elseif( $col_data['type'] == SB_DbTable::TYPE_FLOAT || $col_data['type'] == SB_DbTable::TYPE_DECIMAL )
				$data[$col] = (float)$this->$col;
			elseif( $col_data['type'] == SB_DbTable::TYPE_DATE && empty($this->$col) )
				continue;//$data[$col] = SB_DbTable::MIN_DATE;	
			elseif( $col_data['type'] == SB_DbTable::TYPE_DATE && empty($this->$col) )
				continue;//$data[$col] = SB_DbTable::MIN_DATETIME;	
			else
				$data[$col] = $this->$col;	
		}
		
		return $data;
	}
    public function jsonSerialize()
	{
		return $this->GetRowData();
	}
	public function __get($var)
	{
		if( $var && isset($this->table->hasMany[$var]) )
		{
			$max_rows = 100;
			if( !isset($this->$var) )
			{
				$class = $this->table->hasMany[$var];
				$table = new $class();
				$this->$var = $table->GetRows($max_rows, 0, array($this->table->primary_key => $this->{$this->table->primary_key}));
			}
			return $this->$var;
		}
	}
}
