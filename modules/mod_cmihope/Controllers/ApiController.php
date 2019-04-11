<?php
namespace SinticBolivia\SBFramework\Modules\Cmihope\Controllers;
use SinticBolivia\SBFramework\Classes\SB_ApiRest;
use SinticBolivia\SBFramework\Database\Classes\SB_DbTable;
use LT_ModCmiHope;

class ApiController extends SB_ApiRest
{
	protected $version = '1.0.0';
	
	public function task_default()
	{
		http_response_code(200);
		sb_response_json(array('status' => 'ok', 
				'code' => 200, 
				'message' => __('CMI Hope API REST', 'ch'),
				'version'	=> $this->version
		));
	}
	/**
	 * @method GET
	 * @return  
	 */
	public function task_doctors()
	{
		$dbh 			= LT_ModCmiHope::GetInstance()->GetOpenEMRDbHandle();
		
		$this->dbTable 	= SB_DbTable::GetTable('users', 1, $dbh);
		
		$limit 			= $this->request->getInt('limit', 25);
		$page			= $this->request->getInt('page', 1);
		$keyword		= $this->request->getString('keyword');
		$column			= $this->request->getString('column');
		$match			= $this->request->getString('match', 'equal');
		
		$items			= array();
		$total_rows 	= 0;//$this->dbTable->CountRows();
		$conds			= array('newcrop_user_role' => 'erxdoctor');
		
		if( $column && strpos($column, ',') === false && in_array($column, $this->dbTable->columns) && $match == 'equal' )
		{
			$conds[$column] = $keyword;
			$items 			= $this->dbTable->GetRows($limit, 0, $conds);
		}
		elseif( strpos($column, ',') !== false && $match == 'equal' )
		{
			foreach(explode(',', $column) as $col)
			{
				$conds[$col] = $keyword;
			}
			$items 	= $this->dbTable->GetRows($limit, 0, $conds);
		}
		elseif( $column && strpos($column, ',') !== false && $match == 'like' )
		{
			//print_r($conds);
			$items = $this->dbTable->Search($keyword, explode(',', $column), $conds);	
		}
		else
		{
			$items 	= $this->dbTable->GetRows($limit, 0, $conds);
		}
		$this->Response(array('items' => $items, 'total_items' => $total_rows, 'page' => $page/*, 'query' => $this->dbh->lastQuery*/));
	}
}