<div id="search-results">
	<h1><?php print $title; ?></h1>
	<div class="clearfix"></div>
	<?php foreach($items as $item): ?>
	<div class="row">
		<div class="col-xs-12 col-sm-3 col-md-3">
			<a href="<?php print $item->link; ?>"><?php print $item->TheThumbnail(); ?></a>
		</div>
		<div class="col-xs-12 col-sm-9 col-md-9">
			<h2><a href="<?php print $item->link; ?>"><?php print $item->TheTitle(); ?></a></h2>
			<div><?php print $item->TheExcerpt(); ?></div>
		</div>
	</div>
	<?php endforeach; ?>
	<?php lt_pagination(SB_Route::_('index.php?'.$_SERVER['QUERY_STRING']), $total_pages, $current_page); ?>
</div>