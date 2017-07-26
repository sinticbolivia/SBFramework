<?php
class SB_Image
{
	private $image_file;
	private $image_type;
	private $image;
	private $image_info;
	private $image_ext; 
	public $mime_type;
	
	public function __construct($image_file = null)
	{
		if( $image_file != null )
			$this->load($image_file);
	}
	public function load($image_file)
	{
		if( !file_exists($image_file) )
			die(__FILE__ . ", Line:".__LINE__." | File $image_file not found");
			
		$this->image_file = $image_file;		
		$this->image_info = getimagesize($this->image_file);
		if( !$this->image_info )
		{
			throw new Exception('[SB_Image]: Invalid image info');
		}
		//print_r($this->image_info);die();
		$this->image_type = $this->image_info[2];
		$this->mime_type = $this->image_info['mime'];
      	if( $this->image_type == IMAGETYPE_JPEG ) 
      	{
	        $this->image = imagecreatefromjpeg($this->image_file);
	       
    	} 
    	elseif( $this->image_type == IMAGETYPE_GIF ) 
    	{
        	$this->image = imagecreatefromgif($this->image_file);
      	} 
      	elseif( $this->image_type == IMAGETYPE_PNG ) 
      	{
	        $this->image = imagecreatefrompng($this->image_file);
	         //keep transparency
	        //imagealphablending($this->image, false);
	        //imagesavealpha($this->image, true);
    	}
		if( !$this->image )
		{
			return false;
		}
    	//get image extension
    	$this->image_ext = substr($this->image_file, (strrpos($this->image_file, '.') + 1));
    	
	}
	function save($filename, $image_type = null, $compression = 75, $permissions = null) 
	{
		if($image_type == null )
			$image_type = $this->getImageType();
    	if( $image_type == IMAGETYPE_JPEG ) 
    	{
    		if($filename == null)
      			header('Content-type: image/jpg');
        	imagejpeg($this->image, $filename, $compression);
      	} 
      	elseif( $image_type == IMAGETYPE_GIF ) 
      	{
      		if($filename == null)
      			header('Content-type: image/gif');
	        imagegif($this->image, $filename);
      	} 
      	elseif( $image_type == IMAGETYPE_PNG ) 
      	{
      		if($filename == null)
      			header('Content-type: image/png');
 			imagepng($this->image, $filename, 0, PNG_NO_FILTER);
      	}
      	if( $permissions != null) 
      	{
        	chmod($filename, $permissions);
      	}
   	}
	public function Destroy(){imagedestroy($this->image);}
	public function getWidth() {return $this->image ? imagesx($this->image) : 0;}
   	public function getHeight() {return $this->image ? imagesy($this->image) : 0;}
   	public function getResource(){return $this->image;}
	public function resizeToHeight($height) 
	{
		$ratio = $height / $this->getHeight();
      	$width = $this->getWidth() * $ratio;
      	$this->resize($width,$height);
   	}
   	public function resizeToWidth($width) 
   	{
    	$ratio = $width / $this->getWidth();
      	$height = $this->getheight() * $ratio;
      	$this->resize($width,$height);
   	}
	public function getImageType()
	{
		return $this->image_type;
	}
	public function getExtesion()
	{
		return $this->image_ext;
	}
	public function resize($width, $height)
	{
		$new_image = imagecreatetruecolor($width, $height);
      	imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
      	$this->image = $new_image;
	}
	function scale($scale) 
	{
    	$width = $this->getWidth() * $scale/100;
      	$height = $this->getheight() * $scale/100;
      	$this->resize($width,$height);
   	}
	/**
	 * Resize an image with ratio proportion
	 * 
	 * @param $width int
	 * @param $height int
	 * 
	 * @return bool
	 */
	public function resizeImage($width, $height)
	{
		if( !$this->image ) return false;
		/**
		 * images types accepted
		 */
		$c_imagesTypes = array(IMAGETYPE_GIF => 1,IMAGETYPE_JPEG => 2, IMAGETYPE_PNG => 3);
		if( !file_exists($this->image_file) )
		{
			die("File $this->image_file does not exists");
		}
		$datos 			= getimagesize($this->image_file);
		$img 			= null;
		$content_type 	= null;
		$w 				= $datos[0];
		$h 				= $datos[1];
		$maxWidth 		= $width;
		$maxHeight 		= $height;
		$xRatio 		= $maxWidth / $w;
		$yRatio 		= $maxHeight / $h;
		// Ratio cropping
		$offsetX		= 0;
		$offsetY		= 0;
		$tnHeight 		= null;
		$tnWidth 		= null;
		if ($xRatio * $h < $maxHeight)
		{ 
			// Resize the image based on width
			$tnHeight	= ceil($xRatio * $h);
			$tnWidth	= $maxWidth;
		}
		else // Resize the image based on height
		{
			$tnWidth	= ceil($yRatio * $w);
		 	$tnHeight	= $maxHeight;
		}
		
		$new_image = imagecreatetruecolor($tnWidth, $tnHeight);
		if( $this->image_type == IMAGETYPE_PNG )
		{
			//keep transparency on resize png images
			//$transparent = imagecolorallocate($new_image, 0, 0, 0);
			//imagecolortransparent($new_image, $transparent);
			//imagefilledrectangle($new_image, 0, 0, 127, 127, $transparent);
			imagealphablending($new_image, false);
			imagesavealpha($new_image, true);
		}
		imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $tnWidth, $tnHeight, $this->getWidth(), $this->getHeight());
		$this->image = $new_image;
		//imagedestroy($new_image);
		return true;
		
	}
	/**
	 * Resize and crop image keeping ratio proportion
	 * 
	 * To avoid crop parameter crop needs to be null
	 * Resize parameter format like widthxheight
 	 * @param $crop
	 * @param $size
	 * @return unknown_type
	 */
	public function resize_and_crop($crop = null, $size = null)
	{
		if (is_resource($this->image) === true)
	    {
	    	$x = 0;
	    	$y = 0;
	    	$width = imagesx($this->image);
	    	$height = imagesy($this->image);
	    	/*
	    	CROP (Aspect Ratio) Section
	    	*/
	    	if( is_null($crop) === true )
	    	{
	    		$crop = array($width, $height);
	    	}
	    	else
	    	{
	    		$crop = explode(':', $crop);
	    		if (empty($crop) === true)
	    		{
	    			$crop = array($width, $height);
	    		}
	    		else
	    		{
	    			if ((empty($crop[0]) === true) || (is_numeric($crop[0]) === false))
	    			{
	    				$crop[0] = $crop[1];
	    			}
	
	    			else if ((empty($crop[1]) === true) || (is_numeric($crop[1]) === false))
	    			{
	    				$crop[1] = $crop[0];
	    			}
	    		}
	    		$ratio = array(0 => $width / $height, 1 => $crop[0] / $crop[1],
	    		);
	    		if ($ratio[0] > $ratio[1])
	    		{
	    			$width = $height * $ratio[1];
	    			$x = (imagesx($this->image) - $width) / 2;
	    		}
	    		else if ($ratio[0] < $ratio[1])
	    		{
	    			$height = $width / $ratio[1];
	    			$y = (imagesy($this->image) - $height) / 2;
	    		}
	    		/*
	    		How can I skip (join) this operation
	    		with the one in the Resize Section?
	    		*/
	    		$result = ImageCreateTrueColor($width, $height);
	    		if (is_resource($result) === true)
	    		{
	    			ImageSaveAlpha($result, true);
	    			ImageAlphaBlending($result, false);
	    			ImageFill($result, 0, 0, ImageColorAllocateAlpha($result, 255, 255, 255, 127));
	
	    			ImageCopyResampled($result, $this->image, 0, 0, $x, $y, $width, $height, $width, $height);
	    			$this->image = $result;
	    		}
	    	}
	    	/*
	    	Resize Section
	    	*/
	    	if (is_null($size) === true)
	    	{
	    		$size = array(imagesx($this->image), imagesy($this->image));
	    	}
	    	else
	    	{
	    		$size = explode('x', $size);
	    		if (empty($size) === true)
	    		{
	    			$size = array(imagesx($this->image), imagesy($this->image));
	    		}
	    		else
	    		{
	    			if ((empty($size[0]) === true) || (is_numeric($size[0]) === false))
	    			{
	    				$size[0] = round($size[1] * imagesx($this->image) / imagesy($this->image));
	    			}
	
	    			else if ((empty($size[1]) === true) || (is_numeric($size[1]) === false))
	    			{
	    				$size[1] = round($size[0] * imagesy($this->image) / imagesx($this->image));
	    			}
	    		}
	    	}
	    	$result = ImageCreateTrueColor($size[0], $size[1]);
	    	if (is_resource($result) === true)
	    	{
	    		ImageSaveAlpha($result, true);
	    		ImageAlphaBlending($result, true);
	    		ImageFill($result, 0, 0, ImageColorAllocate($result, 255, 255, 255));
	    		ImageCopyResampled($result, $this->image, 0, 0, 0, 0, $size[0], $size[1], imagesx($this->image), imagesy($this->image));
	    		$this->image = $result;
	    		/*
	    		header('Content-Type: image/jpeg');
	    		ImageInterlace($result, true);
	    		ImageJPEG($result, null, 90);
	    		*/
	    	}
	    }
	}
	public function crop_to_fit($w, $h)
	{
		//----------------------------------------------------------------
		// Crop-to-fit PHP-GD
		// Revision 2 [2009-06-01]
		// Corrected aspect ratio of the output image
		//----------------------------------------------------------------

		$source_aspect_ratio = $this->getWidth() / $this->getHeight();
  		$desired_aspect_ratio = $w / $h;
		$temp_height = $temp_width = 0;
		
		if ( $source_aspect_ratio > $desired_aspect_ratio )
		{
		    //
		    // Triggered when source image is wider
		    //
		    $temp_height = $h;
		    $temp_width = ( int ) ( $h * $source_aspect_ratio );
		}
		else
		{
		    //
		    // Triggered otherwise (i.e. source image is similar or taller)
		    //
		    $temp_width = $w;
		    $temp_height = ( int ) ( $w / $source_aspect_ratio );
		}
		//
		// Resize the image into a temporary GD image
		//
		$temp_gdim = imagecreatetruecolor( $temp_width, $temp_height );
		imagecopyresampled($temp_gdim, $this->image, 0, 0, 0, 0, $temp_width, $temp_height, $this->getWidth(), $this->getHeight());
		//
		// Copy cropped region from temporary image into the desired GD image
		//
		
		$x0 = ( $temp_width - $w ) / 2;
		$y0 = ( $temp_height - $h ) / 2;
		
		$desired_gdim = imagecreatetruecolor( $w, $h );
		imagecopy($desired_gdim, $temp_gdim, 0, 0, $x0, $y0, $w, $h);
		$this->image = $desired_gdim;
	}
	public function displayImageFromBuffer($buffer = null, $mimeType = null)
	{
		$func = null;
		if( $buffer && $mimeType)
		{
			$type = explode("/",$mimeType);
			header("Content-type: $mimeType");
			$func = "image{$type[1]}";
			$func($buffer);
			imagedestroy($buffer);	
		}
		else
		{
			$type = explode("/", $this->mime_type);
			//$func = "image{$type[1]}";
			
			header("Content-type: " . $this->mime_type);
			if( $type[1] == 'png' )
			{
				imagesavealpha($this->image, true);
				imagepng($this->image, null, 0, PNG_NO_FILTER);
			}
			elseif( $type[1] == 'jpg' || $type[1] == 'jpeg' ) 
			{
				imagejpeg($this->image, null, 100);
			}
			elseif( $type[1] == 'gif' )
			{
				imagegif($this->image);
			}
			imagedestroy($this->image);
		}
		return true;
		
	}
	public static function saveImageTo($filename,array $buffer)
	{
		//echo getcwd();
		if($buffer[1] == IMAGETYPE_GIF)
		{
			$quality = round(10 - (90 / 10));
			imagegif($buffer[0],$filename, $quality);
		}
		if($buffer[1]== IMAGETYPE_JPEG)
		{
			imagejpeg($buffer[0],$filename,100);
		}
		if($buffer[1]== IMAGETYPE_PNG)
		{
			$quality = round(10 - (90 / 10));
			imagepng($buffer[0],$filename, $quality);
		}
	}
	public function putWaterMark($waterMarkFilename, $position = 'center')
	{
		if( !file_exists($waterMarkFilename) )
		{
			die("Archivo para la marca de agua '$waterMarkFilename' no existe.");
		}
		//$water = imagecreatefrompng($waterMarkFilename);
		//imagealphablending($water, false);
		/*
		imagesavealpha($water, true);
		header('Content-type: image/png');
		imagepng($water);die();
		*/
		$wm = new SImage($waterMarkFilename);
		if( $wm->getImageType() != IMAGETYPE_PNG )
		{
			die("La marca de agua no es una imagen PNG");
		}
		$width = $wm->getWidth();
		$height = $wm->getHeight();
		//$water = imagecreatefrompng($waterMarkFilename);
		if( $width >= $this->getWidth() )
		{
			$wm->resizeImage( $this->getWidth() - 10, $height );
			//$wm->displayImageFromBuffer();
			//die();
			$width = $wm->getWidth();
			$height = $wm->getHeight();
		}
		$water = $wm->getResource();
		if( $position == 'center' )
		{
			imagecopy($this->image, $water,  ($this->getWidth() / 2) - ($width / 2),
					 	($this->getHeight() / 2) - ($height / 2), 0, 0, $width, $height);	
		}
		elseif( $position == 'right_bottom' )
		{
			$dst_x = imagesx($this->image) - ($width + 1);
			$dst_y = imagesy($this->image) - ($height + 1);
			imagecopy($this->image, $water, $dst_x, $dst_y, 0, 0, $width, $height);
		}
		
		imagedestroy($water);
		unset($wm);
		 
	}
	
	public function split($image_file, $parts = 4)
	{
		$path = dirname($image_file);
		if( !file_exists($image_file)) die('image not found');
		$datos = getimagesize($image_file);
		$src_w = $datos[0];
		$src_h = $datos[1];
		
		$width = $src_w / $parts;
		$height = $src_h;
		$source_x = 0;
		$source_y = 0;
		// Create images
		//$source = imagecreatefrompng($image_file);
		$source = $content_type = $save_image_func = $ext = null;
		//verify the image type
		if($datos[2] == IMAGETYPE_GIF)
		{
			$source = imagecreatefromgif($image_file);
			$content_type = 'image/gif';
			$save_image_func = 'imagegif';
			$ext = '.gif';
		}
		if($datos[2]== IMAGETYPE_JPEG)
		{
			$source = imagecreatefromjpeg($image_file);
			$content_type = 'image/jpeg';
			$save_image_func = 'imagejpeg';
			$ext = '.jpg';
		}
		if($datos[2]== IMAGETYPE_PNG)
		{
			$source = imagecreatefrompng($image_file);
			$content_type = 'image/png';
			$save_image_func = 'imagepng';
			$ext = '.png';
		}
		if($source == null)
		{
			die('source null');
			return false;
		}
		//create new image
		$new = imagecreatetruecolor($width, $height);
		$files = array();
		for($i = 0; $i < $parts; $i++)
		{
			// Copy
			imagecopy($new, $source, 0, 0, $source_x, $source_y, $width, $height);
			$source_x += $width;	
			// Output image
			//header('Content-Type: '. $content_type);
			$files[] = $path . '/ns_'.$i.$ext;
			$save_image_func($new, $path . '/ns_'.$i.$ext);	
		}
		return $files;
	}
	/**
	 * 
	 * @param $text
	 * @param $font
	 * @param $text_rgb array
	 * @return void
	 */
	public function write_text($text, $bg_rgb, $font, $font_size, $text_rgb, $top = 10, $left = 20  )
	{
		if( $this->image == null )
		{
			$this->image = imagecreatetruecolor(493, 68);
			/*
			$gris   = imagecolorallocate($this->image, 128, 128, 128);
			$negro  = imagecolorallocate($this->image, 0, 0, 0);
			$blanco = imagecolorallocate($this->image, 255, 255, 255);
			*/
			$bg_color = imagecolorallocate($this->image, $bg_rgb['red'], $bg_rgb['green'], $bg_rgb['blue']);
			imagefilledrectangle($this->image, 0, 0, 492, 68, $bg_color);
		}
		$color = imagecolorallocate($this->image, $text_rgb['red'], $text_rgb['green'], $text_rgb['blue']);
		imagettftext($this->image, $font_size, 0, $left, $top, $color, $font, $text);
	}
	/**
	 * @brief Try to fix a jpeg bad image
	 * 
	 * @param <unknown> $f 
	 * @param <unknown> $fix 
	 * @return  
	 */
	protected function CheckJpeg($filename, $fix = false )
	{
		# check for jpeg file header and footer - also try to fix it
		if ( false !== (@$fd = fopen($filename, 'r+b' )) )
		{
			if ( fread($fd,2)==chr(255).chr(216) )
			{
				fseek ( $fd, -2, SEEK_END );
				if ( fread($fd,2)==chr(255).chr(217) )
				{
					fclose($fd);
					return true;
				}
				else
				{
					if ( $fix && fwrite($fd,chr(255).chr(217)) ){return true;}
					fclose($fd);
					return false;
				}
			}
			else
			{
				fclose($fd); 
				return false;
			}
		}
		else
		{
			return false;
		}
	}
}
?>