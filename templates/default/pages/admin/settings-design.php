<?php
?>
<br/>
<?php if( sb_get_current_user()->can('manage_design_settings') ): ?>
<div id="design" class="tab-pane">
	<div class="row">
		<div class="col-md-6">
			<div class="form-group form-inline">
				<label class="has-popover" data-content="<?php print SBText::_('TPL_SETTINGS_LABEL_BTN_WIDTH'); ?>">
					<?php print SB_Text::_('Ancho Minimo de Botones:')?></label>
				<div class="input-group">
					<input type="number" name="settings[BTN_WIDTH]" value="<?php print !(int)@$settings->BTN_WIDTH ? 200 : (int)$settings->BTN_WIDTH; ?>" 
						min="0" class="form-control" style="width:90px;" />
					<div class="input-group-addon">px</div>
				</div>
			</div>
			<div class="form-group">
				<div>
					<label class="has-popover" data-content="<?php print SBText::_('TPL_SETTINGS_LABEL_LOGO'); ?>">
						<?php print SB_Text::_('Logo:'); ?></label>
				</div>
				<div>
					<img id="site-logo" src="<?php print $site_logo_url ? $site_logo_url : ''; ?>" alt="" class="img-thumbnail" style="<?php print $site_logo_url ? '' : 'display:none;'; ?>" />
				</div>
				<div>
					<span id="select-image" class="btn btn-primary"><?php print SB_Text::_('Subir imagen'); ?></span><br/>
					<div id="uploading" style="display:none;">
						<img src="<?php print BASEURL; ?>/js/fineuploader/loading.gif" alt=""  /><?php print SB_Text::_('Subiendo imagen'); ?>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="has-popover" data-content="<?php print SBText::_('TPL_SETTINGS_LABEL_LOGO_ALIGN'); ?>">
					<?php print SB_Text::_('Alineacion Logo:')?>
				</label>
				<select name="settings[LOGO_ALIGN]">
					<option value="left" <?php print @$settings->LOGO_ALIGN == 'left' ? 'selected' : ''; ?>><?php print SB_Text::_('Izquierda'); ?></option>
					<option value="right" <?php print @$settings->LOGO_ALIGN == 'right' ? 'selected' : ''; ?>><?php print SB_Text::_('Derecha'); ?></option>
					<option value="center" <?php print @$settings->LOGO_ALIGN == 'center' ? 'selected' : ''; ?>><?php print SB_Text::_('Centro'); ?></option>
				</select>
			</div>
			<div class="form-group row">
				<div class="col-md-4">
					<label class="has-popover" data-content="<?php print SBText::_('TPL_SETTINGS_LABEL_HEADER_BG_COLOR'); ?>">
						<?php print SB_Text::_('Color Cabecera:'); ?></label>
					<input type="text" name="settings[HEADER_BG_COLOR]" value="<?php print @$settings->HEADER_BG_COLOR; ?>" 
						class="form-control colorpicker" style="width:100px;" />
				</div>
			</div>
			<div class="control-group">
				<label class="has-popover" data-content="<?php print SBText::_('TPL_SETTINGS_LABEL_HEADER_IMG'); ?>">
					<?php print SB_Text::_('Imagen de Cabecera:'); ?></label><br/>
				<img id="header-image" src="<?php print $header_image_url ? $header_image_url : ''; ?>" alt="" class="img-thumbnail" style="<?php print $header_image_url ? '' : 'display:none;'; ?>" />
				<div class="btn-group">
					<span id="select-header-image" class="btn btn-primary"><?php print SB_Text::_('Subir imagen'); ?></span>
					<a href="javascript:;" id="remove-header-image" class="btn btn-danger"><?php print SB_Text::_('Quitar imagen'); ?></a>
				</div>
				<div id="uploading-header-image" style="display:none;">
					<img src="<?php print BASEURL; ?>/js/fineuploader/loading.gif" alt=""  /><?php print SB_Text::_('Subiendo imagen'); ?>
				</div>
			</div>
			<div class="control-group row">
				<div class="col-md-4">
					<label class="has-popover" data-content="<?php print SBText::_('TPL_SETTINGS_LABEL_BG_COLOR'); ?>">
						<?php print SB_Text::_('Color de Fondo:'); ?></label>
					<input type="text" name="settings[BG_COLOR]" value="<?php print @$settings->BG_COLOR; ?>" class="form-control colorpicker"
						style="width:100px;" />
				</div>
			</div>
			<div class="row">
				<div class="control-group">
					<div class="col-md-12">
						<label class="has-popover" data-content="<?php print SBText::_('TPL_SETTINGS_LABEL_BG_IMAGE'); ?>">
							<?php print SB_Text::_('Imagen de Fondo:'); ?>
						</label>
					</div>
					<div class="col-md-4">
						<img id="bg-image" src="<?php print $bg_url ? $bg_url : ''; ?>" alt="" class="img-thumbnail" style="<?php print $bg_url ? '' : 'display:none;'; ?>" />
						<div id="uploading-bg" class="hidden">
							<img src="<?php print BASEURL; ?>/js/fineuploader/loading.gif" alt=""  /><?php print SB_Text::_('Subiendo imagen'); ?>
						</div>
						<div class="text-center">
							<select id="bg-design" name="settings[BG_DESIGN]">
								<option value="repeat" <?php print @$settings->BG_DESIGN == 'repeat' ? 'selected' : ''; ?>>Mosaico</option>
								<option value="expand" <?php print @$settings->BG_DESIGN == 'expand' ? 'selected' : ''; ?>>Extrusion</option>
							</select>
						</div>
					</div>
					<div class="col-md-8">
						<div class="btn-group">
							<span id="select-bg-image" class="btn btn-primary btn-xs"><?php print SB_Text::_('Subir imagen'); ?></span>
							<a href="#" id="remove-bg-image" class="btn btn-danger btn-xs"><?php print SB_Text::_('Quitar Imagen'); ?></a>
							<a href="javascript:;" id="open-uploader" class="btn btn-primary btn-xs"><?php print SB_Text::_('Seleccionar Imagen'); ?></a>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="has-popover" data-content="<?php print SBText::_('TPL_SETTINGS_LABEL_USER_WELCOME_MSG'); ?>">
					<?php print SB_Text::_('Mensaje Panel de Usuario'); ?></label><br/>
				<em>
					Puedes incluir el Shortcode:<br/>
					[user_firstname]: Nombre del usuario<br/>
					[user_msg_home]: Mensaje Shortcode
				</em>
				<textarea id="user_welcome_msg" name="settings[USER_WELCOME_MSG]" style="width:500px;height:150px;"><?php print stripslashes(@$settings->USER_WELCOME_MSG); ?></textarea>
			</div>
			<div class="form-group checkbox">
				<label class="has-popover" data-content="<?php print SBText::_('TPL_SETTINGS_LABEL_BG_ANIMATED'); ?>">
					<input type="checkbox" name="settings[BG_ANIMATED]" value="1" <?php print @$settings->BG_ANIMATED == 1 ? 'checked' : ''; ?> />
					<?php print SBText::_('Usar fondo animado:'); ?>
				</label>
				<input type="text" name="settings[BG_ANIMATED_URL]" value="<?php print @$settings->BG_ANIMATED_URL; ?>" class="form-control" 
					style="width:350px;" maxlength="40" />
			</div>
		</div>
		<div class="col-md-6">
			
		</div>
	</div>
