<?php
class LT_AdminControllerMenu extends SB_Controller
{
	public function task_default()
	{
		if( SB_Request::getInt('reset') )
		{
			sb_update_parameter('menus', array());
		}
		$menus = (array)sb_get_parameter('menus', array());
		sb_set_view_var('menus', $menus);
		
		$title = __('Content Menus', 'menu');
		
		$this->document->SetTitle($title);
	}
	public function task_new()
	{
		$title = __('New Content Menu', 'menu');
		$this->document->SetTitle($title);
		sb_set_view_var('title', $title);
	}
	public function task_edit()
	{
		$key 	= SB_Request::getString('key');
		$menus = (array)sb_get_parameter('menus', array());
		if( !isset($menus[$key]) )
		{
			SB_MessagesStack::AddMessage(__('The menu does not exists', 'menu'), 'error');
			sb_redirect('index.php?mod=menu');
		}
		$menu = $menus[$key];
		sb_set_view('new');
		$title = __('Edit Content Menu', 'menu');
		$this->document->SetTitle($title);
		sb_set_view_var('title', $title);
		sb_set_view_var('menu', $menu);
		$query = "SELECT * FROM content WHERE type = 'page' OR type = '' OR type IS NULL ORDER BY title ASC";
		sb_set_view_var('pages', $this->dbh->FetchResults($query));
		$query = "SELECT * FROM section ORDER BY name ASC";
		$this->_sections = $this->dbh->FetchResults($query);
		$this->_posts = $this->dbh->FetchResults("SELECT * FROM content WHERE type = 'post' AND status = 'publish' ORDER BY title ASC");
		$this->_categories = $this->dbh->FetchResults("SELECT * FROM categories ORDER BY name ASC");
		//print_r($menu);
	}
	public function task_save()
	{
		$key 	= SB_Request::getString('key');
		$lang	= SB_Request::getString('lang', 'en_US');
		$name	= SB_Request::getString('name');
		$menus = (array)sb_get_parameter('menus', array());
		$link = SB_Route::_('index.php?mod=menu');
		if( !$key )
		{
			$key	= sb_build_slug($name) . '_' . $lang;
			$menus[$key] = array(
					'name'		=> $name,
					'lang'		=> $lang,
					'key'		=> $key,
					'items'		=> array()
			);
		}
		else
		{
			$menus[$key]->name = $name;
			$menus[$key]->lang = $lang;
			$menu_items = (array)SB_Request::getVar('menu_items', array());
			//print_r($menu_items);die();
			$menus[$key]->items = $menu_items;
			$link = SB_Route::_('index.php?mod=menu&view=edit&key='.$key);
		}
		sb_update_parameter('menus', $menus);
		SB_MessagesStack::AddMessage(__('The new menu has been created', 'menu'), 'success');
		sb_redirect($link);
	}
	public function task_delete()
	{
		$key 	= SB_Request::getString('key');
		$menus = (array)sb_get_parameter('menus', array());
		if( isset($menus[$key]) )
			unset($menus[$key]);
		sb_update_parameter('menus', $menus);
		SB_MessagesStack::AddMessage(__('The menu has been deleted', 'menu'), 'success');
		sb_redirect('index.php?mod=menu');
	}
}