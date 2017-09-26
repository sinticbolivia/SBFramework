<?php
$image = SB_Request::getString('imagefile', isset($user) ? sb_get_user_meta($user_id, '_image') : null);
$image_url = '';
if( !$image )
{
	$image_url = MODULE_URL . '/images/nobody.png';
}
elseif( isset($user) && file_exists($user_dir . SB_DS . $image) )
{
	$image_url = $user_url . '/' . $image;
}
else 
{
	$image_url = MODULE_URL . '/images/nobody.png';
}
$selected_role = isset($user) ? $user->role_id : SB_Request::getInt('role_id', -1);
$countries = sb_include('countries.php', 'file');
?>
<!-- <link rel="stylesheet" href="<?php print BASEURL;?>/js/fineuploader/fineuploader.css" /> -->
<div class="wrap">
	<h2 id="page-title">
		<div class="container-fluid">
			<div class="row">
				<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"><?php print $title; ?></div>
				<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
					<div class="page-buttons">
						<a href="javascript:;" class="btn btn-success" onclick="jQuery('#form-user').submit();">
							<?php _e('Save', 'users'); ?>
						</a>
					</div>
				</div>
			</div>
		</div>
	</h2>
		<form id="form-user" action="" method="post" enctype="multipart/form-data">
			<input type="hidden" name="mod" value="users" />
			<input type="hidden" name="task" value="save_user" />
			<?php if( isset($user) ): ?>
			<input type="hidden" name="user_id" value="<?php print $user_id; ?>" />
			<?php endif; ?>
			<input type="hidden" id="imagefile" name="imagefile" value="<?php print $image; ?>" />
			<div class="form-group row">
				<div class="col-md-2">
					<div id="select-image"  title="<?php print SB_Text::_('Select user image', 'users'); ?>">
						<img src="<?php print $image_url; ?>" alt="" class="img-thumbnail" />
					</div>
					<div id="uploading" style="display:none;">
						<img src="<?php print BASEURL; ?>/js/fineuploader/loading.gif" alt=""  />
						<?php print SB_Text::_('Subiendo imagen...', 'users'); ?>
					</div>
				</div>
				<div class="col-md-10">
					<div class="form-row row">
						<div class="col-md-4">
							<label class="has-popover" data-content="<?php print SBText::_('USERS_FIRST_NAME'); ?>">
								<?php print SB_Text::_('Nombre:', 'users'); ?>
							</label>
							<input type="text" name="first_name" value="<?php print SB_Request::getString('first_name', isset($user) ? $user->first_name : ''); ?>" 
									class="form-control" maxlength="20" />
						</div>
					</div>
					<div class="form-row row">
						<div class="col-md-4">
							<label class="has-popover" data-content="<?php print SBText::_('USERS_LAST_NAME'); ?>">
								<?php print SB_Text::_('Apellidos:', 'users'); ?>
							</label>
							<input type="text" name="last_name" value="<?php print SB_Request::getString('last_name', isset($user) ? $user->last_name : ''); ?>" 
									class="form-control" maxlength="20" />
						</div>
					</div>
				</div>
				<div class="clear"></div>
			</div>
			<div id="user-tabs">
			  	<ul class="nav nav-tabs" role="tablist">
				    <li class="active">
				    	<a href="#profile" aria-controls="home" role="tab" data-toggle="tab" class="has-popover" data-content="<?php print SBText::_('USERS_TAB_PROFILE'); ?>">
				    		<?php print SB_Text::_('Perfil', 'users'); ?></a>
				    </li>
				    <li class="has-popover" data-content="<?php print SBText::_('USERS_TAB_PERSONAL'); ?>">
				    	<a href="#personal" aria-controls="profile" role="tab" data-toggle="tab">
				    		<?php print SBText::_('Personal', 'users'); ?></a></li>
				    <?php SB_Module::do_action('user_tabs', isset($user) ? $user : null); ?>
			  	</ul>
			  	<!-- Tab panes -->
			  	<div class="tab-content">
				    <div role="tabpanel" class="tab-pane active" id="profile">
				    	<?php SB_Module::do_action('before_user_tab_personal'); ?>
				    	<div class="row">
				    		<div class="col-md-3">
					    		<div class="form-group">
						    		<label for="username" class="has-popover" data-content="<?php print SBText::_('USERS_USERNAME'); ?>">
						    			<?php print SBText::_('Usuario:', 'users'); ?>
						    		</label>
			    					<input type="text" class="form-control" id="username" name="username" 
			    							value="<?php print SB_Request::getString('username', isset($user) ? $user->username : ''); ?>"
			    							/>
						    	</div>
					    	</div>
					    	<div class="col-md-3">
					    		<div class="form-group">
						    		<label for="pwd" class="has-popover" data-content="<?php print SBText::_('USERS_PASSWORD'); ?>">
						    			<?php print SB_Text::_('Contrase&ntilde;a:', 'users'); ?></label>
			    					<input type="password" class="form-control" id="pwd" name="pwd" value="" maxlength="20" />
						    	</div>
					    	</div>
				    	</div>
				    	<div class="row">
				    		<div class="col-md-3">
					    		<div class="form-group">
						    		<label for="role_id" class="has-popover" data-content="<?php print SBText::_('USERS_USER_ROLE'); ?>">
						    			<?php print SBText::_('Rol de Usuario:', 'users'); ?>
						    		</label>
			    					<select id="role_id" name="role_id" class="form-control">
			    						<option value="-1"><?php print SBText::_('-- rol --', 'users'); ?></option>
			    						<?php foreach($roles as $role): ?>
			    						<option value="<?php print $role->role_id; ?>" <?php print ($selected_role == $role->role_id) ? 'selected' : ''; ?>>
			    							<?php print $role->role_name; ?>
			    						</option>
			    						<?php endforeach; ?>
			    					</select>
						    	</div>
					    	</div>
					    	<div class="col-md-2">
					    		<div class="form-group">
						    		<label for="creation_date" class="has-popover" data-content="<?php print SBText::_('USERS_CREATION_DATE'); ?>">
						    			<?php print SB_Text::_('Fecha Creaci&oacute;n:', 'users'); ?></label>
						    		<?php if( isset($user) ): ?>
			    					<input type="text" class="form-control datepicker" id="creation_date" name="creation_date" 
			    							value="<?php print sb_format_date($user->creation_date); ?>" />
			    					<?php else: ?>
			    					<input type="text" class="form-control datepicker" id="creation_date" name="creation_date" 
			    							value="<?php print sb_format_date(date('Y-m-d')); ?>" />
			    					<?php endif; ?>
						    	</div>
					    	</div>
				    	</div>
				    	<div class="row">
				    		<div class="col-md-5">
				    			<div class="form-group">
						    		<label for="email" class="has-popover" data-content="<?php print SBText::_('USERS_EMAIL'); ?>">
						    			<?php print SBText::_('Email:', 'users'); ?>
						    		</label>
			    					<input type="email" class="form-control" id="email" placeholder="Email" name="email" 
			    							value="<?php print SB_Request::getString('email', isset($user) ? $user->email : ''); ?>"
			    							maxlength="40" />
						    	</div>
				    		</div>
				    	</div>
				    	<div class="form-group">
				    		<label class="has-popover" data-content="<?php _e('USERS_LABEL_NO_LOGIN') ;?>">
				    			<input type="checkbox" name="no_login" value="1" <?php print isset($user) && $user->_no_login == 1 ? 'checked' : ''; ?> />
				    			<?php _e('No Login', 'users'); ?>
				    		</label>
				    	</div>
				    	<div class="clearfix"></div>
				    	<div class="form-group row">
				    		<div class="col-md-6">
				    			<label for="observations" class="has-popover" data-content="<?php print SBText::_('USERS_OBS'); ?>">
					    			<?php print SBText::_('Observaciones:', 'users'); ?>
					    		</label>
		    					<textarea class="form-control" id="observations" name="observations"><?php print SB_Request::getString('observations', isset($user) ? sb_get_user_meta($user->user_id, '_observations') : ''); ?></textarea>
				    		</div>
				    	</div>
				    	<div class="form-group row">
				    		<div class="col-md-6">
				    			<label for="notes" class="has-popover" data-content="<?php print SBText::_('USERS_NOTES'); ?>">
					    			<?php print SBText::_('Notas:', 'users'); ?></label>
		    					<textarea class="form-control" id="notes" placeholder="" name="notes"><?php print SB_Request::getString('observations', isset($user) ? sb_get_user_meta($user->user_id, '_notes') : ''); ?></textarea>
				    		</div>
				    	</div>
				    	<?php SB_Module::do_action('after_user_tab_personal', isset($user) ? $user : null); ?>
				    </div>
				    <div role="tabpanel" class="tab-pane" id="personal">
				    	<div class="col-md-2 form-group">
				    		<div class="row">
					    		<label class="has-popover" data-content="<?php print SBText::_('USERS_BIRTHDAY'); ?>">
					    			<?php print SB_Text::_('Fecha de Nacimiento:', 'users')?></label>
					    		<input class="form-control datepicker" type="text" name="birthday" value="<?php print SB_Request::getString('birthday', isset($user) ? $user->_birthday : ''); ?>" />
				    		</div>
				    	</div>
				    	<div class="clearfix"></div>
				    	<div class="form-group row">
				    		<div class="col-md-6">
				    			<label class="has-popover" data-content="<?php print SBText::_('USERS_ADDRESS'); ?>">
				    				<?php print SB_Text::_('Direccion:', 'users')?></label>
				    			<input class="form-control" type="text" name="address" value="<?php print SB_Request::getString('address', isset($user) ? $user->_address : ''); ?>"
				    				maxlength="80" />
				    		</div>
				    	</div>
				    	<div class="col-md-2 form-group">
				    		<div class="row">
					    		<label class="has-popover" data-content="<?php print SBText::_('USERS_CITY'); ?>">
					    			<?php print SB_Text::_('Ciudad:', 'users')?></label>
					    		<input class="form-control" type="text" name="city" value="<?php print SB_Request::getString('city', isset($user) ? $user->_city : ''); ?>" />
					    	</div>
				    	</div>
				    	<div class="col-md-2 form-group">
				    		<div class="ro">
				    			<label class="has-popover" data-content="<?php print SBText::_('USERS_STATE'); ?>">
				    				<?php print SB_Text::_('Provincia:', 'users')?></label>
				    			<input class="form-control" type="text" name="state" value="<?php print SB_Request::getString('state', isset($user) ? $user->_state : ''); ?>" />
				    		</div>
				    	</div>
				    	<div class="col-md-4 form-group">
				    		<div class="ro">
					    		<label class="has-popover" data-content="<?php print SBText::_('USERS_COUNTRY'); ?>">
					    			<?php print SB_Text::_('Pais:', 'users')?></label>
					    		<select name="country" class="form-control">
					    			<option value="-1"><?php print SB_Text::_('-- pais --', 'users'); ?></option>
					    			<?php foreach($countries as $code => $label): ?>
					    			<option value="<?php print $code; ?>" <?php print isset($user) && $user->_country == $code ? 'selected' : ''; ?>><?php print $label; ?></option>
					    			<?php endforeach; ?>
					    		</select>
				    		</div>
				    	</div>
				    	<div class="clearfix"></div>
				    </div>
				    <?php SB_Module::do_action('user_tabs_content', isset($user) ? $user : null); ?>
			  	</div>
			</div><br/>
			<p>
				<a href="index.php?mod=users" class="btn btn-danger has-popover" 
					data-content="<?php print SBText::_('USERS_BUTTON_CANCEL'); ?>">
					<?php _e('Cancel', 'users'); ?></a>
				<button type="submit" class="btn btn-success has-popover" 
					data-content="<?php print SBText::_('USERS_BUTTON_SAVE'); ?>">
					<?php _e('Save', 'users'); ?>
				</button>
			</p>
		</form>
