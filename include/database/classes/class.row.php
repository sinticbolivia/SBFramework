<?php
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
		$index = array_search($this->table->primary_key, $this->table->columns);
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
		foreach($this->table->columns as $col)
		{
			$data[$col] = $this->$col;
		}
		return $data;
	}
}