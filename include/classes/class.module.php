<?php
class SB_Module
{
	protected static $_actions = array();
	
	public static function add_action($tag, $callback, $priority = 10)
	{
		if( !isset(self::$_actions[$tag]) )
			self::$_actions[$tag] = array();
		$args = func_get_args();
		//array_shift($args);
		unset($args[0], $args[1]);
		sort($args);
		$hook = array('callback' => $callback, 'args' => $args);
		if( !isset(self::$_actions[$tag][$priority]) )
		{
			self::$_actions[$tag][$priority] = $hook;
		}
		elseif( isset(self::$_actions[$tag][$priority]) && !isset(self::$_actions[$tag][$priority + 1]) )
		{
			self::$_actions[$tag][$priority + 1] = $hook;
		}
		else 
		{
			/*
			var_dump("priority: $priority");
			print_r($hook);
			print_r(self::$_actions[$tag]);
			$head = array_slice(self::$_actions[$tag], 0, $priority);
			$tail = array_slice(self::$_actions[$tag], $priority + 1, count(self::$_actions[$tag]));
			var_dump("head");
			print_r($head);
			var_dump("tail");
			print_r($tail);
			//$tail = array_merge($tail, array($new_menu));
			$tail = array_merge($hook, $tail);
			self::$_actions[$tag] = array_merge($head, $tail);
			*/
			while( isset(self::$_actions[$tag][$priority]) )
			{
				$priority++;
			}
			self::$_actions[$tag][$priority] = $hook;
		}
	}
	public static function do_action_ref($tag, &$param1 = null, &$param2 = null,&$param3 = null,&$param4 = null,&$param5 = null,&$param6 = null,
										&$param7 = null,&$param8 = null,&$param9 = null,&$param10 = null,&$param11 = null,&$param12 = null,
										&$param13 = null,&$param14 = null, &$param15 = null)
	{
		$args = array();
		$argc = func_num_args();
		for ($i = 0; $i < $argc; $i++)
		{
			if( $i == 0 )
			{
			$args[] = $tag;
			continue;
			}
			$name 	= 'param'.$i;
			if( $$name === null )
				break;
			$args[] =& $$name;
		}
		if( !isset(self::$_actions[$tag]) && isset($args[1]) )
			return $args[1];
		if( !isset(self::$_actions[$tag]) )
			return null;
		$args = array_slice($args, 1);
		$res = null;
		$max_priority = max(array_keys(self::$_actions[$tag]));
		for($i = 0; $i <= $max_priority; $i++ )
		{
			if( !isset(self::$_actions[$tag][$i]) ) continue;
			$res = call_user_func_array(self::$_actions[$tag][$i]['callback'], $args);
			//if( $res !== null ) $args[0] = $res;
		}
	}
	public static function do_action($tag)
	{
		$args = func_get_args();
		if( !isset(self::$_actions[$tag]) && isset($args[1]) )
			return $args[1];
		if( !isset(self::$_actions[$tag]) )
			return null;
		$args = array_slice($args, 1);
		$res = null;
		
		$max_priority = max(array_keys(self::$_actions[$tag]));
		for($i = 0; $i <= $max_priority; $i++ )
		{
			if( !isset(self::$_actions[$tag][$i]) ) continue;
			$res = call_user_func_array(self::$_actions[$tag][$i]['callback'], $args);
			if( $res !== null ) $args[0] = $res;
		}
		
		return $res;
	}
	public static function moduleExists($mod_name)
	{
		$module_path = MODULES_DIR . SB_DS . 'mod_' . $mod_name;
		if( !is_dir($module_path) )
			return false;
		
		$module_file = $module_path . SB_DS . 'mod_' . $mod_name . '.info';
		if( !file_exists($module_file) )
			return false;
		
		return true;
	}
	public static function loadModule($mod_name)
	{
		if( !self::moduleExists($mod_name) )
			throw new Exception('The module ' . $mod_name . ' does not exists');
		$module_path = MODULES_DIR . SB_DS . $mod_name;
		$module_file = $module_path . SB_DS . $mod_name . '.php';
		$app = SB_Factory::getApplication();
		$app->currentModule = $mod_name;
		$app->currentModulePath = $module_path;
		define('MODULE_PATH', $app->currentModulePath);
		define('MODULE_URL', MODULES_URL . '/' . $mod_name);
		//include module controllers
		if( file_exists(MODULE_DIR . SB_DS . 'controller.php') )
			require_once MODULE_DIR . SB_DS . 'controller.php';
		if( is_dir(MODULE_PATH . SB_DS . 'controllers') )
			sb_include_dir(MODULE_DIR . SB_DS . 'controllers');
		
		//include module file
		require_once $module_file;
		
		return $module_path;
	}
	public static function getEnabledModules()
	{
		$modules = sb_get_parameter('modules', array());
		
		return (array)$modules;
		
	}
	/**
	 * Get all available/installed modules
	 * 
	 * @return multitype:Ambigous <NULL, multitype:, multitype:string >
	 */
	public static function getAvailableModules()
	{
		$modules = array();
		$dh = opendir(MODULES_DIR);
		while( ($file = readdir($dh)) != false )
		{
			if( $file == '.' || $file == '..' ) continue;
			$mod = MODULES_DIR . SB_DS . $file;
			if( is_dir($mod) )
			{
				$info_file = $mod . SB_DS . basename($mod) . '.info';
				if( file_exists($info_file) )
				{
					$info = self::getModuleInfo($info_file);
					if( $info )
						$modules[] = $info;
				}
			}
		}
		closedir($dh);
		
		return $modules;
	}
	/**
	 * Read module info from main module file and return into array
	 * 
	 * @param string $module_file
	 * @return NULL|array
	 */
	public static function getModuleInfo($module_file)
	{
		if( !file_exists($module_file) )
			return null;
		$fh = fopen($module_file, 'r');
		$info = fread($fh, 8192);
		fclose($fh);
		//$info 		= preg_replace('/\s+/', '', $info);
		$mod_info 	= json_decode($info);
		return $mod_info;
	}
	public static function loadLanguage($module, $domain)
	{
		$lang = SB_Factory::getApplication()->getParameter('lang', 'en');
		$path = MODULES_PATH . SB_DS . $module . SB_DS . 'locale';
		bindtextdomain($domain, './locale/nocache');
		bindtextdomain($domain, $path);
		bind_textdomain_codeset($domain, 'UTF-8');
		//SB_Language::loadLanguage($lang, $domain, $path);
	}
	public static function RunSQL($module)
	{
		$dbh 		= SB_Factory::getDbh();
		$sql_file = sprintf("%sdatabase.%s.sql", self::GetModuleDir($module) . SB_DS . 'sql' . SB_DS, $dbh->db_type);
		if( !file_exists($sql_file) )
		{
			return null;
		}
		
		$sql 		= file_get_contents($sql_file);
		$queries 	= array_map('trim', explode(";", $sql));
		foreach($queries as $query)
		{
			if( empty($query) ) continue;
			$dbh->Query($query);
		}
	}
	public static function GetModuleDir($module)
	{
		return MODULES_DIR . SB_DS . 'mod_'.$module;
	}
	public static function GetControllerClass($module, $suffix = '')
	{
		//$module_dir		= self::GetModuleDir($module);
		$class_prefix 	= defined('LT_ADMIN') ? 'LT_AdminController' : 'LT_Controller';
		$controller_class	= $class_prefix . ucfirst(strtolower($module)) . $suffix;
		
		return $controller_class;
	}
	public static function GetControllerFile($module, $suffix = '', $in_dir = false)
	{
		$module_dir 	= self::GetModuleDir($module);
		$prefix	= defined('LT_ADMIN') ? 'admin.' : '';
		$controller_file = $module_dir . SB_DS;
		if( $in_dir )
		{
			$controllers_dir = 'controllers';
			$controller_file .= $controllers_dir . SB_DS;
		}
		
		$suffix = !empty($suffix) ? '.'.$suffix : '';
		$controller_file .= $prefix . 'controller' . $suffix . '.php';
		return $controller_file;
	}
	public static function GetControllerInstance($module, $class_suffix = '', $file_suffix = '', $in_folder = false)
	{
		$class = self::GetControllerClass($module, $class_suffix);
		$class_file = self::GetControllerFile($module, $file_suffix, $in_folder);
		if( !file_exists($class_file) )
		{
			return null;
		}
		require_once $class_file;
		if( !class_exists($class) )
			return null;
		
		return new $class();
	}
	public static function IsEnabled($module)
	{
		$mods = self::getEnabledModules();
		return array_search('mod_'.$module, $mods);
	}
}