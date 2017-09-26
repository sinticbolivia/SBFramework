<?php
abstract class SB_ApiRest extends SB_Object
{
	protected $method;
	protected $dbh;
	protected $endPoints = array();
	protected $contentType;
	protected $acceptdContentTypes = array('application/json');
	//protected $baseEndpoint;
	/**
	 * The Database table for this endpoint
	 * @var SB_DbTable
	 */
	protected $dbTable;
	
	public function __construct($dbh = null)
	{
		$this->dbh = $dbh ? $dbh : SB_Factory::getDbh();
		
	}
	public function Start()
	{
		if( !$this->dbTable )
		{
			$this->Response(null, 'error', 404, __('No entity associated to API endpoint'));
		}
		$this->contentType = isset($_SERVER["CONTENT_TYPE"]) ? $_SERVER["CONTENT_TYPE"] : null;
		/*
		//##check endpoints
		if( !$this->endPoints || !count($this->endPoints) )
		{
			$this->ResponseError(__('No end points found'));
		}
		*/
	}
	protected function Response($data, $type = 'ok', $code = 200, $message = null)
	{
		$this->dbh->Close();
		http_response_code(200);
		sb_response_json(array(
				'response' 	=> $type, 
				'code' 		=> $code, 
				'error' 	=> $code == 404 ? $message : '',
				'message'	=> $code == 200 ? $message : '', 
				'data' 		=> $data
		));
	}
	public function ResponseError($error)
	{
		$this->Response(null, 'error', 404, $error);
	}
	public function HandleRequest()
	{
		
		switch(SB_Request::$requestMethod)
		{
			case 'GET':
				$id = SB_Request::getInt('id');
				if( $id )
					$this->Show($id);
				else
					$this->All(); 
			break;
			case 'POST':
				if( !$this->contentType || !in_array($this->contentType, $this->acceptdContentTypes) )
					$this->ResponseError(__('Invalid content type request'));
				$this->Save();
			break;
			case 'PUT':
				if( !$this->contentType || !in_array($this->contentType, $this->acceptdContentTypes) )
					$this->ResponseError(__('Invalid content type request'));
				$this->Update();
			break;
			case 'DELETE':
				$this->Delete();
			break;
		}
		die();
	}
	public function All()
	{
		$limit 			= SB_Request::getInt('limit', 25);
		$page			= SB_Request::getInt('page', 1);
		$keyword		= SB_Request::getString('keyword');
		$column			= SB_Request::getString('column');
		$match			= SB_Request::getString('match', 'equal');
		$store_id		= SB_Request::getInt('store_id');
		$warehouse_id	= SB_Request::getInt('warehouse_id');
		$products		= array();
		$total_rows 	= $this->dbTable->CountRows();
		$conds			= array();
		if( $store_id )
			$conds['store_id'] = $store_id;
		if( $warehouse_id )
			$conds['warehouse_id'] = $warehouse_id;
		
		if( $column && strpos($column, ',') === false && in_array($column, $this->dbTable->columns) && $match == 'equal' )
		{
			$conds[$column] = $keyword;
			$products 		= $this->dbTable->GetRows($limit, 0, $conds);
		}
		elseif( strpos($column, ',') !== false && $match == 'equal' )
		{
			foreach(explode(',', $column) as $col)
			{
				$conds[$col] = $keyword;
			}
			$products 	= $this->dbTable->GetRows($limit, 0, $conds);
		}
		elseif( $column && strpos($column, ',') !== false && $match == 'like' )
		{
			//print_r($conds);
			$products = $this->dbTable->Search($keyword, explode(',', $column), $conds);	
		}
		else
		{
			$products 	= $this->dbTable->GetRows($limit, 0, $conds);
		}
		$this->Response(array('items' => $products, 'total_items' => $total_rows, 'page' => $page/*, 'query' => $this->dbh->lastQuery*/));
	}
	public function Show($id)
	{
		$product = $this->dbTable->GetRow($id, 'SB_DbRow');
		$this->Response($product);
	}
	public function Save()
	{
		$data = file_get_contents('php://input');
		if( empty($data) )
			$this->ResponseError(__('The data is empty'));
		$product_data = json_decode($data);
		if( !$product_data )
			$this->ResponseError(__('The data format is invalid'));
		try
		{
			$id = $this->dbTable->Insert((array)$product_data);
			if( !$id )
				$this->Response(__('Unable to save data'));
		}
		catch(Exception $e)
		{
			$this->ResponseError($e->getMessage());
		}
	}
	public function Update()
	{
		$id = SB_Request::getInt('id');
		if( !$id )
		{
			$this->ResponseError(__('Invalid product identifier'));
		}
		$row = $this->dbTable->GetRow($id);
		if( !$row )
			$this->ResponseError(__('The identifier does not exists'));
		
		$data = file_get_contents('php://input');
		if( empty($data) )
			$this->ResponseError(__('The data is empty'));
		$product_data = json_decode($data);
		if( !$product_data )
			$this->ResponseError(__('The data format is invalid'));
		try
		{
			$id = $this->dbTable->UpdateRow($id, (array)$product_data);
			if( !$id )
				$this->ResponseError(__('Unable to save data'));
			$row = $this->dbTable->GetRow($id);
			$this->Response($row);	
		}
		catch(Exception $e)
		{
			$this->ResponseError($e->getMessage());
		}
	}
	public function Delete()
	{
		$id = SB_Request::getInt('id');
		if( !$id )
		{
			$this->ResponseError(__('Invalid product identifier'));
		}
		$row = $this->dbTable->GetRow($id);
		if( !$row )
			$this->ResponseError(__('The identifier does not exists'));
		$row->Delete();
		
	}
}