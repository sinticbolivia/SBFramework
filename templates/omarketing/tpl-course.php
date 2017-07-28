<?php
/**
 * Template: Inscripcion
 */
lt_get_header();
?>
	<div id="content-wrap" class="row">
		<div id="page-banner" class="">
			<img src="<?php print TEMPLATE_URL; ?>/images/banner01.jpg" alt="" />
		</div>
		<?php SB_MessagesStack::ShowMessages(); ?>
		<div  class="col-md-12">
			<div class="row">
				<div id="content" class="col-md-9">
					<h1 id="content-title"><?php print $course->name; ?></h1>
					<section id="the-content">
						<ul class="nav nav-tabs">
							<li class="active">
								<a href="#description" data-toggle="tab"><?php _e('Descripcion', 'om'); ?></a>
							</li>
							<li><a href="#course-content" data-toggle="tab"><?php _e('Contenido', 'om'); ?></a></li>
						</ul>
						<div class="tab-content">
							<div id="description" class="tab-pane fade in active">
								<?php print $course->description; ?>
							</div>
							<div id="course-content" class="tab-pane fade in">
								<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
									<?php $i = 1; foreach($course->GetModules() as $mod): ?>
									<div class="panel panel-default">
										<div class="panel-heading" role="tab" id="headingOne">
											<h4 class="panel-title">
												<a role="button" data-toggle="collapse" data-parent="#accordion" 
													href="#mod<?php print $i; ?>" aria-expanded="true" aria-controls="collapseOne">
													<?php print $mod->name; ?>
												</a>
											</h4>
										</div>
										<div id="mod<?php print $i; ?>" class="panel-collapse collapse" role="tabpanel" 
											aria-labelledby="headingOne">
											<div class="panel-body">
												<?php print nl2br($mod->description); ?>
											</div>
										</div>
									</div>
									<?php $i++; endforeach; ?>
									
								</div>
							</div>
						</div>
					</section><!-- end id="the-content" -->
				</div><!-- end id="content" -->
				<?php lt_get_sidebar('course'); ?>
			</div>
		</div>
	</div><!-- end id="content-wrap" -->
<?php lt_get_footer(); ?>