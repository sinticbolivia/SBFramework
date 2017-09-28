<?php
require_once 'fallbacks.php';
function sb_get_parameter($key, $default = '')
{
	$dbh = SB_Factory::getDbh();
	$rows = $dbh->Query("SELECT {$dbh->lcw}id{$dbh->rcw}, {$dbh->lcw}key{$dbh->rcw}, {$dbh->lcw}value{$dbh->rcw}, {$dbh->lcw}creation_date{$dbh->rcw} ".
							"FROM parameters WHERE {$dbh->lcw}key{$dbh->rcw} = '$key'");
	
	if( $rows <= 0 )
		return $default;
	$row = $dbh->FetchRow();
	$value = json_decode($row->value);
	if( is_array($value) || is_object($value) )
		return $value;
	return $row->value;
}
function sb_update_parameter($key, $value)
{
	if( is_array($value) || is_object($value) )
	{
		$value = json_encode($value);
	}
	$dbh = SB_Factory::getDbh();
	$param = sb_get_parameter($key, null);
	if( $param === null )
	{
		$dbh->Insert('parameters', array('key' => $key, 'value' => $value, 'creation_date' => date('Y-m-d H:i:s')));
	}
	else
	{
		$dbh->Update('parameters', array('value' => $value), array('key' => $key));
	}
}
function sb_initialize_settings()
{
	ini_set('post_max_size', '150M');
	ini_set('upload_max_filesize', '150M');
	$settings = sb_get_parameter('settings', array());
	foreach($settings as $key => $value)
	{
		if( is_array($value) || is_object($value) ) continue;
		$const = strtoupper($key);
		if( $const == 'LANGUAGE' ) 
		{
			$const = 'SYS_LANGUAGE';
		}
		define($const, $value);
	}
	$time_zone = defined('TIME_ZONE') ? TIME_ZONE : 'America/La_Paz';
	if( empty($time_zone) )
		$time_zone = 'America/La_Paz';
	
	date_default_timezone_set($time_zone);
}
function sb_process_module($mod = null)
{
	global $app;
	$app->ProcessModule($mod);
}
function sb_show_module($content = null)
{
	global $view_vars, $app; 
	
	//if();
	$mod 	= SB_Request::getString('mod', null);
	$ctrl 		= $app->GetController();
	if( is_object($ctrl) )
	{
		$ctrl->ShowView();
	}
	else
	{
		$ctrl 		= new SB_Controller();
		$ctrl->mod 	= $mod;
		$ctrl->ShowView();
	}
}
/**
 * Get template directory
 * 
 * @param string frontend|backend|null
 * If null is passed it will returns automatically the template directory of current environment 
 * @return NULL|string
 */
