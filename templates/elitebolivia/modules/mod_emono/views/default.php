<?php emono_breadcrumb(null); ?>
<?php foreach($categories as $cat): $img = $cat->GetImage(); if( !$img ) continue; ?>
<section class="category">
	<figure class="image">
		<a href="<?php print $cat->link; ?>">
			<?php if($img): ?>
			<img src="<?php print $img->GetThumbnail('full')->GetUrl(); ?>" alt="<?php print $cat->name; ?>" 
				title="<?php print $cat->name; ?>" class="img-resp" />
			<?php else: print $cat->name; ?>
			<?php endif; ?>
		</a>
	</figure>
	<div class="products">
		<div class="row">
			<?php foreach($cat->GetLatestProducts(3) as $p): ?>
			<div class="col-xs-12 col-sm-3 col-md-3">
				<div class="product">
					<div class="row">
						<div class="col-xs-6 col-md-6">
							<figure class="image">
								<a href="<?php print $p->link; ?>">
									<img src="<?php print $p->getFeaturedImage()->GetUrl(); ?>" 
										alt="<?php print $p->product_name; ?>" 
										title="<?php print $p->product_name; ?>" class="img-resp" />
								</a>
							</figure>
						</div>
						<div class="col-xs-6 col-md-6">
							<div class="title">
								<a href="<?php print $p->link; ?>"><?php print $p->product_name; ?></a>
							</div>
							<div class="price">
								(<?php print $p->price; ?> Bs)
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php endforeach; ?>
			<div class="col-xs-12 col-sm-3 col-md-3">
				<br/><br/>
				<div class="text-center">
					<a href="<?php print $cat->link; ?>" class="btn btn-see-more">
						<?php _e('Ver mas', 'elite'); ?>
					</a>
				</div>
			</div>
		</div>
	</div>
</section><!-- end class="category" -->
<?php endforeach; ?>