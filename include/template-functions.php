<?php
function lt_title()
{
	global $app;
	
	//var_dump($app);
	$ctrl = $app->GetController();
	if( $ctrl )
		print $ctrl->GetDocument()->GetTitle();
}
function lt_site_title()
{
	return defined('SITE_TITLE') ? SITE_TITLE : '';
}
function lt_get_template($tpl_file)
{
	$theme_dir = sb_get_template_dir();
	if( !file_exists($theme_dir . SB_DS . $tpl_file) )
		return null;
	return $theme_dir . SB_DS . $tpl_file;
}
/**
 * Include a template file.
 * The file is relate to current template
 * 
 * @param string $tpl_file The template file location
 * @param array $args The args passed to template file
 */
function lt_include_template($tpl_file, $args = null)
{
	$tpl_file = $tpl_file . '.php';
	if( $tpl = lt_get_template($tpl_file) )
	{
		include $tpl;
	}
}
function lt_get_header($tpl = null, $args = null)
{
	global $template_html, $view_vars, $app;
	
	$view 			= SB_Request::getString('view', 'default');
	isset($view_vars[$view]) && (is_array($view_vars[$view]) || is_object($view_vars[$view])) ? extract($view_vars[$view]) : '';
	
	$theme_dir = sb_get_template_dir();
	$tpl_file = 'header' . ($tpl ? '-'.$tpl : '') . '.php';
	if( !file_exists($theme_dir . SB_DS . $tpl_file) )
		return '';
	
	include $theme_dir . SB_DS . $tpl_file;
}
function lt_get_footer($tpl = null, $args = null)
{
	global $template_html, $view_vars, $app;
	
	$view 			= SB_Request::getString('view', 'default');
	isset($view_vars[$view]) && (is_array($view_vars[$view]) || is_object($view_vars[$view])) ? @extract($view_vars[$view]) : '';
	
	$theme_dir = sb_get_template_dir();
	$tpl_file = 'footer' . ($tpl ? '-'.$tpl : '') . '.php';

	file_exists($theme_dir . SB_DS . $tpl_file) ? include $theme_dir . SB_DS . $tpl_file : '';
}
function lt_get_sidebar($tpl = null, $args = null)
{
	global $template_html, $view_vars, $app;
	
	$view 			= SB_Request::getString('view', 'default');
	isset($view_vars[$view]) && (is_array($view_vars[$view]) || is_object($view_vars[$view])) ? @extract($view_vars[$view]) : '';
	
	$theme_dir = sb_get_template_dir();
	$tpl_file = 'sidebar' . ($tpl ? '-'.$tpl : '') . '.php';

	include $theme_dir . SB_DS . $tpl_file;
}
function lt_pagination($base_link, $total_pages, $current_page)
{
	if( $total_pages <= 1 )
		return false;
	//var_dump($base_link);
	list($base_link, $query_string) = explode('?', $base_link);
	$vars = array();
	if( !empty($query_string) )
	{
		parse_str($query_string, $vars);
		$vars['page'] = $current_page - 1;
	}
	$pages_to_show = 5;
	$start      = ( ( $current_page - $pages_to_show ) > 0 ) ? $current_page - $pages_to_show : 1;
	$end        = ( ( $current_page + $pages_to_show ) < $total_pages ) ? $current_page + $pages_to_show : $total_pages;
	?>
	<nav id="pagination">
		<ul class="pagination">
			<?php if($current_page > 1): ?>
			<li>
		    	<a href="<?php printf("%s?%s", $base_link, http_build_query($vars)); ?>" aria-label="Previous">
		    		<span aria-hidden="true">&laquo;</span>
		    	</a>
		    </li>
			<?php endif; ?>
			<?php $_vars = $vars;?>
			<?php for($i = $start; $i <= $end; $i++): ?>
				<?php
				 
				$_vars['page'] = $i; 
				?>
			<li <?php print ($current_page == $i) ? 'class="active"' : ''; ?>>
				<a href="<?php printf("%s?%s", $base_link, http_build_query($_vars)); ?>">
					<?php print $i; ?>
				</a>
			</li>
			<?php endfor;?>
			<?php if( $current_page < $total_pages ): $_vars = $vars; $_vars['page'] = $current_page + 1; ?>
			<li>
		    	<a href="<?php printf("%s?%s", $base_link, http_build_query($_vars)); ?>" aria-label="Next">
		    		<span aria-hidden="true">&raquo;</span>
		    	</a>
		    </li>
			<?php endif; ?>
		</ul>
	</nav>
	<?php 
}
function lt_parse_shortcodes($string)
{
	return SB_Shortcode::ParseShortcodes($string);
}
function lt_scripts($footer = false)
{
	$scripts =& SB_Globals::GetVar($footer ? 'footer_scripts' : 'scripts');
	if( is_array($scripts) )
	{
		foreach($scripts as $js)
		{
			printf("<script id=\"%s\" type=\"text/javascript\" src=\"%s\"></script>\n", $js['id'], $js['src']);
		}
	}
	
	SB_Module::do_action($footer ? 'footer_scripts' : 'scripts');
}
function lt_styles()
{
	$styles =& SB_Globals::GetVar('styles');
	if( is_array($styles) )
	{
		foreach($styles as $style)
		{
			printf("<link id=\"%s\" rel=\"%s\" href=\"%s\" />\n", $style['id'], $style['rel'], $style['href']);
		}
	}
	
	SB_Module::do_action('styles');
}
function lt_favicon()
{
	$icon = TEMPLATE_URL.'/images/favicon.png';
	print '<link rel="icon" type="image/png" href="'. SB_Module::do_action('favicon', $icon).'">';
}
function lt_head()
{
	lt_favicon();
	lt_styles();
	lt_scripts();
	SB_Module::do_action('lt_header');
}
function lt_header()
{
	lt_head();
}
function lt_footer()
{
	lt_scripts(true);
	SB_Module::do_action('lt_footer');
}
function lt_is_frontpage()
{
	return defined('LT_FRONTPAGE') && !SB_Request::getString('tpl_file');
}
function lt_body_id(){global $app; print 'id="'.$app->htmlDocument->bodyId. '"';}
function lt_body_class($classes = null)
{
	global $app;
	$body_classes = array_map('trim', $app->htmlDocument->bodyClasses);
	
	if( is_string($classes) )
	{
		$body_classes = array_merge($body_classes, array_map('trim', explode(' ', $classes)));
	}
	elseif( is_array($classes) )
	{
		$body_classes = array_merge($body_classes, $classes);
	}
	$body_classes = SB_Module::do_action('lt_body_class', $body_classes);
	$body_classes_str = implode(' ', $body_classes);
	
	print 'class="' . $body_classes_str . '"';
}
function lt_add_tinymce($args = null)
{
	global $tinymce_args;
	list($lang,) = explode('_', LANGUAGE);
	$def_args = array(
			'convert_urls'			=> true,
			'relative_urls'			=> false,
			'remove_script_host' 	=> false,
			'selector' 		=> 'textarea:not(.mceNoEditor)',
			'editor_deselector' => "mceNoEditor",
			'language'		=> $lang,//'es',
			'height'		=> 300,
			'schema'		=> 'html5',
			'theme'			=> 'modern',
			'plugins'		=> array(
							"advlist autolink lists link image charmap print preview hr anchor pagebreak",
		              		"searchreplace wordcount visualblocks visualchars code fullscreen",
		              		"insertdatetime media nonbreaking save table contextmenu directionality",
		              		//"emoticons template paste textcolor colorpicker textpattern youtube dropbox google_tools picasa flickr"
		              		"emoticons template paste textcolor colorpicker textpattern"
			),
			//'toolbar'		=> 'fontsizeselect',
			'toolbar1'		=> 'insertfile undo redo | styleselect | fontsizeselect | bold italic  | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | youtube',
			'toolbar2'		=> 'print preview media | forecolor backcolor emoticons | dropbox | google_tools | picasa | flickr',
			//'toolbar'		=> "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
			'image_advtab'	=> true,
			'fontsize_formats' => "8pt 10pt 12pt 14pt 18pt 24pt 36pt"
	);
	$tinymce_args = SB_Module::do_action('lt_tinymce_args', array_merge($def_args, (array)$args));
	function __lt_write_tinymce()
	{
		global $tinymce_args;
		?>
		<script src="<?php print BASEURL; ?>/js/tinymce/tinymce.min.js"></script>
		<script id="tinymce-init">
		tinymce.baseURL = lt.baseurl + "/js/tinymce";
		tinymce.suffix = '.min';
		tinymce.init(<?php print json_encode($tinymce_args); ?>);
		</script>
		<?php 
	}
	SB_Module::add_action('scripts', '__lt_write_tinymce');
}
function lt_add_datepicker()
{
	sb_add_style('datepicker-css', BASEURL . '/js/bootstrap-datepicker-1.4.0/css/bootstrap-datepicker.min.css');
	sb_add_script(BASEURL . '/js/bootstrap-datepicker-1.4.0/js/bootstrap-datepicker.min.js', 'datepicker-js');
	list($lang_code,) = explode('_', LANGUAGE);
	sb_add_script(BASEURL . '/js/bootstrap-datepicker-1.4.0/locales/bootstrap-datepicker.'. $lang_code.'.min.js', 'datepicker-js');
}
function lt_get_view($mod, $view)
{
	if( !SB_Module::moduleExists($mod) )
	{
		return null;
	}
	$view_dir 	= MODULES_DIR . SB_DS . 'mod_' . $mod . SB_DS . 'views';
	//##find view into template directory
	$tpl_dir 	= sb_get_template_dir();
	$tpl_path 	= $tpl_dir . SB_DS . 'modules' . SB_DS . 'mod_' . $mod . SB_DS . 'views';
	if( lt_is_admin() )
	{
		$view_dir	.= SB_DS . 'admin';
		$tpl_path 	.= SB_DS . 'admin';
	}
	
	$view_file = null;
	if( is_file($tpl_path . SB_DS . $view) )
	{
		$view_file = $tpl_path . SB_DS . $view;
	}
	elseif( is_file($view_dir . SB_DS . $view) )
	{
		$view_file = $view_dir . SB_DS . $view;
	}
	else
	{
		$view_file = null;
	}
	
	return $view_file;
}
function lt_include_view($mod, $view, $data = array())
{
	$view_file = lt_get_view($mod, $view);
	if( !$view_file )
		return null;
	extract($data);
	include $view_file;
}
/**
 * Include a module partial template file
 * 
 * @param string $mod 
 * @param string $partial partial filename
 * @param array $data Arguments for partial file
 * @return  
 */
