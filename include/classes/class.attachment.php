<?php
/**
 * 
 * @author marcelo
 *
 * @property int attachment_id
 * @property string title
 * @property string description
 * @property string mime
 * @property string file
 * @property int size
 * @property int parent
 * @property datetime last_modification_date
 * @property datetime creation_date
 */
class SB_Attachment extends SB_ORMObject
{
	public function __construct($id = null)
	{
		parent::__construct();
		if( $id )
			$this->GetDbData($id);
	}
	public function GetDbData($id)
	{
		$query = "SELECT * FROM attachments WHERE attachment_id = $id";
		if( !$this->dbh->Query($query) )
		{
			return null;
		}
		$this->_dbData = $this->dbh->FetchRow();
	}
	public function SetDbData($data)
	{
		$this->_dbData = $data;
	}
	public function Delete()
	{
		$query = "SELECT * FROM attachments WHERE parent = $this->attachment_id ";
		foreach($this->dbh->FetchResults($query) as $row)
		{
			$file = UPLOADS_DIR . SB_DS . $row->file;
			if( is_file($file) )
			{
				unlink($file);
			}
		}
		//##delete the main attachment
		if( is_file(UPLOADS_DIR. SB_DS . $this->file) )
			unlink(UPLOADS_DIR. SB_DS . $this->file);
		$this->dbh->Delete('attachments', array('parent' => $this->attachment_id));
		$this->dbh->Delete('attachments', array('attachment_id' => $this->attachment_id));
	}
	public function __get($var)
	{
		if( $var == 'id' )
			return $this->attachment_id;
		return parent::__get($var);
	}
}
class SB_AttachmentImage extends SB_Attachment
{
	protected $thumbnails;
	public function GetDbThumbnails()
	{
		$this->thumbnails = array();
		if( !(int)$this->attachment_id )
			return false;
			
		$query = "SELECT * FROM attachments WHERE parent = {$this->attachment_id} AND type = 'image'";
		foreach($this->dbh->FetchResults($query) as $row)
		{
			$thumb = new SB_AttachmentImage();
			$thumb->SetDbData($row);
			$this->thumbnails[] = $thumb;
		}
	}
	/**
	 * Get image thumbnail
	 * 
	 * Size example: 55x55|150x150|330x330|500x500
	 * @param string $size 
	 * @return  SB_AttachmentImage
	 */
	public function GetThumbnail($size)
	{
		$thumb = null;
		if( !$this->thumbnails || !count($this->thumbnails) )
			$this->GetDbThumbnails();
		if( !count($this->thumbnails) )
			return $this;
		foreach($this->thumbnails as $_thumb)
		{
			if( stristr($_thumb->file, $size) )
			{
				$thumb = $_thumb;
				break;
			}
		}
		//##if thumbnail not found full size exists, return full size
		if( !$thumb && is_file(UPLOADS_DIR . SB_DS . $this->file) )
		{
			$thumb = $this;
		}
		return $thumb != null ? $thumb : new SB_AttachmentImage();
	}
	/**
	 * Get image url
	 * 
	 * @return string 
	 */
	public function GetUrl()
	{
		return UPLOADS_URL . '/' . $this->file;
	}
}