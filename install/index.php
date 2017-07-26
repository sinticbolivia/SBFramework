<?php
define('LT_INSTALL', 1);
$host = $_SERVER['HTTP_HOST'];
$base_url = 'http://' . $host . dirname(dirname($_SERVER['SCRIPT_NAME']));
require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'init.php';
$task = isset($_REQUEST['task']) ? $_REQUEST['task'] : null;
$lang = isset($_REQUEST['lang']) ? $_REQUEST['lang'] : 'en_US';
$db		= isset($_REQUEST['db_type']) ? $_REQUEST['db_type'] : 'mysql';

$reqs = array(
		array('label'		=> __('PHP Version >= 5.2'), 
				'version' 	=> '5.2', 
				'callback' 	=> 'version_compare(PHP_VERSION, \'5.2\', \'>=\');'
		),
		array('label'		=> __('PHP GD'), 
				'version' 	=> null, 
				'callback' 	=> 'function_exists(\'gd_info\');'
		),
		array(
				'label'		=> __('Soporte PHP para compresion ZIP'),
				'version' 	=> null,
				'callback' 	=> 'class_exists(\'ZipArchive\');'
		),
		array(
				'label'		=> __('Soporte PHP para internacionalizacion (gettext)'),
				'version' 	=> null,
				'callback' 	=> 'function_exists(\'gettext\');'
		)
);

