<?php
?>
<div class="wrap">
	<h2 id="page-title"><?php print $base_type == 'base' ? $title : __('Servicios', 'digiprint'); ?></h2>
	<div class="row">
		<div class="col-md-12">
			<?php if( $user->can('mb_create_product') ): ?>
			<a href="<?php print SB_Route::_('index.php?mod=mb&view=new_product'); ?>" class="btn btn-secondary"><?php _e('New product', 'mb'); ?></a>
			<a href="<?php print SB_Route::_('index.php?mod=mb&view=new_product&btype=service'); ?>" class="btn btn-secondary"><?php _e('Nuevo Servicio', 'digiprint'); ?></a>
			<a href="<?php print SB_Route::_('index.php?mod=mb&view=new_product&btype=asm'); ?>" class="btn btn-secondary"><?php _e('New Assembly', 'mb'); ?></a>
			<?php endif; ?>
		</div>
	</div>
	<br/>
	<form id="form-search" class="form-search form-group-sm" action="" method="get">
		<input type="hidden" name="mod" value="mb" />
		<input type="hidden" id="search_by" name="search_by" value="<?php print isset($search_by) && !empty($search_by) ? $search_by : 'product_name'; ?>" />
		<div class="row">
			<div class="col-md-2 col-xs-12">
				<div class="form-group">
					<select id="filter-store-id" name="store_id" class="form-control input-sm">
						<option value="-1">-- <?php print SB_Text::_('Store', 'mb'); ?> --</option>
						<?php foreach($stores as $s): ?>
						<option value="<?php print $s->store_id; ?>" <?php print $store_id == $s->store_id ? 'selected' : ''; ?>>
							<?php print $s->store_name; ?>
						</option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<div class="col-xs-12 col-md-2">
				<div class="form-group">
					<select id="categories" name="category_id" class="form-control input-sm">
						<option value="-1"><?php print SB_Text::_('-- category --', 'mb'); ?></option>
						<?php if(isset($categories)): foreach($categories as $c): ?>
						<option value="<?php print $c->category_id; ?>" <?php print SB_Request::getInt('category_id') == $c->category_id ? 'selected' : ''; ?>>
							<?php print $c->name; ?>
						</option>
						<?php endforeach; endif; ?>
					</select>
				</div>
			</div>
			<div class="col-xs-12 col-md-2">
				<div class="form-group">
					<select id="types" name="type_id" class="form-control input-sm">
						<option value="-1"><?php _e('-- type --', 'mb'); ?></option>
						<?php if(isset($types)): foreach($types as $t): ?>
						<option value="<?php print $t->type_id; ?>" <?php print SB_Request::getInt('type_id') == $t->type_id ? 'selected' : ''; ?>>
							<?php print $t->type; ?>
						</option>
						<?php endforeach; endif; ?>
					</select>
				</div>
			</div>
			<div class="col-xs-12 col-md-6">
				<div class="input-group">
					<input type="text" name="keyword" value="<?php print $keyword; ?>" class="form-control input-sm" placeholder="<?php print SBText::_('Search...', 'mb'); ?>" />
					<div class="input-group-btn">
						<div class="btn-group">
							<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" >
						    	<span id="search-by-text"><?php print $def_search_text; ?></span> <span class="caret"></span>
						  	</button>
						  	<ul class="dropdown-menu">
							    <li>
							    	<a href="#" onclick="jQuery('#search_by').val('product_name');jQuery('#search-by-text').html(this.innerHTML);">
							    		<?php print SB_Text::_('Name', 'mb'); ?></a></li>
							    <li>
							    	<a href="#" onclick="jQuery('#search_by').val('product_code');jQuery('#search-by-text').html(this.innerHTML);">
							    		<?php print SB_Text::_('Code', 'mb'); ?></a></li>
							    <li role="separator" class="divider"></li>
						  	</ul>
						</div>
						<button type="submit" class="btn btn-default btn-sm"><?php print SB_Text::_('Search', 'mb')?></button>
					</div>
				</div>
			</div>
		</div>
	</form>
	<div class="clearfix">&nbsp;</div>
	<form action="" method="get">
		<input type="hidden" name="mod" value="mb" />
		<input type="hidden" name="task" value="batch" />
		<div class="row">
			<div class="col-md-2">
				<select name="action" class="form-control input-sm">
					<option value="-1"><?php _e('-- batch action --', 'mb'); ?></option>
					<option value="delete"><?php _e('Delete', 'mb'); ?></option>
				</select>
			</div>
			<div class="col-md-1">
				<button type="submit" class="btn btn-primary btn-sm"><?php _e('Execute', 'mb'); ?></button>
			</div>
		</div>
		<table class="table">
		<thead>
		<tr>
			<th><input type="checkbox" name="selector" value="" class="tcb-select-all" /></th>
			<th><?php print 'ID'; ?></th>
			<th><?php print SB_Text::_('Code', 'mb'); ?></th>
			<th><?php print SB_Text::_('Image', 'mb'); ?></th>
			<th><?php print SB_Text::_('Product', 'mb'); ?></th>
			<th><?php print SB_Text::_('Store', 'mb'); ?></th>
			<th><?php print SB_Text::_('Category', 'mb'); ?></th>
			<th><?php print SB_Text::_('Stock', 'mb'); ?></th>
			<th><?php print SB_Text::_('Actions', 'mb'); ?></th>
		</tr>
		</thead>
		<tbody>
		<?php if( count($products) ): $i = 1;foreach($products as $p): ?>
		<?php
		$qty_title = __('Stock good', 'mb');
		$qty_class = 'label label-success';
		if($p->product_quantity > 0 && $p->product_quantity <= $p->min_stock  )
		{
			$qty_class = 'label label-warning';
			$qty_title = __('Minimal stock warning', 'mb');
		}
		if($p->product_quantity <= 0  )
		{
			$qty_class = 'label label-danger';
			$qty_title = __('Product stock critical danger', 'mb');
		}
			
		?>
		<tr>
			<td><input type="checkbox" name="ids[]" value="<?php print $p->product_id; ?>" class="tcb-select" /></td>
			<td class="col-id"><?php print $p->product_id; ?></td>
			<td class="col-code"><?php print $p->product_code; ?></td>
			<td class="col-image text-center">
				<?php printf("<a href=\"javascript:;\" class=\"thumb-container\"><img src=\"%s\" alt=\"\" width=\"52\" /></a>", 
								$p->getFeaturedImage()); ?>
			</td>
			<td class="col-name"><a href="javascript:;" class="product-name"><?php print $p->product_name; ?></a></td>
			<td class="col-store"><?php print $p->store_id ? $p->getStore()->store_name : __('No assigned', 'mb'); ?></td>
			<td class="col-category">
				<?php print $p->GetCategoriesName(); ?>
			</td>
			<td class="col-qty text-center">
				<h3 style="margin:0;cursor:pointer;" title="<?php print $qty_title; ?>">
					<span class="<?php print $qty_class; ?>"><?php print $p->product_quantity; ?></span>
				</h3>
			</td>
			<td class="col-actions">
				<a href="<?php print SB_Route::_('index.php?mod=mb&view=edit&id='.$p->product_id); ?>" 
					title="<?php print SB_Text::_('Edit', 'mb')?>" class="btn btn-default btn-sm">
					<span class="glyphicon glyphicon-pencil"></span>
				</a>
				<a href="javascript:;" class="btn-quick-view btn btn-default btn-sm" title="<?php print SB_Text::_('Quick view', 'mb'); ?>"
					data-name="<?php print $p->product_name; ?>"
					data-code="<?php print $p->product_code; ?>"
					data-name="<?php print $p->product_name; ?>"
					data-qty="<?php print $p->product_quantity; ?>"
					data-cost="<?php print sb_get_current_user()->can('mb_can_see_cost') ? $p->product_cost : '0.00'; ?>"
					data-price1="<?php print number_format($p->product_price, 2, '.', ','); ?>"
					data-price2="<?php print number_format($p->product_price_2, 2, '.', ','); ?>"
					data-price3="<?php print number_format($p->product_price_3, 2, '.', ','); ?>"
					data-price4="<?php print number_format($p->product_price_4, 2, '.', ','); ?>">
					<span class="glyphicon glyphicon-eye-open"></span>
				</a>
				<?php if( $user->can('mb_delete_product') ): ?>
				<a class="confirm btn btn-default btn-sm" href="<?php print SB_Route::_('index.php?mod=mb&task=delete&id='.$p->product_id); ?>" title="<?php print SB_Text::_('Delete', 'mb')?>"
					data-message="<?php print SB_Text::_('Are you sure to delete the product?', 'mb'); ?>">
					<span class="glyphicon glyphicon-trash"></span>
				</a>
				<?php endif; ?>
			</td>
		</tr>
		<?php $i++; endforeach; else: ?>
		<tr><td colspan="4"><?php print SB_Text::_('There are no products yet into database.'); ?></td></tr>
		<?php endif; ?>
		</tbody>
		</table>
	</form>
	<?php lt_pagination(SB_Route::_('index.php?'.$_SERVER['QUERY_STRING']), $total_pages, $current_page); ?>
	<script>
	function onClickCell(index, field, html)
	{
		var e = arguments.callee.caller.arguments[0];
		
		if(arguments[1] == 'action')
		{
			//console.log(jQuery(e.target).parent().find('.dropdown-menu').css('display', 'block'));
			var menu = jQuery(e.target).parent().find('.dropdown-menu:first');
			var cell = jQuery(e.target).parents('.datagrid-cell, .datagrid-view2, .datagrid-view, .datagrid-body, .datagrid-wrap, .datagrid');
			cell.css('overflow', 'visible');
			//console.log(menu.css('display'));
			if( menu.css('display') == 'block' )
				menu.css('display', 'none');
			else
				menu.css('display', 'block');
			
			e.stopPropagation();
			//jQuery(html).find('.dropdown-menu').css('display', 'block');
		}
	}
	jQuery(function()
	{
		jQuery('#btn-print-catalog, #btn-print-labels').click(function()
		{
			var store_id = jQuery('#filter-store-id').val();
			var cat_id = jQuery('#categories').val();
			if( store_id > 0 )
			{
				this.href += '&store_id='+store_id;
			}
			if( cat_id > 0 )
			{
				this.href += '&cat_id='+cat_id;
			}
			return true;
		});
		jQuery('#filter-store-id').change(function()
		{
			jQuery('#categories').html('<option value="-1">-- categoria --</option>');
			if( this.value <= 0 )
				return true;
			
			jQuery.get('index.php?mod=mb&task=ajax&action=get_store_cats&store_id=' + this.value, function(res)
			{
				if( res.status == 'ok' )
				{
					jQuery.each(res.categories, function(i, cat)
					{
						jQuery('#categories').append('<option value="'+cat.category_id+'">'+cat.name+'</option>');
					});
				}
			});
			return false;
		});
		jQuery('.btn-quick-view, .product-name').click(function(e)
		{
			//console.log(e);
			var dataset = null;
			if( jQuery(e.target).hasClass('product-name') )
			{
				dataset = jQuery(e.target).parents('tr').find('.btn-quick-view:first').get(0).dataset;
			}
			else
			{
				dataset = this.dataset;
			}
			jQuery('#quick-view-dialog').find('#_code').html(dataset.code);
			jQuery('#quick-view-dialog').find('#_name').html(dataset.name);
			jQuery('#quick-view-dialog').find('#_qty').html(dataset.qty);
			jQuery('#quick-view-dialog').find('#_cost').html(dataset.cost);
			jQuery('#quick-view-dialog').find('#_price1').html(dataset.price1);
			jQuery('#quick-view-dialog').find('#_price2').html(dataset.price2);
			jQuery('#quick-view-dialog').find('#_price3').html(dataset.price3);
			jQuery('#quick-view-dialog').find('#_price4').html(dataset.price4);
			jQuery('#quick-view-dialog').modal('show');
			return false;
		});
	});
	var cint = null;
	function sb_start_camera()
	{
		//##initialize camera
		window.sb_camera = new SBCamera(document.getElementById('camera-video'), document.getElementById('camera-canvas'));
	}
	function __capture()
	{
		cint = setInterval(function()
		{
			sb_camera.Capture();
			var barcode = getBarcodeFromCanvas('camera-canvas');
			if( !isNaN(barcode) )
			{
				alert(barcode);
			}
		}, 400);
		
	}
	function __stopcapture()
	{
		clearInterval(cint);
	}
	</script>
