<?php
/**
 * 
 * @author marcelo
 *
 * @property int 		category_id
 * @property string 	$name
 * @property string		$description
 */
class LT_Category extends SB_ORMObject
{
	
	public function __construct($id = null)
	{
		parent::__construct();
		if( $id )
			$this->GetDbData($id);
	}
	public function GetDbData($id)
	{
		$query = null;
		if( (int)$id )
		{
			$id = (int)$id;
			$query = "SELECT * FROM categories WHERE category_id = $id LIMIT 1";
		}
		else 
		{
			$query = "SELECT * FROM categories WHERE slug = '$id' LIMIT 1";
		}
		$dbh = SB_Factory::getDbh();
		
		if( !$dbh->Query($query) )
			return false;
		$this->_dbData = $dbh->FetchRow();
		$this->GetDbMeta();
		
	}
	public function SetDbData($data)
	{
		$this->_dbData = $data;
		if( !count($this->meta) )
			$this->GetDbMeta();
	}
	public function GetDbMeta()
	{
		if( !$this->category_id )
		{
			return false;
		}
		/*
		$dbh = SB_Factory::getDbh();
		$query = "SELECT * FROM section_meta WHERE section_id = $this->section_id";
		$dbh->Query($query);
		foreach($dbh->FetchResults() as $row)
		{
			$meta_value = json_decode($row->meta_value);
			if( is_object($meta_value) || is_array($meta_value) )
				$this->meta[$row->meta_key] = $meta_value;
			else 
				$this->meta[$row->meta_key] = $row->meta_value;
		}
		*/
	}
	public function GetPosts()
	{
		sb_include_module_helper('content');
		$args = array(
				'type'			=> 'post',
				'category_id' 	=> $this->category_id,
				'order_by'		=> 'show_order',
				'order'			=> 'asc',
				'page'			=> SB_Request::getInt('page', 1)
		);
		$args = SB_Module::do_action('section_query_contents_args', $args);
		$data = LT_HelperContent::GetArticles($args);
		return $data['articles'];
	}
	public function __get($var)
	{
		if( $var == 'creation_date' )
		{
			return sb_format_datetime($this->_dbData->creation_date);
		}
		
		return parent::__get($var);
	}
}