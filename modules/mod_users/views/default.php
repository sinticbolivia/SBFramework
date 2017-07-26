<?php
?>
<h1><?php print SB_Text::_('My Account', 'users'); ?></h1>
<form action="" method="post">
	<input type="hidden" name="mod" value="users" />
	<input type="hidden" name="task" value="save_profile" />
	<div class="row">
		<div class="col-md-2">
			<div id="select-image" href="javascript:;" title="<?php print SB_Text::_('Select user image', 'users'); ?>">
				<img src="<?php print $image_url; ?>" alt="" class="img-thumbnail" />
			</div>
			<div id="uploading" style="display:none;">
				<img src="<?php print BASEURL; ?>/js/fineuploader/loading.gif" alt=""  /><?php print SB_Text::_('Uploading Image', 'users'); ?>
			</div>
		</div>
		<div class="col-md-10">
			<div class="form-row">
				<label><?php _e('Firstname', 'users'); ?></label>
				<input type="text" name="first_name" value="<?php print $user->first_name; ?>" class="form-control" />
			</div>
			<div class="form-row">
				<label><?php _e('Lastname', 'users'); ?></label>
				<input type="text" name="last_name" value="<?php print $user->last_name; ?>" class="form-control" />
			</div>
		</div>
	</div>
	<div id="user-tabs">
		<ul class="nav nav-tabs">
			<li class="active"><a href="#profile" data-toggle="tab"><?php print SB_Text::_('Profile', 'users'); ?></a></li>
			<li><a href="#personal" data-toggle="tab"><?php print SB_Text::_('Personal', 'users'); ?></a></li>
			<?php SB_Module::do_action('user_tabs', $user); ?>
		</ul>
		<div class="tab-content">
			<div id="profile" class="tab-pane active">
				<div class="row">
					<div class="col-xs-12 col-md-6">
						<div class="control-group">
							<label><?php print SB_Text::_('Email:', 'users'); ?></label>
							<input type="text" name="email" value="<?php print $user->email; ?>" class="form-control" />
						</div>
						<div class="control-group">
							<label><?php print SB_Text::_('Password:', 'users'); ?></label>
							<input type="password" name="pwd" value="" placeholder="<?php print SB_Text::_('Dejar en blanco para no actualizar.', 'users'); ?>" class="form-control" />
						</div>
					</div>
					<div class="col-xs-12 col-md-6">
						<h3><?php _e('Security Questions'); ?></h3>
						<div class="form-group">
							<label><?php _e('Question #1'); ?></label>
							<select name="meta[_sec_quest_1]" class="form-control">
								<option value="-1"><?php _e('-- question --', 'users'); ?></option>
								<?php foreach($questions as $key => $q): ?>
								<option value="<?php print $key?>" <?php print $user->_sec_quest_1 == $key ? 'selected' : '';?>>
									<?php print $q; ?>
								</option>
								<?php endforeach; ?>
							</select>
							<input type="text" name="meta[_sec_quest_1_ans]" value="<?php print $user->_sec_quest_1_ans ?>" class="form-control" />
						</div>
						<div class="form-group">
							<label><?php _e('Question #1'); ?></label>
							<select name="meta[_sec_quest_2]" class="form-control">
								<option value="-1"><?php _e('-- question --', 'users'); ?></option>
								<?php foreach($questions as $key => $q): ?>
								<option value="<?php print $key?>" <?php print $user->_sec_quest_2 == $key ? 'selected' : '';?>>
									<?php print $q; ?>
								</option>
								<?php endforeach; ?>
							</select>
							<input type="text" name="meta[_sec_quest_2_ans]" value="<?php print $user->_sec_quest_2_ans ?>" class="form-control" />
						</div>
					</div>
				</div>
			</div><!-- end id="profile" -->
			<div id="personal" class="tab-pane">
				<div class="row">
					<div class="col-xs-12 col-md-6">
						<div class="form-group">
				    		<label><?php print SB_Text::_('Birthday:', 'users')?></label>
				    		<input class="form-control" type="text" name="birthday" value="<?php print $user->_birthday; ?>" />
				    	</div>
				    	<div class="form-group">
				    		<label><?php print SB_Text::_('Address:', 'users')?></label>
				    		<input class="form-control" type="text" name="address" value="<?php print $user->_address; ?>" />
				    	</div>
				    	<div class="form-group">
				    		<label><?php print SB_Text::_('City:', 'users')?></label>
				    		<input class="form-control" type="text" name="city" value="<?php print $user->_city; ?>" />
				    	</div>
				    	<div class="form-group">
				    		<label><?php print SB_Text::_('State:', 'users')?></label>
				    		<input class="form-control" type="text" name="state" value="<?php print $user->_state; ?>" />
				    	</div>
				    	<div class="form-group">
				    		<label><?php print SB_Text::_('Country:', 'users')?></label>
				    		<select name="country" class="form-control">
				    			<option value="-1"><?php print SB_Text::_('-- pais --', 'users'); ?></option>
				    		</select>
				    	</div>
					</div>
					<div class="col-xs-12 col-md-6">
					</div>
				</div>
		    </div><!-- end id="personal" -->
		    <?php SB_Module::do_action('user_tabs_content', $user); ?>
		</div>
	</div><!-- end id="user-tabs" -->
	<div class="control-group">
		<button class="btn" type="submit"><?php print SB_Text::_('Guardar', 'users'); ?></button>
	</div>
</form>
<script>
window.mod_users = {
	upload_endpoint: '<?php print $upload_endpoint; ?>'
};
jQuery('#user-tabs > ul li a').click(function (e) 
{
	e.preventDefault()
	jQuery(this).tab('show');
});
</script>