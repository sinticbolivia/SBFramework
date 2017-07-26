<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'init.php';
//sb_set_view(SB_Request::getString('view', 'register'));
SB_Request::setVar('view', 'register');
$app->ProcessModule('users');
$template_file 	= $app->htmlDocument->GetTemplate();
sb_process_template($template_file);
sb_show_template();
//$dbh->Close();