function sb_get_template_dir($type = null)
{
	if( $type === null && defined('TEMPLATE_DIR') )
	{
		return TEMPLATE_DIR;
	}
	elseif( !defined('TEMPLATE_DIR') ) 
	{
		$template 		= defined('LT_ADMIN') ? sb_get_parameter('template_admin', 'default') : sb_get_parameter('template_frontend', 'default');
		$templates_dir 	= defined('LT_ADMIN') ? ADM_TEMPLATES_DIR : TEMPLATES_DIR;
		if( !$template )
			return null;
		define('TEMPLATE_DIR', $templates_dir . SB_DS . $template);
		return $templates_dir . SB_DS . $template;
	}
	else
	{
		$template 		= ($type == 'backend') ? sb_get_parameter('template_admin', 'default') : sb_get_parameter('template_frontend', 'default');
		$templates_dir 	= ($type == 'backend') ? ADM_TEMPLATES_DIR : TEMPLATES_DIR;
		return $templates_dir . SB_DS . $template;
	}
}
function sb_get_template_url()
{
	static $template_url = null;
	if( $template_url != null )
		return $template_url;
	
	$template		= defined('LT_ADMIN') ? sb_get_parameter('template_admin', 'default') : sb_get_parameter('template_frontend', 'default');
	$template_url	= defined('LT_ADMIN') ? ADMIN_URL . '/templates/' . $template :  TEMPLATES_URL . '/' . $template;
	defined('TEMPLATE_URL') or define('TEMPLATE_URL', $template_url);
	
	return $template_url;
}
function sb_process_template($tpl_file = 'index.php')
{
	$app = SB_Factory::getApplication();
	$app->ProcessTemplate();
}
function sb_show_template()
{
	SB_Factory::getApplication()->ShowTemplate();
}
function sb_set_view($view)
{
	SB_Factory::getApplication()->GetController()->SetView($view);
}
function sb_end()
{
	$dbh = SB_Factory::getDbh();
	$dbh->Close();
	SB_Module::do_action('end');
}
function sb_is_user_logged_in($cookie_name = null)
{
	$session_var = '';
	//$cookie_name = '';
	$timeout_var = '';
	if( $cookie_name === null )
	{
		if( defined('LT_ADMIN') )
		{
			$session_var = 'admin_user';
			$cookie_name = 'lt_session_admin';
			$timeout_var = 'admin_timeout';
		}
		else
		{
			$session_var = 'user';
			$cookie_name = 'lt_session';
			$timeout_var = 'timeout';
		}
	}
	else 
	{
		if( defined('LT_ADMIN') )
		{
			$session_var = 'admin_user';
			$timeout_var = 'admin_timeout';
		}
		else
		{
			$session_var = 'user';
			$timeout_var = 'timeout';
		}
	}
	
	$user 		=& SB_Session::getVar($session_var);
	$session 	=& SB_Session::getVar($cookie_name);
	$timeout	=& SB_Session::getVar($timeout_var);
	
	if( !$user || !$session || !$timeout )
	{
		return false;
	}
	//##check session expiration
	$time_diff = time() - $timeout;
	if( $time_diff > SESSION_EXPIRE )
	{
		SB_MessagesStack::AddMessage(SB_Text::_('La sesion ha expirado', 'info'), 'info');
		$ctrl = SB_Module::GetControllerInstance('users');
		$ctrl->task_logout();
		return false;
	}
	//##renew the timeout
	$timeout = time();
	//require_once MODULES_DIR . SB_DS . 'mod_users' . SB_DS . 'functions.php';
	sb_update_user_meta(sb_get_current_user()->user_id, '_timeout', $timeout);
	return true;
}
function sb_set_view_var($name, $value, $view = null)
{
	//global $view_vars; //##declare global variable
	if( $ctrl = SB_Factory::getApplication()->GetController() )
		$ctrl->SetVar($name, $value);
	/*	
	if( $view_vars == null || !is_array($view_vars) )
		$view_vars = array();
	$view = $view ? $view : SB_Request::getString('view', 'default');
	if( !isset($view_vars[$view]) )
	{
		$view_vars[$view] = array();
	}
	$view_vars[$view][$name] = $value;
	*/
}
function sb_include_module_helper($module)
{
	$module_path = MODULES_DIR . SB_DS . 'mod_' . $module;
	$helper_file = 'helper.' . $module . '.php';
	if( !file_exists($module_path . SB_DS . $helper_file) )
		return false;
	require_once $module_path . SB_DS . $helper_file;
	return true;
}
function sb_download_image($url, $use_curl = true)
{
	$res = null;
	if( $use_curl )
	{
		$agent = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13';
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0); 
		curl_setopt($ch, CURLOPT_TIMEOUT, 1000);
		curl_setopt($ch, CURLOPT_USERAGENT, $agent);
		$res = curl_exec($ch);
		if( curl_errno($ch) )
		{
			trigger_error(curl_error($ch));
		}
		curl_close($ch);
	}
	else
	{
		$res = file_get_contents($url);
	}
	return $res;
}
function sb_file_get_contents($url)
{
	return sb_download_image($url, true);
}
function sb_get_content_images($content)
{
	/*
	preg_match_all('/<img.*src="([^"].*.[jpg|png|gif])["]{1}[\s+|.*].*>/i', $content, $matches);
	print_r($matches);die();
	if( count($matches[1]) )
	{
		return $matches[1];
	}
	*/
	$dom = new DOMDocument();
	$dom->loadHTML($content);
	$items = $dom->getElementsByTagName('img');
	$images = array();
	foreach($items as $item)
	{
		$images[] = $item->getAttribute('src');
	}
	return $images;
}
function sb_get_youtube_img($url)
{
	//$r = urldominian($url);
	//var_dump($r);
	$ext = explode(".", $url);
	$HTMLVER = '';
	//if($r == 'www.youtube.com')
	if( stristr($url, 'youtube.com') )
	{
		preg_match("#v=([a-zA-Z0-9-_]{10,13})#", $url, $dat);
		$HTMLVER = '-_-'.$dat[1].'.jpg';
	}
	else if(end($ext) === 'jpg' || end($ext) === 'gif' || end($ext) === 'png' )
	{
		$HTMLVER = $url;
	}
	else 
	{
		$HTMLVER = NULL;
	}
	return $HTMLVER;
}
function urldominian($text) {
	preg_match('@^(?:http://)?([^/]+)@i',$text, $coincidencias);
	return $coincidencias[1];
}
function sb_captcha($file = null, $session_var = 'login_captcha', $output = true)
{
	// Create a blank image and add some text
	$im 		= imagecreatetruecolor(150, 40);
	imagefill($im, 0, 0, imagecolorallocate($im, 255, 230, 171));
	$text_color = imagecolorallocate($im, 0, 0, 0);
	$val		= sb_get_captcha_text();//rand(9,true).rand(9,true).rand(9,true).rand(9,true).rand(9,true).rand(9,true);
	//##store captcha value in session
	SB_Session::setVar($session_var, $val);
	//imagestring($im, 50, 5, 5, $val , $text_color);
	imagettftext($im, 25, 2.5, 5, 30, $text_color, INCLUDE_DIR . SB_DS . 'fonts' . SB_DS . 'arial-1.ttf', $val);
	if( !$output )
	{
		ob_start();
		imagejpeg($im, null, 100);
		$buffer = ob_get_clean();
		imagedestroy($im);
		return base64_encode($buffer);
	}
	// Save the image as 'simpletext.jpg'
	header('Content-type: image/jpeg');
	imagejpeg($im, null, 100);
	// Free up memory
	imagedestroy($im);
}
function sb_get_captcha_text($length = 6)
{
	$dic		= 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
	$val		= '';
	for($i = 0; $i < $length; $i++)
	{
		$val .= $dic{rand(0,strlen($dic) - 1)};
	}
	return $val;
}
function sb_permission_exists($perm)
{
	$dbh = SB_Factory::getDbh();
	$query = "SELECT permission_id FROM permissions WHERE permission = '$perm' LIMIT 1";
	if( !$dbh->Query($query) )
		return null;
	return $dbh->FetchRow();
}
function sb_get_permissions($labels = true)
{
	$dbh = SB_Factory::getDbh();
	if( $labels )
	{
		//##get groups
		$query = "SELECT {$dbh->lcw}group{$dbh->rcw} from permissions GROUP BY {$dbh->lcw}group{$dbh->rcw}";
		$groups = $dbh->FetchResults($query);
		//##get group permissions
		for($i = 0; $i < count($groups); $i++)
		{
			$groups[$i]->perms = $dbh->FetchResults("SELECT * FROM permissions WHERE {$dbh->lcw}group{$dbh->rcw} = '{$groups[$i]->group}'");
		}
		return $groups;
	}
	
	
	$perms = array();
	foreach($dbh->FetchResults("SELECT * FROM permissions") as $p)
	{
		$perms[] = $p->permission;
	}
	return $perms;
}
/**
 * Insert new permissions into database
 * @param array $permissions
 */
