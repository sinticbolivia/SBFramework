<?php
class LT_ThemeOMarketing
{
	public function __construct()
	{
		define('LIBROS_UPLOADS_URL', UPLOADS_URL . '/libros');
		define('LIBROS_UPLOADS_DIR', UPLOADS_DIR . SB_DS . 'libros');
		if( !is_dir(LIBROS_UPLOADS_DIR) )
			mkdir(LIBROS_UPLOADS_DIR);
		$this->AddActions();
	}
	protected function AddActions()
	{
		SB_Module::add_action('modules_loaded', array($this, 'action_modules_loaded'));
		SB_Module::add_action('init', array($this, 'action_init'));
		SB_Module::add_action('content_types', array($this, 'action_content_types'));
		if( lt_is_admin() )
		{
			SB_Module::add_action('content_libro_sidebar', array($this, 'action_content_libro_sidebar'));
			SB_Module::add_action('save_article', array($this, 'action_save_libro'));
		}
	}
	public function action_modules_loaded()
	{
		
	}
	public function action_content_types($types)
	{
		$types['libro'] = array(
			'labels'	=> array(
					'menu_label'	=> __('Libros', 'om'),
					'new_label'		=> __('Nuevo Libro', 'om'),
					'edit_label'	=> __('Editar Libro', 'om'),
					'listing_label'	=> __('Libros', 'om')
			),
			'features'	=> array(
					'featured_image'		=> true,
					'use_dates'				=> false,
					'calculated_dates'		=> false,
					'text_color'			=> false,
					'background_text_color'	=> false,
					'view_button'			=> true,
					'btn_add_media'			=> true
			),
			'section'	=> array(
				'for_object'	=> 'libro',
				'labels'		=> array(
						'menu_label' 	=> '<span class="glyphicon glyphicon-folder-open"></span> Categoria', 
						'new_label' 	=> 'Nueva Categoria',
						'edit_label'	=> 'Editar Categoria',
						'listing_label'	=> 'Categorias de Libros'
				)
			)
		);
		return $types;
	}
	public function action_init()
	{
		$task = SB_Request::getTask();
		if( $task == 'test_email' )
		{
			$res = lt_mail('marce_nickya@yahoo.es', 'Prueba', 'Mensaje');
			var_dump($res);die();
		}
		elseif( $task == 'send_contact' )
		{
			$this->EnviarContacto();
		}
		if( lt_is_admin() )
		{
			if( $task == 'om_upload_doc' )
				$this->UploadDocument();
			elseif( $task == 'om_delete_doc' )
				$this->DeleteDocument();
		}
	}
	public function action_content_libro_sidebar($libro)
	{
		$endpoint = $libro ? SB_Route::_('index.php?task=om_upload_doc&id=' . $libro->content_id) : 
							SB_Route::_('index.php?task=om_upload_doc');
		?>
		<div class="widget">
			<h2 class="title"><?php _e('Documento', 'om'); ?></h2>
			<div class="body">
				<div id="doc-container">
					<?php if( $libro->_doc_id ): $doc = new SB_Attachment($libro->_doc_id); ?>
					<a href="<?php print UPLOADS_URL . '/' . $doc->file; ?>" target="_blank">
						<?php print basename($doc->file); ?>
					</a>
					<?php endif; ?>
				</div>
				<div>&nbsp;</div>
				<div id="select-doc" class="btn btn-default btn-xs">
					<?php _e('Subir Documento', 'om'); ?>
				</div>
				<a href="javascript:;" id="btn-remove-doc" class="btn btn-danger btn-xs"
					style="<?php print $libro && $libro->_doc_id ? '' : 'display:none;'; ?>"
					data-docid="<?php print $libro ? (int)$libro->_doc_id : 0; ?>">
					<?php _e('Borrar', 'content'); ?>
				</a>
				<div id="uploading-doc" style="display:none;">
					<?php _e('Subiendo archivo...', 'om'); ?>
					<img src="<?php print BASEURL; ?>/images/spin.gif" alt=""/>
				</div>
			</div><!-- end class="body" -->
		</div>
		<script>
		jQuery(function()
		{
			window.doc_uploader = new qq.FineUploaderBasic({
				button: document.getElementById('select-doc'),
				request: {
					endpoint: '<?php print $endpoint; ?>'
				},
				validation: {allowedExtensions: ['pdf','xls', 'xlsx', 'doc', 'docx', 'ppt', 'pptx', 'pps', 'ppsx']},
				callbacks: 
				{
					onUpload: function(id, fileName) {jQuery('#uploading-doc').css('display', 'block');},
					onComplete: function(id, fileName, res) 
					{
						jQuery('#uploading-doc').css('display', 'none');
						if (res.success) 
						{
							jQuery('#doc-container').html('<a href="'+res.doc_url+'" target="_blank">'+res.doc_file+'</a>');
							jQuery('#btn-remove-doc').css('display', 'inline');
							jQuery('#btn-remove-doc').get(0).dataset.docid = res.doc_id;
						} 
						else 
						{
							alert(res.error);
						}
					}
				}
			});
			jQuery(document).on('click', '#btn-remove-doc', function()
			{
				if( !this.dataset.docid || parseInt(this.dataset.docid) <= 0 )
					return false;
					
				jQuery.get('<?php print SB_Route::_('index.php?task=om_delete_doc&id='); ?>' + this.dataset.docid, 
				function(res)
				{
					if( res.status == 'ok' )
					{
						jQuery('#doc-container').html('');
						jQuery('#btn-remove-doc').css('display', 'none');
					}
					else
					{
						alert(res.error);
					}
				});
				return false;
			});
		});
		</script>
		<?php
	}
	protected function EnviarContacto()
	{
		$ajax 		= SB_Request::getInt('ajax');
		$page_id	= SB_Request::getInt('id');
		$page		= new LT_Article($page_id);
		if( !$page->content_id )
		{
			$msg = __('La pagina que estas buscando no existe', 'om');
			if( $ajax )
			{
				sb_response_json(array('status' => 'error', 'error' => $msg));
			}
			SB_MessagesStack::AddMessage($msg, 'error');
			return false;
		}
		$data = SB_Request::getVars(array(
			'firstname',
			'lastname',
			'email',
			'message'
		));
		$subject = 'Nueva Solicitud de Contacto';
		$message = "Hola Administrador.<br/><br/>".
					"Tienes una nueva solicitud de contacto, puedes ver los detalles a continuaci&oacute;n.<br/><br/>".
					"Nombre: {$data['firstname']}<br/>".
					"Apellido: {$data['firstname']}<br/>".
					"Email: {$data['firstname']}<br/>".
					"Mensaje: <br/>{$data['firstname']}<br/><br/>";
		$headers = array(
			'from'	=> "From: {$data['firstname']} {$data['lastname']} <{$data['email']}>",
			'type'	=> "Content-type: text/html"
		);
		lt_mail($page->_email, $subject, $message, $headers);
		$msg = 'Acabamos de recibir tu mensaje, nos pondremos en contacto con usted en un lapso de 24 horas.';
		if( $ajax )
			sb_response_json(array('status' => 'ok', 'message' => $msg));
		SB_MessagesStack::AddMessage($msg, 'success');
		sb_redirect($page->link);
	}
	protected function UploadDocument()
	{
		$id = SB_Request::getInt('id');
				
		sb_include('qqFileUploader.php', 'file');
		$uh = new qqFileUploader();
		$uh->allowedExtensions = array('pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'pps', 'ppsx');
		// Specify the input name set in the javascript.
		$uh->inputName = 'qqfile';
		// If you want to use resume feature for uploader, specify the folder to save parts.
		$uh->chunksFolder = 'chunks';
		$res = $uh->handleUpload(LIBROS_UPLOADS_DIR);
		if( isset($res['error']) )
		{
			sb_response_json($res);
		}
		$file_path 			= LIBROS_UPLOADS_DIR . SB_DS . $uh->getUploadName();
		$res['doc_url']		= LIBROS_UPLOADS_URL . '/' . $uh->getUploadName();
		$res['uploadName'] 	= $uh->getUploadName();
		$res['doc_file']	= $uh->getUploadName();
		
		//##verificar si el id del libro fue enviado
		if( $id )
		{
			$libro = new LT_Article($id);
			if( $libro->_doc_id )
			{
				$doc = new SB_Attachment($libro->_doc_id);
				if( $doc->attachment_id )
					$doc->Delete();
			}
			$doc_id = lt_insert_attachment($file_path, 'libro', $id, 0, 'doc');
			sb_update_content_meta($id, '_doc_id', $doc_id);
			$res['doc_id']		= $doc_id;
		}
		else
		{
			$doc_id = lt_insert_attachment($file_path, 'libro', 0, 0, 'doc');
			SB_Session::setVar('libro_doc_id', $doc_id);
			$res['doc_id']		= $doc_id;
		}
		
		sb_response_json($res);
	}
	protected function DeleteDocument()
	{
		$id = SB_Request::getInt('id');
		$doc = new SB_Attachment($id);
		if( $doc->attachment_id )
		{
			$doc->Delete();
			sb_update_content_meta($id, '_doc_id', null);
		}
		sb_response_json(array('status' => 'ok'));
	}
	public function action_save_libro($libro_id, $updated)
	{
		$type = SB_Request::getString('type');
		if( $type != 'libro' )
			return false;
		$doc_id = SB_Session::getVar('libro_doc_id');
		if( !$doc_id )
			return false;
		$dbh = SB_Factory::GetDbh();
		$dbh->Update('attachments', array('object_id' => $libro_id), array('attachment_id' => $doc_id));
		sb_update_content_meta($libro_id, '_doc_id', $doc_id);
		SB_Session::unsetVar('libro_doc_id');
	}
}
new LT_ThemeOMarketing();