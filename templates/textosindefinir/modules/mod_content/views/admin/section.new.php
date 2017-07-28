<?php
?>
<div class="wrap">
	<h1><?php print isset($section) ? 'Editar Categoria' : 'Nueva Categoria'; ?></h1>
	<form action="" method="post">
		<input type="hidden" name="mod" value="content" />
		<input type="hidden" name="task" value="section.save" />
		<?php if( isset($section) ): ?>
		<input type="hidden" name="section_id" value="<?php print $section->section_id; ?>" />
		<?php endif; ?>
		<div class="col-md-6">
			<div class="form-group">
				<label class="has-popover" data-content="<?php print SBText::_('SECTION_TITLE'); ?>">
					<?php print SB_Text::_('Nombre', 'content'); ?>
				</label>
				<input type="text" name="section_name" value="<?php print SB_Request::getString('section_name', isset($section) ? $section->name : ''); ?>" 
					class="form-control" maxlength="40" />
			</div>
			<div class="form-group">
				<label class="has-popover" data-content="<?php print SBText::_('SECTION_DESCRIPTION'); ?>">
					<?php print SB_Text::_('Descripcion', 'content'); ?>
				</label>
				<textarea id="description" name="description" class="form-control"><?php print SB_Request::getString('description', isset($section) ? $section->description : ''); ?></textarea>

			</div>
			<?php /*
			<div class="form-group">
				<label class="has-popover" data-content="<?php print SBText::_('SECTION_PARENT'); ?>">
					<?php print SB_Text::_('Seccion Padre:', 'content'); ?></label>
				<?php print sb_sections_dropdown(array('id' => 'parent_id', 'selected' => isset($section) ? $section->parent_id : -1)); ?>
				<?php //print SB_Request::getInt('parent_id', isset($section) ? $section->parent_id : -1); ?>
			</div>
			*/?>
			<div class="form-group" style="display:none;">
				<label><?php _e('Language', 'content'); ?></label>
				<select name="lang" class="form-control">
	  				<?php foreach(SB_Factory::getApplication()->GetLanguages() as $code => $lang):?>
	  				<option value="<?php print $code; ?>" <?php print @LANGUAGE == $code ? 'selected' : ''; ?>>
	  					<?php print $lang; ?>
	  				</option>
	  				<?php endforeach; ?>
	  			</select>
			</div>
			<?php SB_Module::do_action('section_fields', isset($section) ? $section : null); ?>
			<p>
				<a class="btn btn-secondary has-popover" href="<?php print SB_Route::_('index.php?mod=content&view=section.default'); ?>"
					data-content="<?php print SBText::_('SECTION_BUTTON_CANCEL'); ?>">
					<?php print SB_Text::_('Cancelar', 'content'); ?></a>
				<button type="submit" class="btn btn-secondary has-popover" data-content="<?php print SBText::_('SECTION_BUTTON_SAVE'); ?>">
					<?php print SB_Text::_('Guardar', 'content'); ?></button>
			</p>
		</div>
	</form>
	<script>
	jQuery(function()
	{
		jQuery('.fg_color_picker').ColorPicker({
			onChange: function (hsb, hex, rgb) 
			{
				jQuery('#section-fg-color').val('#' + hex);
				jQuery('.fg_color_picker i').css('backgroundColor', '#' + hex);
			}
		});
		jQuery('.bg_color_picker').ColorPicker({
			onChange: function (hsb, hex, rgb) 
			{
				jQuery('#section-color-input').val('#' + hex);
				jQuery('.bg_color_picker i').css('backgroundColor', '#' + hex);
			}
		});
		jQuery('#remove-button-image').click(function()
		{
			var params = 'mod=content&task=section.remove_button_image';
			<?php if( isset($section) ):  ?>
			params += '&id=<?php print $section->section_id; ?>';
			<?php else: ?>
			params += '&id=temp';
			<?php endif; ?>
			jQuery.post('index.php', params, function(res){});
			jQuery('#button-image').css('display', 'none');
			return false;
		});
		var button_uploader = new qq.FineUploaderBasic({
			//element: document.getElementById("uploader"),
			//template: 'qq-template-gallery',
			button: document.getElementById('select-button-image'),
			request: {
				endpoint: '<?php print SB_Route::_('index.php?mod=content&task=section.upload_button_image' . (isset($section) ? '&id='.$section->section_id : '')); ?>'
			},
			validation: {
				allowedExtensions: ['jpeg', 'jpg', 'gif', 'png']
			},
			callbacks: 
			{
				onUpload: function(id, fileName) 
				{
					jQuery('#uploading-btn-img').css('display', 'block');
				},
				onProgress: function(id, fileName, loaded, total) 
				{
					
				},
				onComplete: function(id, fileName, responseJSON) 
				{
					jQuery('#uploading-btn-img').css('display', 'none');
					if (responseJSON.success) 
					{
						jQuery('#button-image').css('display', 'block');
						jQuery('#button-image img:first').attr('src', responseJSON.image_url).css('display', 'inline');
						jQuery('#remove-button-image').css('display', 'inline');
		            } 
		            else 
					{
						alert(responseJSON.error);
		            }
				}
			}
		});
		jQuery('input[name=qqfile]').attr('title', 'Sube una imagen de tu equipo');
	});
	</script>
</div>
<link rel="stylesheet" href="<?php print BASEURL; ?>/js/colorpicker/css/colorpicker.css" />
<script src="<?php print BASEURL; ?>/js/colorpicker/js/colorpicker.js"></script>