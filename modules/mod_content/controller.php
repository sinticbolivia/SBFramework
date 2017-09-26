<?php
class LT_ControllerContent extends SB_Controller
{
	public function task_default()
	{
		$keyword = SB_Request::getString('keyword');
		sb_include_module_helper('content');
		if( $keyword )
		{
			$this->task_search();
		}
		else
		{
			$args = array();
			$args = SB_Module::do_action('default_content_query_args', $args);
			$data = LT_HelperContent::GetArticles($args);
			sb_set_view_var('articles', $data['articles']);
			if( lt_is_frontpage() )
			{
				$title = __('Home', 'content');
				$this->document->SetTitle($title);
			}
		}
		
	}
	public function task_blog()
	{
		sb_include_module_helper('content');
		$dbh = SB_Factory::getDbh();
		$args = array(
				'type'	=> 'post'
		);
		$args = SB_Module::do_action('default_content_query_args', $args);
		$data = LT_HelperContent::GetArticles($args);
		sb_set_view_var('posts', $data['articles']);
	}
	public function task_article()
	{
		$id 	= 	SB_Request::getInt('id');
		$slug 	= SB_Request::getString('slug');
		
		if( !$id && !$slug )
		{
			sb_set_view('article-not-found');
			return false;
		}
		sb_include_module_helper('content');
		$article = new LT_Article($id ? $id : $slug);
		
		if( !$article->content_id )
		{
			SB_Module::do_action('before_article_not_found');
			sb_set_view('article-not-found');
			return false;
		}
		if( !$article->IsVisible() )
		{
			SB_Module::do_action('before_article_not_found');
			sb_set_view('article-not-found');
			return false;
		}
		//var_dump($article->type);
		//##check for single template
		if( $article->type != 'page' && file_exists(TEMPLATE_DIR . SB_DS . 'single-'.$article->type.'.php') )
		{
			$this->document->templateFile = 'single-'.$article->type.'.php';
		}
		elseif( file_exists(TEMPLATE_DIR . SB_DS . 'page.php') )
		{
			$this->document->templateFile = 'page.php';
		}
		
		//##check if page has assigned a template file
		if( $article->_template && strstr($article->_template, '.php') )
		{
			$this->document->templateFile = $article->_template;
		}
		$this->document->AddBodyClass($article->type . '-' . $article->slug);
		$this->document->AddBodyClass($this->document->templateFile);
		sb_set_view_var('article', $article);
		$this->document->SetTitle($article->title);
		SB_Module::do_action('before_show_content', $article);
	}
	public function task_section()
	{
		$id 	= SB_Request::getInt('id');
		$slug	= SB_Request::getString('slug');
		if( !$id && !$slug )
		{
			sb_set_view('article-not-found');
			return false;
		}
		sb_include_module_helper('content');
		$section = new LT_Section($id ? $id : $slug);
		if( !$section->section_id )
		{
			sb_set_view('article-not-found');
			return false;
		}
		if( !$section->IsVisible() )
		{
			sb_set_view('article-not-found');
			return false;
		}
		sb_set_view_var('section', $section);
		sb_set_view_var('articles', $section->GetArticles());
		SB_Module::do_action('before_show_section', $section);
		$this->document->SetTitle($section->name);
	}
	public function task_category()
	{
		$id 	= SB_Request::getInt('id');
		$slug	= SB_Request::getString('slug');
		if( !$id && !$slug )
		{
			sb_set_view('article-not-found');
			return false;
		}
		sb_include_module_helper('content');
		$cat = new LT_Category($id ? $id : $slug);
		if( !$cat->category_id )
		{
			sb_set_view('article-not-found');
			return false;
		}
		
		sb_set_view_var('category', $cat);
		sb_set_view_var('posts', $cat->GetPosts());
		SB_Module::do_action_ref('before_show_category', $cat);
		$this->document->SetTitle($cat->name);
	}
	public function task_search()
	{
		$page		= SB_Request::getInt('page', 1);
		$keyword	= SB_Request::getString('keyword');
		if( $page <= 0 )
			$page = 1;
			
		$args = array(
			'type' 		=> null,
			'keyword'	=> $keyword,
			'page'		=> $page
		);
		$res = LT_HelperContent::GetArticles($args);
		$this->document->templateFile = 'tpl-search-results.php';
		$this->SetView('search-results');
		$title = sprintf(__('Search results for "%s"', 'content'), $keyword);
		sb_set_view_var('items', $res['articles']);
		sb_set_view_var('total_rows', $res['total_rows']);
		sb_set_view_var('total_pages', $res['total_pages']);
		sb_set_view_var('title', $title);
		sb_set_view_var('current_page', $page);
		$this->document->SetTitle($title);
		SB_Module::do_action_ref('content_before_show_search_results', $this);
	}
}