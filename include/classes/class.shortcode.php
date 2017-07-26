<?php
class SB_Shortcode
{
	protected static $tags = array();
	
	public static function AddShortcode($tag, $callback, $priority = 10)
	{
		self::$tags[$tag] = array('tag' => $tag, 'callback' => $callback, 'priority' => $priority);
	}
	public static function ParseShortcodes($string)
	{
		if( defined('LT_ADMIN') )
			return $string;
		if( !preg_match_all('/\[(\w+)(.*?)\]/', $string, $matches) )
			return $string;
		
		//print_r($matches);
		//print_r(self::$tags);
		$tags = $matches[1];
		$str_args = $matches[2];
		
		foreach($tags as $index => $tag)
		{
			if( !isset(self::$tags[$tag]) ) continue;
			$shortcode = self::$tags[$tag];
			$args = array();
			if( preg_match_all('/\s*([^=,\s]+)\s*=?\s*(?:"((?:[^"]|"")*)"|([^,"]*))\s*,?/', $str_args[$index], $args_matches) )
			{
				//print_r($args_matches);
				foreach($args_matches[1] as $_index => $key )
				{
					if( $args_matches[2][$_index] )
					{
						$args[$key] = trim($args_matches[2][$_index]);
					}
					else 
					{
						$args[] = trim($args_matches[1][$_index]);
					}
				}
				//print_r($args);
				/*
				foreach($args as $p)
				{
					if( !strstr($p, '=') )
					{
						//$args[] = preg_replace('/[^a-zA-Z0-9_-]/', '', $p);
						$args[] = trim(trim($p), '"');
					}
					else
					{
						list($k, $v) = explode('=', $p);
						//$args[$k] = preg_replace('/[^a-zA-Z0-9_-]/', '', $v);
						$args[$k] = trim(trim($v), '"');
					}
				}
				*/
			}
			//print '<!-- '.$matches[0][$index].' -->';
			$shortcode_res = call_user_func($shortcode['callback'], $args);
			$string = SB_Module::do_action('before_shortcode', '', $tag) . 
						str_replace($matches[0][$index], $shortcode_res, $string) .
						SB_Module::do_action('after_shortcode', '', $tag);
		}
		return $string;
	}
}