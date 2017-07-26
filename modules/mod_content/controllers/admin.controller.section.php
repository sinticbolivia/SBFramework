<?php
class LT_AdminControllerContentSection extends SB_Controller
{
	public function task_default()
	{
		global $content_types;
		
		if( !sb_get_current_user()->can('manage_sections') )
		{
			die('You dont have enough permissions');
		}
		$fo			= SB_Request::getString('fo', 'page');
		$keyword	= SB_Request::getString('keyword');
		$page 		= SB_Request::getInt('page', 1);
		$order_by 	= SB_Request::getString('order_by', 'creation_date');
		$order 		= SB_Request::getString('order', 'desc');
		$limit		= SB_Request::getInt('limit', defined('ITEMS_PER_PAGE') ? ITEMS_PER_PAGE : 25);
		if( $order_by == 'creation_date' )
		{
			$order_by = 's.creation_date';
		}
		elseif( $order_by == 'section_id' )
		{
			$order_by = 's.section_id';
		}
		/*
		$query 			= "SELECT {columns}, STR_TO_DATE(m1.meta_value, '%d-%m-%Y') AS publish_date, STR_TO_DATE(m2.meta_value, '%d-%m-%Y') AS end_date ".
							"FROM section s, section_meta m1, section_meta m2 " . 
							"WHERE s.section_id = m1.section_id " .
							"AND s.section_id = m2.section_id " .
							"AND m1.meta_key = '_publish_date' " . 
							"AND m2.meta_key = '_end_date' ";
		*/					
		$query 			= "SELECT {columns} ".
							"FROM section s " . 
							"WHERE 1 = 1 ";
		if( $fo == 'page' )
		{
			$query .= "AND (for_object = 'page' OR for_object IS NULL)";
		}	
		else
		{
			$query .= "AND for_object = '$fo' ";
		}
		if( $keyword )
		{
			$query .= "AND s.name LIKE '%$keyword%' ";
		}
		$order_query 	= "ORDER BY $order_by $order";
		if( $this->dbh->db_type == 'mysql' )
		{
			$columns = "*, STR_TO_DATE(m1.meta_value, '%d-%m-%Y') AS publish_date, STR_TO_DATE(m2.meta_value, '%d-%m-%Y') AS end_date ";
		}
		elseif( $this->dbh->db_type == 'sqlite3' )
		{
			$columns = "*, DATE(strftime('%d-%m-%Y', m1.meta_value)) AS publish_date, DATE(strftime('%d-%m-%Y', m2.meta_value)) AS end_date ";
		}
		$this->dbh->Query(str_replace('{columns}', 'COUNT(*) AS total_rows', $query));
		$total_rows 	= $this->dbh->FetchRow()->total_rows;
		$total_pages	= ceil($total_rows / $limit);
		$offset			= ($page <= 1) ? 0 : ($page - 1) * $limit;
		$limit_query 	= "LIMIT $offset, $limit";
		//##set meta for the query
		$query 			= "SELECT {columns}".
							"FROM section s " .
							"LEFT JOIN section_meta m1 ON (m1.section_id = s.section_id AND m1.meta_key = '_publish_date') " .
							"LEFT JOIN section_meta m2 ON (m2.section_id = s.section_id AND m2.meta_key = '_end_date') " .
							"WHERE 1 = 1 ";
		if( $fo == 'page' )
		{
			$query .= "AND (for_object = 'page' OR for_object IS NULL)";
		}	
		else
		{
			$query .= "AND for_object = '$fo' ";
		}
		$query = str_replace('{columns}', $columns, $query) . " $order_query $limit_query";
		$this->dbh->Query($query);
		$sections = array();
		foreach($this->dbh->FetchResults() as $row)
		{
			$s = new LT_Section();
			$s->SetDbData($row);
			$sections[] = $s;
		}
		$title 			= __('Sections', 'content');
		$label_btn_new 	= __('New', 'content');
		$link_btn_new	= SB_Route::_('index.php?mod=content&view=section.new');
		$new_order 		= $order == 'desc' ? 'asc' : 'desc';
		if( $fo != 'page' )
		{
			$title 			= $content_types[$fo]['section']['labels']['listing_label'];
			$label_btn_new 	= $content_types[$fo]['section']['labels']['new_label'];
			$link_btn_new	= SB_Route::_('index.php?mod=content&view=section.new&fo='.$fo);
		}
		sb_set_view_var('title', $title);
		sb_set_view_var('label_btn_new', $label_btn_new);
		sb_set_view_var('link_btn_new', $link_btn_new);
		sb_set_view_var('sections', $sections);
		sb_set_view_var('id_order_link', SB_Route::_('index.php?mod=content&view=section.default&order_by=section_id&order='.$new_order));
		sb_set_view_var('name_order_link', SB_Route::_('index.php?mod=content&view=section.default&order_by=name&order='.$new_order));
		sb_set_view_var('date_order_link', SB_Route::_('index.php?mod=content&view=section.default&order_by=creation_date&order='.$new_order));
		sb_set_view_var('publishdate_order_link', SB_Route::_('index.php?mod=content&view=section.default&order_by=publish_date&order='.$new_order));
		sb_set_view_var('enddate_order_link', SB_Route::_('index.php?mod=content&view=section.default&order_by=end_date&order='.$new_order));
		sb_set_view_var('order_link', SB_Route::_('index.php?mod=content&view=section.default&order_by=show_order&order='.$new_order));
		sb_set_view_var('total_pages', $total_pages);
		sb_set_view_var('current_page', $page);
		$this->document->SetTitle($title);
		SB_Module::do_action_ref('sections_before_show', $this);
	}
	public function task_new()
	{
		global $content_types;
		
		if( !sb_get_current_user()->can('create_section') )
		{
			die('You dont have enough permissions');
		}
		$fo			= SB_Request::getString('fo', 'page');
		//var_dump(BASEURL);
		//$data = parse_url(BASEURL);
		sb_include_module_helper('content');
		//sb_add_script($data['path'] . '/js/tinymce/tinymce.min.js', 'tinymce');
		sb_add_script(BASEURL . '/js/fineuploader/all.fine-uploader.min.js', 'fineuploader');
		$title = SB_Text::_('New Section', 'content');
		if( $fo != 'page' && isset($content_types[$fo]) )
		{
			$title = $content_types[$fo]['section']['labels']['new_label'];
		}
		sb_set_view_var('title', $title);
		sb_set_view_var('image_url', null);
		sb_set_view_var('remove_banner_link', SB_Route::_('index.php?mod=content&task=section.remove_banner&id=temp'));
		sb_set_view_var('upload_endpoint', SB_Route::_('index.php?mod=content&task=section.upload_banner'));
		sb_set_view_var('fo', $fo);
		$this->document->SetTitle(SBText::_('Crear nuevo contenido'));
	}
	public function task_edit()
	{
		global $content_types;
		
		if( !sb_get_current_user()->can('edit_section') )
		{
			die('You dont have enough permissions');
		}
		$fo			= SB_Request::getString('fo', 'page');
		$section_id = SB_Request::getInt('id');
		if( !$section_id )
		{
			SB_MessagesStack::AddMessage(SB_Text::_('El identificador de seccion no es valido', 'content'), 'info');
			sb_redirect(SB_Route::_('index.php?mod=content&view=section.default'));	
		}
		$dbh = SB_Factory::getDbh();
		$query = "SELECT * FROM section WHERE section_id = $section_id LIMIT 1";
		if( !$dbh->Query($query) )
		{
			SB_MessagesStack::AddMessage(SB_Text::_('La seccion no existe', 'content'), 'error');
			sb_redirect(SB_Route::_('index.php?mod=content&view=section.default'));
		}
		$section = new LT_Section();
		$section->SetDbData($dbh->FetchRow());
		sb_set_view('section.new');
		
		$image_url = null;
		if( $section->_banner )
		{
			$image_url = MOD_CONTENT_BANNERS_URL . SB_DS . $section->_banner;
			if( !file_exists(MOD_CONTENT_BANNERS_DIR . SB_DS . $section->_banner) )
			{
				$image_url = null;
			}
		}
		
		//$data = parse_url(BASEURL);
		//var_dump($data['path']);
		sb_include_module_helper('content');
		//sb_add_script(BASEURL . '/js/tinymce/tinymce.min.js', 'tinymce');
		sb_add_script(BASEURL . '/js/fineuploader/all.fine-uploader.min.js', 'fineuploader');
		$title = __('Edit Section', 'content');
		if( $fo != 'page' && isset($content_types[$fo]) )
		{
			$title = $content_types[$fo]['section']['labels']['edit_label'];
		}
		sb_set_view_var('title', $title);
		sb_set_view_var('section', $section);
		sb_set_view_var('fo', $fo);
		sb_set_view_var('image_url', $image_url);
		sb_set_view_var('remove_banner_link', SB_Route::_('index.php?mod=content&task=section.remove_banner&id='.$section->section_id));
		sb_set_view_var('upload_endpoint', SB_Route::_('index.php?mod=content&task=section.upload_banner&id='.$section->section_id));
		$this->document->SetTitle($title);
	}
	public function task_save()
	{
		$fo			= SB_Request::getString('fo', 'page');
		$section_id = SB_Request::getInt('section_id');
		$name 		= SB_Request::getString('section_name');
		$desc 		= SB_Request::getString('description');
		$parent_id	= SB_Request::getInt('parent_id', 0);
		$status		= SB_Request::getString('status', 'publish');
		
		if( empty($name) )
		{
			SB_MessagesStack::AddMessage(SB_Text::_('Debe ingresar un nombre para la secci&oacute;n.', 'content'), 'error');
			if( $section_id )
				$this->task_edit();
			else 
				$this->task_new();
			return false;
		}
		if( !$section_id && !sb_get_current_user()->can('create_section') )
		{
			die('You dont have enough permissions to create a section');
		}
		if( $section_id && !sb_get_current_user()->can('edit_section') )
		{
			die('You dont have enough permissions to edit a section');
		}
		if( $parent_id < 0 )
			$parent_id = 0;
		$cdate = date('Y-m-d H:i;s');
		$dbh = SB_Factory::getDbh();
		$data = array(
				'name'						=> $name,
				'parent_id'					=> $parent_id,
				'description'				=> $desc,
				'status'					=> $status,
				'for_object'				=> $fo,
				'last_modification_date'	=> $cdate
		);
		$publish_date 		= SB_Request::getString('publish_date');
		$end_date 			= SB_Request::getString('end_date');
		$calculated_date 	= SB_Request::getInt('calculated_date');
		$calculated_end_date = SB_Request::getInt('calculated_end_date');
		
		$publish_time = strtotime($publish_date);
		$end_date_time = strtotime($end_date);
		if( $end_date_time <= $publish_time )
		{
			$end_date = date(DATE_FORMAT, strtotime(date('Y')+35 . '-01-01'));
		}
		/*
		if( $calculated_end_date <= $calculated_date )
		{
			$calculated_end_date = $calculated_date + 1;
		}
		*/
		$msg = $link = null;
		if( !$section_id )
		{
			$data['slug']			= sb_build_slug($name);
			$data['creation_date'] = $cdate;
			$section_id = $dbh->Insert('section', $data);
			//##add section meta
			sb_add_section_meta($section_id, '_publish_date', $publish_date);
			sb_add_section_meta($section_id, '_end_date', $end_date);
			sb_add_section_meta($section_id, '_calculated_date', $calculated_date);
			sb_add_section_meta($section_id, '_calculated_end_date', $calculated_end_date);
			sb_add_section_meta($section_id, '_use_calculated_dates', SB_Request::getInt('use_calculated_dates'));
			$msg = SB_Text::_('La secci&oacute;n fue creada correctamente.', 'content');
			$link = SB_Route::_('index.php?mod=content&view=section.default&fo='.$fo);
		}
		else 
		{
			$section = new LT_Section($section_id);
			if( empty($section->slug) )
			{
				$data['slug']			= lt_section_get_unique_slug($name);
			}
			$dbh->Update('section', $data, array('section_id' => $section_id));
			//##add section meta
			sb_update_section_meta($section_id, '_publish_date', $publish_date);
			sb_update_section_meta($section_id, '_end_date', $end_date);
			sb_update_section_meta($section_id, '_calculated_date', $calculated_date);
			sb_update_section_meta($section_id, '_calculated_end_date', $calculated_end_date);
			sb_update_section_meta($section_id, '_use_calculated_dates', SB_Request::getInt('use_calculated_dates'));
			
			$msg = SB_Text::_('La secci&oacute;n fue actualizada correctamente.', 'content');
			$link = SB_Route::_('index.php?mod=content&view=section.edit&id='.$section_id);
		}
		sb_update_section_meta($section_id, '_btn_bg_color', SB_Request::getString('btn_bg_color', '#000'));
		sb_update_section_meta($section_id, '_btn_fg_color', SB_Request::getString('btn_fg_color', '#000'));
		sb_update_section_meta($section_id, '_use_button_instead', SB_Request::getInt('use_button_instead'));
		if( $banner = SB_Session::getVar('new_section_banner') )
		{
			sb_update_section_meta($section_id, '_banner', $banner);
		}
		if( $img = SB_Session::getVar('new_section_button_image') )
		{
			sb_update_section_meta($section_id, '_button_image', $img);
		}
		SB_Module::do_action('save_section', $section_id);
		SB_MessagesStack::AddMessage($msg, 'success');
		sb_redirect($link);
	}
	public function task_delete()
	{
		if( !sb_get_current_user()->can('manage_sections') || !sb_get_current_user()->can('delete_section') )
		{
			die(SBText::_('No tienes permisos suficientes para realizar esta accion.', 'content'));
		}
		$section_id = SB_Request::getInt('id');
		if( !$section_id )
		{
			SB_MessagesStack::AddMessage(SB_Text::_('Identificador de secci&oacute;n no valido.', 'content'), 'error');
			sb_redirect(SB_Route::_('index.php?mod=content&view=section.default'));
		}
		$section = new LT_Section($section_id);
		if( !$section->section_id )
		{
			SB_MessagesStack::AddMessage(SB_Text::_('Identificador de secci&oacute;n no existe.', 'content'), 'error');
			sb_redirect(SB_Route::_('index.php?mod=content&view=section.default'));
		}
		$dbh = SB_Factory::getDbh();
		$query = "DELETE FROM section_meta WHERE section_id = $section->section_id";
		$dbh->Query($query);
		$query = "DELETE FROM section WHERE section_id = $section->section_id LIMIT 1";
		$dbh->Query($query);
		$query = "DELETE FROM section2content WHERE section_id = $section->section_id";
		$dbh->Query($query);
		SB_MessagesStack::AddMessage(SB_Text::_('Secci&oacute;n borrada.', 'content'), 'success');
		sb_redirect(SB_Route::_('index.php?mod=content&view=section.default'));
	}
	public function task_upload_banner()
	{
		$id = SB_Request::getInt('id');
		sb_include('qqFileUploader.php', 'file');
		$uh = new qqFileUploader();
		$uh->allowedExtensions = array('jpg', 'jpeg', 'gif', 'png', 'bmp');
		// Specify max file size in bytes.
		//$uh->sizeLimit = 10 * 1024 * 1024; //10MB
		// Specify the input name set in the javascript.
		$uh->inputName = 'qqfile';
		// If you want to use resume feature for uploader, specify the folder to save parts.
		$uh->chunksFolder = 'chunks';
		$res = $uh->handleUpload(MOD_CONTENT_BANNERS_DIR);
		$file_path = MOD_CONTENT_BANNERS_DIR. SB_DS . $uh->getUploadName();
		/*
			sb_include('class.image.php');
			$img = new SB_Image($file_path);
			//if( $img->getWidth() > 150 || $img->getHeight() > 150)
			{
			$img->resizeImage(150, 150);
			$img->save($file_path);
			}
		*/
		$res['uploadName'] = $uh->getUploadName();
		$res['image_url'] = MOD_CONTENT_BANNERS_URL. '/' . $res['uploadName'];
		if( $id )
		{
			$banner = sb_get_section_meta($id, '_banner');
			if( $banner && file_exists(MOD_CONTENT_BANNERS_DIR . SB_DS . $banner) )
			{
				unlink(MOD_CONTENT_BANNERS_DIR . SB_DS . $banner);
			}
			sb_update_section_meta($id, '_banner', $res['uploadName']);
		}
		else
		{
			$banner = SB_Session::getVar('new_section_banner');
			if( $banner && file_exists(MOD_CONTENT_BANNERS_DIR . SB_DS . $banner) )
			{
				unlink($banner);
			}
			SB_Session::setVar('new_section_banner', $res['uploadName']);
		}
		die(json_encode($res));
	}
	public function task_remove_banner()
	{
		$id = SB_Request::getString('id');
	
		if( is_numeric($id) )
		{
			$banner_file = MOD_CONTENT_BANNERS_DIR . SB_DS . sb_get_section_meta($id, '_banner');
			file_exists($banner_file) && unlink($banner_file);
			sb_update_section_meta($id, '_banner', '');
		}
		elseif( $id == 'temp' )
		{
			$banner_file = MOD_CONTENT_BANNERS_DIR . SB_DS . SB_Session::getVar('new_section_banner');
			file_exists($banner_file) && unlink($banner_file);
			SB_Session::unsetVar('new_section_banner');
		}
		die();
	}
	public function task_upload_button_image()
	{
		$id = SB_Request::getInt('id');
		sb_include('qqFileUploader.php', 'file');
		$uh = new qqFileUploader();
		$uh->allowedExtensions = array('jpg', 'jpeg', 'gif', 'png', 'bmp');
		// Specify max file size in bytes.
		//$uh->sizeLimit = 10 * 1024 * 1024; //10MB
		// Specify the input name set in the javascript.
		$uh->inputName = 'qqfile';
		// If you want to use resume feature for uploader, specify the folder to save parts.
		$uh->chunksFolder = 'chunks';
		$res = $uh->handleUpload(MOD_CONTENT_BUTTONS_DIR);
		$file_path = MOD_CONTENT_BUTTONS_DIR . SB_DS . $uh->getUploadName();
		//##resize image
		sb_include('class.image.php');
		$img = new SB_Image($file_path);
		if( $img->getWidth() > 300 || $img->getHeight() > 200)
		{
			$img->resizeImage(300, 200);
			$img->save($file_path);
		}
		$res['uploadName'] = $uh->getUploadName();
		$res['image_url'] = MOD_CONTENT_BUTTONS_URL . '/' . $res['uploadName'];
		if( $id )
		{
			$img = sb_get_section_meta($id, '_button_image');
			if( $img && file_exists(MOD_CONTENT_BUTTONS_DIR . SB_DS . $img) )
			{
				unlink(MOD_CONTENT_BUTTONS_DIR . SB_DS . $img);
			}
			sb_update_section_meta($id, '_button_image', $res['uploadName']);
		}
		else
		{
			if( $img = SB_Session::getVar('new_section_button_image') )
			{
				unlink(MOD_CONTENT_BUTTONS_DIR . SB_DS . $img);
			}
			SB_Session::setVar('new_section_button_image', $res['uploadName']);
		}
		die(json_encode($res));
	}
	public function task_remove_button_image()
	{
		$id = SB_Request::getString('id');
	
		if( is_numeric($id) )
		{
			$file = MOD_CONTENT_BUTTONS_DIR . SB_DS . sb_get_section_meta($id, '_button_image');
			file_exists($file) && unlink($file);
			sb_update_section_meta($id, '_button_image', null);
		}
		elseif( $id == 'temp' )
		{
			$file = MOD_CONTENT_BUTTONS_DIR . SB_DS . SB_Session::getVar('new_section_button_image');
			file_exists($banner_file) && unlink($banner_file);
			SB_Session::unsetVar('new_section_button_image');
		}
		die();
	}
	public function task_change_order()
	{
		$id = SB_Request::getInt('id');
		$order = SB_Request::getInt('order');
		$dbh = SB_Factory::getDbh();
		$dbh->Update('section', array('show_order' => $order), array('section_id' => $id));
		SB_MessagesStack::AddMessage(SBText::_('Orden actualizado'), 'success');
		sb_redirect(SB_Route::_('index.php?mod=content&view=section.default'));
	}
}
