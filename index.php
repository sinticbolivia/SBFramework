<?php
/**
 * Main entry file to start the application
 * 
 * @package SBFramework
 */
 //##include the SBFramework initializacion
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'init.php';
//##get the main module to process
$mod = defined('SB_MAIN_MODULE') ? SB_MAIN_MODULE : SB_Request::getString('mod', 'content');
//##set the main module to process
$app->ProcessModule($mod);
$template_file 	= $app->htmlDocument->GetTemplate();// SB_Request::getString('tpl', 'index.php');
$app->ProcessTemplate($template_file);
$app->ShowTemplate();