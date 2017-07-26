<?php
class SB_Request
{
	protected 	$vars;
	protected 	static $instance;
	public 		static $path;
	public		static $script;
	public		static $queryString;
	public 		static $responseCode;
	public		static $request;
	
	protected function __construct()
	{
		//if( !defined('LT_INSTALL') )
			//$this->ValidateRequest();
	}
	protected function ValidateRequest()
	{
		$req_url 	= urldecode($_SERVER['REQUEST_URI']);//print_r($_SERVER);
		$url 		= parse_url($req_url);
		self::$path 		= isset($url['path']) ? $url['path'] : '';
		self::$queryString 	= isset($url['query']) ? $url['query'] : null;
		self::$script 		= basename(self::$path);
		self::$request 		= str_replace(BASEURL, '', HTTP_HOST . self::$path . (!empty(self::$queryString) ? '?' . self::$queryString : ''));
		if( file_exists(BASEPATH . SB_DS . self::$script) )
			self::$responseCode = 200;
		else
			self::$responseCode = 400;
		
	}
	public static function Start()
	{
		self::$instance = new SB_Request();
		self::$instance->MergeServerVars();
	}
	protected function MergeServerVars()
	{
		if( $this->vars == null || empty($this->vars) )
		{
			$this->vars = array_merge($_GET, $_POST);
		}
	}
	public static function getVar($var, $default = null)
	{
		return isset(self::$instance->vars[$var]) ? self::$instance->vars[$var] : $default;
	}
	public static function setVar($var, $value)
	{
		self::$instance->vars[$var] = $value;
	}
	/**
	 * Get parameters from request and validate to a specific data type
	 * 
	 * @param array $vars
	 * @return array $params
	 */
	public static function getVars($vars)
	{
		$data = array();
		foreach($vars as $var)
		{
			if( strstr($var, ':') )
			{
				list($type, $_var) = array_map('trim', explode(':', $var));
				$method_name = 'get'.ucfirst($type);
				$data[$_var] = method_exists('SB_Request', $method_name) ? call_user_func(array('SB_Request', $method_name), $_var) : trim(self::$instance->vars[$var]);
			}
			else
			{
				$data[$var] = isset(self::$instance->vars[$var]) ? trim(self::$instance->vars[$var]) : null;
			}
		}
		return $data;
	}
	public static function getString($var, $default = null)
	{
		$string = trim(self::getVar($var, $default));
		return $string;
		//return addslashes($string);
	}
	public static function getInt($var, $default = 0)
	{
		$integer = (int)self::getVar($var, $default);
		
		return $integer;
	}
	public static function getFloat($var, $default = 0.0)
	{
		return number_format((float)str_replace(',', '', self::getVar($var, $default)), 2, '.', '');
	}
	public static function getTimeStamp($var, $default = null)
	{
		$value = self::getString($var, $default);
		if( !$value )
			return $default;
		return strtotime(str_replace('/', '-', $value));
	}
	/**
	 * Get date with default format
	 * Y-m-d
	 * @param string $var
	 * @param string $default
	 * @return string
	 */
	public static function getDate($var, $default = null)
	{
		$value = self::getString($var, $default);
		if( !$value )
			return $default;
		$time = strtotime(str_replace('/', '-', $value));
		return date('Y-m-d', $time);
	}
	public static function getDateTime($var, $default = null)
	{
		$value = self::getString($var);
		$time = strtotime(str_replace('/', '-', $value));
		return date('Y-m-d H:i:s', $time);
	}
	public static function getTask($default = null)
	{
		return self::getString('task', $default);
	}
	public static function getArrayVar($array_name, $var, $default = null)
	{
		if( !($array = self::getVar($array_name)) )
			return $default;
		 return isset($array[$var]) ? $array[$var] : $default;
	}
}