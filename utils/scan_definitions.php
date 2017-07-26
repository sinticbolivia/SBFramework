<?php
ini_set('display_errors', 1);error_reporting(E_ALL);
define('SB_DS', DIRECTORY_SEPARATOR);
function get_file_definitions($file)
{
	$definitions = '';
	$buffer = file_get_contents($file);
	preg_match_all('/_*\(["|\']([A-Z0-9_]*)["|\'].*[\)]/', $buffer, $matches);
	$definitions .= "//##file ".basename($file)." definitions\n";
	foreach($matches[1] as $def)
	{
		$definitions .= "define('$def', '$def');\n";
	}	
	return $definitions;
}
$mod 		= isset($_GET['mod']) ? $_GET['mod'] : null;
$file		= isset($_GET['file']) ? $_GET['file'] : null;
$definitions = '';
if( $file )
{
	$definitions = get_file_definitions($file);
}
else
{
	$mod_dir	= dirname(dirname(__FILE__)) . SB_DS . 'modules' . SB_DS . 'mod_' . $mod;
	$base_dir 	= $mod_dir . SB_DS . 'views' . SB_DS . 'admin';
	
	$dh 		= opendir($base_dir);
	$definitions = get_file_definitions($mod_dir . SB_DS . 'init.php');
	
	while( ($file = readdir($dh)) !== false)
	{
		if( $file == '.' || $file == '..' ) continue;
		$the_file = $base_dir . SB_DS . $file;
		$definitions .= get_file_definitions($the_file);
	}
	closedir($dh);
}
die($definitions);