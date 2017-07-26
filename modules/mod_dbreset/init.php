<?php
class LT_ModDbReset
{
	public function __construct()
	{
		$this->AddActions();
	}
	protected function AddActions()
	{
		SB_Module::add_action('admin_menu', array($this, 'action_admin_menu'));
	}
	public function action_admin_menu()
	{
		$user = sb_get_current_user();
		if( !$user->IsRoot() )
		{
			return false;
		}
		SB_Menu::addMenuChild('menu-settings', 
								__('Database Reset', 'dbreset'), 
								SB_Route::_('index.php?mod=dbreset'), 
								'menu-dbreset', 
								'root_manage_backend');
	}
}
new LT_ModDbReset();