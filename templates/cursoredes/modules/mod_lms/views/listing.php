<section id="courses-listing">
	<div class="container-fluid">
		<div class="row">
			<?php foreach($courses as $c): ?>
			<div class="col-xs-12 col-sm-6 col-md-3">
				<div class="course">
					<figure class="image">
						<a href="<?php print $c->link; ?>">
							<?php if( $img = $c->GetFeaturedImage() ): ?>
							<img src="<?php print $img->GetUrl() ?>" alt="" />
							<?php else: ?>
							<img src="<?php print TEMPLATE_URL; ?>/images/curso-linux.jpg" alt="" />
							<?php endif; ?>
						</a>
					</figure>
					<h3 class="title"><a href="<?php print $c->link; ?>"><?php print $c->name; ?></a></h3>
					<div class="excerpt"><?php print $c->excerpt; ?></div>
					<div class="row">
						<div class="col-xs-12 col-sm-6 col-md-6"></div>
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="text-right">
								<span class="course-type">
									<?php if( (int)$c->required_payment ): ?>
									<?php print sb_number_format($c->course_cost); ?>
									<?php else: ?>
									<?php _e('Gratis'); ?>
									<?php endif; ?>
								</span>
							</div>
						</div>
					</div>
				</div>
				
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>