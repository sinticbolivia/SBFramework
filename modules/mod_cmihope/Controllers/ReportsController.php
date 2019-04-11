<?php
namespace SinticBolivia\SBFramework\Modules\Cmihope\Controllers;

use SinticBolivia\SBFramework\Classes\SB_Controller;
use SinticBolivia\SBFramework\Classes\SB_Route;
use SinticBolivia\SBFramework\Classes\SB_Request;
use SinticBolivia\SBFramework\Classes\SB_MessagesStack;
use SinticBolivia\SBFramework\Classes\SB_TableList;
use SinticBolivia\SBFramework\Database\Classes\SB_DbTable;

class ReportsController extends SB_Controller
{
	public function task_default()
	{
		if( !sb_is_user_logged_in() )
		{
			SB_MessagesStack::AddMessage(__('You need to start session', 'cmihope'), 'error');
			sb_redirect(SB_Route::_('index.php?mod=users&view=login'));
		}
		$user			= sb_get_current_user();
		$warehouse_id 	= SB_Request::getInt('warehouse_id');
		$mb_warehouse	= SB_DbTable::GetTable('mb_warehouse', 1);
		$warehouses		= array();
		if( $list = json_decode($user->_warehouses) )
		{
			$warehouses = $mb_warehouse->GetRowsIn('id', array_map('intval', $list), -1, 0, array('store_id' => (int)$user->_store_id));
		}
		$this->SetView('reports/default');
		$title = __('Invetory Report', 'cmihope');
		if( $warehouse_id )
		{
			//$mb_products = SB_DbTable::GetTable('mb_products', 1);
			//$mb_products->GetRows(-1, 0, array('store_id' => (int)$user->_store_id));
			
			$this->dbh->Select('product_id,product_code,product_name, product_quantity, product_price')
					->From('mb_products')
					->Where(null)
					->SqlAND(array('store_id' => $user->_store_id))
					->SqlAND(array('warehouse_id' => $warehouse_id));
				
			$table = new SB_TableList('mb_products', 'product_id', null);
			$table->itemsPerPage = -1;
			//$table->UseTableColumns();
			$table->SetQuery($this->dbh->builtQuery);
			//var_dump($this->dbh->builtQuery);
			$table->Fill();
			$table->showSelector = false;
			$table->SetRowActions(null);
			$cols = $table->columns;
			unset($cols['product_id']);
			/*
			
			unset($cols['product_id'], $cols['extern_type'],$cols['extern_id'], $cols['product_number'], $cols['stocking_code'], 
					$cols['product_description'], $cols['product_line_id'], $cols['type_id'], $cols['product_model']);
			*/
			$table->columns 	= $cols;
			$table->cssClass 	= 'table-striped';
			$table->showExport 	= true;
			$this->dbh->builtQuery = '';
		}
		
		$this->SetVars(get_defined_vars());
		$this->document->SetTitle($title);
	}
}