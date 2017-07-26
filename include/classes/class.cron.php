<?php
class SB_Cron
{
	const		MIN_SECONDS 	= 60;
	const		HOUR_SECONDS 	= 3600;
	const 		DAY_SECONDS		= 86400;
	
	protected	$logFile;
	protected 	$name;
	protected	$intervals;
	
	public function __construct($name)
	{
		$this->logFile = TEMP_DIR . SB_DS . 'cron-jobs.log';
		$this->intervals = SB_Module::do_action('cron_intervals', array(
				'every_minute' 	=> self::MIN_SECONDS,
				'every_hour'	=> self::HOUR_SECONDS,
				'every_day'		=> self::DAY_SECONDS
		));
	}
	protected function Log()
	{
		$fh = file_exists($this->logFile) ? fopen($this->logFile, 'a+') : fopen($this->logFile, 'w+');
		fwrite($fh, sprintf("[%s]#\n%s\n", date('Y-m-d H:i:s'), print_r($str, 1)));
		fclose($fh); 
	}
	protected function JobStartAsync($url, $port = 80, $conn_timeout = 30, $rw_timeout = 86400)
	{
		if( !function_exists('fsockopen') )
		{
			$this->Log('Enable to created sockets');
			return false;
		}
		$this->Log('Starting async task wirh url: ' . $url);
		$errno = '';
		$errstr = '';
		set_time_limit(0);
		$url = str_replace(array('http://','https://'), '', $url);
		$url = str_replace($_SERVER['HTTP_HOST'], '', $url);
		$fp = fsockopen($_SERVER['HTTP_HOST'], $port, $errno, $errstr, $conn_timeout);
		if (!$fp)
		{
			echo "$errstr ($errno)<br />\n";
			$this->Log("$errstr ($errno)");
			return false;
		}
		$out = "GET $url HTTP/1.1\r\n";
		$out .= "Host: {$_SERVER['HTTP_HOST']}\r\n";
		$out .= "Connection: Close\r\n\r\n";
		$this->Log($out);
		stream_set_blocking($fp, false);
		stream_set_timeout($fp, $rw_timeout);
		fwrite($fp, $out);
		//$this->Log($out);
		return $fp;
	}
}