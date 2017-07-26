<?php
?>
<div class="wrap">
	<h2>
		<?php print SBText::_('Quotes', 'quotes'); ?>
		<div class="pull-right">
			<a href="<?php print SB_Route::_('index.php?mod=quotes&view=new'); ?>" class="btn btn-secondary"><?php print SBText::_('New Quote', 'quotes'); ?></a>
		</div>
	</h2>
	<div class="row">
		<form action="" method="get">
			<input type="hidden" name="mod" value="quotes" />
			<div class="col-md-7">
				<div class="form-group">
					<input type="text" name="keyword" value="" class="form-control" />
				</div>
			</div>
			<button type="submit" id="btn-search-quote" class="btn btn-secondary" title="<?php _e('Search quote', 'quotes'); ?>">
				<span class="glyphicon glyphicon-search"></span>
			</button>
		</form>
	</div>
	<table class="table gray grid">
	<thead>
	<tr>
		<th>#</th>
		<th><?php print SBText::_('Quote Num.', 'quotes'); ?></th>
		<th><?php print SBText::_('Date', 'quotes'); ?></th>
		<th><?php print SBText::_('Store', 'quotes'); ?></th>
		<th><?php print SBText::_('Amount', 'quotes'); ?></th>
		<th><?php print SBText::_('Customer', 'quotes'); ?></th>
		<th><?php print SBText::_('Status', 'quotes'); ?></th>
		<th>&nbsp;</th>
		<th>&nbsp;</th>
		<th>&nbsp;</th>
	</tr>
	</thead>
	<tbody>
	<?php if( is_array($quotes) ): $i = 1;foreach($quotes as $q): ?>
	<tr>
		<td class="text-center"><?php print $i; ?></td>
		<td class="text-center"><?php print $q->quote_id; ?></td>
		<td class="text-right"><?php print sb_format_datetime($q->quote_date); ?></td>
		<td><?php print $q->store_name; ?></td>
		<td class="text-right"><?php print $q->total; ?></td>
		<td><?php print $q->customer; ?></td>
		<td class="text-center">
			<?php
			$class = 'danger';
			$label = __('Unknow', 'quotes');
			if( $q->status == 'void' )
			{
				$class = 'danger';
				$label = __('Void', 'quotes');
			}
			if( $q->status == 'created' )
			{
				$class = 'success';
				$label = __('Created', 'Created');
			}
			if( $q->status == 'sent' )
			{
				$class = 'success';
				$label = __('Sent', 'quotes');
			}
			?>
			<label class="label label-<?php print $class; ?>"><?php print $label; ?></label>
		</td>
		<td>
			<a href="javascript:;" class="link-apply-green" title="<?php print SBText::_('Void', 'quotes'); ?>" 
				&nbsp;	
			</a>
		</td>
		<td>
			<?php /* ?>
			<a href="<?php print SB_Route::_('index.php?mod=quotes&view=view&id='.$q->quote_id); ?>">
				<?php print SBText::_('View', 'quotes'); ?></a>
			*/?>
			<a href="<?php print SB_Route::_('index.php?mod=quotes&task=delete&id='.$q->quote_id); ?>" class="confirm link-delete-red"
				data-message="<?php _e('Are you sure to delete the quote?', 'quotes'); ?>" title="<?php print SBText::_('Delete', 'quotes'); ?>">
				&nbsp;
			</a>
		</td>
		<td>
			<a href="<?php print SB_Route::_('index.php?mod=quotes&view=edit&id='.$q->quote_id); ?>" class="link-edit" 
				title="<?php print SBText::_('Edit', 'quotes'); ?>">
				&nbsp;
			</a>
			<?php /* ?>
			<a href="<?php print SB_Route::_('index.php?mod=quotes&task=print&id='.$q->quote_id); ?>" class="print-quote" target="_blank">
				<?php print SBText::_('Print', 'quotes'); ?></a> |
			*/?>
		</td>
	</tr>
	<?php $i++; endforeach; endif; ?>
	</tbody>
	</table>
	<script>
	jQuery(function()
	{
		jQuery('.print-quote').click(function()
		{
			if( jQuery('#quote-iframe').length > 0 )
			{
				jQuery('#quote-iframe').remove();
			}
			var iframe = jQuery('<iframe id="quote-iframe" src="'+this.href+'" style="display:none;"></iframe>');
			//window.iframe = iframe;
			jQuery('body').append(iframe);
			try
			{
				iframe.load(function()
				{
					if(iframe.get(0).contentWindow.mb_print)
						iframe.get(0).contentWindow.mb_print();
				});
				
			}
			catch(e)
			{
				alert(e);
			}
			
			return false;
		});
	});
	</script>
</div>