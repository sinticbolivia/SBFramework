<?php
class LT_AdminControllerContentCategories extends SB_Controller
{
	public function task_default()
	{
		if( !sb_get_current_user()->can('manage_post_categories') )
		{
			die('You dont have enough permissions');
		}
		$page = SB_Request::getInt('page', 1);
		$order_by = SB_Request::getString('order_by', 'creation_date');
		$order 		= SB_Request::getString('order', 'desc');
		$limit		= SB_Request::getInt('limit', defined('ITEMS_PER_PAGE') ? ITEMS_PER_PAGE : 25);
		if( $order_by == 'creation_date' )
		{
			$order_by = 'c.creation_date';
		}
		$dbh = SB_Factory::getDbh();
		$query 			= "SELECT {columns} ".
							"FROM categories c " . 
							"WHERE 1 = 1 ";
		$order_query 	= "ORDER BY $order_by $order";
		$columns 		= "*";
		$dbh->Query(str_replace('{columns}', 'COUNT(*) AS total_rows', $query));
		$total_rows 	= $dbh->FetchRow()->total_rows;
		$total_pages	= ceil($total_rows / $limit);
		$offset			= ($page <= 1) ? 0 : ($page - 1) * $limit;
		$limit_query 	= "LIMIT $offset, $limit";
		$query = str_replace('{columns}', $columns, $query) . " $order_query $limit_query";
		$dbh->Query($query);
		$categories = array();
		foreach($dbh->FetchResults() as $row)
		{
			$s = new LT_Category();
			$s->SetDbData($row);
			$categories[] = $s;
		}
		$new_order = $order == 'desc' ? 'asc' : 'desc';
		sb_set_view_var('categories', $categories);
		sb_set_view_var('id_order_link', SB_Route::_('index.php?mod=content&view=categories.default&order_by=section_id&order='.$new_order));
		sb_set_view_var('name_order_link', SB_Route::_('index.php?mod=content&view=categories.default&order_by=name&order='.$new_order));
		sb_set_view_var('date_order_link', SB_Route::_('index.php?mod=content&view=categories.default&order_by=creation_date&order='.$new_order));
		sb_set_view_var('publishdate_order_link', SB_Route::_('index.php?mod=content&view=categories.default&order_by=publish_date&order='.$new_order));
		sb_set_view_var('enddate_order_link', SB_Route::_('index.php?mod=content&view=categories.default&order_by=end_date&order='.$new_order));
		sb_set_view_var('order_link', SB_Route::_('index.php?mod=content&view=categories.default&order_by=show_order&order='.$new_order));
		sb_set_view_var('total_pages', $total_pages);
		sb_set_view_var('current_page', $page);
	}
	public function task_new()
	{
		if( !sb_get_current_user()->can('create_post_category') )
		{
			lt_die(__('You dont have enough permissions', 'content'));
		}
		sb_include_module_helper('content');
		//sb_add_script($data['path'] . '/js/tinymce/tinymce.min.js', 'tinymce');
		sb_add_script(BASEURL . '/js/fineuploader/all.fine-uploader.min.js', 'fineuploader');
		$title = __('New Category', 'content');
		sb_set_view_var('title', $title);
		sb_set_view_var('image_url', null);
		//sb_set_view_var('remove_banner_link', SB_Route::_('index.php?mod=content&task=section.remove_banner&id=temp'));
		//sb_set_view_var('upload_endpoint', SB_Route::_('index.php?mod=content&task=section.upload_banner'));
		$this->document->SetTitle($title);
	}
	public function task_edit()
	{
		if( !sb_get_current_user()->can('edit_post_category') )
		{
			lt_die(__('You dont have enough permissions', 'content'));
		}
		$id = SB_Request::getInt('id');
		if( !$id )
		{
			SB_MessagesStack::AddMessage(__('The category identifier is invalid', 'content'), 'info');
			sb_redirect(SB_Route::_('index.php?mod=content&view=categories.default'));	
		}
		$query = "SELECT * FROM categories WHERE category_id = $id LIMIT 1";
		if( !$this->dbh->Query($query) )
		{
			SB_MessagesStack::AddMessage(__('The category does not exists', 'content'), 'error');
			sb_redirect(SB_Route::_('index.php?mod=content&view=categories.default'));
		}
		$c = new LT_Category();
		$c->SetDbData($this->dbh->FetchRow());
		sb_set_view('categories.new');
		$title = __('Edit Category', 'content');
		sb_include_module_helper('content');
		//sb_add_script(BASEURL . '/js/tinymce/tinymce.min.js', 'tinymce');
		sb_add_script(BASEURL . '/js/fineuploader/all.fine-uploader.min.js', 'fineuploader');
		sb_set_view_var('category', $c);
		sb_set_view_var('title', $title);
		$this->document->SetTitle($title);
	}
	public function task_save()
	{
		$category_id = SB_Request::getInt('category_id');
		$name 		= SB_Request::getString('category_name');
		$desc 		= SB_Request::getString('description');
		$parent_id	= SB_Request::getInt('parent_id', 0);
		$status		= SB_Request::getString('status', 'publish');
		$lang		= SB_Request::getString('lang', LANGUAGE);
		if( empty($name) )
		{
			SB_MessagesStack::AddMessage(SB_Text::_('You need to enter a category name', 'content'), 'error');
			if( $section_id )
				$this->task_edit();
			else 
				$this->task_new();
			return false;
		}
		if( !$category_id && !sb_get_current_user()->can('create_category') )
		{
			lt_die(__('You dont have enough permissions to create a section', 'content'));
		}
		if( $category_id && !sb_get_current_user()->can('edit_category') )
		{
			lt_die(__('You dont have enough permissions to edit a section', 'content'));
		}
		if( $parent_id < 0 )
			$parent_id = 0;
		$cdate = date('Y-m-d H:i;s');
		$data = array(
				'name'						=> $name,
				'parent_id'					=> $parent_id,
				'description'				=> $desc,
				'status'					=> $status,
				'lang_code'					=> $lang,
				'last_modification_date'	=> $cdate
		);
		$msg = $link = null;
		if( !$category_id )
		{
			$data['slug']			= sb_build_slug($name);
			$data['creation_date'] = $cdate;
			$section_id = $this->dbh->Insert('categories', $data);
			/*
			//##add section meta
			sb_add_section_meta($section_id, '_publish_date', $publish_date);
			sb_add_section_meta($section_id, '_end_date', $end_date);
			sb_add_section_meta($section_id, '_calculated_date', $calculated_date);
			sb_add_section_meta($section_id, '_calculated_end_date', $calculated_end_date);
			sb_add_section_meta($section_id, '_use_calculated_dates', SB_Request::getInt('use_calculated_dates'));
			*/
			$msg = __('The category has been created.', 'content');
			$link = SB_Route::_('index.php?mod=content&view=categories.default');
		}
		else 
		{
			$category = new LT_Category($category_id);
			if( empty($category->slug) )
			{
				$data['slug']			= sb_build_slug($name);
			}
			$this->dbh->Update('categories', $data, array('category_id' => $category_id));
			$msg = __('The category has been updated.', 'content');
			$link = SB_Route::_('index.php?mod=content&view=categories.edit&id='.$category_id);
		}
		SB_Module::do_action('save_category', $category_id, $data);
		SB_MessagesStack::AddMessage($msg, 'success');
		sb_redirect($link);
	}
	public function task_delete()
	{
		if( !sb_get_current_user()->can('manage_post_categories') || !sb_get_current_user()->can('delete_post_category') )
		{
			lt_die(__('You cant perform this action.', 'content'));
		}
		$id = SB_Request::getInt('id');
		if( !$id )
		{
			SB_MessagesStack::AddMessage(SB_Text::_('Category identifier is invalid.', 'content'), 'error');
			sb_redirect(SB_Route::_('index.php?mod=content&view=categories.default'));
		}
		$cat = new LT_Category($id);
		if( !$cat->category_id )
		{
			SB_MessagesStack::AddMessage(__('The category does not exists.', 'content'), 'error');
			sb_redirect(SB_Route::_('index.php?mod=content&view=categories.default'));
		}
		$query = "DELETE FROM categories WHERE category_id = $id LIMIT 1";
		$dbh->Query($query);
		$query = "DELETE FROM category2content WHERE category_id = $id";
		$dbh->Query($query);
		SB_MessagesStack::AddMessage(__('The category has been deleted.', 'content'), 'success');
		sb_redirect(SB_Route::_('index.php?mod=content&view=categories.default'));
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
