<div id="product-category-container">
	<h1 id="page-title"><?php print $category->name; ?></h1>
	<?php lt_include_partial('emono', 'products_filter.php', array()); ?>
	<div id="product_list" class="row">
	<?php foreach($products as $p): ?>
		<?php 
		lt_include_partial('emono', 'product.php', array('p' => $p));
		?>
	<?php endforeach; ?>
	</div>
</div><!-- end id="product-category-container" -->
<?php if( !(int)@$this->ops->infinite_scroll ): ?>
	<?php lt_pagination(SB_Route::_('index.php?'.$_SERVER['QUERY_STRING']), $total_pages, $current_page); ?>
<?php elseif( (int)@$this->ops->infinite_scroll && $total_pages > 1 ): ?>
<p class="infinite-scroll-loading text-center alert alert-info" style="display:block;">
	<a href="javascript:;" id="btn-load-more" class="btn" style="display:block;background:#449bd0;color:#fff;">
		<?php _e('Load more', 'ps'); ?>
	</a>
	<span id="infinite-scroll-loading" style="display:none;">
		<?php _e('Loading...', 'emono'); ?><br />
		<img src="<?php print BASEURL; ?>/images/spin.gif" />
	</span>
</p>
<?php endif; ?>
<?php if( $total_pages > 1 && $current_page != -1 ): ?>
<p class="text-center">
	<span class="alert alert-info">
		<a href="<?php print 'index.php?'.sb_querystring_append($_SERVER['QUERY_STRING'], array('limit' => -1)); ?>" 
			class="btn" style="background:#449bd0;color:#fff;"><?php _e('View all', 'ps'); ?>
		</a>
	</span>
</p>
<?php endif; ?>
<div id="product-preview" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title"><?php _e('Product Quick View', 'ps'); ?></h4>
			</div>
			<div class="modal-body">
			</div>
		</div>
	</div>
</div>
<script>
jQuery(function()
{
	jQuery('.btn-quick-view').click(function(e)
	{
		var modal = jQuery('#product-preview');
		modal.find('.modal-body').html('Loading...');
		var iframe = '<iframe src="'+this.href+'" style="width:100%;height:500px;" frameborder="0"></iframe>';
		
		modal.find('.modal-body').html(iframe);
		modal.modal('show');
		return false;
	});
});
</script>