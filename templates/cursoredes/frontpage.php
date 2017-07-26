<?php 
$tcr = TCR();
$courses 	= SB_DbTable::GetTable('lms_courses', true)
							->GetRows(4, 0, 
										array(
											'order' => array(
												'orderby' => 'creation_date', 
												'order' => 'DESC'
											)
										), 
										'LMS_Course');
lt_get_header(); ?>
	<div id="front-slides" class="row">
		<div class="slide">
			<div class="image"><img src="<?php print TEMPLATE_URL; ?>/images/banner-inicio.jpg" alt="" /></div>
			<div class="bubble-desc">
				Lorem ipsum dolor sit amet, consectetur adipiscing elit, 
				sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. 
				Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut 
				aliquip ex ea commodo consequat.
			</div>
		</div><!-- end class="slide" -->
	</div><!-- end id="front-slides" -->
	<div id="sections" class="row">
		<section id="latest-courses" class="front-section">
			<h2 class="title"><span><?php _e('Nuestros Cursos', 'cr'); ?></span></h2>
			<div class="body">
				<div class="container-fluid">
					<div class="row">
						<?php 
						
						?>
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
				<div class="btn-more-container">
					<a href="<?php print lms_get_page_listing()->link; ?>" class="btn">Ver Todos</a>
				</div>
			</div><!-- end class="body" -->
		</section><!-- class="front-section" -->
		<section class="front-section">
			<div class="container-fluid">
				<div class="row">
					<div class="col-xs-12 col-sm-6 col-md-6">
						<h2 id="front-video-text">
							<?php if( !$tcr->video_text ): ?>
							<?php _e('Conoce nuestra modalidad de estudio', 'cr'); ?>
							<?php else: print $tcr->video_text; endif; ?>
						</h2>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-6">
						<div class="video-container">
							<iframe width="560" height="315" 
								src="<?php print !$tcr->yt_url ? 'https://www.youtube.com/embed/vK9fvSEXw0E?ecver=1' : $tcr->yt_url; ?>" frameborder="0" allowfullscreen></iframe>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div><!-- end id="sections" -->
<?php lt_get_footer(); ?>