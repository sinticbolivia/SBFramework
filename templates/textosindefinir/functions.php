<?php
define('SKIP_SEC_QUESTIONS', 1);
define('TPL_TEXTSINDEF_DIR', dirname(__FILE__));
class LT_TemplateTextoSinDefinir
{
	public function __construct()
	{
		$this->Translates();
		$this->AddActions();
	}
	protected function Translates()
	{
		define('Accesos por secciones', 'Accesos por categorias');
		define('Accesos por Contenidos', 'Accesos por Guion');
		define('Historial Acceso por Contenidos', 'Historial Acceso por Guion');
		define('Historial Acceso a Secciones', 'Historial Acceso a Categorias');
		define('Accesos por contenidos', 'Accesos por guiones');
		define('Historial Acceso a Articulos', 'Historial Acceso a Categorias');
	}
	public function AddActions()
	{
		SB_Module::add_action('init', array($this, 'action_before_process_template'));
		SB_Module::add_action('view_template', array($this, 'action_view_template'));
		SB_Module::add_action('lt_footer', array($this, 'action_lt_footer'));
		SB_Module::add_action('users_new_email_subject', array($this, 'action_users_new_email_subject'));
		SB_Module::add_action('users_new_email_body', array($this, 'action_users_new_email_body'));
		SB_Module::add_action('users_recover_pwd_email_subject', array($this, 'action_users_recover_pwd_email_subject'));
		SB_Module::add_action('users_recover_pwd_email_body', array($this, 'action_users_recover_pwd_email_body'));
		if( lt_is_admin() )
		{
			SB_Module::add_action('after_general_settings', array($this, 'action_after_general_settings'));
			SB_Module::add_action('settings_tabs', array($this, 'action_settings_tabs'));
			SB_Module::add_action('settings_tabs_content', array($this, 'action_settings_tabs_content'));
			SB_Module::add_action('sections_before_show', array($this, 'action_sections_before_show'));
			SB_Module::add_action('mb_content_get_sections_query', array($this, 'action_mb_content_get_sections_query'));
		}
		else
		{
			SB_Module::add_action('authenticated', array($this, 'action_authenticated'), 100);
			SB_Module::add_action('template_file', array($this, 'action_template_file'));
			SB_Module::add_action('users_logout_before_redirect', array($this, 'action_users_logout_before_redirect'));
		}
		SB_Module::add_action('users_new_email_headers', array($this, 'action_users_new_email_headers'));
	}
	public function action_before_process_template()
	{
		if( !lt_is_admin() )
		{
			if( $_SERVER['SCRIPT_NAME'] != '/promo.php' )
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
	}
	public function action_view_template($view_tpl, $mod)
	{
		$view = SB_Request::getString('view');
		if( $view == 'section.default' && $mod == 'content' && lt_is_admin() )
		{
			$view_tpl = TPL_TEXTSINDEF_DIR . SB_DS . 'modules' . SB_DS . 'mod_content' . SB_DS . 'views' . SB_DS . 
						'admin' . SB_DS . 'section.default.php';
		}
		if( $view == 'section.new' && $mod == 'content' && lt_is_admin() )
		{
			$view_tpl = TPL_TEXTSINDEF_DIR . SB_DS . 'modules' . SB_DS . 'mod_content' . SB_DS . 'views' . SB_DS . 
						'admin' . SB_DS . 'section.new.php';
		}
		return $view_tpl;
	}
	public function action_lt_footer()
	{
		
		if( !sb_is_user_logged_in() )
			return false;
		if( !lt_is_frontpage() )
			return false;
		//var_dump(lt_is_frontpage());
		print @constant('TSD_SCRIPT_1');
		print @constant('TSD_SCRIPT_2');
		print @constant('TSD_SCRIPT_3');
	}
	public function action_after_general_settings($settings)
	{
		?>
		<div class="form-group">
			<label>Email de Verificacion</label>
			<input type="email" name="settings[EMAIL_VERIFICATION]" value="<?php print @$settings->EMAIL_VERIFICATION ?>">
		</div>
		<?php
	}
	public function action_settings_tabs()
	{
		?>
		<li>
			<a href="#scripts" data-toggle="tab" class="has-popover" data-content="<?php _e('SETTINGS_TAB_LABEL_GENERAL'); ?>">
				<?php _e('Scripts', 'tsd'); ?>
			</a>
		</li>
		<?php
	}
	public function action_settings_tabs_content($settings)
	{
		?>
		<div id="scripts" class="tab-pane">
			<div class="row">
				<div class="col-md-6 col-sm-6 col-xs-12">
					<div class="form-group">
						<label><?php _e('Script 1'); ?></label>
						<textarea name="settings[TSD_SCRIPT_1]" class="form-control"><?php print @$settings->TSD_SCRIPT_1; ?></textarea>
					</div>
					<div class="form-group">
						<label><?php _e('Script 2'); ?></label>
						<textarea name="settings[TSD_SCRIPT_2]" class="form-control"><?php print @$settings->TSD_SCRIPT_2; ?></textarea>
					</div>
					<div class="form-group">
						<label><?php _e('Script 3'); ?></label>
						<textarea name="settings[TSD_SCRIPT_3]" class="form-control"><?php print @$settings->TSD_SCRIPT_3; ?></textarea>
					</div>
				</div>
				<div class="col-md-6 col-sm-6 col-xs-12">
				</div>
			</div>
		</div>
		<?php
	}
	public function action_authenticated($row, $username, $pwd)
	{
		sb_redirect(SB_Route::_('index.php'));
	}
	public function action_users_new_email_subject($subject)
	{
		$subject = sprintf("Tu cuenta de acceso a %s ha sido creada", SITE_TITLE);
		return $subject;
	}
	public function action_users_new_email_body($body, $username, $email, $pwd, $data)
	{
		$name = isset($data['first_name']) ? $data['first_name'] : '';
		//$name .= isset($data['last_name']) ? $data['last_name'] : '';
		if( empty($name) )
			$name = $username;
		$name = htmlentities($name);
		$body = "Enhorabuena $name<br/><br/>".
				"Tu cuenta de acceso de ExpertoVirtual ha sido creada correctamente<br/><br/>".
				"Para acceder visita la siguiente URL:<br/>".
				sprintf("<a href=\"%s\">%s</a><br/><br/>", BASEURL, BASEURL).
				"Usa tu email como Nombre de usuario<br/>".
				"Tu contrase&ntilde;a es:<br/><br/>".
				$pwd ."<br/><br/>".
				"Si tienes problemas, accede a nuestro Soporte OnLine en:<br/>".
				"<a href=\"http://500sitios.helpdeskdigital.com/\">http://500sitios.helpdeskdigital.com/</a><br/>".
				sprintf("Revisa la Categoria de preguntas frecuentes para %s o abre un Ticket de Soporte<br/><br/>", SITE_TITLE).
				"Feliz dia!";
		return $body;
	}
	public function action_sections_before_show(&$ctrl)
	{
		$ctrl->document->SetTitle('Categorias');
		sb_set_view_var('title', 'Categorias');
	}
	public function action_mb_content_get_sections_query($article)
	{
		$query = "SELECT s.* ".
					"FROM section2content s2c, section s ".
					"WHERE 1 = 1 " .
					"AND s2c.section_id = s.section_id ".
					"AND s2c.content_id = $article->content_id";
		return $query;
	}
	public function action_template_file($tpl)
	{
		if( SB_Request::getString('mod') == 'users' && SB_Request::getString('view') == 'recover_pwd' )
		{
			$tpl = 'tpl-no-session.php';
		}
		return $tpl;
	}
	public function action_users_recover_pwd_email_subject($subject)
	{
		return 'Recuperacion de Contraseña - ExpertoVirtual';
	}
	public function action_users_recover_pwd_email_body($body, $user, $link)
	{
		$name = "{$user->first_name}";
		if( empty($name) )
			$name = $user->username;
			
		$body = "Hola $name<br/><br/>".
				"Para recuperar tu contraseña haz Click en el siguiente enlace<br/><br/>".
				"<a href=\"$link\">$link</a><br/><br/>".
				"Si tu no solicitaste tu contraseña por favor, ignora este email<br/><br/>".
				"Si continuas con problemas para acceder, visita nuestro Soporte OnLine en:<br/>".
				"<a href=\"http://500sitios.helpdeskdigital.com/\">http://500sitios.helpdeskdigital.com/</a><br/>".
				"Revisa la Categoria de preguntas frecuentes para ExpertoVirtual o abre un Ticket de Soporte<br/><br/>".
				"Feliz dia!";
				
		return $body;
	}
	public function action_users_logout_before_redirect()
	{
		sb_redirect('http://500sitios.com/promo99/');
	}
	public function action_users_new_email_headers($headers)
	{
		if( defined('EMAIL_VERIFICATION') && EMAIL_VERIFICATION )
		{
			$headers['cc'] = 'Cc: ' . EMAIL_VERIFICATION;
		}
		return $headers;
	}
}
new LT_TemplateTextoSinDefinir();
