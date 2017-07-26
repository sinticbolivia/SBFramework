<?php
class LT_Edisemas
{
	public function __construct()
	{
		$this->AddActions();
		$this->AddShortcodes();
	}
	protected function AddActions()
	{
		SB_Module::add_action('modules_loaded', array($this, 'action_modules_loaded'));
		SB_Module::add_action('init', array($this, 'action_init'));
	}
	protected function AddShortcodes()
	{
		SB_Shortcode::AddShortcode('web_open', array($this, 'shortcode_web_open'));
	}
	public function action_modules_loaded()
	{
		
	}
	public function action_init()
	{
		if( SB_Module::IsEnabled('emono') )
		{
			sb_add_style('emono', sb_get_template_url() . '/css/emono.css');
			$w = new SB_MBWidgetCart();
			sb_widget_add2area('header_widgets', $w);
		}
	}
	public function shortcode_web_open($args)
	{
		$height = "150";
		if( !isset($args['url']) )
			return '';
		if( isset($args['height']) )
			$height = $args['height'];
		$iframe = "<p><iframe style=\"height:{$height}px;\" src=\"{$args['url']}\" width=\"100%\" height=\"{$height}\"></iframe></p>";
		return $iframe;
	}
}
new LT_Edisemas();