</div><!-- end id="design" -->
<?php endif; ?>
<?php if( sb_get_current_user()->can('manage_limit_settings') ): ?>
<div id="user-settings" class="tab-pane">
	<div class="row">
		<div class="col-md-3 form-inline">
			<div class="control-group">
				<label class="has-popover" data-content="<?php print SBText::_('TPL_SETTINGS_LABEL_MAX_USERS'); ?>">
					<?php print SB_Text::_('Cantidad maxima de usuarios:')?></label>
				<input type="number" name="max_users" value="<?php print (int)sb_get_parameter('max_users', 0); ?>" class="form-control" 
						style="width:80px;" />
			</div>
		</div>
	</div><br/>
	<div class="form-group row">
		<div class="col-md-5">
			<label class="has-popover" data-content="<?php print SBText::_('TPL_SETTINGS_LABEL_FOOTER_TEXT'); ?>">
				<?php print SB_Text::_('Pie de Pagina'); ?>
			</label><br/>
			<em>Puedes incluir el Shortcode [date_year]</em>
			<textarea name="settings[FOOTER_TEXT]" class="form-control"><?php print @$settings->FOOTER_TEXT; ?></textarea>
		</div>
	</div>
	<div class="form-group row">
		<div class="col-md-5">
			<label class="has-popover" data-content="<?php print SBText::_('TPL_SETTINGS_LABEL_FOOTER_LINK'); ?>">
				<?php print SB_Text::_('Link Pie de pagina:'); ?></label>
			<input type="text" name="settings[FOOTER_LINK]" value="<?php print @$settings->FOOTER_LINK; ?>" class="form-control" />
		</div>
	</div>
