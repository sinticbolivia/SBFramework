<?php 
/**
 * Template: Maquinas
 */
$prods = array(
	(object)array('title' => 'Titulo','desc' => 'Descripcion', 'image'	=> TEMPLATE_URL . '/images/maquinas/1-1000x1429.png'),
	(object)array('title' => 'Titulo','desc' => 'Descripcion','image'	=> TEMPLATE_URL . '/images/maquinas/2-1000x2119.png'),
	(object)array('title' => 'Titulo','desc' => 'Descripcion','image'	=> TEMPLATE_URL . '/images/maquinas/P1030734.png'),
	(object)array('title' => 'Titulo','desc' => 'Descripcion','image'	=> TEMPLATE_URL . '/images/maquinas/P1030737.png'),
	(object)array('title' => 'Titulo','desc' => 'Descripcion','image'	=> TEMPLATE_URL . '/images/maquinas/P1030741.png'),
);
sb_add_style('lightgallery', BASEURL . '/js/lightGallery/css/lightgallery.min.css', 'lightgallery-js');
sb_add_script(BASEURL . '/js/lightGallery/js/lightgallery.min.js', 'lightgallery-js', 0, true);
lt_get_header(); ?>
<div class="container">
	<div class="row">
		<div id="content" class="col-md-9">
			<h1 id="page-title"><?php print $article->TheTitle(); ?></h1>
			<div class="content-text"><?php print $article->TheContent(); ?></div>
			<div class="content-text">
				<div class="products-list row">
					<?php foreach($prods as $p): ?>
					<div class="col-xs-12 col-sm-6 col-md-3">
						<div class="product">
							<div class="image" data-src="<?php print $p->image; ?>">
								<img src="<?php print $p->image; ?>" alt="" />
							</div>
							<div class="info">
								<div class="title"><?php print $p->title; ?></div>
								<div class="desc"><?php print $p->desc; ?></div>
							</div>
						</div>
					</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
		<?php lt_get_sidebar('productos', array('products', $prods)); ?>
	</div>
</div>
<?php lt_get_footer(); ?>