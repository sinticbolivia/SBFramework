<?php
define('TPL_BE_DIGIPRINT_DIR', dirname(__FILE__));
define('TPL_BE_DIGIPRINT_URL', ADMIN_URL . '/templates/' . basename(TPL_BE_DIGIPRINT_DIR));
function dp_get_colores()
{
	$colores = array(
		'BLANCO'			=> '#FFFFFF',
		'ARENA'  			=> '#E5DBB6',
		'BEIGE'				=>  '#B0AA83',
		'CHOCOLATE'  		=> '#3F271E',
		'MARRON'  			=> '#49120C',
		'LADRILLO'  		=> '#8C270B',
		'OCRE'  			=> '#A0400E',
		'ROJO'  			=> '#CA1C09',
		'NARANJA'  			=> '#F96110',
		'MANGO'  			=> '#F9B208',
		'CANARIO'  			=> '#F7DE1D',
		'LIMA'  			=> '#8CCA1F',
		'JADE'  			=> '#057741',
		'OLIVO'  			=> '#534E32',
		'VERDE BOSQUE' 		=> '#062B11',
		'MARINO'  			=> '#121751',
		'DELFON'  			=> '#4A5675',
		'ROYAL'  			=> '#182986',
		'TURQUESA'  		=> '#18A0C4',
		'CELESTE'  			=> '#428BBE',
		'AZUL CLARO'  		=> '#94D5E5',
		'AQUA'  			=> '#C5E8E8',
		'ROSA PASTEL'  		=> '#F6CDE3',
		'CORAL'  			=> '#F6A2A5',
		'FUCSIA'  			=> '#EA2992',
		'MORADO'  			=> '#39066A',
		'NEGRO'  			=> '#000000',
		'CARBON'  			=> '#404040',
		'PLATA'  			=> '#C3C3C3',
		'JASPE'				=> '#F0F0F0',
		//------- COLORES JASPE-------
		'NARANJA JASPE'		=> '#EF7D62',
		'MARRON JASPE'  	=> '#7B5763',
		'NEGRO JASPE'  		=> '#595E5E',
		'MORADO JASPE' 		=> '#5C5C84',
		'TURQUESA JASPE'  	=> '#5188B8',
		'ROYAL JASPE'  		=> '#47639F'
	);
	return $colores;
}
function dp_tallas()
{
	$tallas = array(
		'XS', 'S', 'M', 'L', 'XL', '2XL', '3XL'
	);
	return $tallas;
}
function dp_estilos()
{
	$estilos = array(
		'normal' 			=> 'Normal',
		'cuello_redondo' 	=> 'Cuello redondo',
		'polo'				=> 'POLO',
		'cuello_v' 			=> 'Cuello V',
		'manga_larga' 		=> 'Manga Larga',
		'vestir' 			=> 'Vestir',
		'sudaderas' 		=> 'Sudaderas'
	);
	return $estilos;
}
function dp_build_order_code($sequence, $store)
{
    return sprintf("%s%s%s", is_object($store) ? $store->code : '', date('Ymd'), sb_fill_zeros($sequence));
}
class LT_AdminThemeDigiprint
{
	public function __construct()
	{
		SB_Language::loadLanguage(LANGUAGE, 'digiprint', dirname(__FILE__) . SB_DS . 'locale');
		$this->AddActions();
	}
	protected function AddActions()
	{
        SB_Module::add_action('init', array($this, 'action_init'));
		SB_Module::add_action('lt_tinymce_args', array($this, 'tinymce_args'));
        SB_Module::add_action('mb_build_order_code', 'dp_build_order_code');
		if( lt_is_admin() )
		{
			sb_add_style('bootstrap-select', BASEURL . '/js/bootstrap-select-1.10.0/css/bootstrap-select.min.css');
			sb_add_script(BASEURL . '/js/bootstrap-select-1.10.0/js/bootstrap-select.min.js', 'bootstrap-select');
			$view = SB_Request::getString('view');
			if( $view == 'orders.new' || $view == 'orders.edit' )
			{
				sb_add_script(BASEURL . '/js/sb-completion.js', 'sb-completion');
			}
			SB_Module::add_action('mb_save_branch', array($this, 'action_mb_save_branch'));
			SB_Module::add_action('mb_order_receipt_tpl', array($this, 'action_mb_order_receipt_tpl'));
			SB_Module::add_action('mb_ajax_search_results', array($this, 'action_mb_ajax_search_results'));
		}
		SB_Module::add_action('mb_insert_sale_order', array($this, 'action_mb_insert_sale_order'));
	}
    public function action_init()
    {
        $task = SB_Request::getTask();
        $method = lt_is_admin() ? 'task_admin_' . $task : 'task_' . $task;
        if(method_exists($this, $method) )
            call_user_func (array($this, $method));
    }
	public function tinymce_args($args)
	{
		//$args['menubar'] = 'insert';
		//$args['toolbar'] = 'image';
		/*
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
		*/
		return $args;
	}
	public function action_mb_save_branch($id, $data)
	{
		sb_redirect(SB_Route::_('index.php?mod=mb&view=settings'));
	}
	public function action_mb_order_receipt_tpl($tpl)
	{
		return dirname(__FILE__) . SB_DS . 'notas' . SB_DS . 'comprobante_pedido.php';
	}
    public function task_admin_build_order_code()
    {
        $store_id       = SB_Request::getInt('store_id');
        $next_sequence  = mb_get_order_next_sequence($store_id);
        $store          = new SB_MBStore($store_id);
        $res = array(
            'status' => 'ok',
            'code'      => dp_build_order_code($next_sequence, $store)
        );
        sb_response_json($res);
    }
    public function task_add_order_item()
    {
    	
    }
    public function task_admin_save_order_item_variation()
    {
        $product_id = SB_Request::getInt('product_id');
        $variation  = SB_Request::getString('variation');
        if( !$product_id || !$variation )
            return false;
        $variations = null;
        if( !SB_Session::getVar('variations') )
        {
            SB_Session::setVar('variations', array());
        }
        $variations =& SB_Session::getVar('variations');
        $key = 'product_'.$product_id;
        if( !isset($variations[$key]) )
        {
            $variations[$key] = array();
        }
        $variations[$key]['product_id'] = $product_id;
        $variations[$key][$variation]   = SB_Request::getVar($variation, array());
    }
    public function task_admin_get_variations()
    {
        $variation = SB_Request::getString('variation');
        if( !SB_Session::getVar('variations') )
        {
            sb_response_json(array('ok', 'variations' => array()));
        }
        $variations =& SB_Session::getVar('variations'); 
        //##check if we need a specific variation name
        if( $variation && isset($variations[$variation]) )
        {
            sb_response_json(array('status' => 'ok', 'variation' => $variations[$variation]));
        }
        sb_response_json(array('status' => 'ok', 'variations' => $variations));
    }
   	/**
   	 * Hook after a order has been registered or updated
   	 * @param int $order_id
   	 * @param array $data
   	 * @param array $items
   	 */
    public function action_mb_insert_sale_order($order_id, $data, $items)
    {
    	$order = new SB_MBOrder($order_id);
    	$variations =& SB_Session::getVar('variations');
    	foreach($order->GetItems() as $item)
    	{
    		$key = 'product_'.$item->product_id;
    		var_dump($key);
    		if( isset($variations[$key]) )
    		{
    			mb_update_order_item_meta($item->item_id, '_variations', $variations[$key]);
    		}
    	}
    }
    public function action_mb_ajax_search_results(&$rows)
    {
    	$dbh = SB_Factory::getDbh();
    	for($i = 0; $i < count($rows); $i++)
    	{
    		$metas = SB_Meta::getMetas('mb_product_meta', array(
    				'_price_range_1',
    				'_price_range_2',
    				'_price_range_3',
    				'_price_range_4',
    				'_price_range_5',
    				'color'
    		), 'product_id', $rows[$i]->product_id);
    		if( $metas ) foreach($metas as $meta_key => $meta_value)
    		{
    			$rows[$i]->$meta_key = json_decode($meta_value);
    		}
    	}
    }
}
new LT_AdminThemeDigiprint();
