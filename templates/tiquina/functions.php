<?php
class LT_ThemeTiquina
{
	protected $cfg;
	
	public function __construct()
	{
		$this->cfg = (object)sb_get_parameter('t_cfg', array());
		$this->AddActions();
	}
	protected function AddActions()
	{
		SB_Module::add_action('content_types', array($this, 'action_content_types'));
		if( lt_is_admin() )
		{
			SB_Module::add_action('settings_tabs', array($this, 'action_settings_tabs'));
			SB_Module::add_action('settings_tabs_content', array($this, 'action_settings_tabs_content'));
			SB_Module::add_action('save_settings', array($this, 'action_save_settings'));
		}
	}
	public function action_content_types($types)
	{
		$types['evento'] = array(
			'labels'	=> array(
					'menu_label'	=> '<span class="glyphicon glyphicon-calendar"></span> ' . __('Eventos', 'tiquina'),
					'new_label'		=> __('Nuevo Evento', 'tiquina'),
					'edit_label'	=> __('Editar Evento', 'tiquina'),
					'listing_label'	=> __('Eventos', 'tiquina')
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
					'for_object'	=> 'evento',
					'labels'		=> array('menu_label' 		=> '<span class="glyphicon glyphicon-folder-open"></span> Seccion', 
												'new_label' 	=> 'Nueva Seccion de Evento',
												'edit_label'	=> 'Editar Seccion de Evento',
												'listing_label'	=> 'Secciones de Eventos'
										)
			)
		);
		$types['galeria'] = array(
			'labels'	=> array(
					'menu_label'	=> '<span class="glyphicon glyphicon-picture"></span> ' . __('Galeria', 'tiquina'),
					'new_label'		=> __('Nueva Imagen', 'tiquina'),
					'edit_label'	=> __('Editar Imagen', 'tiquina'),
					'listing_label'	=> __('Galeria', 'tiquina')
			),
			'features'	=> array(
					'featured_image'		=> true,
					'use_dates'				=> false,
					'calculated_dates'		=> false,
					'text_color'			=> false,
					'background_text_color'	=> false,
					'view_button'			=> true,
					'btn_add_media'			=> false
			),
			'section'	=> array(
					'for_object'	=> 'galeria',
					'labels'		=> array('menu_label' 		=> '<span class="glyphicon glyphicon-folder-open"></span> Seccion', 
												'new_label' 	=> 'Nueva Seccion de Galeria',
												'edit_label'	=> 'Editar Seccion de Galeria',
												'listing_label'	=> 'Secciones de Galeria'
										)
			)
		);
		return $types;
	}
	public function action_settings_tabs()
	{
		?>
		<li>
			<a href="#conf-tiquina">Configuracion San Pedro de Tiquina</a>
		</li>
		<?php
	}
	public function action_settings_tabs_content($settings)
	{
		$cfg = (object)sb_get_parameter('t_cfg', array());
		?>
		<div id="conf-tiquina" class="tab-pane">
			<div class="row">
				<div class="col-xs-12 col-sm-6 col-md-6">
					<div class="form-group">
						<label>Perfil de Facebook</label>
						<input type="text" name="t_cfg[fb]" value="<?php print @$cfg->fb ?>" class="form-control" />
					</div>
					<div class="form-group">
						<label>Perfil de Twitter</label>
						<input type="text" name="t_cfg[tw]" value="<?php print @$cfg->tw ?>" class="form-control" />
					</div>
					<div class="form-group">
						<label>Video URL</label>
						<input type="text" name="t_cfg[yt_url]" value="<?php print @$cfg->yt_url ?>" class="form-control" />
					</div>
					<div class="form-group">
						<label>Texto Video</label>
						<textarea name="t_cfg[video_text]" class="form-control"><?php print @$cfg->video_text ?></textarea>
					</div>
					<div class="form-group">
						<label>Direccion</label>
						<textarea name="t_cfg[direccion]" class="form-control"><?php print @$cfg->direccion ?></textarea>
					</div>
					<div class="form-group">
						<label>Telefonos</label>
						<input type="text" name="t_cfg[telf]" value="<?php print @$cfg->telf ?>" class="form-control" />
					</div>
					<div class="form-group">
						<label>Email de Contacto</label>
						<input type="text" name="t_cfg[email]" value="<?php print @$cfg->email ?>" class="form-control" />
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6">
				</div>
			</div>
		</div><!-- end id="conf-curso-redes" -->
		<?php
	}
	public function action_save_settings()
	{
		$cfg = (array)SB_Request::getVar('t_cfg', array());
		sb_update_parameter('t_cfg', $cfg);
	}
	public function __get($var)
	{
		if( isset( $this->$var ) ) 
			return $this->$var;
		if( isset( $this->cfg->$var ) ) 
			return $this->cfg->$var;
		return null;
	}
}
function TSPT()
{
	static $tpl;
	if( !$tpl )
		$tpl = new LT_ThemeTiquina();
	return $tpl;
}
TSPT();