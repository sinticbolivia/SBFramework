<?php
$user = sb_get_current_user();
?>
<div class="wrap">
	<h1><?php print $title; ?></h1>
	<form action="" method="post">
		<input type="hidden" name="mod" value="users" />
		<input type="hidden" name="task" value="roles.save" />
		<?php if( isset($role) ): ?>
		<input type="hidden" name="role_id" value="<?php print $role->role_id; ?>" />
		<?php endif; ?>
		<div class="row">
			<div class="col-md-4">
				<div class="form-group">
					<label class="has-popover" data-content="<?php print SBText::_('USERS_ROLE_NAME'); ?>">
						<?php print SB_Text::_('Nombre del Rol:', 'users'); ?></label>
					<input type="text" name="role_name" value="<?php print SB_Request::getString('role_name', isset($role) ? $role->role_name : ''); ?>" 
							class="form-control" maxlength="40" />
				</div>
			</div>
		</div>
		<div class="form-group row">
			<div class="col-md-6">
				<label class="has-popover" data-content="<?php print SBText::_('USERS_ROLE_DESCRIPTION'); ?>">
					<?php print SB_Text::_('Descripci&oacute;n:', 'users'); ?></label>
				<textarea name="description" class="form-control"><?php print SB_Request::getString('role_name', isset($role) ? $role->role_description : ''); ?></textarea>
			</div>
		</div>
		<div class="form-group">
			<h3 class="has-popover" data-content="<?php print SBText::_('USERS_ROLE_PERMISSIONS'); ?>">
				<?php print SB_Text::_('Permisos:', 'users'); ?>
			</h3>
			
			<?php foreach(sb_get_permissions() as $group): ?>
			<div class="panel panel-default">
				<div class="panel-heading"><?php _e('gp_'.$group->group); ?></div>
				<div class="panel-body">
					<div class="row">
						<?php foreach($group->perms as $p): $attr = json_decode($p->attributes); if( !is_object($attr) ) $attr = new stdClass(); ?>
						<?php if( isset($attr->only_root) && $attr->only_root == 'yes' && !$user->IsRoot() ) continue; ?>
						<div class="col-md-3">
							<label>
								<input type="checkbox" name="permissions[]" value="<?php print $p->permission_id; ?>" <?php print (isset($role) && $role->hasPermission($p->permission)) ? 'checked' : ''; ?> />
								<?php print $p->label; ?>
							</label>
						</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
			<?php endforeach; ?>
			
		</div>
		<p>
			<a class="btn btn-secondary has-popover" href="<?php print SB_Route::_('index.php?mod=users&view=roles'); ?>"
				data-content="<?php print SBText::_('USERS_ROLE_BUTTON_CANCEL'); ?>">
				<?php print SB_Text::_('Cancelar', 'users'); ?></a>
			<button class="btn btn-secondary has-popover" type="submit" data-content="<?php print SBText::_('USERS_ROLE_BUTTON_SAVE'); ?>">
				<?php print SB_Text::_('Guardar', 'users'); ?></button>
		</p>
	</form>
</div>