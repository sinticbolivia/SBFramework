<?php
/**
 * @package SBFramework
 */

/**
 * The base application case to initializa any web application
 * 
 * @author SinticBolivia <info@sinticbolivia.net>
 * @version 1.0.0
 */
namespace SinticBolivia\SBFramework\Classes;

class SB_Application extends SB_Object
{
	protected	$defaultModules = array(
			'mod_modules',
			'mod_users',
			'mod_settings'
	);
	protected	$controller;
	protected 	$view;
	protected	$module;
    public      $moduleNamespace;
	protected 	$templateHtml;
	protected	$template;
	protected	$request;
	
	private function __construct()
	{
		//##create a template instance
		$this->template		= new SB_Template();
		$this->template->SetApplication($this);
		$this->request		= SB_Request::GetInstance();
	}
	public function Load()
	{
		if( !is_dir(UPLOADS_DIR) )
			mkdir(UPLOADS_DIR);
		if( !is_dir(TEMP_DIR) )
			mkdir(TEMP_DIR);
		if( !is_dir(APPLICATIONS_DIR) )
			mkdir(APPLICATIONS_DIR);		
	}
	public function StartRewrite()
	{
		if( !defined('LT_REWRITE') || !constant('LT_REWRITE') )
			return;
		$the_path = SB_Request::$path;
		//var_dump($the_path);
		if( strstr($the_path, '?') && strstr($the_path, '=') )
		{
			return;
		}
		//die($the_path);
		$def = array();
		$routes = SB_Module::do_action('rewrite_routes', $def);
		//print_r($routes);die();
		$the_route = null;
		foreach($routes as $_match => $_route)
		{
			$pattern = '@' . $_match . '@';
			//var_dump($pattern);
			if( preg_match($pattern, $the_path, $matches) )
			{
				//print_r($matches);
				//$the_route = preg_replace($match, '', $route);
				//var_dump($route);
				if( is_array($_route) /*$the_path == $_match && is_array($_route) /*&& isset($_route['mod']) && isset($_route['task'])*/ )
				{
					$route 	= $_route;
					$mod	= $_route['mod'];
					$ns		= '';
					$ctrl 	= isset($route['controller']) ? $route['controller'] : '';
					$task	= isset($route['task']) ? $route['task'] : 'Default';
				
				
					if( isset($route['GET']) || isset($route['POST']) || isset($route['PUT']) || isset($route['DELETE']) )
					{
						if( isset($route[SB_Request::$requestMethod]) )
						{
							$ctrl 	= isset($route[SB_Request::$requestMethod]['controller']) ? $route[SB_Request::$requestMethod]['controller'] : $ctrl;
							$task	= isset($route[SB_Request::$requestMethod]['task']) ? $route[SB_Request::$requestMethod]['task'] : $task;
						}
					}
					else
					{
							
					}
					$ns			= dirname($ctrl);
					$ctrl		= basename($ctrl);
					$theTask	= !empty($ctrl) ? str_replace(['Controller', 'AdminController'], '', $ctrl) . '.' : '';
					$theTask	.= $task;
					$the_route = sprintf("mod=%s&task=%s", $mod, $theTask);
					//##store matches as vars
					$vars = [];
					for($i = 1; $i < count($matches); $i++)
					{
						$vars[] = $matches[$i];
					}
					SB_Globals::SetVar('path_vars', $vars);
				}
				else
				{
					//var_dump($pattern, $_match, $_route);
					$vars 		= [];
					$the_route 	= $_route;
					for($i = 1; $i < count($matches); $i++)
					{
						$the_route = str_replace("$$i", $matches[$i], $the_route);
						$vars[] = $matches[$i];
					}
					SB_Globals::SetVar('path_vars', $vars);
				}
				break;
			}	
		}
		
		//var_dump($the_route);
		if( !$the_route )
			return false;
		SB_Route::SetRoute($the_route, SB_Request::$requestMethod);
	}
	public function LoadModules()
	{
		//##include default hooks
		sb_include('default-hooks.php', 'file');
		//##load modules
		$modules = SB_Module::getEnabledModules();
		$modules = array_merge($modules, $this->defaultModules);
		//print_r($modules);
		//##load the users module before
		require_once MODULES_DIR . SB_DS . 'mod_users' . SB_DS . 'init.php';
		foreach($modules as $module)
		{
			//var_dump($module);
			if( file_exists(MODULES_DIR . SB_DS . $module . SB_DS . 'init.php') )
			{
				require_once MODULES_DIR . SB_DS . $module . SB_DS . 'init.php';
			}
		}
		SB_Module::do_action('modules_loaded');
	}
	public function Start()
	{
		if( !defined('LT_ADMIN') )
		{
			sb_include('template-functions.php', 'file');
		}
		SB_Module::do_action('before_init');
		$this->SetupTemplate();
		//##check and execute cron jobs
		$this->CronJobs();
		if( $this->request->getString('mod') == null && $this->request->getString('tpl_file') == null && !defined('LT_ADMIN') )
		{
			define('LT_FRONTPAGE', 1);
		}
		SB_Module::do_action('init');
	}
	public function SetupTemplate()
	{
		//##check for backend template functions.php file
		$backend_template_dir = sb_get_template_dir('backend');
		if( file_exists($backend_template_dir . SB_DS . 'functions.php') )
			require_once $backend_template_dir . SB_DS . 'functions.php';
		define('BACKEND_TEMPLATE_DIR', $backend_template_dir);
		//##check for frontend template functions.php file
		$template_dir = sb_get_template_dir('frontend');
		$template_url = sb_get_template_url();
		if( file_exists($template_dir . SB_DS . 'functions.php') )
			require_once $template_dir . SB_DS . 'functions.php';
		SB_Module::do_action('template_loaded');
		//##get current environment template and set constants
		//$template_dir = sb_get_template_dir();
	}
	/**
	 * Load default language for application
	 * 
	 * @return  
	 */
	public function LoadLanguage()
	{
		$r_lang		= $this->request->getString('lang');
		if( $r_lang )
		{
			define('LANGUAGE', $r_lang);
			SB_Session::setVar('lang', $r_lang);
		}
		elseif( $r_lang = SB_Session::getVar('lang') )
		{
			define('LANGUAGE', $r_lang);
		}
		else
		{
			define('LANGUAGE', defined('SYS_LANGUAGE') ? SYS_LANGUAGE : 'en_US');
		}
		$lang_code 	= defined('LANGUAGE') ? LANGUAGE : 'en_US';
		$domain 	= 'default';
		$path 		= BASEPATH . SB_DS . 'locale';
		defined('LANGUAGE') or define('LANGUAGE', $lang_code);
        
        if( !function_exists('textdomain') || defined('TRANSLATION_USE_POMO') )
            SB_Language::UsePOMO();
		SB_Language::loadLanguage($lang_code, $domain, $path);
		//setlocale(LC_NUMERIC, 'en_GB.utf-8');
		//setlocale(LC_NUMERIC, 'en_GB');
		//putenv('LC_NUMERIC=en_GB.utf-8');
	}
	/**
	 * Get the module controller instance
	 * 
	 * @return SB_Controller
	 */
	protected function InstanceController()
	{
		
	}
	public function ProcessModule($mod)
	{	
		if( !$mod )
		{
			//##create a dummy controller
			$this->controller = new SB_Controller();
			return false;
		}
		$this->module	= $mod;
		SB_Request::setVar('mod', $mod);
		
		$dbh 			= SB_Factory::getDbh();
		$_task 			= $this->request->getTask();
		$ctrl			= $this->request->getString('ctrl');
		$view 			= $this->request->getString('view', 'default');
		
        if( !$_task && $view )
		{
			$_task = $view;
		}
		if( strstr('.', $view) )
		{
			list(,$view) = explode('.', $view);
		}
		
		if( $_task )
		{
			
			$task               = $_task;
            if( !$this->IncludeModule($mod, $task) )
                $this->IncludeOldModule ($mod, $task);
            if( strstr($_task, '.') )
            {
                list($ctrl, $task) 	= explode('.', $_task);
            }
			
			//##old version controller method backward compatibility
            $old_method = 'task_' . preg_replace('/[^a-zA-Z0-9_]/', '_', $task);
			//##new version controller method
			$new_method = 'Task' . preg_replace('/[^a-zA-Z0-9_]/', '_', ucfirst($task));
			$method		= method_exists($this->controller, $new_method) ? 
							$new_method : 
							(method_exists($this->controller, $old_method) ? $old_method : null);
			
			//##set the module
            $this->controller->mod = $mod;
			SB_Module::do_action('before_process_module', $this->controller, $method);
			if( !$method )
            {
                return false;
            }
            $vars = SB_Globals::GetVar('path_vars');
			call_user_func_array(array($this->controller, $method), $vars ? $vars : []);
			SB_Module::do_action('after_process_module');
		}
	}
    public function IncludeModule($mod, $task)
    {
        //var_dump($task);
        $module_dir		= MODULES_DIR . SB_DS . 'mod_' . $mod;
        //##build module namespace
        $this->moduleNamespace = $namespace = 'SinticBolivia\\SBFramework\\Modules\\' . ucfirst($mod);
        $controller_class   = '';
        if( strstr($task, '.') )
        {
            list($_class_prefix, $_task) = explode('.', $task);
            $namespace .= '\\Controllers\\';
            
            $controller_class = $namespace . ucfirst($_class_prefix) . (defined('LT_ADMIN') ? 'Admin' : '') . 'Controller';
            //die($controller_class);
        }
        else
        {
            $controller_class   = $namespace . (defined('LT_ADMIN') ? '\\Admin' : '\\') . 'Controller';
        }
        
        
        $controller_file    = sb_namespace2path($controller_class); 
        
        if( !is_file($controller_file) /*&& !is_file($controller_path . SB_DS . $controller_file)*/ )
            return false;
        try
        {
            $this->controller   = new $controller_class($this->template->GetHtmlDocument());
            define('MODULE_DIR', $module_dir);
            define('MODULE_URL', MODULES_URL . '/mod_'.$mod);
        }
        catch(Exception $e)
        {
            return false;
        }
        return true;
    }
    public function IncludeOldModule($mod, $task)
    {
        $module_dir		= MODULES_DIR . SB_DS . 'mod_'.$mod;
        $class_prefix 	= defined('LT_ADMIN') ? 'LT_AdminController' : 'LT_Controller';
        $file_prefix	= defined('LT_ADMIN') ? 'admin.' : '';
        if( !is_dir($module_dir) )
        {
            die('The module "'.$mod.'" does not exists.');
        }
        define('MODULE_DIR', $module_dir);
        define('MODULE_URL', MODULES_URL . '/mod_'.$mod);
        $controllers_dir 	= $module_dir . SB_DS . 'controllers';
        $controller_file 	= $module_dir . SB_DS . $file_prefix . 'controller.php';
        $controller_class	= $class_prefix . ucfirst(strtolower($mod));
        if( strstr($task, '.') )
        {
            list($ctrl, $task) 	= explode('.', $task);
            $controller_file 	= $controllers_dir . SB_DS . $file_prefix . 'controller.' . $ctrl . '.php';
            $controller_class	.= ucfirst($ctrl);   
        }
        if( !is_file($controller_file) )
        {
            return false;
        }
        
        require_once $controller_file;
        $this->controller = new $controller_class($this->template->GetHtmlDocument());
        
        return true;
    }
	/**
	 * Return the current controller
	 * @return SB_Controller
	 */
	public function GetController()
	{
		return $this->controller;
	}
	public function PrepareTemplate($tpl_file = null)
	{
		$tpl_file 		= $tpl_file ? $tpl_file : SB_Request::getString('tpl_file', null);
		$template_dir 	= sb_get_template_dir();
		$template_url	= sb_get_template_url();
		//var_dump($template_dir, $template_url);
		$this->template->SetPath($template_dir);
		$this->template->SetUrl($template_url);
		if( $tpl_file )
		{
			$this->template->SetFile($tpl_file);
		}
		
	}
	public function ProcessTemplate()
	{
		global $view_vars;
	
		$view 			= $this->request->getString('view', 'default');
		
		SB_Module::do_action('before_process_template');
		$this->templateHtml	= $this->template->BuildHtml();
	}
	public function ShowTemplate()
	{
		print $this->templateHtml;
		sb_end();
	}
	/**
	 * 
	 * @return SB_Template
	 */
	public function GetTemplate()
	{
		return $this->template;
	}
	/**
	 * Execute all cron jobs
	 */
	protected function CronJobs()
	{
		return true;
		if( defined('LT_CRON') )
			return false;
		
		if( !function_exists('fsockopen') )
		{
			$this->Log('Enable to created sockets');
			return false;
		}
		$errno = '';
		$errstr = '';
		set_time_limit(0);
		$url = SB_Route::_('cron.php', 'frontend');
		$url = str_replace(array('http://','https://'), '', $url);
		$url = str_replace($_SERVER['HTTP_HOST'], '', $url);
		$fp = fsockopen($_SERVER['HTTP_HOST'], $port = 80, $errno, $errstr, $conn_timeout = 30);
		if (!$fp)
		{
			echo "$errstr ($errno)<br />\n";
			return false;
		}
		$out = "GET $url HTTP/1.1\r\n";
		$out .= "Host: {$_SERVER['HTTP_HOST']}\r\n";
		$out .= "Connection: Close\r\n\r\n";
		stream_set_blocking($fp, false);
		stream_set_timeout($fp, $rw_timeout = 86400);
		fwrite($fp, $out);
		//$this->Log($out);
		return $fp;
	} 
	/**
	 * Get an instance of application
	 * 
	 * @param string $app
	 * @throws Exception
	 * @return unknown|\SinticBolivia\SBFramework\Classes\SB_Application
	 */
	public static function GetApplication($app = null)
	{
		if( !$app )
		{
			$the_app = new SB_Application();
			return $the_app;
		}
	
		$the_app_file = APPLICATIONS_DIR . SB_DS . 'app.' . $app . '.php';
		if( !file_exists($the_app_file) )
			throw new Exception('The application ' . ucfirst($app) . ' does not exists.');
		require_once $the_app_file;
		$the_app_class = 'SB_Application' . ucfirst(preg_replace('/[^a-zA-Z]/', '', $app));
		if( !class_exists($the_app_class) )
			throw new Exception('The application class "'.$the_app_class.'" does not exists');
		$the_app = new $the_app_class();
		return $the_app;
	}
	public function GetLanguages()
	{
		return SB_Module::do_action('lt_languages', array(
				'es_ES'	=> __('Spanish', 'lt'),
				'en_US'	=> __('English', 'lt'),
		));
	}
	/**
	 * Log a thing into application log file
	 * @param mixed $str 
	 * @return  
	 */
	public function Log($str)
	{
		$fh = is_file(LOG_FILE) ? fopen(LOG_FILE, 'a+') : fopen(LOG_FILE, 'w+');
		fwrite($fh, sprintf("[%s]:\n%s\n", date('Y-m-d H:i:s'), print_r($str, 1)));
		fclose($fh);
	}
}
