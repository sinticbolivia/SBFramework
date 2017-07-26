<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'init.php';
sb_include_lib('S3.php');
define('AWS_ACCESS_KEY', SB_Request::getString('akey'));
define('AWS_SECRET_KEY', SB_Request::getString('skey'));

if( empty(AWS_ACCESS_KEY) || empty(AWS_SECRET_KEY) )
	die('Invalid credentials');
set_time_limit(0);
$dest_dir = dirname(__FILE__) . SB_DS . 'bck';
if( !is_dir($dest_dir) )
	mkdir($dest_dir);

$backup_package = $dest_dir . SB_DS . 'cmscopy.zip';
$dump_file 		= $dest_dir . SB_DS . "database-dump.sql";
$zip_files 		= $dest_dir . SB_DS . 'files.zip';

//##backup database
$cmd = sprintf("/usr/bin/mysqldump -u %s -p%s \"%s\" %s > \"%s\"", DB_USER, DB_PASS, DB_NAME, '', $dump_file);
system($cmd);
//##backup files
$zip = new SB_Compress();
$zip->DestinationFile = $zip_files;
$zip->CompressDir(array(dirname(__FILE__)));
$zip->Save();

//##build package
$zip = new SB_Compress();
$zip->DestinationFile = $backup_package;
$zip->CompressFiles(array($dump_file, $zip_files));
$zip->Save();
//##remove files
unlink($dump_file);
unlink($zip_files);
//##upload to Amazon
$bucket = 'evs-hosted-150f5db0a26cf9';
$s3 = new S3(AWS_ACCESS_KEY, AWS_SECRET_KEY);
$s3->putBucket($bucket, S3::ACL_PUBLIC_READ_WRITE);
$s3->putObjectFile($backup_package, $bucket, basename($backup_package), S3::ACL_PUBLIC_READ);
unlink($backup_package);
if( is_dir($dest_dir) ) rmdir($dest_dir);