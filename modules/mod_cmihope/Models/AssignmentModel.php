<?php
namespace SinticBolivia\SBFramework\Modules\Cmihope\Models;

use SinticBolivia\SBFramework\Classes\SB_Model;
use SinticBolivia\SBFramework\Database\Classes\SB_DbTable;

class AssignmentModel extends SB_Model
{
	public function Get($id)
	{
		$table = SB_DbTable::GetTable('cmihope_patient_assignments', 1);
		
		$obj = $table->Get($id);
		if( !$obj )
			return null;
		$obj->items = $this->GetDetails($id);
		
		return $obj;
		
	}
	public function GetDetails($id)
	{
		$assigment_detail 	= SB_DbTable::GetTable('cmihope_assignment_detail', 1);
		$mb_products 		= $assigment_detail->AddJoin('mb_products', 'product_id', 1);
		$items 				= $assigment_detail->GetRows(-1, 0, array('assignment_id' => $id));
		
		return $items;
	}
	public function Create($patient_id, $event_id, $room_id, $user_id)
	{
		$table = SB_DbTable::GetTable('cmihope_patient_assignments', 1);
	
		$id = $table->Insert(array(
			'room_id' 		=> $room_id, 
			'patient_id' 	=> $patient_id, 
			'event_id' 		=> $event_id, 
			'store_id'		=> 0,
			'user_id'		=> $user_id,
			'status' 		=> 'open'
		));
		
		return $this->Get($id);
	}
}