function sb_add_permissions($permissions)
{
	$dbh = SB_Factory::getDbh();
	$local_permissions = sb_get_permissions(false);
	foreach($permissions as $perm)
	{
		if( in_array($perm['permission'], $local_permissions) ) continue;
		$dbh->Insert('permissions', $perm);
	}
}
function sb_redirect($link = null)
{
	$_link = null;
	if( !$link && isset($_SERVER['HTTP_REFERER']) )
	{
		$_link = $_SERVER['HTTP_REFERER'];
	}
	if( !$_link )
	{
		$_link = SB_Route::_('index.php');
	}
	SB_Factory::GetDbh()->Close();
	header('Location: ' . $link);
	die($link);
}
/**
 * Enqueue a javascript file
 * 
 * @param string $src The script url
 * @param string $id  The unique id for script
 * @param mixed $order 
 * @param mixed $footer 
 * @return  void
 */
function sb_add_script($src, $id, $order = 0, $footer = false)
{
	$scripts =& SB_Globals::GetVar($footer ? 'footer_scripts' : 'scripts');
	if( !$scripts )
	{
		SB_Globals::SetVar($footer ? 'footer_scripts' : 'scripts', array());
		$scripts =& SB_Globals::GetVar($footer ? 'footer_scripts' : 'scripts');
	}
	$scripts[] = array('id' => $id, 'src' => $src);
	
}
function sb_add_style($id, $src)
{
	$styles = &SB_Globals::GetVar('styles');
	if( !$styles )
	{
		SB_Globals::SetVar('styles', array());
		$styles =& SB_Globals::GetVar('styles');
	}
	$styles[] = array('id' => $id, 'href' => $src, 'rel' => 'stylesheet');
}
function sb_include($file, $type = 'class')
{
	if( $type == 'class' && file_exists(INCLUDE_DIR . SB_DS . 'classes' . SB_DS . $file) )
	{
		return require_once INCLUDE_DIR . SB_DS . 'classes' . SB_DS . $file;
	}
	if( $type = 'file' && file_exists(INCLUDE_DIR . SB_DS . $file) )
	{
		return require_once INCLUDE_DIR . SB_DS . $file;
	}
	if( $type == null && file_exists($file) )
	{
		return require_once $file;
	}
}
function sb_include_lib($lib_file)
{
	$lib_path = INCLUDE_DIR . SB_DS . 'libs' . SB_DS . $lib_file;
	sb_include($lib_path, null);
}
/**
 * Get unique filename and return the full path filename
 * @param string $filename
 * @param string $directory
 * @return string The unique full path filename
 */
