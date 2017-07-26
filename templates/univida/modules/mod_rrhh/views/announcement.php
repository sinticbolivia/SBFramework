<div class="container">
	<div class="row">
		<div class="col-md-12 centered">
			<h3><span><?php print $title; ?></span></h3>
		</div>
	</div>
</div>
<div id="announcement-container" class="container">
	<div>
		<a href="<?php print SB_Route::_('index.php?mod=rrhh'); ?>" class="btn btn-default <?php print $view == 'default' ? 'active' : ''; ?>">
			<?php _e('Announcements', 'rrhh'); ?>
		</a>
		<a href="<?php print SB_Route::_('index.php?mod=rrhh&view=profile'); ?>" class="btn btn-default <?php print $view == 'profile' ? 'active' : ''; ?>">
			<?php _e('My Profile', 'rrhh'); ?>
		</a>
		<a href="<?php print SB_Route::_('index.php?mod=rrhh&task=logout'); ?>" class="btn btn-default">
			<?php _e('Cerrar Sesi&oacute;n', 'rrhh'); ?>
		</a>
	</div><br/>
	<div class="row">
		<div class="col-xs-12 col-md-9">
			<div id="anouncement-messages">
				<?php if( $obj->status == 'closed' ): ?>
				<div class="alert alert-danger"><b><?php _e('La convocatoria esta cerrada', 'rrhh'); ?></b></div>
				<?php elseif( $already_applied ): ?>
				<div class="alert alert-info"><b><?php _e('You already applied to this announcement', 'rrhh'); ?></b></div>
				<?php endif; ?>
			</div>
			<div id="announcement-content"><?php print $obj->description; ?></div>
		</div>
		<div class="col-xs-12 col-md-3">
			<div class="panel panel-default">
				<div class="panel-heading"><h3 class="panel-title"><?php _e('Information', 'rrhh'); ?></h3></div>
				<div class="panel-body">
					<table>
					<tr>
						<td><b><?php _e('Limit Date:', 'rrhh'); ?></b></td><td><?php print sb_format_date($obj->end_date);?></td>
					</tr>
					<tr>
						<td><b><?php _e('Vacancies:', 'rrhh'); ?></b></td><td><?php print $obj->vacancies; ?></td>
					</tr>
					</table>
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-heading"><h3 class="panel-title"><?php _e('Actions', 'rrhh'); ?></h3></div>
				<div class="panel-body">
					<a href="<?php print SB_Route::_('index.php?mod=rrhh'); ?>" class="btn btn-default">
						<span class="glyphicon glyphicon-step-backward"></span> <?php _e('Back', 'rrhh'); ?>
					</a>
					<?php if( $obj->status == 'active' ): ?>
					<a href="#" id="btn-apply-now" class="btn btn-default btn-success">
						<span class="glyphicon glyphicon-ok"></span> <?php _e('Apply Now', 'rrhh'); ?>
					</a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div><!-- end id="announcement-container" -->
