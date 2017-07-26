<?php
class LT_AdminControllerContentPosts extends SB_Controller
{
	public function task_default()
	{
		$page		= SB_Request::getInt('page', 1);
		$limit		= SB_Request::getInt('limit', defined('ITEMS_PER_PAGE') ? ITEMS_PER_PAGE : 25);
		
		$columns = "c.*, CONCAT(u.first_name, ' ', u.last_name) AS author";
		$where = "WHERE type = 'post'";
		$query = "SELECT {columns} FROM content c ";
		$order = "ORDER BY creation_date DESC";
		$left_join = "LEFT JOIN users u ON c.author_id = u.user_id ";
		
		$total_rows = $this->dbh->GetVar(str_replace('{columns}', 'COUNT(content_id)', "$query $where"));
		//##calculate total pages
		$total_pages = ceil($total_rows / $limit);
		//##get items
		$contents = $this->dbh->FetchResults(str_replace('{columns}', $columns, "$query $left_join $where $order"));
		sb_set_view_var('contents', $contents);
		sb_set_view_var('total_pages', $total_pages);
		sb_set_view_var('current_page', $page);
	}
}