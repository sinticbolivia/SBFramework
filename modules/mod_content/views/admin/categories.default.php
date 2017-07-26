<?php
?>
<div class="wrap">
	<h1><?php print SB_Text::_('Categorias', 'content'); ?></h1>
	<ul class="view-buttons">
		<li>
			<a class="btn btn-secondary has-popover" href="<?php print SB_Route::_('index.php?mod=content&view=categories.new'); ?>"
				data-content="<?php print SBText::_('SECTION_BUTTON_NEW'); ?>">
				<?php print SB_Text::_('Nuevo', 'content'); ?></a></li>
	</ul>
	<table class="table">
	<thead>
	<tr>
		<th>#</th>
		<th>
			<a href="<?php print $id_order_link; ?>" class="has-popover" data-content="<?php print SBText::_('SECTION_TH_ID'); ?>">
				<?php print SB_Text::_('ID', 'content'); ?>
				<span class="glyphicon glyphicon-triangle-<?php print (SB_Request::getString('order_by') == 'content_id' && SB_Request::getString('order', 'asc') == 'asc') ? 'bottom' : 'top'; ?>"></span>
			</a>
		</th>
		<th>
			<a href="<?php print $name_order_link; ?>" class="has-popover" data-content="<?php print SBText::_('SECTION_TH_NAME'); ?>">
				<?php print SB_Text::_('Name', 'content'); ?>
				<span class="glyphicon glyphicon-triangle-<?php print (SB_Request::getString('order_by') == 'content_id' && SB_Request::getString('order', 'asc') == 'asc') ? 'bottom' : 'top'; ?>"></span>
			</a>
		</th>
		<th>
			<a href="<?php print $order_link; ?>" class="has-popover" data-content="<?php print SBText::_('SECTION_TH_ORDER'); ?>">
				<?php print SB_Text::_('Order', 'content'); ?>
				<span class="glyphicon glyphicon-triangle-<?php print (SB_Request::getString('order_by') == 'show_order' && SB_Request::getString('order', 'asc') == 'asc') ? 'bottom' : 'top'; ?>"></span>
			</a>
		</th>
		<th>
			<a href="<?php print $date_order_link?>" class="has-popover" data-content="<?php print SBText::_('SECTION_TH_DATE'); ?>">
				<?php print SB_Text::_('Creation Date', 'content'); ?>
				<span class="glyphicon glyphicon-triangle-<?php print (SB_Request::getString('order_by') == 'content_id' && SB_Request::getString('order', 'asc') == 'asc') ? 'bottom' : 'top'; ?>"></span>
			</a>
		</th>
		<th>
			<a href="#" class="has-popover" data-content="<?php print SBText::_('SECTION_TH_ACTIONS'); ?>">
				<?php print SB_Text::_('Action', 'content'); ?>
			</a>
		</th>
	</tr>
	</thead>
	<tbody>
	<?php $i = 1; foreach($categories as $c): ?>
	<tr>
		<td><?php print $i; ?></td>
		<td><?php print $c->category_id; ?></td>
		<td><?php print $c->name; ?></td>
		<td width="90">
			<input type="number" min="0" name="order" value="<?php print $s->show_order; ?>" class="form-control change-order" 
				data-id="<?php print $c->category_id; ?>" />
		</td>
		<td><?php print $c->creation_date; ?></td>
		<td>
			<a href="<?php print SB_Route::_('index.php?mod=content&view=categories.edit&id='.$c->category_id); ?>"
				title="<?php print SB_Text::_('Editar', 'content'); ?>" class="btn btn-default">
				<span class="glyphicon glyphicon-edit"></span>
			</a>
			<a href="<?php print SB_Route::_('index.php?mod=content&task=categories.delete&id='.$c->category_id); ?>" class="confirm btn btn-default"
				title="<?php print SB_Text::_('Borrar', 'content'); ?>" 
				data-message="<?php print SB_Text::_('Are you sure to delete the category?', 'content'); ?>">
				<span class="glyphicon glyphicon-trash"></span>
			</a>
		</td>
	</tr>
	<?php $i++; endforeach; ?>
	</tbody>
	</table>
	<?php lt_pagination(SB_Route::_('index.php?'.$_SERVER['QUERY_STRING']), $total_pages, $current_page); ?>
	<script>
	jQuery(function()
	{
		jQuery('.change-order').keyup(function(e)
		{
			if( e.keyCode == 13 )
			{
				window.location = 'index.php?mod=content&task=section.change_order&id='+this.dataset.id+'&order='+parseInt(this.value);
			}
		});
	});
	</script>
</div>