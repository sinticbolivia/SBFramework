<?php
define('MOD_STORAGE_DIR', dirname(__FILE__));
define('MOD_STORAGE_URL', MODULES_URL . '/' . basename(MOD_STORAGE_DIR));
define('STORAGE_DIR', UPLOADS_DIR . SB_DS . 'storage');
define('STORAGE_URL', UPLOADS_URL . '/storage');
require_once MOD_STORAGE_DIR . SB_DS . 'functions.php';
if( !is_dir(STORAGE_DIR) )
	mkdir(STORAGE_DIR);
class LT_ModStorage
{
	public function __construct()
	{
        SB_Language::loadLanguage(LANGUAGE, 'storage', MOD_STORAGE_DIR . SB_DS . 'locale');
		$this->AddActions();
	}
	protected function AddActions()
	{
		SB_Module::add_action('admin_menu', array($this, 'action_admin_menu'));
		
	}
	public function action_admin_menu()
	{
		SB_Menu::addMenuPage('<span class="glyphicon glyphicon-hdd"></span> '.__('Storage', 'storage'), SB_Route::_('index.php?mod=storage'), 'menu-storage', 'manage_backend');
	}
}
new LT_ModStorage();