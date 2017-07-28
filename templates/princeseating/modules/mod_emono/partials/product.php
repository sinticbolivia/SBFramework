<?php
extract($data);
$link = $p->link;
$featured = $p->getFeaturedImage('330x330');
?>
<!--
<?php //print_r($featured);  ?>
-->
<div class="product col-xs-12 col-sm-6 col-md-3">
	<div class="image" data-id="<?php print $featured->id; ?>" data-fid="<?php print $p->_featured_image_id; ?>">
		<a href="<?php print $link; ?>">
			<img src="<?php print $featured->GetUrl(); ?>" 
				alt="<?php print $p->product_name; ?>" />
		</a>
	</div>
	<div class="name"><?php print $p->product_name; ?></div>
	<div class="code"><a href="<?php print $link ?>"><?php print $p->product_code; ?></a></div>
	<div class="options">
		<a href="<?php print SB_Route::_('index.php?task=ps_specsheet&id='.$p->product_id); ?>" 
			class="btn btn-primary option">
			<?php _e('View Spec Sheet', 'ps'); ?>
		</a>
		<a href="<?php print $link; ?>&tpl_file=module" class="btn btn-default text-center option btn-quick-view">
			<span class="glyphicon glyphicon-search"></span>
		</a>
	</div>
</div>