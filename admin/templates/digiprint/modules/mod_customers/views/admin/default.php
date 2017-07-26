<?php
?>
<div class="wrap">
	<h2 id="page-title">
		<?php print SBText::_('Customers Management', 'customers'); ?>
		<a class="btn btn-success pull-right" href="<?php print SB_Route::_('index.php?mod=customers&view=new'); ?>">
			<?php print SB_Text::_('New Customer', 'customers'); ?>
		</a>
	</h2>
	<form action="" method="get" class="">
		<input type="hidden" name="mod" value="customers" />
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<input type="text" name="keyword" value="" placeholder="<?php _e('Search customer', 'customers'); ?>" class="form-control" />
				</div>
			</div>
			<button class="btn btn-gray"><span class="glyphicon glyphicon-search"></span></button>
		</div>
	</form>
		<table id="mb-customers-table" class="table gray grid">
		<thead>
		<tr>
			<th><?php print SB_Text::_('Customer Id', 'digiprint'); ?></th>
			<th><?php print SB_Text::_('First Name', 'digiprint'); ?></th>
			<th><?php print SB_Text::_('Last Name', 'digiprint'); ?></th>
			<th><?php print SB_Text::_('Email', 'digiprint'); ?></th>
			<th><?php print SB_Text::_('Telephone', 'digiprint'); ?></th>
			<th><?php print SB_Text::_('Status', 'customers'); ?></th>
			<th>&nbsp;</th>
			<th>&nbsp;</th>
		</tr>
		</thead>
		<tbody>
		<?php $i=1;if(count($customers)): foreach($customers as $c): ?>
		<tr>
			<td><?php print $c->customer_id; ?></td>
			<td><?php print $c->first_name; ?></td>
			<td><?php print $c->last_name; ?></td>
			<td><?php print $c->email; ?></td>
			<td><?php printf("%s, %s", $c->phone, $c->mobile);?></td>
			<td><?php print $c->status; ?></td>
			<td>
				<?php /* ?>
				<a href="<?php print SB_Route::_('index.php?mod=customers&task=get_customer&id='.$c->customer_id); ?>">
					<?php print SB_Text::_('View', 'customers')?>
				</a>
				*/ ?>
				<a href="<?php print SB_Route::_('index.php?mod=customers&task=edit&id='.$c->customer_id); ?>" class="link-edit"
					title="<?php print SB_Text::_('Edit', 'digiprint')?>">
					&nbsp;
				</a>
			</td>
			<td>
				<a class="confirm link-delete" data-message="<?php print SBText::_('Are you sure to delete the customer?', 'customers'); ?>" 
					href="<?php print SB_Route::_('index.php?mod=customers&task=delete&id='.$c->customer_id); ?>"
					title="<?php print SB_Text::_('Delete', 'digiprint')?>">
					&nbsp;
				</a>
			</td>
		</tr>
		<?php $i++;endforeach; else: ?>
		<tr>
			<td colspan="4"><?php print SB_Text::_('There are no customers registered yet', 'mb_c'); ?></td>
		</tr>
		<?php endif; ?>
		</tbody>
		</table>
		<?php /* ?>
	<div class="">
		<div id="customer-data-panel" class="panel panel-primary">
			<div class="panel-heading"><?php print SB_Text::_('Customer', 'customers')?></div>
			<div id="customer-data" class="panel-body">
				<div class="row">
					<div class="col-md-2">
						<img src="<?php print BASEURL; ?>/images/empty-avatar.png" alt="" class="img-thumbnail" />
					</div>
					<div class="col-md-10">
						<div class="row-fluid">
							<div class="span2"><b><?php print SB_Text::_('Name', 'customers')?>&emsp;&emsp;&emsp;:</b>
							<label style="color: blue"><?php print isset($customerView) ? $customerView->name : '';?></label>
							</div>
						</div>
						<div class="row-fluid">
							<div class="span2"><b><?php print SB_Text::_('Email', 'customers')?>&emsp;&emsp;&emsp; :</b>
							<label style="color: blue"><?php print isset($customerView) ? $customerView->email : '';?></label>
							</div>
						</div>
						<div class="row-fluid">
							<div class="span2"><b><?php print SB_Text::_('Telephone', 'customers')?>&nbsp; :</b>
							<b style="color: blue"><?php print isset($customerView) ? $customerView->phone : '';?></b>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
	jQuery(function()
	{
		jQuery('.btn-view-customer').click(function()
		{
			return false;
		});
	});
	</script>
	*/?>
</div>