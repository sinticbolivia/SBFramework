<?php
define('LT_ADMIN', 1);

require_once dirname(dirname(__FILE__)) . '/init.php';
require_once ADM_INCLUDE_DIR . SB_DS . 'functions.php';
require_once INCLUDE_DIR . SB_DS . 'template-functions.php';
use SinticBolivia\SBFramework\Classes\SB_Route;
use SinticBolivia\SBFramework\Classes\SB_Request;

if( !sb_is_user_logged_in() )
{
	sb_redirect(SB_Route::_('login.php'));
}
$template_file = SB_Request::getString('tpl_file', 'index.php');
SB_Request::setVar('view', 'edit_user');
SB_Request::setVar('id', sb_get_current_user()->user_id);
$app->PrepareTemplate($template_file);
$app->ProcessModule(SB_Request::getVar('mod', 'users'));
$app->ProcessTemplate();
$app->ShowTemplate();