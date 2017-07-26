<?php
?>
<div class="wrap">
	<form id="dashboard-form" action="<?php print SB_Route::_('index.php'); ?>" method="get" style="margin:250px 0 0 50px;">
		<input type="hidden" name="mod" value="" />
		<input type="hidden" name="view" value="" />
		<div class="row">
			<div class="col-xs-12 col-md-4">
				<div class="form-group">
					<input type="text" name="keyword" value="" placeholder="<?php _e('Order number', 'digiprint'); ?>" class="form-control"
                           autocomplete="off" style="height:61px;text-transform: uppercase;font-size:18px;" />
				</div>
			</div>
			<div class="col-xs-12 col-md-4">
				<a href="javascript:;" class="btn btn-square btn-red" data-mod="mb" data-view="orders.default" >
					<img src="<?php print TEMPLATE_URL; ?>/images/icons/50x50_blancos/icon-02.png" alt="" class="icon" />
					<span class="label"><?php _e('New Order', 'digiprint'); ?></span>
				</a>
				<a href="javascript:;" class="btn btn-square btn-red" data-mod="quotes" data-view="default">
					<img src="<?php print TEMPLATE_URL; ?>/images/icons/50x50_blancos/icon-08.png" alt="" class="icon" />
					<span class="label"><?php _e('Quote', 'digiprint'); ?></span>
				</a>
				<a href="<?php print SB_Route::_('index.php?mod=customers'); ?>" class="btn btn-square btn-red" data-mod="customers" data-view="default">
					<img src="<?php print TEMPLATE_URL; ?>/images/icons/50x50_blancos/icon-18.png" alt="" class="icon" />
					<span class="label"><?php _e('Customers', 'digiprint'); ?></span>
				</a>
			</div>
		</div>
	</form>
	<script>
	jQuery(function()
	{
                  var form = jQuery('#dashboard-form').get(0);
                    form.mod.value = '';
        form.view.value = '';
		jQuery('.btn-square').click(function()
		{
            if( this.dataset.mod === 'customers' )
                return true;
            
			
            if( this.dataset.mod === 'mb' )
            {
                if( form.keyword.value.trim().length <= 0 )
                {
                    alert('<?php _e('You need to enter the order number', 'digiprint'); ?>');
                    return false;
                }
               
            }
            else if( this.dataset.mod === 'quotes' )
            {
                if( form.keyword.value.trim().length <= 0 )
                {
                    alert('<?php _e('You need to enter the quote number', 'digiprint'); ?>');
                    return false;
                }
            }
            form.mod.value = this.dataset.mod;
            form.view.value = this.dataset.view;
			form.submit();
			return false;
		});
		jQuery('#dashboard-form').submit(function()
        {
            if( this.mod.value === '' || this.view.value === '' )
                return false;
        });
	});
	</script>
</div>