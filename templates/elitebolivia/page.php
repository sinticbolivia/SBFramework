<?php lt_get_header(); ?>
<div id="content" class="col-xs-12 col-sm-9 col-md-9">
	<?php SB_MessagesStack::ShowMessages(); ?>
	<div class="container-fluid">
		<div class="row"><h1 id="page-title"><?php print $article->TheTitle(); ?></h1></div>
		<div class="row">
			<div class="content-text"><?php print $article->TheContent(); ?></div>
		</div>
	</div>
</div><!-- end id="content" -->
<?php lt_get_sidebar(); ?>
<?php lt_get_footer(); ?>