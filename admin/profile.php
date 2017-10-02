<?php
define('LT_ADMIN', 1);

require_once dirname(dirname(__FILE__)) . '/init.php';
require_once ADM_INCLUDE_DIR . SB_DS . 'functions.php';
require_once INCLUDE_DIR . SB_DS . 'template-functions.php';
if( !sb_is_user_logged_in() )
{
	sb_redirect(SB_Route::_('login.php'));
}
$template_file = SB_Request::getString('tpl_file', 'index.php');
SB_Request::setVar('view', 'edit_user');
SB_Request::setVar('id', sb_get_current_user()->user_id);
$app->ProcessModule(SB_Request::getVar('mod', 'users'));
$template_file = $app->htmlDocument->GetTemplate();//SB_Request::getString('tpl_file', 'index.php');
$app->ProcessTemplate($template_file);
$app->ShowTemplate();
//sb_process_module(SB_Request::getVar('mod', 'users'));
//sb_process_template($template_file);
//sb_show_template();
//$dbh->Close();