<?php
function sb_get_section_meta($section_id, $meta_key, $default = null)
{
	return SB_Meta::getMeta('section_meta', $meta_key, 'section_id', $section_id, $default);
}
function sb_add_section_meta($section_id, $meta_key, $meta_value)
{
	return SB_Meta::addMeta('section_meta', $meta_key, $meta_value, 'section_id', $section_id);
}
function sb_update_section_meta($section_id, $meta_key, $meta_value)
{
	return SB_Meta::updateMeta('section_meta', $meta_key, $meta_value, 'section_id', $section_id);
}
function sb_get_content_meta($content_id, $meta_key, $default = null)
{
	return SB_Meta::getMeta('content_meta', $meta_key, 'content_id', $content_id, $default);
}
function sb_add_content_meta($content_id, $meta_key, $meta_value)
{
	return SB_Meta::addMeta('content_meta', $meta_key, $meta_value, 'content_id', $content_id);
}
function sb_update_content_meta($content_id, $meta_key, $meta_value)
{
	return SB_Meta::updateMeta('content_meta', $meta_key, $meta_value, 'content_id', $content_id);
}
function sb_get_content_sections($parent_id = 0, $for_object = 'page')
{
	$parent_id = (int)$parent_id;
	$sections = LT_HelperContent::GetSections($parent_id, $for_object);
	foreach($sections as $index => $s)
	{
		$sections[$index]->childs = sb_get_content_sections($s->section_id, $for_object);
	}
	
	return $sections;
}
function sb_get_categories($parent_id = 0, $lang = LANGUAGE)
{
	$parent_id = (int)$parent_id;
	$query = "SELECT * FROM categories ";
	$query .= "WHERE parent_id = $parent_id AND lang_code = '$lang'";
	
	$cats = SB_Factory::getDbh()->FetchResults($query);
	
	foreach($cats as $i => $c)
	{
		//print_r($cats);die($query);
		$cats[$i]->childs = sb_get_categories($c->category_id);
	}
	
	return $cats;
}
function sb_sections_dropdown($args = array())
{
	sb_include_module_helper('content');
	$args = array_merge(array('id' => 'section_id', 
								'option_text' => __('-- seccion --', 'content'), 
								'selected' => -1), $args);
	function ___get_section_dropdown_childs($_s, $prefix = '', $args)
	{
		$ops = '';
		if( !count($_s->childs) )
			return $ops;
		foreach($_s->childs as $__s)
		{
			$selected = ((int)$args['selected'] == $__s->section_id) ? 'selected' : '';
			$ops .= '<option value="'.$__s->section_id.'" '.$selected.'>' . $prefix . $__s->name . '</option>';
			$ops .= count($__s->childs) ? ___get_section_dropdown_childs($__s, $prefix . '-', $args) : '';
		}
		return $ops;
	}
	$sections = sb_get_content_sections();
	
	$select = '<select id="'.$args['id'].'" name="'.$args['id'].'" class="form-control">';
	$select .= '<option value="-1">'.$args['option_text'].'</option>';
	foreach($sections as $s)
	{
		$selected = ((int)$args['selected'] == $s->section_id) ? 'selected' : '';
		$select .= '<option value="'.$s->section_id.'" ' . $selected . '>' . $s->name . '</option>';
		$select .= ___get_section_dropdown_childs($s, '-', $args);
	}
	$select.= '</select>';
	return $select;
}
function sb_sections_html_list($args = array())
{
	sb_include_module_helper('content');
	$args = array_merge(array(
								'id' 			=> 'sections', 
								'checked' 		=> array(), 
								'show_checkbox' => true, 
								'show_links' 	=> false, 
								'class' 		=> 'list',
								'for_object'	=> 'page'
						), $args);
	
	if( !function_exists('___get_section_list_childs') ):
	function ___get_section_list_childs($_s, $_args)
	{
		$ops = '';
		if( !count($_s->childs) )
			return $ops;
		$ops .= '<ul style="margin:0 0 0 10px;">';
		foreach($_s->childs as $__s)
		{
			if( !defined('LT_ADMIN') && !$__s->IsVisible() )
			{
				continue;
			}
			$ops .= '<li>';
			if( $_args['show_checkbox'] )
				$ops .= '<label><input type="checkbox" name="section[]" value="'.$__s->section_id.'" '.(in_array($__s->section_id, $_args['checked']) ? 'checked' : '').' />';
			if( $_args['show_links'] )
			{
				$ops .= sprintf("<a href=\"%s\">%s</a>", SB_Route::_('index.php?mod=content&view=section&id='. $__s->section_id), $__s->name);
			}
			else 
			{
				$ops .= $__s->name;
				if( $_args['show_checkbox'] )
					$ops .= '</label>';
			}
			$ops .= count($__s->childs) ? ___get_section_list_childs($__s, $_args) : '';
			$ops .= '</li>';
		}
		return $ops . '</ul>';
	}
	endif;
	$sections = sb_get_content_sections(0, $args['for_object']);
	$select = '<ul id="'.$args['id'].'" class="'.$args['class'].'">';
	foreach($sections as $s)
	{
		if( !defined('LT_ADMIN') && !$s->IsVisible() )
		{
			continue;
		}
		$select .= '<li>';
		if( $args['show_checkbox'] )
			$select .= '<label><input type="checkbox" name="section[]" value="'.$s->section_id.'" '.(in_array($s->section_id, $args['checked']) ? 'checked' : '').' />';
		if( $args['show_links'] )
		{
			$select .= sprintf("<a href=\"%s\">%s</a>", SB_Route::_('index.php?mod=content&view=section&id='. $s->section_id), $s->name);
		}
		else
		{
			$select .= $s->name;
			if( $args['show_checkbox'] )
				$select .= '</label>';
		}
		 
		$select .= ___get_section_list_childs($s, $args);
		$select .= '</li>';
	}
	$select.= '</ul>';
	return $select;
}
function sb_categories_html_list($args = array())
{
	sb_include_module_helper('content');
	$args = array_merge(array('id' => 'categories', 'checked' => array(), 'show_checkbox' => true, 
								'show_links' => false, 
								'class' => 'list'), $args);

	if( !function_exists('___get_categories_list_childs') ):
	function ___get_categories_list_childs($_s, $_args)
	{
		$ops = '';
		if( !count($_s->childs) )
			return $ops;
		$ops .= '<ul style="margin:0 0 0 10px;">';
		foreach($_s->childs as $__s)
		{
			$ops .= '<li>';
			if( $_args['show_checkbox'] )
				$ops .= '<input type="checkbox" name="section[]" value="'.$__s->category_id.'" '.(in_array($__s->category_id, $_args['checked']) ? 'checked' : '').' />';
			if( $_args['show_links'] )
			{
				$ops .= sprintf("<a href=\"%s\">%s</a>", SB_Route::_('index.php?mod=content&view=category&id='. $__s->category_id), $__s->name);
			}
			else
			{
				$ops .= $__s->name;
			}
			$ops .= count($__s->childs) ? ___get_categories_list_childs($__s, $_args) : '';
			$ops .= '</li>';
		}
		return $ops . '</ul>';
	}
	endif;
	$sections = sb_get_categories();
	
	$select = '<ul id="'.$args['id'].'" class="'.$args['class'].'">';
	foreach($sections as $s)
	{
		$select .= '<li>';
		if( $args['show_checkbox'] )
			$select .= '<input type="checkbox" name="section[]" value="'.$s->category_id.'" '.(in_array($s->category_id, $args['checked']) ? 'checked' : '').' />';
		if( $args['show_links'] )
		{
			$select .= sprintf("<a href=\"%s\">%s</a>", SB_Route::_('index.php?mod=content&view=category&id='. $s->category_id), $s->name);
		}
		else
		{
			$select .= $s->name;
		}
			
		$select .= ___get_categories_list_childs($s, $args);
		$select .= '</li>';
	}
	$select.= '</ul>';
	return $select;
}
function sb_categories_dropdown($args)
{
	sb_include_module_helper('content');
	$args = array_merge(array('id' => 'category_id', 
							'option_text' => __('-- seccion --', 'content'), 
							'selected' => -1), $args);
	function ___get_category_dropdown_childs($_s, $prefix = '', $args)
	{
		$ops = '';
		if( !count($_s->childs) )
			return $ops;
		foreach($_s->childs as $__s)
		{
			$selected = ((int)$args['selected'] == $__s->category_id) ? 'selected' : '';
			$ops .= '<option value="'.$__s->category_id.'" '.$selected.'>' . $prefix . $__s->name . '</option>';
			$ops .= count($__s->childs) ? ___get_category_dropdown_childs($__s, $prefix . '-', $args) : '';
		}
		return $ops;
	}
	$sections = sb_get_categories();
	$select = '<select id="'.$args['id'].'" name="'.$args['id'].'" class="form-control">';
	$select .= '<option value="-1">'.$args['option_text'].'</option>';
	foreach($sections as $s)
	{
		$selected = ((int)$args['selected'] == $s->category_id) ? 'selected' : '';
		$select .= '<option value="'.$s->category_id.'" ' . $selected . '>' . $s->name . '</option>';
		$select .= ___get_category_dropdown_childs($s, '-', $args);
	}
	$select.= '</select>';
	return $select;
}
function lt_content_get_unique_slug($str)
{
	$slug = sb_build_slug($str);
	$query = "SELECT group_concat(slug) FROM content WHERE slug <> ''";
	$dbh = SB_Factory::getDbh();
	if( !$dbh->Query($query) )
		return $slug;
	$string = $dbh->GetVar();
	$slugs = array_map('trim', explode(',', $string));
	if( !in_array($slug, $slugs) )
		return $slug;
	$i = -1;
	do
	{
		$i++;
		
	}while( in_array($slug . '-' . $i, $slugs) );

	return $slug . '-' . $i;
}
function lt_section_get_unique_slug($str)
{
	$slug = sb_build_slug($str);
	$query = "SELECT group_concat(slug) FROM section WHERE slug <> ''";
	$dbh = SB_Factory::getDbh();
	if( !$dbh->Query($query) )
		return $slug;
	$string = $dbh->GetVar();
	$slugs = array_map('trim', explode(',', $string));
	if( !in_array($slug, $slugs) )
		return $slug;
	//print_r($slugs); die();
	$i = -1;
	do
	{
		$i++;
		
	}while( in_array($slug . '-' . $i, $slugs) );

	return $slug . '-' . $i;
}
function lt_content_rewrite($raw_url, $components)
{
	$dbh = SB_Factory::getDbh();
	if( !$components['view'] )
		return $raw_url;
	
	if( $components['view'] == 'article' && (isset($components['id']) || isset($components['slug'])) )
	{
		$slug = '';
		if( isset($components['id']) && is_numeric($components['id']) )
		{
			$slug = $dbh->GetVar("SELECT slug FROM content WHERE content_id = {$components['id']} LIMIT 1");
		}
		else
		{
			$slug = $components['slug'];
		}
		//var_dump(BASEURL . '/' . $slug);
		return BASEURL . '/' . $slug; 
	}
	elseif( $components['view'] == 'section' && $components['id'] )
	{
		$slug = $dbh->GetVar("SELECT slug FROM section WHERE section_id = {$components['id']} LIMIT 1");
		if( empty( $slug ) )
			return $raw_url;
		//var_dump(BASEURL . '/' . $slug);
		return BASEURL . '/' . __('section', 'content') . '/' . $slug;
	}
	else
	{
		return $raw_url;
	}
}
function lt_content_get_page_templates()
{
	$templates = array();
	$tpl_dir = sb_get_template_dir('frontend');
	if( !$tpl_dir || $tpl_dir == TEMPLATES_DIR || !is_dir($tpl_dir) )
		return $templates;
		
	$dh = opendir($tpl_dir);
	while( ($file = readdir($dh)) !== false)
	{
		if( strstr($file, 'tpl-') )
		{
			$fh = fopen($tpl_dir . SB_DS . $file, 'r');
			$header = fread($fh, 1024);
			fclose($fh);
			if( preg_match('/Template:(.*)/', $header, $matches) )
			{
				$tpl = array(
						'file'	=> $file,
						'name'	=> trim($matches[1])
				);
				if( preg_match('/Fields:(.*)/', $header, $matches) )
				{
					$tpl['fields'] = explode(',', trim($matches[1]));
				}
				$templates[] = $tpl;
			}
			
			
		}
	}
	closedir($dh);
	return $templates;
}
function lt_content_get_frontpage_items($limit = 6)
{
	$dbh = SB_Factory::getDbh();
	
	$query = "SELECT c.* 
				FROM content c, content_meta cm 
				WHERE 1 = 1
				AND c.content_id = cm.content_id
				AND c.status = 'publish' AND `type` = 'page'
				AND cm.meta_key = '_in_frontpage'
				AND cm.meta_value = '1'
				ORDER BY c.creation_date DESC
				LIMIT $limit";
	$rows = $dbh->FetchResults($query);
	$pages = array();
	foreach($rows as $row)
	{
		$page = new LT_Article();
		$page->SetDbData($row);
		$pages[] = $page;
	}
	return $pages;
}
function lt_content_register_content_types()
{
	global $content_types;
	
	$content_types = array(
			'page'	=> array(
					'labels'	=> array(
							'menu_label'	=> __('Pages', 'content'),
							'new_label'		=> __('New Page', 'content'),
							'edit_label'	=> __('Edit Page', 'content'),
							'listing_label'	=> __('Pages', 'content')
					),
					'features'	=> array(
							'featured_image'		=> true,
							'use_dates'				=> true,
							'calculated_dates'		=> true,
							'text_color'			=> false,
							'background_text_color'	=> false,
							'view_button'			=> true,
							'btn_add_media'			=> true
					)
			),
			'post'	=> array(
					'labels'	=> array(
							'menu_label'	=> __('Posts', 'content'),
							'new_label'		=> __('New Post', 'content'),
							'edit_label'	=> __('Edit Post', 'content'),
							'listing_label'	=> __('Posts', 'content')
					),
					'features'	=> array(
							'featured_image'		=> true,
							'use_dates'				=> true,
							'calculated_dates'		=> true,
							'text_color'			=> false,
							'background_text_color'	=> false,
							'view_button'			=> true,
							'btn_add_media'			=> true
					)
			),
	);
	$content_types = SB_Module::do_action('content_types', $content_types);
	
	return $content_types;
}
/**
 * Get tags for an object
 * 
 * Example:
 * $type = content|section|category|post
 * 
 * @param string $type The object type
 * @param int $id The object id
 * @return  array
 */
function lt_content_get_object_tags($type = null, $id = null)
{
	$conds = array();
	if( $type )
		$conds['object_type'] = $type;
	if( (int)$id )
		$conds['object_id'] = (int)$id;
	
	$tags = SB_DbTable::GetTable('tags', 1)->GetRows(-1, 0, $conds);
	
	return $tags;
}
/**
 * Insert object tags
 * 
 * @param mixed $tags The object tags string or array
 * @param mixed $object_type The object type
 * @param mixed $object_id The object id
 * @return  
 */
function lt_content_set_object_tags($tags, $object_type, $object_id)
{
	if( !(int)$object_id )
		return false;
	$query = "REPLACE INTO tags";
	if( !is_array($tags) )
	{
		$tags = array_map('trim', explode(',', $tags));
	}
	$dbh = SB_Factory::getDbh();
	$_tags = array();
	foreach($tags as $tag)
	{
		$_tags[] = array(
			'object_type' 	=> $object_type,
			'object_id' 	=> (int)$object_id,
			'str'			=> $tag,
			'creation_date'	=> date('Y-m-d H:i:s')
		);
	}
	$dbh->InsertBulk('tags', $_tags);
	return true;
}