//##try to include the installer translations
if( file_exists( dirname(__FILE__) . DIRECTORY_SEPARATOR . $lang . '.php' ) )
{
	include dirname(__FILE__) . DIRECTORY_SEPARATOR . $lang . '.php';
}
if( $task == 'test_connection' )
{
	function __sb_catch_error($errno, $errstr, $errfile, $errline, $errcontext)
	{
		throw new Exception("[$errno]: ".$errstr, $errno);
	}
	set_error_handler('__sb_catch_error');
	$res = array('status' => 'ok', 'message' => SB_Text::_('Conexion exitosa'));
	try 
	{
		if( $db == 'sqlite3' )
		{
			include dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'database.sqlite3.php';
			$db_name = preg_replace('/\s+/', ' ', trim($_POST['db_name']));
			$db_name = preg_replace('/[^a-zA-z0-9-_]/', '-', $db_name);
			$db_file = dirname(dirname(__FILE__)) . SB_DS . 'db' . SB_DS . $db_name . '.sqlite3';
			$dbh = new SB_Sqlite3($db_file);
			$dbh->Close();
		}
		elseif( $db == 'postgres' )
		{
			include dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'database.postgres.php';
			$dbh = new SB_Postgres($_POST['server'], $_POST['db_user'], $_POST['db_pass'], $_POST['db_name']);
			$dbh->Close();
		}
		else 
		{
			$dbh = new SB_MySQL($_POST['server'], $_POST['db_user'], $_POST['db_pass']);
			$dbh->selectDB($_POST['db_name']);
			$dbh->Close();
		}
		
	}
	catch(Exception $e)
	{
		$res['status'] 	= 'error';
		$res['error'] = $res['message'] = $e->getMessage();
		$res['stack_trace'] = $e->getTrace();
	}
	restore_error_handler();
	header('Content-type: application/json');
	die(json_encode($res));
}
elseif( $task == 'do_install' )
{
	$dbh = null;
	function __sb_catch_error($errno, $errstr, $errfile, $errline, $errcontext)
	{
		throw new Exception("[$errno]: ".$errstr, $errno);
	}
	set_error_handler('__sb_catch_error');
	try
	{
		$db_host		= trim($_POST['db_host']);
		$db_name		= trim($_POST['db_name']);
		$db_username	= trim($_POST['db_username']);
		$db_pwd			= trim($_POST['db_pwd']);
		$language		= isset($_POST['language']) ? trim($_POST['language']) : 'en_US';
		$root_pwd 		= trim($_POST['root_pwd']);
		$base_url		= trim($_POST['base_url']);
	
		$pass			= true;
		
		if( ($db == 'postgres' || $db == 'mysql') && empty($db_host) )
		{
			SB_MessagesStack::AddMessage(SB_Text::_('Debe ingresar el servidor de base de datos.'), 'error');
			$pass = false;
		}
		if( empty($db_name) )
		{
			SB_MessagesStack::AddMessage(SB_Text::_('Debe ingresar el nombre de la base de datos.'), 'error');
			$pass = false;
		}
		if( ($db == 'postgres' || $db == 'mysql') && empty($db_username) )
		{
			SB_MessagesStack::AddMessage(SB_Text::_('Debe ingresar el usuario de base de datos.'), 'error');
			$pass = false;
		}
		if( empty($root_pwd) )
		{
			SB_MessagesStack::AddMessage(SB_Text::_('Debe ingresar una contrase&ntilde;a para root.'), 'error');
			$pass = false;
		}
		if( $pass )
		{
			//$cfg_file 		= file_exists(dirname(dirname(__FILE__)) . SB_DS . 'config.php') ? 'config.php' : 'config-min.php';
			$cfg_file 		= dirname(dirname(__FILE__)) . SB_DS . 'config-min.php';
			$new_cfg_file	= dirname(dirname(__FILE__)) . SB_DS . 'config.php';
			//##write config file
			$fh 		= fopen($cfg_file, 'r');
			$cfg_fh 	= fopen($new_cfg_file, 'w+');
			while( $line = fgets($fh) )
			{
				if( strstr($line, 'DB_TYPE') )
				{
					fwrite($cfg_fh, "define('DB_TYPE', '$db');\n");
				}
				elseif( strstr($line, 'DB_SERVER') )
				{
					fwrite($cfg_fh, "define('DB_SERVER', '$db_host');\n");
				}
				elseif( strstr($line, 'DB_NAME') )
				{
					if( $db == 'sqlite3' )
					{
						$db_name = preg_replace('/\s+/', ' ', $db_name);
						$db_name = preg_replace('/[^a-zA-z0-9-_]/', '-', $db_name) . '.sqlite3';
					}
					
					fwrite($cfg_fh, "define('DB_NAME', '{$db_name}');\n");
				}
				elseif( strstr($line, 'DB_USER') )
				{
					fwrite($cfg_fh, "define('DB_USER', '$db_username');\n");
				}
				elseif( strstr($line, 'DB_PASS') )
				{
					fwrite($cfg_fh, "define('DB_PASS', '$db_pwd');\n");
				}
				elseif( strstr($line, "'BASEURL'") )
				{
					$url 		= parse_url($base_url);
					$the_url 	= 'HTTP_HOST' . ((isset($url['path']) && $url['path'] != '/') ? ".'" . $url['path'] . "'" : '');
					fwrite($cfg_fh, "define('BASEURL', $the_url);\n");
				}
				else
				{
					fwrite($cfg_fh, $line);
				}
			}
			fclose($fh);
			fclose($cfg_fh);
			SB_Session::setVar('root_pwd', $root_pwd);
			SB_Session::setVar('language', $language);
			header("Location: $base_url/install/success.php");die();
		}
		
	}
	catch(Exception $e)
	{
		print $e->getMessage();
	}
}
if( isset($_GET['createdb']) )
{
	$dbh = new SB_MySQL('127.0.0.1', 'root', '');
	$res = $dbh->Query("CREATE DATABASE {$_GET['createdb']}");
	var_dump($res);
	die();
}

