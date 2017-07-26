<?php
define('TPL_FE_DIGIPRINT_DIR', dirname(__FILE__));
define('TPL_FE_DIGIPRINT_URL', TEMPLATES_URL. '/' . basename(TPL_FE_DIGIPRINT_DIR));
ini_set('display_errors', 1);error_reporting(E_ALL);
class LT_ThemeDigiprint
{
	public function __construct()
	{
		SB_Language::loadLanguage(LANGUAGE, 'digiprint', dirname(__FILE__) . SB_DS . 'locale');
		$this->AddActions();
	}
	protected function AddActions()
	{
		if( lt_is_admin() )
			return false;
		SB_Module::add_action('init', array($this, 'action_init'));
		SB_Module::add_action('template_file', array($this, 'action_template_file'));
		SB_Module::add_action('register_validation', array($this, 'action_register_validation'));
		SB_Module::add_action('after_insert_user', array($this, 'action_after_insert_user'));
	}
	public function action_init()
	{
		
	}
	public function action_template_file($tpl)
	{
		if( SB_Request::getString('view') == 'register' )
		{
			$tpl = 'register.php';
		}
		if( !sb_is_user_logged_in() && SB_Request::getString('view') != 'register' )
		{
			$tpl = 'login.php';
		}
		return $tpl;
	}
	public function action_register_validation($bool)
	{
		if( !SB_Request::getString('first_name') )
		{
			SB_MessagesStack::AddMessage(__('Debe ingresar su nombre', 'digiprint'), 'error');
			$bool = false;
		}
		if( !SB_Request::getString('last_name') )
		{
			SB_MessagesStack::AddMessage(__('Debe ingresar sus apellidos', 'digiprint'), 'error');
			$bool = false;
		}
		if( !SB_Request::getString('phone') )
		{
			SB_MessagesStack::AddMessage(__('Debe ingresar su numero de telefono', 'digiprint'), 'error');
			$bool = false;
		}
		if( !SB_Request::getInt('i_agree') )
		{
			SB_MessagesStack::AddMessage(__('Debe aceptar los terminos y condiciones', 'digiprint'), 'error');
			$bool = false;
		}
		return $bool;
	}
	public function action_after_insert_user($id, $data)
	{
		if( !SB_Request::getInt('digiprint') )
			return false;
		$data = SB_Request::getVars(array(
				'first_name',
				'last_name',
				'mobile',
				'phone',
				'company',
				'city',
				'int:zip_code',
				'email',
				'address_1'
		));
		$dbh = SB_Factory::getDbh();
		$dbh->Update('users', array('first_name' => $data['first_name'], 'last_name' => $data['last_name']), array('user_id' => $id));
		//##Register the customer
		$data['status'] = 'active';
		$data['last_modification_date'] = date('Y-m-d H:i:s');
		$data['creation_date'] = date('Y-m-d H:i:s');
		$customer_id = $dbh->Insert('mb_customers', $data);
		$meta = (array)SB_Request::getVar('meta', array());
		foreach($meta as $meta_key => $meta_value)
		{
			sb_update_customer_meta($customer_id, trim($meta_key), trim($meta_value));
		}
	}
}
new LT_ThemeDigiprint();
