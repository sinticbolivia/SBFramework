<?php 
lt_get_header(); 
?>
<div id="content">
	<?php SB_MessagesStack::ShowMessages(); ?>
	<h1 id="page-title"><?php print $article->TheTitle(); ?></h1>
	<div id="the-content">
		<?php print $article->TheContent(); ?>
	</div><!-- end id="the-content" -->
</div>
<?php lt_get_footer(); ?>