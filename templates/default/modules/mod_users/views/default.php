<?php
sb_include_module_helper('content');
$sections = LT_HelperContent::GetSections(0);
//$articles = array();
$dbh = SB_Factory::getDbh();
lt_include_template('tpl-user-menu');
?>
<div id="account-content">
	<div id="welcome-message" class="text-center">
		<?php
		$msg = defined('USER_WELCOME_MSG') ? USER_WELCOME_MSG : null;
		$msg =  !empty($msg) ? $msg : sprintf(SB_Text::_('Hola %s <br/>Haz CLICK en la seccion o contenido que deseas consultar.'), $user->first_name);
		$msg = stripslashes($msg);
		$msg1 = sb_get_user_meta($user->user_id, '_msg_shortcode');
		$fn = $user->first_name;
		print str_replace(array('[user_firstname]', '[user_msg_home]'), array($fn, $msg1), $msg); 
		?>
	</div>
	<ul class="section-list">
		<?php foreach($sections as $s): if( !$s->IsVisible() ) continue;?>
		<li class="text-center lt-section">
			<?php
			$styles = $class = $text = $js = '';
			if( !sb_get_section_meta($s->section_id, '_use_button_instead') || !sb_get_section_meta($s->section_id, '_button_image') )
			{
				$class = 'btn btn-primary';
				if( $s->_btn_bg_color )
				{
					$aux_color = '#222222';
					$class = 'btn';
					$styles .= 'background-color:'.$s->_btn_bg_color.';';
					$fcolor = str_replace('#', '', $s->_btn_bg_color);
					$scolor = dechex(hexdec($fcolor) + 16384);
					$scolor = sb_fill_zeros($scolor, 6);
					//$styles .= "background-image: linear-gradient($s->_btn_bg_color, $aux_color);";
					//$js .= "onmouseover=\"this.style.backgroundImage = 'linear-gradient($aux_color, $s->_btn_bg_color)';\"";
					//$js .= "onmouseout=\"this.style.backgroundImage = 'linear-gradient($s->_btn_bg_color, $aux_color)';\"";
				}
				if( $s->_btn_fg_color )
				{
					$class = 'btn';
					$styles .= 'color:'.$s->_btn_fg_color.';';
				}
				$styles = "style=\"$styles\"";
				$text = $s->name;
			}
			else
			{
				$src = MOD_CONTENT_BUTTONS_URL . '/' . sb_get_section_meta($s->section_id, '_button_image');
				$text = sprintf("<img src=\"%s\" alt=\"%s\" title=\"%s\" />", $src, $s->name, $s->name);
			}
			$url = $s->_external_url ? $s->_external_url : SB_Route::_('index.php?mod=content&view=section&id='.$s->section_id);
			?>
			<a href="<?php print $url; ?>" class="<?php print $class; ?>" <?php print $s->_external_url ? 'target="_blank"' : ''; ?> 
				<?php print $styles; ?> <?php print $js; ?>>
				<?php print $text; ?>
			</a>
		</li>
		<?php endforeach; ?>
		<?php
		$query = "SELECT a.* FROM content a,content_meta am ".
					"WHERE a.content_id = am.content_id ".
					"AND am.meta_key = '_in_frontpage' ".
					"AND am.meta_value = '1' ".
					"ORDER BY a.show_order ASC";
		$dbh->Query($query);
		$articles = $dbh->FetchResults();
		?>
		<?php foreach($articles as $row): ?>
		<?php
		$article = new LT_Article();
		$article->SetDbData($row);
		if( !$article->IsVisible() ) continue;
		?>
		<li class="text-center lt-article">
			<?php
			$styles = 'border:0;';
			$class = '';
			$text = '';
			$js	= '';
			$img = (int)sb_get_content_meta($article->content_id, '_user_button_instead');
			$aimage = sb_get_content_meta($article->content_id, '_button_image');
			if( !$img || !$aimage )
			{
				$class = 'btn btn-primary';
				if( $article->_btn_bg_color )
				{
					$class = 'btn';
					$aux_color = '#222222';
					$styles .= 'background-color:'.$article->_btn_bg_color.';';
					$fcolor = str_replace('#', '', $article->_btn_bg_color);
					$scolor = dechex(hexdec($fcolor) + hexdec('4000'));
					$scolor = sb_fill_zeros($scolor, 6);
					//$styles .= "background-image: linear-gradient($article->_btn_bg_color, $aux_color);";
					//$styles .= ':hover{background-image: linear-gradient(#555555,'.$article->_btn_bg_color.');}';
					//$js .= "onmouseover=\"this.style.backgroundImage = 'linear-gradient($aux_color, $article->_btn_bg_color)';\"";
					//$js .= "onmouseout=\"this.style.backgroundImage = 'linear-gradient($article->_btn_bg_color, $aux_color)';\"";
				}
				if( $article->_btn_fg_color )
				{
					$class = 'btn';
					$styles .= 'color:'.$article->_btn_fg_color.';';
				}
				$styles = "style=\"$styles\"";
				
				$text 	= $article->title;
			}
			else 
			{
				$src = MOD_CONTENT_BUTTONS_URL . '/' . $aimage;
				$text = sprintf("<img src=\"%s\" alt=\"%s\" title=\"%s\" />", $src, $article->title, $article->title);
			}
			?>
			<a href="<?php print SB_Route::_('index.php?mod=content&view=article&id='.$article->content_id); ?>" class="<?php print $class?>" 
				<?php print $styles; ?> <?php print $js?>>
				<?php print $text ?>
			</a>
		</li>
		<?php endforeach; ?>
	</ul>
</div>