</div>
<script>
jQuery(function()
{
	var uploader = new qq.FineUploaderBasic({
		//element: document.getElementById("uploader"),
		//template: 'qq-template-gallery',
		button: document.getElementById('select-image'),
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
				/*
				if (loaded < total) 
				{
					progress = Math.round(loaded / total * 100) + '% of ' + Math.round(total / 1024) + ' kB';
	              	jQuery('#file-' + id).removeClass('alert-info')
	                              .html('<img src="client/loading.gif" alt="In progress. Please hold."> ' +
	                                    'Uploading ' +
	                                    '“' + fileName + '” ' +
	                                    progress);
				} 
				else 
				{
					jQuery('#file-' + id).addClass('alert-info')
	                              .html('<img src="client/loading.gif" alt="Saving. Please hold."> ' +
	                                    'Saving ' +
	                                    '“' + fileName + '”');
	            }
	            */
			},
			onComplete: function(id, fileName, responseJSON) 
			{
				jQuery('#uploading').css('display', 'none');
				if (responseJSON.success) 
				{
					jQuery('#select-image img:first').attr('src', responseJSON.image_url);
					jQuery('#imagefile').val(responseJSON.uploadName);
	            } 
	            else 
				{
					alert(responseJSON.error);
	            }
			}
		}
	});
	jQuery('#select-image input[type=file]').attr('title', 'Click para añadir o cambiar Fotografia o Avatar');
});
jQuery('#user-tabs .nav-tabs li a').click(function (e) 
{
	e.preventDefault();
	jQuery(this).tab('show');
});
</script>
