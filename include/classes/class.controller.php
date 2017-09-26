<?php
class SB_Controller extends SB_Object
{
	/**
	 * @var SB_HtmlDoc
	 */
	protected	$document;
	protected	$mod;
	protected	$view		= 'default';
	protected	$viewFile 	= 'default.php';
	/**
	 * @var SB_Database
	 */
	protected	$dbh;
	protected	$viewVars = array();
	
	public function __construct($doc = null)
	{
		if( $doc )
			$this->document = $doc;
		else
			$this->document = new SB_HtmlDoc();
		$this->dbh = SB_Factory::getDbh(); 
		//##try to get the view from request
		$view 	= SB_Request::getString('view', 'default');
		$this->SetView($view);
	}
	public function GetDocument()
	{
		return $this->document;
	}
	public function ShowView($print = true)
	{
		global $view_vars;
		//print_r($view_vars);
		$view 		= preg_replace('/[^a-zA-Z0-9\.\-\/]/', '_', $this->view);
		//$view_ns 	= str_replace('/', '.', $view);
		//var_dump($view_ns);
		//$view_vars 	= isset($view_vars[$view_ns]) ? $view_vars[$view_ns] : array();
		//extract($view_vars);
		
		if( isset($this->viewVars['_html_content']) 
			&& !empty($this->viewVars['_html_content']) 
			&& !is_object($this->viewVars['_html_content']) )
		{
			print $this->viewVars['_html_content'];
			return true;
		}
		if( !$this->mod )
		{
			printf("<div class=\"no-module\">%s</div>", SB_Text::_('There is no module to process'));
			return false;
		}
		//##get module views dir
		$views_dir 			= MODULES_DIR . SB_DS . 'mod_' . $this->mod . SB_DS . 'views' . SB_DS . ( defined('LT_ADMIN') ? 'admin' : '');
		//##get template module views dir
		$template_views_dir = sb_get_template_dir() . SB_DS . 'modules' . SB_DS . 'mod_' . $this->mod . SB_DS . 'views';
		if( lt_is_admin() )
			$template_views_dir .= SB_DS . 'admin';
		//##get module view file
		$view_file 				= $views_dir . SB_DS . $view . '.php';
		
		//##get template module view file
		$template_view_file 	= $template_views_dir . SB_DS . $view . '.php';
		$template_view_file		= SB_Module::do_action('template_view_file', $template_view_file);
		
		//##check if template module view file exists
		if( file_exists($template_view_file) )
		{
			$this->document->AddBodyClass($view_file);
			SB_Module::do_action('before_show_view', $template_view_file, $this->viewVars);
			extract($this->viewVars, EXTR_OVERWRITE);
			require_once SB_Module::do_action('view_template', $template_view_file, $this->mod);
			SB_Module::do_action('after_show_view');
		}
		else
		{
			//var_dump($views_dir, $view_file);
			if( !file_exists($view_file) )
			{
				printf("<div class=\"view-not-found\">%s</div>", sprintf(SB_Text::_('View "%s" not found'), $view));
				return false;
			}
			$this->document->AddBodyClass($view_file);
			SB_Module::do_action('before_show_view', $view_file, $this->viewVars);
			extract($this->viewVars, EXTR_OVERWRITE);
			require_once SB_Module::do_action('view_template', $view_file, $this->mod);
			SB_Module::do_action('after_show_view');
		}
	}
	public function SetView($view)
	{
		SB_Request::setVar('view', str_replace('/', '.', $view));
		$this->view 	= $view;
		$this->viewFile = $view . '.php';
	}
	public function SetVar($var, $value)
	{
		$this->viewVars[$var] = $value;
	}
	/**
	 * Set view vars
	 * 
	 * @param array $vars 
	 * @return void
	 */
	public function SetVars($vars)
	{
		foreach((array)$vars as $var => $value)
		{
			$this->SetVar($var, $value);
		}
	}
	public function __set($var, $value)
	{
		//##check if we want to assign a view var
		if( $var{0} == '_' )
		{
			$this->viewVars[ltrim($var, '_')] = $value;
		}
		parent::__set($var, $value);
	}
	public function __get($var)
	{
		if( isset($this->viewVars[$var]) )
			return $this->viewVars[$var];
		return parent::__get($var);
	}
	public function task_ajax()
	{
		$action = SB_Request::getString('action');
		if( !$action )
			return false;
		$method = 'ajax_'.$action;
		if( !method_exists($this, $method) )
			return false;
		call_user_func(array($this, $method));
	}
	public function __call($method, $args)
	{
		
	}
}