function lt_include_partial($mod, $partial, $data = array())
{
	if( !SB_Module::moduleExists($mod) )
	{
		return null;
	}
	$partial_dir 	= MODULES_DIR . SB_DS . 'mod_' . $mod . SB_DS . 'partials';
	//var_dump($partial_dir);
	$partial_file 	= null;
	//##find partial into template directory
	$tpl_dir = sb_get_template_dir();
	$tpl_path = $tpl_dir . SB_DS . 'modules' . SB_DS . 'mod_' . $mod . SB_DS . 'partials';
	//var_dump($tpl_path . SB_DS . $partial);
	//var_dump($partial_dir . SB_DS . $partial);
	if( file_exists($tpl_path . SB_DS . $partial) )
	{
		$partial_file = $tpl_path . SB_DS . $partial;
	}
	elseif( file_exists($partial_dir . SB_DS . $partial) )
	{
		$partial_file = $partial_dir . SB_DS . $partial;
	}
	else
		$partial_file = null;
	
	$partial_file = SB_Module::do_action('partial_file', $partial_file, $mod, $partial, $data);
	if( !$partial_file )
		return null;
	//var_dump($partial_file);
	include $partial_file;
}
function lt_template_fallback()
{
	?>
	<!doctype html>
	<html>
	<head>
		<meta charset="utf-8" />
		<title><?php lt_title(); ?></title>
		<link rel="stylesheet" href="<?php print BASEURL; ?>/js/bootstrap-3.3.5/css/bootstrap.min.css" />
		<script src="<?php print BASEURL; ?>/js/jquery.min.js"></script>
		<script src="<?php print BASEURL; ?>/js/bootstrap-3.3.5/js/bootstrap.min.js"></script>
		<?php lt_head(); ?>
	</head>
	<body <?php lt_body_id(); ?> <?php lt_body_class('tpl-fallback'); ?>>
	<div id="container">
		<div id="content">
			<div class="content-wrap">
				<?php SB_MessagesStack::ShowMessages(); ?>
				<?php sb_show_module(); ?>
			</div>
		</div>
		<footer id="footer">
			<div class="wrap">
				<p id="copyright">
					Powered by Little CMS &copy;<a href="http://sinticbolivia.net" target="_blank">Sintic Bolivia</a> <?php print date('Y'); ?>
				</p>
			</div>
		</footer><!-- end id="footer" -->
	</div>
	<?php lt_footer(); ?>
	</body>
	</html>
	<?php 
}
function sb_get_templates($type = 'frontend')
{
	$templates = array();
	$path = $type == 'frontend' ? TEMPLATES_DIR : ADM_TEMPLATES_DIR;
	if( !is_dir($path) )
		return $templates;
	
	$dh = opendir($path);
	while( ($file = readdir($dh)) !== false )
	{
		if( $file{0} == '.' ) continue;
		$info_file = $path . SB_DS . $file . SB_DS . 'style.css';
		//var_dump($info_file);
		if( !file_exists($info_file) ) continue;
		$templates[$file] = sb_get_template_info($info_file);
	}
	closedir($dh);

	return $templates;
}
function sb_get_template_info($theme_file)
{
	if( !file_exists($theme_file) )
		return false;
	$theme_info = array('Template name' 		=> '',
			'Template author' 		=> '',
			'Template Url' 			=> '',
			'Template Version' 		=> '',
			'Template Description' 	=> '',
			'Fields'				=> ''
	);
	$fh = fopen($theme_file, 'r');
	$info = fread($fh, 8192);
	fclose($fh);
	foreach($theme_info as $tag => $val)
	{
		if( preg_match('/'.$tag.':(.*)/i', $info, $matches) )
		{
			$theme_info[$tag] = trim($matches[1]);
		}

	}
	$theme_info['template_dir'] = dirname($theme_file);
	$theme_info['template_file'] = $theme_file;
	return array_map('trim', $theme_info);
}
function sb_add_js_global_var($module = null, $varname, $value)
{
	$globals =& SB_Globals::GetVar('js_globals');
	if( !$globals )
	{
		SB_Globals::SetVar('js_globals', array());
		$globals =& SB_Globals::GetVar('js_globals');
		$globals['modules'] = array();
	}
	if( $module )
	{
		if( !isset($globals['modules'][$module]) )
			$globals['modules'][$module] = array();
		$globals['modules'][$module][$varname] = $value;
		return true;
	}
	$globals[$varname] = $value;
	return true;
}
/**
 * Enqueue a javascript file
 * 
 * @param mixed $id 
 * @param mixed $src 
 * @param mixed $order 
 * @param mixed $footer 
 * @return  
 */
function lt_add_js($id, $src = null, $order = 0, $footer = false)
{
	$registerd_js = array(
		'jquery'	=> array('src' => BASEURL . '/js/jquery.min.js'),
		'bootstrap'	=> array(
			'src'	=> BASEURL . '/js/bootstrap-3.3.5/js/bootstrap.min.js',
			'href'	=> BASEURL . '/js/bootstrap-3.3.5/css/bootstrap.min.css'
		)
	);
	if( isset($registerd_js[$id]) )
	{
		if( isset($registerd_js[$id]['href']) )
		{
			sb_add_style($id, $registerd_js[$id]['href']);
		}
		sb_add_script($registerd_js[$id]['src'], $id, $order, $footer);
	}
	elseif( $id && $src )
	{
		sb_add_script($src, $id, $order, $footer);
	}
	
}