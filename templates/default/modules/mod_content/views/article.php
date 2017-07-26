<?php
lt_include_template('tpl-user-menu');
?>
<div id="article-container">
	<div class="lt-article">
		<?php if($article->_banner): ?>
		<div class="banner text-center"><img src="<?php print MOD_CONTENT_BANNERS_URL . '/' . $article->_banner; ?>" alt="" /></div>
		<?php endif;?>
		<?php /*<h1 class="title"><?php print $article->title; ?></h1>*/ ?>
		<div class="content"><?php print stripslashes($article->content); ?></div>
	</div>
	<p class="text-center">
		<a href="<?php print $_SERVER['HTTP_REFERER']; ?>" class="btn btn-default"><?php print SB_Text::_('Volver'); ?></a>
	</p>
</div>