</div><!-- end id="user-settings" -->
<?php endif; ?>
<?php if( sb_get_current_user()->role_id === 0 ): ?>
<div id="backend-menus" class="tab-pane">
	<?php for($i = 1; $i <= 9; $i++): ?>
	<?php if( $i == 8 ): ?>
	<h3><?php printf(SBText::_("Menu %d (Autoresponder)"), $i); ?></h3>
	<?php elseif( $i == 9 ): ?>
	<h3><?php printf(SBText::_("Menu %d (Galeria)"), $i); ?></h3>
	<?php else:?>
	<h3><?php printf(SBText::_("Menu %d"), $i); ?></h3>
	<?php endif; ?>
	
	<div class="form-froup">
		<label class="has-popover" data-content="<?php print SBText::_('TPL_SETTINGS_LABEL_MENU_SHOW'); ?>">
			<?php print SBText::_('Mostrar menu:'); ?>
			<input type="checkbox" name="backend_menus[menu_<?php print $i; ?>][show]" value="1" <?php print @$backend_menus->{'menu_'.$i}->show == '1' ? 'checked' : ''; ?> />
		</label>
	</div>
	<div class="form-group row">
		<div class="col-md-3">
			<label class="has-popover" data-content="<?php print SBText::_('TPL_SETTINGS_LABEL_MENU_LABEL'); ?>">
				<?php print SB_Text::_('Nombre del Menu:'); ?></label>
			<input type="text" name="backend_menus[menu_<?php print $i; ?>][label]" value="<?php print @$backend_menus->{'menu_'.$i}->label; ?>" 
				class="form-control" maxlength="80" />
		</div>
	</div>
	<div class="control-group">
		<label class="has-popover" data-content="<?php print SBText::_('TPL_SETTINGS_LABEL_MENU_LINK'); ?>">
			<?php print SB_Text::_('Enlace del Menu:'); ?></label>
		<input type="text" name="backend_menus[menu_<?php print $i; ?>][link]" value="<?php print @$backend_menus->{'menu_'.$i}->link; ?>" class="form-control" />
	</div>
	<?php if( $i == 8 ): ?>
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<label class="has-popover" data-content="<?php print SBText::_('TPL_SETTINGS_LABEL_MENU_USERNAME'); ?>">
					<?php print SBText::_('Usuario:'); ?></label>
				<input type="text" name="backend_menus[menu_<?php print $i?>][username]" value="<?php print @$backend_menus->{'menu_'.$i}->username; ?>" class="form-control" />
			</div>
			<div class="form-group">
				<label class="has-popover" data-content="<?php print SBText::_('TPL_SETTINGS_LABEL_MENU_PASS'); ?>">
					<?php print SBText::_('Contraseña:'); ?></label>
				<input type="password" name="backend_menus[menu_<?php print $i?>][password]" value="<?php print @$backend_menus->{'menu_'.$i}->password; ?>" class="form-control" />
			</div>
		</div>
	</div>
	<?php endif; ?>
	<?php if( $i == 9 ): ?>
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<label class="has-popover" data-content="<?php print SBText::_('TPL_SETTINGS_LABEL_MENU_USERNAME1'); ?>">
					<?php print SBText::_('Usuario:'); ?></label>
				<input type="text" name="backend_menus[menu_<?php print $i?>][username]" value="<?php print @$backend_menus->{'menu_'.$i}->username; ?>" class="form-control" />
			</div>
			<div class="form-group">
				<label class="has-popover" data-content="<?php print SBText::_('TPL_SETTINGS_LABEL_MENU_PASS1'); ?>">
					<?php print SBText::_('Contraseña:'); ?></label>
				<input type="password" name="backend_menus[menu_<?php print $i?>][password]" value="<?php print @$backend_menus->{'menu_'.$i}->password; ?>" class="form-control" />
			</div>
		</div>
	</div>
	<?php endif; ?>
	<?php endfor; ?>
