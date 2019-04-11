<?php
namespace SinticBolivia\SBFramework\Classes;
class SB_Request
{
	protected 	$vars;
	protected 	static $instance;
	public 		static	$rawPath;
	public 		static $path;
	public		static $script;
	public		static $queryString;
	public 		static $responseCode;
	public		static $request;
	public		static $requestMethod = 'GET';
	public      static $pathValues = array();
	protected function __construct()
	{
		//if( !defined('LT_INSTALL') )
			//$this->ValidateRequest();
	}
	protected function ValidateRequest()
	{
		//print_r($_SERVER);
		$req_url 			= urldecode($_SERVER['REQUEST_URI']);//print_r($_SERVER);
		$url 				= parse_url($req_url);
		//print_r($url);
		self::$rawPath 		= isset($url['path']) ? $url['path'] : '';
		self::$queryString 	= isset($url['query']) ? $url['query'] : null;
		self::$script 		= basename(self::$path);
		self::$path 		= str_replace(BASEURL, '', HTTP_HOST . self::$rawPath);
		self::$request 		= str_replace(BASEURL, '', HTTP_HOST . self::$rawPath . (!empty(self::$queryString) ? '?' . self::$queryString : ''));
		//var_dump(self::$path);
		if( file_exists(BASEPATH . SB_DS . self::$script) )
			self::$responseCode = 200;
		else
			self::$responseCode = 400;
		self::$requestMethod = $_SERVER['REQUEST_METHOD'];
	}
	public static function Start()
	{
		self::$instance = new SB_Request();
		self::$instance->ValidateRequest();
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
		
		if( self::$requestMethod == 'GET' && isset($_GET[$var]) )
			return $_GET[$var];
		elseif( self::$requestMethod == 'POST' && isset($_POST[$var]) )
			return $_POST[$var];
		elseif( isset($_REQUEST[$var]) )
			return $_REQUEST[$var];
		else 
			return $default;
		//return isset(self::$instance->vars[$var]) ? self::$instance->vars[$var] : $default;
	}
	public static function setVar($var, $value, $type = null)
	{
		$type = $type ? $type : self::$requestMethod;
		self::$instance->vars[$var] = $value;
		if( $type == 'GET' )
			$_GET[$var] = $value;
		elseif( $type == 'POST' || $type == 'PUT' )
			$_POST[$var] = $value;
	
		$_REQUEST[$var] = $value;
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
		foreach($vars as $alias => $var)
		{
			if( strstr($var, ':') )
			{
				list($type, $_var) 	= array_map('trim', explode(':', $var));
				$method_name 		= 'get'.ucfirst($type);
				$exists				= method_exists(self::class, $method_name);
				if( is_string($alias) )
					$_var = $alias;
				$data[$_var] 		= $exists ? 
									call_user_func(array('SinticBolivia\SBFramework\Classes\SB_Request', $method_name), $_var) : 
									trim(self::$instance->vars[$var]);
			}
			else
			{
				$data[ is_string($alias) ? $alias : $var] = isset(self::$instance->vars[$var]) ? trim(self::$instance->vars[$var]) : null;
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
	public static function getArray($array_name)
	{
		return (array)self::getVar($array_name);
	}
	public static function getArrayVar($array_name, $var, $default = null)
	{
		if( !($array = self::getVar($array_name)) )
			return $default;
		 return isset($array[$var]) ? $array[$var] : $default;
	}
    
	/**
	 * Save the current request data
	 * 
	 * @param string $name
	 */
	public static function Save($name)
	{
		SB_Session::setVar($name, self::$instance->vars);
	}
    public static function Restore($name)
    {
		$data = self::GetSaved($name);
		if( !$data )
			return false;
		//##restore the data
		foreach($data as $key => $val)
		{
			self::setVar($key, $val);
		}
		return true;
	}
	/**
	 * Recover a saved request data
	 * 
	 * @param string $name
	 * @return NULL|Ambigous <string, mixed>
	 */
	public static function GetSaved($name)
	{
		$data = SB_Session::getVar($name);
		if( !$data )
			return null;
		SB_Session::unsetVar($name);
		return $data;
	}
	/**
	 * Get the request raw body
	 * 
	 * @return string
	 */
	public static function GetBody()
	{
		return file_get_contents('php://input');
	}
	/**
	 * Gets request body and parse as JSON notation
	 * 
	 * @return object
	 */
	public static function ToJSON()
	{
		return json_decode(self::GetBody());
	}
	/**
	 * Transform the GET or POST request data to object
	 * 
	 * @return StdClass
	 */
	public static function ToObject()
	{
		return self::$requestMethod == 'GET' ? (object)$_GET : (object)$_POST;
	}
	/**
	 * Get the request header by name
	 * 
	 * @param string $headerName 
	 * @return string The header value
	 */
	public static function GetHeader($headerName)
	{
		$headers = getallheaders();
		
		return isset($headers[$headerName]) ? $headers[$headerName] : null;
	}
    public static function GetPathComponents($prefix = null)
    {
        $path = $prefix ? str_replace($prefix, '', self::$path) : self::$path;
        $data = array();
        $parts = explode('/', ltrim($path, '/'));
        
        for($i = 0; $i < count($parts); $i += 2)
        {
            $data[$parts[$i]] = isset($parts[$i + 1]) ? $parts[$i + 1] : null;
        }
        
        self::$pathValues = $data;
        return $data;
    }
    public static function GetPathComponentValue($component)
    {
        
    }
    /**
     * Get the request instance
     * 
     * @return SB_Request
     */
    public static function GetInstance(){return self::$instance;}
    
    public function __call($name, $arguments) 
    {
        $function_name = "SB_Request::$name";
        //var_dump($function_name);die(__FILE__);
        if( function_exists($function_name) )
            return call_user_func_array($function_name, $arguments);
    }
    
    /*
    public function getVar($var, $default = null){return self::getVar($var, $default);}
    public function setVar($var, $value, $type = null){self::setVar($var, $value, $type);}
    public function getVars($vars){return self::getVars($vars);}
    public function getString($var, $default = null){return self::getString($var, $default);}
    public function getInt($var, $default = 0){self::getInt($var, $default);}
    public function getFloat($var, $default = 0){return self::getFloat($var, $default);}
    public function getTimeStamp($var, $default = null){return self::getTimeStamp($var, $default);}
    public function getDate($var, $default = null){return self::getDate($var, $default);}
    public function getDateTime($var, $default = null){return self::getInt($var, $default);}
    public function GetSaved($name){return self::GetSaved($name);}
    public function Save($name){self::Save($name);}
    public function getTask($default = null){return self::getTask($default);}
    public function getArrayVar($array_name, $var, $default = null){return self::getArrayVar($array_name, $var, $default);}
    */
}
