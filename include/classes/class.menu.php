<?php
class SB_Menu
{
	protected static $_menu = array();
	protected static $_menu_positions = array();
	protected static $html_menu = '';
	protected	static $menus_levels = array();
	
	public static function addMenuPage($title, $link, $id, $capability = '', $order = 0)
	{
		$new_menu = array('id' => $id, 
							'title' => $title, 
							'link' => $link, 
							'childs' => array(), 
							'capability' => $capability, 
							'order' => $order
		);
		
		if( isset(self::$_menu[$order]) )
		{
			$index = ($order + 0.1);
			while( true )
			{
				if( !isset(self::$_menu[$index]) )
				{
					break;
				}
				$index = $index + 0.1;
			}
			/*
			$head = array_slice(self::$_menu, 0, $order + 1);
			$tail = array_slice(self::$_menu, $order + 1, count(self::$_menu));
			//$tail = array_merge($tail, array($new_menu));
			//$tail = array_merge(array($new_menu), $tail);
			self::$_menu = array_merge($head, $new_menu, $tail);
			self::$menus_levels[$id] = "[".($order + 1)."]";
			*/
			self::$_menu[$index] = $new_menu;
			self::$menus_levels[$id] = "[".($order + 1)."]";
		}
		elseif( isset(self::$_menu[$order]) ) 
		{
			self::$_menu[(string)($order + 1)] = $new_menu;
			self::$menus_levels[$id] = "[".($order + 1)."]";
		}
		else 
		{
			self::$_menu[$order] = $new_menu;
			self::$menus_levels[$id] = "[".$order."]";
			//print_r(self::$_menu);var_dump($new_menu['id']);
		}
		//sort(self::$_menu);
		//$_menu_positions[$id] = "$order:$id";
	}
	public static function &addMenuChild($parent_menu_id, $title, $link, $id, $capability = '', $order = 0, $args = array())
	{
		$the_menu = null;
		
		if( isset(self::$menus_levels[$parent_menu_id]) )
		{
			$level = self::$menus_levels[$parent_menu_id];
			/*
			eval('$the_menu =& self::$_menu'.$level.';');
			print_r($the_menu);
			var_dump($level);
			die($parent_menu_id);
			*/
		}
		foreach(self::$_menu as $_order => $m)
		{
			if( !isset($m['id']) ) continue;
			if( $m['id'] == $parent_menu_id )
			{
				$the_menu = &self::$_menu[$_order];
				break;
			}
			if( isset($m['childs']) )
			{
				$the_menu = &self::FindMenu(self::$_menu[$_order]['childs'], $parent_menu_id);
				if( $the_menu )
				{
					//$levels = explode(':', $the_menu);
					//$the_menu = &self::$_menu{'[]'};
					break;
				}
			}
		}
		if( !$the_menu )
			return $the_menu;
		/*
		if( $parent_menu_id == 'menu-statistics' )
		{
			var_dump($id);
			print_r($the_menu);
		}
		*/
		$new_menu = array(
				'id'			=> $id,
				'title' 		=> $title,
				'link' 			=> $link,
				'childs' 		=> array(),
				'capability' 	=> $capability,
				'order'			=> $order,
				'data'			=> $args
		);
		if( isset($the_menu['childs'][$order]) && isset($the_menu['childs'][$order + 1]) )
		{
			$head = array_slice($the_menu['childs'], 0, $order + 1);
			$tail = array_slice($the_menu['childs'], $order + 1, count($the_menu['childs']));
			$tail = array_merge($tail, array($new_menu));
			$the_menu['childs'] = array_merge($head, $tail);
		}
		elseif( isset($the_menu['childs'][$order]) )
		{
			$the_menu['childs'][$order + 1] = $new_menu;
		}
		else
		{
			$the_menu['childs'][$order] = $new_menu;
		}
		/*
		if( $parent_menu_id == 'menu-statistics' )
		{
			print_r($the_menu);
			print_r(self::$_menu);
		}
		*/
		//sort($the_menu['childs']);
		return $the_menu;
	}
	public static function &FindMenu(&$menu, $menu_id)
	{
		$result_menu = '';
		if( empty($menu) )
			return $result_menu;
		
		$max_order = max(array_keys($menu));
		//foreach($menu as $order => $the_menu)
		for($i = 0; $i <= $max_order; $i++)
		{
			if( !isset($menu[$i]) ) continue;
			
			if( $menu_id == $menu[$i]['id'] )
			{
				$result_menu =& $menu[$i];
				break;
			}
			elseif( isset($menu[$i]['childs']) && count($menu[$i]['childs']) ) 
			{
				$result_menu =& self::FindMenu($menu[$i]['childs'], $menu_id);
				if( $result_menu )
					break;
			}
		}
		
		return $result_menu;
	}
	
