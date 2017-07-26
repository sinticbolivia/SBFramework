<div class="wrap">
	<h2><?php _e('Database Information', 'dbreset'); ?></h2>
	<table class="table table-condensed">
	<thead>
	<tr>
		<th><?php _e('Num', 'dbreset'); ?></th>
		<th><?php _e('Table', 'dbreset'); ?></th>
		<th><?php _e('Description', 'dbreset'); ?></th>
		<th><?php _e('Current Value', 'dbreset'); ?></th>
		<th></th>
	</tr>
	</thead>
	<tbody>
	<?php $i = 1; foreach($tables as $t): ?>
	<tr>
		<td class="text-center"><?php print $i; ?></td>
		<td><?php print $t->table; ?></td>
		<td><?php print $t->description; ?></td>
		<td class="text-center"><?php print $t->current_increment; ?></td>
		<td>
			<a href="<?php print SB_Route::_('index.php?mod=dbreset&task=reset&table='.$t->table); ?>" 
				class="btn btn-primary btn-xs confirm"
				data-message="<?php _e('Alert: Are you sure to delete the table data and reset the counter IDs?', 'dbreset'); ?>">
				<?php print _e('Reset', 'db'); ?>
			</a>
		</td>
	</tr>
	<?php $i++; endforeach; ?>
	</tbody>
	</table>
</div>