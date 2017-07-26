<?php
if( !sb_get_current_user()->can('manage_users') )
{
	die('You dont have enough permissions');
}

$dbh = SB_Factory::getDbh();
$columns = array(
		'u.*, CONCAT(u.first_name, \' \', u.last_name) AS name',
		'r.role_name',
		'r.role_key'
);
$tables = array(
		'users u LEFT JOIN user_roles r ON u.role_id = r.role_id',
		//'user_roles r'
);
$where = array(
		"username <> 'root'",
		//'u.role_id = r.role_id'
);
//##if user is different than root, just get their users
if( sb_get_current_user()->role_id !== 0 )
{
	$tables[] = 'user_meta um';
	$where[] = 'u.user_id = um.user_id';
	$where[] = 'um.meta_key = "_owner_id"';
	$where[] = 'um.meta_value = "'.sb_get_current_user()->user_id.'"';
}
$order = SB_Request::getString('order', 'desc');
$order_by = SB_Request::getString('order_by', 'creation_date');
$query = sprintf("SELECT %s FROM %s WHERE %s ORDER BY %s $order",
		implode(',', $columns),
		implode(',', $tables),
		implode(' AND ', $where),
		$order_by
);
$res = $dbh->Query($query);
$users = $dbh->FetchResults();

$new_order = $order == 'desc' ? 'asc' : 'desc';
$username_order_link 	= 'index.php?tpl_file=module&mod=users&view=users_list&order_by=username&order='.$new_order;
$name_order_link 		= 'index.php?tpl_file=module&mod=users&view=users_list&order_by=name&order='.$new_order;
$email_order_link		= 'index.php?tpl_file=module&mod=users&view=users_list&order_by=email&order='.$new_order;
$lastname_order_link	= 'index.php?tpl_file=module&mod=users&view=users_list&order_by=last_name&order='.$new_order;
?>
<div class="wrap1">
	<table class="table">
	<thead>
	<tr>
		<th>
			<a href="<?php print $name_order_link; ?>" class="has-popover" data-content="<?php print SBText::_('USERS_TH_NAME'); ?>">
				<?php print SB_Text::_('Nombre', 'users'); ?>
				<span class="glyphicon glyphicon-triangle-<?php print (SB_Request::getString('order_by') == 'name' && SB_Request::getString('order', 'asc') == 'asc') ? 'bottom' : 'top'; ?>"></span>
			</a>
		</th>
		<th>
			<a href="<?php print $lastname_order_link; ?>" class="has-popover" data-content="<?php print SBText::_('USERS_TH_NAME'); ?>">
				<?php print SBText::_('Apellidos', 'users'); ?>
				<span class="glyphicon glyphicon-triangle-<?php print (SB_Request::getString('order_by') == 'last_name' && SB_Request::getString('order', 'asc') == 'asc') ? 'bottom' : 'top'; ?>"></span>
			</a>
		</th>
		<th>
			<a href="<?php print $email_order_link; ?>" class="has-popover" data-content="<?php print SBText::_('USERS_TH_EMAIL'); ?>">
				<?php print SB_Text::_('Email', 'users'); ?>
				<span class="glyphicon glyphicon-triangle-<?php print (SB_Request::getString('order_by') == 'email' && SB_Request::getString('order', 'asc') == 'asc') ? 'bottom' : 'top'; ?>"></span>
			</a>
		</th>
		<th><?php print SBText::_('Accion', 'users'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach($users as $u): ?>
	<tr>
		<td><?php print $u->first_name; ?></td>
		<td><?php print $u->last_name; ?></td>
		<td><?php print $u->email; ?></td>
		<td>
			<a href="javascript:;" class="btn-choose-user" data-id="<?php print $u->user_id; ?>" data-fullname="<?php printf("%s %s", $u->first_name, $u->last_name); ?>"
				data-email="<?php print $u->email; ?>">
				<?php print SBText::_('Escoger', 'users'); ?></a>
		</td>
	</tr>
	<?php endforeach; ?>
	</tbody>
	</table>
</div>
<script>
jQuery(function()
{
	jQuery('.btn-choose-user').click(function()
	{
		var callback = null;
		if( typeof choose_user_callback == 'function' )
			callback == choose_user_callback;
		else if( typeof parent.choose_user_callback == 'function' )
			callback = parent.choose_user_callback;
		if( callback )
		{
			callback(this.dataset);
		}
		return false;
	});
});
</script>