function sb_get_unique_filename($filename, $directory)
{
	$pathinfo = pathinfo($filename);
	$base = $pathinfo['filename'];
	$ext = isset($pathinfo['extension']) ? $pathinfo['extension'] : '';
	$ext = $ext == '' ? $ext : '.' . $ext;
	$unique = $base;
	$suffix = 0;
	// Get unique file name for the file, by appending random suffix.
	while( file_exists($directory . SB_DS . $unique . $ext) )
	{
		$suffix += rand(1, 999);
		$unique = $base . '-' . $suffix;
	}
	$result =  $directory . SB_DS . $unique . $ext;
	/*
	//Create an empty target file
	if ( !touch($result) )
	{
		//Failed
		$result = false;
	}
	*/
	return $result;
}
function sb_get_module_url($mod)
{
	return MODULES_URL . '/mod_'.$mod;
}
function sb_format_date($date, $format = null, $from_format = null)
{
	$date_format = 'Y-m-d';
	if( defined('DATE_FORMAT') )
	{
		$date_format = DATE_FORMAT;
	}
	if( $format )
	{
		$date_format = $format;
	}
	if( is_numeric($date) )
		return date("$date_format", $date);
	
	$date = str_replace('/', '-', $date);
	$the_date = $from_format ? DateTime::createFromFormat($from_format, $date) : new DateTime($date);
	return $the_date ? $the_date->format($date_format) : null;
}
function sb_format_time($time, $format = null)
{
	if( !is_numeric($time) )
	{
		$time = strtotime(str_replace('/', '-', trim($time)));
	}
	$time_format = 'H:i:s';
	if( defined('DATE_FORMAT') )
	{
		$date_format = DATE_FORMAT;
	}
	if( defined('TIME_FORMAT') )
	{
		$time_format = TIME_FORMAT;
	}
	if( $format )
	{
		$time_format = $format;
	}
	//$date_time = strtotime($date);

	return date("$time_format", $time);
}
function sb_format_datetime($date, $format = null)
{
	$date_format = 'Y-m-d';
	$time_format = 'H:i:s';
	if( defined('DATE_FORMAT') )
	{
		$date_format = DATE_FORMAT;
	}
	if( defined('TIME_FORMAT') )
	{
		$time_format = TIME_FORMAT;
	}
	$the_format = "$date_format $time_format";
	if( $format != null )
	{
		$the_format = $format;  
	}
	$date_time = is_numeric($date) ? $date : strtotime($date);
	
	return date($the_format, $date_time);
}
/**
 * Get browser name
 * 
 * @return string
 */
