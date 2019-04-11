<?php
define('LT_ADMIN', 1);

require_once dirname(dirname(__FILE__)) . '/init.php';
require_once INCLUDE_DIR . SB_DS . 'template-functions.php';
require_once ADM_INCLUDE_DIR . SB_DS . 'functions.php';
use SinticBolivia\SBFramework\Classes\SB_Request;
if( !sb_is_user_logged_in() )
{
	sb_redirect(b_route('login.php'));
}
try
{
	$template_file = SB_Request::getString('tpl_file', 'index.php');
	$app->PrepareTemplate($template_file);
	$app->ProcessModule(SB_Request::getVar('mod', 'settings'));
	$app->ProcessTemplate();
	$app->ShowTemplate();
}
catch(Exception $e)
{
	print $e->getMessage();
}

