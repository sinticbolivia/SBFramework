<?php
use SinticBolivia\SBFramework\Classes\SB_Controller;
use SinticBolivia\SBFramework\Classes\SB_Request;
use SinticBolivia\SBFramework\Classes\SB_Route;
use SinticBolivia\SBFramework\Classes\SB_MessagesStack;
use SinticBolivia\SBFramework\Classes\SB_Attachment;
use SinticBolivia\SBFramework\Classes\SB_AttachmentImage;
use SinticBolivia\SBFramework\Classes\SB_TableList;


class LT_AdminControllerStorage extends SB_Controller
{
	/**
	 * 
	 * @var \SinticBolivia\SBFramework\Modules\Storage\Models\AttachmentModel
	 */
	protected $attachmentModel;
	
	public function task_default()
	{
		$limit	= $this->request->getInt('limit', defined('ITEMS_PER_PAGE') ? ITEMS_PER_PAGE : 25);
		$layout	= $this->request->getString('layout', 'list');
		
		$query = "SELECT * FROM attachments 
					WHERE 1 = 1
					AND parent = 0
					ORDER BY creation_date DESC
					LIMIT $limit";
		$class = 'SinticBolivia\SBFramework\Classes\SB_AttachmentImage';
		
		$items = $this->dbh->FetchResults($query, $class);
		$extensions = array(
				'jpg', 'jpeg',
				'png',
				'gif',
				'tiff',
				'eps',
				'ai',
				'pdf',
				'psd',
				'cdr',
				'zip',
				'tar.gz',
				'mp4'
		);
		$upload_endpoint = $this->Route('index.php?mod=storage&task=upload');
		$this->SetVars(get_defined_vars());
		sb_add_style('storage', MOD_STORAGE_URL . '/css/styles.css');
		sb_add_script(BASEURL . '/js/fineuploader/all.fine-uploader.min.js', 'fineuploader');
	}
	public function task_upload()
	{
		$storage_dir = UPLOADS_DIR . SB_DS . 'storage';
		//##check if storage dir exists, otherwise create it
		if( !is_dir($storage_dir) )
			mkdir($storage_dir);
		
		sb_include('qqFileUploader.php', 'file');
		$uh = new qqFileUploader();
		$uh->allowedExtensions = array('jpg', 'jpeg', 'gif', 'png', 'bmp', 'psd', 'tiff', 'ai', 'zip', 'cdr', 'tar.gz', 'mp4');
		//$uh->sizeLimit = 10 * 1024 * 1024; //10MB
		$uh->inputName = 'qqfile';
		// If you want to use resume feature for uploader, specify the folder to save parts.
		$uh->chunksFolder = 'chunks';
		$res = $uh->handleUpload($storage_dir);
        if( isset($res['error']) )
        {
            sb_response_json($res);
        }
        
		$filename 	= $uh->getUploadName();
		//##get uploaded file mime type
		$mime 		= sb_get_file_mime($storage_dir . SB_DS . $filename);
		$type		= strstr($mime, 'image') ? 'image' : 'file';
		
		$id = lt_insert_attachment($storage_dir . SB_DS . $filename, null, null, 0, $type);
		/*
		
		//##get file extension
		$extension = sb_get_file_extension($storage_dir . SB_DS . $filename);
		//## insert the file into database
		$data = array(
				'object_type' 	=> '',
				'object_id'		=> '',
				'title'			=> sb_build_slug($filename),
				'description'	=> '',
				'type'			=> $extension,
				'mime'			=> $mime,
				'file'			=> basename($storage_dir) . '/' . $filename,
				'size'			=> filesize($storage_dir . SB_DS . $filename),
				'parent'		=> 0,
				'last_modification_date'	=> date('Y-m-d H:i:s'),
				'creation_date'	=> date('Y-m-d H:i:s')
		);
		$id = $this->dbh->Insert('attachments', $data);
		*/
		$query = "SELECT * FROM attachments ORDER BY creation_date DESC LIMIT 25";
		$items = $this->dbh->FetchResults($query);
		ob_start();
		foreach($items as $_item)
		{
			$item = new SB_Attachment();
			$item->SetDbData($_item);
			include 'views/admin/attachment-row.php';
		}
		$res['rows'] = ob_get_clean();
		sb_response_json($res);
	}
	public function task_download()
	{
		$id = SB_Request::getInt('id');
		if( !$id )
		{
			SB_MessagesStack::AddMessage(__('Invalid file identifier', 'storage'), 'error');
			sb_redirect(SB_Route::_('index.php?mod=storage'));
		}
		$attachment = new SB_Attachment($id);
		if( !$attachment->attachment_id )
		{
			SB_MessagesStack::AddMessage(__('The file requested does not exists', 'storage'), 'error');
			sb_redirect(SB_Route::_('index.php?mod=storage'));
		}
		$filename = STORAGE_DIR . SB_DS . basename($attachment->file);
		if( !file_exists($filename) )
		{
			lt_die(__('The file you are trying to download does not exists', 'storage'));
		}
		header('Content-Type: ' . $attachment->mime);
		header('Content-Disposition: attachment; filename=' . basename($attachment->file));
		header('Pragma: no-cache');
		readfile($filename);
		die();
	}
	public function task_uploader()
	{
		$layout	= $this->request->getString('layout', 'list');
		$port	= $this->request->getString('port');
		/*
		$this->dbh->Select('COUNT(*)')
					->From('attachments')
					->Where(array('parent' => 0));
		
		$this->dbh->Query(null);
		$total_rows = (int)$this->dbh->GetVar();
		*/
		$table = new SB_TableList('attachments', 'attachment_id', 'storage');
		$table->SetColumns(array(
				'attachment_id'		=> array('label' => __('ID', 'storage')),
				'image'				=> array(
						'label' 	=> __('Image', 'storage'), 
						'db_col' 	=> false, 
						'callback' 	=> 'mod_storage_show_table_column_image'
				),
				'file'				=> array('label' => __('File', 'storage')),
				'mime'				=> array('label' => __('Type', 'storage'))
		));
		$table->SetRowActions(array(
				'select' => array('link' => '', 'label' => __('Select', 'storage'), 'icon' => 'glyphicon glyphicon-check'), 
				'task:delete' => array('link' => '', 'label' => __('Delete', 'storage'), 'icon' => 'glyphicon glyphicon-trash')));
		$table->showCount = true;
		$table->AddCondition('parent', '=', 0);
		$table->Fill();
		$extensions = array(
				'jpg', 'jpeg',
				'png',
				'gif',
				'tiff',
				'eps',
				'ai',
				'pdf',
				'psd',
				'cdr'
		);
		$upload_endpoint = SB_Route::_('index.php?mod=storage&task=upload');
		
		$this->SetVars(get_defined_vars());
		sb_add_js_global_var('storage', 'layout', $layout);
		sb_add_js_global_var('storage', 'port', $port);
		sb_add_style('storage', MOD_STORAGE_URL . '/css/styles.css');
		sb_add_script(BASEURL . '/js/fineuploader/all.fine-uploader.min.js', 'fineuploader');
	}
    public function task_delete()
    {
        $id = SB_Request::getInt('id');
        $attachment = new SB_Attachment($id);
        
        if( !$attachment || !$attachment->attachment_id )
        {
			SB_MessagesStack::AddError(__('The attachment does not exists', 'storage'));
            sb_redirect(SB_Route::_('index.php?mod=storage'));
        }
        $attachment->Delete();
        SB_MessagesStack::AddMessage(__('The attachment has been deleted', 'storage'), 'success');
        sb_redirect(SB_Route::_('index.php?mod=storage'));
    }
    public function ajax_get_attachment()
    {
    	$id = $this->request->getInt('id');
    	if( !$id )
    		throw new Exception($this->__('The attachment id is invalid'));
    	$attachment = new SB_Attachment($id);
    	if( !$attachment->attachment_id )
    		throw new Exception($this->__('The attachment does not exists'));
    	
    	sb_response_json(array('status' => 'ok', 'attachment' => $attachment));
    
    }
}