function sb_get_browser()
{
	$ExactBrowserNameUA=$_SERVER['HTTP_USER_AGENT'];

	if (strpos(strtolower($ExactBrowserNameUA), "safari/") and strpos(strtolower($ExactBrowserNameUA), "opr/")) {
		// OPERA
		$ExactBrowserNameBR="Opera";
	} elseIf (strpos(strtolower($ExactBrowserNameUA), "safari/") and strpos(strtolower($ExactBrowserNameUA), "chrome/")) {
		// CHROME
		$ExactBrowserNameBR="Chrome";
	} elseIf (strpos(strtolower($ExactBrowserNameUA), "msie")) {
		// INTERNET EXPLORER
		$ExactBrowserNameBR="Internet Explorer";
	} elseIf (strpos(strtolower($ExactBrowserNameUA), "firefox/")) {
		// FIREFOX
		$ExactBrowserNameBR="Firefox";
	} elseIf (strpos(strtolower($ExactBrowserNameUA), "safari/") and strpos(strtolower($ExactBrowserNameUA), "opr/")==false and strpos(strtolower($ExactBrowserNameUA), "chrome/")==false) {
		// SAFARI
		$ExactBrowserNameBR="Safari";
	} else {
		// OUT OF DATA
		$ExactBrowserNameBR="OUT OF DATA";
	};

	return $ExactBrowserNameBR;
}
/**
 * Gives a nicely-formatted list of timezone strings.
 *
 *
 * @param string $selected_zone Selected timezone.
 * @return string
 */
function sb_timezone_choice( $selected_zone ) 
{
	static $mo_loaded = false;

	$continents = array( 'Africa', 'America', 'Antarctica', 'Arctic', 'Asia', 'Atlantic', 'Australia', 'Europe', 'Indian', 'Pacific');

	$zonen = array();
	foreach ( timezone_identifiers_list() as $zone ) 
	{
		$zone = explode( '/', $zone );
		if ( !in_array( $zone[0], $continents ) ) 
		{
			continue;
		}

		// This determines what gets set and translated - we don't translate Etc/* strings here, they are done later
		$exists = array(
				0 => ( isset( $zone[0] ) && $zone[0] ),
				1 => ( isset( $zone[1] ) && $zone[1] ),
				2 => ( isset( $zone[2] ) && $zone[2] ),
		);
		$exists[3] = ( $exists[0] && 'Etc' !== $zone[0] );
		$exists[4] = ( $exists[1] && $exists[3] );
		$exists[5] = ( $exists[2] && $exists[3] );

		$zonen[] = array(
				'continent'   => ( $exists[0] ? $zone[0] : '' ),
				'city'        => ( $exists[1] ? $zone[1] : '' ),
				'subcity'     => ( $exists[2] ? $zone[2] : '' ),
				't_continent' => ( $exists[3] ? str_replace( '_', ' ', $zone[0] ) : '' ),
				't_city'      => ( $exists[4] ? str_replace( '_', ' ', $zone[1] ) : '' ),
				't_subcity'   => ( $exists[5] ? str_replace( '_', ' ', $zone[2] ) : '' )
		);
	}
	//usort( $zonen, '_wp_timezone_choice_usort_callback' );

	$structure = array();

	if ( empty( $selected_zone ) ) {
		$structure[] = '<option selected="selected" value="">' . SB_Text::_('-- ciudad --') . '</option>';
	}

	foreach ( $zonen as $key => $zone ) {
		// Build value in an array to join later
		$value = array( $zone['continent'] );

		if ( empty( $zone['city'] ) ) {
			// It's at the continent level (generally won't happen)
			$display = $zone['t_continent'];
		} else {
			// It's inside a continent group

			// Continent optgroup
			if ( !isset( $zonen[$key - 1] ) || $zonen[$key - 1]['continent'] !== $zone['continent'] ) {
				$label = $zone['t_continent'];
				$structure[] = '<optgroup label="'. $label .'">';
			}

			// Add the city to the value
			$value[] = $zone['city'];

			$display = $zone['t_city'];
			if ( !empty( $zone['subcity'] ) ) {
				// Add the subcity to the value
				$value[] = $zone['subcity'];
				$display .= ' - ' . $zone['t_subcity'];
			}
		}

		// Build the value
		$value = join( '/', $value );
		$selected = '';
		if ( $value === $selected_zone ) {
			$selected = 'selected="selected" ';
		}
		$structure[] = '<option ' . $selected . 'value="' .  $value . '">' . $display . "</option>";

		// Close continent optgroup
		if ( !empty( $zone['city'] ) && ( !isset($zonen[$key + 1]) || (isset( $zonen[$key + 1] ) && $zonen[$key + 1]['continent'] !== $zone['continent']) ) ) {
			$structure[] = '</optgroup>';
		}
	}

	// Do UTC
	$structure[] = '<optgroup label="UTC">';
	$selected = '';
	if ( 'UTC' === $selected_zone )
		$selected = 'selected="selected" ';
	$structure[] = '<option ' . $selected . 'value="UTC">UTC</option>';
	$structure[] = '</optgroup>';

	// Do manual UTC offsets
	$structure[] = '<optgroup label="Manual Offsets">';
	$offset_range = array (-12, -11.5, -11, -10.5, -10, -9.5, -9, -8.5, -8, -7.5, -7, -6.5, -6, -5.5, -5, -4.5, -4, -3.5, -3, -2.5, -2, -1.5, -1, -0.5,
			0, 0.5, 1, 1.5, 2, 2.5, 3, 3.5, 4, 4.5, 5, 5.5, 5.75, 6, 6.5, 7, 7.5, 8, 8.5, 8.75, 9, 9.5, 10, 10.5, 11, 11.5, 12, 12.75, 13, 13.75, 14);
	foreach ( $offset_range as $offset ) {
		if ( 0 <= $offset )
			$offset_name = '+' . $offset;
		else
			$offset_name = (string) $offset;

		$offset_value = $offset_name;
		$offset_name = str_replace(array('.25','.5','.75'), array(':15',':30',':45'), $offset_name);
		$offset_name = 'UTC' . $offset_name;
		$offset_value = 'UTC' . $offset_value;
		$selected = '';
		if ( $offset_value === $selected_zone )
			$selected = 'selected="selected" ';
		$structure[] = '<option ' . $selected . 'value="' . $offset_value . '">' . $offset_name . "</option>";

	}
	$structure[] = '</optgroup>';

	return join( "\n", $structure );
}
function sb_delete_dir($directory, $include_dir = true)
{
	$dh = opendir($directory);
	while( ($file = readdir($dh)) !== false)
	{
		if( $file{0} == '.' ) continue;
		if( is_dir($directory . SB_DS . $file) )
		{
			sb_delete_dir($directory . SB_DS . $file);
		}
		else
		{
			unlink($directory . SB_DS . $file);
		}
	}
	closedir($dh);
	if( $include_dir )
	{
		rmdir($directory);
	}
	return true;
}
/**
 * Reply with a json object
 * @param array|object $obj
 */
