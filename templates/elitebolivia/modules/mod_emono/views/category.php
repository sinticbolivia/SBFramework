<?php
$img = $category->GetImage();
?>
<?php emono_breadcrumb($category); ?>
<div id="product-category-container">
	<h1 id="page-title"><?php print $category->name; ?></h1>
	<?php if( $img ): ?>
	<figure class="image">
		<a href="<?php print $category->link; ?>">
			<img src="<?php print $img->GetThumbnail('full')->GetUrl(); ?>" alt="<?php print $category->name; ?>" 
				title="<?php print $category->name; ?>" class="img-resp" />
		</a>
	</figure>
	<?php endif; ?>
	<?php lt_include_partial('emono', 'products_filter.php', array()); ?>
	<div id="product_list" class="row">
	<?php foreach($products as $p): ?>
		<?php 
		//$slug = (empty($p->slug) ? sb_build_slug($p->product_name) : $p->slug);
		//SB_Route::_('index.php?mod=emono&view=product&id='.$p->product_id.'&slug='. $slug);
		$link = $p->link;
		?>
		<div class="col-xs-12 col-sm-6 col-md-3">
			<div class="product">
				<div class="image">
					<a href="<?php print $link; ?>">
						<img src="<?php print $p->getFeaturedImage('330x330')->GetUrl(); ?>" 
							alt="<?php print $p->product_name; ?>" title="<?php print $p->product_name; ?>"
							class="img-resp" />
					</a>
				</div>
				<div class="name"><?php print $p->product_name; ?></div>
				<div class="code"><a href="<?php print $link ?>"><?php print $p->product_code; ?></a></div>
				<?php if( @$this->ops->show_price): ?>
				<div class="price">
					<span class="text"><?php _e('Precio:', 'elite'); ?></span>
					<span class="number"><?php print $p->price; ?></span>
				</div>
				<?php endif; ?>
			</div>
		</div>
		<?php endforeach; ?>
	</div>
</div><!-- end id="product-category-container" -->
<?php lt_pagination(SB_Route::_('index.php?'.$_SERVER['QUERY_STRING']), $total_pages, $current_page); ?>