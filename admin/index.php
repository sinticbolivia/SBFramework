<?php
namespace SinticBolivia\BeetleCMS\Admin;
use SinticBolivia\SBFramework\Classes\SB_Request;
use SinticBolivia\SBFramework\Classes\SB_Route;
use Exception;

define('LT_ADMIN', 1);
require_once dirname(dirname(__FILE__)) . '/init.php';
require_once INCLUDE_DIR . SB_DS . 'template-functions.php';
require_once ADM_INCLUDE_DIR . SB_DS . 'functions.php';
$ajax = SB_Request::getInt('ajax');

if( !sb_is_user_logged_in() )
{
    if( $ajax )
    {
        sb_response_json(array(
            'status' => 'error', 
            'error' => __('You session has expired, please start session again'),
            'login_url' => SB_Route::_('login.php?tpl=module')
        ));
    }
	header('Location: ' . SB_Route::_('login.php'));die();
}
try
{
    $mod = SB_Request::getString('mod', 'dashboard');
    $app->PrepareTemplate();
    $app->ProcessModule($mod);
    $app->ProcessTemplate();
    $app->ShowTemplate();
}
catch(Exception $e)
{
	
    ob_end_clean();
    ob_end_flush();
    if( $ajax )
    {
        sb_response_json(array(
            'status' => 'error', 
            'error' => $e->getMessage()
        ));
    }
    lt_die($e->getMessage());
}

