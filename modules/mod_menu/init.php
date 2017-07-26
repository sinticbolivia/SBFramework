<?php
require_once 'functions.php';
class LT_ModMenu
{
	public function __construct()
	{
		SB_Language::loadLanguage(LANGUAGE, 'menu', dirname(__FILE__) . SB_DS . 'locale');
		$this->AddActions();
	}
	protected function AddActions()
	{
		if( lt_is_admin() )
		{
			SB_Module::add_action('admin_menu', array($this, 'action_admin_menu'));
		}
		else
		{
			
		}
	}
	public function action_admin_menu()
	{
		SB_Menu::addMenuChild('menu-content', 
				'<span class="glyphicon glyphicon-menu-hamburger"></span> '.__('Menus', 'menu'), SB_Route::_('index.php?mod=menu'), 
				'menu-content-menu', 
				'manage_menu_content');
	}
}
new LT_ModMenu();