function sb_response_json($obj)
{
	header('Content-type: application/json');
	//die(json_encode($obj));
	die(sb_json_encode($obj));
}
/**
 * Send an email
 * 
 * @param <unknown> $email 
 * @param <unknown> $subject 
 * @param <unknown> $message 
 * @param <unknown> $headers 
 * @param <unknown> $attachment 
 * @return  
 */
function lt_mail($email, $subject, $message, $headers = null, $attachment = null)
{
	$plain_message = strip_tags($message);
	$headers = !is_array($headers) ? explode("\r\n", $headers) : $headers;
	$charset = 'UTF-8';
	$encoding = 'quoted-printable';
	if( $attachment && file_exists($attachment) )
	{
		//so we use the MD5 algorithm to generate a random hash
		$boundary1 = md5(date('r', time()));
		$boundary2 = md5(rand());
		$headers['mime'] = "MIME-Version: 1.0";
		$headers['type'] = "Content-Type: multipart/mixed; boundary=$boundary1";
		//##read the file
		$attachment_raw = chunk_split(base64_encode(file_get_contents($attachment)));
		$mime_type = mime_content_type($attachment);
		$basename = basename($attachment);
		$_message = <<<EOA
--$boundary1
Content-Type: multipart/alternative; boundary=$boundary2

--$boundary2
Content-Type: text/plain; charset=$charset
Content-Transfer-Encoding: $encoding

$plain_message

--$boundary2 
Content-Type: text/html; charset=$charset
Content-Transfer-Encoding: $encoding

$message
		
--$boundary2--
--$boundary1
Content-Type: $mime_type; name="$basename" 
Content-Disposition: attachment; filename="$basename"
Content-Transfer-Encoding: base64

$attachment_raw
--$boundary1--
EOA;
		$message = $_message;
	}
	$func = SB_Module::do_action('lt_mail_function', 'mail');
	if( $func == 'mail' )
	{
		return mail($email, $subject, $message, implode("\r\n", $headers));
	}
	return call_user_func($func, $email, $subject, $message, implode("\r\n", $headers));
}
function lt_is_admin()
{
	return defined('LT_ADMIN');
}
function lt_die($str)
{
	if( !$str )
	{
		SB_Factory::getDbh()->Close();
		die();
	}
	$url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : SB_Route::_('index.php');
	ob_start();
	?>
	<html>
	<head>
		<title><?php _e('Application Error', 'lt'); ?></title>
		<link rel="stylesheet" href="<?php print BASEURL; ?>/js/bootstrap-3.3.5/css/bootstrap.min.css" />
	</head>
	<body>
		<br/>
		<div class="container">
			<div class="alert alert-danger" role="alert"><?php print $str; ?></div>
			<p class="text-center">
				<a href="<?php print $url; ?>" class="btn btn-default"><?php _e('Back', 'lt'); ?></a>
			</p>
		</div>
	</body>
	</html>
	<?php 
	$html = SB_Module::do_action('lt_die', ob_get_clean());
	
	die($html);
}
function sb_get_dir_contents($path)
{
	if( !is_dir($path) )
		return array();
	function __sb_read_dir($path)
	{
		$items = array();
		$dh = opendir($path);
		while( ($file = readdir($dh)) !== false)
		{
			//skip current and parent dirs
			if( $file == '.' || $file == '..' ) continue;
			if( is_dir($path . '/' . $file) )
			{
				$items[$file] = __sb_read_dir($path . '/' . $file);
			}
			else
			{
				$items[] = $file;
			}
		}
		closedir($dh);
		return $items;
	}
	$contents = __sb_read_dir($path);
	return $contents;
}
function sb_get_file_mime($filename)
{
	$mime = null;
	if( function_exists('finfo_open') )
	{
		$fh = finfo_open(FILEINFO_MIME_TYPE);
		$mime = finfo_file($fh, $filename);
		finfo_close($fh);
	}
	else
	{
		$mime = mime_content_type($filename);
	}
	return $mime;
}
function sb_get_file_extension($filename)
{
	return trim(strrchr($filename, '.'), '.');
	//return pathinfo($filename, PATHINFO_EXTENSION);
}
function sb_copy_recursive($source, $dest)
{
	// Simple copy for a file
	if ( is_file($source) ) 
	{
		return copy($source, $dest);
	}
	
	// Make destination directory
	if ( !is_dir($dest) ) 
	{
		mkdir($dest);
	}
	
	// Loop through the folder
	$dir = dir($source);
	while ( false !== ($entry = $dir->read()) ) 
	{
		// Skip pointers
		if ($entry == '.' || $entry == '..') {
			continue;
		}
	
		// Deep copy directories
		if ( $dest !== $source . SB_DS . $entry ) 
		{
			sb_copy_recursive($source . SB_DS . $entry, $dest . SB_DS . $entry);
		}
	}
	// Clean up
	$dir->close();
	return true;
}
function lt_insert_attachment($filename, $obj_type = '', $id = '', $parent = 0, $attachment_type = '', $title = '', $dir = null)
{
	if( !file_exists($filename) )
		return null;
	sb_include('class.image.php');
	$file_dir	= dirname($filename);
	$mime 		= sb_get_file_mime($filename);
	$extension 	= sb_get_file_extension($filename);
	$name 		= str_replace('.' . $extension, '', basename($filename));
	$data = array(
			'object_type' 	=> $obj_type,
			'object_id'		=> $id,
			'title'			=> !empty($title) ? $title : sb_build_slug(basename($filename)),
			'description'	=> '',
			'type'			=> empty($attachment_type) ? $extension : $attachment_type,
			'mime'			=> $mime,
			'file'			=> $dir == null ? str_replace(UPLOADS_DIR . SB_DS, '', $filename) : 
												$dir . SB_DS . basename($filename),
			'size'			=> file_exists($filename) ? filesize($filename) : 0,
			'parent'		=> $parent,
			'last_modification_date'	=> date('Y-m-d H:i:s'),
			'creation_date'				=> date('Y-m-d H:i:s')
	);
	$dbh = SB_Factory::getDbh();
	$attach_id = $dbh->Insert('attachments', $data);
	if( $attachment_type == 'image' )
	{
		$sizes	= SB_Module::do_action('image_sizes', array(
					array('w' => 55, 'h' => 55),
					array('w' => 150, 'h' => 150),
					array('w' => 330, 'h' => 330),
					array('w' => 500, 'h' => 500)
		));
		foreach($sizes as $size)
		{
			$ih 	= new SB_Image($filename);
			if( $ih->getResource() )
			{
				//if( $ih->getWidth() > $size['w'] )
				//{
					if( $ih->resizeImage($size['w'], $size['h']) )
					{
						$new_image = $file_dir . SB_DS . "$name-{$size['w']}x{$size['h']}.$extension";
						$ih->save($new_image);
						//##insert resized image
						$img_data = array(
							'object_type'	=> $obj_type,
							'object_id'		=> $id,
							'title'			=> basename($new_image),
							'type'			=> 'image',
							'mime'			=> $mime,
							'file'			=> str_replace(UPLOADS_DIR . SB_DS , '', $new_image),
							'size'			=> filesize($new_image),
							'parent'		=> $attach_id,
							'creation_date'	=> date('Y-m-d H:i:s')
						);
						$dbh->Insert('attachments', $img_data);
					}
				//}
				$ih->Destroy();
			}
			
			unset($ih);
		}
	}
	else
	{
		SB_Module::do_action('insert_attachment_type_'.$attachment_type, $attach_id, func_get_args());
	}
	return $attach_id;
}
function lt_delete_attachment($id)
{
	$attachment = new SB_Attachment($id);
	if( !$attachment->attachment_id )
		return false;
	$attachment->Delete();
	return true;
}
/**
 * Get yar months
 * 
 * @return Array
 */
