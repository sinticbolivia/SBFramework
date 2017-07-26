<?php
?>
<div class="container">
	<h2><?php print SBText::_('Usuarios Conectados', 'users'); ?></h2>
	<table class="table">
	<thead>
	<tr>
		<th>#</th>
		<th>
			<a href="<?php print $id_order_link; ?>" class="has-popover" data-content="<?php print SBText::_('USERS_ONLINE_TH_ID'); ?>">
				<?php print SBText::_('ID', 'users'); ?>
				<span class="glyphicon glyphicon-triangle-<?php print (SB_Request::getString('order_by') == 'user_id' && SB_Request::getString('order', 'asc') == 'asc') ? 'bottom' : 'top'; ?>"></span>
			</a>
		</th>
		<th>
			<a href="<?php print $username_order_link; ?>" class="has-popover" data-content="<?php print SBText::_('USERS_ONLINE_TH_USER'); ?>">
				<?php print SBText::_('Usuario', 'users'); ?>
				<span class="glyphicon glyphicon-triangle-<?php print (SB_Request::getString('order_by') == 'username' && SB_Request::getString('order', 'asc') == 'asc') ? 'bottom' : 'top'; ?>"></span>
			</a>
		</th>
		<th>
			<a href="<?php print $order_email_link; ?>" class="has-popover" data-content="<?php print SBText::_('USERS_ONLINE_TH_EMAIL'); ?>">
				<?php print SBText::_('Email', 'users'); ?>
				<span class="glyphicon glyphicon-triangle-<?php print (SB_Request::getString('order_by') == 'email' && SB_Request::getString('order', 'asc') == 'asc') ? 'bottom' : 'top'; ?>"></span>
			</a>
		</th>
		<th>
			<a href="<?php print $order_first_name_link; ?>" class="has-popover" data-content="<?php print SBText::_('USERS_ONLINE_TH_FNAME'); ?>">
				<?php print SBText::_('Nombres', 'users'); ?>
				<span class="glyphicon glyphicon-triangle-<?php print (SB_Request::getString('order_by') == 'first_name' && SB_Request::getString('order', 'asc') == 'asc') ? 'bottom' : 'top'; ?>"></span>
			</a>
		</th>
		<th>
			<a href="<?php print $order_last_name_link; ?>" class="has-popover" data-content="<?php print SBText::_('USERS_ONLINE_TH_LNAME'); ?>">
				<?php print SBText::_('Apellidos', 'users'); ?>
				<span class="glyphicon glyphicon-triangle-<?php print (SB_Request::getString('order_by') == 'last_name' && SB_Request::getString('order', 'asc') == 'asc') ? 'bottom' : 'top'; ?>"></span>
			</a>
		</th>
		<th>
			<a href="<?php print $order_last_login_link; ?>" class="has-popover" data-content="<?php print SBText::_('USERS_ONLINE_TH_LAST_SESSION'); ?>">
				<?php print SBText::_('Ultima sesi&oacute;n', 'users'); ?>
				<span class="glyphicon glyphicon-triangle-<?php print (SB_Request::getString('order_by') == 'last_login' && SB_Request::getString('order', 'asc') == 'asc') ? 'bottom' : 'top'; ?>"></span>
			</a>
		</th>
	</tr>
	</thead>
	<tbody>
	<?php $i = 1; foreach($users as $u): ?>
	<tr>
		<td><?php print $i; ?></td>
		<td><?php print $u->user_id; ?></td>
		<td><?php print $u->username; ?></td>
		<td><?php print $u->email; ?></td>
		<td><?php print $u->first_name; ?></td>
		<td><?php print $u->last_name; ?></td>
		<td><?php print sb_format_datetime($u->last_login); ?></td>
	</tr>
	<?php $i++; endforeach; ?>
	</tbody>
	</table>
	<p>
		<a href="<?php print SB_Route::_('index.php'); ?>" class="button primary has-popover" data-content="<?php print SBText::_('USERS_ONLINE_BUTTON_BACK'); ?>">
			<?php print SBText::_('Volver', 'users'); ?></a>
	</p>
</div>