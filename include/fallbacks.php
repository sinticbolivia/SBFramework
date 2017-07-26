<?php
if(!function_exists('mime_content_type')) 
{
	function mime_content_type($filename) 
	{
		$mime_types = array(

				'txt' => 'text/plain',
				'htm' => 'text/html',
				'html' => 'text/html',
				'php' => 'text/html',
				'css' => 'text/css',
				'js' => 'application/javascript',
				'json' => 'application/json',
				'xml' => 'application/xml',
				'swf' => 'application/x-shockwave-flash',
				'flv' => 'video/x-flv',

				// images
				'png' => 'image/png',
				'jpe' => 'image/jpeg',
				'jpeg' => 'image/jpeg',
				'jpg' => 'image/jpeg',
				'gif' => 'image/gif',
				'bmp' => 'image/bmp',
				'ico' => 'image/vnd.microsoft.icon',
				'tiff' => 'image/tiff',
				'tif' => 'image/tiff',
				'svg' => 'image/svg+xml',
				'svgz' => 'image/svg+xml',

				// archives
				'zip' => 'application/zip',
				'rar' => 'application/x-rar-compressed',
				'exe' => 'application/x-msdownload',
				'msi' => 'application/x-msdownload',
				'cab' => 'application/vnd.ms-cab-compressed',

				// audio/video
				'mp3' => 'audio/mpeg',
				'qt' => 'video/quicktime',
				'mov' => 'video/quicktime',

				// adobe
				'pdf' => 'application/pdf',
				'psd' => 'image/vnd.adobe.photoshop',
				'ai' => 'application/postscript',
				'eps' => 'application/postscript',
				'ps' => 'application/postscript',

				// ms office
				'doc' => 'application/msword',
				'rtf' => 'application/rtf',
				'xls' => 'application/vnd.ms-excel',
				'ppt' => 'application/vnd.ms-powerpoint',

				// open office
				'odt' => 'application/vnd.oasis.opendocument.text',
				'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
		);

		$ext = @strtolower(array_pop(explode('.',$filename)));
		if (array_key_exists($ext, $mime_types)) {
			return $mime_types[$ext];
		}
		elseif (function_exists('finfo_open')) {
			$finfo = finfo_open(FILEINFO_MIME);
			$mimetype = finfo_file($finfo, $filename);
			finfo_close($finfo);
			return $mimetype;
		}
		else {
			return 'application/octet-stream';
		}
	}
}
function sb_json_normalize_obj($obj)
{
	$data = array();
	$members = get_object_vars($obj);
	/*
	print_r($members);
	foreach($members as $member)
	{
		$data[$member] = $obj->$member;
	}
	*/
	$data = $members;
	
	if( method_exists($obj, 'jsonSerialize') )
	{
		$data = array_merge($data, (array)$obj->jsonSerialize());
	}
	return $data;
}
function sb_json_normalize_array($array)
{
	$data = array();
	foreach($array as $key => $item)
	{
		if( is_object($item) )
		{
			$data[$key] = sb_json_normalize_obj($item);
		}
		elseif( is_array($item) )
		{
			$data[$key] = sb_json_normalize_array($item);
		}
		else
		{
			$data[$key] = $item;
		}
	}
	return $data;
}
function sb_json_encode($obj)
{
	$res = array();
	if( is_array($obj) )
	{
		foreach($obj as $key => $item)
		{
			if( is_object($item) )
			{
				$res[$key] = sb_json_normalize_obj($item);
			}
			elseif( is_array($item) )
			{
				$res[$key] = sb_json_normalize_array($item);
			}
			else
			{
				$res[$key] = $item;
			}
		
		}
	}
	elseif( is_object($obj) )
	{
		$res = sb_json_normalize_obj($obj);
	}
	else 
		$res = null;
	return json_encode($res);
}