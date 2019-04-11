<?php
namespace SinticBolivia\SBFramework\Modules\Cmihope\Models;

use SinticBolivia\SBFramework\Classes\SB_Model;
use SinticBolivia\SBFramework\Database\Classes\SB_DbTable;
use SinticBolivia\SBFramework\Modules\Cmihope\Models\AssignmentModel;

class PacientModel extends SB_Model
{
	/**
	 * @namespace SinticBolivia\SBFramework\Modules\Cmihope\Models
	 * @var AssignmentModel
	 */
	protected $assignmentModel;
	
	public function GetAssignment($patient_id, $event_id, $room_id, $status = '@')
	{
		$table = SB_DbTable::GetTable('cmihope_patient_assignments', 1);
	
		$obj = $table->QueryRow(array(
			'room_id'		=> $room_id,
			'patient_id'	=> $patient_id,
			'event_id'		=> $event_id
		));
		if( !$obj )
			return null;
		$obj->items = $this->assignmentModel->GetDetails($obj->id);
		
		return $obj;
	}
}