	public static function buildMainMenu($ops = array())
	{
	
		$def_ops = array('class' => 'sb-menu', 
							'class_submenu' => 'sb-submenu');
		$ops = array_merge($def_ops, $ops);
		$user = sb_get_current_user();
		$html_menu = sprintf("<ul %s class=\"%s\">", 
								isset($ops['id']) ? 'id="'.$ops['id'].'"' : '', 
								$ops['class']);
		$max_order = max(array_keys(self::$_menu));
		//foreach(self::$_menu as $order => $menu)
		for($i = 0; $i <= $max_order; $i += 0.1)
		{
			$index = (string)$i;
			if( !isset(self::$_menu[$index]) || empty(self::$_menu[$index]) ) continue;
			$menu = self::$_menu[$index];
			if( !$user->can($menu['capability']) ) continue;
			$attr = '';
			$submenu_id = "submenu_$i";
			$ops['submenu_id'] = $submenu_id;
			$childs = self::_buildChilds($menu, $ops);
			if( $childs )
			{
				$attr .= "data-toggle=\"collapse\" ";
				$menu['link'] = '#'.$submenu_id;
			}
			if( isset($menu['data']) ) foreach($menu['data'] as $name => $val)
			{
				$attr .= sprintf("%s=\"%s\" ", $name, $val);
			}
			/*
			if( isset($menu['data']['target']) )
			{
				$target = 'target="'.$menu['data']['target'].'"';
				unset();
			}
			*/
			
			$html_menu .= sprintf("<li id=\"%s\" data-menu_order=\"%d\" class=\"sb-menu-item\"><a href=\"%s\" %s>%s</a>%s</li>", 
									$menu['id'], 
									$menu['order'], 
									$menu['link'], 
									$attr, 
									$menu['title'], 
									$childs);
		}
		$html_menu .= '</ul>';
		self::$html_menu = $html_menu;
	}
	protected static function _buildChilds($menu, $ops = array())
	{
		if( !count($menu['childs']) )
			return null;
		$sub_menu = '<ul '.(isset($ops['submenu_id']) ? 'id="'.$ops['submenu_id'].'"' : '').'class="'.$ops['class_submenu'].'">';
		$user = sb_get_current_user();
		$curren_menu_link = '';
		foreach($menu['childs'] as $order => $submenu)
		{
			if( !$user->can($submenu['capability']) ) continue;
			$attr = '';
			if( isset($submenu['data']) ) foreach($submenu['data'] as $name => $val)
			{
				$attr .= sprintf("%s=\"%s\" ", $name, $val);
			}
			
			$sub_menu .= sprintf("<li class=\"sb-menu-item %s\" data-order=\"%d\"><a href=\"%s\" %s>%s</a>%s</li>", 
									($curren_menu_link == $submenu['link']) ? 'current' : '', 
									$submenu['order'], 
									$submenu['link'], 
									$attr,
									$submenu['title'], self::_buildChilds($submenu, $ops));
		}
		$sub_menu .= '</ul>';
		
		return $sub_menu;
	}
	protected static function _buildApplicationMenu()
	{
		
	}
	public static function &FindMenuItem($menu_id)
	{
		return self::FindMenu(self::$_menu, $menu_id);
	}
	/**
	 * 
	 * @param string $type web|webapp
	 * @param unknown $ops
	 */
	public static function rederMenu($type, $ops = array())
	{
		
		SB_Module::do_action('before_render_menu', self::$_menu);
		self::buildMainMenu($ops);
		print self::$html_menu;
	}
}