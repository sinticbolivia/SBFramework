<?php
class SB_Route
{
	public static function _($_url, $type = null)
	{
		$url = BASEURL . '/';
		if( defined('ROUTE_SKIP_ADMIN_URL') )
		{
			$url .= $_url;
			return $url;
		}
		if( $type === null )
		{
			if( defined('LT_ADMIN') )
			{
				$url .= 'admin/' . $_url;
			}
			else
			{
				$url = self::BuildUrl($_url);
			}
		}
		elseif( $type == 'frontend' )
		{
			$url = self::BuildUrl($_url);
		}
		elseif( $type == 'backend' )
		{
			$url .= 'admin/' . $_url;
		}
		return $url;
	}
	public static function SetRoute($the_route)
	{
		parse_str($the_route, $query);
		foreach($query as $p => $v)
		{
			SB_Request::setVar($p, $v);
		}
	}
	protected static function GetComponents()
	{
		
	}
	protected static function BuildUrl($url)
	{
		if( !defined('LT_REWRITE') || !constant('LT_REWRITE') )
			return BASEURL . '/' . $url;
		$_url = substr($url, strpos($url, '?') + 1);
		parse_str($_url, $array);
		if( !isset($array['mod']) )
			return $url;
		
		$function = 'lt_' . $array['mod'] . '_rewrite';
		if( !function_exists($function) )
		{
			return $url;
		}
		
		return $function($url, $array);
	}
}