<?php

?>
<div class="wrap">
	<h1><?php print SB_Text::_('Roles de Usuario', 'users'); ?></h1>
	<ul class="view-buttons">
		<li>
			<a class="btn btn-secondary has-popover" data-content="<?php print SBText::_('USERS_ROLE_BUTTON_NEW'); ?>" 
				href="<?php print SB_Route::_('index.php?mod=users&view=roles.new&ctrl=roles'); ?>">
				<?php print SB_Text::_('Nuevo Rol', 'users'); ?>
			</a>
		</li>
	</ul>
	<table class="table">
	<thead>
	<tr>
		<th>#</th>
		<th>
			<a href="<?php print $role_order_link; ?>" class="has-popover" data-content="<?php print SBText::_('USERS_ROLE_TH_ROLE'); ?>">
				<?php print SB_Text::_('Rol', 'users'); ?>
				<span class="glyphicon glyphicon-triangle-<?php print (SB_Request::getString('order_by') == 'role_name' && SB_Request::getString('order', 'asc') == 'asc') ? 'bottom' : 'top'; ?>"></span>
			</a>
		</th>
		<th>
			<a href="#" class="has-popover" data-content="<?php print SBText::_('USERS_ROLE_TH_ACTIONS'); ?>">
				<?php print SB_Text::_('Acciones'); ?>
			</a>
		</th>
	</tr>
	</thead>
	<tbody>
	<?php $i = 1;foreach($roles as $role): ?>
	<tr>
		<td><?php print $i; ?></td>
		<td><?php print $role->role_name; ?></td>
		<td>
			<a href="index.php?mod=users&view=roles.edit&id=<?php print $role->role_id; ?>" class="btn btn-default"
				title="<?php print SB_Text::_('Editar', 'users'); ?>">
				<span class="glyphicon glyphicon-edit"></span>
			</a> 
			<a href="index.php?mod=users&task=roles.delete&id=<?php print $role->role_id; ?>" class="confirm btn btn-default" 
				data-message="<?php print SB_Text::_('Esta seguro de borrar el rol de usuario?'); ?>" title="Borrar">
				<span class="glyphicon glyphicon-trash"></span>
			</a>
		</td>
	</tr>
	<?php $i++; endforeach; ?>
	</tbody>
	</table>
</div><!-- end class="container" -->
