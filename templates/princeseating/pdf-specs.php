<?php
$attr 		= json_decode($product->_attributes);
//##check if attributes are in correct format
if( !isset($attr->height) )
{
	$new_attr = (object)array(
		'height'		=> '',
		'seat_height'	=> '',
		'width'			=> '',
		'depth'			=> '',
		'weight'		=> ''
	);
	if( $attr )
	{
		foreach($attr as $a)
		{
			if( $a->taxonomy == 'pa_height' )
				$new_attr->height = $a->value;
			elseif( $a->taxonomy == 'pa_seat-height' )
				$new_attr->seat_height = $a->value;
			elseif( $a->taxonomy == 'pa_width' )
				$new_attr->width = $a->value;
			elseif( $a->taxonomy == 'pa_depth' )
				$new_attr->depth = $a->value;
			elseif( $a->taxonomy == 'pa_weight' )
				$new_attr->weight = $a->value;
		}
	}
	$attr = $new_attr;
}
$logo_url = sb_get_template_url() . '/images/logo.png';
?>
<style>
*{font-family:Helvetica,Verdana,Arial;font-size:12px;}
#product-fabrics{width:100%;}
</style>
<div id="header" style="text-align:center;">
	<img src="<?php print $logo_url; ?>" alt="" />
</div>
<div>
	<div id="image" style="text-align:center;">
		<img src="<?php print $product->getFeaturedImage()->GetUrl(); ?>" alt="" style="width:80%;" />
	</div>
	<hr/>
	<table style="width:100%;">
	<tr>
		<td style="width:33.33%;vertical-align:top;">
			<h2><?php print $product->product_name; ?></h2>
			<table>
			<tr>
				<td><b><?php _e('Model#:', 'ps'); ?></b></td><td><?php print $product->product_code; ?></td>
			</tr>
			<tr>
				<td><b><?php _e('Height:', 'ps'); ?></b></td><td><?php print $attr->height; ?></td>
			</tr>
			<tr>
				<td><b><?php _e('Width:', 'ps'); ?></b></td><td><?php print $attr->width; ?></td>
			</tr>
			<tr>
				<td><b><?php _e('Depth:', 'ps'); ?></b></td><td><?php print $attr->depth; ?></td>
			</tr>
			<tr>
				<td><b><?php _e('Seat Height:', 'ps'); ?></b></td><td><?php print $attr->seat_height; ?></td>
			</tr>
			<tr>
				<td><b><?php _e('Materials:', 'ps'); ?></b></td><td><?php ?></td>
			</tr>
			</table>
		</td>
		<td style="width:33.33%;vertical-align:top;">
			<h2><?php _e('Description', 'ps'); ?></h2>
			<?php print $product->product_description; ?>
		</td>
		<td style="width:33.33%;vertical-align:top;">
			<h2><?php _e('Features', 'ps'); ?></h2>
			<?php print $product->_features; ?>
		</td>
	</tr>
	</table>
	<?php  $_fabrics = json_decode($product->_fabrics); if( is_array($_fabrics) && count($_fabrics) ): ?>
	<div id="fabrics">
		<h2><?php _e('Fabrics', 'ps'); ?></h2>
		<?php 
		sb_include_module_helper('content');
		$fabrics = LT_HelperContent::GetArticles(array(
			'type'			=> 'fabrics',
			'rows_per_page'	=> -1,
			'order_by'		=> 'title',
			'order'			=> 'asc',
			'in_ids' 		=> (array)$_fabrics
		));
		?>
		<table id="product-fabrics">
			<?php $i = 0; foreach($fabrics['articles'] as $f): ?>
			<?php if( $i == 0 ): ?><tr><?php endif; ?>
			<td class="fabric">
				<span class="image"><?php print $f->TheThumbnail(); ?></span><br/>
				<span class="title"><?php print $f->title; ?></span>
			</td>
			<?php $i++; if( $i == 5 ): $i = 0; ?></tr><?php endif; ?>
			<?php endforeach; ?>
		</table>
	</div><!-- end id="fabrics" -->
	<?php endif; ?>
	<?php $_finishes = json_decode($product->_finishes); if( is_array($_finishes) && count($_finishes) ): ?>
	<div id="finishes">
		<h2><?php _e('Finishes', 'ps'); ?></h2>
		<?php 
		sb_include_module_helper('content');
		$finishes = LT_HelperContent::GetArticles(array(
			'type'			=> 'finishes',
			'rows_per_page'	=> -1,
			'order_by'		=> 'title',
			'order'			=> 'asc',
			'in_ids' 		=> (array)$_finishes
		));
		?>
		<table id="product-fabrics">
			<?php $i = 0; foreach($finishes['articles'] as $f): ?>
			<?php if( $i == 0 ): ?><tr><?php endif; ?>
			<td class="fabric">
				<span class="image"><?php print $f->TheThumbnail(); ?></span><br/>
				<span class="title"><?php print $f->title; ?></span>
			</td>
			<?php $i++; if( $i == 5 ): $i = 0; ?></tr><?php endif; ?>
			<?php endforeach; ?>
		</table>
	</div><!-- end id="finishes" -->
	<?php endif; ?>
</div>
<div id="footer" style="position:fixed;bottom:0;padding:7px;border-top:1px solid #000;color:#bcbcbc;">
	<div style="text-align:center;font-size:11px;">
		<?php print $ops->business_address; ?>
		Ph: <?php print $ops->business_phone; ?>
		Em: sales@princeseating.com www.princeseating.com
	</div>
</div>