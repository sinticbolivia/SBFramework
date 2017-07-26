<?php
?>
<div class="wrap">
	<h1>
		<?php _e('Entradas', 'content'); ?>
		<span class="pull-right">
			<a class="btn btn-secondary has-popover" href="<?php print SB_Route::_('index.php?mod=content&view=new&type=post'); ?>" 
				data-content="<?php print SBText::_('CONTENT_BUTTON_NEW'); ?>">
				<?php print SB_Text::_('Nuevo', 'content'); ?>
			</a>
		</span>
	</h1>
	<div class="row">
		<div class="col-md-6">
			<form action="" method="get" class="">
				<input type="hidden" name="mod" value="content" />
				<input type="hidden" name="view" value="posts.default" />
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
	
	<table class="table">
	<thead>
	<tr>
		<th>#</th>
		<th>
			<a href="<?php print $id_order_link; ?>" class="has-popover" data-content="<?php print SBText::_('CONTENT_TH_ID'); ?>">
				<?php print SB_Text::_('ID', 'content'); ?>
				<span class="glyphicon glyphicon-triangle-<?php print (SB_Request::getString('order_by') == 'content_id' && SB_Request::getString('order', 'asc') == 'asc') ? 'bottom' : 'top'; ?>"></span>
			</a>
		</th>
		<th>
			<a href="<?php print $title_order_link; ?>" class="has-popover" data-content="<?php print SBText::_('CONTENT_TH_TITLE'); ?>" >
				<?php print SB_Text::_('Titulo', 'content'); ?>
				<span class="glyphicon glyphicon-triangle-<?php print (SB_Request::getString('order_by') == 'title' && SB_Request::getString('order', 'asc') == 'asc') ? 'bottom' : 'top'; ?>"></span>
			</a>
		</th>
		<th>
			<a href="<?php print $author_order_link; ?>" class="has-popover" data-content="<?php print SBText::_('CONTENT_TH_AUTHOR'); ?>">
				<?php print SB_Text::_('Autor', 'content'); ?>
				<span class="glyphicon glyphicon-triangle-<?php print (SB_Request::getString('order_by') == 'author' && SB_Request::getString('order', 'asc') == 'asc') ? 'bottom' : 'top'; ?>"></span>
			</a>
		</th>
		<th><a href="javascript:;"><?php print SB_Text::_('Categorias', 'content'); ?></a></th>
		<th>
			<a href="<?php print $order_link; ?>" class="has-popover" data-content="<?php print SBText::_('CONTENT_TH_ORDER'); ?>">
				<?php print SBText::_('Orden', 'content'); ?>
				<span class="glyphicon glyphicon-triangle-<?php print (SB_Request::getString('order_by') == 'show_order' && SB_Request::getString('order', 'asc') == 'asc') ? 'bottom' : 'top'; ?>"></span>
			</a>
		</th>
		<th>
			<a href="<?php print $date_order_link; ?>" class="has-popover" data-content="<?php print SBText::_('CONTENT_TH_DATE'); ?>">
				<?php print SB_Text::_('Fecha', 'content'); ?>
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
					title="<?php print SB_Text::_('Editar', 'content')?>" class="btn btn-default">
					<span class="glyphicon glyphicon-pencil"></span>
				</a>
				<a href="<?php print SB_Route::_('index.php?mod=content&task=delete&id='.$c->content_id); ?>" class="confirm btn btn-default" 
					data-message="<?php print SBText::_('Seguro que desea borrar este contenido?'); ?>"
					title="<?php print SB_Text::_('Borrar', 'content')?>">
					<span class="glyphicon glyphicon-trash"></span>
				</a>
			</div>
		</td>
		<td><?php print $c->author; ?></td>
		<td>
			<?php 
			?>
		</td>
		<td width="90">
			<input type="number" min="0" name="order" value="<?php print $c->show_order; ?>" class="change-order form-control" data-id="<?php print $c->content_id; ?>" />
		</td>
		<td>
			<table class="0">
			<tr><td><b><?php print SB_Text::_('Creacion:', 'content'); ?></b></td><td><?php print sb_format_datetime($c->creation_date); ?></td></tr>
			<tr><td><b><?php print SB_Text::_('Publicacion:', 'content'); ?></b></td><td><?php print sb_format_datetime($c->publish_date); ?></td></tr>
			<tr><td><b><?php print SB_Text::_('Caducidad:', 'content'); ?></b></td><td><?php print sb_format_datetime($c->end_date); ?></td></tr>
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