</div>
<div id="quick-view-dialog" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header"><?php _e('Product Details', 'mb'); ?></div>
			<div class="modal-body">
				<table>
				<tr>
					<td><b><?php _e('Code:', 'mb'); ?></b></td>
					<td><span id="_code">0.00</span></td>
				</tr>
				<tr>
					<td><b><?php _e('Product Name:', 'mb'); ?></b></td>
					<td><span id="_name">0.00</span></td>
				</tr>
				<tr>
					<td><b><?php _e('Quantity:', 'mb'); ?></b></td>
					<td><span id="_qty">0.00</span></td>
				</tr>
				<tr>
					<td><b><?php _e('Costo:', 'mb'); ?></b></td>
					<td><span id="_cost">0.00</span></td>
				</tr>
				<tr>
					<td><b><?php _e('Price 1:', 'mb'); ?></b></td>
					<td><span id="_price1">0.00</span></td>
				</tr>
				<tr>
					<td><b><?php _e('Price 2:', 'mb'); ?></b></td>
					<td><span id="_price2">0.00</span></td>
				</tr>
				<tr>
					<td><b><?php _e('Price 3:', 'mb'); ?></b></td>
					<td><span id="_price3">0.00</span></td>
				</tr>
				<tr>
					<td><b><?php _e('Price 4:', 'mb'); ?></b></td>
					<td><span id="_price4">0.00</span></td>
				</tr>
				</table>
			</div>
			<div class="modal-footer">
		        <button type="button" class="btn btn-primary" data-dismiss="modal"><?php _e('Close', 'mb'); ?></button>
			</div>
		</div>
	</div>
