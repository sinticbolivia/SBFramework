<?php
$filter = SB_Request::getString('filter', 'last_week');
?>
<div class="wrap">
	<h2><?php print SBText::_('Conexion de usuarios por hora'); ?></h2>
	<ul class="view-buttons">
		<li <?php print $filter == 'last_week' ? 'class="current"' : ''; ?>>
			<a href="<?php print SB_Route::_('index.php?mod=statistics&view=graph_user_connections&filter=last_week'); ?>">
				<?php print SB_Text::_('Ultima Semana')?>
			</a> |
		</li>
		<li <?php print $filter == 'last_month' ? 'class="current"' : ''; ?>>
			<a href="<?php print SB_Route::_('index.php?mod=statistics&view=graph_user_connections&filter=last_month'); ?>">
				<?php print SB_Text::_('Ultimo Mes', 'statistics')?>
			</a> |
		</li>
		<li <?php print $filter == 'last_3months' ? 'class="current"' : ''; ?>>
			<a href="<?php print SB_Route::_('index.php?mod=statistics&view=graph_user_connections&filter=last_3months'); ?>">
				<?php print SB_Text::_('Ultimos 3 Meses')?>
			</a> |
		</li>
		<li <?php print $filter == 'last_6months' ? 'class="current"' : ''; ?>>
			<a href="<?php print SB_Route::_('index.php?mod=statistics&view=graph_user_connections&filter=last_6months'); ?>">
				<?php print SB_Text::_('Ultimos 6 Meses')?>
			</a> 
		</li>
	</ul>
	<div class="text-center">
		<h4><?php print $chart_title; ?></h4>
		<canvas id="connections-chart" width="800" height="400"></canvas>
	</div>
	<script>
	var data = <?php print json_encode($data); ?>;
	
	jQuery(function()
	{
		window.chart01_ctx = document.querySelector('#connections-chart').getContext('2d');
		window.chart01 = new Chart(chart01_ctx).Bar(data);
	});
	</script>
</div>