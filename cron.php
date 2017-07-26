<?php
define('LT_CRON', 1);
error_log('STARTING CRON JOBS THREAD');
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'init.php';
$jobs = SB_Module::do_action('cron_job', array());
if( count($jobs) <= 0 )
{
	error_log("LT_CRON: no cron jobs");
	return false;
}
/*
$i = 0;
while($i < 10000)
{
	error_log("\$i => $i");
	sleep(1);
	$i++;
}
*/
$stamps = (object)sb_get_parameter('cron_times', array());
foreach($jobs as $id => $cron)
{
	if( !isset($stamps->$id) )
	{
		error_log("Executing cron job: {$cron['name']} at " . time());
		$stamps->$id = time();
		call_user_func($cron['callback']);
	}
	else
	{
		$last_time = $stamps->$id;
		$ctime = time();
		$time_diff = $ctime - $last_time;
		if( $time_diff >= $cron['interval'] )
		{
			$stamps->$id = time();
			call_user_func($cron['callback']);
		}
	}
}
sb_update_parameter('cron_times', $stamps);