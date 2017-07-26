<?php
define('LIBROS_UPLOADS_DIR', UPLOADS_DIR . SB_DS . 'libros');
define('LIBROS_UPLOADS_URL', UPLOADS_URL . '/libros');
if( !is_dir(LIBROS_UPLOADS_DIR) )
	mkdir(LIBROS_UPLOADS_DIR);
	
class LT_TemplateQAyllon
{
	public function __construct()
	{
		$this->AddActions();
	}
	public function AddActions()
	{
		SB_Module::add_action('content_types', array($this, 'action_content_types'));
		SB_Module::add_action('init', array($this, 'action_init'));
		if( lt_is_admin() )
		{
			SB_Module::add_action('content_libros_sidebar', array($this, 'action_content_libros_sidebar'));
		}
	}
	public function action_content_types($types)
	{
		$types['page']['features']['use_dates'] = false;
		$types['page']['features']['calculated_dates'] = false;
		$types['post']['features']['use_dates'] = false;
		$types['post']['features']['calculated_dates'] = false;
		$types['servicios'] = array(
					'labels'	=> array(
							'menu_label'	=> __('Servicios', 'qa'),
							'new_label'		=> __('Nuevo Servicio', 'qa'),
							'edit_label'	=> __('Nuevo Servicio', 'qa'),
							'listing_label'	=> __('Servicios', 'qa')
					),
					'features'	=> array(
							'featured_image'	=> true,
							'use_dates'			=> false,
							'calculated_dates'	=> false
					)
		);
		$types['libros'] = array(
					'labels'	=> array(
							'menu_label'	=> __('Libros', 'qa'),
							'new_label'		=> __('Nuevo Libro', 'qa'),
							'edit_label'	=> __('Editar Libro', 'qa'),
							'listing_label'	=> __('Libros', 'qa')
					),
					'features'	=> array(
							'featured_image'	=> true,
							'use_dates'			=> false,
							'calculated_dates'	=> false
					)
		);
		return $types;
	}
	public function action_init()
	{
		$task = SB_Request::getTask();
		if( !$task )
			return false;
		if( $task == 'qa_contacto' )
		{
			$this->EnviarContacto();
		}
		
		$method = 'task_' . str_replace('qa_', '', $task);
		if( method_exists($this, $method) )
		{
			call_user_func(array($this, $method));
		}
	}
	protected function EnviarContacto()
	{
		$data = SB_Request::getVars(array(
			'nombre',
			'telefono',
			'email',
			'mensaje'
		));
		$body = "Hola Administrador<br/><br/>".
				"Tienes una nueva solicitud de contacto, los datos son los siguientes:<br/><br/>".
				"Nombre: {$data['nombre']}<br/>";
		if( !empty($data['telefono']) )
			$body .= "Telefono: {$data['telefono']}<br/>";
		$subject = 'Nuevo solicitud de contacto';
		$body .= "Email: {$data['email']}<br/>".
					"Mensaje:<br/>{$data['mensaje']}<br/><br/>";
		$header = array('Content-type: text/html', 'From: ' . $data['nombre'] . ' <' . $data['email'] .'>');
		lt_mail('contacto@consultoraquispeayllon.com,info@sinticbolivia.net', $subject, $body, $header);
		SB_MessagesStack::AddMessage('Tu mensaje fue enviado correctamente', 'success');
		$redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : SB_Route::_('index.php');
		sb_redirect($redirect);
	}
	public function action_content_libros_sidebar($content)
	{
		if( !$content )
			return false;
		?>
		<div class="widget">
			<h2 class="title"><?php _e('Archivo PDF', 'ps'); ?></h2>
			<div class="body">
				<div id="pdf-container">
					<?php if( $content->_pdf_file ): ?>
					<a href="<?php print UPLOADS_DIR . '/libros/' . $content->_pdf_file; ?>" target="_blank">
						<?php print $content->_pdf_file; ?>
					</a>
					<?php endif; ?>
				</div>
				<div>&nbsp;</div>
				<div id="select-pdf" class="btn btn-default btn-xs">
					<?php _e('Subir PDF', 'ps'); ?>
				</div>
				<a href="javascript:;" id="btn-remove-pdf" class="btn btn-danger btn-xs"
					style="<?php print $content->_pdf_file ? '' : 'display:none;'; ?>">
					<?php _e('Borrar', 'content'); ?>
				</a>
				<div id="uploading-pdf" style="display:none;">
					<?php _e('Subiendo archivo...', 'ps'); ?>
				</div>
			</div>
		</div>
		<script>
		jQuery(function()
		{
			window.pdf_uploader = new qq.FineUploaderBasic({
				button: document.getElementById('select-pdf'),
				request: {
					endpoint: '<?php print SB_Route::_('index.php?task=qa_upload_pdf&id='.$content->content_id); ?>'
				},
				validation: {allowedExtensions: ['pdf']},
				callbacks: 
				{
					onUpload: function(id, fileName) {jQuery('#uploading-pdf').css('display', 'block');},
					onComplete: function(id, fileName, res) 
					{
						jQuery('#uploading-pdf').css('display', 'none');
						if (res.success) 
						{
							jQuery('#pdf-container').append('<a href="'+res.pdf_url+'" target="_blank">'+res.pdf_file+'</a>');
							jQuery('#btn-remove-pdf').css('display', 'inline');
						} 
						else 
						{
							alert(res.error);
						}
					}
				}
			});
			jQuery(document).on('click', '#btn-remove-pdf', function()
			{
				jQuery.get('<?php print SB_Route::_('index.php?task=qa_delete_pdf&id='.$content->content_id)?>', function(res)
				{
					if( res.status == 'ok' )
					{
						jQuery('#pdf-container').html('');
						jQuery('#btn-remove-pdf').css('display', 'none');
					}
					else
					{
						alert(res.error);
					}
				});
			});
		});
		</script>
		<?php
	}
	public function task_upload_pdf()
	{
		$id = SB_Request::getInt('id');
		if( !$id )
		{
			sb_response_json(array('success' => false, 'error' => __('Invalid content identifier', 'content')));
		}
		
		sb_include('qqFileUploader.php', 'file');
		$uh = new qqFileUploader();
		$uh->allowedExtensions = array('pdf');
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
		$res['pdf_url']		= LIBROS_UPLOADS_URL . '/' . $uh->getUploadName();
		$res['uploadName'] 	= $uh->getUploadName();
		$res['pdf_file']	= $uh->getUploadName();
		
		$pdf = sb_get_content_meta($id, '_pdf_file');
		if( $pdf && file_exists(LIBROS_UPLOADS_DIR . SB_DS . $pdf) )
		{
			@unlink(LIBROS_UPLOADS_DIR . SB_DS . $pdf);
		}
		sb_update_content_meta($id, '_pdf_file', $uh->getUploadName());
		sb_response_json($res);
	}
}
new LT_TemplateQAyllon();