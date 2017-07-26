<?php
$template_url = TEMPLATES_URL . '/elitebolivia';
$print_prices = (int)$quote->_print_prices;
$user = $quote->user_id && (int)$quote->user_id > 0 ? new SB_User($quote->user_id) 
													: (object)array(
														'first_name'	=> 'Sitio',
														'last_name'		=> 'Web'
													);
?>
<style>
@page 
{
    size: Letter/*A4*/;
    margin: 0.5cm 2cm 2.5cm 2cm;
	font-family: Arial, Verdana, Helvetica;
	font-size:12px;
}
/*#header{position:fixed;top:-1.8cm;height:50px;overflow:hidden;border:0px solid #000;font-size:12px;}*/
#footer{height:50px;text-align:center;position:fixed;bottom:-1.8cm;font-size:10px;border-top:1px solid #000;}
#quote-container{font-family: Arial, Verdana, Helvetica;font-size:12px;margin:0;}
#quote-table{font-size:12px;border-collapse: collapse;width:100%;}
.col{border:1px solid #bcbcbc;padding:2px;}
th.col{background-color:#F2F2F2;color:#000;text-align:center;}
.col-num{width:6%;text-align:center;}
.col-code{width:10%;text-align:center;}
.col-name{width:47%;}
.col-qty{width:6%;text-align:center;}
.col-price{width:11%;text-align:right;}
.col-total{width:11%;text-align:right;}
.table-border{border-collapse:collapse;}
.table-border td,.table-border th{padding:2px;border:1px solid #000;}
</style>
<?php
$logo 		= UPLOADS_DIR . SB_DS . $business->business_logo;
$logo_url 	= UPLOADS_URL . '/' . $business->business_logo;
?>
<div id="header">
	<table style="width:100%;">
	<tr>
		<td style="width:15%;text-align:center;">
			<img src="<?php print $logo; ?>" style="height:70px;" />
		</td>
		<td style="width:85%;">
			<p style="color:#9a0e0e;text-align:right;">SU SEGURIDAD ES NUESTRO TRABAJO</p>
			<table style="width:100%;" class="table-border">
			<tr>
				<td>Solicita:</td>
				<td><?php print $quote->customer->GetBillingName(); ?></td>
				<td>Fecha:</td>
				<td><?php print sb_format_date($quote->quote_date); ?></td>
			</tr>
			<tr>
				<td>Direcci&oacute;n:</td>
				<td><?php print $quote->customer->address_1; ?></td>
				<td>Validez:</td>
				<td>15 dias</td>
			</tr>
			<tr>
				<td>Cotizado por:</td>
				<td colspan="3"><?php printf("%s %s", $user->first_name, $user->last_name);  ?></td>
			</tr>
			</table>
		</td>
	</tr>
	</table>
</div>
<div id="quote-container">
	
	<table style="width:100%;">
	<tr>
		<td style="width:75%;">
			&nbsp;
		</td>
		<td style="width:25%;">
			<table style="width:100%;" class="table-border">
			<tr>
				<td style="width:50%;text-align:center;color:#9a0e0e;">ORIGINAL CLIENTE</th>
				<td style="width:50%;text-align:center;color:#9a0e0e;">
					COTIZACION:<br/>
					<?php print sb_fill_zeros($quote->sequence); ?>
				</td>
			</tr>
			</table>
		</td>
	</tr>
	</table>
	<table id="quote-table">
	<thead>
	<tr>
		<th class="col col-num"><?php _e('No.', 'quotes'); ?></th>
		<th class="col col-code"><?php _e('Code', 'quotes'); ?></th>
		<th class="col col-name"><?php _e('Product', 'quotes'); ?></th>
		<th class="col col-image"><?php _e('Imagen', 'quotes'); ?></th>
		<th class="col col-qty"><?php _e('Qty', 'quotes'); ?></th>
		<th class="col col-price"><?php _e('Price', 'quotes'); ?></th>
		<th class="col col-total"><?php _e('Total', 'quotes'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php $i = 1; foreach($quote->GetItems() as $item): $prod = new SB_MBProduct($item->product_id);?>
	<tr>
		<td class="col col-num"><?php print $i; ?></td>
		<td class="col col-code"><?php print $item->item_code; ?></td>
		<td class="col col-name">
			<?php print $item->name; ?><br/>
			<?php if( $prod->product_id ): ?>
			<?php print $prod->product_description; ?>
			<?php endif; ?>
		</td>
		<td>
			<?php if( $prod->product_id ): ?>
			<img src="<?php print $prod->getFeaturedImage('55x55')->GetUrl(); ?>" width="55" />
			<?php endif; ?>
		</td>
		<td class="col col-qty"><?php print $item->quantity; ?></td>
		<td class="col col-price"><?php print $print_prices ? sb_number_format($item->price) : '0.00'; ?></td>
		<td class="col col-total"><?php print $print_prices ? sb_number_format($item->total) : '0.00'; ?></td>
	</tr>
	<?php $i++; endforeach; ?>
	</tbody>
	<tfoot>
	<?php /*
	<tr>
		<td colspan="4" style="border:1px solid #fff;border-right:1px solid #000;">&nbsp;</td>
		<td style="text-align:right;font-weight:bold;" class="col"><?php _e('Tax:', 'quotes'); ?></td>
		<td style="text-align:right;" class="col"><?php print $quote->total_tax; ?></td>
	</tr>
	*/?>
	<tr>
		<td colspan="5" style="border:1px solid #fff;border-right:1px solid #000;">&nbsp;</td>
		<td style="text-align:right;font-weight:bold;" class="col"><?php _e('Total:', 'quotes'); ?></td>
		<td style="text-align:right;" class="col"><?php print sb_number_format($quote->total); ?></td>
	</tr>
	</tfoot>
	</table>
	<div>
		<b><?php _e('Notes:', 'quotes'); ?></b><br/>
		<?php 
		if( !empty($quote->notes) ) 
			html_entity_decode($quote->notes); 
		else
		{
			?>
			<ul>
				<li>Tiempo de entrega 24 hrs</li>
				<li>Incluye instalaci&oacute;n</li>
				<li>Garantia de 12 meses</li>
			</ul>
			<?php
		}
		?>
	</div>
	<br/>
	<div><?php print SB_Request::getString('view') == 'print' ? html_entity_decode($quote->_content) : html_entity_decode($quote->_content); ?></div>
	<br/>
</div>
<div id="footer">
	<div style="color:#9a0e0e;">DISE&Ntilde;O, VENTA, INSTALACION DE SISTEMAS ELECTRONICOS DE SEGURIDAD</div>
	<?php print @$business->business_address; ?>, 
	<?php _e('Telephone:', 'quotes'); ?> <?php print @$business->business_phone; ?>, 
	<?php print @$business->business_mobile_telephone; ?><br/>,
	<?php _e('City:', 'quotes'); ?> <?php print @$business->business_city; ?>
	<div id="marcas" style="text-align:center;">
		<img src="<?php print $template_url ?>/images/marcas/zkteco.jpg" alt="" height="25" />
		<img src="<?php print $template_url ?>/images/marcas/hik-vision.png" alt="" height="25" />
		<img src="<?php print $template_url ?>/images/marcas/dalhua.jpg" alt="" height="25" />
		<img src="<?php print $template_url ?>/images/marcas/ubiquiti.jpg" alt="" height="25" />
		<img src="<?php print $template_url ?>/images/marcas/mikrotik.png" alt="" height="25" />
	</div>
</div>
