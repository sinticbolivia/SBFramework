<?php
class LT_AdminControllerModules extends SB_Controller
{
	public function task_default()
	{
		if( !sb_get_current_user()->can('manage_modules') )
		{
			die('You dont have enough permissions.');
		}
		sb_set_view_var('page_title', SB_Text::_('Modules Management'));
		sb_set_view_var('available_modules', SB_Module::getAvailableModules());
		sb_set_view_var('enabled_modules', SB_Module::getEnabledModules());
	}
	public function task_enable_module()
	{
		if( !sb_get_current_user()->can('enable_module') )
		{
			die('You dont have enough permissions.');
		}
		$modules = SB_Module::getEnabledModules();
		if( !in_array(SB_Request::getString('the_mod'), $modules) )
		{
			$the_mod = SB_Request::getString('the_mod');
			$modules[] = $the_mod;
			sb_update_parameter('modules', $modules);
			//call on enabled module file
			$module_path = MODULES_DIR . SB_DS . $the_mod;
			if( file_exists($module_path . SB_DS . 'on_enabled.php') )
			{
				require_once $module_path . SB_DS . 'on_enabled.php';
			}
		}
		SB_MessagesStack::AddMessage(SB_Text::_('Modulo habilitado corectamente'), 'success');
		sb_redirect(SB_Route::_('index.php?mod=modules'));
	}
	public function task_disable_module()
	{
		if( !sb_get_current_user()->can('disable_module') )
		{
			die('You dont have enough permissions.');
		}
		$modules = SB_Module::getEnabledModules();
		$modules = array_filter($modules);
		$index = (int)array_search(trim($_GET['the_mod']), $modules);
		if( $index === false )
		{
			
		}
		else
		{
			unset($modules[$index]);
			sort($modules);
		}
		
		sb_update_parameter('modules', $modules);
		SB_MessagesStack::AddMessage(SB_Text::_('Module disabled successfull'), 'success');
		sb_redirect(SB_Route::_('index.php?mod=modules'));
	}
}