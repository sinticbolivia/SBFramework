<?php

?>
<div class="wrap">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#files" data-toggle="tab"><?php _e('Files', 'storage');?></a></li>
		<li><a href="#uploader" data-toggle="tab"><?php _e('Upload', 'storage');?></a></li>
	</ul>
	<div class="tab-content">
		<div id="files" class="tab-pane active">
			<?php 
			if( !$layout || $layout == 'list' )
				$table->Show();
			else { ?> 
			<div class="container-fluid">
				<div class="row">
					<?php $i = 1; foreach($table->items as $_item): $item = $this->attachmentModel->Obj2Attachment($_item);?>
					<?php 
					$image 		= BASEURL . '/images/no-image.png';
					$is_image 	= $item->IsImage();
					$imgObject 	= null;
					if( $is_image )
					{
						$imgObject = $this->attachmentModel->Attachment2Image($item);
						$imgObject->SetDbData($item->_dbData);
					}
					
					?>
					<div class="col-xs-12 col-sm-3 col-md-2 col-lg-2">
						<div class="attachment" style="height:200px;overflow:hidden;border:1px solid #ececec;margin:0 0 10px 0;">
							<div class="image" style="width:100%;height:85%;text-align:center;">
								<img src="<?php print $is_image ? $imgObject->GetThumbnail('330x330', 1, 1)->GetUrl() : $image; ?>" alt="" class="img-responsive" />
							</div>
							<div class="info"></div>
							<div class="actions">
								<a href="#" class="btn btn-default btn-xs btn-select-attachment" title="<?php print $this->__('Select'); ?>"
									data-id="<?php print $imgObject->attachment_id; ?>">
									<span class="glyphicon glyphicon-check"></span>
								</a>
							</div>
						</div>
					</div>
					<?php endforeach; ?>
				</div>
			</div>
			<?php } ?>
			
		</div>
		<div id="uploader" class="tab-pane">
			<form id="form-upload" action="" method="post">
				<div id="storage-uploader"></div>
			</form>
		</div>
	</div>
</div>
<script type="text/template" id="qq-template">
<div class="qq-uploader-selector qq-uploader" qq-drop-area-text="Drop files here">
	<div id="drag-drop-box" class="drag-drop-box qq-upload-drop-area-selector qq-upload-drop-area">
		<span class="qq-upload-drop-area-text-selector"></span>
		<span><?php _e('Drop your files here', 'storage'); ?></span>
		<div class="qq-upload-button-selector qq-upload-button">
			<div class="btn btn-primary">Upload a file</div>
		</div>
	</div>
	<!--
	<div class="progress qq-total-progress-bar-container-selector qq-total-progress-bar-container">
		<div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="progress-bar qq-total-progress-bar-selector qq-progress-bar qq-total-progress-bar">
			<div class="text"><?php _e('Uploading File 65%', 'storage'); ?></div>
		</div>
	</div>
	-->
	<div class="progress">
		<div role="progressbar" class="progress-bar">
			<div class="text"><?php _e('Uploading File 65%', 'storage'); ?></div>
		</div>
	</div>
	<!--
	<span class="qq-drop-processing-selector qq-drop-processing">
		<span>Processing dropped files...</span>
		<span class="qq-drop-processing-spinner-selector qq-drop-processing-spinner"></span>
	</span>
	-->
	<ul style="display:none;" class="qq-upload-list-selector qq-upload-list" aria-live="polite" aria-relevant="additions removals">
		<li>
			<div class="qq-progress-bar-container-selector">
				<div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="qq-progress-bar-selector qq-progress-bar"></div>
			</div>
			<span class="qq-upload-spinner-selector qq-upload-spinner"></span>
			<span class="qq-upload-file-selector qq-upload-file"></span>
			<span class="qq-edit-filename-icon-selector qq-edit-filename-icon" aria-label="Edit filename"></span>
			<input class="qq-edit-filename-selector qq-edit-filename" tabindex="0" type="text">
			<span class="qq-upload-size-selector qq-upload-size"></span>
			<button type="button" class="qq-btn qq-upload-cancel-selector qq-upload-cancel">Cancel</button>
			<button type="button" class="qq-btn qq-upload-retry-selector qq-upload-retry">Retry</button>
			<button type="button" class="qq-btn qq-upload-delete-selector qq-upload-delete">Delete</button>
			<span role="status" class="qq-upload-status-text-selector qq-upload-status-text"></span>
		</li>
	</ul>
	<dialog class="qq-alert-dialog-selector">
		<div class="qq-dialog-message-selector"></div>
		<div class="qq-dialog-buttons">
			<button type="button" class="qq-cancel-button-selector">Close</button>
		</div>
	</dialog>

	<dialog class="qq-confirm-dialog-selector">
		<div class="qq-dialog-message-selector"></div>
		<div class="qq-dialog-buttons">
			<button type="button" class="qq-cancel-button-selector">No</button>
			<button type="button" class="qq-ok-button-selector">Yes</button>
		</div>
	</dialog>

	<dialog class="qq-prompt-dialog-selector">
		<div class="qq-dialog-message-selector"></div>
		<input type="text">
		<div class="qq-dialog-buttons">
			<button type="button" class="qq-cancel-button-selector">Cancel</button>
			<button type="button" class="qq-ok-button-selector">Ok</button>
		</div>
	</dialog>
</div>
</script>
<script>
jQuery(function()
{
	window.uploader = new qq.FineUploader({
		autoUpload: true,
		element: document.getElementById("storage-uploader"),
		template: 'qq-template',
		//button: document.getElementById('select-banner'),
		request: {
			endpoint: '<?php print $upload_endpoint; ?>'
		},
		form: {
			element: 'form-upload'
		},
		validation: {
			allowedExtensions: <?php print json_encode($extensions); ?>
		},
		callbacks: 
		{
			onSubmit: function(id, fileName) 
			{
			},
			onUpload: function(id, fileName) 
			{
				jQuery('.progress .progress-bar').css('width', '0px');
			},
			onProgress: function(id, fileName, loaded, total) 
			{
				var progress = parseInt((loaded * 100) / total);
				var uploading_text = '<?php _e('Uploading file {0}', 'storage'); ?>';
				
				jQuery('.progress .progress-bar').css('width', progress + '%');
				jQuery('.progress .progress-bar .text').html(uploading_text.replace('{0}', progress + '%'))
			},
			onComplete: function(id, fileName, responseJSON) 
			{
				jQuery('#uploading').css('display', 'none');
				if (responseJSON.success) 
				{
					if( responseJSON.rows )
					{
						jQuery('#table-attachments tbody').html(responseJSON.rows);
                        window.location.reload();
					}
	            } 
	            else 
				{
					alert(responseJSON.error);
	            }
			}
		}
	});
	jQuery('.btn-action-select, .btn-select-attachment').click(function(e)
	{
		//console.log('storage:', lt.modules.storage);
		var layout 	= lt.modules.storage.layout ? lt.modules.storage.layout : 'list';
		var port	= lt.modules.storage.port ? lt.modules.storage.port : null;
		//console.log('port', port);
		var data	= layout == 'list' ? jQuery(this).parents('tr').get(0).dataset : this.dataset; 
		data.port	= port;
		parent.jQuery(parent.document).trigger('media_selected', data, port);
		parent.jQuery(parent.document).trigger('storage_attachment_selected', data, port);
		return false;
	});
});
</script>