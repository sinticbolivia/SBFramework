<?php
?>
<div class="wrap">
	<h2 class="">
		<?php _e('Orders History', 'digiprint'); ?>
		<span class="pull-right">
			<a href="<?php print SB_Route::_('index.php?mod=mb&view=orders.new'); ?>" class="btn btn-success"><?php _e('New Order', 'digiprint');?></a>
		</span>
	</h2>
	<div class="row">
		<form action="" method="get">
            <input type="hidden" name="mod" value="mb" />
            <input type="hidden" name="view" value="orders.default" />
			<div class="col-xs-6 col-md-6">
                <input type="text" name="keyword" value="<?php print SB_Request::getString('keyword'); ?>" placeholder="<?php _e('Search by order or customer No.', 'digiprint'); ?>" class="form-control" />
			</div> 
			<button type="submit" class="btn btn-search"><span class="glyphicon glyphicon-search"></span></button>
		</form>
	</div><br/>
	<table class="table gray grid">
	<thead>
	<tr>
		<th><?php _e('Num. Order', 'digiprint'); ?></th>
		<th><?php _e('Order Date', 'digiprint'); ?></th>
		<th><?php _e('Branch office', 'digiprint'); ?></th>
		<th><?php _e('Amount', 'digiprint'); ?></th>
		<th><?php _e('Pending', 'digiprint'); ?></th>
		<th><?php _e('Status', 'digiprint'); ?></th>
		<th>&nbsp;</th>
		<th>&nbsp;</th>
	</tr>
	</thead>
	<tbody>
	<?php foreach($orders as $o): ?>
	<tr>
		<td class="text-center"><?php print $o->order_id; ?></td>
		<td><?php print sb_format_datetime($o->creation_date); ?></td>
		<td><?php print $o->store_name; ?></td>
		<td class="text-right"><?php print $o->total; ?></td>
		<td class="text-right">0.00</td>
		<td class="text-center">
			<?php
			$status = __('Unknow', 'mb');
			$class = '';
			if( $o->status == 'sold' || $o->status == 'complete' || $o->status == 'shipped' )
			{
				$status = SBText::_('Complete', 'mb');
				$class = 'success';
			}
			if( $o->status == 'pending' )
			{
				$status = SBText::_('Pending', 'mb');
				$class = 'warning';
			}
			if( $o->status == 'cancelled' )
			{
				$status = SBText::_('Cancelled', 'mb');
				$class = 'danger';
			}
			?>
			<span ><?php print $status; ?></span>
		</td>
		<td class="text-center">
			<a href="<?php print SB_Route::_('index.php?mod=mb&view=orders.edit&id='.$o->order_id); ?>" class="link-edit" title="<?php _e('Edit order', 'digiprint'); ?>">&nbsp;</a>
		</td>
		<td class="text-center"><span class="status-color <?php print $o->status; ?>"></span></td>
	</tr>
	<?php endforeach; ?>
	</tbody>
	</table>
</div>