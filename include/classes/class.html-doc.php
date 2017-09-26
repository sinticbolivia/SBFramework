<?php
/**
 * 
 * @author marcelo
 *
 * @property string $templateFile
 * @property string $bodyId
 */
class SB_HtmlDoc extends SB_Object
{
	protected $_head;
	protected $_title;
	protected $_css = array();
	protected $_js = array();
	protected $_registered_js = array();
	protected $_globals_js_var = array();
	protected $_body;
	protected $_template;
	protected $_template_data;
	protected $_tpl_path;
	protected $_raw_template_html = '';
	protected 	$templateFile = 'index.php';
	protected	$bodyId;
	protected	$bodyClasses = array();
	
	public function __construct()
	{
		/*
		$this->registerJs('jquery', FRAMEWORK_URL . '/js/jquery-1.10.2.min.js');
		$this->registerJs('bootstrap', FRAMEWORK_URL . '/css/bootstrap/js/bootstrap.min.js', array('jquery'));
		$this->registerJs('jeasyui-js', FRAMEWORK_URL . '/js/jquery-easyui-1.3.4/jquery.easyui.min.js', array('jquery'));
		$this->registerJs('fineuploader', FRAMEWORK_URL . '/js/fineuploader-3.5.0/jquery.fineuploader-3.5.0.min.js', array('jquery'));
		*/
	}
	public function setTplPath($tpl_data)
	{
		$this->_template_data = $tpl_data;
		$this->_tpl_path = $tpl_data['path'];
	}
	public function registerJs($id, $src, $deps = array())
	{
		$this->_registered_js[$id] = array('id' => $id, 'src' => $src, 'deps' => $deps);
	}
	public function addJs($id, $src = null, $deps = array(), $priority = 10)
	{
		if( !$src && isset($this->_registered_js[$id]) )
		{
			$this->_js[$id] = array('id' => $id, 'src' => $this->_registered_js[$id]['src'], 'deps' => $this->_registered_js[$id]['deps']);
		}
		elseif( $src )
		{
			$this->_js[$id] = array('id' => $id, 'src' => $src, 'deps' => $deps);
		}
	}
	public function declareGlobalJsVar($var, $value)
	{
		if(is_string($value))
			$value = "'$value'";
		$this->_globals_js_var[] = array('var' => $var, 'value' => $value);
	}
	public function addCss($id, $href)
	{
		$this->_css[$id] = $href;
	}
	public function buildJsTags()
	{
		$globals_js = '';
		foreach($this->_globals_js_var as $d)
		{
			$globals_js .= sprintf("var %s = %s;", $d['var'], $d['value']);
		}
		
		$globals = sprintf("<script id=\"mono-business-globals\">var mb_base_url = '%s';%s</script>\n", BASE_URL, $globals_js);
		$deps = array();
		$tags = array();
		$exclude = array();
		
		foreach($this->_js as $id => $js)
		{
			foreach($js['deps'] as $dep)
			{
				if( isset($this->_registered_js[$dep]) )
				{
					if( !isset($deps[$dep]) )
					{
						$deps[$dep] = sprintf("<script id=\"%s\" src=\"%s\"></script>", $this->_registered_js[$dep]['id'], $this->_registered_js[$dep]['src']);
						unset($this->_js[$id]);
					}
				}
				elseif( isset($this->_js[$dep]) )
				{
					if( !isset($deps[$dep]) )
					{
						$deps[$dep] = sprintf("<script id=\"%s\" src=\"%s\"></script>", $this->_js[$dep]['id'], $this->_js[$dep]['src']);
						unset($this->_js[$id]);
					}
				}
			}
			if( !isset($tags[$id]) )
				$tags[$id] = sprintf("<script id=\"%s\" src=\"%s\"></script>", $js['id'], $js['src']);
		}
		//return implode("\n", $deps) . implode("\n", $tags);
		return $globals . implode("\n", array_merge($deps, $tags));
	}
	/**
	 * Get all enqueued css sources
	 * 
	 * @return string
	 */
	public function buildCssTags()
	{
		$tags = '';
		foreach($this->_css as $id => $href)
		{
			$tags .= sprintf("<link rel=\"stylesheet\" href=\"%s\" />\n", $href);
		}
		
		return $tags;
	}
	/**
	 * Set HTML document title
	 * 
	 * @param string $title 
	 * @return SB_HtmlDoc
	 */
	public function SetTitle($title)
	{
		$this->_title = $title;
		return $this;
	}
	public function GetTitle()
	{
		return lt_site_title() . ' | ' . $this->_title;
	}
	public function getHead()
	{
		$head = "<title>$this->_title</title>\n";
		$head .= $this->buildCssTags();
		$head .= $this->buildJsTags();
		
		return $head;
	}
	public function SetBodyId($id)
	{
		$this->bodyId = $id;
	}
	public function AddBodyClass($class)
	{
		$this->bodyClasses[] = $class;
	}
	public function setBodyContent($body)
	{
		$this->_body = $body;
		
	}
	public function processTemplate()
	{
		$this->_template = new SB_Template($this->_template_data);
		$this->_raw_template_html = $this->_template->parseTemplate();
	}
	public function buildHtml()
	{
		$body = $this->_raw_template_html;
		//replace sections
		$body = str_replace('[[head]]', $this->getHead(), $body);
		$body = str_replace('[[content]]', $this->_body, $body);
		
		return $body;
	}
	public function GetTemplate()
	{
		$template_dir = sb_get_template_dir();
		$req_template = SB_Request::getString('tpl_file', 'index.php');
		if( !strstr($req_template, '.php') )
			$req_template .= '.php';
	
		$template = $req_template;
		if( $req_template != 'index.php' )
		{
			$this->templateFile = $req_template;
		}
		if( !file_exists($template_dir . SB_DS . $this->templateFile) )
		{
			$this->templateFile = 'index.php';
		}
		
		return $this->templateFile;
	}
}