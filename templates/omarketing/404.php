<?php
lt_get_header();
?>
<div id="content-wrap" class="row">
	<div id="page-banner" class="">
		<img src="<?php print TEMPLATE_URL; ?>/images/banner01.jpg" alt="" />
	</div>
	<div  class="col-md-12">
		<div class="row">
			<div id="content" class="col-md-9">
				<h1 id="content-title"><?php _e('Contenido no encontrado', 'om'); ?></h1>
				<section id="the-content">
					El contenido que estas buscando no existe.
				</section><!-- end id="the-content" -->
			</div><!-- end id="content" -->
			<?php lt_get_sidebar(); ?>
		</div>
	</div>
</div><!-- end id="content-wrap" -->
<?php
lt_get_footer(); 
?>