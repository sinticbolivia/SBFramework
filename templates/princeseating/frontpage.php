<?php lt_get_header(); ?>
	<div id="content-wrap" class="row">
		<div id="frontpage" class="col-xs-12 col-md-12">
			<?php SB_MessagesStack::ShowMessages(); ?>
			<div id="front-slider">
				<div class="row">
					<?php if( function_exists('lt_slider') ): lt_slider('slider_0'); else: ?>
					<div class="slide col-xs 12 col-md-12">
						<a href=""><img src="<?php print TEMPLATE_URL; ?>/images/slider/bentwood-slider.jpg" alt="" /></a>
					</div>
					<?php endif; ?>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12 col-sm-6 col-md-3">
					<div class="front-category">
						<div class="image"><img src="<?php print TEMPLATE_URL; ?>/images/new_col.png" alt="" /></div>
						<h2 class="title">
							<a href="<?php print SB_Route::_('index.php?mod=emono&view=category&id=32&slug=new-products'); ?>">
								<?php _e('New Products', 'ps'); ?>
							</a>
						</h2>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-3">
					<div class="front-category">
						<div class="image"><img src="<?php print TEMPLATE_URL; ?>/images/modern_dine.png" alt="" /></div>
						<h2 class="title">
							<a href="<?php print SB_Route::_('index.php?mod=emono&view=category&id=27&slug=stack-chairs'); ?>">
								<?php _e('Banquet Seating', 'ps'); ?></a></h2>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-3">
					<div class="front-category">
						<div class="image"><img src="<?php print TEMPLATE_URL; ?>/images/outdoor_seat.png" alt="" /></div>
						<h2 class="title">
							<a href="<?php print SB_Route::_('index.php?mod=emono&view=category&id=12&slug=side-chairs'); ?>">
								<?php _e('Restaurant Seating', 'ps'); ?>
							</a>
						</h2>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-3">
					<div class="front-category">
						<div class="image"><img src="<?php print TEMPLATE_URL; ?>/images/quickship.png" alt="" /></div>
						<h2 class="title">
							<a href="<?php print SB_Route::_('index.php?mod=emono&view=category&id=33&slug=quick-ship'); ?>">
								<?php _e('Quick Ship', 'ps'); ?></a></h2>
					</div>
				</div>
			</div>
		</div><!-- end id="content" --> 
	</div>
<?php lt_get_footer(); ?>