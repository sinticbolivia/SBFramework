<?php lt_get_header(); ?>
<div id="content">
	<div class="container">
		<div class="row"><h1 id="page-title"><?php print $article->TheTitle(); ?></h1></div>
		<div class="row">
			<div class="content-text"><?php print $article->TheContent(); ?></div>
		</div>
	</div>
</div>
<?php lt_get_footer(); ?>