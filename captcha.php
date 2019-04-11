<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'init.php';
use SinticBolivia\SBFramework\Classes\SB_Request;
use SinticBolivia\SBFramework\Classes\SB_Session;

if( SB_Request::getInt('inverse') )
	SB_Session::setVar('inverse_captcha', 1);
sb_captcha(null, SB_Request::getString('var', 'login_captcha'));
die();