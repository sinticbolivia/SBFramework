<?php
?>
<tr>
	<td class="text-center">
		<img src="<?php print file_exists(UPLOADS_DIR . SB_DS . $item->file) ? UPLOADS_URL . '/' . $item->file : '' ?>" alt="" width="90" />
	</td>
	<td><?php print basename($item->file); ?></td>
	<td>
		<textarea name="attachment[<?php print $item->attachment_id; ?>][description]" class="form-control"><?php print $item->description; ?></textarea>
	</td>
	<td>
		<select name="" class="form-control"></select>
	</td>
	<td class="col-action">
		<a href="javascript:;" class="link-save-green" title="<?php _e('Save', 'storage'); ?>"></a>
		<a href="<?php print SB_Route::_('index.php?mod=storage&task=download&id='.$item->attachment_id)?>" class="btn btn-default btn-sm" 
			title="<?php _e('Download', 'storage'); ?>">
			<span class="glyphicon glyphicon-circle-arrow-down"></span>
		</a>
		<a href="<?php print SB_Route::_('index.php?mod=storage&task=delete&id='.$item->attachment_id); ?>" class="btn btn-default btn-sm confirm" 
			title="<?php _e('Delete', 'storage'); ?>"
			data-message="<?php _e('Are you sure to delete the file?', 'storage'); ?>">
			<span class="glyphicon glyphicon-trash"></span>
		</a>
	</td>
</tr>