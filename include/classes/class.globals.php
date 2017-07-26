<?php
class SB_Globals
{
	protected static $vars = array();
	
	public static function SetVar($varname, $value)
	{
		self::$vars[$varname] = $value;
	}
	public static function &GetVar($varname)
	{
		$var = null;
		if( !isset(self::$vars[$varname]) )
			return $var;
		
		return self::$vars[$varname];
	}
}