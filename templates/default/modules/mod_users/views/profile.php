<?php
lt_include_template('tpl-user-menu');
?>
<h1><?php print SB_Text::_('Mi Perfil', 'users'); ?></h1>
<form action="" method="post">
	<input type="hidden" name="mod" value="users" />
	<input type="hidden" name="task" value="save_profile" />
	<div class="row">
		<div class="col-md-2">
			<div id="select-image" title="<?php print SB_Text::_('Select user image', 'users'); ?>">
				<img src="<?php print $image_url; ?>" alt="" class="img-thumbnail" />
			</div>
			<div id="uploading" style="display:none;">
				<img src="<?php print BASEURL; ?>/js/fineuploader/loading.gif" alt=""  /><?php print SB_Text::_('Subiendo imagen', 'users'); ?>
			</div>
		</div>
		<div class="col-md-10">
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label><?php print SBText::_('Nombres:'); ?></label>
						<input type="text" name="first_name" value="<?php print $user->first_name; ?>" class="form-control" />
					</div>
					<div class="form-group">
						<label><?php print SBText::_('Apellidos:'); ?></label>
						<input type="text" name="last_name" value="<?php print $user->last_name; ?>" class="form-control" />
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="user-tabs">
		<ul class="nav nav-tabs">
			<li class="active"><a href="#profile"><?php print SB_Text::_('Perfil', 'users'); ?></a></li>
			<?php SB_Module::do_action('user_tabs', $user); ?>
		</ul>
		<div class="tab-content">
			<div id="profile" class="tab-pane active">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label><?php print SB_Text::_('Email:', 'users'); ?></label>
							<input type="text" name="email" value="<?php print $user->email; ?>" class="form-control" />
						</div>
						<div class="form-group">
							<label><?php print SB_Text::_('Password:', 'users'); ?></label>
							<input type="password" name="pwd" value="" placeholder="<?php print SB_Text::_('Dejar en blanco para no actualizar.', 'users'); ?>" class="form-control" />
						</div>
					</div>
				</div>
			</div><!-- end id="profile" -->
		    <?php SB_Module::do_action('user_tabs_content', $user); ?>
		</div>
	</div><!-- end id="user-tabs" -->
	<div class="control-group">
		<button class="btn" type="submit"><?php print SB_Text::_('Guardar', 'users'); ?></button>
	</div>
</form>
<script>
window.mod_users = {
	upload_endpoint: '<?php print $upload_endpoint; ?>'
};
jQuery('#user-tabs > ul li a').click(function (e) 
{
	e.preventDefault()
	jQuery(this).tab('show');
});
</script>