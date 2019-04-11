<?php
use SinticBolivia\SBFramework\Classes\SB_AttachmentImage;

$image = BASEURL . '/images/no-image.png';
$is_image 	= $item->IsImage();
$imgObject 	= null;
if( $is_image )
{
	$imgObject = new SB_AttachmentImage();
	$imgObject->SetDbData($item->_dbData);
}
?>
<tr>
	<td class="text-center">
		<?php print $imgObject->attachment_id; ?>
	</td>
	<td class="text-center">
		<img src="<?php print $is_image ? $imgObject->GetThumbnail('150x150')->GetUrl() : $image; ?>" alt="" width="90" />
	</td>
	<td><?php print basename($item->file); ?></td>
	<td><span class="label label-info"><?php print $item->type; ?></span></td>
	<td class="col-action">
		<a href="javascript:;" class="link-save-green" title="<?php _e('Save', 'storage'); ?>"></a>
		<a href="<?php print b_route('index.php?mod=storage&task=download&id='.$item->attachment_id)?>" class="btn btn-default btn-sm" 
			title="<?php _e('Download', 'storage'); ?>">
			<span class="glyphicon glyphicon-circle-arrow-down"></span>
		</a>
		<a href="<?php print b_route('index.php?mod=storage&task=delete&id='.$item->attachment_id); ?>" class="btn btn-default btn-sm confirm" 
			title="<?php _e('Delete', 'storage'); ?>"
			data-message="<?php _e('Are you sure to delete the file?', 'storage'); ?>">
			<span class="glyphicon glyphicon-trash"></span>
		</a>
		<a href="<?php print is_file($item->GetFilePath()) ? $item->GetFileUrl() : '' ?>" target="_blank" class="btn btn-default btn-sm">
			<span class="glyphicon glyphicon-eye-open"></span>
		</a>
	</td>
</tr>
