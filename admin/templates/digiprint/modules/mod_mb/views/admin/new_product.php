<?php
if( isset($base_type) && $base_type == 'service' )
{
	include 'new_service.php';
	return true;	
}
?>
<div class="wrap">
	<h2 id="page-title">
		<?php if( !isset($the_product) ): _e('New Product / Stock', 'digiprint'); ?>
		<?php else: _e('Edit Product / Stock', 'digiprint'); ?>
		<?php endif; ?>
		<span class="pull-right">
			<a class="btn btn-gray btn-gray-cancel" href="<?php print SB_Route::_('index.php?mod=mb'); ?>"><?php print SB_Text::_('Cancel and Back', 'mb')?></a>
			<a href="javascript:;" class="btn btn-orange" onclick="document.getElementById('product-form').submit();">
				<span class="icon-save-white"></span><?php print SB_Text::_('Save and Add', 'mb'); ?>
			</a>
			<a href="javascript:;" class="btn btn-success" onclick="document.getElementById('product-form').submit();">
				<span class="icon-save-white"></span><?php print SB_Text::_('Save', 'mb'); ?>
			</a>
		</span>
	</h2>
		<form id="product-form" action="" method="post">
			<input type="hidden" name="mod" value="mb" />
			<input type="hidden" name="task" value="save" />
			<?php if( isset($the_product) ): ?>
			<input type="hidden" id="product_id" name="id" value="<?php print $the_product->product_id; ?>" />
			<?php endif; ?>
			<div class="row">
				<div class="col-md-3">
					<div class="form-group">
						<label><?php print SB_Text::_('Code', 'mb'); ?></label>
						<input type="text" id="product_code" name="product_code" value="<?php print isset($the_product) ? $the_product->product_code : ''; ?>" 
								class="form-control" />
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label><?php print SB_Text::_('Product Name', 'digiprint'); ?></label>
						<input type="text" id="product_name" name="product_name" value="<?php print isset($the_product) ? $the_product->product_name : ''; ?>" 
								class="form-control" />
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label class="control-label"><?php print SB_Text::_('Category:', 'mb'); ?></label>
						<select id="category_id" name="category_id" class="form-control">
							<option value="-1">-- <?php print SB_Text::_('category', 'mb'); ?> --</option>
							<?php if( isset($categories) ):foreach($categories as $cat): ?>
							<option value="<?php print $cat->category_id; ?>" 
								<?php print (isset($the_product) && in_array($cat->category_id, $the_product->categories_ids)) ? 'selected' : '';?>><?php print $cat->name; ?></option>
							<?php endforeach; endif;?>				
						</select>
					</div>
				</div>
			</div>
			<div class="control-group">
				<label><?php print SB_Text::_('Description', 'mb') ?></label>
				<textarea id="description" name="description" class="form-control"><?php print isset($the_product) ? $the_product->product_description : ''; ?></textarea>
			</div>
			<h2 class="text-center"><?php _e('Storage Settings', 'digiprint'); ?></h2>
			<div class="row">
				<div class="col-xs-3 col-md-3">
					<div class="form-group">
						<label><?php print SB_Text::_('Quantity', 'mb'); ?></label>
							<input type="number" min="0" name="product_quantity" value="<?php print isset($the_product) ? $the_product->product_quantity : '0'; ?>" 
								class="form-control" />
					</div>
					<span class="help-block"><?php _e('Real Stock in stock and this decreases as purchases', 'digiprint'); ?></span>
				</div>
				<div class="col-xs-3 col-md-3">
					<div class="form-group">
						<label><?php print SB_Text::_('Minimum Stock', 'mb'); ?></label>
						<input type="number" min="0" name="min_stock" value="<?php print isset($the_product) ? $the_product->min_stock : 0; ?>" class="form-control" />
					</div>
					<span class="help-block"><?php _e('Minimum stock for further processing purchase orders in the system.', 'digiprint'); ?></span>
				</div>
				<div class="col-xs-3 col-md-3">
					<div class="form-group">
						<label><?php print SB_Text::_('Stock Alert', 'digiprint'); ?></label>
						<input type="number" min="0" name="stock_alert" value="<?php print isset($the_product) ? $the_product->_stock_alert : 0; ?>" class="form-control" />
					</div>
					<span class="help-block">
						<?php _e('The system alerts by email whenever it is necessary to supply the inventory.', 'digiprint'); ?>
					</span>
				</div>
				<div class="col-xs-3 col-md-3">
					<div class="form-group">
						<label><?php print SB_Text::_('Unit Measure', 'digiprint'); ?></label>
						<select name="unit_measure" class="form-control">
							<option value="-1"><?php _e('-- unit measure --'); ?></option>
							<?php foreach($measurement_units as $unit): ?>
							<option value="<?php print $unit->measure_id; ?>" <?php print (isset($the_product) && $the_product->product_unit_measure == $unit->measure_id) ? 'selected' : ''; ?>><?php print $unit->name; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<span class="help-block">
						<?php _e('It refers to the unit to describe the product. Axis. 100 pieces or 20 meters canvas', 'digiprint');?>
					</span>
				</div>
			</div>
			<?php /*
			<ul id="product-tabs" class="nav nav-tabs">
			    <li class="active"><a href="#general-info" data-toggle="tab"><?php print SB_Text::_('General Information', 'mb_w'); ?></a></li>
			    <li><a href="#prices" data-toggle="tab"><?php print SB_Text::_('Prices', 'mb_w'); ?></a></li>
			    <li><a href="#warehouse" data-toggle="tab"><?php print SB_Text::_('Store', 'mb_w'); ?></a></li>
			    <li><a href="#images" data-toggle="tab"><?php print SB_Text::_('Images', 'mb_w'); ?></a></li>
			    <?php if( isset($the_product) ): ?>
			    <li><a href="#kardex" onclick="jQuery('#kardex-table').datagrid();"><?php print SB_Text::_('Kardex', 'mb'); ?></a></li>
			    <?php endif; ?>
			    <?php SB_Module::do_action('product_tabs'); ?>
		    </ul>
		    <div class="tab-content">
				<div class="tab-pane active" id="general-info">
					<div class="form-group clearfix">
						<label class="col-sm-2 control-label"><?php print SB_Text::_('Serial Number:', 'mb'); ?></label>
						<div class="col-sm-4">
							<input type="text" name="product_serial_number" value="<?php print isset($the_product) ? $the_product->product_serial_number : ''; ?>" 
								class="form-control" />
						</div>
					</div>
					<div class="form-group clearfix">
						<label class="col-sm-2 control-label"><?php print SB_Text::_('Barcode:', 'mb_w'); ?></label>
						<span class="col-sm-4">
							<input type="text" name="product_barcode" value="<?php print isset($the_product) ? $the_product->product_barcode : ''; ?>" class="form-control" />
						</span>
					</div>
					<div class="form-group clearfix">
						<label class="col-sm-2 control-label"><?php print SB_Text::_('Measure Unit', 'mb_w'); ?></label>
						<div class="col-sm-4">
							<input type="text" id="product_measure_unit" name="product_measure_unit" value="<?php print isset($the_product) ? $the_product->product_unit_measure : ''; ?>" 
								class="form-control" />
						</div>
					</div>
				</div>
				<div class="tab-pane" id="prices">
					<div class="form-horizontal">
						<div class="form-group clearfix">
							<label class="col-sm-1"><?php print SB_Text::_('Purchase Price', 'mb_w'); ?></label>
							<div class="col-sm-3">
							    <div class="input-group">
								    <span class="input-group-addon">$</span>
								    <input type="text" name="product_cost" value="<?php print isset($the_product) ? number_format($the_product->product_cost, 2) : '0'; ?>" class="form-control" />
								    <span class="input-group-addon">.00</span>
							    </div>
							</div>
						</div>
						<div class="form-group clearfix">
							<label class="col-sm-1"><?php print SB_Text::_('Sale Price', 'mb_w'); ?></label>
							<div class="col-sm-3">
							    <div class="input-group">
								    <span class="input-group-addon">$</span>
								    <input type="text" name="product_sale_price" value="<?php print isset($the_product) ? number_format($the_product->product_price, 2) : '0'; ?>" class="form-control" />
								    <span class="input-group-addon">.00</span>
							    </div>
							</div>
						</div>
						<div class="form-group clearfix">
							<label class="col-sm-1"><?php print SB_Text::_('Sale Price 2', 'mb_w'); ?></label>
							<div class="col-sm-3">
							    <div class="input-group">
								    <span class="input-group-addon">$</span>
								    <input type="text" name="product_sale_price_2" value="<?php print isset($the_product) ? number_format($the_product->product_price_2, 2) : '0'; ?>" class="form-control" />
								    <span class="input-group-addon">.00</span>
							    </div>
							</div>
						</div>
						<div class="form-group clearfix">
							<label class="col-sm-1"><?php print SB_Text::_('Discount', 'mb_w'); ?></label>
							<div class="col-sm-3">
								<input type="text" name="product_discount" value="" class="form-control" />
							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane" id="warehouse">
					<div class="form-horizontal">
						<div class="form-group clearfix">
							<label class="col-sm-1 control-label"><?php print SB_Text::_('Store', 'mb'); ?></label>
							<span class="col-sm-10">
								<select id="store_id" name="store_id" class="form-control">
									<option value="-1">-- <?php print SB_Text::_('store', 'mb'); ?> --</option>
									<?php foreach($stores as $store): ?>
									<option value="<?php print $store->store_id; ?>" <?php print (isset($the_product) && $the_product->store_id == $store->store_id) ? 'selected' : ''; ?>>
										<?php print $store->store_name; ?>
									</option>
									<?php endforeach; ?>
								</select>
							</span>
						</div>
						<div class="form-group">
							<label class="col-sm-1 control-label"><?php print SB_Text::_('Category:', 'mb'); ?></label>
							<div class="col-sm-10">
								<select id="category_id" name="category_id" class="form-control">
									<option value="-1">-- <?php print SB_Text::_('category', 'mb'); ?> --</option>
									<?php if( isset($categories) ):foreach($categories as $cat): ?>
									<option value="<?php print $cat->category_id; ?>" 
										<?php print (isset($the_product) && in_array($cat->category_id, $the_product->categories_ids)) ? 'selected' : '';?>><?php print $cat->name; ?></option>
									<?php endforeach; endif;?>				
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-1 control-label"><?php print SB_Text::_('Line:', 'mb'); ?></label>
							<div class="col-sm-10">
								<select name="line_id" class="form-control">
									<option value="-1">-- <?php print SB_Text::_('line', 'mb'); ?> --</option>
									<?php foreach($lines as $line): ?>
									<option value="<?php print $line->line_id; ?>" <?php print (isset($the_product) && $the_product->product_line_id == $line->line_id) ? 'selected' : ''; ?>>
										<?php print $line->line_name; ?>
									</option>
									<?php endforeach; ?>				
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-1 control-label"><?php print SBText::_('Type:', 'mb'); ?></label>
							<div class="col-sm-10">
								<select id="type_id" name="type_id" class="form-control">
									<option value="-1">-- <?php print SB_Text::_('type', 'mb'); ?> --</option>
									<?php foreach($types as $t): ?>
									<option value="<?php print $t->type_id; ?>" <?php print (isset($the_product) && $the_product->type_id == $t->type_id) ? 'selected' : ''; ?>>
										<?php print $t->type; ?>
									</option>
									<?php endforeach; ?>				
								</select>
							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane" id="images">
					 <div id="fine-uploader"></div>
					 <div id="product-gallery">
					 	<?php ;if( isset($images) ) foreach($images as $img): ?>
					 	<a href="" class="product-image"><img src="<?php printf("%s/%s", UPLOADS_URL, $img->thumbs[0]->file); ?>" alt="" class="img-circle" /></a>
					 	<?php endforeach; ?>
					 </div>
				</div><!-- end id="images" -->
				<?php if( isset($the_product) ): ?>
				<div id="kardex" class="tab-pane" >
					<div class="row-fluid">
						<?php print SB_HtmlBuilder::writeToolButton(SB_Route::_('index.php?mod=warehouse&task=products.export_kardex&pid='.$the_product->product_id.'&type=excel'), '', 'excel.png', '48x48', null, SB_Text::_('Export to Excel', 'mb_w')); ?>
						<?php print SB_HtmlBuilder::writeToolButton(SB_Route::_('index.php?mod=warehouse&task=products.export_kardex&pid='.$the_product->product_id.'&type=pdf'), '', 'adobe-reader.png', '48x48', null, SB_Text::_('Export to PDF', 'mb_w')); ?>
					</div>
					<table id="kardex-table" class="easyui-datagrid" title="<?php print SB_Text::_('Kardex', 'mb_w') ?>" data-options="singleSelect:true">
					<thead>
					<tr>
						<th data-options="field:'product_id',width:60,align:'center'"><?php print SB_Text::_('Number', 'mb_w');?></th>
						<th data-options="field:'transaction',width:200"><?php print SB_Text::_('Transaction', 'mb_w');?></th>
						<th data-options="field:'in',width:50,align:'center'"><?php print SB_Text::_('In', 'mb_w');?></th>
						<th data-options="field:'out',width:50,align:'center'"><?php print SB_Text::_('Out', 'mb_w');?></th>
						<th data-options="field:'unit_cost',width:60,align:'right'"><?php print SB_Text::_('Unit Cost', 'mb_w');?></th>
						<th data-options="field:'balance',width:60,align:'right'"><?php print SB_Text::_('Balance', 'mb_w');?></th>
						<th data-options="field:'date',width:150,align:'right'"><?php print SB_Text::_('Date', 'mb_w');?></th>
					</tr>
					</thead>
					<tbody>
					<?php $i = 1; foreach($the_product->kardex as $r): //print_r($r);?>
					<tr>
						<td><?php print $i; ?></td>
						<td><?php print ($r->transaction_type_id == -1) ? SB_Text::_('Inicial') : 
																	sprintf("[%s]%s (%s)", $r->transaction_key, $r->transaction_name, $r->in_out); ?>
						</td>
						<td><?php print (strtoupper(trim($r->in_out)) == 'IN' || $r->transaction_type_id == -1) ? $r->quantity : 0;?></td>
						<td><?php print (strtoupper(trim($r->in_out)) == 'OUT') ? $r->quantity : 0; ?></td>
						<td><?php print number_format($r->unit_price, 2); ?></td>
						<td><?php print $r->quantity_balance; ?></td>
						<td><?php print $r->creation_date; ?></td>
					</tr>
					<?php $i++; endforeach; ?>
					</tbody>
					</table>
				</div><!-- end id="kardex" -->
				<?php endif;
				<?php SB_Module::do_action('product_tabs_content'); ?>
			</div>*/ ?>
		</form>
	<script>
	function validate_form_product()
	{
		if( jQuery('#product_name').val().length <= 0 )
		{
			alert('Ingrese un nombre para el producto');
			return false;
		}
		/*
		if( jQuery('#product_measure_unit').val().length <= 0 )
		{
			alert('Ingrese la unidad de medida para el producto');
			return false;
		}
		*/
		if( jQuery('#store_id').val().length <= 0 || jQuery('#store_id').val() == -1 )
		{
			alert('Ingrese la unidad de medida para el producto');
			return false;
		}
		return true;
	}
	jQuery('#product-tabs a').click(function (e) 
	{
	    e.preventDefault();
	    jQuery(this).tab('show');
	    if( jQuery(this).attr('href') == '#kardex' )
	    {
	    	jQuery('#kardex-table').datagrid();
		}
	});
	jQuery(function()
	{
		jQuery('#store_id').change(function()
		{
			if( this.value <= 0 )
				return false;
			var cats = jQuery('#category_id');
			var types = jQuery('#type_id');
			cats.html('');
			types.html('');
			jQuery.get('index.php?mod=mb&task=ajax&action=get_store_cats&store_id='+this.value, function(res)
			{
				cats.append('<option value="-1">-- category --</option>');
				if( res.status == 'ok' )
				{
					jQuery.each(res.categories, function(i, cat)
					{
						var op = jQuery('<option value="'+cat.category_id+'">'+cat.name+'</option>');
						cats.append(op);
					});
					
				}
			});
			jQuery.get('index.php?mod=mb&task=ajax&action=get_store_types&store_id='+this.value, function(res)
			{
				types.append('<option value="-1">-- type --</option>');
				if( res.status == 'ok' )
				{
					jQuery.each(res.types, function(i, type)
					{
						var op = jQuery('<option value="'+type.type_id+'">'+type.type+'</option>');
						types.append(op);
					});
					
				}
			});
		});
		/*
		jQuery('#fine-uploader').fineUploader(
			{
				request: 
				{
					endpoint: 'index.php?mod=mb&task=handle_ajax_upload'
				},
				validation: 
				{
					acceptFiles: 'gif,jpeg,jpg,png,bmp',
			        allowedExtensions: ["gif", "jpeg", "jpg", "png", 'bmp']
				}
			}
		).on('complete', function(event, id, name, responseJSON)
		{
			console.log('id:'+id);
			console.log('name:'+name);
			console.log(responseJSON);
			var product_id = (jQuery('#product_id').length > 0) ? jQuery('#product_id').val() : false;
			//send ajax request to resize image and attach to product
			var params = 'mod=warehouse&task=products.attach_image&image='+responseJSON.uploadName;
			if( product_id )
			{
				params += '&product_id='+product_id;
			}
			jQuery.post('<?php print SB_Route::_('index.php')?>', params, function(res)
			{
				if( res.status == 'ok' )
				{
					jQuery('#product-gallery').append('<a href="javascript:;" class="product-image"><img class="img-circle" src="'+res.resized_image_url+'" alt="" /></a>');
				}
				else
				{
					alert(res.error);
				}
			});
		});
		*/
		jQuery('#product-form').submit(validate_form_product);
	});
	</script>
</div>