<?php

?>
<?php if( !isset($show_title) || $show_title ): ?>
<h1><?php _e('Products', 'emono'); ?></h1>
<?php endif; ?>
<div id="products-list" class="container-fluid">
	<div class="row">
		<?php foreach($products as $p): $link = $p->link;?>
		<div class="col-xs-12 col-sm-3 col-md-3">
			<div class="product">
				<figure class="image">
					<a title="<?php print $p->product_name; ?>" class="product_img_link" href="#">
						<img alt="<?php print $p->product_name; ?>" src="<?php print $p->getFeaturedImage()->GetUrl(); ?>">
					</a>
				</figure>
				<h3 class="title">
					<a title="<?php print $p->product_name; ?>" href="<?php print $link; ?>" 
						class="product_link"><?php print $p->product_name; ?>
					</a>
				</h3>
				<div class="excerpt">
					<?php print $p->excerpt; ?>
				</div>
				<div class="container-fluid">
					<div class="row">
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="product_flags">
								<span class="availability <?php print $p->product_quantity > 0 ? 'in_stock' : 'out_of_stock' ?>">
									<?php _e('Available', 'emono'); ?>
								</span> 
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="text-right">
								<span class="price"><?php print $p->GetPrice(); ?></span> 
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<a title="<?php _e('Ver Detalle', 'emono'); ?>" href="<?php print $link; ?>" class="btn btn-view-product">
								<?php _e('Ver Detalle', 'emono'); ?>
							</a>
						</div>
					</div>
				</div>
			</div><!-- end class="product" -->
		</div>
		<?php endforeach; ?>
	</div>
</div>