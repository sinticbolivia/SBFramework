<?php
abstract class SB_ORMObject extends SB_Object
{
	protected $dbh;
	protected $_dbData = null;
	protected $meta = array();
	public abstract function GetDbData($id);
	public abstract function SetDbData($data);
	protected function __construct()
	{
		$this->_dbData = new stdClass();
		$this->dbh = SB_Factory::getDbh();
	}
	public function jsonSerialize()
	{
		return (object)array_merge((array)$this->_dbData, (array)$this->_data, (array)$this->meta);
	}
	public function __get($var)
	{
		if( is_object($this->_dbData) && property_exists($this->_dbData, $var) )
			return $this->_dbData->$var;
		
		if( isset($this->meta[$var]))
			return $this->meta[$var];
		
		return parent::__get($var);
	}
	public function __set($var, $value)
	{
		//if( property_exists($this->_dbData, $var) )
			$this->_dbData->$var = $value;
			
		parent::__set($var, $value);
	}
}