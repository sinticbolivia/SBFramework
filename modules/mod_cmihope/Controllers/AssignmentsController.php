<?php
namespace SinticBolivia\SBFramework\Modules\Cmihope\Controllers;

use SinticBolivia\SBFramework\Classes\SB_Request;
use SinticBolivia\SBFramework\Classes\SB_Route;
use SinticBolivia\SBFramework\Classes\SB_MessagesStack;
use SinticBolivia\SBFramework\Classes\SB_Controller;
use SinticBolivia\SBFramework\Database\Classes\SB_DbTable;
use SinticBolivia\SBFramework\Database\Classes\SB_DbRow;
use SinticBolivia\SBFramework\Modules\Cmihope\Models\PacientModel;
use SinticBolivia\SBFramework\Modules\Cmihope\Models\AsignmentModel;
use SinticBolivia\SBFramework\Modules\Cmihope\Models\RoomModel;

class AssignmentsController extends SB_Controller
{
	/**
	 * @namespace SinticBolivia\SBFramework\Modules\Cmihope\Models
	 * @var PacientModel
	 */
	protected $pacientModel;
	/**
	 * @namespace SinticBolivia\SBFramework\Modules\Cmihope\Models
	 * @var AssignmentModel
	 */
	protected $assignmentModel;
	/**
	 * @namespace SinticBolivia\SBFramework\Modules\Cmihope\Models
	 * @var RoomModel
	 */
	protected $roomModel;
	
