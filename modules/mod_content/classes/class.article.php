<?php
/**
 * 
 * @author marcelo
 * @property int content_id
 * @property string title
 * @property string content
 * @property string slug
 * @property string excerpt
 * @property string link
 */
class LT_Article extends SB_ORMObject
{
	protected $sections = array();
	
	public function __construct($id = null)
	{
		parent::__construct();
		if( $id )
			$this->GetDbData($id);
	}
	public function GetDbData($id)
	{
		$query = "SELECT * FROM content WHERE 1 = 1 ";
		if( (int)$id )
		{
			$query .= "AND content_id = $id";
		} 
		else 
		{
			$query .= "AND slug = '".trim($id)."'";
		}
		$dbh = SB_Factory::getDbh();
		if( !$dbh->Query($query) )
		{
			return false;
		}
		$this->_dbData = $dbh->FetchRow();
		$this->GetDbMeta();
		$this->GetDbSections();
	}
	public function SetDbData($data)
	{
		$this->_dbData = $data;
		if( !count($this->meta) )
			$this->GetDbMeta();
	}
	public function GetDbMeta()
	{
		if( !$this->content_id )
			return false;
		$dbh = SB_Factory::getDbh();
		$query = "SELECT * FROM content_meta WHERE content_id = $this->content_id";
		$dbh->Query($query);
		foreach($dbh->FetchResults() as $row)
		{
			$this->meta[$row->meta_key] = $row->meta_value;
		}
	}
	public function GetDbSections()
	{
		if( !(int)$this->content_id )
		{
			return false;
		}
		$query = '';
		if( $this->type != 'post' )
		{
			$query = "SELECT s.* FROM section2content s2c, section s WHERE 1 = 1 " .
					"AND s2c.section_id = s.section_id ".
					"AND s2c.content_id = $this->content_id";
		}
		else//if( $this->type == 'post' )
		{
			$query = "SELECT c.* FROM category2content c2c, categories c WHERE 1 = 1 " .
					"AND c2c.category_id = c.category_id ".
					"AND c2c.content_id = $this->content_id";
		}
		/*
		else 
		{
			$query = SB_Module::do_action('mb_content_get_sections_query', $this);
		}
		*/
		if( empty($query) || !is_string($query) )
		{
			return false;
		}
		$rows = $this->dbh->FetchResults($query);
		foreach($rows as $row)
		{
			$s = new LT_Section();
			$s->SetDbData($row);
			$this->sections[] = $s;
		}
	}
	public function GetSectionIds()
	{
		if( !count($this->sections) )
			$this->GetDbSections();
		$ids = array();
		foreach($this->sections as $section)
		{
			$ids[] = $this->type == 'post' ?  $section->category_id : $section->section_id;
		}
		
		return $ids;
	}
	public function __get($var)
	{
		if( $var == 'excerpt' )
		{
			return $this->TheExcerpt();
		}
		if( $var == 'content' )
		{
			return $this->TheContent();
		}
		if( $var == 'banner' )
		{
			return $this->TheBanner();
		}
		if( $var == 'link' )
		{
			$raw_link = 'index.php?mod=content&view=article&id=' . $this->content_id;
			$raw_link .= '&slug='.(empty($this->slug) ? sb_build_slug($this->title) : $this->slug);
			return SB_Route::_($raw_link, 'frontend');
		}
		return parent::__get($var);
	}
	public function IsVisible()
	{
		$visible = true;
		if( $this->status != 'publish' )
		{
			$visible = false;
		}
		$publish_date = $this->publish_date;
		if( !empty($publish_date) )
		{
			$publish_date = strtotime($this->publish_date);
			//var_dump($this->publish_date);
			//var_dump($publish_date);
			if( $publish_date > time() )
			{
				$visible = false;
			}
		}
		//var_dump($visible);die($this->status);
		$end_date = $this->end_date;
		if( !empty($end_date) )
		{
			$end_date = strtotime($this->end_date);
			/*
			var_dump(date('Y-m-d'));
			var_dump(date('Y-m-d', $end_date));
			var_dump("if( $end_date > ".time()." )");
			*/
			if( $end_date <= time() )
			{
				$visible = false;
			}
		}
		
		$visible = SB_Module::do_action('content_is_visible', $visible, $this);
		//var_dump($visible);
		return $visible;
	}
	public function TheTitle($max_length = -1)
	{
		if( $max_length == -1 )
			return $this->title;
		return substr($this->title, 0, $max_length) . '[...]';
	}
	public function TheContent()
	{
		SB_Globals::SetVar('article', $this);
		$content = lt_parse_shortcodes(stripslashes($this->_dbData->content));
		
		return $content;
	}
	public function TheExcerpt($length = 150)
	{
		$content = stripslashes(strip_tags($this->_dbData->content));
		if( strlen($content) > $length )
		{
			$content = substr($content, 0, $length);
		}
		return $content;
	}
	/**
	 * Get Content featured image
	 * 
	 * @param string $size  The image size 300x300|500x500|full
	 * @return  
	 */
	public function TheThumbnail($size = '330x330')
	{
		if( $this->_featured_image_id )
		{
			$img_src = $this->GetThumbnailUrl($size);
			return sprintf("<img src=\"%s\" alt=\"%s\" class=\"img-responsive img-thumbnail\" />", 
								$img_src, $this->title);
		}
		//##backward compatibility
		if( !isset($this->meta['_featured_image']) )
		{
			return sprintf("<img src=\"%s\" alt=\"%s\" class=\"img-responsive img-thumbnail\" />", 
							BASEURL . '/images/no-image.png', $this->title);;
		}
		return sprintf("<img src=\"%s/%s\" alt=\"%s\" class=\"img-responsive img-thumbnail\" />", 
						UPLOADS_URL, 
						$size == 'full' ? $this->meta['_featured_image_full'] : $this->meta['_featured_image'], $this->title);
	}
	public function GetThumbnailUrl($size = '330x330')
	{
		if( !$this->_featured_image_id )
		{
			return BASEURL . '/images/no-image.png';
		}
		$image = new SB_AttachmentImage($this->_featured_image_id);
		//print_r($image);
		$img_src = $size == 'full' ? $image->GetUrl() : $image->GetThumbnail($size)->GetUrl();
		return $img_src;
	}
	public function TheBanner()
	{
		if( !$this->_banner )
			return '';
		return sprintf("<img src=\"%s/%s\" alt=\"%s\" />", MOD_CONTENT_BANNERS_URL, $this->_banner, $this->title);
	}
}