$reqs_pass = true;
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
	<script>
	var cms = 
	{
		check_connection: function(db_type, server, db_name, db_user, db_pass)
		{
			var params = 'task=test_connection&db_type='+db_type+'&server='+server+'&db_name='+db_name+'&db_user='+db_user+'&db_pass='+db_pass;
			jQuery.post('index.php', params, function(res)
			{
				if( res.status == 'ok' )
				{
					alert(res.message);
					jQuery('#collapse-database').collapse('hide');
					jQuery('#panel-collapse-language').collapse('show');
					//jQuery('#collapse-admin-cfg').collapse('show');
				}
				else
				{
					alert(res.error);
				}
			});
			
		}
	};
	jQuery(function()
	{
		jQuery('#btn-check-connection').click(function()
		{
			var db_type	= jQuery('#db_type').val().trim();
			var server 	= jQuery('#db_host').val().trim();
			var db_name = jQuery('#db_name').val().trim();
			var db_user = jQuery('#db_username').val().trim();
			var db_pass = jQuery('#db_pwd').val().trim();
			
			if( server.length <= 0 )
			{
				alert('<?php print SB_Text::_('Debe ingresar el servidor de base de datos'); ?>');
				jQuery('#db_host').focus();
				return false;
			}
			if( db_name.length <= 0 )
			{
				alert('<?php print SB_Text::_('Debe ingresar el nombre de la base de datos'); ?>');
				jQuery('#db_name').focus();
				return false;
			}
			if( db_user.length <= 0 )
			{
				alert('<?php print SB_Text::_('Debe ingresar el usuario de base de datos'); ?>');
				jQuery('#db_username').focus();
				return false;
			}
			cms.check_connection(db_type, server, db_name, db_user, db_pass);
			
			return false;
		});
	});
	</script>
