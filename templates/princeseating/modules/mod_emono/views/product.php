<?php
$attr = json_decode($product->_attributes);
//##check if attributes are in correct format
if( !isset($attr->height) )
{
	$new_attr = (object)array(
		'height'		=> '',
		'seat_height'	=> '',
		'width'			=> '',
		'depth'			=> '',
		'weight'		=> ''
	);
	if( $attr )
	{
		foreach($attr as $a)
		{
			if( $a->taxonomy == 'pa_height' )
				$new_attr->height = $a->value;
			elseif( $a->taxonomy == 'pa_seat-height' )
				$new_attr->seat_height = $a->value;
			elseif( $a->taxonomy == 'pa_width' )
				$new_attr->width = $a->value;
			elseif( $a->taxonomy == 'pa_depth' )
				$new_attr->depth = $a->value;
			elseif( $a->taxonomy == 'pa_weight' )
				$new_attr->weight = $a->value;
		}
	}
	$attr = $new_attr;
}
?>
<div id="product-container">
	<div id="product-images" class="col-md-6">
		<div id="the-image">
			<a href="<?php print $product->getFeaturedImage()->GetUrl(); ?>" class="image">
				<img src="<?php print $product->getFeaturedImage('500x500')->GetUrl(); ?>" alt="<?php print $product->product_name ?>" />
			</a>
		</div>
		<ul id="thumbnails">
			<?php foreach($product->getImages() as $img): ?>
			<li class="thumb">
				<a href="<?php print $img->GetUrl(); ?>" class="image">
					<img src="<?php print $img->GetThumbnail('500x500')->GetUrl(); ?>" alt="" />
				</a>
			</li>
			<?php endforeach; ?>
		</ul><!-- end id="thumbnails" -->
	</div><!-- end id="product-images" -->
	<div id="product-data" class="col-md-6">
		<h2 id="product-title"><?php print $product->product_name; ?></h2>
		<div id="product_code"><?php print $product->product_code; ?></div>
		<div id="product-short-description">
			<?php print $product->product_description; ?>
		</div>
		<div class="clearfix"></div>
		<?php if( $attr ): ?>
		<section id="section-dimensions">
			<h4><?php _e('Dimensions', 'ps'); ?></h4>
			<ul class="dimensions">
				<li class="dimension-height">
					<span class="icon"></span><span class="text"><?php print $attr->height; ?></span></li>
				<li class="dimension-width">
					<span class="icon"></span><span class="text"><?php print $attr->width; ?></span></li>
				<li class="dimension-depth">
					<span class="icon"></span><span class="text"><?php print $attr->depth; ?></span></li>
				<li class="dimension-seat-height">
					<span class="icon"></span><span class="text"><?php print $attr->seat_height; ?></span></li>
				<li class="dimension-weight">
					<span class="icon"></span>
					<span class="text"><?php print $attr->weight; ?></span>
				</li>
			</ul>
		</section>
		<?php endif; ?>
		<div class="clearfix"></div>
		<a href="<?php print SB_Route::_('index.php?task=ps_specsheet&id='.$product->product_id); ?>" 
			class="btn btn-blue btn-lg btn-specs">
			<?php _e('View Spec Sheet', 'ps'); ?>
		</a>
		<div class="clearfix"></div>
		<section id="availability">
			<h4><?php _e('Availability', 'ps'); ?></h4>
			<?php _e('Instock', 'ps') ?>
		</section>
		<div class="clearfix"></div>
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
	</div><!-- end id="product-data" -->
	<div class="clearfix"></div>
	<div id="tabs-container">
		<ul id="product-tabs" class="nav nav-tabs">
			<li class="active"><a href="#enquiry" data-toggle="tab"><?php _e('Enquiry', 'ps'); ?></a></li>
			<li><a href="#fabrics" data-toggle="tab"><?php _e('Fabrics', 'ps'); ?></a></li>
			<li><a href="#finishes" data-toggle="tab"><?php _e('Finishes', 'ps'); ?></a></li>
			<li><a href="#features" data-toggle="tab"><?php _e('Features', 'ps'); ?></a></li>
			<li><a href="#file_request" data-toggle="tab"><?php _e('File Request', 'ps'); ?></a></li>
		</ul>
		<div class="tab-content">
			<div id="enquiry" class="tab-pane active">
				<?php print SB_Shortcode::ParseShortcodes('[forms id="1"]'); ?>
			</div><!-- end id="enquiry" -->
			<div id="fabrics" class="tab-pane">
				<?php 
				sb_include_module_helper('content');
				$fabrics = LT_HelperContent::GetArticles(array(
					'type'			=> 'fabrics',
					'rows_per_page'	=> -1,
					'order_by'		=> 'title',
					'order'			=> 'asc',
					'in_ids' 		=> (array)json_decode($product->_fabrics)
				));
				?>
				<ul id="product-fabrics">
					<?php foreach($fabrics['articles'] as $f): ?>
					<li class="fabric">
						<div class="image"><?php print $f->TheThumbnail(); ?></div>
						<div class="title"><?php print $f->title; ?></div>
					</li>
					<?php endforeach; ?>
				</ul>
			</div><!-- end id="fabrics" -->
			<div id="finishes" class="tab-pane">
				<?php
				$f_groups_ids = (array)json_decode($product->_finishes);
				$groups = LT_HelperContent::GetSections(null, 'finishes', $f_groups_ids);
				foreach($groups as $group):
				/*
				$finishes = LT_HelperContent::GetArticles(array(
					'type'			=> 'finishes',
					'rows_per_page'	=> -1,
					'order_by'		=> 'title',
					'order'			=> 'asc',
					'in_ids' 		=> (array)json_decode($product->_finishes)
				));
				*/
				$finishes = $group->GetArticles();
				?>
				<ul id="product-fabrics">
					<?php foreach($finishes as $f): ?>
					<li class="fabric">
						<div class="image"><?php print $f->TheThumbnail(); ?></div>
						<div class="title"><?php print $f->title; ?></div>
					</li>
					<?php endforeach; ?>
				</ul>
				<?php endforeach; ?>
			</div><!-- end id="finishes" -->
			<div id="features" class="tab-pane">
				<?php print $product->_features; ?>
			</div><!-- end id="features" -->
			<div id="file_request" class="tab-pane">
			</div><!-- end id="file_request" -->
		</div><!-- end class="tab-content" -->
	</div>
	<?php lt_include_partial('emono', 'related-products.php', array('product' => $product)); ?>
</div><!-- end id="product-cotainer" -->
<script>
function loadImage()
{
	var fullurl 	= this.href;
	var thumburl 	= jQuery(this).find('img:first').prop('src');
	var img 		= new Image();
	img.src 		= thumburl;
	img.addEventListener('load', function()
	{
		jQuery('#the-image .image').prop('href', fullurl);
		jQuery('#the-image .image').html('');
		jQuery('#the-image .image').append(img);
		//console.log(arguments);
	});
	return false;
}
jQuery(function()
{
	jQuery('#thumbnails .thumb .image').click(loadImage);
	jQuery('#product-images').lightGallery({
        selector: '.image'
    });
});
</script>