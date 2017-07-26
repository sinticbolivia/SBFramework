<?php

?>
<div class="wrap">
	<h2>
		<?php _e('Files Storage', 'storage'); ?>
		<span class="pull-right" style="font-size:15px;">
			<?php _e('The file types you can upload are:', 'storage'); ?><br/>
			JPG, TIFF, EPS, AI, PDF, PSD Y CDR <?php  _e('up to 50MB', 'storage') ?>
		</span>
	</h2>
	<form id="form-upload" action="" method="post">
		<div id="storage-uploader"></div>
	</form><br/>
	<form id="form-storage" class="form-search">
		<input type="hidden" name="mod" value="storage" />
		<input type="hidden" name="task" value="upload" />
		<input type="hidden" name="ajax" value="1" />
		<div class="row">
			<div class="col-md-9">
				<div class="form-group">
					<input type="text" name="keyword" value="" class="form-control keyword" />
				</div>
			</div>
			<div class="col-md-1">
				<button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span></button>
			</div>
		</div>
	</form>
	<table id="table-attachments" class="table table-condensed">
	<thead>
	<tr>
		<th><?php _e('Image', 'storage'); ?></th>
		<th><?php _e('File', 'storage'); ?></th>
		<th><?php _e('Description', 'storage'); ?></th>
		<th><?php _e('Type', 'storage'); ?></th>
		<th><?php _e('Actions', 'storage'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php $i = 1; foreach($items as $item): ?>
		<?php include 'attachment-row.php'; ?>
	<?php endforeach; ?>
	</tbody>
	</table>
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
				var uploading_text = '<?php print __('Uploading file {0}', 'storage'); ?>';
				
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
					}
	            } 
	            else 
				{
					alert(responseJSON.error);
	            }
			}
		}
	});
});
</script>