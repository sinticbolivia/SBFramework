<?php
//##reset session variations
SB_Session::unsetVar('variations');
$dp_colors = dp_get_colores();
?>
<div class="wrap">
	<form id="form-order" action="" method="post" class="avoid-submission" >
		<input type="hidden" name="mod" value="mb" />
		<input type="hidden" name="task" value="orders.save" />
		<?php if( isset($order) ): ?>
		<input type="hidden" name="id" value="<?php print $order->order_id; ?>" />
		<?php endif; ?>
		<h2>
			<?php print $title; ?>
			<span class="pull-right">
				<a href="<?php print SB_Route::_('index.php?mod=mb&view=orders.default'); ?>" class="btn btn-danger">
					<span class="glyphicon glyphicon-remove"></span> <?php print SBText::_('Cancel', 'mb'); ?>
				</a>
				<?php if( isset($order) ): ?>
				<a href="<?php print SB_Route::_('index.php?mod=mb&view=orders.print_receipt&id='.$order->order_id); ?>" class="btn btn-warning" target="_blank">
					<span class="glyphicon glyphicon-print"></span> <?php print SBText::_('Print', 'mb'); ?>
				</a>
				<?php endif; ?>
				<button type="submit" class="btn btn-success">
					<span class="glyphicon glyphicon-save"></span> <?php print SBText::_('Save', 'mb'); ?>
				</button>
			</span>
		</h2>
		<fieldset>
			<legend><?php _e('General Details', 'quotes'); ?></legend>
			<div class="row">
				<div class="col-md-7">
					<div class="row">
						<div class="col-md-2">
							<div class="form-group">
								<label class="control-label"><?php print SBText::_('ID', 'mb'); ?></label>
								<input type="text" id="customer_id" name="customer_id" value="<?php print isset($order) ? $order->customer_id : ''; ?>" 
                                       class="form-control" autocomplete="off" />
							</div>
						</div>
						<div class="col-md-7">
							<div class="form-group">
								<label class="control-label"><?php print SBText::_('Customer Name', 'mb'); ?></label>
								<input type="text" id="customer" name="customer" 
									value="<?php print isset($order) ? $order->customer->first_name . ' ' . $order->customer->last_name : ''; ?>" class="form-control" />
							</div>
						</div>
						<div class="">
							<div class="form-group">
								<label>&nbsp;</label>
								<div>
									<a href="#" id="btn-search-customer" class="btn btn-gray" title="<?php _e('Search customer', 'mb'); ?>"
										data-toggle="modal" data-target="#search-customer-modal">
										<span class="glyphicon glyphicon-search"></span>
									</a>
									<a href="#" id="btn-add-customer" class="btn btn-gray" title="<?php _e('Create customer', 'mb'); ?>"
										data-toggle="modal" data-target="#create-customer-modal">
										<span class="glyphicon glyphicon-user"></span>
									</a>
								</div>
							</div>
							
						</div>
					</div>
					<div class="row">
						<div class="col-md-12"><p id="customer-address"><?php isset($order) ? printf("%s,%s,%s,%s", 
																										$order->customer->address_1,
																										$order->customer->city,
																										$order->customer->country,
																										$order->customer->zip_code) : ''; ?></p></div>
					</div>
					<div class="row">
						<div class="col-md-4"><b id="customer-rfc"><?php print isset($order) ? $order->customer->_nit_ruc_nif : ''; ?></b></div>
						<div class="col-md-5"><b id="customer-email"><?php print isset($order) ? $order->customer->email : ''; ?></b></div>
						<div class="col-md-3"><b id="customer-phone"><?php print isset($order) ? $order->customer->phone : ''; ?></b></div>
					</div>
					<div class="row">
						<div class="col-xs-4 col-md-4">
							<div class="form-group">
								<label><?php _e('Sucursal', 'digiprint'); ?></label>
								<select id="store_id" name="store_id" class="form-control">
									<option value="-1"><?php _e('-- sucursal --', 'digiprint'); ?></option>
									<?php foreach($stores as $s): ?>
									<option value="<?php print $s->store_id; ?>" <?php print isset($order) && $order->store_id == $s->store_id ? 'selected' : ''; ?>>
										<?php print $s->store_name; ?>
									</option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div class="col-xs-4 col-md-4">
							<div class="form-group">
								<label><?php _e('Apply Promo', 'quotes'); ?></label>
								<select name="coupon_id" class="form-control">
									<option value="-1"><?php _e('-- coupon --', 'mb'); ?></option>
									<?php foreach($coupons as $c): ?>
									<option value="<?php print $c->coupon_id; ?>"><?php print $c->description; ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div class="col-xs-4 col-md-4">
							<div class="form-group">
								<label><?php _e('Taxes', 'quotes'); ?></label>
								<select name="tax_id" class="form-control">
									<option value="-1"><?php _e('-- taxes --', 'mb'); ?></option>
									<?php foreach($taxes as $t): ?>
									<option value="<?php print $t->tax_id; ?>">
										<?php printf("%s (%.2f)", $t->name, $t->rate); ?>
									</option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-5">
					<div class="text-right">
						<?php _e('Order Num.', 'mb');?><br/>
						<span id="order-number" class="text-red">
                            <b><?php print isset($order) ? $order->code : ''; ?></b>
                        </span>
					</div>
					<table id="table-totals" class="totals-table" style="width:100%;">
					<tr>
						<th class="text-right"><?php _e('Sub Total:', 'mb'); ?></th>
						<td class="text-right"><span id="quote-subtotal"><?php print isset($order) ? $order->subtotal : 0.00; ?></span></td>
					</tr>
					<tr>
						<th class="text-right"><?php _e('Discount:', 'mb'); ?></th>
						<td class="text-right"><span id="quote-discount"><?php print isset($order) ? $order->discount : 0.00; ?></span></td>
					</tr>
					<tr>
						<th class="text-right"><?php _e('Tax:', 'mb'); ?></th>
						<td class="text-right"><span id="quote-tax"><?php print isset($order) ? $order->total_tax : 0.00; ?></span></td>
					</tr>
					<tr>
						<th class="text-right"><b><?php _e('Advance:', 'quotes'); ?></b></th>
						<td class="text-right"><b><span id="invoice-total">0.00</span></b></td>
					</tr>
					<tr id="row-total">
						<th class="text-right"><?php _e('Grand Total:', 'mb'); ?></th>
						<td class="text-right"><span id="quote-total"><?php print isset($order) ? $order->total : 0.00; ?></span></td>
					</tr>
					</table>
				</div>
			</div>
		</fieldset><br/>
		<div class="form-group">
			<div class="input-group">
				<input type="text" id="search_product" name="search_product" value="" placeholder="<?php _e('Search product', ',mb'); ?>" class="form-control" />
				<span class="input-group-btn">
		        	<button id="btn-add-item" class="btn btn-default" type="button"><?php print SBText::_('Add item', 'mb'); ?></button>
		      	</span>
			</div>
		</div>
		<table id="quote-table" class="table">
			<thead>
			<tr>
				<th class="text-center"><?php _e('No.', 'mb'); ?></th>
				<th class="text-center"><?php print SBText::_('Code', 'mb'); ?></th>
				<th class="column-product"><?php print SBText::_('Product', 'mb'); ?></th>
				<th class="column-qty"><?php print SBText::_('Quantity', 'mb'); ?></th>
				<th class="column-price text-right"><?php print SBText::_('Price', 'mb'); ?></th>
				<th class="column-total text-right"><?php print SBText::_('Total', 'mb'); ?></th>
				<th>&nbsp;</th>
			</tr>
			</thead>
			<tbody>
			<?php if( isset($order) ): $i = 1; foreach($order->GetItems() as $item): ?>
			<tr data-id="<?php print $item->product_id; ?>" data-item_id="<?php print $item->item_id; ?>">
				<td><?php print $i; ?><input type="hidden" name="item[<?php print $i - 1; ?>][product_id]" value="<?php print $item->product_id; ?>" /></td>
				<td><input type="text" name="item[<?php print $i - 1; ?>][code]" value="<?php print $item->product_code; ?>" class="form-control" /></td>
				<td class="column-product"><input type="text" name="item[<?php print $i - 1; ?>][name]" value="<?php print $item->name ?>" class="form-control" /></td>
				<td class="column-qty text-center">
					<input type="number" min="1" name="item[<?php print $i - 1; ?>][qty]" value="<?php print $item->quantity ?>" class="form-control item-qty" />
				</td>
				<td class="column-price"><input type="text" name="item[<?php print $i - 1; ?>][price]" value="<?php print $item->price ?>" class="form-control item-price" /></td>
				<td><?php print $item->total ?></td>
				<td class="column-actions">
                    <a href="javascript:;" class="configure-item btn btn-default btn-sm"><span class="glyphicon glyphicon-cog"></span></a>
                    <a href="javascript:;" class="remove-item btn btn-default btn-sm"><span class="glyphicon glyphicon-trash"></span></a>
                </td>
			</tr>
			<?php if( $variations = mb_get_order_item_meta($item->item_id, '_variations') ): ?>
			<?php foreach($variations as $vindex => $var): if($vindex == 'product_id') continue; ?>
				<?php foreach($var as $var_num => $vi): ?>
				<tr class="variation product-<?php print $item->product_id; ?>-variations-<?php print $vindex; ?>">
					<td></td>
					<td><?php printf("Cantidad: %d", $vi->quantity); ?></td>
					<td>
						<?php 
						$label = array_search($vi->color, $dp_colors); 
						?>
						<span class="status-color" style="background-color:<?php print $vi->color; ?>"></span>
						<?php print $label; ?>
					</td>
					<td colspan="4">
						<?php _e('Archivo', 'digiprint'); ?>
					</td>
				</tr>
				<?php endforeach; ?>
			<?php endforeach; ?>
			<?php endif; ?>
			<?php $i++; endforeach; endif; ?>
			</tbody>
		</table><!-- end id="quote-table" -->
		<br/>
		<hr class="ht--"/>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label><?php _e('Order Comments', 'mb'); ?></label>
					<textarea name="notes" class="form-control"><?php print isset($order) ? $order->details : ''; ?></textarea>
				</div>
			</div>
			<div class="col-md-2">
				<div class="form-group">
					<label><?php _e('Status', 'mb'); ?></label>
					<select name="status" class="form-control selectpicker">
						<?php foreach($order_status as $status => $label): ?>
						<?php 
						$status_color = "<span class='status-color $status'>&nbsp;</span>$label";
						?>
						<option value="<?php print $status; ?>" data-content="<?php print $status_color; ?>"><?php print $label; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<div class="col-md-2">
				<div class="form-group">
					<label><?php _e('Delivery date', 'digiprint'); ?></label>
					<input name="delivery_date" value="<?php print isset($order) ? sb_format_date($order->delivery_date) : ''; ?>" class="form-control datepicker" />
				</div>
			</div>
		</div>
		<script>
		function SBQuote()
		{
			var item_tpl = '<tr data-id="{id}">'+
								'<td>{number}<input type="hidden" name="item[{index}][product_id]" value="{id}" /></td>' +
								'<td><input type="text" name="item[{index}][code]" value="{code}" class="form-control" /></td>' +
								'<td class="column-product"><input type="text" name="item[{index}][name]" value="{name}" class="form-control" /></td>' +
								'<td><input type="number" min="1" name="item[{index}][qty]" value="1" class="form-control item-qty" /></td>' +
								'<td class="column-price"><input type="text" name="item[{index}][price]" value="{price}" class="form-control item-price" /></td>' +
								'<td><span class="item-total">{total}</span></td>' +
								'<td>\
                                    <a href="javascript:;" class="configure-item btn btn-default btn-sm"><span class="glyphicon glyphicon-cog"></span></a>\
                                    <a href="javascript:;" class="remove-item btn btn-default btn-sm"><span class="glyphicon glyphicon-trash"></span></a>\
                                </td>' +
							'</tr>';
			var $this 	= this;
			var table	= jQuery('#quote-table');
			var search 	= jQuery('#search_product');
			var form	= jQuery('#form-quote');
			this.ItemExists = function(id)
			{
				var result = false;
				table.find('tbody tr').each(function(i, row)
				{
					if( row.dataset.id == id )
					{
						result = row;
						return false;
					}
				});
				return result;
			};
			this.AddItem = function()
			{
				if( search.val().trim().length <= 0 )
				{
					return false;
				}
				
				var rows = table.find('tbody').find('tr').length;
				var row = item_tpl.replace(/{index}/g, rows)
									.replace('{number}', rows + 1)
									.replace(/{name}/g, search.val());
				//console.log(jQuery(window.mb_product).data('obj'));
				if( window.mb_product )
				{
					row = row.replace(/{id}/g, mb_product.product_id)
								.replace('{code}', mb_product.product_code)
								.replace('{price}', mb_product.product_price)
								.replace('{total}', mb_product.product_price);
				}
				else
				{
					row = row.replace(/{id}/g, '')
								.replace('{code}', '')
								.replace('{price}', '0.00')
								.replace('{total}', '0.00');
				}
				var exists = false;
				if( window.mb_product )
					$this.ItemExists(mb_product.product_id);
				if( window.mb_product && exists )
				{
					//##update product qty
					var qty = jQuery(exists).find('.item-qty').val();
					qty++;
					jQuery(exists).find('.item-qty').val(qty);
					$this.CalculateRowTotal(jQuery(exists));
				}
				else
				{
					table.find('tbody').append(row);
				}
				search.val('').focus();
				$this.CalculateTotals();
			};
			this.RemoveItem = function(e)
			{
				this.parentNode.parentNode.remove();
				return false;
			};
			this.OnQtyChanged = function(e)
			{
				if( e.type == 'change' || e.type == 'keyup' )
				{
					if( e.type == 'keyup' && e.keyCode != 13 )
					{
						return false;
					}
					
					var row = jQuery(this).parents('tr:first');
					$this.CalculateRowTotal(row);
					$this.CalculateTotals();
				}
			};
			this.OnPriceChanged = function(e)
			{
				var row = jQuery(this).parents('tr:first');
				if( e.keyCode == 13 )
				{
					$this.CalculateRowTotal(row);
					$this.CalculateTotals();
					return false;
				}
				
				$this.CalculateRowTotal(row);
				$this.CalculateTotals();
				return true;
			};
			this.CalculateRowTotal = function(row)
			{
				var qty = parseInt(row.find('.item-qty:first').val());
				var price = parseFloat(row.find('.item-price:first').val());
				var tax = 0;//parseFloat(row.find('.item-tax:first').val());
				var total = (qty * price) + tax;
				row.find('.item-total:first').html(total.toFixed(2));
				
				return total;
			};
			this.CalculateTotals = function()
			{
				var rows = jQuery('#quote-table tbody tr');
				var subtotal = 0;
				var tax = 0;
				var total = 0;
				jQuery.each(rows, function(i, row)
				{
					subtotal += $this.CalculateRowTotal(jQuery(row));
				});
				total = subtotal;
				if( window.invoice_tax )
				{
					tax = subtotal * window.invoice_tax;
					total = subtotal + tax;
				}
				jQuery('#quote-subtotal').html(subtotal.toFixed(2));
				jQuery('#quote-tax').html(tax.toFixed(2));
				jQuery('#quote-total').html(total.toFixed(2));

				return total;
			}
			this.Save = function()
			{
				var customer = jQuery('#customer').val().trim();
				var nit		= parseInt(jQuery('#nit_ruc_nif').val().trim());
				if( customer.length <= 0 )
				{
					alert('<?php print SBText::_('You need to enter a customer name', 'invoices'); ?>');
					return false;
				}
				if( nit.length <= 0 )
				{
					alert('<?php print SBText::_('You need to enter a customer nit/ruc/nif', 'invoices'); ?>');
					return false;
				}
				if( isNaN(nit) )
				{
					alert('<?php print SBText::_('The nit/ruc/nif is invalid', 'invoices'); ?>');
					return false;
				}
				var params = form.serialize();
				jQuery.post('index.php', params, function(res)
				{
					if( res.status == 'ok' )
					{
						alert(res.message);
						window.location.reload();
					}
					else
					{
						alert(res.error);
					}
				});
			};
			this.OnCustomerSelected = function(e, obj)
			{
				jQuery('#customer_id').val(obj.customer_id);
				jQuery('#customer').val(obj.first_name + ' ' + obj.last_name );
				jQuery('#customer-address').html(obj.address_1 + ','+obj.city + ','+obj.country+','+obj.zip_code);
				jQuery('#customer-rfc').html(obj._nit_ruc_nif);
				jQuery('#customer-email').html(obj.email);
				jQuery('#customer-phone').html(obj.phone);
				jQuery('#search-customer-modal').modal('hide');
				
			};
			this.UpdateVariations = function()
			{
				jQuery.get('index.php?task=get_variations', function(res)
				{
					jQuery.each(res.variations, function(i, prod)
					{
						if( prod.color )
							AddVariation(prod.product_id, 'color', prod.color);
						if( prod.talla )
							AddVariation(prod.product_id, 'talla', prod.color);
					});
				});
			};
			function AddVariation(product_id, variation_name, variations)
			{
				var class_row = 'product-'+product_id+'-variations-' + variation_name;
				var product_row = jQuery('tr[data-id='+product_id+']');
				var last_row = product_row;
				jQuery('tr.'+class_row).remove();
				var total_price = 0;
				jQuery.each(variations, function(i, $var)
				{
					total_price += parseFloat($var.price) * parseInt($var.quantity);
					var row = jQuery('<tr class="'+class_row+' variation">\
								<td></td>\
								<td>Cantidad: '+$var.quantity+'</td>\
								<td><span class="status-color" style="background-color:'+$var.color+';"></span>'+$var.color_label+'</td>\
								<td colspan="4">Archivo</td>\
								</tr>');
					jQuery(row).insertAfter(last_row);
					last_row = row;
				});
				console.log(total_price);
				product_row.find('.item-price').val(total_price.toFixed(2));
			};
			function setEvents()
			{
				
				jQuery('#customer_id').keyup(function(e)
				{
					if( e.keyCode == 13 && this.value.length > 0 )
					{
						jQuery('#customer').val('');
						jQuery('#customer-address').html('');
						jQuery('#customer-rfc').html('');
						jQuery('#customer-email').html('');
						jQuery('#customer-phone').html('');
						jQuery.get('index.php?mod=customers&task=get_customer&ajax=1&id='+this.value, function(res)
						{
							if( res.status == 'ok' )
							{
                                if( res.customer.customer_id )
                                {
                                    jQuery('#customer').val(res.customer.first_name + ' ' + res.customer.last_name);
                                    jQuery('#customer-address').html(res.customer.address_1 + ','+res.customer.city + ','+res.customer.country+','+res.customer.zip_code);
                                    jQuery('#customer-rfc').html(res.customer._nit_ruc_nif);
                                    jQuery('#customer-email').html(res.customer.email);
                                    jQuery('#customer-phone').html(res.customer.phone);
                                }
								
							}
							else
							{
								alert(res.error);
							}
						});						
					}
				});
				jQuery(document).bind('on_customer_selected', $this.OnCustomerSelected);
				jQuery('#search_product').keyup(function(e)
				{
					if( e.keyCode != 13)
						return false;
					
					$this.AddItem();
				});
                jQuery('#store_id').change(function(e)
                {
                    jQuery.get('index.php?task=build_order_code&store_id='+this.value, function(res)
                    {
                        if( res.status == 'ok' )
                        {
                            jQuery('#order-number').html(res.code);
                        }
                    });
                });
				jQuery('#btn-add-item').click($this.AddItem);
				jQuery(document).on('click', '.remove-item', $this.RemoveItem);
				jQuery(document).on('keyup change keydown', '.item-qty', $this.OnQtyChanged);
				jQuery(document).on('keyup keydown', '.item-price', $this.OnPriceChanged);
                jQuery(document).on('click', '.configure-item', function(e)
                {
					var row 		= jQuery(this).parents('tr:first');
                    var product_id 	= row.data('id');                         
                    var src 		= 'index.php?tpl_file=configure&id='+product_id;
                    if( row.get(0).dataset.item_id )
                    {
						src += '&item_id=' + row.get(0).dataset.item_id;
					}
                    jQuery('#modal-configure .modal-content').html('<iframe src="'+src+'" style="width:100%;height:350px;" frameborder="0"></iframe>')
                    jQuery('#modal-configure').modal('show');
                    $this.current_item_id = product_id; 
                    return false;
                });
                jQuery('#modal-configure').on('hidden.bs.modal', function (e) 
                {
                   $this.UpdateVariations();
                });
				form.submit(function()
				{
					if(this.customer_id.value <= 0 )
					{
						alert('<?php _e('You need to select a customer', 'mb'); ?>');
						return false;
					}
					if( $this.CalculateTotals() <= 0 )
					{
						alert('<?php _e('Your order has no amount, please review your items prices and quantities', 'mb'); ?>');
						return false;
					}
					return true;
				});
			};
			setEvents();
		}
		jQuery(function()
		{
			window.mb_quote = new SBQuote();
			var completion = new SBCompletion({
				input: document.getElementById('search_product'),
				url: '<?php print SB_Route::_('index.php?mod=mb&task=ajax&action=search_product'); ?>',
				callback: function(sugesstion)
				{
					window.mb_product = jQuery(sugesstion).data('obj');
				}
			});
		});
		</script>
		<style>
		.inv-item-remove{width:5%;text-align:center;}
		.inv-item-remove img{width:25px;}
		.inv-item-number{width:7.5%;}
		.inv-item-name{width:50%;}
		.inv-item-qty{width:10%;}
		.inv-item-price{width:12.5%;}
		.inv-item-tax{width:12.5%;}
		.inv-item-total{width:15%;}
		.cool-table{background:#fff;}
		.cool-table .body{max-height:250px;}
		.cool-table .inv-item-number, .cool-table .inv-item-qty{text-align:center;}
		.cool-table .inv-item-qty input{text-align:center;}
		.cool-table .inv-item-price input, .cool-table .inv-item-tax input{text-align:right;}
		.cool-table .inv-item-total{text-align:right;}
		.table .column-product{width:40%;}
		.table .column-qty{width:120px;}
		.table .column-price input{text-align:right;}
		.table .column-actions{width:90px;}
		.sb-suggestions{max-height:200px;width:100%;}
		.sb-suggestions .the_suggestion{padding:5px;display:block;}
		.sb-suggestions .the_suggestion:focus,
		.sb-suggestions .the_suggestion:hover{background:#ececec;text-decoration:none;}, 
		</style>
	</form>
</div>
<!-- Modal -->
<div class="modal fade" id="search-customer-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php _e('Search Customer', 'mb'); ?></h4>
      </div>
      <div class="modal-body">
      	<iframe src="<?php print SB_Route::_('index.php?mod=customers&view=customers_list'); ?>" style="width:100%;height:300px;" frameborder="0"></iframe>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php _e('Close', 'mb'); ?></button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="create-customer-modal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
	    <div class="modal-content">
			<div class="modal-header">
	        	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        	<h4 class="modal-title"><?php _e('Create Customer', 'mb'); ?></h4>
	      	</div>
	      	<div class="modal-body">
	      		<iframe src="<?php print SB_Route::_('index.php?mod=customers&view=new&tpl_file=module'); ?>" style="width:100%;height:300px;" frameborder="0"></iframe>
	      	</div>
	      	<div class="modal-footer">
	        	<button type="button" class="btn btn-default" data-dismiss="modal"><?php _e('Close', 'mb'); ?></button>
	      	</div>
	    </div>
  	</div>
</div>
<div id="modal-configure" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        	<h4 class="modal-title"><?php _e('Configuracion de Servicio', 'digiprint'); ?></h4>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php _e('Cerrar', 'digiprint'); ?></button>
            </div>
        </div>
    </div>
</div>
