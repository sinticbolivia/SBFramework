<?php
use SinticBolivia\SBFramework\Classes\SB_Module;
use SinticBolivia\SBFramework\Classes\SB_Globals;

class LT_DefaultScripts
{
	public function __construct()
	{
		$this->AddActions();
	}
	protected function AddActions()
	{
		SB_Module::add_action('scripts', array($this, 'default_scripts'));
	}
	public function default_scripts()
	{
		$format = array(
				'Y-m-d' => 'yyyy-mm-dd',
				'd-m-Y' => 'dd-mm-yyyy',
				'm-d-Y' => 'mm-dd-yyyy'
		);
		list($lang,) = explode('_', LANGUAGE);
		
		$_globals = (array)SB_Globals::GetVar('js_globals');
		
		$globals = array_merge(array(
				'baseurl' 			=> BASEURL,
				'lang'				=> $lang,
				'dateformat'		=> $format[DATE_FORMAT]
		), $_globals);
		$globals = SB_Module::do_action('lt_js_globals', $globals);
		?>
		<script>var lt = <?php print json_encode($globals); ?>;</script>
		<?php 
	}
}
new LT_DefaultScripts();