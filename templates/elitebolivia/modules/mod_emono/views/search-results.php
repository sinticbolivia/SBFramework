<?php

?>
<h1><?php print $title; ?></h1>
<div id="search-product-list" class="bordercolor list search-results">
	<?php foreach($products as $p): $link = $p->link; ?>
	<div class="product ajax_block_product bordercolor">
		<div class="row">
			<div class="col-xs-12 col-sm-4 col-md-2">
				<a title="<?php print $p->product_name; ?>" class="product_img_link" href="<?php print $link ?>">
					<img alt="<?php print $p->product_name; ?>" src="<?php print $p->getFeaturedImage('150x150')->GetUrl(); ?>">
				</a>
			</div>
			<div class="col-xs-12 col-sm-8 col-md-10">
				<div class="center_block">
					<?php /*
					<div class="product_flags">
						<span class="availability bordercolor"><?php _e('Available', 'emono'); ?></span> 
					</div>
					*/?>
					<h3 class="product-title">
						<a title="<?php print $p->product_name; ?>" href="<?php print $link; ?>" 
							class="product_link"><?php print $p->product_name; ?>
						</a>
					</h3>
					<p class="product_desc">
						<?php print $p->excerpt; ?>
					</p>
					<div class="product-price">
						Precio: <span class="price">Bs. <?php print $p->price; ?></span> 
					</div>
					<div class="product-buttons">
						<a title="<?php _e('Add to cart', 'emono'); ?>" href="<?php print SB_Route::_('index.php?mod=emono&task=add_to_cart&id='.$p->product_id); ?>" 
							class="btn btn-add-to-cart btn-red">
							<?php _e('Add to cart', 'emono'); ?>
						</a>
						<a title="<?php _e('View', 'emono'); ?>" href="<?php print $p->link; ?>" 
							class="btn btn-view-product btn-red">
							<?php _e('View', 'emono'); ?>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php endforeach; ?>
</div><!-- end id="product_list" -->
<?php lt_pagination(SB_Route::_('index.php?'.$_SERVER['QUERY_STRING']), $total_pages, $current_page); ?>