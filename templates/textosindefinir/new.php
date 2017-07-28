<?php lt_get_header(); ?>
		<?php lt_get_sidebar(); ?>
		<div id="content" class="col-md-9">
			<div id="new-script-container">
				<div class="wrap">
					<h2 class="title">CREA TU GUION</h2>
					<div class="body">
						<form id="form-new" action="" method="post" class="form-horizontal form-group-sm">
							<input type="hidden" name="mod" value="scripts" />
							<input type="hidden" name="task" value="new" />
							<input type="hidden" id="script_id" name="script_id" value="0" />
							<div class="form-group">
								<label class="control-label col-sm-3">Nombre Guion:</label>
								<div class="col-sm-7">
									<input type="text" name="script_name" value="" class="form-control" placeholder="El nombre para el guion" />
								</div>
								
							</div>
							<div class="form-group">
								
								<label class="control-label col-sm-3">Categoria:</label>
								<div class="col-sm-7">
									<?php print sb_sections_dropdown(); ?>
								</div>
								
							</div>
							<div class="form-group">
								
								<label class="control-label col-sm-3">Plantilla:</label>
								<div class="col-sm-7">
									<div id="scripts"></div>
								</div>
								
							</div>
							<div class="clear-fix"></div>
							<div class="form-group">
								<div class="col-sm-12">
									<button class="btn btn-info pull-right">Comenzar</button>
								</div>
							</div>
						</form>
						<div id="script-info"></div>
					</div>
				</div>
			</div><!-- end id="new-script-container" -->
		</div><!-- end id="content" -->
		<script>
		var available_scripts = {};
		jQuery(function()
		{
			jQuery('#section_id').change(function()
			{
				available_scripts = {}
				jQuery('#script_id').val(0);
				jQuery('#script-info').css('display', 'none').html('');
				var scripts = jQuery('#scripts');
				scripts.html('');
				
				if( parseInt(this.value) <= 0 )
				{
					return true;
				}
				
				var params = 'mod=scripts&task=get_scripts&cat_id='+this.value;
				jQuery.post('index.php',params, function(res)
				{
					if( res.status == 'ok' )
					{
						jQuery.each(res.scripts, function(i, script)
						{
							available_scripts['script_'+ script.content_id] = script;
							scripts.append('<a href="javascript:;" class="template" data-id="'+script.content_id+'"><span class="number">'+(i + 1)+'</span></a>');
						});
					}
				});
			});
			jQuery(document).on('click', '.template', function(e)
			{
				jQuery('.template').removeClass('selected');
				jQuery(this).addClass('selected');
				jQuery('#script_id').val(this.dataset.id);
				return false;
			});
			jQuery(document).on('mouseenter mouseleave', '.template', function(e)
			{
				if( e.type == 'mouseenter' )
				{
					var id = this.dataset.id;
					jQuery('#script-info').css('display', 'block').html(available_scripts['script_'+id]._overview);
				}
				else
				{
					jQuery('#script-info').css('display', 'none').html('');
				}
			});
			jQuery('#form-new').submit(function()
			{
				if( this.script_name.value.trim().length <= 0 )
				{
					alert('Debe ingresar un nombre para el guion');
					return false;
				}
				if( parseInt(this.section_id.value) <= 0 )
				{
					alert('Debe seleccionar una categoria');
					return false;
				}
				if( parseInt(this.script_id.value) <= 0 )
				{
					alert('Debe seleccionar una plantilla');
					return false;
				}
				return true;
			});
		});
		</script>
<?php lt_get_footer(); ?>