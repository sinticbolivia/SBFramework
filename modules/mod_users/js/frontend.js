/**
 * 
 */
jQuery(function()
{
	var uploader = new qq.FineUploaderBasic({
		//element: document.getElementById("uploader"),
		//template: 'qq-template-gallery',
		button: document.getElementById('select-image'),
		request: {
			endpoint: mod_users.upload_endpoint
		},
		validation: {
			allowedExtensions: ['jpeg', 'jpg', 'gif', 'png']
		},
		callbacks: 
		{
			onSubmit: function(id, fileName) 
			{
				//$messages.append('<div id="file-' + id + '" class="alert" style="margin: 20px 0 0"></div>');
			},
			onUpload: function(id, fileName) 
			{
				jQuery('#uploading').css('display', 'block');
			},
			onProgress: function(id, fileName, loaded, total) 
			{
				/*
				if (loaded < total) 
				{
					progress = Math.round(loaded / total * 100) + '% of ' + Math.round(total / 1024) + ' kB';
	              	jQuery('#file-' + id).removeClass('alert-info')
	                              .html('<img src="client/loading.gif" alt="In progress. Please hold."> ' +
	                                    'Uploading ' +
	                                    '“' + fileName + '” ' +
	                                    progress);
				} 
				else 
				{
					jQuery('#file-' + id).addClass('alert-info')
	                              .html('<img src="client/loading.gif" alt="Saving. Please hold."> ' +
	                                    'Saving ' +
	                                    '“' + fileName + '”');
	            }
	            */
			},
			onComplete: function(id, fileName, responseJSON) 
			{
				jQuery('#uploading').css('display', 'none');
				if (responseJSON.success) 
				{
					jQuery('#select-image img:first').attr('src', responseJSON.image_url);
					jQuery('#imagefile').val(responseJSON.uploadName);
	            } 
	            else 
				{
					alert(responseJSON.error);
	            }
			}
		}
	});
});