</div><!-- end id="backend-menus" -->
<div id="user-scripts" class="tab-pane">
	<div class="control-group">
		<label class="has-popover" data-content="<?php print SBText::_('TPL_SETTINGS_LABEL_SCRIPT_CHAT'); ?>">
			<?php print SBText::_('Script Chat:'); ?>
		</label>
		<div class="checkbox">
			<label class="has-popover" data-content="<?php print SBText::_('TPL_SETTINGS_LABEL_SCRIPT_CHAT_IN_HOME'); ?>">
				<input type="checkbox" name="user_scripts[CHAT_IN_HOME]" value="1" <?php print @$user_scripts->CHAT_IN_HOME ? 'checked' : ''; ?> />
				<?php print SBText::_('Usar este Script solo en la pantalla inicial'); ?>
			</label>
		</div>
		<textarea rows="7" cols="" name="user_scripts[CHAT]" class="form-control"><?php print stripslashes(@$user_scripts->CHAT); ?></textarea>
	</div>
	<div class="control-group">
		<label class="has-popover" data-content="<?php print SBText::_('TPL_SETTINGS_LABEL_SCRIPT_COM'); ?>">
			<?php print SBText::_('Script Comunicaciones:'); ?>
		</label><br/>
		<label class="has-popover" data-content="<?php print SBText::_('TPL_SETTINGS_LABEL_SCRIPT_COM_IN_HOME'); ?>">
			<input type="checkbox" name="user_scripts[COM_IN_HOME]" value="1" <?php print @$user_scripts->COM_IN_HOME ? 'checked' : ''; ?> />
			<?php print SBText::_('Usar este Script solo en la pantalla inicial'); ?>
		</label>
		<textarea rows="7" cols="" name="user_scripts[COM]" class="form-control"><?php print stripslashes(@$user_scripts->COM); ?></textarea>
	</div>
	<div class="control-group">
		<label class="has-popover" data-content="<?php print SBText::_('TPL_SETTINGS_LABEL_SCRIPT_GENERAL'); ?>">
			<?php print SBText::_('Script General:'); ?>
		</label><br />
		<label class="has-popover" data-content="<?php print SBText::_('TPL_SETTINGS_LABEL_SCRIPT_GENERAL_IN_HOME'); ?>">
			<input type="checkbox" name="user_scripts[GENERAL_IN_HOME]" value="1" <?php print @$user_scripts->GENERAL_IN_HOME ? 'checked' : ''; ?> />
			<?php print SBText::_('Usar este Script solo en la pantalla inicial'); ?>
		</label>
		<textarea rows="7" cols="" name="user_scripts[GENERAL]" class="form-control"><?php print stripslashes(@$user_scripts->GENERAL); ?></textarea>
	</div>
