<?php
class LT_Statistics
{
	public function __construct()
	{
		$this->AddActions();
		SB_Language::loadLanguage(LANGUAGE, 'statistics', dirname(__FILE__) . SB_DS . 'locale');
	}
	protected function AddActions()
	{
		SB_Module::add_action('admin_menu', array($this, 'action_admin_menu'));
		SB_Module::add_action('authenticate_error', array($this, 'action_authenticate_error'));
		SB_Module::add_action('authenticated', array($this, 'action_authenticated'));
		SB_Module::add_action('before_show_section', array($this, 'action_before_show_section'));
		SB_Module::add_action('before_show_content', array($this, 'action_before_show_content'));
	}
	public function action_admin_menu()
	{
		SB_Menu::addMenuChild('menu-management', 
								'<span class="glyphicon glyphicon-stats" aria-hidden="true"></span>'.SB_Text::_('Estadisticas', 'statistics'), 
								SB_Route::_('index.php?mod=statistics&view=section_access'), 'menu-statistics', 'manage_statistics');
		SB_Menu::addMenuChild('menu-statistics', SBText::_('Accesos Contenidos'), SB_Route::_('index.php?mod=statistics&view=section_access'),
								'menu-stats-contents', 'manage_statistics');
		SB_Menu::addMenuChild('menu-statistics', SBText::_('Conexiones'), SB_Route::_('index.php?mod=statistics&view=user_access'), 
								'menu-stats-connections', 'manage_statistics');
		SB_Menu::addMenuChild('menu-statistics', SBText::_('Graficos'), SB_Route::_('index.php?mod=statistics&view=graph_user_connections'),
								'menu-stats-graphs', 'manage_statistics');
		SB_Menu::addMenuChild('menu-stats-graphs', SBText::_('Graficos por hora'), SB_Route::_('index.php?mod=statistics&view=graph_user_connections'),
								'menu-stats-graphs-hours', 'manage_statistics');
		SB_Menu::addMenuChild('menu-stats-graphs', SBText::_('Graficos por dia'), SB_Route::_('index.php?mod=statistics&view=graph_user_connections_daily'),
				'menu-stats-graphs-days', 'manage_statistics');
	}
	public function action_authenticate_error($user_db_row, $username, $pwd)
	{
		$dbh = SB_Factory::getDbh();
		$query = "SELECT * FROM users WHERE username = '$username' LIMIT 1";
		//##check if user exists
		if( !$dbh->Query($query) )
		{
			return false;
		}
		
		$user_db_row = $dbh->FetchRow(); 
		$data = array(
				'user_id'		=> $user_db_row->user_id,
				'type'			=> 'authenticate_error',
				'data'			=> json_encode(array(
						'server_data'	=> $_SERVER,
						'user_data'		=> array('username' => $username, 'pwd' => $pwd)
				)),
				'creation_time'	=> time(),
				'creation_date'	=> date('Y-m-d H:i:s')
		);
		$dbh->Insert('user_stats', $data);
	}
	public function action_authenticated($user_db_row, $username, $pwd)
	{
		$dbh = SB_Factory::getDbh();
		$data = array(
				'user_id'		=> $user_db_row->user_id,
				'type'			=> 'authenticated',
				'data'			=> json_encode(array(
						'server_data'	=> $_SERVER,
						'user_data'		=> array('username' => $username, 'pwd' => $pwd)
				)),
				'creation_time'	=> time(),
				'creation_date'	=> date('Y-m-d H:i:s')
		);
		$dbh->Insert('user_stats', $data);
	}
	public function action_before_show_section($section)
	{
		$user = sb_get_current_user();
		$dbh = SB_Factory::getDbh();
		$data = array(
				'user_id'		=> (int)$user->user_id,
				'section_id'	=> (int)$section->section_id,
				'type'			=> 'section_view',
				'data'			=> json_encode(array(
						'server_data'	=> $_SERVER
				)),
				'creation_time'	=> time(),
				'creation_date'	=> date('Y-m-d H:i:s')
		);
		$dbh->Insert('section_stats', $data);
	}
	public function action_before_show_content($article)
	{
		$user = sb_get_current_user();
		$dbh = SB_Factory::getDbh();
		$data = array(
				'user_id'		=> (int)$user->user_id,
				'content_id'	=> (int)$article->content_id,
				'type'			=> 'content_view',
				'data'			=> json_encode(array(
						'server_data'	=> $_SERVER
				)),
				'creation_time'	=> time(),
				'creation_date'	=> date('Y-m-d H:i:s')
		);
		$dbh->Insert('content_stats', $data);
	}
}
new LT_Statistics();
