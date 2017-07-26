<?php
/**
 * 
 * @author marcelo
 *
 * @property string 	$name
 * @property string	$description
 */
class LT_Section extends SB_ORMObject
{
	protected $sections = array();
	
	public function __construct($id = null)
	{
		if( $id )
			$this->GetDbData($id);
	}
	public function GetDbData($id)
	{
		$query = null;
		if( (int)$id )
		{
			$id = (int)$id;
			$query = "SELECT * FROM section WHERE section_id = $id LIMIT 1";
		}
		else 
		{
			$query = "SELECT * FROM section WHERE slug = '$id' LIMIT 1";
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
		if( !$this->section_id )
		{
			return false;
		}
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
	}
	public function GetArticles()
	{
		sb_include_module_helper('content');
		$args = array(
				'section_id' => $this->section_id,
				'order_by'	=> 'show_order',
				'order'		=> 'asc'
		);
		$args = SB_Module::do_action('section_query_contents_args', $args);
		$data = LT_HelperContent::GetArticles($args);
		return $data['articles'];
	}
	public function IsVisible()
	{
		$visible = true;
		if( $this->status != 'publish' )
			$visible = false;
		$publish_date = $this->_publish_date;
		
		if( !empty($publish_date) )
		{
			$publish_date = strtotime($publish_date);
			if( $publish_date > time() )
				$visible = false;
		}
		$end_date = $this->_end_date;
		if( !empty($end_date) )
		{
			$end_date = strtotime($end_date);
			if( $end_date <= time() )
			{
				$visible = false;
			}
		}
		return SB_Module::do_action('content_section_is_visible', $visible, $this);
	}
	public function __get($var)
	{
		if( $var == 'creation_date' )
		{
			return sb_format_datetime($this->_dbData->creation_date);
		}
		if( $var == '_end_date' )
		{
			return sb_format_datetime($this->meta['_end_date']);
		}
		if( $var == '_publish_date' )
		{
			return sb_format_datetime($this->meta['_publish_date']);
		}
		
		return parent::__get($var);
	}
}