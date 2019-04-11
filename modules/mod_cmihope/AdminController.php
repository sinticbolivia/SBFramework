<?php
namespace SinticBolivia\SBFramework\Modules\Cmihope;

use SinticBolivia\SBFramework\Classes\SB_Request;
use SinticBolivia\SBFramework\Classes\SB_Route;
use SinticBolivia\SBFramework\Classes\SB_MessagesStack;
use SinticBolivia\SBFramework\Classes\SB_Controller;
use SinticBolivia\SBFramework\Database\Classes\SB_DbTable;
use LT_ModCmiHope;

class AdminController extends SB_Controller
{
	public function task_sync()
	{
		/*
		set_time_limit(0);
		$store_id = 1;
		$this->SyncExamRooms($store_id);
		//$this->SyncCategories($store_id);
		//$this->SyncProducts($store_id);
		die(__METHOD__);
		*/
		$stores	= SB_DbTable::GetTable('mb_stores', 1)->GetRows(-1, 0, array());
		$title 	= __('Synchronization', 'cmihope');
		$this->SetVars(get_defined_vars());
		$this->document->SetTitle($title);
	}
	public function task_sync_examrooms()
	{
		set_time_limit(0);
		$store_id				= SB_Request::getInt('store_id');
		if( !$store_id )
		{
			SB_MessagesStack::AddMessage(__('The store identifier is invalid'), 'error');
			sb_redirect(SB_Route::_('index.php?mod=cmihope&view=sync'));
			return false;
		}
		
		$openemr_dbh 			= LT_ModCmiHope::GetInstance()->GetOpenEMRDbHandle();
		$table_list_options		= SB_DbTable::GetTable('list_options', 1, $openemr_dbh);
		$table_mb_warehouse 	= SB_DbTable::GetTable('mb_warehouse', 1);
		
		$exam_rooms 			= $table_list_options->GetRows(-1, 0, array('list_id' => 'patient_flow_board_rooms'));
		$count = 0;
		foreach($exam_rooms as $ex)
		{
			$id = $table_mb_warehouse->QueryRow(array('role' => 'exam_room', 
														'openemr_room_number' => $ex->option_id
												)
			);
			if( $id ) continue;
			$table_mb_warehouse->Insert(array(
				'store_id'				=> $store_id,
				'name'					=> $ex->title,
				'creation_date'			=> date('Y-m-d H:i:s'),
				'role'					=> 'exam_room',
				'openemr_room_number'	=> $ex->option_id
			));
			$count++;
		}
		SB_MessagesStack::AddMessage(sprintf(__('The exam rooms sync is completed, %d exam rooms created', 'cmihope'), $count), 'success');
		sb_redirect(SB_Route::_('index.php?mod=cmihope&view=sync'));
	}
	public function task_sync_categories()
	{
		set_time_limit(0);
		$store_id				= SB_Request::getInt('store_id');
		if( !$store_id )
		{
			SB_MessagesStack::AddMessage(__('The store identifier is invalid'), 'error');
			sb_redirect(SB_Route::_('index.php?mod=cmihope&view=sync'));
			return false;
		}
		$admin_dbh = LT_ModCmiHope::GetInstance()->GetSysAdminDbHandle();
		//##get grupos
		$grupos = SB_DbTable::GetTable('grupo', 1, $admin_dbh)->GetRows(-1);
		//print_r($grupos);die($admin_dbh->lastQuery);
		$mb_categories = SB_DbTable::GetTable('mb_categories', 1);
		$bulk = array();
		foreach($grupos as $g)
		{
			
			if( $cat_id = $this->dbh->GetVar("SELECT category_id FROM mb_categories WHERE extern_id = {$g->Id} LIMIT 1") )
			{
				continue;
			}
			$bulk[] = array(
				'name'			=> $g->nombre,
				'extern_id'		=> $g->Id,
				'store_id'		=> $store_id,
				'slug'			=> sb_build_slug($g->nombre),
				'parent'		=> 0,
				'extern_type'	=> 'grupo'
			);
		}
		$mb_categories->Insert($bulk, 1);
		SB_MessagesStack::AddMessage(__('The categories sync is completed', 'cmihope'), 'success');
		sb_redirect(SB_Route::_('index.php?mod=cmihope&view=sync'));
	}
	public function task_sync_products()
	{
		set_time_limit(0);
		$store_id				= SB_Request::getInt('store_id');
		if( !$store_id )
		{
			SB_MessagesStack::AddMessage(__('The store identifier is invalid'), 'error');
			sb_redirect(SB_Route::_('index.php?mod=cmihope&view=sync'));
			return false;
		}
		$user		= sb_get_current_user();
		$wid 		= 0;
		$admin_dbh 	= LT_ModCmiHope::GetInstance()->GetSysAdminDbHandle();
		//##get items
		$table_items = SB_DbTable::GetTable('item', 1, $admin_dbh);
		$table_grupo = $table_items->AddLeftJoin('grupo', 'id_grupo', 1);
		//print_r($table_grupo);
		unset($table_grupo->columns['nombre']);
		$items 			= $table_items->GetRows(-1, 0, array('tipo' => 'Drug'));
		
		$mb_products	= SB_DbTable::GetTable('mb_products', 1);
		$mb_categories 	= SB_DbTable::GetTable('mb_categories', 1);
		
		$bulk = array();
		$cats = array();
		$this->dbh->BeginTransaction();
		foreach($items as $item)
		{
			
			if( $product_id = $this->dbh->GetVar("SELECT product_id FROM mb_products WHERE extern_id = $item->id LIMIT 1") )
			{
				//##update prices
			}
			else
			{
				$p = array(
						'extern_id'					=> $item->id,
						'extern_type'				=> 'item',
						'product_code'				=> $item->codigo,
						'product_name'				=> $item->nombre,
						'product_price'				=> (float)$item->precio,
						'store_id'					=> $store_id,
						'user_id'					=> $user->user_id,
						'status'					=> 'publish',
						'base_type'					=> 'base',
						'slug'						=> sb_build_slug($item->nombre),
						'for_sale'					=> 1,
						'last_modification_date'	=> date('Y-m-d H:i:s'),
						'creation_date'				=> date('Y-m-d H:i:s'),
						'warehouse_id'				=> $wid
				);
					
				$id 	= $mb_products->Insert($p);
				$res 	= $mb_categories->Search(null, array(), array('extern_id' => $item->id_grupo));
				$cat 	= array_shift($res);
				if( $cat )
				{
					$cats[] = array('product_id' => $id, 'category_id' => $cat->category_id);
				
				}
			}
			
			
		}
		if( count($cats) )
		{
			$this->dbh->InsertBulk('mb_product2category', $cats);
		}
		
		//var_dump($this->dbh->lastQuery);
		$this->dbh->EndTransaction();
		SB_MessagesStack::AddMessage(__('The products sync is completed', 'cmihope'), 'success');
		sb_redirect(SB_Route::_('index.php?mod=cmihope&view=sync'));
	}
}