</head>
<body>
<div class="container-fluid">
	<div class="row">
		<div id="content" class="col-md-10 col-md-offset-1">
			<div class="jumbotron">
				<h1><?php print SB_Text::_('Bienvenido a la installacion de Little CMS.'); ?></h1>
			</div>
			<table class="table">
			<thead>
			<tr>
				<th><?php _e('Requerimiento'); ?></th>
				<th class="text-center"><?php _e('Satisface'); ?></th>
			</tr>
			</thead>
			<?php foreach($reqs as $r): ?>
			<tr>
				<td style="width:80%;"><?php print $r['label']; ?></td>
				<td style="width:20%;text-align:center;">
					<?php 
					eval('$reqs_pass = ' . $r['callback']);
					?>
					<?php if( $reqs_pass ): ?>
					<span class="glyphicon glyphicon-ok text-success"></span>
					<?php else: ?>
					<span class="glyphicon glyphicon-remove text-danger"></span>
					<?php endif; ?>
				</td>
			</tr>
			<?php endforeach; ?>
			</table>
			<?php if( $reqs_pass ): ?>
			<form action="" method="post" class="form-horizontal">
				<input type="hidden" name="task" value="do_install" />
				<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
					<div id="panel-licence" class="panel panel-default">
				    	<div id="headingOne" class="panel-heading" role="tab" >
					      	<h4 class="panel-title">
					          	<?php print SB_Text::_('Acuerdo de licencia'); ?>
					      	</h4>
				    	</div>
				    	<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
				      		<div class="panel-body">
				        		Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, 
				        		non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, 
				        		sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, 
				        		craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. 
				        		Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them 
				        		accusamus labore sustainable VHS.
				        		<p class="text-right">
				        			<a href="javascript:;" onclick="jQuery('#collapseOne').collapse('hide');jQuery('#collapse-database').collapse('show');" class="btn btn-primary"><?php print SB_Text::_('Siguiente'); ?></a>
				        		</p>
				      		</div>
				    	</div>
				  	</div><!-- end id="panel-licence" -->
				  	<div id="panel-database" class="panel panel-default">
				    	<div class="panel-heading" role="tab" id="headingTwo">
				      		<h4 class="panel-title"><?php print SB_Text::_('Configuracion Base de Datos'); ?></h4>
				    	</div>
				    	<div id="collapse-database" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
				      		<div class="panel-body">
								<div class="form-group">
								    <label for="db_type" class="col-sm-2 control-label"><?php _e('Base de Datos:'); ?></label>
								    <div class="col-sm-10">
								    	<select id="db_type" name="db_type" class="form-control">
								    		<option value="mysql">MySQL</option>
								    		<option value="postgres">Postgres</option>
								    		<option value="sqlite3">SQLite 3</option>
								    	</select>
								    </div>
								</div>			      		
				      			<div class="form-group">
								    <label for="db_host" class="col-sm-2 control-label"><?php print SB_Text::_('Servidor:'); ?></label>
								    <div class="col-sm-10">
								    	<input type="text" class="form-control" id="db_host" name="db_host" value="" placeholder="">
								    </div>
								</div>
				        		<div class="form-group">
								    <label for="db_name" class="col-sm-2 control-label"><?php print SB_Text::_('Nombre Base de Datos:'); ?></label>
								    <div class="col-sm-10">
								    	<input type="text" class="form-control" id="db_name" name="db_name" value="" placeholder="">
								    </div>
								</div>
								<div class="form-group">
								    <label for="db_username" class="col-sm-2 control-label"><?php print SB_Text::_('Usuario:'); ?></label>
								    <div class="col-sm-10">
								    	<input type="text" class="form-control" id="db_username" name="db_username" value="" placeholder="">
								    </div>
								</div>
								<div class="form-group">
								    <label for="db_pwd" class="col-sm-2 control-label"><?php print SB_Text::_('Constrase&ntilde;a:'); ?></label>
								    <div class="col-sm-10">
								    	<input type="text" class="form-control" id="db_pwd" name="db_pwd" value="" placeholder="">
								    </div>
								</div>
								<p>
									<a id="btn-check-connection" href="javascript:;" class="btn btn-primary">
										<?php print SB_Text::_('Verificar conexion'); ?>
									</a>
								</p>
				      		</div>
				    	</div>
				  	</div><!-- end id="panel-database" -->
				  	<div id="panel-language" class="panel panel-default">
				    	<div class="panel-heading" role="tab" >
					      	<h4 class="panel-title"><?php _e('Seleccion de Idioma'); ?></h4>
				    	</div>
				    	<div id="panel-collapse-language" class="panel-collapse collapse" role="tabpanel">
				    		<div class="panel-body">
				    			<div class="container-fluid">
				    				<div class="row">
						    			<div class="col-md-3">
						    				<div class="form-group">
						    					<label><?php _e('Idioma'); ?></label>
						    					<select name="language" class="form-control">
						    						<option value="en_US" <?php print $lang == 'en_US' ? 'selected' : ''; ?>><?php _e('Ingles'); ?></option>
						    						<option value="es_ES" <?php print $lang == 'es_ES' ? 'selected' : ''; ?>><?php _e('Español'); ?></option>
						    					</select>
						    				</div>
						    			</div>
						    		</div>
						    		<p class="text-right">
					        			<a href="javascript:;" onclick="jQuery('.panel-collapse').collapse('hide');jQuery('#collapse-admin-cfg').collapse('show');" class="btn btn-primary"><?php print SB_Text::_('Siguiente'); ?></a>
					        		</p>
				    			</div>
				    		</div>
				    	</div>
				    </div>
				  	<div id="panel-admin-cfg" class="panel panel-default">
				    	<div class="panel-heading" role="tab" id="headingThree">
				      		<h4 class="panel-title"><?php print SB_Text::_('Administracion')?></h4>
				    	</div>
				    	<div id="collapse-admin-cfg" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
				      		<div class="panel-body">
				      			<div style="display:none">
					    			<div class="form-group">
									    <label for="base_url" class="col-sm-2 control-label"><?php print SB_Text::_('URL Instalacion:'); ?></label>
									    <div class="col-sm-10">
									    	<input type="text" class="form-control" id="base_url" name="base_url" value="<?php print $base_url; ?>" placeholder="">
									    </div>
									</div>
					    		</div>
				      			<?php print SB_Text::_('Ingrese una contrase&ntilde;a para el usuario de administracion'); ?>
				      			<div class="form-group">
								    <label for="root_pwd" class="col-sm-2 control-label"><?php print SB_Text::_('Contrase&ntilde;a:'); ?></label>
								    <div class="col-sm-10">
								    	<input type="password" class="form-control" id="root_pwd" name="root_pwd" value="" placeholder="">
								    </div>
								</div>
					      		<p>
					      			<button type="submit" class="btn btn-primary"><?php print SB_Text::_('Terminado'); ?></button>
					      		</p>
				      		</div>
				    	</div>
				  	</div>
				</div><!-- end id="accordion" -->
			</form>
			<?php else: ?>
			<h2 class="bg-danger"><?php _e('El servidor no cumple con los requerimientos minimos necesarios, porfavor verifique que tiene los requerimientos instalados.'); ?></h2>
			<?php endif; ?>
		</div><!-- end id="content" -->
	</div>
</div>
</body>
</html>