<?php
class LT_AdminControllerSettings extends SB_Controller
{
	public function task_default()
	{
		sb_set_view_var('settings', sb_get_parameter('settings', new stdClass()));
		$this->document->SetTitle(SBText::_('Configuraci&oacute;n'), 'settings');
	}
	public function task_save()
	{
		$old_settings = (array)sb_get_parameter('settings', array());
		$settings = SB_Request::getVar('settings');
		/*
		foreach($settings as $key => $val)
		{
			if( isset($old_settings->$key) )
			{
				$old_settings->$key = $val;
			}
		}
		*/
		//$settings = array_merge($old_settings, $settings);
		$dbh = SB_Factory::getDbh();
		sb_update_parameter('settings', $settings);
		SB_Module::do_action('save_settings');
		SB_Session::unsetVar('lang');
		SB_MessagesStack::AddMessage(SB_Text::_('La configuracion se ha guardado correctamente.'), 'success');
		sb_redirect(SB_Route::_('settings.php'));
		
	}
}