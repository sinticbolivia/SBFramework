<?php
$pages = lt_content_get_frontpage_items();
lt_get_header();
?>
		<div id="home-banner"><img src="<?php print TEMPLATE_URL; ?>/images/home_banner_03.jpg" alt="" style="max-width:100%;" /></div>
		<div id="content">
			<div class="container">
				<div class="row">
					<div class="col-sm-12 col-md-6">
						<?php foreach($pages as $page): ?>
						<section>
							<h2 class="section-title"><?php print $page->TheTitle(); ?></h2>
							<p>
								<?php print $page->TheExcerpt(700); ?>
							</p>
						</section>
						<?php endforeach; ?>
					</div>
					<div class="col-sm-12 col-md-6">
						<?php 
						$res = LT_HelperContent::GetArticles(array('type' => 'post', 
																	'publish_date' => false, 
																	'end_date' => false, 
																	'rows_per_page' => 3));
						//print_r($res);
						?>
						<section>
							<h2 class="section-title">Ultimas publicaciones</h2>
							<div class="row">
								<?php foreach($res['articles'] as $item): ?>
								<div class="col-sm-12 col-md-4">
									<div class="blog-entry">
										<div class="image"><?php print $item->TheThumbnail(); ?></div>
										<div class="title">
											<a href="<?php print $item->link ?>"><?php print $item->TheTitle(); ?></a>
										</div>
									</div>
								</div>
								<?php endforeach; ?>
							</div>
						</section>
						
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12 col-md-12">
					<section>
						<h2 class="section-title">Nuestros Servicios</h2>
						<div class="row">
							<?php
							$res = LT_HelperContent::GetArticles(array('type' => 'servicios', 
																	'publish_date' => false, 
																	'end_date' => false, 
																	'rows_per_page' => -1));
							?>
							<?php foreach($res['articles'] as $s): ?>
							<div class="col-sm-12 col-md-3">
								<div class="service">
									<h3 class="title">
										<a href="<?php print $s->link ?>"><?php print $s->TheTitle(); ?></a>
									</h3>
									<div class="image"><?php print $s->TheThumbnail(); ?></div>
									<div class="excerpt"><?php print $s->TheExcerpt(); ?></div>
								</div>
							</div>
							<?php endforeach; ?>
							<!-- 
							<div class="col-sm-12 col-md-3">
								<div class="service">
									<h3 class="title">Apertura de NIT</h3>
									<div class="image"><img src="images/apertura-nit.jpg" alt="" /></div>
									<div class="excerpt"></div>
								</div>
							</div>
							<div class="col-sm-12 col-md-3">
								<div class="service">
									<h3 class="title">Balances Apertura</h3>
									<div class="image"><img src="images/balances.jpg" alt="" /></div>
									<div class="excerpt"></div>
								</div>
							</div>
							<div class="col-sm-12 col-md-3">
								<div class="service">
									<h3 class="title">Asesoramiento</h3>
									<div class="image"><img src="images/asesoramiento.jpg" alt="" /></div>
									<div class="excerpt"></div>
								</div>
							</div>
							-->
						</div>
					</section>
					</div>
				</div>
			</div>
		</div>
<?php lt_get_footer(); ?>