</div>
<div id="camera-dialog" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header"><?php _e('Capture Product Barcode', 'mb'); ?></div>
			<div class="modal-body">
				<video id="camera-video" width="250" height="250" autoplay></video>
				<canvas id="camera-canvas"></canvas>
				<a href="javascript:;" onclick="__capture();" class="btn btn-primary" style="width:100%;"><?php _e('Capture', 'mb'); ?></a>
			</div>
			<div class="modal-footer">
		        <button type="button" class="btn btn-primary" data-dismiss="modal"><?php _e('Close', 'mb'); ?></button>
			</div>
		</div>
	</div>
</div>
<style>
@media (max-width:768px)
{
	table, thead, tbody, th, td, tr { 
		display: block; 
		position:relative;
	}
	table thead{display:none;}
	table tbody{margin:0;padding:0;position:relative;width:100%;}
	table tbody tr{border:0;overflow:hidden;border-bottom:1px solid #ddd;margin:0 0 5px 0;}
	table tbody tr td{position:relative;border:0;display:block;border:0 !important;}
	/*.col-count,.col-image,.col-name{float:left;}*/
	.col-id{display:none !important;float:left !important;}
	.col-code,.col-image, .col-store,.col-qty{width:25%;float:left !important;}
	.col-name{width:50%;height:85px;float:left !important;overflow:hidden;}
	.col-store{}
}
</style>