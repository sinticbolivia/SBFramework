<?php
use SinticBolivia\SBFramework\Classes\SB_Controller;
use SinticBolivia\SBFramework\Classes\SB_MessagesStack;
use SinticBolivia\SBFramework\Classes\SB_Module;
use SinticBolivia\SBFramework\Classes\SB_Session;

class LT_AdminControllerSettings extends SB_Controller
{
	public function task_default()
	{
		sb_set_view_var('settings', sb_get_parameter('settings', new stdClass()));
		$this->document->SetTitle(__('Settings', 'settings'));
	}
	public function task_save()
	{
		$old_settings   = (array)sb_get_parameter('settings', array());
		$settings       = $this->request->getVar('settings');
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
		sb_update_parameter('settings', $settings);
		SB_Module::do_action('save_settings');
		SB_Session::unsetVar('lang');
		SB_MessagesStack::AddMessage(__('The settings has been saved.', 'settings'), 'success');
		sb_redirect(b_route('settings.php'));
	}
}