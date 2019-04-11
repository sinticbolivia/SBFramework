<?php
namespace SinticBolivia\SBFramework\Modules\Cmihope\Models;

use SinticBolivia\SBFramework\Classes\SB_Model;
use SinticBolivia\SBFramework\Database\Classes\SB_DbTable;
use LT_ModCmiHope;

class RoomModel extends SB_Model
{
	public function GetPatients($room_num)
	{
		$openemr_dbh = LT_ModCmiHope::GetInstance()->GetOpenEMRDbHandle();
	
		$date = date('Y-m-d');
		$query = "SELECT p.*,e.*,d.id as doctor_id,d.fname as doctor_fname,d.lname as doctor_lname
						FROM patient_data p, openemr_postcalendar_events e
						LEFT JOIN users d ON d.id = e.pc_aid
						WHERE 1 = 1
						AND p.pid = e.pc_pid
						AND e.pc_apptstatus = '<'
						AND e.pc_room = $room_num
						AND e.pc_eventDate = '$date'";
		
		return $openemr_dbh->FetchResults($query);
	}
}