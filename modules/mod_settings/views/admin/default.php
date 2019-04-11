<?php
//$continents = array( 'Africa', 'America', 'Antarctica', 'Arctic', 'Asia', 'Atlantic', 'Australia', 'Europe', 'Indian', 'Pacific');
//$time_zones = timezone_identifiers_list();
//print_r($time_zones);
?>
<link rel="stylesheet" href="<?php print BASEURL; ?>/js/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css" />
<script src="<?php print BASEURL; ?>/js/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
<div class="wrap">
	<h2 id="page-title">		
		<div class="container-fluid">
			<div class="row">
				<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
					<?php print __('Settings', 'settings'); ?>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
					<div class="text-right">
						<a href="javascript:;" onclick="document.getElementById('form-settings').submit();" 
							class="btn btn-success">
							<?php _e('Save', 'settings'); ?>
						</a>
					</div>
				</div>
			</div>
		</div>
	</h2>
	<div id="settings-tabs">
		<ul class="nav nav-tabs" role="tablist">
			<?php if( sb_get_current_user()->can('manage_general_settings') ): ?>
		    <li class="active">
		    	<a href="#general" role="tab" data-toggle="tab" class="has-popover" data-content="<?php _e('SETTINGS_TAB_LABEL_GENERAL'); ?>">
		    		<?php _e('General', 'settings'); ?>
		    	</a>
		    </li>
		    <?php endif; ?>
		    <?php b_do_action('settings_tabs', $settings); ?>
	  	</ul>
	  	<form id="form-settings" action="" method="post">
	  		<input type="hidden" name="mod" value="settings" />
	  		<input type="hidden" name="task" value="save" />
	  		<div class="tab-content">
	  			<?php if( sb_get_current_user()->can('manage_general_settings') ): ?>
		  		<div id="general" role="tabpanel" class="tab-pane active">
		  			<?php b_do_action('before_general_settings', $settings); ?>
		  			<div class="row">
		  				<div class="col-md-5">
		  					<div class="control-group">
				  				<label class="has-popover" data-content="<?php _e('SETTINGS_LABEL_SITE_TITLE'); ?>">
				  					<?php _e('Site Title:', 'settings'); ?>
				  				</label>
				  				<div class="input-group site_title">
				  					<input type="text" name="settings[SITE_TITLE]" value="<?php print @$settings->SITE_TITLE; ?>" class="form-control" />
				  					<input type="hidden" id="site-title-color" name="settings[SITE_TITLE_COLOR]" value="<?php print isset($settings->SITE_TITLE_COLOR) && $settings->SITE_TITLE_COLOR ? $settings->SITE_TITLE_COLOR : '#0213cc'; ?>" />
				  					<span class="input-group-addon" title="<?php _e('Color de T&iacute;tulo', 'content'); ?>">
								    	<i style="display:inline-block;width:16px;height:16px;cursor:pointer;background-color:<?php print isset($settings->SITE_TITLE_COLOR) && $settings->SITE_TITLE_COLOR ? $settings->SITE_TITLE_COLOR : '#0213cc'; ?>;">&nbsp;</i>
								    </span>
				  				</div>
				  			</div>
				  			<div class="control-group">
				  				<label class="has-popover" data-content="<?php _e('SETTINGS_LABEL_TIME_ZONE'); ?>">
				  					<?php _e('Hourly Zone:', 'settings'); ?></label>
				  				<select name="settings[TIME_ZONE]" class="form-control">
				  					<?php print sb_timezone_choice(@$settings->TIME_ZONE); ?>
				  				</select>
				  			</div>
				  			<div class="form-group">
				  				<label class="has-popover" data-content="<?php _e('SETTINGS_LABEL_DATE_FORMAT'); ?>">
				  					<?php _e('Date Format:', 'settings'); ?></label>
				  				<select name="settings[DATE_FORMAT]" class="form-control">
				  					<option value="Y-m-d" <?php print (isset($settings->DATE_FORMAT) && $settings->DATE_FORMAT == 'Y-m-d' ) ? 'selected' : ''; ?>>yyyy-mm-dd</option>
				  					<option value="m-d-Y" <?php print (isset($settings->DATE_FORMAT) && $settings->DATE_FORMAT == 'm-d-Y' ) ? 'selected' : ''; ?>>mm-dd-yyyy</option>
				  					<option value="d-m-Y" <?php print (isset($settings->DATE_FORMAT) && $settings->DATE_FORMAT == 'd-m-Y' ) ? 'selected' : ''; ?>>dd-mm-yyyy</option>
				  				</select>
				  			</div>
				  			<div class="form-group">
				  				<label><?php _e('Country:', 'settings'); ?></label>
				  				<select name="settings[COUNTRY_CODE]" class="form-control">
				  					<?php foreach(include INCLUDE_DIR . SB_DS . 'countries.php' as $code => $c): ?>
				  					<option value="<?php print $code; ?>" <?php print @COUNTRY_CODE == $code ? 'selected' : ''; ?>><?php print $c; ?></option>
				  					<?php endforeach; ?>
				  				</select>
				  			</div>
				  			<div class="form-group">
				  				<label><?php _e('Language:', 'settings'); ?></label>
				  				<select name="settings[LANGUAGE]" class="form-control">
				  					<?php foreach($this->app->GetLanguages() as $code => $lang):?>
				  					<option value="<?php print $code; ?>" <?php print @LANGUAGE == $code ? 'selected' : ''; ?>>
				  						<?php print $lang; ?>
				  					</option>
				  					<?php endforeach; ?>
				  				</select>
				  			</div>
		  				</div><!-- end class="col-md-5" -->
		  			</div>
		  			<?php b_do_action('after_general_settings', $settings); ?>
		  		</div><!-- end id="general" -->
		  		<?php endif; ?>
		  		<?php b_do_action('settings_tabs_content', $settings); ?>
		  	</div>
		  	<br/>
		  	<div class="form-group">
		  		<a href="<?php print b_route('index.php'); ?>" class="btn btn-danger"><?php _e('Cancelar', 'settings'); ?></a>
		  		<button class="btn btn-success has-popover" type="submit" data-content="<?php _e('SETTINGS_BUTTON_SAVE'); ?>">
		  			<?php _e('Save', 'settings'); ?>
                </button>
		  	</div>
	  	</form>
	</div>
</div>
<script>
jQuery(function()
{
	jQuery('#settings-tabs .nav-tabs a').click(function (e) 
	{
		e.preventDefault();
		jQuery(this).tab('show');
	});
	jQuery('.input-group.site_title').colorpicker({input: '#site-title-color'});
});
</script>