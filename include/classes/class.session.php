<?php
class SB_Session
{
	public static function start()
	{
		//session_set_cookie_params(86400);
		//ini_set('session.gc_maxlifetime', 86400);
		//$id = session_id();
		@session_start();
	}
	public static function destroy()
	{
		session_destroy();
	}
	public static function setVar($var, $value, $ns = null)
	{
		if( $ns && !empty($ns) )
		{
			//error_log("setting var: [$ns][$var] => ".print_r($value, 1));
			if( !isset($_SESSION[$ns]) )
				$_SESSION[$ns] = array();
			$_SESSION[$ns][$var] = $value;
			return true;
		}
		//error_log("setting var: [$var] => ".print_r($value, 1));
		$_SESSION[$var] = $value;
	}
	public static function unsetVar($var, $ns = false)
	{
		if( $ns && isset($_SESSION[$ns]) )
		{
			$_SESSION[$ns][$var] = null;
			unset($_SESSION[$ns][$var]);
			
			return true;
		}
		$_SESSION[$var] = null;
		unset($_SESSION[$var]);
	}
	public static function &getVar($var, $default = null, $ns = null)
	{
		if( $ns && !isset($_SESSION[$ns][$var]) )
			return $default;
		if( $ns && isset($_SESSION[$ns][$var]) )
			return $_SESSION[$ns][$var];
		
		if( !isset($_SESSION[$var]) )
			return $default;
		
		return $_SESSION[$var];
	}
}