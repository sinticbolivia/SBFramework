<?php
$meta = SB_Request::getVar('meta');
?>
<div class="wrap">
	<form action="" method="post">
		<?php print SB_HtmlBuilder::writeInput('hidden', 'mod', 'customers');?>
		<?php print SB_HtmlBuilder::writeInput('hidden', 'task','save_customer');?>
		<?php print (isset($customer)) ? SB_HtmlBuilder::writeInput('hidden', 'customer_id', $customer->customer_id) : ''; ?>
		<div class="row">
			<h2 id="page-title" class="col-xs-6 col-md-6">
				<?php print $page_title; ?>
			</h2>
			<div class="col-xs-6 col-md-6 text-right">
				<a href="<?php print SB_Route::_('index.php?mod=customers'); ?>" class="btn btn-gray"><?php _e('Cancel', 'customers'); ?></a>
				<a href="<?php print SB_Route::_('index.php?task=customers_add_and_send'); ?>" class="btn btn-gray"><?php _e('Add and Send Data', 'customers'); ?></a>
				<button type="submit" class="btn btn-success"><?php isset($customer) ? _e('Save', 'customers') : _e('Add', 'customers'); ?></button>
			</div>
		</div>
		<h3 class="form-section-title"><?php _e('General Information', 'customers'); ?></h3>
		<div class="row">
			<div class="col-xs-4 col-md-4">
				<div class="form-group">
					<label><?php print SB_Text::_('First Name', 'mb_c'); ?></label>
					<?php print SB_HtmlBuilder::writeInput('text', 'first_name', 
															isset($customer) ? $customer->first_name : SB_Request::getString('first_name'), 
															'first_name', array('class' => 'form-control')); ?>
				</div>
				<div class="form-group">
					<label><?php print SB_Text::_('Telephone', 'customers'); ?></label>
						<?php print SB_HtmlBuilder::writeInput('text', 'telephone', 
																isset($customer) ? $customer->phone : SB_Request::getString('telephone'), 
																'telephone', array('class' => 'form-control')); ?>
				</div>
			</div>
			<div class="col-xs-4 col-md-4">
				<div class="form-group">
					<label><?php print SB_Text::_('Last Name', 'customers'); ?></label>
					<?php print SB_HtmlBuilder::writeInput('text', 'last_name', 
															isset($customer) ? $customer->last_name : SB_Request::getString('last_name'), 
															'last_name', array('class' => 'form-control')); ?>
				</div>
				<div class="form-group">
					<label><?php print SB_Text::_('Mobile Telephone', 'customers'); ?></label>
					<?php print SB_HtmlBuilder::writeInput('text', 'mobil_telephone', 
																isset($customer) ? $customer->mobile : SB_Request::getString('mobil_telephone'), 
																'mobil_telephone', array('class' => 'form-control')); ?>
				</div>
			</div>
			<div class="col-xs-4 col-md-4">
				<div class="form-group">
					<label><?php print SB_Text::_('Customer Type', 'customers'); ?></label>
					<select name="customer_type_id" class="form-control">
					</select>
				</div>
				<div class="form-group">
					<label><?php print SB_Text::_('Email', 'customers'); ?></label>
						<?php print SB_HtmlBuilder::writeInput('text', 'email', 
																isset($customer) ? $customer->email : SB_Request::getString('email'), 
																'email', array('class' => 'form-control')); ?>
				</div>
			</div>
		</div>
		<H3 class="form-section-title"><?php _e('Business Information', 'customers'); ?></H3>
		<div class="row">
			<div class="col-xs-4 col-md-4">
				<div class="form-group">
					<label><?php print SB_Text::_('Company', 'mb_c'); ?></label>
						<?php print SB_HtmlBuilder::writeInput('text', 'meta[company]', 
																isset($customer) ? $customer->company : SB_Request::getArrayVar('meta', 'company'), 
																'company', array('class' => 'form-control')); ?>
				</div>
				<div class="form-group">
					<label><?php print SB_Text::_('NIT/RUC/NIF', 'customers'); ?></label>
					<?php print SB_HtmlBuilder::writeInput('text', 'meta[_nit_ruc_nif]', 
															isset($customer) ? $customer->_nit_ruc_nif : SB_Request::getArrayVar('meta', 'nit_ruc_nif'), 
															'nit_ruc_nif', array('class' => 'form-control')); ?>
				</div>
				<div class="form-group">
					<label><?php print SB_Text::_('Office Telephone', 'customers'); ?></label>
					<input type="text" name="meta[_office_telephone]" value="<?php print isset($customer) ? $customer->_office_telephone : ''; ?>"
						class="form-control" />
				</div>
			</div>
			<div class="col-xs-4 col-md-4">
				<div class="form-group">
					<label><?php print SB_Text::_('City', 'customers'); ?></label>
						<?php print SB_HtmlBuilder::writeInput('text', 'city', 
																isset($customer) ? $customer->city : SB_Request::getString('city'), 
																'city', array('class' => 'form-control')); ?>
				</div>
				<div class="form-group">
					<label><?php print SB_Text::_('Address', 'customers'); ?></label>
					<?php print SB_HtmlBuilder::writeInput('text', 'address', 
																	isset($customer) ? $customer->address_1 : SB_Request::getString('address'), 
																	'address', array('class' => 'form-control')); ?>
				</div>
				<div class="form-group">
					<label><?php _e('No. Exterior - Interior', 'customers'); ?></label>
					<input type="text" name="meta[_num_exterior_interior]" class="form-control" 
						value="<?php print isset($customer) ? $customer->_num_exterior_interior : ''; ?>" />
				</div>
			</div>
			<div class="col-xs-4 col-md-4">
				<div class="form-group">
					<label><?php print SB_Text::_('State', 'customers'); ?></label>
					<input type="text" name="meta[_state]" value="<?php print isset($customer) ? $customer->_state : ''; ?>" class="form-control" />
				</div>
				<div class="form-group">
					<label><?php print SB_Text::_('Country', 'customers'); ?></label>
					<select name="country_code" class="form-control">
						<option value="-1"><?php _e('-- country --', 'customers'); ?></option>
						<?php foreach(include INCLUDE_DIR . SB_DS . 'countries.php' as $code => $country): ?>
						<option value="<?php print $code; ?>" <?php print isset($customer) && $customer->country_code == $code ? 'selected' : ''; ?>><?php print $country; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="form-group">
					<label><?php _e('ZIP'); ?></label>
					<input type="text" name="zip" value="<?php print isset($customer) ? $customer->zip_code : ''; ?>" class="form-control" />
				</div>
			</div>
		</div>
		<?php /* 
		<div class="panel panel-primary">
			<div class="panel-heading">
				<?php print SB_Text::_('General Information')?>
			</div><!-- end class="panel-heading" -->
			<div class="panel-body">
				<?php SB_Module::do_action('before_customer_fields'); ?>
				
				
				<div class="form-group">
					<label><?php print SB_Text::_('Position', 'customers'); ?></label>
						<?php print SB_HtmlBuilder::writeInput('text', 'meta[position]', 
																isset($customer) ? $customer->position : SB_Request::getArrayVar('meta', 'position'), 
																'position', array('class' => 'form-control')); ?>
				</div>
				
				<div class="form-group">												
					<label><?php print SB_Text::_('Website', 'customers'); ?></label>
						<?php print SB_HtmlBuilder::writeInput('text', 'meta[website]', 
																isset($customer) ? $customer->website : SB_Request::getArrayVar('meta', 'website'), 
																'website', array('class' => 'form-control')); ?>
				</div>
				<div class="row-fluid">
					<span class="span2"><?php print SB_Text::_('Identity Document', 'customers'); ?></span>
					<span class="span3">
						<?php print SB_HtmlBuilder::writeInput('text', 'meta[identity_document]', 
																isset($customer) ? $customer->identity_document : SB_Request::getArrayVar('meta', 'identity_document'), 
																'identity_document', array('class' => 'input-medium')); ?></span>
					
				</div>
				
				<?php SB_Module::do_action('before_customer_notes_field', isset($customer) ? $customer : null); ?>
				<div class="row-fluid">
					<label><?php print SB_Text::_('Notes', 'mb_c'); ?></label>
					<textarea name="meta[notes]" class="form-control"><?php print isset($customer) ? $customer->notes : SB_Request::getString('notes'); ?></textarea>
				</div>
				<?php SB_Module::do_action('after_customer_fields', isset($customer) ? $customer : null); ?>
			</div>
			<?php SB_Module::do_action('customer_tabs', isset($customer) ? $customer : null); ?>
		</div><!-- end class="easyui-tabs" -->
		<div class="form-actions">
			<button class="btn btn-success" type="submit"><?php print SB_Text::_('Save'); ?></button>
			<a class="btn btn-danger" href="<?php print SB_Route::_('index.php?mod=customers'); ?>"><?php print SB_Text::_('Cancel'); ?></a>
			<?php SB_Module::do_action('customer_buttons'); ?>
		</div>
		*/?>
	</form>
	<script>
	jQuery(function()
	{
		jQuery('#identity_document').keyup(function()
		{
			jQuery('#nit_ruc_nif').val(this.value);
		});
	});
	</script>
</div>