<?php
$back_link = $type != 'page' ? SB_Route::_('index.php?mod=content&type='.$type) : SB_Route::_('index.php?mod=content');
$input_class = ( @$features['text_color'] || @$features['background_text_color'] || @$features['view_button']) ? 'input-group' : '';
if( !isset($content) )
	$input_class = '';

?>
<div class="wrap">
	<h2 id="page-title">
		<div class="container-fluid">
			<div class="row">
				<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"><?php print $title; ?></div>
				<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
					<div class="page-buttons">
						<a href="javascript:;" class="btn btn-success" onclick="jQuery('#form-content').submit();">
							<?php _e('Save', 'content'); ?>
						</a>
					</div>
				</div>
		</div>
	</h2>
	<form id="form-content" action="" method="post">
		<input type="hidden" name="mod" value="content" />
		<input type="hidden" name="task" value="save" />
		<input type="hidden" name="type" value="<?php print $type; ?>" />
		<?php if( isset($content) ): ?>
		<input type="hidden" name="article_id" value="<?php print $content->content_id; ?>" />
		<?php endif; ?>
		<div class="row">
			<div id="article" class="col-md-9">
				<div class="row">
					<div class="col-md-8">
						<div class="form-group">
							<label class="has-popover" data-content="<?php print SBText::_('CONTENT_TITLE'); ?>">
								<?php print SB_Text::_('Title:', 'content'); ?>
							</label>
							<div class="<?php print $input_class; ?> article-color">
								<input type="text" name="title" value="<?php print SB_Request::getString('title', isset($content) ? $content->title : ''); ?>" 
										class="form-control" />
								<?php if( @$features['text_color'] ): ?>
								<input type="hidden" id="article-fg-color" name="btn_fg_color" value="<?php print isset($content) && $content->_btn_fg_color ? $content->_btn_fg_color : '#fff'; ?>" class="form-control hidden" />
								<span class="input-group-addon fg_color_picker" title="<?php _e('Color de Texto', 'content'); ?>">
							    	<i style="display:inline-block;width:16px;height:16px;cursor:pointer;background-color:<?php print isset($content) && $content->_btn_fg_color ? $content->_btn_fg_color : '#fff'; ?>;">&nbsp;</i>
							    </span>
								<?php endif; ?>
								<?php if( @$features['background_text_color'] ): ?>
								<input type="hidden" id="article-color-input" name="btn_bg_color" value="<?php print isset($content) && $content->_btn_bg_color ? $content->_btn_bg_color : '#0213cc'; ?>" class="form-control hidden" />
							    <span class="input-group-addon bg_color_picker" title="<?php _e('Color de Boton', 'content'); ?>">
							    	<i style="display:inline-block;width:16px;height:16px;cursor:pointer;background-color:<?php print isset($content) && $content->_btn_bg_color ? $content->_btn_bg_color : '#0213cc'; ?>;">&nbsp;</i>
							    </span>
								<?php endif; ?>
							    <?php if( isset($content) && @$features['view_button'] ): ?>
							    <a href="<?php print SB_Route::_('index.php?mod=content&view=article&id='.$content->content_id, 'frontend'); ?>" 
							    	class="input-group-addon" target="_blank"><?php _e('View', 'content'); ?>
							    </a>
							    <?php endif; ?>
							</div>
						</div>
					</div>
				</div>
				<?php if( isset($features['button']) && $features['button'] ): ?>
				<div class="form-group">
					<label class="has-popover" data-content="<?php print SBtext::_('CONTENT_LABEL_UPLOAD_IMG_CHECK', 'content'); ?>">
						<?php print SBText::_('Usar Imagen en lugar de Boton de texto', 'content'); ?>
						<input type="checkbox" name="user_button_instead" value="1" <?php print (isset($content) && $content->_user_button_instead == 1) ?  'checked' : ''; ?> />
					</label>
					<div>
						<span class="help-block">
							<?php print SBText::_('Subir Imagen para Boton: (Tamaño recomendado: 200x100 / 150x80)', 'content');?>
						</span>
						<span id="select-button-image" class="btn btn-primary">
							<?php print SBText::_('Subir imagen', 'content'); ?>
							<span id="uploading-btn-img" style="display:none;">
								<img src="<?php print BASEURL; ?>/js/fineuploader/loading.gif" alt=""  />
								<?php print SB_Text::_('Subiendo imagen...', 'content'); ?>
							</span>
						</span>
						<div id="button-image" style="<?php print (isset($content) && $content->_button_image) ? '' : 'display:none'; ?>">
							<img src="<?php print UPLOADS_URL; ?>/buttons/<?php print isset($content) ? $content->_button_image : ''; ?>" alt="" />
							<a href="javascript:;" id="remove-button-image" class="remove" <?php print (isset($content) && $content->_button_image) ? '' : 'style="display:none;"'; ?>>
								<img src="<?php print BASEURL ?>/images/close_window-48x48.png" alt="" title="<?php print SB_Text::_('Eliminar Banner', 'conent')?>" />
							</a>
						</div>
					</div>
				</div>
				<?php endif; ?>
				<?php if( isset($features['banner']) && $features['banner'] ): ?>
				<div id="article-banner">
					<label class="has-popover" data-content="<?php print SBText::_('CONTENT_BANNER'); ?>"><?php print SB_Text::_('Banner:', 'content'); ?></label>
					<span class="help-block">
						<?php print SB_Text::_('(Recommended Size: 1920x560 / 1920x300)'); ?>
					</span>
					<div>
						<span id="select-banner" class="btn btn-primary">
							<?php print SB_Text::_('Upload image', 'content'); ?>
						</span>
						<span id="uploading" style="display:none;">
							<img src="<?php print BASEURL; ?>/js/fineuploader/loading.gif" alt=""  /><?php print SB_Text::_('Subiendo imagen', 'content'); ?>
						</span>
					</div>
					<div id="the-banner">
						<img src="<?php print $image_url; ?>" alt="" class="img-thumbnail" <?php print !$image_url ? 'style="display:none;"' : ''; ?> />
						<a href="javascript:;" id="remove-banner" class="remove" <?php print !$image_url ? 'style="display:none;"' : ''; ?>>
							<img src="<?php print BASEURL ?>/images/close_window-48x48.png" alt="" title="<?php print SB_Text::_('Eliminar Banner', 'conent')?>" />
						</a>
					</div>
				</div>
				<?php endif; ?>
				<div class="form-group">
					<label class="has-popover" data-content="<?php print SBText::_('CONTENT_CONTENT'); ?>">
						<?php _e('Content:', 'content'); ?>
					</label>
				</div>
				<?php if( isset($features['btn_add_media']) && $features['btn_add_media'] ): ?>
				<div class="form-group">
					<a href="#" id="btn-add-media" class="btn btn-default btn-sm">
						<span class="glyphicon glyphicon-hdd"></span> <?php _e('Add Media', 'content'); ?>
					</a>
				</div>
				<?php endif; ?>
				<div class="form-group">
					<textarea id="content_area" name="content" class="form-control"><?php print SB_Request::getString('content', isset($content) ? stripslashes($content->content) : ''); ?></textarea>
				</div>
				<?php SB_Module::do_action('content_data_'.$type, isset($content) ? $content : null); ?>
				<?php SB_Module::do_action('content_data', isset($content) ? $content : null); ?>
				
			</div>
			<div id="sidebar" class="col-md-3">
				<div class="panel panel-default widget">
					<div class="panel-heading">
						<h2 class="panel-title"><?php print SB_Text::_('Options', 'content'); ?></h2>
					</div>
					<div class="panel-body">
						<div class="form-group">
							<label class="has-popover" data-content="<?php print SBText::_('CONTENT_STATUS'); ?>"><?php print SB_Text::_('Estado:', 'content'); ?></label>
							<select name="status" class="form-control">
								<option value="publish" <?php print (isset($content) && $content->status == 'publish') ? 'selected' : ''; ?>><?php print SB_Text::_('Publicado', 'content'); ?></option>
								<option value="draft" <?php print (isset($content) && $content->status == 'draft') ? 'selected' : ''; ?>><?php print SB_Text::_('Borrador', 'content'); ?></option>
							</select>
						</div>
						<div class="form-group">
							<label><?php _e('Language', 'content'); ?></label>
							<select name="lang" class="form-control">
				  				<?php foreach(SB_Factory::getApplication()->GetLanguages() as $code => $lang):?>
				  				<option value="<?php print $code; ?>" <?php print ((isset($content) && $content->lang_code == $code) || (!isset($content) && LANGUAGE == $code)) ? 'selected' : ''; ?>>
				  					<?php print $lang; ?>
				  				</option>
				  				<?php endforeach; ?>
				  			</select>
						</div>
						<?php if( isset($features['use_dates']) && $features['use_dates'] ): ?>
						<div class="form-group">
							<label class="has-popover" data-content="<?php print SBText::_('CONTENT_PUBLSIH_DATE'); ?>">
								<?php print SB_Text::_('Publish Date:', 'content'); ?>
							</label>
							<input type="text" name="publish_date" value="<?php print isset($content) ? sb_format_date($content->publish_date) : date(DATE_FORMAT);  ?>" class="form-control datepicker" />
						</div>
						<div class="form-group">
							<label class="has-popover" data-content="<?php print SBText::_('CONTENT_EXPIRES_DATE'); ?>">
								<?php print SB_Text::_('Expiration Date:', 'content'); ?>
							</label>
							<input type="text" name="end_date" value="<?php print isset($content) ? 
																			sb_format_date($content->end_date) : 
																			date(DATE_FORMAT, strtotime((date('Y')+35).'-01-01'));  ?>" class="form-control datepicker" />
						</div>
						<?php endif; ?>
						<?php if( isset($features['calculated_dates']) && $features['calculated_dates'] ): ?>
						<div class="row">
							<div class="col-md-12 form-group">
								<label class="has-popover" data-content="<?php print SBText::_('CONTENT_PUBLISH_C_DATE'); ?>"><?php print SB_Text::_('Fecha de Publicacion Calculada:', 'content'); ?></label><br/>
								<div class="row col-md-5">
									<input type="number" min="0" name="calculated_date" value="<?php print isset($content) ? (int)$content->_calculated_date : 0;  ?>" class="form-control" />
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 form-group">
								<label class="has-popover" data-content="<?php print SBText::_('CONTENT_EXPIRES_C_DATE'); ?>"><?php print SB_Text::_('Fecha Caducidad Calculada:', 'content'); ?></label><br/>
								<div class="row col-md-5">
									<input type="number" min="0" name="end_calculated_date" value="<?php print isset($content) ? (int)$content->_end_calculated_date : 0;  ?>" class="form-control" />
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="has-popover" data-content="<?php print SBText::_('CONTENT_USE_CDATES'); ?>">
								<input type="checkbox" name="use_calculated" value="1" <?php print (isset($content) && $content->_use_calculated) ? 'checked' : '';?> /> 
								<?php print SB_Text::_('Use calculated dates', 'content')?>
							</label>
						</div>
						<?php endif; ?>
						<?php if( $type == 'page' ): ?>
						<div class="form-group">
							<label class="has-popover" data-content="<?php _e('CONTENT_COVER_PAGE'); ?>">
								<input type="checkbox" name="in_frontpage" value="1" <?php print (isset($content) && $content->_in_frontpage) ? 'checked' : ''; ?> />
								<?php _e('Front page content', 'content'); ?>
							</label>
						</div>
						<?php endif; ?>
						<?php if( $type == 'post' ): ?>
						<div class="form-group">
							<label class="has-popover" data-content="<?php _e('CONTENT_FEATURED_POST'); ?>">
								<input type="checkbox" name="meta[_featured]" value="1" <?php print (isset($content) && $content->_featured == 1) ? 'checked' : ''; ?> />
								<?php _e('Featured', 'content'); ?>
							</label>
						</div>
						<?php endif; ?>
						<?php SB_Module::do_action('content_options', isset($content) ? $content : null); ?>
						<p class="text-center">
							<a class="btn btn-danger has-popover" href="<?php print $back_link; ?>"
								data-content="<?php print SBText::_('CONTENT_CANCEL'); ?>">
								<?php print SB_Text::_('Cancel', 'content'); ?></a>
							<button type="submit" class="btn btn-success has-popover" data-content="<?php print SBText::_('CONTENT_SAVE'); ?>">
								<?php print SB_Text::_('Save', 'content'); ?></button>
						</p>
					</div>
				</div>
				<?php SB_Module::do_action('after_article_options', isset($content) ? $content : null); ?>
				<?php if( isset($content) && $content->type == 'page' || (!isset($content) && $type == 'page') ): ?>
				<div class="panel panel-default">
					<div class="panel-heading">
						<h2 class="panel-title has-popover" data-content="<?php print SBText::_('CONTENT_WH_SECTIONS'); ?>">
							<?php print SB_Text::_('Template Options', 'content'); ?>
						</h2>
					</div>
					<div class="panel-body">
						<select id="template_file" name="meta[_template]" class="form-control">
							<option value="-1"><?php _e('-- plantilla --', 'content'); ?></option>
							<?php foreach(lt_content_get_page_templates() as $tpl): ?>
							<option value="<?php print $tpl['file']; ?>" 
								<?php print isset($content) && $content->_template == $tpl['file'] ? 'selected' : '';?>>
								<?php print $tpl['name']; ?>
							</option>
							<?php endforeach; ?>
						</select>
						<?php $tpl_fields = array(); ?>
						<?php foreach(lt_content_get_page_templates() as $tpl): if( !isset($tpl['fields']) || !is_array($tpl['fields']) ) continue;?>
							<?php $tpl_fields[$tpl['file']] = array(); ?>
							<?php 
							foreach($tpl['fields'] as $field): 
								$meta_key = '_' . $field; 
								$tpl_fields[$tpl['file']][] = array(
									'label'			=> $field,
									'meta_key' 		=> $meta_key, 
									'meta_value' 	=> isset($content) ? $content->{$meta_key} : ''
								); 
							endforeach; ?>
						<?php endforeach; ?>
						<script>
						var tpl_fields = <?php print json_encode($tpl_fields); ?>;
						</script>
						<div id="tpl-file-fields"></div>
					</div>
				</div>
				<?php endif; ?>
				<?php if( $type != 'post'  ): ?>
				<div class="panel panel-default">
					<div class="panel-heading">
						<h2 class="panel-title has-popover" data-content="<?php print SBText::_('CONTENT_WH_SECTIONS'); ?>">
							<?php _e('Sections', 'content'); ?>
						</h2>
					</div>
					<div class="panel-body">
						<?php print sb_sections_html_list(array(
												'checked' 		=> isset($content) ? $content->GetSectionIds() : array(),
												'for_object' 	=> $type)); ?>
					</div>
				</div>
				<?php endif; ?>
				<?php if( isset($content) && $content->type == 'post' || (!isset($content) && $type == 'post') ): ?>
				<div class="panel panel-default">
					<div class="panel-heading">
						<h2 class="panel-title has-popover" data-content="<?php print SBText::_('CONTENT_WH_CATEGORIES'); ?>">
							<?php _e('Categories', 'content'); ?>
						</h2>
					</div>
					<div class="panel-body">
						<?php print sb_categories_html_list(array('checked' => isset($content) ? $content->GetSectionIds() : array()))?>
					</div>
				</div>
				<?php endif; ?>
				<?php if( /*isset($content) &&*/ isset($features['featured_image']) && $features['featured_image'] ): ?>
				<div class="panel panel-default">
					<div class="panel-heading">
						<h2 class="panel-title has-popover" data-content="<?php print SBText::_('CONTENT_WH_FEATURED_IMAGE'); ?>">
							<?php _e('Featured Image', 'content'); ?>
						</h2>
					</div>
					<div class="panel-body">
						<div id="featured-image-container">
							<?php if( isset($content) ): if( $content->_featured_image || $content->_featured_image_id ): ?>
								<?php if( $content->_featured_image ): ?> 
								<img src="<?php print UPLOADS_URL . '/' . $content->_featured_image; ?>" alt="" class="img-thumbnail" />
								<?php else: ?>
								<img src="<?php print $content->GetThumbnailUrl(); ?>" alt="" class="img-thumbnail" />
								<?php endif; ?>
							<?php endif; endif; ?>
						</div>
						<div id="upload-progress" style="display:none;">
							<?php _e('Uploading...', 'content'); ?>
							<img src="<?php print BASEURL; ?>/images/spin.gif" alt=""/>
						</div>
						<div>&nbsp;</div>
						<div id="select-featured-image" class="btn btn-default btn-xs">
							<?php _e('Upload image', 'content'); ?>
						</div>
						<a href="javascript:;" id="btn-remove-featured-image" class="btn btn-danger btn-xs"
							style="<?php print (isset($content) && ($content->_featured_image || $content->_featured_image_id)) ? '' : 'display:none;'; ?>">
							<?php _e('Delete', 'content'); ?>
						</a>
					</div>
				</div>
				<?php endif; ?>
				<?php SB_Module::do_action('article_sidebar', isset($content) ? $content : null); ?>
				<?php SB_Module::do_action('content_sidebar', isset($content) ? $content : null); ?>
				<?php SB_Module::do_action('content_'.$type.'_sidebar', isset($content) ? $content : null); ?>
			</div>
		</div>
	</form>
	<script type="text/javascript">
	jQuery(function()
	{
		jQuery('.fg_color_picker').ColorPicker({
			onChange: function (hsb, hex, rgb) 
			{
				jQuery('#article-fg-color').val('#' + hex);
				jQuery('.fg_color_picker i').css('backgroundColor', '#' + hex);
			}
		});
		jQuery('.bg_color_picker').ColorPicker({
			onChange: function (hsb, hex, rgb) 
			{
				jQuery('#article-color-input').val('#' + hex);
				jQuery('.bg_color_picker i').css('backgroundColor', '#' + hex);
			}
		});
		jQuery('#remove-banner').click(function()
		{
			jQuery.post('<?php print $remove_banner_link; ?>', 'mod=content&task=remove_banner', function(res){});
			jQuery('#the-banner img:first').css('display', 'none');
			jQuery('#remove-banner').css('display', 'none');
			return false;
		});
		jQuery('#remove-button-image').click(function()
		{
			var params = 'mod=content&task=remove_button_image';
			<?php if( isset($content) ):  ?>
			params += '&id=<?php print $content->content_id; ?>';
			<?php else: ?>
			params += '&id=temp';
			<?php endif; ?>
			jQuery.post('index.php', params, function(res){});
			jQuery('#button-image').css('display', 'none');
			//jQuery('#remove-banner').css('display', 'none');
			return false;
		});
		var uploader = new qq.FineUploaderBasic({
			//element: document.getElementById("uploader"),
			//template: 'qq-template-gallery',
			button: document.getElementById('select-banner'),
			request: {
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
						jQuery('#the-banner img:first').attr('src', responseJSON.image_url).css('display', 'inline');
						jQuery('#remove-banner').css('display', 'inline');
		            } 
		            else 
					{
						alert(responseJSON.error);
		            }
				}
			}
		});
		var button_uploader = new qq.FineUploaderBasic({
			//element: document.getElementById("uploader"),
			//template: 'qq-template-gallery',
			button: document.getElementById('select-button-image'),
			request: {
				endpoint: '<?php print SB_Route::_('index.php?mod=content&task=upload_button_image' . (isset($content) ? '&id='.$content->content_id : '')); ?>'
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
						jQuery('#button-image').css('display', 'inline');
						jQuery('#button-image img:first').attr('src', responseJSON.image_url).css('display', 'inline');
						jQuery('#remove-banner').css('display', 'inline');
		            } 
		            else 
					{
						alert(responseJSON.error);
		            }
				}
			}
		});
		
		window.featured_uploader = new qq.FineUploaderBasic({
			button: document.getElementById('select-featured-image'),
			request: {
				endpoint: '<?php print $upload_img_endpoint; ?>'
			},
			validation: {allowedExtensions: ['jpeg', 'jpg', 'gif', 'png']},
			callbacks: 
			{
				onUpload: function(id, fileName) 
				{
					jQuery('#uploading-progress').css('display', 'block');
				},
				onComplete: function(id, fileName, res) 
				{
					jQuery('#uploading-progress').css('display', 'none');
					if (res.success) 
					{
						jQuery('#featured-image-container').append('<img src="'+res.thumbnail_url+'" alt="" class="img-thumbnail" />');
						jQuery('#btn-remove-featured-image').css('display', 'inline');
						jQuery('#btn-remove-featured-image').get(0).dataset.imgid = res.image_id
		            } 
		            else 
					{
						alert(res.error);
		            }
				}
			}
		});
		jQuery(document).on('click', '#btn-remove-featured-image', function()
		{
			if( !this.dataset.imgid )
				return false;
			var url = 'index.php?mod=content&task=delete_featured_image&id=' + this.dataset.imgid;
			jQuery.get(url, function(res)
			{
				if( res.status == 'ok' )
				{
					jQuery('#featured-image-container').html('');
					jQuery('#btn-remove-featured-image').css('display', 'none');
				}
				else
				{
					alert(res.error);
				}
			});
		});
		
		jQuery('input[name=qqfile]').attr('title', '<?php print SB_Text::_('Sube una imagen de tu equipo')?>');
		jQuery('#btn-add-media').click(function(e)
		{
			var iframe	= jQuery('#iframe-upload-media');
			var modal 	= jQuery('#modal-media');
			iframe.prop('src', iframe.data('src'));
			modal.modal('show');
		});
		jQuery(document).on('media_selected', function(e, data)
		{
			jQuery('#modal-media').modal('hide');
			var img = '<img src="<?php print UPLOADS_URL; ?>/'+data.file+'" alt="" />';
			tinymce.activeEditor.execCommand(
					'mceInsertContent',
					false,
					img
				);
		});
		var tpl_field_html = '<div class="form-group">\
								<label>{field_label}</label>\
								<input type="text" name="meta[{field_name}]" value="{field_value}" class="form-control" />\
							</div>';
		jQuery('#template_file').change(function(e)
		{
			var fields_cont = jQuery('#tpl-file-fields').html('');
			if( this.value <= 0 )
			{
				return false;
			}
			if( !tpl_fields[this.value] )
				return false;
			jQuery.each(tpl_fields[this.value], function(i, field)
			{
				var html = tpl_field_html.replace(/{field_label}/g, field.label)
											.replace(/{field_name}/g, field.meta_key)
											.replace(/{field_value}/g, field.meta_value ? field.meta_value : '');
				fields_cont.append(html);
			});
		});
		jQuery('#template_file').trigger('change');
	});
	</script>
</div>
<link rel="stylesheet" href="<?php print BASEURL; ?>/js/colorpicker/css/colorpicker.css" />
<script src="<?php print BASEURL; ?>/js/colorpicker/js/colorpicker.js"></script>
<div id="modal-media" class="modal fade">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
        		<h4 class="modal-title"><?php _e('Upload Files', 'content'); ?></h4>
			</div>
			<div class="modal-body">
				<iframe id="iframe-upload-media" src="" data-src="<?php print SB_Route::_('index.php?mod=storage&view=uploader&tpl_file=module'); ?>" 
					style="width:100%;height:350px;" frameborder="0"></iframe>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php _e('Close', 'content'); ?></button>
			</div>
		</div>
	</div>
</div>