function sb_get_months()
{
	return array(
			__('January', 'lt'),
			__('February', 'lt'),
			__('March', 'lt'),
			__('April', 'lt'),
			__('May', 'lt'),
			__('June', 'lt'),
			__('July', 'lt'),
			__('August', 'lt'),
			__('September', 'lt'),
			__('October', 'lt'),
			__('November', 'lt'),
			__('December', 'lt')
	);
}
/**
 * Convert decimal values for database
 * @param float float
 * @return string the formatted value using decimal sepparator (.) 
 **/
function sb_float_db($float)
{
	//$res = preg_match('/[-+]?[0-9]*[\.|,]?[0-9]*/', $float, $match);
	$res = preg_match('/^\d+[\.|,]\d+$/', trim($float));
	if( $res )
	{
		return str_replace(',', '.', $float);
	}
	return $float;
}
/**
 * Check if a value is float
 **/
function sb_is_float($value)
{
	$value = trim($value);
	if( empty($value) )
		return false;
	if( strstr($value, '-') || strstr($value, ':') )
		return false;
	if( !preg_match('/[0-9]/', $value{0}) )
		return false;
	//return preg_match('/[-+]?[0-9]*[\.|,]?[0-9]*/', $value);
	//return preg_match('/[0-9]*[\.|,]?[0-9]*/', $value);
	return preg_match('/^\d+[\.|,]\d+$/', $value);
}
function sb_is_int($str)
{
	return preg_match('/^\d+$/', $str);
}
/**
 * Check if the string is a datetime
 * 
 * @param string $str
 * @return boolean
 */
