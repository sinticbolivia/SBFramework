<?php
if( !interface_exists('JsonSerializable') ): 
interface JsonSerializable
{
	public function jsonSerialize();
}
endif;
class SB_Object extends stdClass implements JsonSerializable
{
	protected $_data = array();
	
	/**
	 * Build a new object unique alphanumeric code
	 * 
	 * @return string
	 */
	public static function buildNewCode()
	{
		$string = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		$new_code = '';
		$max_length = 8;
		for($i = 0; $i < $max_length; $i++)
		{
			$index = rand(0, strlen($string) - 1);
			$new_code .= $string[$index];
		}
		
		return strtoupper($new_code);
	}
	public function __toString()
	{
		return print_r($this, 1);
	}
	public function getAttachments($object_id, $object_type, $attachment_type)
	{
		$query = "SELECT * FROM attachments WHERE object_type = '$object_type' AND object_id = $object_id AND type = '$attachment_type'";
	}
	public function __get($var)
	{
		if( isset($this->$var) )
			return $this->$var;
		return null;
	}
	public function __set($var, $value)
	{
		$this->$var = $value;
	}
	public function jsonSerialize()
	{
		return $this->_data;
	}
	/**
	 * Set object properties values from data
	 * 
	 * @param array $data The data to bind into object
	 * @param array $props	The properties to bind into object
	 */
	public function Bind($data, $props = null)
	{
		foreach($data as $prop => $value)
		{
			if( isset($this->$prop) || in_array($prop, $props) )
				$this->$prop = trim($value);
		}
	}
}