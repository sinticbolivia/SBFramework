<?php
?>
<div id="sidebar" class="col-xs-12 col-md-3">
	<?php /*
	<div class="widget">
		<div class="body">
			<form action="<?php print SB_Route::_('index.php?mod=emono'); ?>" method="get">
				<input type="hidden" name="mod" value="emono" />
				<div class="input-group">
					<input type="text" name="keyword" value="" placeholder="<?php _e('Search...', 'ps'); ?>" 
						class="form-control" />
					<div class="input-group-btn">
						<button type="submit" class="btn btn-blue"><?php _e('Search...', 'ps'); ?></button>
					</div>
				</div>
			</form>
		</div>
	</div>
	*/?>
	<?php if( !sb_show_widget('SB_MBWidgetCategories', array()) ): ?>
	<?php endif; ?>
</div>