<?php

?>
<div class="wrap">
	<h1><?php print SB_Text::_('Usuarios', 'users'); ?></h1>
	<ul class="view-buttons">
		<li>
			<a class="btn btn-secondary has-popover" href="index.php?mod=users&view=new_user" 
				data-content="<?php print SBText::_('USERS_BUTTON_NEW'); ?>">
				<?php _e('New', 'users'); ?>
			</a>
		</li>
		<li>
			<a href="<?php print SB_Route::_('index.php?mod=users&task=export'); ?>" class="btn btn-warning has-popover"
				data-content="<?php print SBText::_('USERS_BUTTON_EXPORT'); ?>">
				<?php _e('Export', 'users'); ?></a>
		</li>
	</ul>
	<div class="row">
		<div class="col-md-6">
			<form action="" method="get" class="">
				<input type="hidden" name="mod" value="users" />
				<input type="hidden" name="view" value="default" />
				<div class="input-group">
					<input type="text" name="keyword" value="" class="form-control" />
					<span class="input-group-btn">
						<button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span></button>
					</span>
				</div>
				<div>&nbsp;</div>
			</form>
		</div>
	</div>
	<table id="table-users" class="table">
	<thead>
	<tr>
		<th class="col-count">#</th>
		<th class="col-image">&nbsp;</th>
		<th class="col-username">
			<a href="<?php print $username_order_link; ?>" class="has-popover" data-content="<?php print SBText::_('USERS_TH_USER'); ?>">
				<?php print SB_Text::_('Usuario', 'users'); ?>
				<span class="glyphicon glyphicon-triangle-<?php print (SB_Request::getString('order_by') == 'username' && SB_Request::getString('order', 'asc') == 'asc') ? 'bottom' : 'top'; ?>"></span>
			</a>
		</th>
		<th class="col-name">
			<a href="<?php print $name_order_link; ?>" class="has-popover" data-content="<?php print SBText::_('USERS_TH_NAME'); ?>">
				<?php print SB_Text::_('Nombre', 'users'); ?>
				<span class="glyphicon glyphicon-triangle-<?php print (SB_Request::getString('order_by') == 'name' && SB_Request::getString('order', 'asc') == 'asc') ? 'bottom' : 'top'; ?>"></span>
			</a>
		</th>
		<th class="col-email">
			<a href="<?php print $email_order_link; ?>" class="has-popover" data-content="<?php print SBText::_('USERS_TH_EMAIL'); ?>">
				<?php print SB_Text::_('Email', 'users'); ?>
				<span class="glyphicon glyphicon-triangle-<?php print (SB_Request::getString('order_by') == 'email' && SB_Request::getString('order', 'asc') == 'asc') ? 'bottom' : 'top'; ?>"></span>
			</a>
		</th>
		<th class="col-role">
			<a href="<?php print $rol_order_link; ?>" class="has-popover" data-content="<?php print SBText::_('USERS_TH_ROLE'); ?>">
				<?php print SB_Text::_('Rol', 'users'); ?>
				<span class="glyphicon glyphicon-triangle-<?php print (SB_Request::getString('order_by') == 'rol_name' && SB_Request::getString('order', 'asc') == 'asc') ? 'bottom' : 'top'; ?>"></span>
			</a>
		</th>
		<th>
			<a href="#" class="has-popover" data-content="<?php print SBText::_('USERS_TH_ACTIONS'); ?>">
				<?php print SB_Text::_('Acciones', 'users'); ?>
			</a>
		</th>
	</tr>
	</thead>
	<tbody>
	<?php $i = 1; foreach($users as $user): ?>
	<tr>
		<td class="col-count"><?php print $i; ?></td>
		<td class="col-image"><img src="<?php print sb_get_user_image_url($user); ?>" alt="" width="60" class="img-thumbnail" /></td>
		<td class="col-username"><?php print $user->username; ?></td>
		<td class="col-name"><?php printf("%s %s", $user->first_name, $user->last_name); ?></td>
		<td class="col-email"><?php print $user->email; ?></td>
		<td class="col-role"><?php print $user->role_name; ?></td>
		<td>
			<a href="index.php?mod=users&view=edit_user&id=<?php print $user->user_id; ?>" title="<?php _e('Editar', 'users'); ?>"
				class="btn btn-default">
				<span class="glyphicon glyphicon-edit"></span>
			</a>
			<a href="index.php?mod=users&task=delete_user&id=<?php print $user->user_id; ?>" class="confirm btn btn-default" 
				data-message="<?php print SB_Text::_('Esta seguro de borrar el usuario?'); ?>"
				title="<?php _e('Borrar', 'users'); ?>">
				<span class="glyphicon glyphicon-trash"></span>
			</a>
		</td>
	</tr>
	<?php $i++; endforeach; ?>
	</tbody>
	</table>
</div><!-- end class="container" -->
<style>
@media (max-width:768px)
{
	table, thead, tbody, th, td, tr { 
		display: block; 
		position:relative;
	}
	table thead{display:none;}
	table tbody{margin:0;padding:0;position:relative;width:100%;}
	table tbody tr{border:0;}
	table tbody tr td{position:relative;border:0;}
	.col-count,.col-image,.col-name{float:left;}
}
</style>