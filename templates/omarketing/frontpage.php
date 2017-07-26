<?php
lt_get_header();
?>
<div id="frontpage-banner" class="row">
	<div class="banner">
		<img src="<?php print TEMPLATE_URL; ?>/images/inicio.jpg" alt="<?php print SITE_TITLE; ?>" />
		<div class="text">
			<p>Creando profesionales capacitados que el mundo de hoy necesita.</p>
			<span class="light">Estudian con nosotros</span>
		</div>
	</div>
</div>
<section id="latest-entries">
	<?php 
	sb_show_widget('SB_MBWidgetLatestContent', 
					array(
						'title' => __('&Uacute;ltimas publicaciones del Blog', 'om'),
						'type'	=> 'post'
					)
	);
	?>
</section><!-- end id="latest-entries" -->
<section id="latest-courses">
</section><!-- end id="latest-entries" -->
<?php
lt_get_footer(); 
?>