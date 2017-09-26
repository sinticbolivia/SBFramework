<?php
function __lt_show_menu_items($items, $class)
{
	$c_slug = SB_Request::getString('slug');
	foreach($items as $mi => $item):
	$link = @$item->link;
	$css_id = isset($item->css_id) ? $item->css_id : 'menu_item_' . $mi;
	$css_class = isset($item->css_class) ? $item->css_class : 'menu_item_' . $mi;
	if( @$item->type == 'page' )
	{
		$link = SB_Route::_('index.php?mod=content&view=article&id='.$item->id . '&slug='.$item->slug);
	}
	if( @$item->type == 'section' )
	{
		$link = SB_Route::_('index.php?mod=content&view=section&id='.$item->id . '&slug='.$item->slug);
	}
	if( $c_slug == $item->slug )
		$css_class = ' active';
	?>
		<li id="<?php print $css_id; ?>" class="<?php print $css_class; ?>">
			<a href="<?php print $link; ?>">
				<?php _e($item->title); ?>
			</a>
			<?php if( isset($item->items) && is_array($item->items)  ): ?>
			<ul class="<?php print $class; ?>"><?php __lt_show_menu_items($item->items, $class); ?></ul>
			<?php endif;?>
		</li>
	<?php endforeach;
}
function lt_show_content_menu($key, $args = array())
{
	$def_args = array(
			'id' 				=> 'navigation-menu',
			'class' 			=> '',
			'menu_item_class' 	=> '',
			'sub_menu_class'	=> 'submenu',
			'print'				=> 1
	);
	$args = array_merge($def_args, $args);
	$menus = (array)sb_get_parameter('menus', array());
	
	if( !isset($menus[$key]) )
		return false;
	//print '<!-- ';print_r($menus);print '-->';
	$c_slug = SB_Request::getString('slug');
	//var_dump($c_slug);
	$menu = $menus[$key];
	ob_start();?>
	<ul id="<?php print $args['id']; ?>" class="<?php print $args['class']; ?>">
		<?php $mi = 0; foreach($menu->items as $item): ?>
		<?php
		$css_id = isset($item->css_id) ? $item->css_id : 'menu_item_' . $mi;
		$css_class = isset($item->css_class) ? $item->css_class : 'menu_item_' . $mi;
		$link = @$item->link;
		if( @$item->type == 'page' )
		{
			$link = SB_Route::_('index.php?mod=content&view=article&id='.$item->id . '&slug=' . $item->slug);
		}
		elseif( @$item->type == 'section' )
		{
			$link = SB_Route::_('index.php?mod=content&view=section&id='.$item->id . '&slug='.$item->slug);
		} 
		else
		{
			$link = SB_Route::_($item->link);
		}
		if( !empty($c_slug) && $c_slug == $item->slug )
			$css_class .= ' active';
		if( lt_is_frontpage() && !strstr($css_class, 'active') && $mi == 0 )
			$css_class .= ' active';
		?>
		<li id="<?php print $css_id; ?>" class="<?php print $css_class; ?>">
			<?php SB_Module::do_action('menu_before_show_item_'.$item->type, $item); ?>
			<a href="<?php print $link; ?>" <?php print ( isset($item->items) && is_array($item->items) ) ? 'data-toggle="dropdown"' : ''; ?>>
				<span data-hover="<?php _e($item->title); ?>"><?php _e($item->title); ?></span>
				<?php if( isset($item->items) && is_array($item->items) ): ?>
				<b class="caret"></b>
				<?php endif; ?>
			</a>
			<?php if( isset($item->items) && is_array($item->items)  ): ?>
			<ul class="<?php print $args['sub_menu_class']; ?>">
				<?php __lt_show_menu_items($item->items, $args['sub_menu_class']); ?>
			</ul>
			<?php endif;?>
			<?php SB_Module::do_action('menu_after_show_item_'.$item->type, $item); ?>
		</li>
		<?php $mi++; endforeach; ?>
	</ul>
	<?php 
	$menu = ob_get_clean();
	if( $args['print'] == 1 )
	{
		print $menu;
		return true;
	}
	return $menu;
}