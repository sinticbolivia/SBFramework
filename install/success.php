<?php
$base_dir = dirname(dirname(__FILE__));
$cfg_file = $base_dir . DIRECTORY_SEPARATOR . 'config.php';
if( !file_exists($cfg_file) )
{
	die('INSTALL ERROR: The config.php file does not exists or it was not created');
}
define('LT_INSTALL', 1);
define('CFG_FILE', basename($cfg_file));
require_once $base_dir . DIRECTORY_SEPARATOR . 'init.php';
$lang		= SB_Session::getVar('language', 'en_US');
$site_title = SB_Request::getString('site_title', __('Private Access'));
define('LANGUAGE', $lang);
$def_ops = array(
		'SITE_TITLE' 	=> $site_title,
		'DATE_FORMAT'	=> 'd-m-Y',
		'COUNTRY_CODE'	=> 'ES',
		'LANGUAGE'		=> $lang,
		'BG_COLOR'		=> '#7ae09b',
		'USER_WELCOME_MSG'	=> '<div align="center">
									<font color="#ff0000" size="5"><b>Hola [user_firstname]</b></font>
									<font color="#ff0000"><br></font><br>
									<font size="4">
										<b>
											<font>
												<font color="#ff0000"><em></em>Escoge la informacion<br>y haz CLICK en su boton</font><br>
												<font color="#33ffcc">
													<img alt="" src="http://500sitios.com/imag/flechas-rojas-abajo-01.png" align="none" height="94" 
														width="186">
												</font>
												<br>
											</font>
										</b>
									</font>
									<br>
								</div>',
		'TIME_ZONE'			=> 'Europe/Madrid'
);


//##default modules
$def_mods = array(
		'mod_settings',
		'mod_dashboard',
		'mod_users',
		'mod_content',
		'mod_backup',
		'mod_menu',
		'mod_modules',
		'mod_statistics',
		'mod_storage',
		//'mod_exams',
		//'mod_forms',
		//'mod_gcp',
		//'mod_smn',
		//'mod_userpopup',
		//'mod_zaxaa',
		//'mod_levels',
		//'mod_userspromo',
		//'mod_optin',
);


