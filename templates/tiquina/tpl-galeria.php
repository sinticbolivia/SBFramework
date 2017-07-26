<?php 
/**
* Template: Galeria
*/
sb_add_style('lightgallery', BASEURL . '/js/lightGallery/css/lightgallery.min.css', 'lightgallery-js');
sb_add_script(BASEURL . '/js/lightGallery/js/lightgallery.min.js', 'lightgallery-js', 0, true);
lt_get_header(); ?>
<div id="content-wrap" class="container-fluid">
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12">
			<div id="content" class="gallery-content">
				<h1 id="page-title"><?php print $article->TheTitle(); ?></h1>
				<?php print $article->TheContent(); ?>
			</div><!-- end id="content" -->
		</div>
	</div>
</div><!-- end id="content-wrap" -->
<script>
jQuery(function()
{
	if( jQuery().lightGallery )
	{
		jQuery('#galeria-container').lightGallery({
			selector: '.description'
		});
	}
});
</script>
<?php lt_get_footer(); ?>