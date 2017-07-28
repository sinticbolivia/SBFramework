<?php
?>
<?php emono_breadcrumb($product); ?>
<div id="product-container">
	<div id="product-images" class="col-md-6">
		<div id="featured-image">
			<a href="<?php print $product->getFeaturedImage('full')->GetUrl(); ?>" class="product-image">
				<img src="<?php print $product->getFeaturedImage('full')->GetUrl(); ?>" 
					alt="<?php print $product->product_name; ?>" />
			</a>
		</div>
	</div><!-- end id="product-images" -->
	<div id="product-data" class="col-md-6">
		<h2 id="product-title"><?php print $product->product_name; ?></h2>
		<div id="product-short-description">
			<?php print $product->product_description; ?>
		</div>
		<form id="product-form" action="" method="post">
			<input type="hidden" name="mod" value="emono" />
			<input type="hidden" name="task" value="add_to_cart" />
			<div class="form-inline">
				<div class="form-group">
					<input type="number" min="1" name="qty" value="1" class="form-control" />
					<button type="submit" class="btn btn-default btn-add-to-cart"><?php _e('Add to cart', 'emono'); ?></button>
				</div>
			</div>
		</form>
		<div id="product-price">
			<span class="text"><?php _e('Precio:', 'elite'); ?></span>
			<span class="number"><?php print $product->GetPrice(); ?></span>
		</div>
		<div id="product-code-sku">
			<?php _e('Code/SKU:', 'emono'); ?>
			<span id="the-code"><?php print $product->product_code; ?></span>
		</div>
		<div id="product-categories">
			<b><?php _e('Categories:', 'emono'); ?></b><br/>
			<ul class="list">
				<?php foreach($product->GetCategories() as $cat): ?>
				<li>
					<a href="<?php print SB_Route::_('index.php?mod=emono&view=category&id='.$cat->category_id.'&slug='.$cat->slug); ?>">
						<?php print $cat->name; ?>
					</a>
				</li>
				<?php endforeach; ?>
			</ul>
		</div>
		<div id="social-share-buttons">
			<!-- Go to www.addthis.com/dashboard to customize your tools -->
			<div class="addthis_inline_share_toolbox"></div>
		</div>
	</div><!-- end id="product-data" -->
	<div id="tabs-container" class="clearfix">
		<ul id="product-tabs" class="nav nav-tabs">
			<li class="active"><a href="#description"><?php _e('Description', 'emono'); ?></a></li>
			<?php if( @$this->ops->show_comments ): ?>
			<li><a href="#comments"><?php _e('Comments', 'emono'); ?></a></li>
			<?php endif; ?>
			<li><a href="#specs"><?php _e('Specs', 'emono'); ?></a></li>
		</ul>
		<div class="tab-content">
			<div id="description" class="tab-pane active">
				<?php print $product->product_description; ?>
			</div><!-- end id="description" -->
			<?php if( @$this->ops->show_comments ): ?>
			<div id="comments" class="tab-pane">
			</div><!-- end id="description" -->
			<?php endif; ?>
			<div id="specs" class="tab-pane">
			</div><!-- end id="description" -->
		</div><!-- end class="tab-content" -->
	</div>
	<script>
	jQuery(function()
	{
		jQuery('#product-images').lightGallery({
			selector: '.product-image'
		});
		jQuery('#product-tabs a').click(function (e) 
		{
			  e.preventDefault();
			  jQuery(this).tab('show');
		});
	});
	</script>
	<section id="related-products" class="section">
		<h2 class="title"><?php _e('Productos Relacionados', 'elite'); ?></h2>
		
	</section>
</div><!-- end id="product-cotainer" -->