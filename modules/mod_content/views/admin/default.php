<?php
?>
<div class="wrap">
	<h2 id="page-title">
		<div class="container-fluid">
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"><?php print $title; ?></div>
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
				<div class="page-buttons">
					<a class="btn btn-primary has-popover" href="<?php print $new_link; ?>" 
						data-content="<?php print SBText::_('CONTENT_BUTTON_NEW'); ?>">
						<?php print $button_new_label; ?>
					</a>
				</div>
			</div>
		</div>
	</h2>
	<div class="row">
		<div class="col-md-6">
			<form action="" method="get" class="">
				<input type="hidden" name="mod" value="content" />
				<input type="hidden" name="view" value="default" />
				<div class="input-group">
					<input type="text" name="keyword" value="" class="form-control" />
					<span class="input-group-btn">
						<button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span></button>
					</span>
				</div>
				<div>&nbsp;</div>
			</form>
		</div>
	</div>
	<table class="table table-condensed">
	<thead>
	<tr>
		<th>#</th>
		<th>
			<a href="<?php print $id_order_link; ?>" class="has-popover" data-content="<?php print SBText::_('CONTENT_TH_ID'); ?>">
				<?php _e('ID', 'content'); ?>
				<span class="glyphicon glyphicon-triangle-<?php print (SB_Request::getString('order_by') == 'content_id' && SB_Request::getString('order', 'asc') == 'asc') ? 'bottom' : 'top'; ?>"></span>
			</a>
		</th>
		<th>
			<a href="<?php print $title_order_link; ?>" class="has-popover" data-content="<?php print SBText::_('CONTENT_TH_TITLE'); ?>" >
				<?php _e('Title', 'content'); ?>
				<span class="glyphicon glyphicon-triangle-<?php print (SB_Request::getString('order_by') == 'title' && SB_Request::getString('order', 'asc') == 'asc') ? 'bottom' : 'top'; ?>"></span>
			</a>
		</th>
		<th>
			<a href="<?php print $author_order_link; ?>" class="has-popover" data-content="<?php print SBText::_('CONTENT_TH_AUTHOR'); ?>">
				<?php _e('Author', 'content'); ?>
				<span class="glyphicon glyphicon-triangle-<?php print (SB_Request::getString('order_by') == 'author' && SB_Request::getString('order', 'asc') == 'asc') ? 'bottom' : 'top'; ?>"></span>
			</a>
		</th>
		<th><a href="javascript:;"><?php _e('Sections', 'content'); ?></a></th>
		<th>
			<a href="<?php print $order_link; ?>" class="has-popover" data-content="<?php print SBText::_('CONTENT_TH_ORDER'); ?>">
				<?php _e('Order', 'content'); ?>
				<span class="glyphicon glyphicon-triangle-<?php print (SB_Request::getString('order_by') == 'show_order' && SB_Request::getString('order', 'asc') == 'asc') ? 'bottom' : 'top'; ?>"></span>
			</a>
		</th>
		<th>
			<a href="<?php print $date_order_link; ?>" class="has-popover" data-content="<?php print SBText::_('CONTENT_TH_DATE'); ?>">
				<?php _e('Date', 'content'); ?>
				<span class="glyphicon glyphicon-triangle-<?php print (SB_Request::getString('order_by') == 'creation_date' && SB_Request::getString('order', 'asc') == 'asc') ? 'bottom' : 'top'; ?>"></span>
			</a>
		</th>
	</tr>
	</thead>
	<tbody>
	<?php $i = 1; foreach($contents as $c): //var_dump($c->sections);?>
	<tr>
		<td><?php print $i; ?></td>
		<td><?php print $c->content_id; ?></td>
		<td>
			<p><?php print $c->title; ?></p>
			<div>
				<a href="<?php print SB_Route::_('index.php?mod=content&view=edit&id='.$c->content_id); ?>" 
					title="<?php print SB_Text::_('Edit', 'content')?>"
					class="btn btn-default btn-xs">
					<span class="glyphicon glyphicon-edit"></span>
				</a>
				<a href="<?php print SB_Route::_('index.php?mod=content&task=delete&id='.$c->content_id); ?>" 
					class="confirm btn btn-default btn-xs" 
					data-message="<?php print SBText::_('Seguro que desea borrar este contenido?'); ?>"
					title="<?php print SB_Text::_('Delete', 'content')?>">
					<span class="glyphicon glyphicon-trash"></span>
				</a>
			</div>
		</td>
		<td>
			<?php 
			//$author = new SB_User($c->author_id);
			//printf("%s %s", $author->first_name, $author->last_name);
			print $c->author; 
			?>
		</td>
		<td>
			<?php 
			$sections = '';
			foreach($c->sections as $s){$sections .= $s->name . ',';}
			print rtrim($sections, ','); 
			?>
		</td>
		<td width="90">
			<input type="number" min="0" name="order" value="<?php print $c->show_order; ?>" class="change-order form-control" data-id="<?php print $c->content_id; ?>" />
		</td>
		<td>
			<table class="0">
			<tr><td><b><?php _e('Creation:', 'content'); ?></b></td><td><?php print sb_format_datetime($c->creation_date); ?></td></tr>
			<?php if( $features['use_dates'] ): ?>
			<tr><td><b><?php _e('Publish:', 'content'); ?></b></td><td><?php print sb_format_datetime($c->publish_date); ?></td></tr>
			<tr><td><b><?php _e('Expiration:', 'content'); ?></b></td><td><?php print sb_format_datetime($c->end_date); ?></td></tr>
			<?php endif; ?>
			</table>
		</td>
	</tr>
	<?php $i++; endforeach; ?>
	</tbody>
	</table>
	<?php lt_pagination(SB_Route::_('index.php?'.$_SERVER['QUERY_STRING']), $total_pages, $current_page); ?>
</div>
<script>
jQuery(function()
{
	jQuery('.change-order').keyup(function(e)
	{
		if( e.keyCode == 13 )
		{
			//var form = jQuery('<form></form>');
			window.location = 'index.php?mod=content&task=change_order&id='+this.dataset.id+'&order='+parseInt(this.value);
		}
	});
});
</script>