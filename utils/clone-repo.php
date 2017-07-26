<?php 
ini_set('display_errors', 1);error_reporting(E_ALL);
$cmd = 'git clone https://github.com/sinticbolivia/little-cms.git ./desktop-unlocker';
$res = system($cmd);
var_dump($res);