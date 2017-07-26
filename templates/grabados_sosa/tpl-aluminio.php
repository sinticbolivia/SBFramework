<?php 
/**
 * Template: Aluminio
 */
$prods = array(
	(object)array('title' => 'Titulo','desc' => 'Descripcion', 'image'	=> TEMPLATE_URL . '/images/aluminio/3.png'),
	(object)array('title' => 'Titulo','desc' => 'Descripcion','image'	=> TEMPLATE_URL . '/images/aluminio/4.png'),
	(object)array('title' => 'Titulo','desc' => 'Descripcion','image'	=> TEMPLATE_URL . '/images/aluminio/5.png'),
	(object)array('title' => 'Titulo','desc' => 'Descripcion','image'	=> TEMPLATE_URL . '/images/aluminio/6.png'),
	(object)array('title' => 'Titulo','desc' => 'Descripcion','image'	=> TEMPLATE_URL . '/images/aluminio/7.png'),
	(object)array('title' => 'Titulo','desc' => 'Descripcion','image'	=> TEMPLATE_URL . '/images/aluminio/8.png'),
	(object)array('title' => 'Titulo','desc' => 'Descripcion','image'	=> TEMPLATE_URL . '/images/aluminio/9.png'),
	(object)array('title' => 'Titulo','desc' => 'Descripcion','image'	=> TEMPLATE_URL . '/images/aluminio/10.png'),
	(object)array('title' => 'Titulo','desc' => 'Descripcion','image'	=> TEMPLATE_URL . '/images/aluminio/11.png'),
	(object)array('title' => 'Titulo','desc' => 'Descripcion','image'	=> TEMPLATE_URL . '/images/aluminio/12.png'),
	//(object)array('title' => 'Titulo','desc' => 'Descripcion','image'	=> TEMPLATE_URL . '/images/aluminio/13.png'),
	(object)array('title' => 'Titulo','desc' => 'Descripcion','image'	=> TEMPLATE_URL . '/images/aluminio/14.png'),
);

sb_add_style('lightgallery', BASEURL . '/js/lightGallery/css/lightgallery.min.css', 'lightgallery-js');
sb_add_script(BASEURL . '/js/lightGallery/js/lightgallery.min.js', 'lightgallery-js', 0, true);
lt_get_header('', array('show_bolivia' => false)); ?>
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
							<a href="<?php print $p->image; ?>" class="image">
								<img src="<?php print $p->image; ?>" alt="" /></a>
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