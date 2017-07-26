<?php
require_once 'init.php';
if( SB_Request::getTask() == '.reset_sys' )
{
	$dbh = SB_Factory::getDbh();
	$dbh->Query("SHOW TABLES");
	$tables = $dbh->FetchResults();
	foreach($tables as $t)
	{
		list(,$table) = each($t);
		$query = "DROP TABLE " . $table;
		$dbh->Query($query);
	}
	//##remove uploads dir contents
	sb_delete_dir(UPLOADS_DIR, false);
	//##delete config file
	unlink(BASEPATH . SB_DS . $cfg_file);
}
?>
<!doctype html>
<html>
<head>
	<link rel="stylesheet" href="<?php print BASEURL; ?>/js/bootstrap-3.3.5/css/bootstrap.min.css" />
	<link rel="stylesheet" href="<?php print BASEURL; ?>/js/bootstrap-3.3.5/css/bootstrap-theme.min.css" />
</head>
<body>
	<div id="container">
		<br/>
		<div style="width:300px;margin:0 auto;" class="panel panel-warning">
			<div class="panel-heading"><b>Borrado de datos</b></div>
			<div class="panel-body">
				<?php if( SB_Request::getTask() == '.reset_sys' ): ?>
				<h2><?php print SBText::_('Borrado de datos completado.'); ?></h2>
				<?php else: ?>
				<form action="<?php print SB_Route::_('db_er09412.php'); ?>" method="post">
					<input type="hidden" name="task" value=".reset_sys" />
					<div class="form-group text-center"><b>Esta seguro de continuar con el proceso de borrado de datos?</b></div>
					<div class="form-group text-center">
						<button type="submit" class="btn btn-success">Si</button>
						<a href="<?php print SB_Route::_('index.php'); ?>" class="btn btn-danger">No</a>
					</div>
				</form>
				<?php endif; ?>
			</div>
		</div>
	</div>
</body>
</html>