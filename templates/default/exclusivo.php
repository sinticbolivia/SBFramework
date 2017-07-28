<?php
require_ONCE INCLUDE_DIR . SB_DS . 'template-functions.php';
class LT_Exclusivo
{
	protected $dbh;
	
	public function __construct()
	{
		$this->dbh = SB_Factory::GetDbh();
		$this->AddActions();
	}
	protected function AddActions()
	{
		if( lt_is_admin() )
		{
			SB_Module::add_action('admin_menu', array($this, 'admin_menu'));
		}
		SB_Module::add_action('init', array($this, 'init'));
		SB_Module::add_action('payments_accepted', array($this, 'action_payments_accepted'));
	}
	public function admin_menu()
	{
		SB_Menu::addMenuChild('menu-content', 'Accesos', '#', 'menu-accesos', 'manage_backend');
		SB_Menu::addMenuChild('menu-accesos', 'Pendientes', SB_Route::_('index.php?mod=modules&view=pendientes'), 
								'menu-pendientes', 'manage_backend');
		SB_Menu::addMenuChild('menu-accesos', 'Enviados', SB_Route::_('index.php?mod=modules&view=enviados'), 
								'menu-enviados', 'manage_backend');
	}
	public function init()
	{
		$task = SB_Request::getTask();
		$view = SB_Request::getString('view');
		if( $task == 'setup_exclusivo' )
		{
			$query = array(
				"CREATE TABLE IF NOT EXISTS acceso1(
					id 				bigint not null auto_increment primary key,
					nombre			varchar(128),
					asunto			varchar(128),
					texto			text,
					fecha_creacion	datetime
				)",
				"CREATE TABLE IF NOT EXISTS acceso2(
					id 				bigint not null auto_increment primary key,
					nombre			varchar(128),
					asunto			varchar(128),
					texto			text,
					metodo_pago		varchar(128),
					fecha_envio		datetime,
					fecha_creacion	datetime
				)"
			);
			foreach($query as $q)
			{
				$this->dbh->Query($q);
			}
		}
		elseif( $task == 'guardar_pendiente' )
		{
			$data = SB_Request::getVars(array(
				'nombre',
				'asunto',
				'texto'
			));
			$id = SB_Request::getInt('id');
			if( !$id )
			{
				$data['fecha_creacion'] = date('Y-m-d H:i:s');
				$this->dbh->Insert('acceso1', $data);
			}
			else
			{
				$this->dbh->Update('acceso1', $data, array('id' => $id));
			}
			SB_MessagesStack::AddMessage('Informacion guardada correctamente', 'success');
			sb_redirect(SB_Route::_('index.php?mod=modules&view=pendientes'));
		}
		elseif( $task == 'borrar_pendiente' )
		{
			$id = SB_Request::getInt('id');
			$this->dbh->Delete('acceso1', array('id' => $id));
			SB_MessagesStack::AddMessage('Registro pendiente borrado', 'success');
			sb_redirect(SB_Route::_('index.php?mod=modules&view=enviados'));
		}
		elseif( $task == 'borrar_enviado' )
		{
			$id = SB_Request::getInt('id');
			$this->dbh->Delete('acceso2', array('id' => $id));
			SB_MessagesStack::AddMessage('Registro enviado borrado', 'success');
			sb_redirect(SB_Route::_('index.php?mod=modules&view=enviados'));
		}
		elseif( $task == 'test_emails' && SB_Request::getString('key') == '$__$' )
		{
			//$res = ThemeDefault()->SendMail('marce_nickya@yahoo.es', 'test', 'Hole');
			//var_dump($res);die();
			$res = $this->EnviarAcceso1Email('marce_nickya@yahoo.es', 'zaxaa');
			var_dump($res);die();
		}
		elseif( $task == 'x_ver_enviado' && $id = SB_Request::getInt('id') )
		{
			$obj = SB_DbTable::GetTable('acceso2', 1)->GetRow($id);
			$obj->texto = str_replace("\n", "<br/>", $obj->texto);
			sb_response_json(array('status' => 'ok', 'msg' => $obj));
		}
		//##handle views
		if( $view == 'pendientes' )
		{
			$html = $this->GetViewDefaultPendientes();
			sb_set_view_var('_html_content', $html);
		}
		elseif( $view == 'enviados' )
		{
			$html = $this->GetViewDefaultEnviados();
			sb_set_view_var('_html_content', $html);
		}
		elseif( $view == 'nuevo_pendiente' )
		{
			$html = $this->GetViewNuevoPendiente();
			sb_set_view_var('_html_content', $html);
		}
		elseif( $view == 'nuevo_enviado' )
		{
			$html = $this->GetViewNuevoEnviado();
			sb_set_view_var('_html_content', $html);
		}
		elseif( $view == 'editar_pendiente' )
		{
			$id = SB_Request::getInt('id');
			$row = SB_DbTable::GetTable('acceso1', 1)->GetRow($id);
			sb_set_view_var('obj', $row);
			sb_set_view_var('_html_content', $this->GetViewNuevoPendiente());
		}
		elseif( $view == 'editar_enviado' )
		{
			$id = SB_Request::getInt('id');
			$row = SB_DbTable::GetTable('acceso2', 1)->GetRow($id);
			sb_set_view_var('obj', $row);
			sb_set_view_var('_html_content', $this->GetViewNuevoEnviado());
		}
		
	}
	protected function GetViewDefaultPendientes()
	{
		$table = new LT_TableList('acceso1', 'id', 'modules');
		$table->SetColumns(array(
			'id' 				=> array('label' => 'ID', 'can_order' => true),
			'nombre' 			=> array('label' => 'Nombre', 'can_order' => true),
			'asunto' 			=> array('label' => 'Asunto', 'can_order' => true),
			'fecha_creacion' 	=> array('label' => 'Fecha Creacion', 'can_order' => true),
		));
		$table->SetRowActions(array(
			'view:editar_pendiente' => array('label' => 'Editar', 'icon' => 'glyphicon glyphicon-edit'),
			'task:borrar_pendiente' => array('label' => 'Editar', 'icon' => 'glyphicon glyphicon-trash'),
		));
		$table->order_by 	= SB_Request::getString('order_by', 'fecha_creacion');
		$table->order		= SB_Request::getString('order', 'desc');
		$table->showSelector	= false;
		$table->Fill();
		ob_start();
		?>
		<div class="wrap">
			<div class="row">
				<div class="col-xs-12 col-sm-6 col-md-6">
					<h2>Pendientes</h2>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6">
					<div class="text-right">
						<a href="<?php print SB_Route::_('index.php?mod=content&view=nuevo_pendiente'); ?>" 
							class="btn btn-primary">Nuevo</a>
					</div>
				</div>
			</div>
			<?php $table->Show(); ?>
		</div>
		<?php
		return ob_get_clean();
	}
	protected function GetViewDefaultEnviados()
	{
		$table = new LT_TableList('acceso2', 'id', 'modules');
		$table->SetColumns(array(
			'id' 				=> array('label' => 'ID'),
			'nombre' 			=> array('label' => 'Nombre', 'can_order' => true),
			'asunto' 			=> array('label' => 'Asunto', 'can_order' => true),
			'metodo_pago'		=> array('label' => 'Metodo de Pago', 'can_order' => true),
			'fecha_envio' 		=> array('label' => 'Fecha Envio', 'can_order' => true),
			'fecha_creacion' 	=> array('label' => 'Fecha Creacion', 'can_order' => true),
		));
		$table->SetRowActions(array(
			//'view:editar_pendiente' => array('label' => 'Editar', 'icon' => 'glyphicon glyphicon-edit'),
			'task:borrar_enviado' 	=> array('label' => 'Borrar', 'icon' => 'glyphicon glyphicon-trash'),
			'view_enviado' 			=> array(
				'label' 	=> 'Ver', 
				'icon'		=> 'glyphicon glyphicon-eye-open', 
				'link'	 	=> 'javascript:;',
				
			),
		));
		$table->order_by 	= SB_Request::getString('order_by', 'fecha_creacion');
		$table->order		= SB_Request::getString('order', 'desc');
		$table->showSelector = false;
		$table->Fill();
		ob_start();
		?>
		<div class="wrap">
			<div class="row">
				<div class="col-xs-12 col-sm-6 col-md-6">
					<h2>Enviados</h2>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6">
					<div class="text-right">
						<!--
						<a href="<?php print SB_Route::_('index.php?mod=content&view=nuevo_enviado'); ?>"></a>
						-->
					</div>
				</div>
			</div>
			<?php $table->Show(); ?>
		</div>
		<script>
		jQuery('.btn-action-view_enviado').click(function(e)
		{
			jQuery('#msg-metodo-pago').html('');
			jQuery('#msg-enviado-fecha').html('');
			jQuery('#msg-enviado-subject').html('');
			jQuery('#msg-enviado-body').html('');
			jQuery.get('index.php?mox=x&task=x_ver_enviado&id=' + this.dataset.id, function(res)
			{
				jQuery('#msg-metodo-pago').html(res.msg.metodo_pago);
				jQuery('#msg-enviado-fecha').html(res.msg.fecha_envio);
				jQuery('#msg-enviado-subject').html(res.msg.asunto);
				jQuery('#msg-enviado-body').html(res.msg.texto);
				jQuery('#modal-ver').modal('show');
			});
			return false;
		});
		</script>
		<div id="modal-ver" class="modal fade" tabindex="-1" role="dialog">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">Mensaje Enviado</h4>
					</div>
					<div class="modal-body">
						<b>Metodo de Pago:</b><br/>
						<div id="msg-metodo-pago"></div>
						<b>Fecha Envio:</b><br/>
						<div id="msg-enviado-fecha"></div>
						<b>Asunto:</b><br/>
						<div id="msg-enviado-subject"></div>
						<b>Texto:</b><br/>
						<div id="msg-enviado-body" style="height:300px;overflow:auto;"></div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->
		<?php
		return ob_get_clean();
	}
	protected function GetViewNuevoPendiente()
	{
		global $view_vars;
		extract(isset($view_vars['editar_pendiente']) ? $view_vars['editar_pendiente'] : array() );
		ob_start();
		?>
		<div class="wrap">
			<h2>Nuevo Email Pendiente</h2>
			<form action="" method="post">
				<input type="hidden" name="task" value="guardar_pendiente" />
				<?php if( isset($obj) ): ?>
				<input type="hidden" name="id" value="<?php print $obj->id; ?>" />
				<?php endif; ?>
				<div class="container-fluid">
					<div class="row">
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<label><?php _e('Nombre'); ?></label>
								<input type="text" name="nombre" value="<?php print isset($obj) ? $obj->nombre : ''; ?>" class="form-control" required />
							</div>
							<div class="form-group">
								<label><?php _e('Asunto'); ?></label>
								<input type="text" name="asunto" value="<?php print isset($obj) ? $obj->asunto : ''; ?>" class="form-control" required />
							</div>
							<div class="form-group">
								<label><?php _e('Texto'); ?></label>
								<textarea name="texto" class="form-control"><?php print isset($obj) ? $obj->texto : ''; ?></textarea>
							</div>
							<div class="form-group">
								<a href="<?php print SB_Route::_('index.php?mod=modules&view=pendientes'); ?>" class="btn btn-danger">Volver</a>
								<button class="btn btn-primary">Guardar</button>
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6"></div>
					</div>
					
				</div>
			</form>
		</div>
		<?php
		return ob_get_clean();
	}
	protected function GetViewNuevoEnviado()
	{
		ob_start();
		
		return ob_get_clean();
	}
	protected function EnviarAcceso1Email($buyer_email, $payment_method)
	{
		ini_set('display_errors', 1);error_reporting(E_ALL);
		$rows = SB_DbTable::GetTable('acceso1', 1)->GetRows(1, 0, 
												array('order' => array(
																	'orderby' => 'fecha_creacion', 
																	'order' => 'ASC'
																)
												));
		if( !count($rows) )
		{
			ThemeDefault()->Log("No existen pendientes a enviar");
			return false;
		}
		$pendiente = array_shift($rows);
		
		ThemeDefault()->Log($pendiente);
		//##enviar email al comprador
		ThemeDefault()->Log("Enviando email al comprador: $buyer_email");
		ThemeDefault()->SendMail($buyer_email, $pendiente->asunto, str_replace("\n", "<br/>", $pendiente->texto),
									array(
										'ishtml' 	=> true,
										'from'		=> null,
										'from_name'	=> null
									)
		);
		//##enviar email al administrador
		//$email = 'accesos@accesoprivado.es';
		$email = 'nuevosusuarios@accesoprivado.es';
		$subject = 'Atencion: Nuevo Acceso enviado';
		$body = "Se ha enviado el Acceso al siguiente comprador:\n".
					"$buyer_email\n\n".
					"En medio de pago usado ha sido:\n".
					"$payment_method\n\n".
					"El texto que se ha enviado al comprador ha sido:\n\n".
					"{$pendiente->texto}\n\n";
		ThemeDefault()->Log("Enviando email a: $email");
		ThemeDefault()->SendMail($email, $subject, str_replace("\n", "<br/>", $body),
									array(
										'ishtml' 	=> true,
										'from'		=> null,
										'from_name'	=> null
									)
		);
		ThemeDefault()->Log("Borrando pendiente {$pendiente->id}");
		//##borrar el registro de la tabla acces1 (envios)
		$this->dbh->Delete('acceso1', array('id' => $pendiente->id));
		//##mover a la table enviados
		$data = (array)$pendiente;
		$data['metodo_pago'] = $payment_method;
		unset($data['id']);
		$data['fecha_envio'] = date('Y-m-d H:i:s');
		ThemeDefault()->Log($data);
		$id = $this->dbh->Insert('acceso2', $data);
		ThemeDefault()->Log("Enviado insertado $id");
	}
	public function action_payments_accepted($gateway)
	{
		ThemeDefault()->Log('action_payments_accepted');
		ThemeDefault()->Log('gateway: ' . $gateway);
		
		if( $gateway == 'zaxaa' )
		{
			$products 	= SB_Request::getVar('products');
			$product	= array_shift($products);
			ThemeDefault()->Log($product);			
			preg_match('/.*#([a-zA-Z0-9]+).*/', $product['prod_name'], $matches);
			if( isset($matches[1]) )
			{
				$group_key = trim($matches[1]);
				if( strtoupper($group_key) == 'PREMIUM' )
				{
					//##send emails
					$buyer_email = SB_Request::getString('cust_email');
					$this->EnviarAcceso1Email($buyer_email, $gateway);
				}
			}
			else
			{
				ThemeDefault()->Log('No match found for premium product');
			}
		}
		elseif( $gateway == 'paypal' )
		{
			$group_key 		= SB_Request::getString('item_number');
			if( strtoupper($group_key) == 'PREMIUM' )
			{
				//##send email
				$buyer_email = SB_Request::getString('payer_email');
				$this->EnviarAcceso1Email($buyer_email, $gateway);
			}
		}
		elseif( $gateway == 'click2sell' )
		{
			$product_name = SB_Request::getString('product_name');
			if( strstr($product_name, '#') )
			{
				list(,$group_key) = array_map('trim', explode('#', $product_name));
				if( !empty($group_key) && strtoupper($group_key) == 'PREMIUM' )
				{
					//##send emails
					$buyer_email = SB_Request::getString('buyer_email');
					$this->EnviarAcceso1Email($buyer_email, $gateway);
				}
			}
		}
	}
	
}
new LT_Exclusivo();