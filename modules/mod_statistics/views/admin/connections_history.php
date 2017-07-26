<?php
?>
<div class="wrap">
	<h2><?php print SBText::_('Historial de Conexiones', 'statistics'); ?></h2>
	<?php include dirname(__FILE__) . SB_DS . 'connections-menu.php'; ?> 
	<form action="<?php print SB_Route::_('index.php') ?>" method="get">
		<input type="hidden" name="mod" value="statistics" />
		<input type="hidden" name="view" value="connections_history" />
		<input type="hidden" name="page" value="<?php print 1; ?>" />
		<input type="hidden" name="order_by" value="<?php print $order_by; ?>" />
		<div class="form-horizontal">
			<div class="form-group">
				<label class="col-sm-1 control-label has-popover" data-content="<?php print SBText::_('STATS_DATE_FROM'); ?>">
					<?php print SBText::_('Desde:', 'statistics'); ?>
				</label>
				<div class="col-sm-3">
					<input type="text" name="dfrom" value="<?php print SB_Request::getString('dfrom', sb_format_date(strtotime('2010-12-10'))); ?>" class="form-control datepicker" />
				</div>
				<div class="col-sm-1">
					<label class="control-label has-popover" data-content="<?php print SBText::_('STATS_DATE_TO'); ?>">
						<?php print SBText::_('Hasta:', 'statistics'); ?></label>
				</div>
				<div class="col-sm-3">
					<input type="text" name="dto" value="<?php print SB_Request::getString('dto', sb_format_date(mktime(0, 0, 0, 12, 10, date('Y')))); ?>" class="form-control datepicker" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-1 control-label has-popover" data-content="<?php print SBText::_('STATS_SEARCH_IDS'); ?>">
					<?php print SBText::_('IDS:', 'statistics'); ?></label>
				<div class="col-sm-6">
					<input type="text" name="ids" value="" class="form-control" />
				</div>
				<div class="col-sm-1">
					<button type="submit" class="btn btn-default has-popover" data-content="<?php print SBText::_('STATS_BUTTON_FILTER'); ?>">
						<?php print SBText::_('Filtrar'); ?></button></div>
			</div>
		</div>
			
	</form>
	<table class="table">
	<thead>
	<tr>
		<th>#</th>
		<th>
			<a href="<?php print $order_id_link; ?>" class="has-popover" data-content="<?php print SBText::_('STATS_CON_H_TH_ID'); ?>">
				<?php print SBText::_('Id'); ?>
				<span class="glyphicon glyphicon-triangle-<?php print (SB_Request::getString('order_by') == 'user_id' && SB_Request::getString('order', 'asc') == 'asc') ? 'bottom' : 'top'; ?>"></span>
			</a>
		</th>
		<th>
			<a href="<?php print $order_firstname_link; ?>" class="has-popover" data-content="<?php print SBText::_('STATS_CON_H_TH_FNAME'); ?>">
				<?php print SBText::_('Nombre', 'statistics'); ?>
				<span class="glyphicon glyphicon-triangle-<?php print (SB_Request::getString('order_by') == 'first_name' && SB_Request::getString('order', 'asc') == 'asc') ? 'bottom' : 'top'; ?>"></span>
			</a>
		</th>
		<th>
			<a href="<?php print $order_lastname_link; ?>" class="has-popover" data-content="<?php print SBText::_('STATS_CON_H_TH_LNAME'); ?>">
				<?php print SBText::_('Apellidos', 'statistics'); ?>
				<span class="glyphicon glyphicon-triangle-<?php print (SB_Request::getString('order_by') == 'last_name' && SB_Request::getString('order', 'asc') == 'asc') ? 'bottom' : 'top'; ?>"></span>
			</a>
		</th>
		<th>
			<a href="<?php print $order_creation_date_link; ?>" class="has-popover" data-content="<?php print SBText::_('STATS_CON_H_TH_DATE'); ?>">
				<?php print SBText::_('Fecha y Hora', 'statistics'); ?>
				<span class="glyphicon glyphicon-triangle-<?php print (SB_Request::getString('order_by') == 'creation_date' && SB_Request::getString('order', 'asc') == 'asc') ? 'bottom' : 'top'; ?>"></span>
			</a>
		</th>
	</tr>
	</thead>
	<tbody>
	<?php $i = 1; foreach($records as $r): ?>
	<tr>
		<td><?php print $i; ?></td>
		<td><?php print $r->user_id; ?></td>
		<td><?php print $r->first_name; ?></td>
		<td><?php print $r->last_name; ?></td>
		<td><?php print sb_format_datetime($r->creation_date); ?></td>
	</tr>
	<?php $i++; endforeach; ?>
	</tbody>
	</table>
	<?php lt_pagination(SB_Route::_('index.php?'.$_SERVER['QUERY_STRING']), $total_pages, $current_page); ?>
</div>