function sb_is_datetime($str)
{
	//return (strstr($str, '-') || strstr($str, '/')) && strstr($str, ':') && strtotime($str);
	return strtotime($str); 
}
function sb_dropdown_countries($args)
{
	$def_args = array(
			'id'		=> 'country',
			'selected'	=> -1,
			'class'		=> 'form-control',
			'echo'		=> true,
			'text'		=> __('-- country --')
	);
	$args = array_merge($def_args, $args);
	$select = '<select id="'.$args['id'].'" name="'.$args['id'].'" class="'.$args['class'].'">';
	$select .= '<option value="-1">'.$args['text'].'</option>';
	foreach(include 'countries.php' as $code => $label)
	{
		$select .= '<option value="'.$code.'" '.($args['selected'] == $code ? 'selected' : '').'>'.$label.'</option>';
	}
	$select .= '</select>';
	if( $args['echo'] )
	{
		print $select;
		return true;
	}
	return $select;
}
/**
 * Append a new var into query string
 * 
 * @param string $query_string The query string
 * @param array $new_args The new vars (key => value)
 * @return string
 */
function sb_querystring_append($query_string, $new_args)
{
	
	$params = null;
	parse_str($query_string, $params);
	if( !$params )
		return $query_string;
	foreach($new_args as $arg => $value)
	{
		//if( isset($params[$arg]) )
		//	continue;
		$params[$arg] = $value;
	}
	
	return http_build_query($params);
	
}