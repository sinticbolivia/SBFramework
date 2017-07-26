<?php
?>
<div class="wrap">
	<h1>
		<span class="glyphicon glyphicon-stats"></span>
		<?php print SB_Text::_('Accesos por contenidos', 'statistics'); ?>
	</h1>
	<?php include dirname(__FILE__) . SB_DS . 'contents_access_menu.php'; ?>
	<table class="table">
	<thead>
	<tr>
		<th class="text-center"><a href="#">#</a></th>
		<th class="text-center">
			<a href="<?php print $order_id_link; ?>" class="has-popover" data-content="<?php print SBText::_('STATS_CONT_TH_ID'); ?>">
				ID
				<span class="glyphicon glyphicon-triangle-<?php print (SB_Request::getString('content_id') == 'name' && SB_Request::getString('order', 'asc') == 'asc') ? 'bottom' : 'top'; ?>"></span>
			</a>
		</th>
		<th class="text-center">
			<a href="<?php print $order_name_link; ?>" class="has-popover" data-content="<?php print SBText::_('STATS_CONT_TH_ARTICLE'); ?>">
				<?php print SB_Text::_('Contenido', 'statistics'); ?>
				<span class="glyphicon glyphicon-triangle-<?php print (SB_Request::getString('order_by') == 'name' && SB_Request::getString('order', 'asc') == 'asc') ? 'bottom' : 'top'; ?>"></span>
			</a>
		</th>
		<th class="text-center">
			<a href="<?php print $order_views_link; ?>" class="has-popover" data-content="<?php print SBText::_('STATS_CONT_TH_ACCESS'); ?>">
				<?php print SB_Text::_('Accesos', 'statistics'); ?>
				<span class="glyphicon glyphicon-triangle-<?php print (SB_Request::getString('order_by') == 'views' && SB_Request::getString('order', 'asc') == 'asc') ? 'bottom' : 'top'; ?>"></span>
			</a>
		</th>
	</tr>
	</thead>
	<tbody>
	<?php $i = 1; foreach($articles as $a): ?>
	<tr>
		<td class="text-center"><?php print $i; ?></td>
		<td class="text-center"><?php print $a->content_id; ?></td>
		<td><?php print $a->title; ?></td>
		<td class="text-center"><?php print $a->views; ?></td>
	</tr>
	<?php $i++; endforeach; ?>
	</tbody>
	</table>
	<?php lt_pagination(SB_Route::_('index.php?mod=statistics'), $total_pages, $current_page); ?>
</div>