</div><!-- end id="user-scripts" -->
<?php endif; ?>
<script src="<?php print BASEURL; ?>/js/nicEdit/nicEdit.js"></script>
<script>
jQuery(function()
{
	new nicEditor({iconsPath : '<?php print BASEURL; ?>/js/nicEdit/nicEditorIcons.gif', fullPanel:true}).panelInstance('user_welcome_msg');
	jQuery('.colorpicker').colorpicker({ /*options...*/ });
	var uploader = new qq.FineUploaderBasic({
		//element: document.getElementById("uploader"),
		//template: 'qq-template-gallery',
		button: document.getElementById('select-image'),
		request: 
		{
			endpoint: '<?php print $upload_endpoint; ?>'
		},
		validation: {
			allowedExtensions: ['jpeg', 'jpg', 'gif', 'png']
		},
		callbacks: 
		{
			onSubmit: function(id, fileName) 
			{
				//$messages.append('<div id="file-' + id + '" class="alert" style="margin: 20px 0 0"></div>');
			},
			onUpload: function(id, fileName) 
			{
				jQuery('#uploading').css('display', 'block');
			},
			onProgress: function(id, fileName, loaded, total) 
			{
			},
			onComplete: function(id, fileName, responseJSON) 
			{
				jQuery('#uploading').css('display', 'none');
				if (responseJSON.success) 
				{
					jQuery('#site-logo').attr('src', responseJSON.image_url).css('display', 'inline');
	            } 
	            else 
				{
					alert(responseJSON.error);
	            }
			}
		}
	});
	window.bg_uploader = new qq.FineUploaderBasic({
		//element: document.getElementById("uploader"),
		button: document.getElementById('select-bg-image'),
		request: 
		{
			endpoint: '<?php print $upload_bg_endpoint; ?>'
		},
		validation: {
			allowedExtensions: ['jpeg', 'jpg', 'gif', 'png']
		},
		callbacks: 
		{
			onSubmit: function(id, fileName) 
			{
				//$messages.append('<div id="file-' + id + '" class="alert" style="margin: 20px 0 0"></div>');
			},
			onUpload: function(id, fileName) 
			{
				jQuery('#uploading-bg').css('display', 'block');
			},
			onProgress: function(id, fileName, loaded, total) 
			{
			},
			onComplete: function(id, fileName, responseJSON) 
			{
				jQuery('#uploading-bg').css('display', 'none');
				if (responseJSON.success) 
				{
					jQuery('#bg-image').attr('src', responseJSON.image_url).css('display', 'inline');
	            } 
	            else 
				{
					alert(responseJSON.error);
	            }
			}
		}
	});
	window.header_uploader = new qq.FineUploaderBasic({
		//element: document.getElementById("uploader"),
		button: document.getElementById('select-header-image'),
		request: 
		{
			endpoint: '<?php print $upload_header_endpoint; ?>'
		},
		validation: {
			allowedExtensions: ['jpeg', 'jpg', 'gif', 'png']
		},
		callbacks: 
		{
			onSubmit: function(id, fileName) 
			{
				//$messages.append('<div id="file-' + id + '" class="alert" style="margin: 20px 0 0"></div>');
			},
			onUpload: function(id, fileName) 
			{
				jQuery('#uploading-header-image').css('display', 'block');
			},
			onProgress: function(id, fileName, loaded, total) 
			{
			},
			onComplete: function(id, fileName, responseJSON) 
			{
				jQuery('#uploading-header-image').css('display', 'none');
				if (responseJSON.success) 
				{
					jQuery('#header-image').attr('src', responseJSON.image_url).css('display', 'inline');
	            } 
	            else 
				{
					alert(responseJSON.error);
	            }
			}
		}
	});
	window.bg_uploader = new qq.FineUploaderBasic({
		//element: document.getElementById("uploader"),
		button: document.getElementById('select-bg-image'),
		request: 
		{
			endpoint: '<?php print $upload_bg_endpoint; ?>'
		},
		validation: {
			allowedExtensions: ['jpeg', 'jpg', 'gif', 'png']
		},
		callbacks: 
		{
			onSubmit: function(id, fileName) 
			{
				//$messages.append('<div id="file-' + id + '" class="alert" style="margin: 20px 0 0"></div>');
			},
			onUpload: function(id, fileName) 
			{
				jQuery('#uploading-bg').css('display', 'block');
			},
			onProgress: function(id, fileName, loaded, total) 
			{
			},
			onComplete: function(id, fileName, responseJSON) 
			{
				jQuery('#uploading-bg').css('display', 'none');
				if (responseJSON.success) 
				{
					jQuery('#bg-image').attr('src', responseJSON.image_url).css('display', 'inline');
	            } 
	            else 
				{
					alert(responseJSON.error);
	            }
			}
		}
	});
	jQuery('input[name=qqfile]').attr('title', '<?php print SB_Text::_('Sube una imagen de tu equipo')?>');
	jQuery('#remove-bg-image').click(function()
	{
		jQuery.get('index.php?task=remove_bg_image', function(res)
		{
			jQuery('#bg-image').css('display', 'none');
		});
		return false;
	});
	jQuery('#remove-header-image').click(function()
	{
		jQuery.get('index.php?task=remove_header_image', function(res)
		{
			jQuery('#header-image').css('display', 'none');
		});
		return false;
	});
	//jQuery();
	jQuery('#open-uploader').click(function()
	{
		jQuery('#uploader-iframe').get(0).contentWindow.location.reload();
		//data-toggle="modal" data-target="#uploader-dialog"
		jQuery('#uploader-dialog').modal('show');
	});
});
</script>
<!-- Modal -->
<div class="modal fade" id="uploader-dialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        		<h4 class="modal-title" id="myModalLabel"><?php print SB_Text::_('Imagenes de Fondo'); ?></h4>
      		</div>
	      	<div class="modal-body">
	      		<iframe id="uploader-iframe" src="<?php print SB_Route::_('index.php?task=get_bg_images'); ?>" style="width:100%;height:400px;" frameborder="0"></iframe>
	      	</div><!-- end class="modal-body" -->
      		<div class="modal-footer">
        		<!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>  -->
        		<button type="button" class="btn btn-primary"><?php print SBText::_('Grabar Cambios');?></button>
      		</div>
    	</div>
	</div>
</div>