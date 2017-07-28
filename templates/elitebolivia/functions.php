<?php
function isMobile() 
{
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}
class LT_TemplateEliteBolivia
{
	public function __construct()
	{
		$this->AddActions();
	}
	public function AddActions()
	{
		SB_Module::add_action('init', array($this, 'action_init'));
		SB_Module::add_action('emono_before_show_default_products', array($this, 'emono_default'));
		SB_Module::add_action('quote_tpl', array($this, 'action_quote_tpl'));
		if( lt_is_admin() )
		{
			SB_Module::add_action('mb_before_import_item', array($this, 'action_mb_before_import_item'));
		}
	}
	public function action_init()
	{
		$task = SB_Request::getTask();
		if( $task == 'send_contact' )
		{
			$page_id = SB_Request::getInt('page_id'); 
			$data = SB_Request::getVars(array(
			'nombre', 'apellido', 'email', 'mensaje'));
			$page = new LT_Article($page_id);
			$to = $page->_mail;
			$subject 	= __('Nueva Solicitud de Contacto', 'elite');
			$message	= "Hola Administrador.<br/><br/>".
							"Tienes una nueva solicitud de contacto, puedes ver los detalles abajo.<br/><br/>".
							"Nombres: {$data['nombre']}<br/>".
							"Apellidos: {$data['apellido']}<br/>".
							"Email: {$data['email']}<br/>".
							"Mensaje:<br/>{$data['mensaje']}<br/><br/>".
							"Saludos.";
			$headers 	= array('Content-Type: text/html', 
								sprintf("From: %s %s <%s>", $data['nombre'], $data['apellido'], $data['email']));
			lt_mail($to, $subject, $message, $headers);
			lt_mail('marce_nickya@yahoo.es', $subject, $message, $headers);
			SB_MessagesStack::AddMessage(__('Gracias por tu mensaje, nos pondremos en contacto en un plazo de 24 horas.', 'elite'), 'success');
			sb_redirect($page->link);
		}
	}
	public function emono_default(&$ctrl)
	{
		$ctrl->continue_task = false;
		$categories = SB_DbTable::GetTable('mb_categories', 1)
						->GetRows(-1, 0, array('parent' => 0), 'SB_MBCategory');
		sb_set_view_var('categories', $categories);
		sb_set_view_var('products', array());
		sb_set_view_var('total_pages', 1);
		sb_set_view_var('current_page', 1);
		$ctrl->document->templateFile = 'tpl-emono.php';
		$ctrl->document->SetTitle(__('Products', 'emono'));
	}
	public function action_mb_before_import_item(&$data)
	{
		$category_id 	= SB_Request::getInt('category_id');
		if( $category_id <= 0 )
			return true;
		$table 	= SB_DbTable::GetTable('mb_categories', true);
		$cat 	= $table->GetRow($category_id);
		$data['product_name'] = "$cat->name {$data['product_name']}";
		//print_r($data);die();
		return null;
	}
	public function action_quote_tpl($tpl)
	{
		$tpl = dirname(__FILE__) . SB_DS . 'plantillas' . SB_DS . 'cotizacion.php';
		return $tpl;
	}
}
new LT_TemplateEliteBolivia();