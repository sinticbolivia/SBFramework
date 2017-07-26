<?php

?>
<div class="wrap">
	<h2>
		<?php _e('Content Menus', 'menu'); ?> 
		<a href="<?php print SB_Route::_('index.php?mod=menu&view=new'); ?>" class="pull-right btn btn-primary"><?php _e('New', 'menu'); ?></a>
	</h2>
	<table class="table">
	<thead>
	<tr>
		<th><?php _e('No.', 'menu'); ?></th>
		<th><?php _e('Name', 'menu'); ?></th>
		<th><?php _e('Key', 'menu'); ?></th>
		<th><?php _e('Language', 'menu'); ?></th>
		<th></th>
	</tr>
	</thead>
	<tbody>
	<?php if( isset($menus) ): $i = 1; foreach($menus as $key => $menu): ?>
	<tr>
		<td><?php print $i; ?></td>
		<td><?php print $menu->name; ?></td>
		<td><?php print $key; ?></td>
		<td><?php print $menu->lang; ?></td>
		<td>
			<a href="<?php print SB_Route::_('index.php?mod=menu&view=edit&key='.$key); ?>" class="btn btn-default">
				<span class="glyphicon glyphicon-pencil"></span>
			</a>
			<a href="<?php print SB_Route::_('index.php?mod=menu&task=delete&key='.$key); ?>" class="btn btn-default">
				<span class="glyphicon glyphicon-trash"></span>
			</a>
		</td>
	</tr>
	<?php $i++; endforeach; endif; ?>
	</tbody>
	</table>
</div>