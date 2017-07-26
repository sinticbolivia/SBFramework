<?php
?>
<div id="article-container">
	<div class="lt-article">
		<h1 class="title"><?php print $article->title; ?></h1>
		<div class="content"><?php print stripslashes($article->TheContent()); ?></div>
	</div>
</div><!-- end id="article-container" -->