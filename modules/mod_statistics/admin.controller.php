<?php
class LT_AdminControllerStatistics extends SB_Controller
{
	public function task_default()
	{
		$this->document->SetTitle(SBText::_('Estadisticas', 'statistics'));
	}
	public function task_user_access()
	{
		$limit = 25;
		$page = SB_Request::getInt('page', 1);
		$order = SB_Request::getString('order', 'desc');
		$order_by = SB_Request::getString('order_by', 'username');
		$order_by = ($order_by == 'username') ? 'u.username' : $order_by;
		$user = sb_get_current_user();
		$dbh 	= SB_Factory::getDbh();
		$query 	= "SELECT u.*, (
								SELECT count(us.id) 
								FROM user_stats us 
								WHERE us.user_id = u.user_id
								AND type = 'authenticated'
								) AS auths
					FROM users u 
					WHERE username <> 'root' 
					ORDER BY $order_by $order";
		
		$total_rows = $dbh->Query($query);
		$total_pages = ceil($total_rows / $limit);
		$offset = $page == 1 ? 0 : ($page - 1) * $limit;
		$query .= "LIMIT $offset, $limit";
		
		$users = array();
		foreach($dbh->FetchResults() as $row)
		{
			$user = new SB_User();
			$user->SetDbData($row);
			$users[] = $user;
		}
		$base_link = 'index.php?mod=statistics&view=user_access';
		$order_fname_link = $order_lname_link = $base_link;
		$order_auths_link = $base_link;
		$order_fname_link .= '&order_by=first_name&order=' . (($order == 'desc') ? 'asc' : 'desc');
		$order_lname_link .= '&order_by=last_name&order=' . (($order == 'desc') ? 'asc' : 'desc');
		$order_auths_link .= '&order_by=auths&order=' . (($order == 'desc') ? 'asc' : 'desc');
		$new_order = ($order == 'desc') ? 'asc' : 'desc';
		sb_set_view_var('users', $users);
		sb_set_view_var('order_id_link', SB_Route::_('index.php?mod=statistics&view=user_access&order_by=user_id&order='.$new_order));
		sb_set_view_var('order_fname_link', $order_fname_link);
		sb_set_view_var('order_lname_link', $order_lname_link);
		sb_set_view_var('order_auths_link', $order_auths_link);
		sb_set_view_var('total_pages', $total_pages);
		$this->document->SetTitle(SBText::_('Estadisticas | Acceso por usuarios', 'statistics'));
	}
	public function task_section_access()
	{
		$user 		= sb_get_current_user();
		$limit 		= 25;
		$page 		= SB_Request::getInt('page', 1);
		$order 		= SB_Request::getString('order', 'desc');
		$order_by 	= SB_Request::getString('order_by', 'name');
		$order_by 	= ($order_by == 'name') ? 's.name' : $order_by;
		$dbh 		= SB_Factory::getDbh();
		$query 	= "SELECT s.*, (
						SELECT count(ss.id)
						FROM section_stats ss
						WHERE ss.section_id = s.section_id
						AND type = 'section_view'
						) AS views
						FROM section s
						ORDER BY $order_by $order";
		
		$total_rows = $dbh->Query($query);
		$total_pages = ceil($total_rows / $limit);
		$offset = $page == 1 ? 0 : ($page - 1) * $limit;
		$query .= "LIMIT $offset, $limit";
		
		$sections = array();
		foreach($dbh->FetchResults() as $row)
		{
			$section = new LT_Section();
			$section->SetDbData($row);
			$sections[] = $section;
		}
		$base_link = 'index.php?mod=statistics&view=section_access';
		$order_name_link = $base_link;
		$order_views_link = $base_link;
		$order_id_link = $base_link . '&order_by=section_id&order=' . (($order == 'desc') ? 'asc' : 'desc');
		$order_name_link .= '&order_by=name&order=' . (($order == 'desc') ? 'asc' : 'desc');
		$order_views_link .= '&order_by=views&order=' . (($order == 'desc') ? 'asc' : 'desc');
		sb_set_view_var('sections', $sections);
		sb_set_view_var('order_id_link', $order_id_link);
		sb_set_view_var('order_name_link', $order_name_link);
		sb_set_view_var('order_views_link', $order_views_link);
		sb_set_view_var('total_pages', $total_pages);
		$this->document->SetTitle(SBText::_('Estadisticas | Accesos por secciones', 'statistics'));
	}
	public function task_content_access()
	{
		$user 		= sb_get_current_user();
		$page 		= SB_Request::getInt('page', 1);
		$limit 		= SB_Request::getInt('limit', defined('ITEMS_PER_PAGE') ? ITEMS_PER_PAGE : 25);
		$order 		= SB_Request::getString('order', 'desc');
		$order_by 	= SB_Request::getString('order_by', 'title');
		
		$dbh 		= SB_Factory::getDbh();
		$views_query = "SELECT COUNT(cs.id)
						FROM content_stats cs
						WHERE cs.content_id = c.content_id
						AND type = 'content_view'";
		$query 	= "SELECT {columns}
						FROM content c";
		
		$dbh->Query(str_replace('{columns}', 'COUNT(content_id) as total_rows', $query));
		
		$total_rows	= $dbh->FetchRow()->total_rows;
		$total_pages = ceil($total_rows / $limit);
		$offset = $page == 1 ? 0 : ($page - 1) * $limit;
		$order_query = "ORDER BY $order_by $order";
		$limit_query = "LIMIT $offset, $limit";
		$dbh->Query(str_replace('{columns}', "c.*, ($views_query) AS views", $query) . " $order_query $limit_query");
		$articles 	= $dbh->FetchResults();
		$base_link 	= 'index.php?mod=statistics&view=content_access';
		$order_views_link = $base_link;
		$new_order	= ($order == 'desc') ? 'asc' : 'desc';
		$order_id_link	= SB_Route::_('index.php?mod=statistics&view=content_access&order_by=content_id&order=' . $new_order . '&page=' . $page);
		$order_name_link	= SB_Route::_('index.php?mod=statistics&view=content_access&order_by=title&order=' . $new_order . '&page=' . $page);
		$order_views_link 	= SB_Route::_('index.php?mod=statistics&view=content_access&order_by=views&order=' . $new_order . '&page=' . $page);
		sb_set_view_var('articles', $articles);
		sb_set_view_var('order_id_link', $order_id_link);
		sb_set_view_var('order_name_link', $order_name_link);
		sb_set_view_var('order_views_link', $order_views_link);
		sb_set_view_var('total_pages', $total_pages);
		sb_set_view_var('current_page', $page);
		$this->document->SetTitle(SBText::_('Estadisticas | Accesos por articulos', 'statistics'));
	}
	public function task_login_errors()
	{
		$user 		= sb_get_current_user();
		$limit 		= defined('ITEMS_PER_PAGE') ? ITEMS_PER_PAGE : 25;
		$page 		= SB_Request::getInt('page', 1);
		$order 		= SB_Request::getString('order', 'desc');
		$order_by 	= SB_Request::getString('order_by', 'username');
		$order_by 	= ($order_by == 'name') ? 's.name' : $order_by;
		$dbh 		= SB_Factory::getDbh();
		
		$query 	= "SELECT u.*, (
						SELECT count(us.id)
						FROM user_stats us
						WHERE us.user_id = u.user_id
						AND type = 'authenticate_error'
						) AS errors
					FROM users u
					ORDER BY $order_by $order";
		$total_rows = $dbh->Query($query);
		$total_pages = ceil($total_rows / $limit);
		$new_order = $order == 'asc' ? 'desc' : 'asc';
		$users = $dbh->FetchResults();
		
		sb_set_view_var('users', $users);
		sb_set_view_var('total_pages', $total_pages);
		sb_set_view_var('order_id_link', SB_Route::_('index.php?mod=statistics&view=login_errors&order_by=user_id&order='.$new_order));
		sb_set_view_var('order_fname_link', SB_Route::_('index.php?mod=statistics&view=login_errors&order_by=first_name&order='.$new_order));
		sb_set_view_var('order_lname_link', SB_Route::_('index.php?mod=statistics&view=login_errors&order_by=last_name&order='.$new_order));
		sb_set_view_var('order_auths_link', SB_Route::_('index.php?mod=statistics&view=login_errors&order_by=errors&order='.$new_order));
		$this->document->SetTitle(SBText::_('Estadisticas | Errores de login', 'statistics'));
	}
	public function task_connections_history()
	{
		$user 		= sb_get_current_user();
		$limit 		= 50;
		$page 		= SB_Request::getInt('page', 1);
		$order 		= SB_Request::getString('order', 'desc');
		$order_by 	= SB_Request::getString('order_by', 'creation_date');
		$dfrom		= SB_Request::getString('dfrom');
		$dto		= SB_Request::getString('dto');
		$ids		= SB_Request::getString('ids');
		
		$dbh 		= SB_Factory::getDbh();
		if( $page < 1 )
			$page = 1;
		
		$query = "SELECT {columns}
					from users u, user_stats us
					WHERE 1 = 1
					AND u.user_id = us.user_id
					AND us.type = 'authenticated' ";
		$query_string = '';
		if( $dfrom && $dto )
		{
			$fdfrom 	= sb_format_date(strtotime($dfrom), 'Y-m-d');
			$fdto	= sb_format_date(strtotime($dto), 'Y-m-d');
			$query .= "AND (DATE(us.creation_date) >= '$fdfrom' AND DATE(us.creation_date) <= '$fdto') ";
			$query_string .= "dfrom=$dfrom&dto=$dto";
		}
		if( !empty($ids) )
		{
			//$ids = explode(',', $ids);
			
			$query .= "AND u.user_id IN($ids) ";
			$query_string .= "&ids=$ids";
		}
		$dbh->Query(str_replace('{columns}', 'COUNT(u.user_id) as total_rows', $query));
		$total_rows 	= $dbh->FetchRow()->total_rows;
		$total_pages	= ceil($total_rows / $limit);
		$offset 		= ($page <= 1) ? 0 : ($page - 1) * $limit;
		$order_query 	= "ORDER BY $order_by $order";
		$limit_query 	= "LIMIT $offset, $limit";
		$columns 		= 'u.user_id, u.first_name,u.last_name,u.username, us.type, us.creation_date';
		
		$the_query		= str_replace('{columns}', $columns, "$query $order_query $limit_query");
		//print $the_query;
		$dbh->Query($the_query);
		$records = $dbh->FetchResults();
		$new_order = $order == 'asc' ? 'desc' : 'asc';
		sb_set_view_var('records', $records);
		sb_set_view_var('order_by', $order_by);
		sb_set_view_var('order', $order);
		sb_set_view_var('order_id_link', SB_Route::_('index.php?mod=statistics&view=connections_history&order_by=user_id&order='.$new_order . '&page='.$page . '&' . $query_string));
		sb_set_view_var('order_firstname_link', SB_Route::_('index.php?mod=statistics&view=connections_history&order_by=first_name&order='.$new_order . '&page='.$page . '&' . $query_string));
		sb_set_view_var('order_lastname_link', SB_Route::_('index.php?mod=statistics&view=connections_history&order_by=last_name&order='.$new_order . '&page='.$page . '&' . $query_string));
		sb_set_view_var('order_creation_date_link', SB_Route::_('index.php?mod=statistics&view=connections_history&order_by=creation_date&order='.$new_order . '&page='.$page . '&' . $query_string));
		sb_set_view_var('total_pages', $total_pages);
		sb_set_view_var('current_page', $page);
		$this->document->SetTitle(SBText::_('Estadisticas | Historial de Conexiones', 'statistics'));
	}
	public function task_history_content_access()
	{
		$user 		= sb_get_current_user();
		$limit 		= 50;
		$page 		= SB_Request::getInt('page', 1);
		$order 		= SB_Request::getString('order', 'desc');
		$order_by 	= SB_Request::getString('order_by', 'cs.creation_date');
		$dfrom		= SB_Request::getString('dfrom');
		$dto		= SB_Request::getString('dto');
		$ids		= SB_Request::getString('ids');
		
		$dbh 		= SB_Factory::getDbh();
		if( $page < 1 )
			$page = 1;
		$query = "SELECT {columns} ".
				"FROM content_stats cs, content c, users u ".
				"WHERE 1 = 1 " .
				"AND cs.content_id = c.content_id ".
				"AND cs.user_id = u.user_id ".
				"AND cs.type = 'content_view' ";
		$query_string = '';
		if( $dfrom && $dto )
		{
			$fdfrom = sb_format_date(strtotime($dfrom), 'Y-m-d');
			$fdto	= sb_format_date(strtotime($dto), 'Y-m-d');
			$query .= "AND (DATE(cs.creation_date) >= '$fdfrom' AND DATE(cs.creation_date) <= '$fdto') ";
			$query_string .= "dfrom=$dfrom&dto=$dto";
		}
		if( !empty($ids) )
		{
			$query .= "AND c.content_id IN($ids) ";
			$query_string .= "&ids=$ids";
		}
		
		$user_query = "SELECT u.username FROM users u WHERE u.user_id = cs.user_id";
		$dbh->Query(str_replace('{columns}', 'COUNT(c.content_id) as total_rows', $query));
		$total_rows 	= $dbh->FetchRow()->total_rows;
		$total_pages	= ceil($total_rows / $limit);
		$offset 		= ($page <= 1) ? 0 : ($page - 1) * $limit;
		$order_query 	= "ORDER BY $order_by $order";
		$limit_query 	= "LIMIT $offset, $limit";
		$columns 		= 'c.*, u.username, cs.type, cs.creation_date AS access_date';
		$dbh->Query(str_replace('{columns}', $columns, "$query $order_query $limit_query"));
		$records		= $dbh->FetchResults();
		$new_order 		= ($order == 'asc') ? 'desc' : 'asc';
		
		sb_set_view_var('records', $records);
		sb_set_view_var('order_by', $order_by);
		sb_set_view_var('order', $order);
		sb_set_view_var('total_pages', $total_pages);
		sb_set_view_var('current_page', $page);
		sb_set_view_var('order_id_link', SB_Route::_('index.php?mod=statistics&view=history_content_access&order_by=id&order='.$new_order . '&page='.$page . '&' . $query_string));
		sb_set_view_var('order_title_link', SB_Route::_('index.php?mod=statistics&view=history_content_access&order_by=title&order='.$new_order . '&page='.$page . '&' . $query_string));
		sb_set_view_var('order_username_link', SB_Route::_('index.php?mod=statistics&view=history_content_access&order_by=u.username&order='.$new_order . '&page='.$page . '&' . $query_string));
		sb_set_view_var('order_creation_date_link', SB_Route::_('index.php?mod=statistics&view=history_content_access&order_by=cs.creation_date&order='.$new_order . '&page='.$page . '&' . $query_string));
		$this->document->SetTitle(SBText::_('Estadisticas | Historial Accesos a Articulos', 'statistics'));
	}
	public function task_history_section_access()
	{
		$user 		= sb_get_current_user();
		$limit 		= 50;
		$page 		= SB_Request::getInt('page', 1);
		$order 		= SB_Request::getString('order', 'desc');
		$order_by 	= SB_Request::getString('order_by', 'ss.creation_date');
		$dfrom		= SB_Request::getString('dfrom');
		$dto		= SB_Request::getString('dto');
		$ids		= SB_Request::getString('ids');
	
		$dbh 		= SB_Factory::getDbh();
		if( $page < 1 )
			$page = 1;
		
		$query = "SELECT {columns} ".
					"FROM section_stats ss, section s, users u ".
					"WHERE 1 = 1 " .
					"AND ss.section_id = s.section_id ".
					"AND ss.user_id = u.user_id ".
					"AND type = 'section_view' ";
		$query_string = '';
		if( $dfrom && $dto )
		{
			$fdfrom = sb_format_date(strtotime($dfrom), 'Y-m-d');
			$fdto	= sb_format_date(strtotime($dto), 'Y-m-d');
			$query .= "AND (DATE(ss.creation_date) >= '$fdfrom' AND DATE(ss.creation_date) <= '$fdto') ";
			$query_string .= "dfrom=$dfrom&dto=$dto";
		}
		if( !empty($ids) )
		{
			$query .= "AND s.section_id IN($ids) ";
			$query_string .= "&ids=$ids";
		}
	
		$dbh->Query(str_replace('{columns}', 'COUNT(s.section_id) as total_rows', $query));
		$total_rows 	= $dbh->FetchRow()->total_rows;
		$total_pages	= ceil($total_rows / $limit);
		$offset 		= ($page <= 1) ? 0 : ($page - 1) * $limit;
		$order_query 	= "ORDER BY $order_by $order";
		$limit_query 	= "LIMIT $offset, $limit";
		$columns 		= 's.*, u.username, ss.type, ss.creation_date AS access_date';
		$dbh->Query(str_replace('{columns}', $columns, "$query $order_query $limit_query"));
		$records		= $dbh->FetchResults();
		$new_order 		= ($order == 'asc') ? 'desc' : 'asc';
	
		sb_set_view_var('records', $records);
		sb_set_view_var('order_by', $order_by);
		sb_set_view_var('order', $order);
		sb_set_view_var('total_pages', $total_pages);
		sb_set_view_var('current_page', $page);
		sb_set_view_var('order_id_link', SB_Route::_('index.php?mod=statistics&view=history_section_access&order_by=id&order='.$new_order . '&page='.$page . '&' . $query_string));
		sb_set_view_var('order_title_link', SB_Route::_('index.php?mod=statistics&view=history_section_access&order_by=name&order='.$new_order . '&page='.$page . '&' . $query_string));
		sb_set_view_var('order_username_link', SB_Route::_('index.php?mod=statistics&view=history_section_access&order_by=u.username&order='.$new_order . '&page='.$page . '&' . $query_string));
		sb_set_view_var('order_creation_date_link', SB_Route::_('index.php?mod=statistics&view=history_section_access&order_by=ss.creation_date&order='.$new_order . '&page='.$page . '&' . $query_string));
		$this->document->SetTitle(SBText::_('Estadisticas | Historial Accesos a Secciones', 'statistics'));
	}
	public function task_graph_user_connections()
	{
		$filter = SB_Request::getString('filter', 'last_week');
		$chart_title = '';
		$queries = array();
		for($i = 1; $i <= 24; $i++)
		{
			$h = $i;
			if( $i == 24 )
				$h = '0';
			
			
			$q = "(SELECT COUNT(id) ".
							"FROM user_stats " . 
							"WHERE type = 'authenticated' ";
			if( $filter == 'last_week' )
			{
				$q .= "AND DATE(creation_date) >= (CURRENT_DATE() - INTERVAL (DAYOFWEEK(CURRENT_DATE()) - 2) + (7 * 1) DAY ".
						"AND DATE(creation_date) <= (CURRENT_DATE() - INTERVAL (DAYOFWEEK(CURRENT_DATE()) - 1) DAY) ) ";
			}
			elseif( $filter == 'last_month' )
			{
				$fdate = mktime(null, null, null, date('m') - 1, 1, date('Y'));
				//$q .= "AND DATE(creation_date) >= '$fdate' ".
				$q .= "AND DATE(creation_time) >= '$fdate' ".
						"AND DATE(creation_date) <= LAST_DAY(CURRENT_DATE() - INTERVAL 1 MONTH) ";
			}
			elseif( $filter == 'last_3months' )
			{
				$fdate = mktime(null, null, null, date('m') - 3, 1, date('Y'));
				$q .= "AND DATE(creation_time) >= '$fdate' ".
						"AND DATE(creation_date) <= LAST_DAY(CURRENT_DATE() - INTERVAL 1 MONTH)";
			}
			elseif( $filter == 'last_6months' )
			{
				$fdate = mktime(null, null, null, date('m') - 6, 1, date('Y'));
				$q .= "AND DATE(creation_time) >= '$fdate' ".
						"AND DATE(creation_date) <= LAST_DAY(CURRENT_DATE() - INTERVAL 1 MONTH) ";
			}
			$q .= "AND HOUR(creation_date) = $h) AS H$i";
			$queries[] = $q;
		}
		$dbh = SB_Factory::getDbh();
		if( $filter == 'last_week' )
		{
			$query = "SELECT (CURRENT_DATE() - INTERVAL (DAYOFWEEK(CURRENT_DATE()) - 2) + (7 * 1) DAY) AS dfrom,".
						"(CURRENT_DATE() - INTERVAL (DAYOFWEEK(CURRENT_DATE()) - 1) DAY) AS dto ".
						"";
			$dbh->Query($query);
			$res = $dbh->FetchRow();
			$chart_title = sprintf(SBText::_("Desde: %s Hasta: %s", 'statistics'), sb_format_date($res->dfrom), sb_format_date($res->dto));
		}
		elseif( $filter == 'last_month' )
		{
			$fdate = mktime(null, null, null, date('m') - 1, 1, date('Y'));
			$query = "SELECT '$fdate' AS dfrom, ".
						"LAST_DAY(CURRENT_DATE() - INTERVAL 1 MONTH) AS dto";
			$dbh->Query($query);
			$res = $dbh->FetchRow();
			$chart_title = sprintf(SBText::_("Desde: %s Hasta: %s", 'statistics'), sb_format_date($res->dfrom), sb_format_date($res->dto));
		}
		elseif( $filter == 'last_3months' )
		{
			$fdate = mktime(null, null, null, date('m') - 3, 1, date('Y'));
			$query = "SELECT '$fdate' AS dfrom, ".
					"LAST_DAY(CURRENT_DATE() - INTERVAL 1 MONTH) AS dto";
			$dbh->Query($query);
			$res = $dbh->FetchRow();
			$chart_title = sprintf(SBText::_("Desde: %s Hasta: %s", 'statistics'), sb_format_date($res->dfrom), sb_format_date($res->dto));
		}
		elseif( $filter == 'last_6months' )
		{
			$fdate = mktime(null, null, null, date('m') - 6, 1, date('Y'));
			$query = "SELECT '$fdate' AS dfrom, ".
					"LAST_DAY(CURRENT_DATE() - INTERVAL 1 MONTH) AS dto";
			$dbh->Query($query);
			$res = $dbh->FetchRow();
			$chart_title = sprintf(SBText::_("Desde: %s Hasta: %s", 'statistics'), sb_format_date($res->dfrom), sb_format_date($res->dto));
		}
		$query = "SELECT " . implode(',', $queries);
		//print '<!--'; var_dump($query);print '-->';
		$dbh->Query($query);
		$res = $dbh->FetchRow();
		
		$the_data = array(
				'labels' 	=> array_keys((array)$res),
				'datasets'	=> array(
						array(
								'label' 			=> 'User connections dataset',
								//'fillColor' 		=> "rgba(220,220,220,0.5)",
								'fillColor'			=> 'rgba(151,187,205,0.5)',
								'strokeColor' 		=> "rgba(220,220,220,0.8)",
								'highlightFill'		=> "rgba(220,220,220,0.75)",
								'highlightStroke'	=> "rgba(220,220,220,1)",
								'data'				=> array_values((array)$res)
						)
				) 
		);
		sb_set_view_var('data', $the_data);
		sb_set_view_var('chart_title', $chart_title);
		sb_add_script(BASEURL . '/js/Chart.js', 'js-chart', 0, true);
	}
	public function task_graph_user_connections_daily()
	{
		$filter = SB_Request::getString('filter', 'last_month');
		$chart_title = '';
		$queries = array();
		$dbh = SB_Factory::getDbh();
		if( $filter == 'last_month' )
		{
			for($i = 1; $i <= 31; $i++)
			{
				$day = $i;
				$fdate = mktime(null, null, null, date('m') - 1, $day, date('Y'));
				$fdate = date('Y-m-d', $fdate);
				$q = "(SELECT COUNT(id) ".
						"FROM user_stats " .
						"WHERE type = 'authenticated' ".
						"AND DATE(creation_date) = '$fdate') AS D$day";
				$queries[] = $q;
			}
			$fdate = mktime(null, null, null, date('m') - 1, 1, date('Y'));
			$query = "SELECT '$fdate' AS dfrom, ".
						"LAST_DAY(CURRENT_DATE() - INTERVAL 1 MONTH) AS dto";
			$dbh->Query($query);
			$res = $dbh->FetchRow();
			$chart_title = sprintf(SBText::_("Desde: %s Hasta: %s", 'statistics'), sb_format_date($res->dfrom), sb_format_date($res->dto));
		}
		elseif( $filter == 'last_3months' )
		{
			for($day = 1; $day <= 31; $day++)
			{
				$q = "(SELECT COUNT(id) ".
						"FROM user_stats " .
						"WHERE type = 'authenticated' ".
						"AND (%s)) AS D$day";
				$date_query = array();
				for($month = 1; $month <= 3; $month++)
				{
					$fdate = mktime(null, null, null, date('m') - $month, $day, date('Y'));
					$fdate = date('Y-m-d', $fdate);
					$date_query[] = "DATE(creation_date) = '$fdate'";
				}
				$q = sprintf($q, implode(' OR ', $date_query));
				$queries[] = $q;
			}
			//print '<!-- ';print_r($queries);print '-->';
			$fdate = mktime(null, null, null, date('m') - 3, 1, date('Y'));
			$query = "SELECT '$fdate' AS dfrom, LAST_DAY(CURRENT_DATE() - INTERVAL 1 MONTH) AS dto";
			$dbh->Query($query);
			$res = $dbh->FetchRow();
			$chart_title = sprintf(SBText::_("Desde: %s Hasta: %s", 'statistics'), sb_format_date($res->dfrom), sb_format_date($res->dto));
		}
		elseif( $filter == 'last_6months' )
		{
			for($day = 1; $day <= 31; $day++)
			{
				$q = "(SELECT COUNT(id) ".
						"FROM user_stats " .
						"WHERE type = 'authenticated' ".
						"AND (%s)) AS D$day";
				$date_query = array();
				for($month = 1; $month <= 6; $month++)
				{
					$fdate = mktime(null, null, null, date('m') - $month, $day, date('Y'));
					$fdate = date('Y-m-d', $fdate);
					$date_query[] = "DATE(creation_date) = '$fdate'";
				}
				$q = sprintf($q, implode(' OR ', $date_query));
				$queries[] = $q;
			}
			$fdate = mktime(null, null, null, date('m') - 6, 1, date('Y'));
			$query = "SELECT '$fdate' AS dfrom, LAST_DAY(CURRENT_DATE() - INTERVAL 1 MONTH) AS dto";
			$dbh->Query($query);
			$res = $dbh->FetchRow();
			$chart_title = sprintf(SBText::_("Desde: %s Hasta: %s", 'statistics'), sb_format_date($res->dfrom), sb_format_date($res->dto));
		}
		
		$query = "SELECT " . implode(',', $queries);
		$dbh->Query($query);
		$res = $dbh->FetchRow();
		
		$the_data = array(
				'labels' 	=> array_keys((array)$res),
				'datasets'	=> array(
						array(
								'label' 			=> 'User connections dataset',
								//'fillColor' 		=> "rgba(220,220,220,0.5)",
								'fillColor'			=> 'rgba(151,187,205,0.5)',
								'strokeColor' 		=> "rgba(220,220,220,0.8)",
								'highlightFill'		=> "rgba(220,220,220,0.75)",
								'highlightStroke'	=> "rgba(220,220,220,1)",
								'data'				=> array_values((array)$res)
						)
				)
		);
		sb_set_view_var('data', $the_data);
		sb_set_view_var('chart_title', $chart_title);
		sb_add_script(BASEURL . '/js/Chart.js', 'js-chart');
	}
}