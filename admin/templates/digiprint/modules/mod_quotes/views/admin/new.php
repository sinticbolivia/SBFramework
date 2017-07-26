<?php
?>
<div class="wrap">
	<form action="" method="post" id="form-quote" class="avoid-submission">
		<input type="hidden" name="mod" value="quotes" />
		<input type="hidden" name="task" value="save" />
		<?php if( isset($quote) ): ?>
		<input type="hidden" name="quote_id" value="<?php print $quote->quote_id; ?>" />
		<?php endif; ?>
		<h2>
			<?php print $title; ?>
			<span class="pull-right">
				<a href="<?php print SB_Route::_('index.php?mod=quotes'); ?>" class="btn btn-danger">
					<span class="glyphicon glyphicon-remove"></span> <?php print SBText::_('Cancel', 'quotes'); ?>
				</a>
				<?php if( isset($quote) ): ?>
				<a href="<?php print SB_Route::_('index.php?mod=quotes&view=print&id='.$quote->quote_id); ?>" class="btn btn-warning" target="_blank">
					<span class="glyphicon glyphicon-print"></span> <?php print SBText::_('Print', 'quotes'); ?>
				</a>
				<a href="javascript:;" class="btn btn-success" data-toggle="modal" data-target="#send-quote-form">
					<span class="glyphicon glyphicon-envelope"></span> <?php print SBText::_('Send Quote', 'quotes'); ?></a>
				<?php endif; ?>
				<button type="submit" class="btn btn-success">
					<span class="glyphicon glyphicon-save"></span> <?php print SBText::_('Save', 'quotes'); ?>
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
								<label class="control-label"><?php print SBText::_('ID', 'quotes'); ?></label>
                                <input type="text" id="customer_id" name="customer_id" value="<?php print isset($quote) ? $quote->customer_id : ''; ?>" 
                                       class="form-control" autocomplete="off" />
							</div>
						</div>
						<div class="col-md-7">
							<div class="form-group">
								<label class="control-label"><?php print SBText::_('Customer Name', 'quotes'); ?></label>
								<input type="text" id="customer" name="customer" 
									value="<?php print isset($quote) ? $quote->customer->first_name . ' ' . $quote->customer->last_name : ''; ?>" class="form-control" />
							</div>
						</div>
						<div class="">
							<div class="form-group">
								<label>&nbsp;</label>
								<div>
									<a href="javascript:;" id="btn-search-customer" class="btn btn-gray" title="<?php _e('Search customer', 'quotes'); ?>"
										data-toggle="modal" data-target="#search-customer-modal">
										<span class="glyphicon glyphicon-search"></span>
									</a>
									<a href="javascript:;" id="btn-add-customer" class="btn btn-gray" title="<?php _e('Create customer', 'quotes'); ?>"
										data-toggle="modal" data-target="#create-customer-modal">
										<span class="glyphicon glyphicon-user"></span>
									</a>
								</div>
							</div>
							
						</div>
					</div>
                    <div class="row">
                        <div class="col-md-12"><p id="customer-address"></p></div>
                    </div>
					<div class="row">
						<p id="customer-address"></p>
						<div class="col-md-3"><span id="customer-rfc"></span></div>
						<div class="col-md-3"><span id="customer-email"></span></div>
						<div class="col-md-3"><span id="customer-phone"></span></div>
					</div>
					<div class="row">
						<div class="col-xs-4 col-md-4">
							<div class="form-group">
								<label><?php _e('Sucursal', 'digiprint'); ?></label>
								<select name="store_id" class="form-control">
									<option value="-1"><?php _e('-- sucursal --', 'digiprint'); ?></option>
									<?php foreach($stores as $s): ?>
									<option value="<?php print $s->store_id; ?>" <?php print isset($quote) && $quote->store_id == $s->store_id ? 'selected' : ''; ?>>
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
									<option value="-1"><?php _e('-- coupon --'); ?></option>
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
									<option value="-1"><?php _e('-- taxes --', 'quotes'); ?></option>
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
						<?php _e('Quote No.', 'quotes');?><br/>
						<span id="quote-number" class="text-red"><b><?php print isset($quote) ? __('Q-', 'quotes') . sb_fill_zeros($quote->quote_id) : 'C-0000X'; ?></b></span>
					</div>
					<table id="table-totals" class="totals-table" style="width:100%;">
					<tr>
						<th class="text-right"><?php _e('Sub Total:', 'quotes'); ?></th>
						<td class="text-right"><span id="quote-subtotal"><?php print isset($quote) ? $quote->subtotal : 0.00; ?></span></td>
					</tr>
					<tr>
						<th class="text-right"><?php _e('Discount:', 'quotes'); ?></th>
						<td class="text-right"><span id="quote-discount"><?php print isset($quote) ? $quote->discount : 0.00; ?></span></td>
					</tr>
					<tr>
						<th class="text-right"><?php _e('Tax:', 'quotes'); ?></th>
						<td class="text-right"><span id="quote-tax"><?php print isset($quote) ? $quote->total_tax : 0.00; ?></span></td>
					</tr>
					<tr>
						<th class="text-right"><b><?php _e('Advance:', 'quotes'); ?></b></th>
						<td class="text-right"><b><span id="invoice-total">0.00</span></b></td>
					</tr>
					<tr id="row-total">
						<th class="text-right"><?php _e('Grand Total:', 'quotes'); ?></th>
						<td class="text-right"><span id="quote-total"><?php print isset($quote) ? $quote->total : 0.00; ?></span></td>
					</tr>
					</table>
				</div>
			</div>
		</fieldset><br/>
		<div class="form-group">
			<div class="input-group">
				<input type="text" id="search_product" name="search_product" value="" placeholder="<?php print SBText::_('Search product', 'invoice'); ?>" class="form-control" />
				<span class="input-group-btn">
		        	<button id="btn-add-item" class="btn btn-default" type="button"><?php print SBText::_('Add item', 'quotes'); ?></button>
		      	</span>
			</div>
		</div>
		<table id="quote-table" class="table">
			<thead>
			<tr>
				<th class="text-center"><?php _e('No.', 'quotes'); ?></th>
				<th class="text-center"><?php print SBText::_('Code', 'quotes'); ?></th>
				<th class="column-product"><?php print SBText::_('Product', 'quotes'); ?></th>
				<th class="column-qty"><?php print SBText::_('Quantity', 'quotes'); ?></th>
				<th class="column-price text-right"><?php print SBText::_('Price', 'quotes'); ?></th>
				<th class="column-total text-right"><?php print SBText::_('Total', 'quotes'); ?></th>
				<th>&nbsp;</th>
			</tr>
			</thead>
			<tbody>
			<?php if( isset($quote) ): $i = 1; foreach($quote->GetItems() as $item): ?>
			<tr>
				<td><?php print $i; ?></td>
				<td><input type="text" name="items[<?php print $i - 1; ?>][code]" value="<?php print $item->item_code; ?>" class="form-control" /></td>
				<td class="column-product"><input type="text" name="items[<?php print $i - 1; ?>][name]" value="<?php print $item->name ?>" class="form-control" /></td>
				<td class="text-center">
					<input type="number" min="1" name="items[<?php print $i - 1; ?>][qty]" value="<?php print $item->quantity ?>" class="form-control item-qty" />
				</td>
				<td class="column-price"><input type="text" name="items[<?php print $i - 1; ?>][price]" value="<?php print $item->price ?>" class="form-control item-price" /></td>
				<td><?php print $item->total ?></td>
				<td><a href="javascript:;" class="remove-item"><img src="<?php print MOD_INVOICES_URL; ?>/images/remove.png" width="25" /></a></td>
			</tr>
			<?php $i++; endforeach; endif; ?>
			</tbody>
		</table><!-- end id="quote-table" -->
		<br/>
		<hr class="ht--"/>
		<div class="row">
			<div class="col-md-7">
				<div class="form-group">
					<label><?php _e('Quote Comments', 'quotes'); ?></label>
					<textarea name="notes" class="form-control"><?php print isset($quote) ? $quote->notes : ''; ?></textarea>
				</div>
			</div>
			<div class="col-md-5">
			</div>
		</div>
		<script>
		function SBQuote()
		{
			var item_tpl = '<tr data-id="{id}">'+
								'<td>{number}<input type="hidden" name="items[{index}][id]" value="{id}" /></td>' +
								'<td><input type="text" name="items[{index}][code]" value="{code}" class="form-control" /></td>' +
								'<td class="column-product"><input type="text" name="items[{index}][name]" value="{name}" class="form-control" /></td>' +
								'<td><input type="number" min="1" name="items[{index}][qty]" value="1" class="form-control item-qty" /></td>' +
								'<td class="column-price"><input type="text" name="items[{index}][price]" value="{price}" class="form-control item-price" /></td>' +
								'<td><span class="item-total">{total}</span></td>' +
								'<td><a href="javascript:;" class="remove-item"><img src="<?php print MOD_INVOICES_URL; ?>/images/remove.png" width="25" /></a></td>' +
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
				jQuery('#search_product').keyup(function(e)
				{
					if( e.keyCode != 13)
						return false;
					
					$this.AddItem();
				});
				jQuery('#btn-add-item').click($this.AddItem);
				jQuery(document).on('click', '.remove-item', $this.RemoveItem);
				jQuery(document).on('keyup change keydown', '.item-qty', $this.OnQtyChanged);
				jQuery(document).on('keyup keydown', '.item-price', $this.OnPriceChanged);
				form.submit(function()
				{
					if(this.customer_id.value <= 0 )
					{
						alert('<?php _e('You need to select a customer', 'quotes'); ?>');
						return false;
					}
					if( $this.CalculateTotals() <= 0 )
					{
						alert('<?php _e('Your quote has no amount, please review your items prices and quantities', 'quotes'); ?>');
						return false;
					}
					return true;
				});
			};
			setEvents();
		}
		//##define the customer search select callback
		window.select_callback = function(obj)
		{
			jQuery('#search-customer-modal').modal('hide');
			var params = 'mod=customers&ajax=1&task=get_customer&id=' + obj.id;
			jQuery.post('index.php', params, function(res)
			{
				//console.log(res.customer.customer_id);
				if( res.status == 'ok' )
				{
					jQuery('#customer_id').val(res.customer.customer_id);
					jQuery('#customer').val(res.customer.first_name + ' ' + res.customer.last_name );
				}
				else
				{
					alert(res.error);
				}
			});
		};
		jQuery(function()
		{
			window.mb_quote = new SBQuote();
			var completion = new SBCompletion({
				input: document.getElementById('search_product'),
				url: 'http://localhost/little-cms/admin/index.php?mod=quotes&task=search_product',
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
		.table .column-product{width:45%;}
		.table .column-price input{text-align:right;}
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
        <h4 class="modal-title" id="myModalLabel"><?php _e('Search Customer', 'customers'); ?></h4>
      </div>
      <div class="modal-body">
      	<iframe src="<?php print SB_Route::_('index.php?mod=customers&view=customers_list'); ?>" style="width:100%;height:300px;" frameborder="0"></iframe>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php _e('Close', 'customers'); ?></button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="create-customer-modal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
	    <div class="modal-content">
			<div class="modal-header">
	        	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        	<h4 class="modal-title"><?php _e('Create Customer', 'customers'); ?></h4>
	      	</div>
	      	<div class="modal-body">
	      		<iframe src="<?php print SB_Route::_('index.php?mod=customers&view=new&tpl_file=module'); ?>" style="width:100%;height:300px;" frameborder="0"></iframe>
	      	</div>
	      	<div class="modal-footer">
	        	<button type="button" class="btn btn-default" data-dismiss="modal"><?php _e('Close', 'customers'); ?></button>
	      	</div>
	    </div>
  	</div>
</div>
<?php if( isset($quote) ): ?>
<div class="modal fade" id="send-quote-form" tabindex="-1" role="dialog"><form action="" method="post">
	<div class="modal-dialog" role="document">
	    <div class="modal-content">
			<div class="modal-header">
	        	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        	<h4 class="modal-title"><?php _e('Send Quotation', 'customers'); ?></h4>
	      	</div>
	      	<div class="modal-body">
      			<input type="hidden" name="mod" value="quotes" />
      			<input type="hidden" name="task" value="send" />
      			<?php if( isset($quote) ): ?>
      			<input type="hidden" name="id" value="<?php print $quote->quote_id; ?>" />
      			<?php endif; ?>
      			<div class="form-group">
      				<input type="text" name="email_to" value="<?php print $quote->customer->email; ?>" placeholder="<?php _e('Email', 'quotes'); ?>" class="form-control" />
      			</div>
      			<div class="form-group">
      				<input type="text" name="subject" value="" placeholder="<?php _e('Email subject', 'quotes'); ?>" class="form-control" />
      			</div>
      			<div class="form-group">
      				<textarea rows="" cols="" name="message" placeholder="<?php _e('Quote message', 'quoes'); ?>" class="form-control"></textarea>
      			</div>
	      	</div>
	      	<div class="modal-footer">
	        	<button type="button" class="btn btn-default" data-dismiss="modal"><?php _e('Close', 'quotes'); ?></button>
	        	<button type="submit" class="btn btn-primary"><?php _e('Send Quote', 'quotes'); ?></button> 
	      	</div>
	    </div>
  	</form></div>
</div>
<?php endif; ?>