<?php
?>
<div class="wrap">
	<h1>
		<span class="glyphicon glyphicon-stats"></span>
		<?php print SB_Text::_('Accesos por usuarios', 'statistics'); ?>
	</h1>
	<?php include dirname(__FILE__) . SB_DS . 'connections-menu.php'; ?>
	<table class="table">
	<thead>
	<tr>
		<th>#</th>
		<th>
			<a href="<?php print $order_id_link; ?>" class="has-popover" data-content="<?php print SBText::_('STATS_UA_TH_ID'); ?>">ID</a>
		</th>
		<th>
			<a href="<?php print $order_fname_link; ?>" class="has-popover" data-content="<?php print SBText::_('STATS_UA_TH_USER'); ?>">
				<?php print SB_Text::_('Nombre', 'statistics'); ?>
				<span class="glyphicon glyphicon-triangle-<?php print (SB_Request::getString('order_by') == 'first_name' && SB_Request::getString('order', 'asc') == 'asc') ? 'bottom' : 'top'; ?>"></span>
			</a>
		</th>
		<th>
			<a href="<?php print $order_lname_link; ?>" class="has-popover" data-content="<?php print SBText::_('STATS_UA_TH_USER'); ?>">
				<?php print SB_Text::_('Apellidos', 'statistics'); ?>
				<span class="glyphicon glyphicon-triangle-<?php print (SB_Request::getString('order_by') == 'last_name' && SB_Request::getString('order', 'asc') == 'asc') ? 'bottom' : 'top'; ?>"></span>
			</a>
		</th>
		<th>
			<a href="<?php print $order_auths_link; ?>" class="has-popover" data-content="<?php print SBText::_('STATS_UA_TH_ACCESS'); ?>">
				<?php print SB_Text::_('Accesos', 'statistics'); ?>
				<span class="glyphicon glyphicon-triangle-<?php print (SB_Request::getString('order_by') == 'auths' && SB_Request::getString('order', 'asc') == 'asc') ? 'bottom' : 'top'; ?>"></span>
			</a>
		</th>
	</tr>
	</thead>
	<tbody>
	<?php $i = 1; foreach($users as $user): ?>
	<tr>
		<td><?php print $i; ?></td>
		<td><?php print $user->user_id; ?></td>
		<td><?php print $user->first_name; ?></td>
		<td><?php print $user->last_name; ?></td>
		<td><?php print $user->auths; ?></td>
	</tr>
	<?php $i++; endforeach; ?>
	</tbody>
	</table>
	<?php lt_pagination(SB_Route::_('index.php?mod=statistics'), $total_pages, SB_Request::getInt('page', 1)); ?>
</div>