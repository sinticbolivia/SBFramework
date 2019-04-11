<div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
	<div class="panel panel-default">
		<div class="panel-heading"><?php print $w->name; ?></div>
		<div class="panel-body">
			<div class="form-group">
				<label><?php _e('Quantity'); ?></label>
				<input type="hidden" name="quantities[<?php print $i ?>][warehouse_id]" value="<?php print $w->id; ?>" class="form-control" />
				<input type="text" name="quantities[<?php print $i ?>][quantity]" value="<?php print isset($the_product) ? $the_product->GetWarehouseQty($w->id) : 0; ?>" class="form-control" />
			</div>
			<div class="form-group">
				<label><?php _e('Min Stock'); ?></label>
				<input type="text" name="quantities[<?php print $i ?>][min_stock]" value="<?php print isset($the_product) ? $the_product->GetWarehouseMinStock($w->id) : 0; ?>" class="form-control" />
			</div>
		</div>
		<?php b_do_action('mb_product_warehouse_fields', isset($the_product) ? $the_product : null, $w);?>
	</div>
</div>