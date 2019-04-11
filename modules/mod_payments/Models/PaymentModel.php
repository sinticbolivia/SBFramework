<?php
namespace SinticBolivia\SBFramework\Modules\Payments\Models;
use SinticBolivia\SBFramework\Classes\SB_Model;
use SinticBolivia\SBFramework\Database\Classes\SB_DbTable;
use SinticBolivia\SBFramework\Database\Classes\SB_DbRow;
use SinticBolivia\SBFramework\Modules\Payments\Entities\Payment;

class PaymentModel extends SB_Model
{
	/**
	 * 
	 * @param mixed $id 
	 * @return \SinticBolivia\SBFramework\Modules\Payments\Entities\Payment
	 */
	public function Get($id)
	{
        $payment = Payment::Get($id);
        /*
		$table = SB_DbTable::GetTable('mb_payments', 1);
		$payment = $table->Get($id);
        */
        
		return $payment;
	}
    public function GetMain($id)
    {
        $payment = $this->Get($id);
        if( $payment->parent )
            $payment = $this->Get($payment->parent);
        
        return $payment;
    }
	public function UpdateStatus($id, $status)
	{
		$payment = $this->Get($id);
		if( !$payment )
			throw new Exception($this->__('The payment does not exists'));
		$payment->status = $status;
		$payment->Save();
		
		return $payment;
	}
	public function GetTotalPayments($month = null, $year = null)
	{
		$month 	= $month ? (int)$month : date('m');
		$year	= $year ? (int)$year : date('Y');
		
		$query 	= "SELECT SUM(amount) FROM mb_payments WHERE 1 = 1 AND status = 'received'";
		
		return (float)$this->dbh->GetVar($query);
	}
    public function Receive(Payment $payment)
    {
    }
    public function Delete(Payment $payment)
    {
    	if( !$payment || !$payment->id )
    		throw new Exception(__('Invalid payment object', 'payments'));
    	$payment->Delete();
    	
    	return true;
    }
}
