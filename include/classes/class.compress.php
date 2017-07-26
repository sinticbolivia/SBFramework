<?php
ini_set('display_errors', 1);error_reporting(E_ALL);
defined('SB_DS') or define('SB_DS', DIRECTORY_SEPARATOR);
class SB_Compress
{
	protected 	$DestinationFile = null;
	protected 	$zip;
	protected	$baseDirectory = null;
	
	public function __construct()
	{
		$this->zip = new ZipArchive();
	}
	public function CompressString($str)
	{
		return gzcompress($str);
		/*
		 $gz = gzopen($this->DestinationFile, 'w9');
		 while( ($file = readdir($dh)) !== false)
		 {
		 if( $file == '.' || $file == '..' || $file == basename($this->DestinationFile) ) continue;
		 $filename = $directory . SB_DS . $file;
		 print "Compressing: $filename<br/>";
		 gzwrite($gz, file_get_contents($filename));
		 }
		 gzclose($gz);
		 */
	}
	public function DecompressString($str)
	{
		return gzuncompress($str);
	}
	public function CompressDir($directory, $parent_dir = null)
	{
		$dirs = null;
		if( !is_array($directory) )
			$dirs = array($directory);
		else
			$dirs = $directory;
		
		foreach($dirs as $directory)
		{
			if( !is_dir($directory) )
				throw new Exception('The directory does not exists');
			if( !is_readable($directory) )
				throw new Exception('The directory can\'t be readed.');
			if( !$this->DestinationFile )
				throw new Exception('Destination file invalid.');
			
			
			$dh = opendir($directory);
			while( ($file = readdir($dh)) !== false)
			{
				if( $file == '.' || $file == '..' /*|| $file == basename($this->DestinationFile)*/ ) continue;
				$filename 	= $directory . SB_DS . $file;
				$local_name = $parent_dir ? $parent_dir . SB_DS . basename($directory) . SB_DS . $file : basename($directory) . SB_DS . $file;
				if( is_dir($filename) )
				{
					
					$this->CompressDir($filename, $parent_dir ? $parent_dir . SB_DS . basename($directory) : basename($directory));
				}
				else
				{
					if( !is_readable($filename) )
					{
						//print "Skiping: $filename, unable to read<br/>";
						continue;
					}
					//print "Compressing: $filename ----> $local_name<br/>";
					$this->zip->addFile($filename, $local_name);
				}
					
			}
			closedir($dh);
		}
	}
	public function CompressFiles(array $files)
	{
		foreach($files as $file)
		{
			if( !file_exists($file) ) continue;
			$this->zip->addFile($file, basename($file));
		}
	}
	public function Save()
	{
		$this->zip->close();
	}
	public function Decompress($filename, $destination)
	{
		if( !is_dir($destination) )
			mkdir($destination);
		
		$this->zip->open($filename);
		$this->zip->extractTo($destination);
		$this->zip->close();
	}
	public function __set($var, $value)
	{
		if( $var == 'DestinationFile' )
		{
			$this->DestinationFile = $value;
			/*
			if( file_exists($this->DestinationFile) )
				unlink($this->DestinationFile);
			*/
			$this->zip->open($this->DestinationFile, ZipArchive::CREATE | ZipArchive::OVERWRITE);
			
		}
	}
}
