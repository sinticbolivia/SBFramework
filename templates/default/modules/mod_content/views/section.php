<?php
sb_include_module_helper('content');
$section_id = SB_Request::getInt('id');
$section = new LT_Section($section_id);
$back_url = '';
if( $section->parent_id )
{
	$back_url = SB_Route::_('index.php?mod=content&view=section&id='.$section->parent_id);
}
else 
{
	$back_url = SB_Route::_('index.php?mod=users');
}
$user = sb_get_current_user();
lt_include_template('tpl-user-menu');
?>

<div id="welcome-message" class="text-center">
	<?php
	$msg = defined('USER_WELCOME_MSG') ? USER_WELCOME_MSG : null;
	$msg =  !empty($msg) ? $msg : sprintf(SB_Text::_('Hola %s <br/>Haz CLICK en la seccion o contenido que deseas consultar.'), $user->first_name);  
	$msg = stripslashes($msg);
	$msg1 = sb_get_user_meta($user->user_id, '_msg_shortcode');
	print str_replace(array('[user_firstname]', '[user_msg_home]'), array($user->first_name, $msg1), $msg);
	?>
</div>
<ul class="section-list">
	<?php foreach($section->GetArticles() as $a): if( !$a->IsVisible() ) continue; ?>
	<?php
	$styles = $class = $text = '';
	if( !sb_get_content_meta($a->content_id, '_user_button_instead') || !sb_get_content_meta($a->content_id, '_button_image') )
	{
		$class = 'btn btn-info';
		if( $a->_btn_bg_color )
		{
			$class = 'btn';
			$styles .= 'background-color:'.$a->_btn_bg_color.';';
			$fcolor = str_replace('#', '', $a->_btn_bg_color);
			$scolor = dechex(hexdec($fcolor) + hexdec('4000'));
			$scolor = sb_fill_zeros($scolor, 6);
			//$styles .= 'background-image: linear-gradient('.$a->_btn_bg_color.', #'.$scolor.');';
		}
		if( $a->_btn_fg_color )
		{
			$class = 'btn';
			$styles .= 'color:'.$a->_btn_fg_color.';';
		}
		$styles = "style=\"$styles\"";
		$text = $a->title;
	}
	else
	{
		$src = MOD_CONTENT_BUTTONS_URL . '/' . sb_get_content_meta($a->content_id, '_button_image');
		$text = sprintf("<img src=\"%s\" alt=\"%s\" title=\"%s\" />", $src, $a->title, $a->title);
	}
	
	$url = SB_Route::_('index.php?mod=content&view=article&id='.$a->content_id); 
	?>
	<li class="text-center lt-article">
		<a href="<?php print $url; ?>" class="<?php print $class; ?>" <?php print $styles; ?>>
			<?php print $text; ?>
		</a>
	</li>
	<?php endforeach; ?>
	<?php foreach(LT_HelperContent::GetSections($section_id) as $ss): if( !$ss->IsVisible() ) continue; ?>
	<?php
	$styles = $class = $text = '';
	if( !sb_get_section_meta($ss->section_id, '_use_button_instead') || !sb_get_section_meta($ss->section_id, '_button_image') )
	{
		$class = 'btn btn-primary';
		if( $ss->_btn_bg_color )
		{
			$class = 'btn';
			$styles .= 'background-color:'.$ss->_btn_bg_color.';';
			$fcolor = str_replace('#', '', $ss->_btn_bg_color);
			$scolor = dechex(hexdec($fcolor) + 16384);
			$scolor = sb_fill_zeros($scolor, 6);
			//$styles .= 'background-image: linear-gradient('.$ss->_btn_bg_color.', #'.$scolor.');';
		}
		if( $ss->_btn_fg_color )
		{
			$class = 'btn';
			$styles .= 'color:'.$ss->_btn_fg_color.';';
		}
		$styles = "style=\"$styles\"";
		$text = $ss->name;
	}
	else
	{
		$src 	= MOD_CONTENT_BUTTONS_URL . '/' . sb_get_section_meta($ss->section_id, '_button_image');
		$text 	= sprintf("<img src=\"%s\" alt=\"%s\" title=\"%s\" />", $src, $ss->name, $ss->name);
		$class 	= '';
	}
	$url = $ss->_external_url ? $ss->_external_url : SB_Route::_('index.php?mod=content&view=section&id='.$ss->section_id);
	?>
	<li class="text-center">
		<a href="<?php print $url; ?>" class="<?php print $class; ?>" <?php print $ss->_external_url ? 'target="_blank"' : ''; ?> 
			<?php print $styles; ?>>
			<?php print $text; ?>
		</a>
	</li>
		<?php /*foreach($ss->GetArticles() as $a): if( !$a->IsVisible() ) continue; ?>
		<li class="text-center lt-article">
			<a href="<?php print SB_Route::_('index.php?mod=content&view=article&id='.$a->content_id); ?>" class="btn btn-info">
				<?php print $a->title; ?>
			</a>
		</li>
		<?php endforeach;*/ ?>
	<?php endforeach; ?>
</ul>
<p class="text-center">
	<a href="<?php print $back_url; ?>" class="btn btn-default"><?php print SB_Text::_('Volver'); ?></a>
</p>
