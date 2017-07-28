<?php
/**
 * Template: Contacto
 * Fields: telephone,address,mail,latitud,longitud
 */
 lt_get_header();
 ?>
	<div id="content-wrap" class="row">
		<?php lt_get_sidebar(); ?>
		<div id="content" class="col-xs-12 col-md-9">
			<?php SB_MessagesStack::ShowMessages(); ?>
			<?php sb_show_module(); ?>
			<form id="contact-form" class="form-group-sm" action="" method="post">
				<div class="row">
					<div class="col-xs-12 col-md-6">
						<div class="form-group">
							<label class="control-label"><?php _e('Name', 'ps'); ?></label>
							<input type="text" name="fullname" value="" class="form-control" />
						</div>
					</div>
					<div class="col-xs-6 col-md-3">
						<div class="form-group">
							<label class="control-label"><?php _e('Phone', 'ps'); ?></label>
							<input type="text" name="phone" value="" class="form-control" />
						</div>
					</div>
					<div class="col-xs-6 col-md-3">
						<div class="form-group">
							<label class="control-label"><?php _e('Fax', 'ps'); ?></label>
							<input type="text" name="fax" value="" class="form-control" />
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-md-6">
						<div class="form-group">
							<label class="control-label"><?php _e('Address', 'ps'); ?></label>
							<input type="text" name="address" value="" class="form-control" />
						</div>
					</div>
					<div class="col-xs-12 col-md-6">
						<div class="form-group">
							<label class="control-label"><?php _e('Email (required)', 'ps'); ?></label>
							<input type="email" name="email" value="" class="form-control" />
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-6 col-md-6">
						<div class="form-group">
							<label class="control-label"><?php _e('City', 'ps'); ?></label>
							<input type="text" name="city" value="" class="form-control" />
						</div>
					</div>
					<div class="col-xs-3 col-md-3">
						<div class="form-group">
							<label class="control-label"><?php _e('State', 'ps'); ?></label>
							<input type="text" name="state" value="" class="form-control" />
						</div>
					</div>
					<div class="col-xs-3 col-md-3">
						<div class="form-group">
							<label class="control-label"><?php _e('Zip', 'ps'); ?></label>
							<input type="text" name="zip" value="" class="form-control" />
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-md-12">
						<div class="form-group">
							<label><?php _e('Your Enquiry', 'ps'); ?></label>
							<textarea name="message" class="form-control"></textarea>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="form-group">
						<button type="submit" class="btn btn-blue"><?php _e('Submit', 'ps'); ?></button>
					</div>
				</div>
			</form>
		</div><!-- end id="content" --> 
	</div>
 <?php lt_get_footer(); ?>
 