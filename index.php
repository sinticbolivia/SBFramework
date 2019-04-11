<?php
/**
 * Main entry file to start the application
 * 
 * @package SBFramework
 */
//##include the SBFramework initializacion
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'init.php';
use SinticBolivia\SBFramework\Classes\SB_Request;
use SinticBolivia\SBFramework\Classes\SB_ApiRestException;

try
{
    //##get the main module to process
    $mod = defined('SB_MAIN_MODULE') ? SB_MAIN_MODULE : SB_Request::getString('mod', 'content');
    //##set the main module to process
    $app->ProcessModule($mod);
    $app->PrepareTemplate();
    $app->ProcessTemplate();
    $app->ShowTemplate();
}
catch(SB_ApiRestException $e)
{
    $e->ResponseError($e->getMessage());
}
catch(Exception $e)
{
    lt_die($e->getMessage());
}
