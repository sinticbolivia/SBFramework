<?php
class LT_WordpressImporter
{
	protected $xmlFile = null;
	protected $attachments = null;
	
	public function __construct($file)
	{
		$this->xmlFile = $file;
	}
	public function ReadCategories()
	{
		$cats = array();
		$xml = new XMLReader();
		$xml->open($this->xmlFile);
		$xml->next('rss');
		//$xml->next('wp:category');
		//var_dump($xml->name);die();
		while($xml->read() && $xml->name != 'wp:category' ){$xml->next();}
		//var_dump($xml->name);
		do
		{
			//print ($xml->name) . "\n";
			$node = new SimpleXMLElement($xml->readOuterXml());
			$nterm_id 	= $node->xpath('/wp:category/wp:term_id');
			$ncat_name 	= $node->xpath('/wp:category/wp:cat_name');
			$nparent	= $node->xpath('/wp:category/wp:category_parent');
			$cat = array(
					'term_id' 	=> (int)$nterm_id[0],
					'parent'	=> (int)$nparent[0],
					'name' 		=> htmlentities($ncat_name[0])
			);
			$cats[] = $cat;
			/*
			//print $node->xpath('/wp:term_id');
			foreach($node->xpath('/wp:category/wp:cat_name') as $p => $v)
			{
				var_dump("$p => $v");
			}
			*/
			/*
			foreach($node->children() as $child)
			{
				print $child;
			}
			*/
			$xml->next();
		}while( $xml->read() && $xml->name == 'wp:category' );
		$xml->close();
		return $cats;
	}
	public function GetAttachments()
	{
		if( $this->attachments != null )
			return $this->attachments;
		
		$this->attachments = array();
		$xml = new XMLReader();
		$xml->open($this->xmlFile);
		$xml->next('rss');
		while($xml->read() && $xml->name != 'item' ){$xml->next();}
		do
		{
			$node = new SimpleXMLElement($xml->readOuterXml());
			$id 	= $node->xpath('/item/wp:post_id');
			$url 	= $node->xpath('/item/wp:attachment_url');
			$type	= $node->xpath('/item/wp:post_type');
			if( (string)$type[0] == 'attachment' )
			{
				$post = array(
						'id' 	=> (int)$id[0],
						'url'	=> (string)$url[0]
				);
				$index = 'id_' . $post['id'];
				$this->attachments[$index] = $post;
			}
			$xml->next();
		}while( $xml->read() && $xml->name == 'item' );
		$xml->close();
		return $this->attachments;
	}
	public function GetPosts($callback = null)
	{
		$posts = array();
		$xml = new XMLReader();
		$xml->open($this->xmlFile);
		$xml->next('rss');
		while($xml->read() && $xml->name != 'item' ){$xml->next();}
		do
		{
			$node = new SimpleXMLElement($xml->readOuterXml());
			$id 	= $node->xpath('/item/wp:post_id');
			$title 	= $node->xpath('/item/title');
			$slug 	= $node->xpath('/item/wp:post_name');
			$type	= $node->xpath('/item/wp:post_type');
			$parent = $node->xpath('/item/wp:post_parent');
			$content = $node->xpath('/item/content:encoded');
			if( (string)$type[0] == 'post' || (string)$type[0] == 'page' ) 
			{
				$post = array(
						'id' 		=> (int)$id[0],
						'parent'	=> (int)$parent[0],
						'title' 	=> htmlentities($title[0]),
						'slug'		=> (string)$slug[0],
						'type'		=> (string)$type[0],
						'content'	=> (string)$content[0],
						'meta'		=> array()
				);
				//##parse post meta
				$metas = $node->xpath('/item/wp:postmeta');
				foreach($metas as $meta)
				{
					$key 	= $meta->xpath('wp:meta_key');
					$value 	= $meta->xpath('wp:meta_value');
					$post['meta'][(string)$key[0]] = (string)$value[0];
				}
				$this->FindPostImage($post);
				$posts[] = $post;
			}
			else
			{
				
			}
			
			/*
			 //print $node->xpath('/wp:term_id');
			 foreach($node->xpath('/wp:category/wp:cat_name') as $p => $v)
			 {
			 var_dump("$p => $v");
			 }
			 */
			/*
			 foreach($node->children() as $child)
			 {
			 print $child;
			 }
			 */
			$xml->next();
		}while( $xml->read() && $xml->name == 'item' );
		$xml->close();
		return $posts;
	}
	public function FindPostImage(&$post)
	{
		if( !isset($post['meta']['_thumbnail_id']) )
			return false;;
		$this->GetAttachments();
		$index = 'id_' . $post['meta']['_thumbnail_id'];
		if( isset($this->attachments[$index]) )
		{
			$post['image'] = $this->attachments[$index]['url'];
		}
	}
	public function ImportPosts()
	{
		$posts = $this->GetPosts();
		$dbh = SB_Factory::getDbh();
		$user_id = sb_get_current_user()->user_id;
		foreach($posts as $p)
		{
			$content = array(
					'title'			=> $p['title'],
					'content'		=> $p['content'],
					'slug'			=> $p['slug'],
					'author_id'		=> $user_id,
					'type'			=> $p['type'],
					'status'		=> 'publish',
					'creation_date' => date('Y-m-d H:i:s')
			);
			$meta = array();
			
			if( isset($p['image']) )
			{
				//##download the post image
				$raw = sb_download_image($p['image']);
				$filename = UPLOADS_DIR . SB_DS . basename($p['image']);
				$filename = sb_get_unique_filename(basename($filename), dirname($filename));
				file_put_contents($filename, $raw);
				$meta[] = array('meta_key' => '_featured_image',
								'meta_value' => basename($filename)
				);
			}
			//##insert the content
			$id = $dbh->Insert('content', $content);
			//##prepare meta
			foreach($p['meta'] as $meta_key => $meta_value)
			{
				$meta[] = array('meta_key' 		=> $meta_key, 
								'meta_value' 	=> $meta_value,
								'content_id'	=> $id
				);
			}
			$meta[] = array(
					'meta_key' 		=> '_wp_id',
					'meta_value'	=> $p['id'],
					'content_id'	=> $id 
			);
			//##insert the post meta
			$dbh->InsertBulk('content_meta', $meta);
		}
	}
}
/*
$reader = new LT_WordpressImporter('data.xml');
$cats = $reader->ReadCategories();
$attachments = $reader->GetAttachments();
$posts = $reader->GetPosts();
//print_r($attachments);
print_r($posts);
*/