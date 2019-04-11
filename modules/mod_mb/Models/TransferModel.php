<?php
namespace SinticBolivia\SBFramework\Modules\Mb\Models;
use SinticBolivia\SBFramework\Classes\SB_Model;
use SinticBolivia\SBFramework\Classes\SB_Module;
use SinticBolivia\SBFramework\Modules\Mb\Models\ProductModel;
use SinticBolivia\SBFramework\Database\Classes\SB_DbTable;
use SB_MBTransfer;
use SB_MBProduct;
use Exception;

class TransferModel extends SB_Model
{
	/**
	 * @namespace SinticBolivia\SBFramework\Modules\Mb\Models
	 * @var ProductModel
	 */
	protected $productModel;
    protected $table;
    
    public function __construct($dbh = null) 
    {
        parent::__construct($dbh);
        //$this->table = SB_DbTable::GetTable('mb_transfers', 1);
    }
    /**
     * 
     * @param type $id
     * @return SB_MBTransfer
     */
    public function Get($id)
    {
        return SB_MBTransfer::Get($id);
    }
	/**
	 * Creates a new transfer
	 * 
	 * from_store_id		=> The source store
	 * from_warehouse_id	=> The source warehouse
	 * items				=> An array of items to transfer
	 * 			qty			=> The quantity to transfer
	 * 			id			=> The product identifier
	 * 			batch		=> (optional)
	 * @param object $data 
	 * @return  
	 */
	public function Create(SB_MBTransfer $data)
	{
        //$currentUser = sb_get_current_user();
        if( !isset($data->items) || !is_array($data->items) || empty($data->items) )
            throw new Exception(__('The transfer need atleast on item', 'mb'));
        if( !isset($data->user_id) || !$data->user_id  )
            throw new Exception(__('The transfer has no user id', 'mb'));
        
		$transaction_type_id    = (int)mb_get_store_meta($data->from_store, '_transfer_out_tt_id');
        if( !$transaction_type_id )
            throw new Exception(__('The source store does not has a transaction type to do transfers', 'mb'));
        unset($data->id);
        $data->from_sequence    = $this->GetNextFromSequence($data->from_store);
        $data->to_sequence      = $this->GetNextToSequence($data->to_store);
        $data->status           = \SB_MBTransfer::STATUS_ON_THE_WAY;
        $data->creation_date	= date('Y-m-d H:i:s');
        $data->reception_date   = SB_DbTable::MIN_DATE;
        
        $items = $data->items;
        unset($data->items);
        $this->dbh->BeginTransaction();
        //##register the transfer
		$transfer_id = $this->dbh->Insert('mb_transfers', $data);
        $transferItems = array();
		foreach($items as $_item)
		{
            $item = (object)$_item;
			$product = new SB_MBProduct($item->id);
            
			if( !$product->product_id )
			{
				$error = sprintf(__('The product with id "%d" does not exists', 'mb'), $item->id);
				throw new Exception($error);
				break;
			}
			if( $product->warehouse_id != $data->from_warehouse )
			{
			
			}
			if( $product->product_quantity <= 0 || (int)$product->product_quantity < (int)$item->qty )
			{
				$error = sprintf(__('The product with code "%s" has no enough stock to transfer', 'mb'), $product->product_code);
				throw new Exception($error);
				break;
			}
			b_do_action('mb_transfer_before_add_item', true, $product);
            $tItem = (object)array(
					'transfer_id'		=> $transfer_id,
					'product_id'		=> $product->product_id,
					'batch'				=> isset($item->batch) ? $item->batch : '',
					'quantity'			=> $item->qty,
					'price'				=> $item->transfer_price ? $item->transfer_price : $product->product_price,
					'creation_date'		=> date('Y-m-d H:i:s')
			);
            
            $transferItems[] = $tItem;
            //##get the current product quantity
            $currentQty = $this->productModel->GetQuantity($product->product_id, $data->from_warehouse);
            $newQty     = $currentQty - $item->qty;
            //##update the product quantity
			$this->productModel->SetQuantity($product->product_id, $data->from_store_id, $data->from_warehouse, $newQty, 0, false);
			SB_Module::do_action('mb_transfer_update_source_product', $product, $data);
			//##insert kardex for output
			$kitem = $this->BuildKardexItem('output', $transfer_id, $product, $tItem, $transaction_type_id, $data->user_id);
			SB_Module::do_action_ref('mb_transfer_before_insert_kardex_item', $kitem, $product);
			$this->dbh->Insert('mb_product_kardex', $kitem);
		}
        $this->dbh->InsertBulk('mb_transfer_items', $transferItems);
		$this->dbh->EndTransaction();
    
		return $this->Get($transfer_id);
	}
    /**
     * Accept a transfer
     * 
     * @param SB_MBTransfer $transfer
     * @return SB_MBTransfer
     */
	public function Accept(SB_MBTransfer $transfer)
	{
		$user 			= sb_get_current_user();
		$receiver_id 	= $user->user_id;
		//##update the product stock into destination store
		foreach($transfer->GetItems() as $item)
		{
			if( $item->status == 'complete' ) continue;
			//##check if product exists on destination store
			$query = "SELECT * FROM mb_products 
						WHERE product_code = '$item->product_code' 
						AND store_id = $transfer->to_store ";
			//$query .= "AND warehouse_id	= $this->to_warehouse ";
			$query .= "LIMIT 1";
			$row	= $this->dbh->FetchRow($query);
			$product_id = null;
			$quantity_balance 	= $item->quantity;
			//##check if product exists
			if( !$row )
			{
				//##get the source product
				$p 					= new SB_MBProduct($item->product_id);
				
				//##create the product into destination store
				$data = (array)$p->_dbData;
				unset($data['extern_id'],$data['product_line_id'], $data['department_id'], $data['width'], 
						$data['height'], $data['unit_pack']);
				
				$data['user_id'] 				= $user->user_id;
				$data['store_id']				= $transfer->to_store;
				//$data['warehouse_id']			= $this->to_warehouse;
				$data['last_modification_date'] = date('Y-m-d H:i:s');
				$data['creation_date'] 			= date('Y-m-d H:i:s');
				$data['product_quantity']		= $item->quantity;
				//$data['product_price']		= $p->transfer_price;
				unset($data['product_id'], $data['transfer_qty'], $data['transfer_price']);
				$product_id = $this->productModel->Create((object)$data, array(), $p->meta, $user);
				//##set quantities
				$this->productModel->SetQuantity($product_id, $transfer->to_store, $transfer->to_warehouse, $item->quantity);
				
				SB_Module::do_action('mb_transfer_product_created', $item, $product_id, $p, $this);
				$this->dbh->Update('mb_transfer_items', array('status' => 'complete'), array('id' => $item->id));
			}
			else
			{
				$product_id = $row->product_id;
				//##set quantities
				$this->productModel->SetQuantity($product_id, $transfer->to_store, $transfer->to_warehouse, 
													$item->quantity);
				$quantity_balance = $row->product_quantity + $item->quantity;
				
				SB_Module::do_action('mb_transfer_product_updated', $row->product_id, $item, $this);
				
				$this->dbh->Update('mb_transfer_items', array('status' => 'complete'), array('id' => $item->id));
			}
			//##insert product kardex for input
			$kitem = array(
					'product_id' 			=> $product_id,
					'in_out'				=> 'input',
					'quantity'				=> $item->quantity,
					'quantity_balance'		=> $quantity_balance,
					'unit_price'			=> $item->price,
					'total_amount'			=> $quantity_balance * $item->price,
					'monetary_balance'		=> $quantity_balance * $item->price,
					'transaction_type_id'	=> (int)$transfer->target_store->_transfer_tt_id,
					'author_id'				=> $receiver_id,
					'transaction_id'		=> $transfer->id,
					'creation_date'			=> date('Y-m-d H:i:s')
			);
			SB_Module::do_action_ref('mb_transfer_before_insert_kardex_item_input', $kitem, $item);
			$this->dbh->Insert('mb_product_kardex', $kitem);
		}
        $transfer->receiver_id      = $receiver_id;
        $transfer->reception_date   = date('Y-m-d H:i:s');
        $transfer->status           = SB_MBTransfer::STATUS_COMPLETED;
		//##update the transfer status
		$transfer->Update();
		SB_Module::do_action('mb_transfer_complete', $transfer);
		
		return $this->Get($transfer->id);
		
	}
    public function Delete($id, $fisically = true)
    {
        $transfer = $this->Get($id);
        if( !$transfer || !$transfer->id )
        {
            throw new Exception(__('The transfer does not exists', 'mb'));
        }
        $this->dbh->BeginTransaction();
        //##delete transfer items
        $this->dbh->Delete('mb_transfer_items', array('transfer_id' => $transfer->id));
        $transfer->Delete($fisically);
        $this->dbh->EndTransaction();
        return true;
    }
    public function Revert(SB_MBTransfer $transfer)
	{
		$user = sb_get_current_user();
		$res = true;
		if( $transfer->status == 'complete' )
		{
			foreach($transfer->GetItems() as $item)
			{
				$prod = new SB_MBProduct($item->product_id);
                //##update source store
                $this->productModel->SetQuantity($prod->product_id, 
                                                    $prod->store_id, 
                                                    $transfer->from_warehouse, 
                                                    $item->quantity);
                $transferredProduct = $prod;
                //##check if transfer was for different store
				if( $transfer->from_store != $transfer->to_store )
                {
                    $query = "SELECT * FROM mb_products ".
                            "WHERE product_code = '$prod->product_code' ".
                            "AND store_id = $transfer->to_store LIMIT 1";
                    //##update distination store
                    $transferredProduct = $this->dbh->FetchRow($query);
                    
                }
				if( $transferredProduct && $transferredProduct->product_id )
                {
                    //##get current quantity
                    $currentQty = $this->productModel->GetQuantity($transferredProduct->product_id, $transfer->to_warehouse);
                    //##update destination store quantities
                    $this->productModel->SetQuantity($transferredProduct->product_id, 
                                                $transferredProduct->store_id, 
                                                $transfer->to_warehouse, 
                                                $currentQty - $item->quantity);

                }
				SB_Module::do_action('mb_transfer_revert_item', $item, $transferredProduct, $transfer->status);
				$this->dbh->Update('mb_transfer_items', array('status' => 'reverted'), array('id' => $item->id));
			}
            $transfer->status = SB_MBTransfer::STATUS_REVERTED;
			$transfer->Update();
		}
		elseif( $transfer->status == 'on_the_way' )
		{
			foreach($transfer->GetItems() as $item)
			{
				$prod = new SB_MBProduct($item->product_id);
				//##update source store
                $this->productModel->SetQuantity($prod->product_id, 
                                                    $prod->store_id, 
                                                    $transfer->from_warehouse, 
                                                    $item->quantity);
                $sourceStore = new SB_MBStore($transfer->from_store);
				//##build kardex
				$balance = $prod->product_quantity + $item->quantity;
				$kitem = array(
					'product_id' 			=> $item->product_id,
					'in_out'				=> 'input',
					'quantity'				=> $item->quantity,
					'quantity_balance'		=> $balance,
					'unit_price'			=> $prod->product_cost,
					'total_amount'			=> $prod->product_cost * $item->quantity,
					'monetary_balance'		=> $balance + $prod->product_cost,
					'transaction_type_id'	=> $sourceStore->_tt_transfer_reverted,
					'author_id'				=> $user->user_id,
					'transaction_id'		=> $transfer->id,
					'creation_date'			=> date('Y-m-d H:i:s')
				);
				SB_Module::do_action_ref('mb_transfer_revert_item_kardex', $kitem, $item, $prod);
				$this->dbh->Insert('mb_product_kardex', $kitem);
				SB_Module::do_action('mb_transfer_revert_item', $item, array('source_product' => $prod), $this->status);
				$this->dbh->Update('mb_transfer_items', array('status' => SB_MBTransfer::STATUS_REVERTED), 
                                    array('id' => $item->id));
			}
            $transfer->status = SB_MBTransfer::STATUS_REVERTED;
			$transfer->Update();
		}
		
		return $res;
	}
    public function GetNextFromSequence($store_id)
	{
		$year = date('Y');
		//$query = "SELECT MAX(from_sequence) FROM mb_transfers WHERE from_store = $store_id";
		$query = "SELECT from_sequence
					FROM mb_transfers 
					WHERE from_store = $store_id ";
		switch( $this->dbh->db_type )
		{
			case 'sqlite3':
				$query .= "AND strftime('%Y', creation_date) = '$year' ";
			break;
			case 'mysql';
			default:
				$query .= "AND YEAR(creation_date) = '$year' ";
			break; 
		}
		$query .= "ORDER BY creation_date DESC LIMIT 1";
		$seq = (int)$this->dbh->GetVar($query);
		if( !$seq )
			$seq = 1;
		else
			$seq++;
		
		return $seq;
	}
	public function GetNextToSequence($store_id)
	{
		$year = date('Y');
		//$query = "SELECT MAX(to_sequence) FROM mb_transfers WHERE to_store = $store_id";
		$query = "SELECT to_sequence
					FROM mb_transfers 
					WHERE to_store = $store_id ";
		switch( $this->dbh->db_type )
		{
			case 'sqlite3':
				$query .= "AND strftime('%Y', creation_date) = '$year' ";
				break;
			case 'mysql';
			default:
				$query .= "AND YEAR(creation_date) = '$year' ";
				break;
		}
		
		$query .= "ORDER BY creation_date DESC LIMIT 1";
					
		$seq = (int)$this->dbh->GetVar($query);
		if( !$seq )
			$seq = 1;
		else
			$seq++;
	
		return $seq;
	}
    public function BuildKardexItem($inputOutput, $transferId, $product, $transferItem, $transactionTypeId, $authorId)
    {
        $item = (object)array(
					'product_id' 			=> $product->product_id,
					'in_out'				=> $inputOutput,
					'quantity'				=> $transferItem->quantity,
					'quantity_balance'		=> $product->product_quantity - $transferItem->quantity,
					'unit_price'			=> $product->product_price,
					'total_amount'			=> ($product->product_quantity - $transferItem->quantity) * $product->product_price,
					'monetary_balance'		=> ($product->product_quantity - $transferItem->quantity) * $product->product_cost,
					'transaction_type_id'	=> $transactionTypeId,
					'author_id'				=> $authorId,
					'transaction_id'		=> $transferId,
					'creation_date'			=> date('Y-m-d H:i:s')
				
		);
        
        return SB_Module::do_action('mb_build_transfer_kardex_item', $item);
    }
}