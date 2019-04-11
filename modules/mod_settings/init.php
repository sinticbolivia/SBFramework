<?php
namespace SinticBolivia\BeetleCMS\Modules\Settings;
use SinticBolivia\SBFramework\Classes\SB_Language;
use SinticBolivia\SBFramework\Classes\SB_Module;

class SB_ModuleSettings
{
	public function __construct()
	{
		$this->AddActions();
	}
	protected function AddActions()
	{
		SB_Module::add_action('init', array($this, 'action_init'));
	}
	public function action_init()
	{
		$this->LoadLanguage();
	}
	protected function LoadLanguage()
	{
		SB_Language::loadLanguage(LANGUAGE, 'settings', dirname(__FILE__) . SB_DS . 'locale');
	}
}
new SB_ModuleSettings();