	public function task_default()
	{
		if( !sb_is_user_logged_in() )
			sb_redirect(SB_Route::_('index.php?mod=users'));
		$user 	= sb_get_current_user();
		
		if( !$user->_warehouses || !json_decode($user->_warehouses) || !count((array)json_decode($user->_warehouses)) )
			lt_die(__('The user has no warehouses assigned'));
		//var_dump($user->user_id);
		$rooms 	= cmihope_get_exam_rooms(1, $user);
		//print_r($rooms);
		lt_add_js('assignments-js', MOD_CMIHOPE_URL . '/js/assignments.js?v=' . time());
		sb_add_js_global_var('cmihope', 
								'locale', 
								array(
									'ERROR_ROOM_ID_REQUIRED' => __('You need to select a exam room', 'cmihope'),
									'ERROR_PATIENT_ID_REQUIRED' => __('You need to select a patient', 'cmihope'),
								)
		);
		$drugstores = SB_DbTable::GetTable('mb_warehouse', 1)->GetRows(-1, 0, array('role' => 'drugstore') );
		$this->SetVars(compact(
			'rooms',
			'drugstores'
		));
	}
	public function ajax_search()
	{
		$keyword 		= SB_Request::getString('keyword');
		$store_id		= SB_Request::getInt('store_id', 1);
		$room_id		= SB_Request::getInt('room_id');
		$drugstore		= SB_Request::getInt('drugstore', 0);
		if( !$room_id )
		{
			sb_response_json(array('status' => 'error', 'error' => __('Invalid exam room identifier', 'cmihope')));
		}
		$table_products = SB_DbTable::GetTable('mb_products', 1);
		$rows 			= $table_products->Search($keyword, 
								array('product_code', 'product_name', 'product_barcode'), 
								array('store_id' => $store_id, 'warehouse_id' => $drugstore > 0 ? $drugstore : $room_id/*, 'for_sale' => 1*/)
		);
		sb_response_json(array('status' => 'ok', 'results' => $rows, 'query' => SB_Factory::getDbh()->lastQuery));
	}
	public function ajax_get_room_patients()
	{
		if( !sb_is_user_logged_in() )
		{
			sb_response_json(array('status' => 'error', 'error' => __('Invalid user session', 'cmihope')));
		}
		$user		= sb_get_current_user();
		$room_id 	= $this->request->getInt('room_id');
		$exam_room 	= SB_DbTable::GetTable('mb_warehouse', 1)->GetRow($room_id);
		if( !$exam_room )
		{
			sb_response_json(array(
				'status'	=> 'error',
				'error'		=> __('The exam room does not exists', 'cmihope')
			));
		}
		
		$patients 	= $this->roomModel->GetPatients($exam_room->openemr_room_number);
		//print_r($patients);die();
		$patient = isset($patients[0]) ? $patients[0] : null;
		$assignment = null;
		$items = array();
		if( $patient )
		{
			$assignment = $this->pacientModel->GetAssignment($patient->pc_pid, $patient->pc_eid, $room_id);
			if( $assignment )
			{
				if( $assignment->status != 'open' )
				{
					$assignment = null;
					$patient = null;
				}
				
			}
			else
			{
				$assignment = $this->assignmentModel->Create($patient->pc_pid, $patient->pc_eid, $room_id, $user->user_id);
				
			}
		}
		sb_response_json(array(
			'status'		=> 'ok',
			'assignment'	=> $assignment, 
			'patient'		=> $patient,
		));
	}
	public function task_addproduct()
	{
		$id 		= SB_Request::getInt('id');
		$product_id = SB_Request::getInt('product_id');
		$quantity	= SB_Request::getInt('quantity', -1);
		if( !$id )
		{
			sb_response_json(array('status' => 'error', 'error' => __('Invalid assignment identifier', 'cmihope')));
		}
		if( !$product_id )
		{
			sb_response_json(array('status' => 'error', 'error' => __('Invalid product identifier', 'cmihope')));
		}
		$table 		= SB_DbTable::GetTable('cmihope_patient_assignments', 1);
		$assignment = $table->GetRow($id);
		if( !$assignment )
		{
			sb_response_json(array('status' => 'error', 'error' => __('The assignment does not exists', 'cmihope')));
		}
		$table_detail 	= SB_DbTable::GetTable('cmihope_assignment_detail', 1);
		$row 			= $table_detail->QueryRow(array('assignment_id' => $assignment->id, 'product_id' => $product_id));
		//var_dump($row);
		if( $row && $row->id )
		{
			if( $quantity == 0 )
				$row->Delete();
			else 
			{
				$row->quantity = $quantity > 0 ? $quantity : (int)$row->quantity + 1;
				$row->Save();
			}
			sb_response_json(array(
					'status'	=> 'ok'
			));
		}
		$mb_products = SB_DbTable::GetTable('mb_products', 1);
		$product = $mb_products->GetRow($product_id);
		$id = $table_detail->Insert(array(
			'assignment_id'		=> $id,
			'product_id'		=> $product_id,
			'code'				=> $product->product_code,
			'name'				=> $product->product_name,
			'quantity'			=> 1,
			'price'				=> $product->product_price
		));
		sb_response_json(array(
				'status'	=> 'ok',
				'task'		=> 'created',
				'id'		=> $id
		));
	}
	/**
	 * Get request data from "Administrativo"
	 * 
	 * @return  
	 */
	public function task_update_stocks()
	{
		SB_Factory::getApplication()->Log($_POST);
	}
	public function task_close()
	{
		$id = SB_Request::getInt('id');
		if( !$id )
		{
			sb_response_json(array('status' => 'error', 'error' => __('Invalid assignment identifier', 'cmihope')));
		}
		$table_assignments = SB_DbTable::GetTable('cmihope_patient_assignments', 1);
		$assignment = $table_assignments->GetRow($id);
		if( !$assignment )
		{
			sb_response_json(array('status' => 'error', 'error' => __('The assignment does not exists', 'cmihope')));
		}
		$assignment->status = 'closed';
		$assignment->closed_time = date('Y-m-d H:i:s');
		$assignment->Save();
		//##change the status into openemr to checked out
		//$table_events = SB_DbTable::GetTable('openemr_postcalendar_events', 1, LT_ModCmiHope::GetInstance()->GetOpenEMRDbHandle());
		LT_ModCmiHope::GetInstance()->GetOpenEMRDbHandle()
			->Update('openemr_postcalendar_events', 
						array('pc_apptstatus' => '>'), 
						array('pc_eid' => $assignment->event_id));
		sb_response_json(array('status' => 'error', 'error' => __('The assignment has been closed', 'cmihope')));
	}
}