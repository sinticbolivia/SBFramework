<?php
class SB_ControllerVideos
{
	public function __construct()
	{
		
	}
	public function task_save()
	{
		global $dbh;
		//print_r($_POST);die();
		$images_path 	= dirname(BASEPATH) . SB_DS . 'imagen' . SB_DS . 'archivos';
		
		$image_id 		= isset($_POST['id']) ? (int)$_POST['id'] : null;
		$title			= $dbh->escape_string(trim($_POST['title']));
		$type			= 'video';
		$image_url		= trim($_POST['image_url']);
		$description	= trim($_POST['description']);
		$cat_id			= (int)$_POST['category'];
		$video_url		= $dbh->escape_string(trim($_POST['yt_video_url']));
		//print_r($_POST);
		//##extract content images
		$content_images = sb_get_content_images($description);
		foreach($content_images as $_img_url)
		{
			if( strstr($_img_url, BASEURL) ) continue;
			$ext = substr($_img_url, strrpos($_img_url, '.'));
			$data = sb_download_image($_img_url);
			$hash = md5($data);
			$new_img_name = 'image-'.$hash . $ext;
			//file_put_contents($images_path . SB_DS . $new_img_name, $data);
			$fh = fopen($images_path . SB_DS . $new_img_name, 'wb');
			fwrite($fh, $data);
			fclose($fh);
			$new_url = BASEURL . '/imagen/archivos/'.$new_img_name;
			$description = str_replace($_img_url, $new_url, $description);
			//print "$_img_url => $new_url<br/>";
		}
		//print $description;
		$description	= $dbh->escape_string($description);
		//die("-------------" . $description);
		if( empty($title) )
		{
			SB_MessagesStack::AddMessage('Debe ingresar un titulo para la entrada', 'error');
			return false;
		}
		if( empty($type) || !in_array($type, array('imagen', 'video')) )
		{
			SB_MessagesStack::AddMessage('Debe seleccionar el tipo de contenido', 'error');
			return false;
		}
		if(  $cat_id <= 0 )
		{
			SB_MessagesStack::AddMessage('Debe seleccionar una categoria', 'error');
			return false;
		}
		$imagen = '';
		if( !empty($image_url) && stristr($image_url, 'http') )
		{
			$ext = substr($image_url, strrpos($image_url, '.'));
			$buffer = sb_download_image($image_url);
			$imagen = 'image-' . md5($buffer) . $ext;
			file_put_contents($images_path . SB_DS . $imagen, $buffer);
		}
		elseif( isset($_FILES['image_file']) && $_FILES['image_file']['size'] > 0 )
		{
			$ext 	= substr($_FILES['image_file']['name'], strrpos($_FILES['image_file']['name'], '.'));
			$hash 	= md5(file_get_contents($_FILES['image_file']['tmp_name']));
			$imagen = 'image-' . $hash . $ext;
			move_uploaded_file($_FILES['image_file']['tmp_name'], $images_path . SB_DS . $imagen);
		}
		$seo_title = sb_sanitize_title($title);
		if( !$image_id )
		{
				
			$query = "INSERT INTO contenido(seo_title,title,id_categoria,tipo,descripcion,imagen,url,visitas,fecha) VALUES".
					"('$seo_title', '$title', $cat_id, '$type', '$description', '$imagen', '$video_url', 0, ".time().")";
			$res = $dbh->query($query);
			if( !$res )
			{
				SB_MessagesStack::AddMessage('Ocurrio un error al insertar la entrada', 'error');
				error_log($dbh->error . 'QUERY WAS:' . $query);
				return false;
			}
			SB_MessagesStack::AddMessage('Nueva entrada creada', 'success');
		}
		else
		{
			$update = "UPDATE contenido SET seo_title = '$seo_title',title = '$title', descripcion = '$description' ";
			if( !empty($imagen) )
			{
				$update .= ", imagen = '$imagen' ";
			}
			if( !empty($video_url) )
			{
				$update .= ", url = '$video_url' ";
			}
			$update .= "WHERE id = $image_id LIMIT 1";
			$res = $dbh->query($update);
			if( !$res )
			{
				SB_MessagesStack::AddMessage('Ocurrio un error al actualizar la entrada', 'error');
				error_log($dbh->error . 'QUERY WAS:' . $query);
				return false;
			}
			SB_MessagesStack::AddMessage('Entrada actualizada', 'success');
		}
		header('Location: index.php?mod=videos');
	}
	public function task_delete()
	{
		global $dbh;
		
		$video_id = isset($_GET['id']) ? (int)$_GET['id'] : null;
		if( !$video_id )
		{
			return false;
		}
		$query = "SELECT * FROM contenido WHERe id = $video_id LIMIT 1";
		$row = $dbh->query($query)->fetch_object();
		//##delete image
		if( !empty($row->imagen) )
		{
			@unlink(BASEPATH . '/imagen/archivos/' . $row->imagen);
		}
		$query = "DELETE FROM contenido WHERE id = $video_id LIMIT 1";
		$dbh->query($query);
		SB_MessagesStack::AddMessage('El video fue borrado.', 'success');
		header('Location: index.php?mod=videos');
	}
}