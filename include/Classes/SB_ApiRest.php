<?php
namespace SinticBolivia\SBFramework\Classes;
use SinticBolivia\SBFramework\Classes\SB_Controller;
use SinticBolivia\SBFramework\Classes\SB_Module;
use ReflectionClass;
use Exception;

abstract class SB_ApiRest extends SB_Controller
{
	protected $method;
	protected $dbh;
	protected $endPoints = array();
	protected $contentType;
	protected $acceptdContentTypes = array('application/json');
	protected $token = null;
	
	//protected $baseEndpoint;
	/**
	 * The Database table for this endpoint
	 * @var SB_DbTable
	 */
	protected $dbTable;
	
	public function __construct($dbh = null)
	{
		parent::__construct(null);
		$this->Start();
	}
	public function Start()
	{
		/*
		if( !$this->dbTable )
		{
			$this->Response(null, 'error', 404, __('No entity associated to API endpoint'));
		}
		*/
		$this->contentType = isset($_SERVER["CONTENT_TYPE"]) ? $_SERVER["CONTENT_TYPE"] : null;
		/*
		//##check endpoints
		if( !$this->endPoints || !count($this->endPoints) )
		{
			$this->ResponseError(__('No end points found'));
		}
		*/
		SB_Module::add_action('before_process_module', array($this, 'HandleRequest'));
		SB_Module::add_action('after_process_module', array($this, 'End'));
	}
	public function End()
	{
		$this->dbh->Close();
		die();
	}
	protected function Response($data, $type = 'ok', $code = 200, $message = null)
	{
		http_response_code($code);
		$response = array(
				'response' 	=> $type, 
				'code' 		=> $code, 
				'data' 		=> $data
		);
		
		if( $code != 200 )
			$response['error']		= $message;
		if( $code == 200 && $message )
			$response['message']	= $message;
				
		sb_response_json($response, false);
		$this->End();
	}
	public function ResponseOK($data, $message = null)
	{
		$this->Response($data, 'ok', 200, $message);
	}
	public function ResponseError($error, $code = 500)
	{
		$this->Response(null, 'error', $code, $error);
	}
    /**
     * Get the class method data
     * 
     * @param type $method
     * @return object
     */
	public function GetMethodData($method)
	{
		$self 				= new ReflectionClass($this);
		$reflectionMethod 	= $self->getMethod($method);
		$comment 			= $reflectionMethod->getDocComment();
		$res = preg_match_all('/@(\w+)\s+(.*)\s+/', $comment, $matches);
		if( !$res )
			return null;
		$data = array();
		foreach($matches[1] as $index => $key)
		{
			$data[$key] = $matches[2][$index];
		}
		
		return (object)$data;
	}
	public function HandleRequest($ctrl, $method)
	{
        if( !$method )
            return false;
		$data = $this->GetMethodData($method);
        if( !$data )
            return false;
		try
		{
            
			if( !$data->method || SB_Request::$requestMethod != $data->method )
				throw new Exception(__('The method does not exists or invalid request method'));
			if( isset($data->access) && strtoupper($data->access) == 'PROTECTED' )
			{
				//##check for session authentication
				if( !sb_is_user_logged_in() )
				{
					//##check JWT authentication
					$this->ValidateToken();
					$this->ValidateAuthentication();
				}	
				
			}
		}
		catch(Exception $e)
		{
			$this->ResponseError($e->getMessage());
		}
		
		return true;
		/*
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
		*/
	}
	/**
	 * Get and validate the authorization token
	 * 
	 * @return  
	 */
	protected function ValidateToken()
	{
		$authorization 	= $this->request->GetHeader('Authorization');
        //var_dump("$authorization");
		list($bearer)	= sscanf($authorization, 'Bearer %s');
		if( !$bearer )
			throw new Exception(__('Access denied, the token is invalid or empty'));
		$this->token	= $bearer;
		
		return true;
	}
	/**
	 * 
	 * @return  
	 */
	protected function ValidateAuthentication()
	{
		return true;
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
        if( !$this->table )
            return false;
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