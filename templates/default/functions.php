<?php 
define('SKIP_SEC_QUESTIONS', 1);
require_once dirname(__FILE__) . SB_DS . 'exclusivo.php';

class LT_ThemeDefault
{
	protected	$template_dir;
	protected	$content_shortcodes = array();
	
	public function __construct()
	{
		$this->template_dir = dirname(__FILE__);
		$this->content_shortcodes = array(
				//'username'		=> SBText::_('Usuario'), 
				'first_name'	=> SBText::_('Nombre'),
				'last_name'		=> SBText::_('Apellidos'),
				'current_date'	=> SBText::_('Fecha actual'),
				'current_time'	=> SBText::_('Hora actual'),
				'article_btn'	=> SBText::_('Boton de contenido'),
				'section_btn'	=> SBText::_('Boton de seccion'),
				'web_open'		=> SBText::_('A&ntilde;adir iframe'),
				'pdf_open'		=> __('Abrir Pdf'),
				'google_folder'	=> __('Google Drive'),
				'btn_paypal'	=> __('Boton Paypal')
		);
		SB_Language::loadLanguage(LANGUAGE, 'tpl_def', $this->template_dir . SB_DS . 'locale');
		$this->AddActions();
	}
	protected function AddActions()
	{
		SB_Module::add_action('init', array($this, 'action_init'));
		if( lt_is_admin() )
		{
			SB_Module::add_action('after_process_module', array($this, 'action_after_process_module'));
			SB_Module::add_action('admin_menu', array($this, 'action_admin_menu'));
			SB_Module::add_action('settings_tabs', array($this, 'action_settings_tabs'));
			SB_Module::add_action('settings_tabs_content', array($this, 'action_settings_tabs_content'));
			SB_Module::add_action('after_general_settings', array($this, 'action_after_general_settings'));
			SB_Module::add_action('save_settings', array($this, 'action_save_settings'));
			SB_Module::add_action('on_create_new_user', array($this, 'action_on_create_new_user'));
			SB_Module::add_action('content_options', array($this, 'action_content_options'), 5);
			SB_Module::add_action('after_article_options', array($this, 'action_article_sidebar'), 0);
			SB_Module::add_action('save_article', array($this, 'action_save_article'));
			SB_Module::add_action('section_fields', array($this, 'action_section_fields'));
			SB_Module::add_action('save_section', array($this, 'action_save_section'));
			SB_Module::add_action('user_tabs', array($this, 'action_user_tabs'));
			SB_Module::add_action('user_tabs_content', array($this, 'action_user_tabs_content'));
			SB_Module::add_action('after_user_tab_personal', array($this, 'action_after_user_tab_personal'), 1);
			SB_Module::add_action('save_user', array($this, 'action_save_user'));
		} 
		else 
		{
			SB_Module::add_action('before_process_template', array($this, 'action_before_process_template'));
			SB_Module::add_action('authenticated', array($this, 'action_authenticated'));
			SB_Module::add_action('lt_footer', array($this, 'action_lt_footer'));
			SB_Module::add_action('section_query_contents_args', array($this, 'action_section_query_contents_args'));
			SB_Module::add_action('content_is_visible', array($this, 'action_content_is_visible'), 11);
			SB_Module::add_action('content_section_is_visible', array($this, 'action_content_section_is_visible'));
			SB_Module::add_action('before_show_content', array($this, 'action_before_show_content'));
			SB_Module::add_action('before_article_not_found', array($this, 'action_before_article_not_found'));
			function __before_after_shortcode()
			{
				return '<div style="border:1px solid #000;"></div>';
			}
			//SB_Module::add_action('before_shortcode', '__before_after_shortcode');
			//SB_Module::add_action('after_shortcode', '__before_after_shortcode');
		}
		SB_Module::add_action('register_user_email_subject', array($this, 'action_register_user_email_subject'));
		SB_Module::add_action('register_user_email_body', array($this, 'action_register_user_email_body'));
		SB_Module::add_action('users_new_email_headers', array($this, 'action_users_new_email_headers'));
	} 
	public function action_init()
	{
		if( defined('LT_ADMIN') )
		{
			$this->HandleAdminRequests();
		}
		else
		{
			if( SB_Request::getTask() == 'send_contact_form' )
			{
				$contact_email = defined('CONTACT_EMAIL') ? CONTACT_EMAIL : null;
				if( !$contact_email  )
				{
					SB_MessagesStack::AddMessage(SBText::_('No se definio un email para la recepcion del formulario de contacto.'), 'error');
					return false;
				}
				sb_include_module_helper('forms');
				$user 		= sb_get_current_user();
				$email 		= $user->email;
				$subject 	= SB_Request::getString('subject');
				$message 	= SB_Request::getString('message');
				$mailer 	= LT_HelperForms::GetMailerInstance();
				
				$mailer->isHTML(1);
				$mailer->addAddress($contact_email);
				$mailer->From		= FORMS_EMAIL_FROM;
				$mailer->FromName	= SITE_TITLE;
				$mailer->Subject 	= $subject;
				$mailer->Body 		= "Mensaje enviado por el Usuario: $user->first_name $user->last_name, desde formulario de contacto de " . 
										SITE_TITLE . 
										"<br/<br/><br/>".
										"$message<br/>";
				$res = $mailer->send();
				if( !$res )
				{
					SB_MessagesStack::AddMessage(__('Ocurrio un error tratanto de enviar el correo.<br/>') . $mailer->ErrorInfo, 'error');
					sb_redirect(SB_Route::_('index.php?tpl_file=tpl-contact'));
				}
				sb_redirect(SB_Route::_('index.php?tpl_file=tpl-contact&sent=1'));
			}
			elseif( SB_Request::getTask() == 'paypal_cb' )
			{
				$this->PaypalIPN();
			}
			elseif( SB_Request::getTask() == 'click2sell_cb' )
			{
				$this->Click2SellCallback();
			}
			foreach($this->content_shortcodes as $short_code => $label)
			{
				if( $short_code == 'article_btn' || $short_code == 'section_btn' ) continue;
				SB_Shortcode::AddShortcode($short_code, array($this, 'shortcode_' . $short_code));
			}
			
		}
	}
	public function action_admin_menu()
	{
		SB_Menu::addMenuPage(SBText::_('Ayuda'), 'javascript:;', 'help-menu', 'manage_backend', 11);
		SB_Menu::addMenuChild('help-menu', 
								'<span class="glyphicon glyphicon-question-sign"></span>' . SBText::_('Ayuda y Formacion'), 
								'http://500sitios.com/ayudaprvm', 'menu-help-and-trining', 
								'manage_backend', 0, array('target' => '_blank'));
		SB_Menu::addMenuChild('help-menu', 
								'<span class="glyphicon glyphicon-wrench"></span>' . SBText::_('Soporte Tecnico'), 
								'http://500sitios.com/soporteprvm', 'menu-help-suport', 
								'manage_backend', 0, array('target' => '_blank'));
		$backend_menus		= (object)sb_get_parameter('backend_menus', new stdClass());
		if( !empty($backend_menus) && count(get_object_vars($backend_menus)) )
		{
			SB_Menu::addMenuPage(SBText::_('Extras'), 'javascript:;', 'menu-extras', 'manage_backend', 12);
			foreach($backend_menus as $index => $menu)
			{
				if( !isset($menu->show) || (int)$menu->show != 1 ) continue;
				if( trim($menu->label) == '' ) continue;
				$args = array('target' => '_blank');
				if( isset($menu->username) )
				{
					$args['class'] 			= 'remote-login';
					$args['data-username'] 	= $menu->username;
					$args['data-password'] 	= $menu->password;
				}
				SB_Menu::addMenuChild('menu-extras', trim($menu->label), $menu->link, 'menu-extra-' . $index, 'manage_backend', 0, $args);
				if( isset($menu->username) )
				{
					sb_add_script(TEMPLATES_URL . '/default/js/menu-login.js', 'menu-login-js');
				}
			}
			
		}
	}
	public function action_after_process_module()
	{
		if( defined('LT_ADMIN') && SB_Request::getString('mod') == 'settings' )
		{
			sb_add_script(BASEURL . '/js/fineuploader/all.fine-uploader.min.js', 'fineuploader');
		}
	}
	public function action_settings_tabs($settings)
	{
		$user = sb_get_current_user();
		?>
		<?php if( $user->can('manage_design_settings') || $user->can('manage_settings') ): ?>
		<li>
			<a href="#design" class="has-popover" data-content="<?php print SBText::_('TPL_TAB_DESIGN'); ?>">
				<?php print SB_Text::_('Dise&ntilde;o'); ?></a></li>
		<?php endif; ?>
		<?php if( $user->can('manage_limit_settings') ): ?>
		<li>
			<a href="#user-settings" class="has-popover" data-content="<?php print SBText::_('TPL_TAB_APPLICATION'); ?>">
				<?php print SB_Text::_('Aplicacion'); ?></a></li>
		<?php endif; ?>
		<?php if( $user->IsRoot() ): ?>
		<li>
			<a href="#backend-menus" class="has-popover" data-content="<?php print SBText::_('TPL_TAB_MENUS'); ?>">
				<?php print SBText::_('Menus'); ?></a></li>
		<li>
			<a href="#user-scripts" class="has-popover" data-content="<?php print SBText::_('TPL_TAB_SCRIPTS'); ?>">
				<?php print SBText::_('Scripts'); ?></a></li>
		<?php endif; ?>
		<?php
	}
	public function action_settings_tabs_content($settings)
	{
		$site_logo_url 		= null;
		$bg_url 			= null;
		$header_image_url 	= null;
		$site_logo			= sb_get_parameter('site_logo', null);
		$bg_image 			= sb_get_parameter('bg_image', null);
		$header_image 		= sb_get_parameter('header_image', null);
		$backend_menus		= (object)sb_get_parameter('backend_menus', new stdClass());
		$user_scripts		= (object)sb_get_parameter('user_scripts', new stdClass());
		if( $site_logo && file_exists(UPLOADS_DIR . SB_DS . $site_logo) )
		{
			$site_logo_url = UPLOADS_URL . '/' . $site_logo;
		}
		
		if( is_object($bg_image) )
		{
			$bg_url = $bg_image->url;
			$bg_image = $bg_image->file;
		}
		elseif( $bg_image && file_exists(UPLOADS_DIR . SB_DS . $bg_image) )
		{
			$bg_url = UPLOADS_URL . '/' . $bg_image;
		}
		//##check header image
		if( $header_image && file_exists(UPLOADS_DIR . SB_DS . $header_image) )
		{
			$header_image_url = UPLOADS_URL . '/' . $header_image;
		}
		
		$upload_endpoint = SB_Route::_('index.php?task=upload_logo');
		$upload_bg_endpoint = SB_Route::_('index.php?task=upload_bg_image');
		$upload_header_endpoint = SB_Route::_('index.php?task=upload_header_image');
		require_once $this->template_dir . SB_DS . 'pages' . SB_DS . 'admin' . SB_DS . 'settings-design.php';
	}
	public function action_after_general_settings($settings)
	{
		?>
		<div class="row">
			<div class="col-md-5">
				<div class="form-group">
					<label class="has-popover" data-content="<?php print SBText::_('TPL_CONTACT_EMAIL_LABEL'); ?>">
						<?php print SBText::_('Email que recibe formulario de Contacto:'); ?>
					</label>
					<input type="text" name="settings[CONTACT_EMAIL]" value="<?php print defined('CONTACT_EMAIL') ? CONTACT_EMAIL : ''; ?>" 
							class="form-control" />
				</div>
				<div class="form-group">
					<label>Email de Verificacion</label>
					<input type="email" name="settings[EMAIL_VERIFICATION]" value="<?php print @$settings->EMAIL_VERIFICATION ?>">
				</div>
			</div>
		</div>
		<?php
	}
	public function action_save_settings()
	{
		sb_update_parameter('max_users', SB_Request::getInt('max_users'));
		sb_update_parameter('backend_menus', SB_Request::getVar('backend_menus'));
		sb_update_parameter('user_scripts', SB_Request::getVar('user_scripts'));
	}
	public function action_before_process_template()
	{
		if( !lt_is_admin() )
		{
			if( !sb_is_user_logged_in() && SB_Request::getString('mod') != 'users' )
			{
				sb_redirect(SB_Route::_('index.php?mod=users'));
			}
			elseif( sb_is_user_logged_in() )
			{
				//sb_redirect(SB_Route::_('index.php?mod=users'));
			}
		}
	}
	public function action_on_create_new_user()
	{
		$max_users = (int)sb_get_parameter('max_users', null);
		if( !$max_users )
			return false;
		$dbh = SB_Factory::getDbh();
		$role = SB_Role::GetRoleByKey('user');
		if( !$role )
			return false;
		$query = "SELECT COUNT(u.user_id) total_users FROM users u, user_roles ur WHERE u.role_id = ur.role_id AND ur.role_id = {$role->role_id}";
		if( !$dbh->Query($query) )
			return false;
		$total_users = (int)$dbh->FetchRow()->total_users;
		if( $total_users >= $max_users )
		{
			SB_MessagesStack::AddMessage(SB_Text::_('A llegado al maximo de usuarios permitidos, no es posible crear nuevos.'), 'info');
			sb_redirect(SB_Route::_('index.php?mod=users'));
		}	
	}
	protected function HandleAdminRequests()
	{
		$task = SB_Request::getTask();
		if( $task == 'upload_logo' )
		{
			$site_logo = UPLOADS_DIR . SB_DS . sb_get_parameter('site_logo', null);
			if( file_exists($site_logo) )
			{
				@unlink($site_logo);
			}
			sb_include('qqFileUploader.php', 'file');
			sb_include('class.image.php');
			$uh = new qqFileUploader();
			$uh->allowedExtensions = array('jpg', 'jpeg', 'gif', 'png', 'bmp');
			// Specify max file size in bytes.
			//$uh->sizeLimit = 10 * 1024 * 1024; //10MB
			// Specify the input name set in the javascript.
			$uh->inputName = 'qqfile';
			// If you want to use resume feature for uploader, specify the folder to save parts.
			$uh->chunksFolder = 'chunks';
			$res = $uh->handleUpload(UPLOADS_DIR);
			$file_path = UPLOADS_DIR . SB_DS . $uh->getUploadName();
			/*
			$img = new SB_Image($file_path);
			if( $img->getWidth() > 300 || $img->getHeight() > 300 )
			{
				$img->resizeImage(300, 300);
				$img->save($file_path);
			}
			*/
			$res['uploadName'] = $uh->getUploadName();
			$res['image_url'] = UPLOADS_URL . '/' . $res['uploadName'];
			sb_update_parameter('site_logo', $res['uploadName']);
			die(json_encode($res));
		}
		elseif( $task == 'upload_bg_image' )
		{
			$bg_image = sb_get_parameter('bg_image', null);
			if( is_object($bg_image) )
			{
				@unlink($bg_image->path);
			}
			elseif( $bg_image && file_exists(UPLOADS_DIR . SB_DS . $bg_image) )
			{
				@unlink(UPLOADS_DIR . SB_DS . $bg_image);
			}
			sb_include('qqFileUploader.php', 'file');
			sb_include('class.image.php');
			$uh = new qqFileUploader();
			$uh->allowedExtensions = array('jpg', 'jpeg', 'gif', 'png', 'bmp');
			// Specify max file size in bytes.
			//$uh->sizeLimit = 10 * 1024 * 1024; //10MB
			// Specify the input name set in the javascript.
			$uh->inputName = 'qqfile';
			// If you want to use resume feature for uploader, specify the folder to save parts.
			$uh->chunksFolder = 'chunks';
			$res = $uh->handleUpload(UPLOADS_DIR);
			$file_path = UPLOADS_DIR . SB_DS . $uh->getUploadName();
			$res['uploadName'] = $uh->getUploadName();
			$res['image_url'] = UPLOADS_URL . '/' . $res['uploadName'];
			sb_update_parameter('bg_image', $res['uploadName']);
			die(json_encode($res));
		}
		elseif( $task == 'remove_bg_image' )
		{
			$bg_image = sb_get_parameter('bg_image', null);
			if( is_object($bg_image) )
			{
				unlink($bg_image->path);
			}
			elseif( $bg_image && file_exists(UPLOADS_DIR . SB_DS . $bg_image) )
			{
				@unlink(UPLOADS_DIR . SB_DS . $bg_image);
			}
			sb_update_parameter('bg_image', '');
			$res = array('status' => 'ok', 'message' => 'removed');
			die(json_encode($res));
		}
		elseif( $task == 'get_bg_images' )
		{
			require_once $this->template_dir . SB_DS . 'tpl-bg-images.php';
			die();
		}
		elseif( $task == 'set_bg_image' )
		{
			$file	= SB_Request::getString('file');
			$url 	= SB_Request::getString('url');
			$path 	= SB_Request::getString('path');
			$type 	= SB_Request::getString('type');
			if( !$file )
				die('Invalid bg file');
			
			$data = array('type' => $type, 'file' => $file, 'url' => $url, 'path' => $path);
			sb_update_parameter('bg_image', $data);
			die($data);
		}
		elseif( $task == 'upload_header_image' )
		{
			$header_image = sb_get_parameter('header_image', null);
			if( $header_image && file_exists(UPLOADS_DIR . SB_DS . $header_image) )
			{
				@unlink(UPLOADS_DIR . SB_DS . $header_image);
			}
			sb_include('qqFileUploader.php', 'file');
			sb_include('class.image.php');
			$uh = new qqFileUploader();
			$uh->allowedExtensions = array('jpg', 'jpeg', 'gif', 'png', 'bmp');
			// Specify max file size in bytes.
			//$uh->sizeLimit = 10 * 1024 * 1024; //10MB
			// Specify the input name set in the javascript.
			$uh->inputName = 'qqfile';
			// If you want to use resume feature for uploader, specify the folder to save parts.
			$uh->chunksFolder = 'chunks';
			$res = $uh->handleUpload(UPLOADS_DIR);
			$file_path = UPLOADS_DIR . SB_DS . $uh->getUploadName();
			$res['uploadName'] = $uh->getUploadName();
			$res['image_url'] = UPLOADS_URL . '/' . $res['uploadName'];
			sb_update_parameter('header_image', $res['uploadName']);
			die(json_encode($res));
		}
		elseif( $task == 'remove_header_image' )
		{
			$bg_image = sb_get_parameter('header_image', null);
			if( $bg_image && file_exists(UPLOADS_DIR . SB_DS . $bg_image) )
			{
				@unlink(UPLOADS_DIR . SB_DS . $bg_image);
			}
			sb_update_parameter('header_image', '');
			$res = array('status' => 'ok', 'message' => 'removed');
			die(json_encode($res));
		}
	}
	public function action_section_query_contents_args($args)
	{
		$args['publish_date'] 	= null;
		$args['end_date']		= null;
		return $args;
	}
	/**
	 * Add article visibilite based on calculated dates
	 * @param unknown $visible
	 * @param unknown $article
	 * @return unknown|boolean
	 */
	public function action_content_is_visible($visible, $article)
	{
		/*
		//##if visibility is false, just return it
		if( !$visible )
			return $visible;
		*/
		$hour_seconds 			= 60 * 60;
		$day_seconds			= 24 * $hour_seconds; 
		$use_calculated			= (int)$article->_use_calculated;
		$calculated_date 		= (int)$article->_calculated_date;
		$end_calculated_date 	= (int)$article->_end_calculated_date;
		if( !$use_calculated || !sb_is_user_logged_in() )
		{
			return $visible;
		}
		$visible = true;
		$user = sb_get_current_user();
		//var_dump($user->creation_date);
		if( !$user->creation_date )
		{
			$visible = false;
		}
		else
		{
			$creation_time = strtotime(sb_format_date($user->creation_date, 'Y-m-d'));
			//var_dump('user creation date => ' . $creation_time);
			//var_dump('user creation date => ' . date('Y-m-d', $creation_time));
			if( $calculated_date > 0 )
			{
				$calculated_publish_time = $creation_time + ($calculated_date * $day_seconds);
				//var_dump(date('Y-m-d', time()) .' < ' . date('Y-m-d', $calculated_publish_time) );
				if( time() < $calculated_publish_time )
				{
					$visible = false;
				}		
			}
			if( $end_calculated_date > 0 )
			{
				$calculated_end_date = $creation_time + ($end_calculated_date * $day_seconds);
				//var_dump(date('Y-m-d', time()) .' > ' . date('Y-m-d', $calculated_end_date) );
				if( time() > $calculated_end_date )
				{
					$visible = false;
				}
			}
		}
		//var_dump(__METHOD__);
		//var_dump($visible);
		return $visible;
	}
	public function action_content_section_is_visible($visible, $section)
	{
		$hour_seconds 			= 60 * 60;
		$day_seconds			= 24 * $hour_seconds;
		$use_calculated			= (int)$section->_use_calculated_dates;
		$calculated_date 		= (int)$section->_calculated_date;
		$end_calculated_date 	= (int)$section->_calculated_end_date;
		
		if( !$use_calculated || !sb_is_user_logged_in() )
		{
			return $visible;
		}
		$visible = true;
		$user = sb_get_current_user();
		if( !$user->creation_date )
		{
			$visible = false;
		}
		else
		{
			$creation_time = strtotime(sb_format_date($user->creation_date, 'Y-m-d'));
			if( $calculated_date > 0 )
			{
				$calculated_publish_time = $creation_time + ($calculated_date * $day_seconds);
				if( time() < $calculated_publish_time )
				{
					$visible = false;
				}
			}
			if( $end_calculated_date > 0 )
			{
				$calculated_end_date = $creation_time + ($end_calculated_date * $day_seconds);
				if( time() > $calculated_end_date )
				{
					$visible = false;
				}
			}
		}
		
		return $visible;
	}
	public function action_content_options($content)
	{
		/*
		?>
		<label class="has-popover" data-content="<?php print SBText::_('TPL_LABEL_COVER_PAGE'); ?>">
			<input type="checkbox" name="in_frontpage" value="1" <?php print ($content && $content->_in_frontpage) ? 'checked' : ''; ?> />
			<?php print SB_Text::_('Articulo de Portada'); ?>
		</label>
		<?php 
		*/
	}
	public function action_article_sidebar($content)
	{
		?>
		<div class="widget">
			<h2 class="title"><?php print SBText::_('Shortcodes'); ?></h2>
			<div class="body">
				<?php foreach($this->content_shortcodes as $shortcode => $label): ?>
				<?php
				$def = preg_replace('/[^A-Z0-9_]/', '', strtoupper($shortcode)); 
				?>
				<div class="has-popover" data-content="<?php print SBText::_('TPL_CONTENT_SHORTCODE_'.$def); ?>">
					<?php printf("[%s]: %s", ($shortcode == 'article_btn' || $shortcode == 'section_btn') ? $shortcode . ' id="XX"' : $shortcode, $label); ?>
				</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php 
	}
	public function action_save_article($article_id)
	{
		sb_update_content_meta($article_id, '_in_frontpage', SB_Request::getInt('in_frontpage', 0));
	}
	public function action_section_fields($section)
	{
		$url = $section ? $section->_external_url : '';
		?>
		<div class="row">
			<!-- 
			<div class="col-md-1">
				<label><input type="checkbox" name="external_content" value="1" /></label>
			</div>
			 -->
			<div class="col-md-6">
				<label class="has-popover" data-content="<?php print SBText::_('TPL_LABEL_EXTERN_URL'); ?>">
					<?php print SB_Text::_('URL Externa:')?></label>
				<input type="text" name="external_url" value="<?php print $url; ?>" placeholder="http://google.com" class="form-control" maxlength="90" size="90" />
			</div>
		</div><br/>
		<?php 
	}
	public function action_save_section($section_id)
	{
		sb_update_section_meta($section_id, '_external_url', SB_Request::getString('external_url', ''));
	}
	public function action_user_tabs($user)
	{
		?>
		<li>
			<a href="#scripts" role="tab" data-toggle="tab" class="has-popover" data-content="<?php print SBText::_('TPL_USERS_TAB_LABEL_SCRIPTS'); ?>">
				<?php print SBText::_('Scripts'); ?></a></li>
		<?php 
	}
	public function action_user_tabs_content($user)
	{
		$scripts = $user ? sb_get_user_meta($user->user_id, '_scripts') : new stdClass();
		?>
		<div id="scripts" role="tabpanel" class="tab-pane">
			<div class="control-group">
				<ul style="width:200px;height:200px;overflow:auto;">
					<li><label><?php print SBText::_('Chat'); ?> <input type="checkbox" name="scripts[chat]" value="1" <?php print @$scripts->chat == 1 ? 'checked' : ''; ?> /></label></li>
					<li><label><?php print SBText::_('Comunicaciones'); ?> <input type="checkbox" name="scripts[com]" value="1" <?php print @$scripts->com == 1 ? 'checked' : ''; ?> /></label></li>
					<li><label><?php print SBText::_('General'); ?> <input type="checkbox" name="scripts[general]" value="1" <?php print @$scripts->general == 1 ? 'checked' : ''; ?> /></label></li>
				</ul>
			</div>
		</div>
		<?php 
	}
	public function action_save_user($user_id)
	{
		sb_update_user_meta($user_id, '_scripts', array_map('trim', SB_Request::getVar('scripts', array())));
		sb_update_user_meta($user_id, '_msg_shortcode', SB_Request::getString('msg_shortcode'));
	}
	public function action_lt_footer()
	{
		//##set scripts
		if( sb_is_user_logged_in() )
		{
			$view = SB_Request::getString('view', 'default');
			$mod	= SB_Request::getString('mod', 'users');
			$g_scripts = (object)sb_get_parameter('user_scripts', new stdClass());
			$scripts = sb_get_user_meta(sb_get_current_user()->user_id, '_scripts');
			foreach($g_scripts as $key => $js)
			{
				//##check if user has enabled the script
				if( isset($scripts->{strtolower($key)}) && (int)$scripts->{strtolower($key)} == 1 )
				{
					$prop = $key . '_IN_HOME';
					if( !isset($g_scripts->$prop) || (int)$g_scripts->$prop != 1 )
					{
						print $js . "\n";
					}
					elseif( $mod == 'users' && $view == 'default' && isset($g_scripts->$prop) && (int)$g_scripts->$prop == 1)
					{
						print $js . "\n";
					}
				}
			}
		}
		?>
		<?php if( !sb_is_user_logged_in() && defined('BG_ANIMATED') && BG_ANIMATED == 1 ): ?>
		<?php $vurl = BG_ANIMATED_URL; ?>
		<?php if( !empty($vurl) ): ?>
		<script src="<?php print BASEURL; ?>/js/jquery.tubular.1.0.js"></script>
		<script>	
		jQuery(function()
		{
			 jQuery('#container').tubular({videoId: '<?php print $vurl; ?>', wrapperZIndex: 100}); // where idOfYourVideo is the YouTube ID.
		});
		</script>
		<?php endif; ?>
		<?php endif; ?>
		<?php 
	}
	public function shortcode_username($args)
	{
		$content = '';
		if( sb_is_user_logged_in() )
		{
			$content = sb_get_current_user()->username;
		}
		return $content;
	}
	public function shortcode_first_name($args)
	{
		$content = '';
		if( sb_is_user_logged_in() )
		{
			$content = sb_get_current_user()->first_name;
		}
		return $content;
	}
	public function shortcode_last_name($args)
	{
		$content = '';
		if( sb_is_user_logged_in() )
		{
			$content = sb_get_current_user()->last_name;
		}
		return $content;
	}
	public function shortcode_current_date($args)
	{
		$content = sb_format_date(time());
		
		return $content;
	}
	public function shortcode_current_time($args)
	{
		$content = sb_format_time(time());
		return $content;
	}
	public function shortcode_web_open($args)
	{
		$height = "150";
		if( !isset($args['url']) )
			return '';
		if( isset($args['height']) )
			$height = $args['height'];
		$iframe = "<p><iframe style=\"height:{$height}px;\" src=\"{$args['url']}\" width=\"100%\" height=\"{$height}\"></iframe></p>";
		return $iframe;
	}
	public function shortcode_pdf_open($args)
	{
		$url	= isset($args['url']) ? $args['url'] : null;
		$height = isset($args['height']) ? (int)$args['height'] : 150;
		$pdf 	= isset($args['pdf']) ? (int)$args['pdf'] : 0;
		if( !$url )
			return '';
		$html = '';
		
		if( $pdf === 1 )
		{
			$html = '<object title="DocumentoPdf" data="'.$url.'" type="application/x-pdf" width="100%" '.
						'height="'.$height.'">
						<a href="'.$url.'">Ver Documento</a>'.
					'</object>';
		}
		elseif( $pdf === 2 )
		{
			$html = '<iframe src="https://drive.google.com/viewerng/viewer?url='.$url.'?pid=explorer&efh=false&a=v&chrome=false&embedded=true" width="100%" height="'.$height.'"></iframe>';
		}
		else
		{
			$html = "<p><iframe style=\"height:{$height}px;\" src=\"{$url}\" width=\"100%\" height=\"{$height}\"></iframe></p>";
		}
		return $html;
	}
	public function action_authenticated($row, $username, $pwd)
	{
		$address = trim(sb_get_user_meta($row->user_id, '_address'));
		$city = trim(sb_get_user_meta($row->user_id, '_city'));
		
		if( empty($row->first_name) || empty($row->last_name) || empty($address) || empty($city) )
		{
			sb_redirect(SB_Route::_('index.php?mod=users&view=form_profile'));
		}
	}
	public function action_before_show_content($article)
	{
		if( !$article->content_id )
		{
			sb_redirect(SB_Route::_('error404b/'));
		}
	}
	public function action_after_user_tab_personal($user)
	{
		?>
		<div class="form-group row">
			<div class="col-md-6">
				<label class="has-popover" data-content="<?php _e('TPL_DEFAULT_LABEL_MSG_SHORTCODE'); ?>">Mensaje Shortcode:</label>
				<textarea rows="" cols="" maxlength="40" name="msg_shortcode" class="form-control"><?php print isset($user) ? $user->_msg_shortcode : ''; ?></textarea>
			</div>
		</div>
		<?php 
	}
	public function action_before_article_not_found()
	{
		sb_redirect(SB_Route::_('error404b/'));
	}
	public function action_register_user_email_subject($subject)
	{
		return sprintf("%s - Tus datos de Acceso", SITE_TITLE);
	}
	public function action_register_user_email_body($body, $user, $pass)
	{
		$site_title = SITE_TITLE;
		$site_url 	= SB_Route::_('index.php', 'frontend');
		$msg =<<<EOB
Hola {$user->first_name} {$user->last_name}<br/><br/>

Gracias por registrarte como Usuari@<br/>
A continuacion puedes ver tus datos de Acceso<br/><br/>

Nombre de Usuario: {$user->username}<br/>
Contraseña(Password): $pass<br/>
Notas importantes:<br/>
1- En la Contraseña, respeta Mayusculas y minusculas<br/>
2- El codigo de seguridad (Captcha) de la web de entrada deberas teclearlo asi: 3 ultimos digitos primero y 3 primeros digitos despues<br/>
Ejemplo: Jy4NM9, tendras que teclear NM9Jy4  para poder resolverlo<br/><br/>

Para poder acceder a los contenidos utiliza el siguiente enlace (URL)<br/><br/>

<a href="$site_url">$site_url</a><br/><br/>

Gracias<br/><br/>

Staff de $site_title<br/>
		
EOB;
		return $msg;
	}
	public function shortcode_google_folder($args)
	{
		$def_args = array(
			'id'		=> null,
			'height'	=> 150
		);
		$args = array_merge($def_args, $args);
		if( (int)$args['id'] )
			return 'Identificador de folder invalido!!!';
		ob_start(); ?>
		<iframe style="width:100%;height:<?php print $args['height'] ?>px;border:0;" 
			src="https://drive.google.com/embeddedfolderview?id=<?php print $args['id']; ?>#list" 
			width="100%" height="<?php print $args['height']; ?>"></iframe>
		<?php
		$html = ob_get_clean();
		return $html;
	}
	public function shortcode_btn_paypal($args)
	{
		/**
		 * _xclick -> Buy Now button
		 */
		$def_args = array(
			'producto'		=> '',
			'precio'		=> 1,
			'codigo'		=> '',
			'email'			=> 'business@paypal.com',
			'moneda'		=> 'USD',
			'notify_url'	=> SB_Route::_('index.php?task=paypal_cb')
		);
		$args = array_merge($def_args, $args);
		ob_start();?>
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
			<input name="cmd" type="hidden" value="_xclick" />
			<input name="business" type="hidden" value="<?php print $args['email']; ?>" />
			<input name="item_number" type="hidden" value="<?php print $args['codigo']; ?>" />
			<input name="quantity" type="hidden" value="1" />
			<input name="currency_code" type="hidden" value="<?php print $args['moneda']; ?>" />
			<input name="item_name" type="hidden" value="<?php print $args['producto']; ?>" />
			<input name="amount" type="hidden" value="<?php print $args['precio']; ?>" />
			<input name="notify_url" type="hidden" value="<?php print $args['notify_url']; ?>" />
			<div class="form-group">
				<input type="image" 
					src="https://www.paypalobjects.com/webstatic/en_US/i/btn/png/gold-rect-paypal-60px.png" 
					alt="Buy Now" />
			</div>
			<!--
			<input type="hidden" name="lc" value="US" />
			-->
		</form>
		<?php
		$html = ob_get_clean();
		return $html;
	}
	protected function PaypalIPN()
	{
		$this->Log('PAYPAL IPN');
		$this->Log($_GET);
		$this->Log($_POST);
		//read raw POST data from the input stream.
		$raw_post_data	= file_get_contents('php://input');
		$raw_post_array = explode('&', $raw_post_data);
		$myPost 		= array();
		foreach ($raw_post_array as $keyval) 
		{
			$keyval = explode ('=', $keyval);
			if ( count($keyval) == 2 )
				$myPost[$keyval[0]] = urldecode($keyval[1]);
		}
		// read the IPN message sent from PayPal and prepend 'cmd=_notify-validate'
		$req = 'cmd=_notify-validate';
		if (function_exists('get_magic_quotes_gpc')) 
		{
			$get_magic_quotes_exists = true;
		}
		foreach ($myPost as $key => $value) 
		{
			if ($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) 
			{
				$value = urlencode(stripslashes($value));
			} 
			else {
				$value = urlencode($value);
			}
			$req .= "&$key=$value";
		}

		// Step 2: POST IPN data back to PayPal to validate
		$ch = curl_init('https://ipnpb.paypal.com/cgi-bin/webscr');
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
		// In wamp-like environments that do not come bundled with root authority certificates,
		// please download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html" and set
		// the directory path of the certificate as shown below:
		// curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . '/cacert.pem');
		if ( !($res = curl_exec($ch)) ) 
		{
			// error_log("Got " . curl_error($ch) . " when processing IPN data");
			curl_close($ch);
			exit;
		}
		curl_close($ch);
		// inspect IPN validation result and act accordingly
		if (strcmp ($res, "VERIFIED") == 0) 
		{
			
			// The IPN is verified, process it:
			// check whether the payment_status is Completed
			// check that txn_id has not been previously processed
			// check that receiver_email is your Primary PayPal email
			// check that payment_amount/payment_currency are correct
			// process the notification
			// assign posted variables to local variables
			$item_name 			= $_POST['item_name'];
			$item_number 		= $_POST['item_number'];
			$payment_status 	= $_POST['payment_status'];
			$payment_amount 	= $_POST['mc_gross'];
			$payment_currency 	= $_POST['mc_currency'];
			$txn_id 			= $_POST['txn_id'];
			$receiver_email 	= $_POST['receiver_email'];
			$payer_email 		= $_POST['payer_email'];
			$this->CreateUser($item_number, $payer_email);
			SB_Module::do_action('payments_accepted', 'paypal');
			die('IPN FINISHED');
			
		} 
		elseif( strcmp($res, "INVALID") == 0 ) 
		{
			// IPN invalid, log for manual investigation
			error_log("The response from IPN was: <b>" .$res ."</b>");
		}
	}
	protected function Click2SellCallback()
	{
		if( SB_Request::getString('payment_status') != 'OK' )
			return false;
			
		$this->Log($_POST);
		$product_name = SB_Request::getString('product_name');
		if( !strstr($product_name, '#') )
		{
			$this->Log('The product name has no the group key');
			return false;
		}
		list(,$group_key) = array_map('trim', explode('#', $product_name));
		if( empty($group_key) )
		{
			$this->Log('The group key is empty');
			return false;
		}
		$user_id = $this->CreateUser(trim($group_key),
							SB_Request::getString('buyer_email'), 
							SB_Request::getString('buyer_name'), 
							SB_Request::getString('buyer_surname'));
		sb_update_user_meta($user_id, '_acquirer_transaction_id', SB_Request::getString('acquirer_transaction_id'));
		SB_Module::do_action('payments_accepted', 'click2sell');
		header("HTTP/1.1 200 OK");
		die();
	}
	protected function CreateUser($group_key, $payer_email, $first_name = '', $last_name = '')
	{
		$user_id			= null;
		$mail_data			= null;
		$password			= '';
		//##validate if user already exists using the email
		if( $user = sb_get_user_by('username', $payer_email) )
		{
			$user_id = $user->user_id;
			$this->Log("El nombre de usuario $email ya existe.");
			$mail_data = $this->EmailExistingUser();
		}
		elseif( $user = sb_get_user_by('email', $payer_email) )
		{
			$user_id = $user->user_id;
			$this->Log("El email de usuario $email ya existe.");
			$mail_data = $this->EmailExistingUser();
		}
		else
		{
			$role		= SB_Role::GetRoleByKey('user');
			$password 	= sb_gen_random_password();
			$data = array(
					'first_name'				=> $first_name,
					'last_name'					=> $last_name,
					'email'						=> $payer_email,
					'username'					=> $payer_email,
					'pwd'						=> $password,
					'role_id'					=> $role ? $role->role_id : 2,
					'status'					=> 'enabled',
					'last_modification_date'	=> $cdate,
					'creation_date'				=> $cdate
			);
			$user_id 	= sb_insert_user($data);
			$mail_data 	= $this->EmailNewUser();
		}
		$dbh = SB_Factory::GetDbh();
		$query = "SELECT g.*, (
									SELECT group_concat(gl.level_id) 
									FROM user_group2levels gl 
									WHERE gl.group_id = g.group_id
								) AS levels
					FROM user_levels_group g 
					WHERE g.group_key = '$group_key' 
					LIMIT 1";
		$this->Log($query);
		if( $row = $dbh->FetchRow($query) )
		{
			$levels 	= explode(',', $row->levels);
			$old_levels	= sb_get_user_meta($user_id, '_levels');
			$this->Log('Niveles actuales');
			$this->Log($old_levels);
			//##check if user already has levels
			if( !$old_levels )
			{
				$this->Log('El usuario no tiene niveles existentes');
				$this->Log('Asignando niveles');
				$this->Log($levels);
				sb_add_user_meta($user_id, '_levels', $levels);
			}
			else
			{
				$this->Log('El usuario tiene niveles existentes');
				$new_levels = array_unique(array_merge($old_levels, $levels));
				$this->Log('Nuevos niveles');
				$this->Log($new_levels);
				sb_update_user_meta($user_id, '_levels', $new_levels);
			}
		}
		else
		{
			$this->Log("Grupo de niveles no existente");
		}
		$msg = str_replace(array('[baseurl]', '[username]', '[password]', "\r\n"), 
								array(BASEURL, $payer_email, $password, "<br/>"), 
								$mail_data['body']);
		lt_mail($payer_email, $mail_data['subject'], $msg, 
				array(
					'from'	=> 'From: ' . SITE_TITLE . ' <no-reply@domain.com>',
					'type'	=> 'Content-Type: text/html'
				)
		);
		return $user_id;
	}
	protected function EmailNewUser()
	{
		$body = "Gracias por tu compra<br/><br/>
				Te damos la bienvenida<br/>
				Ahora puedes acceder al Area Privada desde la siguiente direccion web:<br/><br/>
				[baseurl]<br/><br/>
				Tus datos de usuario son:<br/><br/>
				Usuario: [username]<br/>
				Contraseña: [password]<br/><br/>
				Importante: por favor no pierdas estos datos y recuerda que puedes cambiarlos desde el boton perfil<br/><br/>
				Ayuda para acceder:<br/><br/> 
				¿Como se cubre el formulario de acceso?<br/><br/>
				1.- Tienes que poner como nombre de usuario tu direccion de email (la direccion de email que has usado durante la compra)<br/>
				2.- Utiliza tu contraseña (respetando mayusculas, minusculas, puntos y simbolos)<br/>
				3.- ¿Como resolver el codigo Captcha (codigo de seguridad)?... El codigo se divide en 2	bloques de 3 letras o numeros (6 en total).<br/>
				Primero, hay que teclear las 3 ultimas letras o numeros y despues las 3 primeras, respetando mayusculas, minusculas, etc.<br/>
				Ejemplo: si el codigo es Rt8GH1, para resolverlo hay que teclear: GH1Rt8<br/><br/>
				La primera vez que te conectes, deberas completar tu perfil	con los datos que faltan:<br/>
				1- Tu direccion completa<br/>
				2- La Ciudad donde vives<br/>
				3- La Provincia o Estado<br/>
				4- El Pais<br/>
				5- Tu fecha de Cumpleaños<br/><br/>
				
				Gracias de nuevo<br/>
				Saludos<br/>
				Feliz dia<br/>";
		return array('subject' => 'Tus datos de acceso al Area Privada', 'body' => $body);
	}
	protected function EmailExistingUser()
	{
		$body = "Gracias por tu compra<br/><br/>
				Tu nivel de acceso ha sido actualizado<br/>
				Recuerda que puedes acceder al Area Privada desde la siguiente direccion web:<br/>
				[baseurl]<br/><br/>
				Tus datos de usuario son:<br/>
				Usuario: [username]<br/>
				Contraseña: [password]<br/>
				Utiliza tu contraseña habitual<br/><br/>
				Importante: por favor no pierdas estos datos y recuerda que puedes cambiarlos desde el boton perfil<br/><br/>
				Gracias de nuevo<br/>
				Saludos<br/>
				Feliz dia<br/>";
		return array('subject' => 'Tus datos de acceso al Area Privada se han actualizado correctamente', 
						'body' => $body);
	}
	/**
	 * Write a string into log file
	 * @param mixed $str 
	 * @return  
	 */
	public function Log($str)
	{
		$log_file = dirname(__FILE__) . SB_DS . 'log.txt';
		$fh = file_exists($log_file) ? fopen($log_file, 'a+') : fopen($log_file, 'w+');
		fwrite($fh, '[' . date('Y-m-d H:i:s') . "]\n" . print_r($str, 1) . "\n");
		fclose($fh);
		//error_log(print_r($str, 1));
	}
	public function action_users_new_email_headers($headers)
	{
		if( defined('EMAIL_VERIFICATION') && EMAIL_VERIFICATION )
		{
			$headers['cc'] = 'Cc: ' . EMAIL_VERIFICATION;
		}
		return $headers;
	}
	public function SendMail($to, $subject, $msg, $data = array())
	{
		sb_include_module_helper('smn');
		//$mailer = LT_HelperForms::GetMailerInstance(1);
		$mailer	= LT_HelperMessages::GetMailerInstance(1);
		$mailer->isHTML(isset($data['ishtml']) ? (int)$data['ishtml'] : false);
		$mailer->From 		= isset($data['from']) ? $data['from'] : FORMS_EMAIL_FROM;
		$mailer->FromName	= isset($data['from_name']) ? $data['from_name'] : SITE_TITLE;
		$mailer->Subject	= $subject;
		$mailer->Body		= $msg;
		$mailer->AltBody	= strip_tags(str_replace("<br/>", "\r\n", $msg));
		$mailer->addAddress($to);
				
		return $mailer->send();
	}
}
/**
 * 
 * @return  LT_ThemeDefault
 */
function ThemeDefault()
{
	static $theme;
	if( !$theme )
		$theme = new LT_ThemeDefault();
	return $theme;
}
ThemeDefault();