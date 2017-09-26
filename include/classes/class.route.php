<?php
class SB_Route
{
	public static function _($_url, $type = null)
	{
		$url = BASEURL . '/';
		if( $_url == '/' )
			return lt_is_admin() ? $url . 'admin/' : $url;
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
	public static function SetRoute($the_route, $request_method = 'GET')
	{
		$request_method = strtoupper($request_method);
		if( in_array($request_method, array('POST', 'PUT')) )
		{
			$request_method = 'POST';
		}
		parse_str($the_route, $query);
		foreach($query as $p => $v)
		{
			SB_Request::setVar($p, $v, $request_method);
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
			return BASEURL . '/' . $url;
		}
		
		$seo_url = $function($url, $array);
		//##remove common vars
		unset($array['mod'], $array['task'], $array['view'], $array['id'], $array['slug']);
		if( count($array) )
		{
			if( strstr($seo_url, '?') )
			{
				list($url, $query_string) = explode('?', $seo_url);
				$query_string = sb_querystring_append($query_string, $array);
				$seo_url = $url . '?' . $query_string;
			}
			else
			{
				$query_string = http_build_query($array);
				if( !empty($query_string) )
				{
					$seo_url .= '?' . $query_string;
				}
			}
			
		}
		return $seo_url;
	}
}