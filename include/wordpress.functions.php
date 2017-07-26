<?php
/**
 * Functions to have compatibility with wordpress code/engine
 * @version 1.0
 */

global $wpdb;
$wpdb = SB_Factory::getDbh();
$wpdb->prefix = '';

/**
 * 
 * @param unknown $id
 * @param unknown $url
 * @param unknown $args
 * @param unknown $version
 */
function wp_enqueue_style($id, $url, $args, $version)
{
	sb_add_style($id, $url);
}
function wp_enqueue_script($id, $url, $args, $version)
{
	sb_add_script($url, $id);
}
function wp_editor($content, $id)
{
	print "<textarea id=\"$id\" class=\"form-control\" name=\"$id\">$content</textarea>";
}
function wp_nonce_field($key)
{
	
}
function wp_localize_script($handle, $object_name, $l10n, $footer = true)
{
	$json = json_encode($l10n);
	$code =<<<EOS
	print '<script>var $object_name = $json;</script>'; 
EOS;
	SB_Module::add_action($footer ? 'lt_footer' : 'scripts', create_function('', $code));
}
function apply_filters($filter)
{
	$args = func_get_args();
	if( count($args) > 1 )
		unset($args[0]);
	sort($args);
	$code = '$ret = SB_Module::do_action("'.$filter.'", ';
	foreach($args as $arg)
	{
		$code .= "'".addslashes($arg)."',";
	}
	$code = rtrim($code, ',') . ');';
	$ret = null;
	eval($code);
	
	return $ret;
}
function wpautop( $pee, $br = true ) 
{
	return $pee;
}
function plugins_url($path)
{
	return MODULES_URL . '/' . $path;
}
function current_time($type, $gmt = 0)
{
	return date('Y-m-d H:i:s');
}
function admin_url($path)
{
	return SB_Route::_($path, 'backend');
}
function esc_html($html){return $html;}
function get_option($key, $default = null)
{
	return sb_get_parameter($key, $default);
}
function current_user_can($permission)
{
	return sb_get_current_user()->can($permission);
}
function wp_mail($email, $subject, $message, $headers = null)
{
	return lt_mail($email, $subject, $message, $headers);
}
function get_site_url()
{
	return BASEURL;
}