<script>
jQuery(function()
{
	jQuery('#btn-apply-now').click(function(e)
	{
		jQuery('#form-apply-announcement').find('button[type=submit]').prop('disabled', false);
		jQuery('#modal-apply-now').modal('show');
		return false;
	});
	jQuery('input[name=inmediate_availability]').click(function(e)
	{
		if( this.value == 'yes' && jQuery(this).is(':checked') )
		{
			jQuery('input[name=days]').prop('disabled', true);
		}
		else
		{
			jQuery('input[name=days]').prop('disabled', false);
		}
	});
    jQuery('input[name=employee_relationship]').click(function(e)
	{
		if( this.value == '1' && jQuery(this).is(':checked') )
		{
			jQuery('input[name=employee_name]').prop('disabled', false);
		}
		else
		{
			jQuery('input[name=employee_name]').prop('disabled', true);
		}
	});
	jQuery('#form-apply-announcement').submit(function()
	{
		jQuery(this).find('button[type=submit]').prop('disabled', true);
		try
		{
			if( jQuery('.experience-row input[type=checkbox]:checked').length <= 0 )
			{
				alert('Debe seleccionar almenos una experiencia especifica');
				return false;
			}
			var salary = parseFloat(this.salary_pretension.value);
			if( isNaN(salary) || salary <= 0 )
			{
				alert('Su pretension salarial es invalida');
				return false;
			}
			if( !this.inmediate_availability[0].checked && !this.inmediate_availability[1].checked  )
			{
				alert('Debe elegir la disponibilidad');
				return false;
			}
			if( !this.employee_relationship[0].checked && !this.employee_relationship[1].checked )
			{
				alert('Debe elegir una opcion si tiene parentesco con un funcionario(a) de la empresa');
				return false;
			}
            if( this.employee_relationship[0].checked && this.employee_name.value.trim().length <= 0 )
			{
				alert('Debe ingresar el nombre del empleado con el cual tiene parentesco');
				return false;
			}
			if( !this.i_agree.checked )
			{
				alert('Debe confirmar que toda la informacion es verdadera');
				return false;
			}
			var params = jQuery(this).serialize();
			jQuery.post('index.php', params, function(res)
			{
				jQuery(this).find('button[type=submit]').prop('disabled', false);
				jQuery('#modal-apply-now').modal('hide');
				if(res.status == 'ok')
				{
					jQuery('#anouncement-messages').append('<div class="alert alert-success"><b>'+res.message+'</b></div>');
				}
				else
				{
					jQuery('#anouncement-messages').append('<div class="alert alert-danger"><b>'+res.error+'</b></div>');
				}
			})
		}
		catch(e)
		{
			window.console && console.log(e);
		}
		
		return false;
	});
});
</script>
<style>
.experience-row:nth-of-type(odd){background-color: #f9f9f9;}
.experience-row:hover{cursor:pointer;}
</style>
<div id="modal-apply-now" class="modal fade">
	<form action="" method="post" id="form-apply-announcement" class="modal-dialog form-horizontal">
		<input type="hidden" name="mod" value="rrhh" />
		<input type="hidden" name="task" value="apply" />
		<input type="hidden" name="ajax" value="1" />
		<input type="hidden" name="id" value="<?php print $obj->id; ?>" />
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        	<h4 class="modal-title"><?php _e('Apply to Announcement', 'rrhh'); ?></h4>
		</div>
		<div class="modal-body">
			<div class="">
				<label class="control-label col-sm-3"><?php _e('Code', 'rrhh'); ?></label>
				<div class="col-sm-3"><p class="form-control-static"><?php print $obj->code; ?></p></div>
				<label class="control-label col-sm-2"><?php _e('Announcement', 'rrhh'); ?></label>
				<div class="col-sm-4"><p class="form-control-static"><?php print $obj->name; ?></p></div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-12" style="text-align:left;">
					<?php _e('Seleccione el tipo de experiencia que aplica al cargo', 'rrhh'); ?>
				</label>
				<div class="col-sm-12 rrhh-table">
					<div class="head" style="padding:0 14px;">
						<div class="row">
							<div class="col-sm-2 text-center"><b><?php _e('General', 'rrhh'); ?></b></div>
							<div class="col-sm-2 text-center"><b><?php _e('Specific', 'rrhh'); ?></b></div>
							<div class="col-sm-4 text-center"><b><?php _e('Company', 'rrhh'); ?></b></div>
							<div class="col-sm-4 text-center"><b><?php _e('Position', 'rrhh'); ?></b></div>
						</div>
					</div>
					<div class="body" style="width:100%;height:180px;overflow:auto;border:1px solid #dedede;padding:0 14px;">
						<?php foreach($person->GetWorkExperience() as $exp): ?>
						<div class="row experience-row">
							<div class="col-sm-2 text-center">
								<input type="checkbox" name="general_experience[]" value="<?php print $exp->id; ?>" />
							</div>
							<div class="col-sm-2 text-center">
								<input type="checkbox" name="specific_experience[]" value="<?php print $exp->id; ?>" />
							</div>
							<div class="col-sm-4 text-center"><?php print $exp->company; ?></div>
							<div class="col-sm-4 text-center"><?php print $exp->position; ?></div>
						</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="label-control col-sm-3"><?php _e('Salary Pretension', 'rrhh'); ?></label>
				<div class="col-sm-9"><input type="text" name="salary_pretension" value="" class="form-control" /></div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-3"><?php _e('Inmediate availability', 'rrhh'); ?></label>
				<label class="control-label col-sm-2"><input type="radio" name="inmediate_availability" value="yes" /><?php _e('Yes', 'rrhh'); ?></label>
				<label class="control-label col-sm-2"><input type="radio" name="inmediate_availability" value="no" /><?php _e('No', 'rrhh'); ?></label>
				<label class="control-label col-sm-2"><?php _e('Days', 'rrhh'); ?></label>
				<div class="col-sm-3"><input type="number" min="1" name="days" value="1" class="form-control" /></div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-7"><?php _e('TIENE PARENTESCO CON ALGÚN FUNCIONARIO(A) DE LA EMPRESA?', 'rrhh'); ?></label>
				<label class="control-label col-sm-2"><input type="radio" name="employee_relationship" value="1" /><?php _e('Yes', 'rrhh'); ?></label>
				<label class="control-label col-sm-2"><input type="radio" name="employee_relationship" value="0" /><?php _e('No', 'rrhh'); ?></label>
			</div>
            <div class="form-group">
                <label class="control-label col-sm-3"><?php _e('Ingrese el nombre de la persona:', 'rrhh'); ?></label>
                <div class="col-sm-9"><input type="text" name="employee_name" value="" class="form-control" /></div>
            </div>
			<div class="form-group">
				<label class="control-label col-sm-12" style="text-align:left;">
					<input type="checkbox" name="i_agree" value="1" />
					TODA LA INFORMACIÓN DECLARADA  ES VERDADERA Y  ESTOY EN CONDICIONES DE SUSTENTARLA A REQUERIMIENTO DE LA EMPRESA. 
					LA POSTULACI&Oacute;N ES UNA DECLARACIÓN JURADA, SEGUROS Y REASEGUROS UNIVIDA SA. SE RESERVA EL DERECHO DE LA VERIFICACIÓN DE LA 
					INFORMACIÓN QUE CONTIENE.
				</label>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-danger" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span><?php _e('Close', 'rrhh');?></button>
        	<button type="submit" class="btn btn-warning"><span class="glyphicon glyphicon-ok"></span> <?php _e('Apply', 'rrhh'); ?></button>
		</div>
	</form>
</div>