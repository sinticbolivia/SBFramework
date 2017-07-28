<?php 
/**
 * Template: Troqueles
 */
sb_add_style('lightgallery', BASEURL . '/js/lightGallery/css/lightgallery.min.css', 'lightgallery-js');
sb_add_script(BASEURL . '/js/lightGallery/js/lightgallery.min.js', 'lightgallery-js', 0, true);
$prods = array(
	(object)array('title' => 'Titulo','desc' => 'Descripcion', 'image'	=> TEMPLATE_URL . '/images/troqueles/1.png'),
	(object)array('title' => 'Titulo','desc' => 'Descripcion', 'image'	=> TEMPLATE_URL . '/images/troqueles/2-1000x561.png'),
	(object)array('title' => 'Titulo','desc' => 'Descripcion', 'image'	=> TEMPLATE_URL . '/images/troqueles/3-1200x673.png'),
	(object)array('title' => 'Titulo','desc' => 'Descripcion','image'	=> TEMPLATE_URL . '/images/troqueles/4-1200x126.png'),
	(object)array('title' => 'Titulo','desc' => 'Descripcion','image'	=> TEMPLATE_URL . '/images/troqueles/5-100x1931.png'),
	(object)array('title' => 'Titulo','desc' => 'Descripcion','image'	=> TEMPLATE_URL . '/images/troqueles/6-1000x1055.png'),
	(object)array('title' => 'Titulo','desc' => 'Descripcion','image'	=> TEMPLATE_URL . '/images/troqueles/7.png')
);
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
							<div class="image" data-src="<?php print $p->image; ?>"><img src="<?php print $p->image; ?>" alt="" /></div>
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