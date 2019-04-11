<?php
$filename = basename($_SERVER['REQUEST_URI']);
//if( preg_match('/\.(map|jpg|jpeg|css|js|gif|png|txt)$/', $filename, $matches) )
if( preg_match('/\.(map)$/', $filename, $matches) )
	return false;
define('SBFRAMEWORK_VER', '2.0.0');
$base_dir = dirname(__FILE__);
//$cfg_file = file_exists($base_dir . DIRECTORY_SEPARATOR . 'config.php') ? 'config.php' : 'config-min.php';
$cfg_file = defined('LT_INSTALL') ? 'config-min.php' : 'config.php';
if( defined('CFG_FILE') )
{
	$cfg_file = CFG_FILE;
}
//##detect OS
if( in_array(PHP_OS, array('WIN32', 'WINNT', 'Windows')) )
	define('OS_WIN', true);
else if( PHP_OS == 'Linux' || PHP_OS == 'Unix' )
	define('OS_LINUX', true);
	
if( !defined('LT_INSTALL') && !file_exists($base_dir . DIRECTORY_SEPARATOR . $cfg_file) )
{
	header('Location: install/index.php');die();
}
	
require_once $base_dir . DIRECTORY_SEPARATOR . $cfg_file;
require_once $base_dir . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'core-functions.php';

if( DEVELOPMENT == 1 )
{
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
}
use SinticBolivia\SBFramework\Classes\SB_Module;
use SinticBolivia\SBFramework\Classes\SB_Request;// as SB_Request;
use SinticBolivia\SBFramework\Classes\SB_Session;// as SB_Session;
use SinticBolivia\SBFramework\Classes\SB_Factory;// as SB_Factory;
use SinticBolivia\SBFramework\Classes\SB_Menu;
use SinticBolivia\SBFramework\Classes\SB_Route;

require_once INCLUDE_DIR . SB_DS . 'functions.php';
require_once INCLUDE_DIR . SB_DS . 'formatting.php';

SB_Session::start();
SB_Request::Start();
$app = null;
if( $rapp = SB_Request::getString('ltapp') )
{
	if( (int)$rapp === -1 )
	{
		SB_Session::unsetVar('ltapp');
	}
	else
	{
		$app = $rapp;
		SB_Session::setVar('ltapp', $app);
	}
	 
}
elseif( SB_Session::getVar('ltapp') )
{
	$app = SB_Session::getVar('ltapp');
}
elseif( defined('APP_NAME') )
{
	$app = APP_NAME;
}

//$app = SB_Application::GetApplication(defined('APP_NAME') ? APP_NAME : null);
$app = SB_Factory::getApplication($app);

set_error_handler('sb_error_handler', E_ALL);
$app->Load();

if( defined('LT_INSTALL') )
{
	return true;
}
$dbh = SB_Factory::getDbh();
//##start and propagate settings
sb_initialize_settings();
$app->LoadLanguage();
//##load modules
$app->LoadModules();
$app->StartRewrite();
$app->Start();
//ini_set('post_max_size', '128M');
//ini_set('upload_max_filesize', '128M');
//setlocale(LC_NUMERIC, 'en_GB.utf-8');
//error_log(__FILE__);
//var_dump($_GET['rule']);
