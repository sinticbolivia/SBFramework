<?php
class LT_AdminControllerDbReset extends SB_Controller
{
	public function task_default()
	{
	}
	public function task_getdb()
	{
		$dbserver	= 'localhost';
		$dbname 	= SB_Request::getString('dbname');
		$username 	= SB_Request::getString('username');
		$pass		= SB_Request::getString('pass');
		$dbh 		= new SB_MySQL($dbserver, $username, $pass, $dbname);
		
		$rows = $dbh->FetchResults("SHOW TABLES");
		$allow_tables = array(
			'content' 			=> 'Almacenamiento de los contenidos',
			'content_meta'		=> 'Metadatos de contenidos',
			'content_stats'		=> 'Estadisticas de contenido',
			'section' 			=> 'Almacenamiento de la secciones',
			'section_meta'		=> 'Metadatos de la secciones',
			'section2content'	=> 'Relacion entre contenido y seccion',
			'section_stats'		=> 'Estadisticas de la seccion',
			'users' 			=> 'Registro de usuarios',
			'user_meta'			=> 'Metadatos de usuarios',
			'users_stats'		=> 'Estadisticas de usuarios',
			'user_levels'		=> 'Tabla donde se almacenan los niveles de usuarios',
			'user_levels_group'	=> 'Tabla donde se almacenan los grupos de niveles',
			'user_group2levels'	=> 'Relacion entre el grupo de nivels y nivel',
			'watu_takings'		=> 'Tests',
			'forms'				=> 'Formularios',
			'messages'			=> 'Tabla de mensajeria',
			'message_rcpts'		=> 'Tabla de cola de envios de mensajeria',
			'user_popups'		=> 'Tabla de mensajes para los usuarios (popup)',
			'user_popup_rcpts'	=> 'Tabla de cola para popups de usuarios'
		);
		$tables = array();
		foreach($rows as $row)
		{
			list($key, $table) = each($row);
			if( !isset($allow_tables[$table]) )
				continue;
			$query = "SELECT `AUTO_INCREMENT`
						FROM  INFORMATION_SCHEMA.TABLES
						WHERE TABLE_SCHEMA = '$dbname'
						AND   TABLE_NAME   = '$table';";
			$tables[] = (object)array(
				'table'				=> $table,
				'description'		=> $allow_tables[$table],
				'current_increment'	=> $dbh->GetVar($query)
			);
		}
		sb_set_view('getdb');
		sb_set_view_var('tables', $tables);
		
	}
	public function task_reset()
	{
		$table = SB_Request::getString('table');
		$this->dbh->Query("TRUNCATE $table");
		if( $table == 'users' )
		{
			//##insert root user
			$this->dbh->Insert('users', array(
				'first_name'	=> 'Super',
				'last_name'		=> 'Root',
				'username'		=> 'root',
				'pwd'			=> md5('lt_admin_001'),
				'status'		=> 'enabled',
				'role_id'		=> 0,
				'creation_date'	=> date('Y-m-d H:i:s')
			));
		}
		sb_redirect(SB_Route::_('index.php?mod=dbreset'));
	}
}