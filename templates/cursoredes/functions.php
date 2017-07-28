<?php
class LT_ThemeCursoRedes
{
	public $cfg;
	
	public function __construct()
	{
		$this->cfg = (object)sb_get_parameter('cursoredes_cfg', array());
		$this->AddActions();
	}
	protected function AddActions()
	{
		if( lt_is_admin() )
		{
			SB_Module::add_action('settings_tabs', array($this, 'action_settings_tabs'));
			SB_Module::add_action('settings_tabs_content', array($this, 'action_settings_tabs_content'));
			SB_Module::add_action('save_settings', array($this, 'action_save_settings'));
		}
	}
	public function action_settings_tabs()
	{
		?>
		<li>
			<a href="#conf-curso-redes">Configuracion CursoRedes</a>
		</li>
		<?php
	}
	public function action_settings_tabs_content($settings)
	{
		$cfg = (object)sb_get_parameter('cursoredes_cfg', array());
		?>
		<div id="conf-curso-redes" class="tab-pane">
			<div class="row">
				<div class="col-xs-12 col-sm-6 col-md-6">
					<div class="form-group">
						<label>Perfil de Facebook</label>
						<input type="text" name="cr_cfg[fb]" value="<?php print @$cfg->fb ?>" class="form-control" />
					</div>
					<div class="form-group">
						<label>Perfil de Twitter</label>
						<input type="text" name="cr_cfg[tw]" value="<?php print @$cfg->tw ?>" class="form-control" />
					</div>
					<div class="form-group">
						<label>Video URL</label>
						<input type="text" name="cr_cfg[yt_url]" value="<?php print @$cfg->yt_url ?>" class="form-control" />
					</div>
					<div class="form-group">
						<label>Texto Video</label>
						<textarea name="cr_cfg[video_text]" class="form-control"><?php print @$cfg->video_text ?></textarea>
					</div>
					<div class="form-group">
						<label>Direccion</label>
						<textarea name="cr_cfg[direccion]" class="form-control"><?php print @$cfg->direccion ?></textarea>
					</div>
					<div class="form-group">
						<label>Telefonos</label>
						<input type="text" name="cr_cfg[telf]" value="<?php print @$cfg->telf ?>" class="form-control" />
					</div>
					<div class="form-group">
						<label>Email de Contacto</label>
						<input type="text" name="cr_cfg[email]" value="<?php print @$cfg->email ?>" class="form-control" />
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
		$cfg = (array)SB_Request::getVar('cr_cfg', array());
		sb_update_parameter('cursoredes_cfg', $cfg);
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
/**
 * Inicializar Plantilla CursoRedes
 * @return  
 */
function TCR()
{
	static $tcr;
	if( !$tcr )
		$tcr = new LT_ThemeCursoRedes();
		
	return $tcr;
}
TCR();