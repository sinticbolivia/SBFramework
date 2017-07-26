<?php
?>
<div class="wrap">
	<h2 class="great-title">
		<?php _e('System Settings', 'mb'); ?>
		<div class="pull-right">
			<a href="javascript:;" id="btn-submit-settings" class="btn btn-success"><?php _e('Save', 'digiprint'); ?></a>
		</div>
	</h2>
	<h2 class="text-center"><?php _e('Company Information', 'digiprint'); ?></h2>
	<div class="row">
		<div class="col-xs-6 col-md-4">
			<div class="form-group">
				<label><?php print SBText::_('Business Name:', 'digiprint'); ?></label>
				<input type="text" name="ops[business_name]" value="<?php print @$ops->business_name; ?>" class="form-control try-submit" />
			</div>
		</div>
		<div class="col-xs-6 col-md-4">
			<div class="form-group">
				<label><?php print SBText::_('Telephone:', 'digiprint'); ?></label>
				<input type="text" id="business_phone" name="ops[business_phone]" value="<?php print @$ops->business_phone; ?>" class="form-control try-submit" />
			</div>
		</div>
		<div class="col-xs-12 col-md-4">
			<div class="form-group">
				<label><?php print SBText::_('Slogan:', 'digiprint'); ?></label>
				<input type="text" id="business_slogan" name="ops[business_slogan]" value="<?php print @$ops->business_slogan; ?>" class="form-control try-submit" />
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-6 col-md-4">
			<div class="form-group">
				<label><?php print SBText::_('Registro Federal de Contribuyentes:', 'digiprint'); ?></label>
				<input type="text" id="business_nit_ruc_nif" name="ops[business_nit_ruc_nif]" value="<?php print @$ops->business_nit_ruc_nif; ?>" class="form-control try-submit" />
			</div>
		</div>
		<div class="col-xs-6 col-md-4">
			<div class="form-group">
				<label><?php print SBText::_('Email:', 'digiprint'); ?></label>
				<input type="text" id="business_email" name="ops[business_email]" value="<?php print @$ops->business_email; ?>" class="form-control try-submit" />
			</div>
		</div>
		<div class="col-xs-12 col-md-4">
			<div class="form-group">
				<label><?php print SBText::_('Website:', 'digiprint'); ?></label>
				<input type="text" id="business_website" name="ops[business_website]" value="<?php print @$ops->business_website; ?>" class="form-control try-submit" />
			</div>
		</div>
	</div>
	<ul class="nav nav-tabs">
		<li class="active"><a href="#mono-business" data-toggle="tab"><?php _e('General Information', 'digiprint'); ?></a></li>
		<li><a href="#branch-office" data-toggle="tab"><?php _e('Branch Office', 'digiprint'); ?></a></li>
		<li><a href="#categories" data-toggle="tab"><?php _e('Categories', 'digiprint'); ?></a></li>
		<?php SB_Module::do_action('mb_settings_tab', $ops); ?>
	</ul>
	<div class="tab-content">
		<div id="mono-business" class="tab-pane active">
			<form id="form-settings" action="" method="post" enctype="multipart/form-data">
				<input type="hidden" name="mod" value="mb" />
				<input type="hidden" name="task" value="save_settings" />
				<div class="row">
					<div class="col-xs-6 col-md-4">
						<div class="form-group">
							<label><?php print SBText::_('Business Address:', 'digiprint'); ?></label>
							<input type="text" name="ops[business_address]" value="<?php print @$ops->business_address; ?>" class="form-control" />
						</div>
					</div>
					<div class="col-xs-6 col-md-4">
						<div class="form-group">
							<label><?php print SBText::_('City, State', 'digiprint'); ?></label>
							<input type="text" name="ops[business_city]" value="<?php print @$ops->business_city; ?>" class="form-control" />
						</div>
					</div>
					<div class="col-xs-12 col-md-4">
						<div class="form-group">
							<label><?php print SBText::_('State', 'digiprint'); ?></label>
							<input type="text" name="ops[business_state]" value="<?php print @$ops->business_state; ?>" class="form-control" />
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-6 col-md-4">
						<div class="form-group">
							<label><?php print SBText::_('Country:', 'invoices'); ?></label>
							<select name="ops[business_country]" class="form-control">
								<option value="-1"><?php print SBText::_('-- country --', 'invoices'); ?></option>
								<?php foreach( include INCLUDE_DIR . SB_DS . 'countries.php' as $code => $country): ?>
								<option value="<?php print $code; ?>" <?php print @$ops->business_country == $code ? 'selected' : ''; ?>><?php print $country; ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
					<div class="col-xs-6 col-md-4">
						<div class="form-group">
							<label>&nbsp;</label>
							<input type="text" name="ops[system_blank]" value="<?php print @$ops->system_blank; ?>" class="form-control" />
						</div>
					</div>
					<div class="col-xs-12 col-md-4">
						<div class="form-group">
							<label>&nbsp;</label>
							<input type="file" name="business_logo" value="" class="form-control" />
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-6 col-md-4">
						<div class="form-group">
							<label><?php print SBText::_('System Email', 'digiprint'); ?></label>
							<input type="text" name="ops[system_email]" value="<?php print @$ops->system_email; ?>" class="form-control" />
						</div>
					</div>
				</div>
			</form>
		</div>
		<div id="branch-office" class="tab-pane">
			<?php
			$args = array(
					'title' 	=> '',
					'branches' 	=> SB_Warehouse::GetBranches()
			); 
			lt_include_view('mb', 'admin/branches.default.php', $args); 
			?>
		</div>
		<div id="categories" class="tab-pane">
			<?php
			$categories = SB_Warehouse::getCategories();
			?>
			<form action="" method="post" class="form-search">
				<input type="hidden" name="" />
			</form>
			<table class="table gray grid">
			<thead>
			<tr>
				<th>ID</th>
				<th><?php _e('Category Name', 'digiprint'); ?></th>
				<th><?php _e('Products', 'digiprint'); ?></th>
				<th><?php _e('Status', 'digiprint'); ?></th>
				<th class="col-action">&nbsp;</th>
				<th class="col-action">&nbsp;</th>
			</tr>
			</thead>
			<tbody>
			<?php foreach($categories as $c): ?>
			<tr>
				<td><?php print $c->category_id; ?></td>
				<td><?php print $c->name; ?></td>
				<td><?php print 0; ?></td>
				<td><?php print $c->status; ?></td>
				<td>
					<a href="javascript:;" class="link-edit">&nbsp;</a>
				</td>
				<td>
					<a href="<?php print SB_Route::_('index.php?mod=mb&task=categories.delete&id='.$c->category_id)?>" class="link-delete confirm"
						data-message="<?php _e('Are you sure to delete the category?', 'digiprint'); ?>"
						title="<?php _e('Delete', 'digiprint'); ?>">
						&nbsp;
					</a>
				</td>
			</tr>
			<?php endforeach; ?>
			</tbody>
			</table>
		</div>
		<?php SB_Module::do_action('mb_settings_pane', $ops); ?>
	</div>
</div>
<script>
jQuery(function()
{
	jQuery('#btn-submit-settings').click(function()
	{
		jQuery('.try-submit').each(function(i,obj)
		{
			var hidden = obj.cloneNode(); 
			hidden.type = 'hidden';
			jQuery('#form-settings').append(hidden);
		});
		jQuery('#form-settings').submit();
		
		return false;
	});
});
</script>