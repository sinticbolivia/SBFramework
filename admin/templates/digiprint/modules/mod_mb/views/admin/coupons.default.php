<?php
?>
<div class="wrap">
	<h2>
		<?php _e('Discount Coupons', 'mb'); ?>
		<a href="<?php print SB_Route::_('index.php?mod=mb&view=coupons.new'); ?>" class="pull-right btn btn-orange"><?php _e('New Coupon', 'mb'); ?></a>
	</h2>
	<div class="row">
		<div class="col-md-7">
			<form action="index.php" method="get">
				<input type="hidden" name="mod" value="mb" />	
				<input type="hidden" name="view" value="coupons.default" />
				<div class="form-group">
					<input type="text" name="keyword" value="" placeholder="<?php _e('Search by coupon code or name', 'mb'); ?>" class="form-control" />
				</div>
			</form>
		</div>
		<div class="col-md-5">
		</div>
	</div>
	<table class="table gray grid">
	<thead>
	<tr>
		<th><?php _e('Coupon Code', 'mb'); ?></th>
		<th><?php _e('Description', 'mb'); ?></th>
		<th><?php _e('Start Date', 'mb'); ?></th>
		<th><?php _e('End Date', 'mb'); ?></th>
		<th><?php _e('Status', 'mb'); ?></th>
		<th>&nbsp;</th>
	</tr>
	</thead>
	<tbody>
	<?php $i = 1; foreach($coupons as $c): ?>
	<tr>
		<td class="text-center"><?php print $c->code; ?></td>
		<td><?php print $c->description; ?></td>
		<td class="text-center"><?php print sb_format_datetime($c->start_date); ?></td>
		<td class="text-center"><?php print sb_format_datetime($c->end_date); ?></td>
		<td class="text-center">
			<?php if($c->status == 'active'): ?>
			<label class="label label-success"><?php _e('Active', 'mb'); ?></label>
			<?php else: ?>
			<label class="label label-danger"><?php _e('Inactive', 'mb'); ?></label>
			<?php endif; ?>
		</td>
		<td>
			<a href="<?php print SB_Route::_('index.php?mod=mb&task=coupons.delete&id='.$c->coupon_id); ?>" class="confirm link-delete"
				data-message="<?php _e('Are you sure to delete the coupon?', 'mb'); ?>" title="<?php _e('Delete', 'mb'); ?>">
				&nbsp;
			</a>
		</td>
	</tr>
	<?php $i++; endforeach; ?>
	</tbody>
	</table>
</div>