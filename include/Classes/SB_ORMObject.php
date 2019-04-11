<?php
namespace SinticBolivia\SBFramework\Classes;

use SinticBolivia\SBFramework\Database\SB_Database;

abstract class SB_ORMObject extends SB_Object
{
	/**
	 * @var SB_Database
	 */
	protected $dbh;
	protected $_dbData = null;
	protected $meta = array();
	public abstract function GetDbData($id);
	public abstract function SetDbData($data);
	protected function __construct($dbh = null)
	{
		$this->_dbData = new \stdClass();
		$this->dbh = $dbh ? $dbh : SB_Factory::getDbh();
	}
	public function jsonSerialize()
	{
		return (object)array_merge((array)$this->_dbData, (array)$this->_data, (array)$this->meta);
	}
	public function getAttachments($object_id, $object_type, $attachment_type, $parent = 0)
	{
		$query = "SELECT * FROM attachments
				WHERE object_type = '$object_type'
				AND object_id = $object_id
				AND type = '$attachment_type'";
		if( $parent )
			$query .= " AND parent = $parent";
		
		return $this->dbh->FetchResults($query, '\SinticBolivia\SBFramework\Classes\SB_Attachment');
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
		if( !$this->_dbData )
			$this->_dbData = new stdClass();
			
		$this->_dbData->$var = $value;
			
		parent::__set($var, $value);
	}
}