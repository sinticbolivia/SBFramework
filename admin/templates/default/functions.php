<?php
class LT_ThemeAdminDefault
{
	public function __construct()
	{
		$this->AddActions();
	}
	protected function AddActions()
	{
		SB_Module::add_action('lt_tinymce_args', array($this, 'tinymce_args'));
	}
	public function tinymce_args($args)
	{
		//$args['menubar'] = 'insert';
		//$args['toolbar'] = 'image';
		
		$args['image_list'] = array(
				array('title' => 'separador1', 'value' => 'http://500sitios.com/imag/separador1.png'),
				array('title' => 'separador2', 'value' => 'http://500sitios.com/imag/separador2.png'),
				array('title' => 'separador3', 'value' => 'http://500sitios.com/imag/separador3.png'),
				array('title' => 'separador4', 'value' => 'http://500sitios.com/imag/separador4.png'),
				array('title' => 'separador5', 'value' => 'http://500sitios.com/imag/separador5.png'),
				array('title' => 'separador6', 'value' => 'http://500sitios.com/imag/separador6.png'),
				array('title' => 'separador7', 'value' => 'http://500sitios.com/imag/separador7.png'),
				array('title' => 'separador8', 'value' => 'http://500sitios.com/imag/separador8.png'),
				array('title' => 'separador9', 'value' => 'http://500sitios.com/imag/separador9.png'),
				array('title' => 'separador10', 'value' => 'http://500sitios.com/imag/separador10.png')
				
		);
		
		return $args;
	}
}
new LT_ThemeAdminDefault();