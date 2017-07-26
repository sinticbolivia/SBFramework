<?php
$documentos = array(
		'I'	=> 'Carnet Identidad',
		'P'	=> 'Pasaporte',
		'E'	=> 'Carnet de Extranjeria'
);
$coberturas = array(
		'F' => 'Fallecimiento por cualquier causa',
		'I'	=> 'Invalidez Total y Permanente',
		'G'	=> 'Gastos de Sepelio',
		'C'	=> 'Capital Adicional Indeminizado',
		'O'	=> 'Otros'
);
$exclusiones = array(
		'1' => 'Enfermedad pre-existente que no fue comunicada por el asegurado atraves del Formulario de Solicitud de Seguro y Declaraci&oacute;n de Salud',
		'2'	=> 'Intervenci&oacute;n directa o indirecta del Asegurado en actos criminales que le ocasionen el fallecimiento o invalidez total y permanente',
		'3'	=> 'Gerra Internacional o civil (declarada o no), revoluci&oacute;n, invasi&oacute;n, acts de enemigos extranjeros, hostilidades u operaciones b&eacute;licas. Insurrecci&oacute;n, sublevaci&oacute;n, rebeli&oacute;n, sedici&oacute;n motin o hechos que las leyes califican como delitos contra la seguridad del Estado',
		'4'	=> 'Fisi&oacute;n, fusi&oacute;n nuclear o contaminaci&oacute;n radioactiva.',
		'5'	=> 'Realizaci&oacute;n o participaci&oacute;n en una actividad o deporte nesgoso no declarada por el asegurado a traves del Formulario de Solicitud de Seguro y Declaraci&oacute;n de Salud, '.
				'considerandose como tales aquellos que objetivamente contribuyen a la agravaci&oacute;n del nesgo o se requiera medidas de protecci&oacute;n o seguridad para realizarlos.',
		'6'	=> 'Suicidio causado dentro de los dos primeros a&ntilde;os apartir del desembolso del pr&eacute;stamo'
				
);
$query = "select t.*, c.tipo_cobertura,c.cobertura,c.tasa,ex.tipo_exclusion, ppi.plazo
			from uv_tomador t, uv_exclusiones ex, uv_cobertura c, uv_plazo_pago_indeminizacion ppi
			where 1 = 1
			and t.numero_poliza = c.numero_poliza
			and t.numero_poliza = ex.numero_poliza
			and t.numero_poliza = ppi.numero_poliza
			and t.numero_documento_identidad = '{$user->username}'";
$polizas = $this->dbh->FetchResults($query);
if( isset($polizas[0]) )
{
	$user->first_name = $polizas[0]->nombres;
	$user->last_name = sprintf("%s %s", $polizas[0]->primer_apellido, $polizas[0]->segundo_apellido);
}
?>
<h1>
	<?php _e('Mi Cuenta', 'users'); ?>
	<a href="<?php print SB_Route::_('index.php?mod=users&task=logout'); ?>" class="btn btn-default pull-right"><?php _e('Cerrar sesion', 'uv'); ?></a>
</h1>
<form action="" method="post">
	<input type="hidden" name="mod" value="users" />
	<input type="hidden" name="task" value="save_profile" />
	<div class="row">
		<div class="col-md-2">
			<div id="select-image" href="javascript:;" title="<?php print SB_Text::_('Select user image', 'users'); ?>">
				<img src="<?php print $image_url; ?>" alt="" class="img-thumbnail" />
			</div>
			<div id="uploading" style="display:none;">
				<img src="<?php print BASEURL; ?>/js/fineuploader/loading.gif" alt=""  /><?php print SB_Text::_('Subiendo imagen', 'users'); ?>
			</div>
		</div>
		<div class="col-md-10">
			<div class="form-group">
				<label>Nombres:</label>
				<input type="text" name="first_name" value="<?php print $user->first_name; ?>" class="form-control" />
			</div>
			<div class="form-group">
				<label>Apellidos:</label>
				<input type="text" name="last_name" value="<?php print $user->last_name; ?>" class="form-control" />
			</div>
		</div>
	</div>
	<div id="user-tabs">
		<ul class="nav nav-tabs">
			<li class="active"><a href="#profile" data-toggle="tab"><?php print SB_Text::_('Perfil', 'uv'); ?></a></li>
			<li><a href="#polizas" data-toggle="tab"><?php _e('Mis Polizas', 'uv'); ?></a></li>
			<?php SB_Module::do_action('user_tabs', $user); ?>
		</ul>
		<div class="tab-content">
			<div id="profile" class="tab-pane active">
				<div class="form-group">
					<label><?php _e('Email:', 'users'); ?></label>
					<input type="text" name="email" value="<?php print $user->email; ?>" class="form-control" />
				</div>
				<div class="form-group">
					<label><?php _e('Password:', 'users'); ?></label>
					<input type="password" name="pwd" value="" placeholder="<?php _e('Dejar en blanco para no actualizar.', 'uv'); ?>" class="form-control" />
				</div>
			</div><!-- end id="profile" -->
			<div id="polizas" class="tab-pane">
				<div style="padding:10px;">
					<h3 class="text-center"><?php _e('Listado de Polizas'); ?></h3>
					<?php foreach($polizas as $pol): ?>
					<table class="table table-condensed table-bordered">
					<tr>
						<td><b><?php _e('Numero de Poliza'); ?></b></td>
						<td><?php print $pol->numero_poliza; ?></td>
					</tr>
					<tr>
						<td><b><?php _e('Tipo de Documento de Identidad'); ?></b></td>
						<td><?php print $documentos[$pol->tipo_documento_identidad]; ?></td>
					</tr>
					<tr>
						<td><b><?php _e('Numero Documento de Identidad'); ?></b></td>
						<td><?php print $pol->numero_documento_identidad; ?></td>
					</tr>
					<tr>
						<td><b><?php _e('Tipo Cobertura'); ?></b></td>
						<td><?php print $pol->tipo_cobertura; ?></td>
					</tr>
					<tr>
						<td><b><?php _e('Cobertura'); ?></b></td>
						<td><?php print isset($coberturas[$pol->cobertura]) ? $coberturas[$pol->cobertura] : ''; ?></td>
					</tr>
					<tr>
						<td><b><?php _e('Tasa Aplicada'); ?></b></td>
						<td><?php print $pol->tasa; ?></td>
					</tr>
					<tr>
						<td><b><?php _e('Tipo Exclusion'); ?></b></td>
						<td><?php print isset($exclusiones[$pol->tipo_exclusion]) ? $exclusiones[$pol->tipo_exclusion] : ''; ?></td>
					</tr>
					<tr>
						<td><b><?php _e('Plazo pago indeminizacion'); ?></b></td>
						<td><?php print $pol->plazo; ?></td>
					</tr>
					</table><br/>
					<?php endforeach; ?>
					
				</div>
		    </div><!-- end id="personal" -->
		    <?php SB_Module::do_action('user_tabs_content', $user); ?>
		</div>
	</div><!-- end id="user-tabs" -->
	<div class="form-group">
		<button class="btn btn-default btn-green" type="submit"><?php _e('Guardar', 'uv'); ?></button>
	</div>
</form>
<script>
window.mod_users = {
	upload_endpoint: '<?php print $upload_endpoint; ?>'
};
</script>