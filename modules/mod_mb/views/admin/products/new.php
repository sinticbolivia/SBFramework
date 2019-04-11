<?php
$view_cost = $user->can('mb_can_see_cost');
?>
<div class="wrap">
	<h2 id="page-title">
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                <?php print $title; ?>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                <div class="text-right">
                    <a class="btn btn-danger" href="<?php print isset($search_link) ? $search_link : b_route('index.php?mod=mb'); ?>">
                        <?php _e('Cancel', 'mb')?>
                    </a>
                    <?php if( $user->can('mb_edit_product') || $user->can('mb_create_product') ): ?>
                    <button class="btn btn-success" onclick="jQuery('#product-form').submit();">
						<?php _e('Save', 'mb'); ?></button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </h2>
		<form id="product-form" action="" method="post">
			<input type="hidden" name="mod" value="mb" />
			<input type="hidden" name="task" value="save" />
			<?php if( isset($base_type) ): ?>
			<input type="hidden" name="base_type" value="<?php print $base_type; ?>" />
			<?php endif; ?>
			<?php if( isset($the_product) ): ?>
			<input type="hidden" id="product_id" name="id" value="<?php print $the_product->product_id; ?>" />
			<?php endif; ?>
			<div class="form-group">
				<label><?php _e('Code', 'mb'); ?></label>
				<input type="text" id="product_code" name="product_code" value="<?php print isset($the_product) ? $the_product->product_code : ''; ?>" 
						class="form-control" />
			</div>
			<?php //endif; ?>
			<div class="form-group">
				<label><?php _e('Name', 'mb'); ?></label>
				<input type="text" id="product_name" name="product_name" value="<?php print isset($the_product) ? $the_product->product_name : ''; ?>" 
						class="form-control" />
			</div><br/>
			<ul id="product-tabs" class="nav nav-tabs">
			    <li class="active"><a href="#general-info" data-toggle="tab"><?php _e('General Information', 'mb'); ?></a></li>
			    <li><a href="#prices" data-toggle="tab"><?php _e('Prices', 'mb'); ?></a></li>
			    <li><a href="#warehouse" data-toggle="tab"><?php _e('Store', 'mb'); ?></a></li>
			    <li><a href="#images" data-toggle="tab"><?php _e('Images', 'mb'); ?></a></li>
			    <?php /* if( isset($the_product) ): ?>
			    <li><a href="#kardex" onclick="jQuery('#kardex-table').datagrid();"><?php print SB_Text::_('Kardex', 'mb'); ?></a></li>
			    <?php endif;*/ ?>
			    <?php b_do_action('product_tabs'); ?>
		    </ul>
		    <div class="tab-content">
				<div class="tab-pane active" id="general-info">
					<?php b_do_action('mb_product_general_info_before', isset($the_product) ? $the_product : null); ?>
					<div class="form-group">
						<label><?php _e('Description', 'mb') ?></label>
						<textarea id="description" name="description" class="form-control"><?php print isset($the_product) ? $the_product->product_description : ''; ?></textarea>
					</div>
					<?php b_do_action('mb_product_after_description', isset($the_product) ? $the_product : null); ?>
					<div class="row">
						<div class="col-xs-12 col-md-6 form-horizontal">
							<div class="form-group clearfix">
								<label class="col-sm-5 control-label"><?php _e('Serial Number:', 'mb'); ?></label>
								<div class="col-sm-7">
									<input type="text" name="product_serial_number" value="<?php print isset($the_product) ? $the_product->product_number : ''; ?>" 
										class="form-control" />
								</div>
							</div>
							<div class="form-group clearfix">
								<label class="col-sm-5 control-label"><?php _e('Barcode:', 'mb_w'); ?></label>
								<span class="col-sm-7">
                                    <div class="input-group">
                                        <input type="text" id="product_barcode" name="product_barcode" value="<?php print isset($the_product) ? $the_product->product_barcode : ''; ?>" class="form-control" />
                                        <?php if( isset($the_product) ): ?>
                                        <div class="input-group-btn">
                                            <a href="javascript:;" id="btn-generate-barcode" class="btn btn-default" title="<?php _e('Generate barcode', 'mb'); ?>">
                                                <span class="glyphicon glyphicon-cog"></span>
                                            </a>
                                        </div>
                                        <?php endif; ?>
                                    </div>
								</span>
							</div>
							<div class="form-group clearfix">
								<label class="col-sm-5 control-label"><?php _e('Measure Unit', 'mb'); ?></label>
								<div class="col-sm-7">
									<select id="unit_measure" name="unit_measure" class="form-control">
										<option value="-1"><?php _e('-- measure unit --', 'mb'); ?></option>
										<?php foreach($measurement_units as $unit): ?>
										<option value="<?php print $unit->measure_id; ?>" 
											<?php print isset($the_product) && $the_product->product_unit_measure == $unit->measure_id ? 'selected' : ''; ?>>
											<?php print $unit->name; ?>
										</option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
                            <?php b_do_action('mb_product_general_left_fields', isset($the_product) ? $the_product : null ); ?>
						</div>
						<div class="col-xs-12 col-md-6 form-horizontal">
							<div class="form-group clearfix">
								<label class="col-sm-5 control-label"><?php _e('Batch:', 'mb'); ?></label>
								<div class="col-sm-7">
									<input type="text" name="meta[_batch]" value="<?php print isset($the_product) ? $the_product->_batch: ''; ?>" 
										class="form-control" />
								</div>
							</div>
							<div class="form-group clearfix">
								<label class="col-sm-5 control-label"><?php _e('Expiration Date:', 'mb'); ?></label>
								<div class="col-sm-7">
									<input type="text" name="meta[_expiration_date]" value="<?php print isset($the_product) ? sb_format_date($the_product->_expiration_date) : ''; ?>" 
										class="form-control datepicker" />
								</div>
							</div>
                            <?php b_do_action('mb_product_general_right_fields', isset($the_product) ? $the_product : null ); ?>
						</div>
					</div>
					
					<div class="form-group clearfix">
						<label class="col-sm-2"><?php _e('For Sale', 'mb'); ?></label>
						<div class="col-sm-4">
							<input type="checkbox" name="for_sale" value="1" <?php print isset($the_product) && $the_product->for_sale == 1 ? 'checked' : ''; ?>>
						</div>
					</div>
					<?php b_do_action('mb_product_general_info_after', isset($the_product) ? $the_product : null); ?>
					<div class="clearfix"></div>
				</div>
				<div class="tab-pane" id="prices">
					<div class="form-horizontal">
						<div class="form-group clearfix">
							<label class="col-sm-2"><?php _e('Purchase Price', 'mb'); ?></label>
							<div class="col-sm-3">
							    <div class="input-group">
								    <span class="input-group-addon">$</span>
								    <input type="text" name="product_cost" 
                                        value="<?php print isset($the_product) && $view_cost ? number_format($the_product->product_cost, 2) : '0'; ?>" class="form-control" />
								    <span class="input-group-addon">.00</span>
							    </div>
							</div>
						</div>
						<div class="form-group clearfix">
							<label class="col-sm-2"><?php _e('Sale Price', 'mb'); ?></label>
							<div class="col-sm-3">
							    <div class="input-group">
								    <span class="input-group-addon">$</span>
								    <input type="text" name="product_sale_price" value="<?php print isset($the_product) ? number_format($the_product->product_price, 2) : '0'; ?>" class="form-control" />
								    <span class="input-group-addon">.00</span>
							    </div>
							</div>
						</div>
						<div class="form-group clearfix">
							<label class="col-sm-2"><?php _e('Sale Price 2', 'mb'); ?></label>
							<div class="col-sm-3">
							    <div class="input-group">
								    <span class="input-group-addon">$</span>
								    <input type="text" name="product_sale_price_2" value="<?php print isset($the_product) ? number_format($the_product->product_price_2, 2) : '0'; ?>" class="form-control" />
								    <span class="input-group-addon">.00</span>
							    </div>
							</div>
						</div>
						<div class="form-group clearfix">
							<label class="col-sm-2"><?php _e('Sale Price 3', 'mb'); ?></label>
							<div class="col-sm-3">
							    <div class="input-group">
								    <span class="input-group-addon">$</span>
								    <input type="text" name="product_sale_price_3" value="<?php print isset($the_product) ? number_format($the_product->product_price_3, 2) : '0'; ?>" class="form-control" />
								    <span class="input-group-addon">.00</span>
							    </div>
							</div>
						</div>
						<div class="form-group clearfix">
							<label class="col-sm-2"><?php _e('Sale Price 4', 'mb'); ?></label>
							<div class="col-sm-3">
							    <div class="input-group">
								    <span class="input-group-addon">$</span>
								    <input type="text" name="product_sale_price_4" value="<?php print isset($the_product) ? number_format($the_product->product_price_4, 2) : '0'; ?>" class="form-control" />
								    <span class="input-group-addon">.00</span>
							    </div>
							</div>
						</div>
						<div class="form-group clearfix">
							<label class="col-sm-2"><?php _e('Discount', 'mb'); ?></label>
							<div class="col-sm-3">
								 <div class="input-group">
								 	<input type="text" name="product_discount" value="<?php print isset($the_product) ? $the_product->_discount : ''; ?>" class="form-control" />
								 	<span class="input-group-addon">%</span>
								 </div>
							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane" id="warehouse">
					<div class="form-horizontal">
						<div class="form-group clearfix">
							<label class="col-sm-1 control-label"><?php _e('Store', 'mb'); ?></label>
							<span class="col-sm-4">
								<select id="store_id" name="store_id" class="form-control">
									<option value="-1">-- <?php _e('store', 'mb'); ?> --</option>
									<?php foreach($stores as $store): ?>
									<option value="<?php print $store->store_id; ?>" <?php print (isset($the_product) && $the_product->store_id == $store->store_id) ? 'selected' : ''; ?>>
										<?php print $store->store_name; ?>
									</option>
									<?php endforeach; ?>
								</select>
							</span>
						</div>
						<div class="form-group">
							<label class="col-sm-1 control-label"><?php _e('Category:', 'mb'); ?></label>
							<div class="col-sm-10">
								<div class="form-control" style="width:100%;height:200px;overflow:auto;">
									<?php print mb_dropdown_categories(['checkbox' 	=> true, 
																		'selected' 	=> isset($the_product) ? $the_product->categories_ids : null,
																		'store_id'	=> isset($the_product) && $the_product->store_id ? (int)$the_product->store_id : null
											]); ?>
									<?php /*if( isset($categories) ):foreach($categories as $cat): ?>
									<div><label>
										<input type="checkbox" name="cats[]" value="<?php print $cat->category_id ?>" 
											class="" 
											<?php print (isset($the_product) && in_array($cat->category_id, $the_product->categories_ids)) ? 'checked' : ''; ?> />
										<?php print $cat->name ?>
									</label></div>
									<?php endforeach; endif; */?>
								</div>
								<?php /*
								<select id="category_id" name="category_id" class="form-control">
									<option value="-1">-- <?php _e('category', 'mb'); ?> --</option>
									<?php if( isset($categories) ):foreach($categories as $cat): ?>
									<option value="<?php print $cat->category_id; ?>" 
										<?php print (isset($the_product) && in_array($cat->category_id, $the_product->categories_ids)) ? 'selected' : '';?>><?php print $cat->name; ?></option>
									<?php endforeach; endif;?>				
								</select>
								*/?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-1 control-label"><?php _e('Line:', 'mb'); ?></label>
							<div class="col-sm-10">
								<select name="line_id" class="form-control">
									<option value="-1">-- <?php _e('line', 'mb'); ?> --</option>
									<?php if( isset($lines) && is_array($lines) ): foreach($lines as $line): ?>
									<option value="<?php print $line->line_id; ?>" <?php print (isset($the_product) && $the_product->product_line_id == $line->line_id) ? 'selected' : ''; ?>>
										<?php print $line->line_name; ?>
									</option>
									<?php endforeach; endif;  ?>				
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-1 control-label"><?php _e('Type:', 'mb'); ?></label>
							<div class="col-sm-10">
								<select id="type_id" name="type_id" class="form-control">
									<option value="-1">-- <?php _e('type', 'mb'); ?> --</option>
									<?php foreach($types as $t): ?>
									<option value="<?php print $t->type_id; ?>" <?php print (isset($the_product) && $the_product->type_id == $t->type_id) ? 'selected' : ''; ?>>
										<?php print $t->type; ?>
									</option>
									<?php endforeach; ?>				
								</select>
							</div>
						</div>
					</div>
					<fieldset>
						<legend><?php _e('Warehouses', 'mb'); ?></legend>
						<div id="warehouses-container" class="row">
							<?php foreach($warehouse_list as $i => $w): ?>
								<?php include 'warehouses-list.php' ?>
							<?php endforeach; ?>
						</div>
					</fieldset>
				</div>
				<div class="tab-pane" id="images">
					 <div id="uploader"></div>
					 <div id="product-gallery">
					 	<?php if( isset($the_product) ): foreach($the_product->getImages() as $img): ?>
					 	<div class="product-image">
					 		<img src="<?php print $img->GetUrl('330x330'); ?>" alt="" class="img-responsive thumbnail" />
							<div class="buttons">
								<a href="javascript:;" class="btn btn-default btn-xs btn-delete-img" 
									title="<?php _e('Remove image', 'mb'); ?>"
									data-id="<?php print $img->id ?>"
									data-product_id="<?php print $the_product->product_id ?>">
									<span class="glyphicon glyphicon-trash"></span>
								</a>
								<a href="javascript:;" class="btn btn-default btn-xs btn-set-featured-img" 
									style="<?php print $img->id == (int)$the_product->_featured_image_id  ? 'display:none;' : ''; ?>"
									title="<?php _e('Mark as featured image', 'mb'); ?>"
									data-id="<?php print $img->id ?>"
									data-product_id="<?php print $the_product->product_id ?>">
									<span class="glyphicon glyphicon-check"></span>
								</a>
							</div>
							<?php if( (int)$the_product->_featured_image_id && $img->id == $the_product->_featured_image_id ): ?>
							<span class="featured"><?php _e('Featured Image', 'mb'); ?></span>
							<?php endif; ?>
					 	</div>
					 	<?php endforeach; endif;?>
					 </div>
				</div><!-- end id="images" -->
				<?php /*if( isset($the_product) ): ?>
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
				<?php endif; */?>
				<?php b_do_action('product_tabs_content', isset($the_product) ? $the_product : null); ?>
			</div>
			<?php isset($base_type) ? b_do_action('mb_product_base_type_'.$base_type, isset($the_product) ? $the_product : null) : null; ?>
			<?php b_do_action('mb_product_after_tabs', isset($the_product) ? $the_product : null); ?>
			<br/>
			<div class="form-group">
				<a class="btn btn-danger" href="<?php print isset($search_link) ? $search_link : b_route('index.php?mod=mb'); ?>">
					<?php _e('Cancel', 'mb')?>
				</a>
				<?php if( $user->can('mb_edit_product') || $user->can('mb_create_product') ): ?>
				<button class="btn btn-success"><?php _e('Save', 'mb'); ?></button>
				<?php endif; ?>
			</div>
		</form>
	<script>
	jQuery(function()
	{
		<?php if( isset($the_product) ): ?>
		jQuery('#btn-generate-barcode').click(function()
		{
			jQuery.get('index.php?mod=mb&task=ajax&action=generate_barcode&id=<?php print $the_product->product_id; ?>', function(res)
			{
				jQuery('#product_barcode').val(res.barcode);
			});
			return false;
		});
		<?php endif; ?>
		
		//##build uploader
		window.uploader = new qq.FineUploader({
			autoUpload: true,
			element: document.getElementById("uploader"),
			template: 'qq-template',
			//button: document.getElementById('select-banner'),
			request: {
				endpoint: '<?php print $upload_endpoint; ?>'
			},
			form: {
				element: 'form-upload'
			},
			validation: {
				allowedExtensions: <?php print json_encode($extensions); ?>
			},
			callbacks: 
			{
				onSubmit: function(id, fileName) 
				{
				},
				onUpload: function(id, fileName) 
				{
					jQuery('.progress .progress-bar').css('width', '0px');
				},
				onProgress: function(id, fileName, loaded, total) 
				{
					var progress = parseInt((loaded * 100) / total);
					var uploading_text = '<?php print __('Uploading file {0}', 'mb'); ?>';
					
					jQuery('.progress .progress-bar').css('width', progress + '%');
					jQuery('.progress .progress-bar .text').html(uploading_text.replace('{0}', progress + '%'))
				},
				onComplete: function(id, fileName, res) 
				{
					jQuery('#uploading').css('display', 'none');
					if (res.success) 
					{
						var img = jQuery('<a href="javascript:;" class="product-image"><img src="'+res.url+'" alt="" class="img-responsive thumbnail" /></a>');
						jQuery('#product-gallery').append(img);
		            } 
		            else 
					{
						alert(responseJSON.error);
		            }
				}
			}
		});
	});
	</script>