//##try to include the installer translations
if( file_exists( dirname(__FILE__) . DIRECTORY_SEPARATOR . $lang . '.php' ) )
{
	include dirname(__FILE__) . DIRECTORY_SEPARATOR . $lang . '.php';
}
try 
{
	$root_pwd	= SB_Session::getVar('root_pwd');
	$dbh 		= SB_Factory::getDbh();
	$cdate 		= date('Y-m-d H:i:s');
	$sql 		= null;
	if( DB_TYPE == 'mysql' )
		$sql = file_get_contents('database.sql');
	else 
		$sql = file_get_contents('database.'.DB_TYPE.'.sql');
	
	$queries 	= array_map('trim', explode(";\n", $sql));
	
	foreach($queries as $query)
	{
		if( empty($query) ) continue;
		$dbh->Query($query);
	}
	$query = "SELECT * FROM users WHERE username = 'root' LIMIT 1";
	//##check if root user already exists
	if( !$dbh->Query($query) )
	{
		$root_user = array(
				'first_name'	=> 'Super',
				'last_name'		=> 'Root',
				'username'		=> 'root',
				'pwd'			=> md5($root_pwd),
				'status'		=> 'enabled',
				'role_id'		=> 0,
				'creation_date'	=> $cdate
		);
		$dbh->Insert('users', $root_user);
	}
	
	$roles = array(
			array( 'label' => __('Administrador', 'lti'), 'key' => 'admin'),
			array( 'label' => __('Usuario', 'lti'), 'key' => 'user'),
			array( 'label' => __('Visitante', 'lti'), 'key' => 'guest'),
			array( 'label' => __('SuperAdmin', 'lti'), 'key' => 'superadmin'),
	);
	
	foreach($roles as $role)
	{
		if( !$dbh->Query('SELECT * FROM user_roles WHERE role_key = \''.$role['key'].'\' LIMIT 1') )
		{
			$role_data = array(
					'role_name'			=> $role['label'],
					'role_key'			=> $role['key'],
					'creation_date'		=> date('Y-m-d H:i:s')
			);
			$dbh->Insert('user_roles', $role_data);
		}
	}
	$permissions = array(
			array(
					'group'			=> 'system',
					'permission'	=> 'manage_modules',
					'label'			=> SB_Text::_('Administrar Modulos'),
					'attributes'	=> json_encode(array('only_root' => 'yes')),
					'last_modification_date'	=> $cdate,
					'creation_date'				=> $cdate
			),
			array(
					'group'			=> 'system',
					'permission'	=> 'manage_templates',
					'label'			=> __('Administrar Plantillas'),
					'attributes'	=> '',
					'last_modification_date'	=> $cdate,
					'creation_date'				=> $cdate
			),
			array(
					'group'			=> 'system',
					'permission'	=> 'enable_module',
					'label'			=> SB_Text::_('Habilitar Modulo'),
					'attributes'	=> json_encode(array('only_root' => 'yes')),
					'last_modification_date'	=> $cdate,
					'creation_date'				=> $cdate
			),
			array(
					'group'			=> 'system',
					'permission'	=> 'disable_module',
					'label'			=> SB_Text::_('Deshabilitar Modulo'),
					'attributes'	=> json_encode(array('only_root' => 'yes')),
					'last_modification_date'	=> $cdate,
					'creation_date'				=> $cdate
			),
			array(
					'group'			=> 'system',
					'permission'	=> 'manage_backend',
					'label'			=> __('Administrar Backend'),
					'last_modification_date'	=> $cdate,
					'creation_date'				=> $cdate
			),
			array(
					'group'			=> 'system',
					'permission'	=> 'create_role_admin',
					'label'			=> SB_Text::_('Crear Rol Admin'),
					'last_modification_date'	=> $cdate,
					'creation_date'				=> $cdate
			),
			array(
					'group'			=> 'system',
					'permission'	=> 'create_role_user',
					'label'			=> SB_Text::_('Crear Rol Usuario'),
					'last_modification_date'	=> $cdate,
					'creation_date'				=> $cdate
			),
			array(
					'group'			=> 'system',
					'permission'	=> 'create_role_guest',
					'label'			=> SB_Text::_('Crear Rol Invitado'),
					'last_modification_date'	=> $cdate,
					'creation_date'				=> $cdate
			),
	);
	$local_permissions = sb_get_permissions(false);
	foreach($permissions as $perm)
	{
		if( in_array($perm['permission'], $local_permissions) ) continue;
		$dbh->Insert('permissions', $perm);
	}
	//##enable default modules
	foreach($def_mods as $mod)
	{
		//call on enabled module file
		$module_path = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . $mod;
		if( file_exists($module_path . SB_DS . 'on_enabled.php') )
		{
			require_once $module_path . SB_DS . 'on_enabled.php';
		}
	}
	//##update database enabled modules
	sb_update_parameter('modules', $def_mods);
	//##insert whole permissions to superadminr role
	$role_id = $dbh->GetVar("SELECT role_id FROM user_roles WHERE role_key = 'superadmin' LIMIT 1");
	$insert = "INSERT INTO role2permission(role_id,permission_id) select $role_id,permission_id from permissions";
	$dbh->Query($insert);
	
	//##insert default users
	$user_id = $dbh->Query("INSERT INTO users(first_name,last_name,email,role_id) " . 
							"VALUES('Nombre', 'Apellidos', 'email@email.es', (SELECT role_id FROM user_roles WHERE role_key = 'user'))");
	SB_Meta::addMeta('user_meta', '_birthday', '10-12-1970', 'user_id', $user_id);
	SB_Meta::addMeta('user_meta', '_address', 'direccion', 'user_id', $user_id);
	SB_Meta::addMeta('user_meta', '_country', 'ES', 'user_id', $user_id);
	
	$user_id = $dbh->Query("INSERT INTO users(first_name,last_name,email,role_id) VALUES('Nombre', 'Apellidos', 'email2@email.es', $role_id)");
	SB_Meta::addMeta('user_meta', '_birthday', '10-12-1970', 'user_id', $user_id);
	SB_Meta::addMeta('user_meta', '_address', 'direccion', 'user_id', $user_id);
	SB_Meta::addMeta('user_meta', '_country', 'ES', 'user_id', $user_id);
	
	//##update system settings
	sb_update_parameter('settings', $def_ops);
	$dbh->Close();
	SB_Session::unsetVar('root_pwd');
}
catch(Exception $e)
{
	//header('Location: index.php');die();
	die($e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title></title>
	<meta charset="utf-8" />
	<link href="../js/bootstrap-3.3.5/css/bootstrap.min.css" rel="stylesheet" />
	<link href="../js/bootstrap-3.3.5/css/bootstrap-theme.min.css" rel="stylesheet" />
	<script src="../js/jquery.min.js"></script>
	<script src="../js/bootstrap-3.3.5/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container-fluid">
	<div class="row">
		<div id="content" class="col-md-10 col-md-offset-1">
			<div class="jumbotron">
				<h1><?php _e('Instalacion FINALIZADA CORRECTAMENTE.'); ?></h1>
			</div>
			<p class="text-center">
				<a href="<?php print SB_Route::_('index.php'); ?>" class="btn btn-primary" target="_blank">
					<?php print SB_Text::_('Acceder al FrontEnd')?></a>
			</p>
			<p class="text-center">
				<a href="<?php print SB_Route::_('admin/index.php'); ?>" class="btn btn-primary" target="_blank">
					<?php print SB_Text::_('Acceder al BackEnd')?></a>
			</p>
			<p><b><?php _e('Datos de Acceso al BackEnd:'); ?></b></p>
			<p>
				<b><?php print SB_Text::_('Usuario:'); ?></b> root<br/>
				<b><?php print SB_Text::_('Contrase&ntilde;a:'); ?></b> <?php print $root_pwd; ?>
			</p>
			<div class="bg-danger text-center" style="padding:10px;">
				<p>&quot;<?php _e('Aviso de seguridad: Recuerda BORRAR o cambiar el nombre al directorio /install'); ?>&quot;</p>
			</div>
		</div>
	</div>
</div>
</body>
</html>