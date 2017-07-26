<?php
$filter = SB_Request::getString('filter', 'last_month');
?>
<div class="container">
	<h2><?php print SBText::_('Conexion de usuarios por d&iacute;a', 'statistics'); ?></h2>
	<ul class="view-buttons">
		<li <?php print $filter == 'last_month' ? 'class="current"' : ''; ?>>
			<a href="<?php print SB_Route::_('index.php?mod=statistics&view=graph_user_connections_daily&filter=last_month'); ?>">
				<?php print SB_Text::_('Ultimo mes')?>
			</a> |
		</li>
		<li <?php print $filter == 'last_3months' ? 'class="current"' : ''; ?>>
			<a href="<?php print SB_Route::_('index.php?mod=statistics&view=graph_user_connections_daily&filter=last_3months'); ?>">
				<?php print SB_Text::_('Ultimos 3 meses', 'statistics')?>
			</a> |
		</li>
		<li <?php print $filter == 'last_6months' ? 'class="current"' : ''; ?>>
			<a href="<?php print SB_Route::_('index.php?mod=statistics&view=graph_user_connections_daily&filter=last_6months'); ?>">
				<?php print SB_Text::_('Ultimos 6 meses')?>
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