</div>
<script type="text/template" id="qq-template">
<div class="qq-uploader-selector qq-uploader" qq-drop-area-text="Drop files here">
	<div id="drag-drop-box" class="drag-drop-box qq-upload-drop-area-selector qq-upload-drop-area">
		<span class="qq-upload-drop-area-text-selector"></span>
		<span><?php _e('Drop your files here', 'mb'); ?></span>
		<div class="qq-upload-button-selector qq-upload-button">
			<div class="btn btn-primary"><?php _e('Upload a file', 'mb'); ?></div>
		</div>
	</div>
	<!--
	<div class="progress qq-total-progress-bar-container-selector qq-total-progress-bar-container">
		<div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="progress-bar qq-total-progress-bar-selector qq-progress-bar qq-total-progress-bar">
			<div class="text"><?php _e('Uploading File 65%', 'mb'); ?></div>
		</div>
	</div>
	-->
	<div class="progress">
		<div role="progressbar" class="progress-bar">
			<div class="text"><?php _e('Uploading File 65%', 'storage'); ?></div>
		</div>
	</div>
	<!--
	<span class="qq-drop-processing-selector qq-drop-processing">
		<span>Processing dropped files...</span>
		<span class="qq-drop-processing-spinner-selector qq-drop-processing-spinner"></span>
	</span>
	-->
	<ul style="display:none;" class="qq-upload-list-selector qq-upload-list" aria-live="polite" aria-relevant="additions removals">
		<li>
			<div class="qq-progress-bar-container-selector">
				<div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="qq-progress-bar-selector qq-progress-bar"></div>
			</div>
			<span class="qq-upload-spinner-selector qq-upload-spinner"></span>
			<span class="qq-upload-file-selector qq-upload-file"></span>
			<span class="qq-edit-filename-icon-selector qq-edit-filename-icon" aria-label="Edit filename"></span>
			<input class="qq-edit-filename-selector qq-edit-filename" tabindex="0" type="text">
			<span class="qq-upload-size-selector qq-upload-size"></span>
			<button type="button" class="qq-btn qq-upload-cancel-selector qq-upload-cancel">Cancel</button>
			<button type="button" class="qq-btn qq-upload-retry-selector qq-upload-retry">Retry</button>
			<button type="button" class="qq-btn qq-upload-delete-selector qq-upload-delete">Delete</button>
			<span role="status" class="qq-upload-status-text-selector qq-upload-status-text"></span>
		</li>
	</ul>
	<!--
	<dialog class="qq-alert-dialog-selector">
                <div class="qq-dialog-message-selector"></div>
                <div class="qq-dialog-buttons">
                    <button type="button" class="qq-cancel-button-selector">Close</button>
                </div>
	</dialog>
	<dialog class="qq-confirm-dialog-selector">
                <div class="qq-dialog-message-selector"></div>
                <div class="qq-dialog-buttons">
                    <button type="button" class="qq-cancel-button-selector">No</button>
                    <button type="button" class="qq-ok-button-selector">Yes</button>
                </div>
	</dialog>
	<dialog class="qq-prompt-dialog-selector">
                <div class="qq-dialog-message-selector"></div>
                <input type="text">
                <div class="qq-dialog-buttons">
                    <button type="button" class="qq-cancel-button-selector">Cancel</button>
                    <button type="button" class="qq-ok-button-selector">Ok</button>
                </div>
	</dialog>
	-->
</div>
</script>
