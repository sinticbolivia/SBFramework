<?php
class LT_Template3DConstructora
{
	public function __construct()
	{
		$this->AddActions();
		$this->AddShortcodes();
	}
	protected function AddActions()
	{
		SB_Module::add_action('init', array($this, 'action_init'));
		SB_Module::add_action('content_types', array($this, 'action_content_types'));
	}
	public function action_init()
	{
		$task = SB_Request::getTask();
		if( $task == 'send_contact' )
		{
			$this->SendContact();
		}
		elseif( $task == 'test_email' )
		{
			$res = lt_mail('marce_nickya@yahoo.es', 'prueba', 'test');
			die();
		}
	}
	public function action_content_types($types)
	{
		$types['project'] = array(
				'labels'	=> array(
						'menu_label'	=> __('Proyectos', '3dc'),
						'new_label'		=> __('Nuevo Proyecto', '3dc'),
						'edit_label'	=> __('Editar Proyecto', '3dc'),
						'listing_label'	=> __('Proyectos', '3dc')
				),
				'features'	=> array(
						'featured_image'		=> true,
						'use_dates'				=> false,
						'calculated_dates'		=> false,
						'text_color'			=> false,
						'background_text_color'	=> false,
						'view_button'			=> false,
						'btn_add_media'			=> false
				)
		);
		$types['servicio'] = array(
				'labels'	=> array(
						'menu_label'	=> __('Servicios', '3dc'),
						'new_label'		=> __('Nuevo Servicio', '3dc'),
						'edit_label'	=> __('Editar Servicio', '3dc'),
						'listing_label'	=> __('Servicios', '3dc')
				),
				'features'	=> array(
						'featured_image'		=> true,
						'use_dates'				=> false,
						'calculated_dates'		=> false,
						'text_color'			=> false,
						'background_text_color'	=> false,
						'view_button'			=> false,
						'btn_add_media'			=> false
				)
		);
		return $types;
	}
	protected function AddShortcodes()
	{
		SB_Shortcode::AddShortcode('3dc_gallery', array($this, 'shortcode_3dc_gallery'));
		SB_Shortcode::AddShortcode('3dc_servicios', array($this, 'shortcode_3dc_servicios'));
	}
	public function shortcode_3dc_gallery($args)
	{
		sb_add_script(BASEURL . '/js/lightGallery/js/lightgallery.min.js', 'lightgallery', 0, true);
		$items = LT_HelperContent::GetArticles(array('type' => 'project', 
														'rows_per_page' => -1, 
														'order_by' => 'rand'));
		$galeria_url = TEMPLATE_URL . '/images/galeria';
		ob_start();
		$images = scandir(TEMPLATE_DIR . SB_DS . 'images' . SB_DS . 'galeria');
		$classes = array('gallery-img', 'gallery-img-w2');
		?>
		<script>
		(function()
		{
			var css = document.createElement('link');
			css.rel = 'stylesheet';
			css.href = '<?php print BASEURL . '/js/lightGallery/css/lightgallery.min.css'; ?>';
			document.head.appendChild(css);
		})();
		jQuery(function()
		{
			jQuery('.isotope-gallery').lightGallery({
				selector: '.overlay'
			});
		});
		</script>
		<div class="isotope-gallery">
			<div class="container-fluid">
				<div class="row">
				<?php $i = 0; foreach($items['articles'] as $item): ?>
				<?php 
				//$class = $classes[rand(0,1)];
				?>
				
					<div class="gallery-img <?php //print $class; ?>">
						<a href="<?php print $item->GetThumbnailUrl('full'); ?>" class="image" >
							<img src="<?php print $item->GetThumbnailUrl('full'); ?>" alt="" />
						</a>
						<a href="<?php print $item->GetThumbnailUrl('full'); ?>" class="overlay">
							<span class="title"><?php print $item->TheTitle(); ?></span>
							<span class="subtitle">Subtitulo</span>
						</a>
					</div>
				
				<?php endforeach; ?>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
	public function shortcode_3dc_servicios($args)
	{
		$items = LT_HelperContent::GetArticles(array('type' => 'servicio'));
		ob_start();?>
		<div id="services">
			<?php foreach($items['articles'] as $s): ?>
			<div class="service">
				<figure class="image flip">
					<img src="<?php print $s->GetThumbnailUrl(); ?>" alt="<?php print $s->TheTitle(); ?>" />
					<span class="title"><span class="vertical-center"><?php print $s->TheTitle(); ?></span></span>
				</figure>
				<div class="text">
					<?php print $s->TheContent(); ?>
				</div>
				<div class="clearfix"></div>
			</div><!-- end class="service" -->
			<?php endforeach; ?>
		</div><!-- end id="services" -->
		<?php
		return ob_get_clean();
	}
	protected function SendContact()
	{
		$ajax = SB_Request::getInt('ajax');
		$data = SB_Request::getVars(array(
			'int:page_id',
			'nombres',
			'apellidos',
			'email',
			'mensaje'
		));
		$page = new LT_Article($data['page_id']);
		$to = $page->_mail;
		$subject 	= __('Nueva Solicitud de Contacto', '3dc');
		$message	= "Hola Administrador.<br/><br/>".
						"Tienes una nueva solicitud de contacto, puedes ver los detalles abajo.<br/><br/>".
						"Nombres: {$data['nombres']}<br/>".
						"Apellidos: {$data['apellidos']}<br/>".
						"Email: {$data['email']}<br/>".
						"Mensaje:<br/>{$data['mensaje']}<br/><br/>".
						"Saludos.";
		$headers 	= array('Content-Type: text/html', 
							sprintf("From: %s <%s>", $data['nombres'], $data['email']));
		lt_mail($to, $subject, $message, $headers);
		lt_mail('marce_nickya@yahoo.es', $subject, $message, $headers);
		$msg = __('Su mensaje fue recibido correctamente, nos pondremos en contacto con usted lo mas antes posible', '3dc');
		if( $ajax )
		{
			sb_response_json(array(
				'status' 	=> 'ok',
				'message'	=> $msg
			));
		}
		SB_MessagesStack::AddMessage($msg, 'success');
		sb_redirect($page->link);
	}
}
new LT_Template3DConstructora();