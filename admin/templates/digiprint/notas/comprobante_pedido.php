<?php
$store = new SB_MBStore($order->store_id);
//print_r($settings);
?>
<link rel="stylesheet" href="<?php print TPL_BE_DIGIPRINT_URL; ?>/css/notas.css" />
<div class="contenedor-nota">
	<div class="cabecera" style="background:#fff;">
		<table style="width:100%;">
		<tr>
			<td style="width:50%;">
				<img src="<?php print UPLOADS_URL . '/'. SITE_LOGO; ?>" width="200" />
			</td>
			<td style="width:50%;">
				<div style="text-align:right;">
					LLAMANOS: <?php print $store->phone ? $store->phone : $settings->business_phone; ?><BR/>
					o visitanos en <?php print BASEURL; ?>
				</div>
			</td>
		</tr>
		</table>
	</div>
	<h1 style="background:#c1272d;color:#fff;padding:5px;margin:0;font-size:20px;">NUEVA ORDEN</h1>
	<div class="nota">
		<br/>
		Estimado(a) <?php printf("%s %s", $order->customer->first_name, $order->customer->last_name); ?><br/>
		<p>
			Tu compra se realizo con exito. Gracias por prferir DigiPrint.<br/>
			A continuacion detallaremos toda la informacion de su nueva orden.<br/>
		</p>
		<h4>NO DE ORDEN: <?php print sb_fill_zeros($order->sequence); ?></h4>
		<table class="order-items">
		<thead>
		<tr>
			<th>CODIGO</th>
			<th>PRODUCTO</th>
			<th>CANTIDAD</th>
			<th>PRECIO</th>
			<th>IMPORTE</th>
		</tr>
		</thead>
		<tbody>
		<?php foreach($order->GetItems() as $item): ?>
		<tr>
			<td><?php print $item->product_code; ?></td>
			<td><?php print $item->product_name; ?></td>
			<td class="text-center"><?php print $item->quantity; ?></td>
			<td class="text-right"><?php print sb_number_format($item->price); ?></td>
			<td class="text-right"><?php print sb_number_format($item->total); ?></td> 
		</tr>
		<?php endforeach; ?>
		</tbody>
		<tfoot>
		<tr>
			<td colspan="3"></td>
			<td class="text-right totals">SUB TOTAL</td>
			<td class="text-right totals"><?php print sb_number_format($order->subtotal); ?></td>
		</tr>
		<tr>
			<td colspan="3"></td>
			<td class="text-right totals">DESCUENTO</td>
			<td class="text-right totals"><?php print sb_number_format($order->discount); ?></td>
		</tr>
		<tr>
			<td colspan="3">
				<table style="width:230px;">
				<tr>
					<td style="width:150px;">
						Haz Check-IN y podras ver el estado de tu orden en tiempo real
					</td>
					<td style="width:80px;">
						<a href="<?php print SB_Route::_('index.php?task=dp_checkin&id='.$order->order_id, 'frontend')?>" class="btn btn-red">CHECK-IN</a>
					</td>
				</tr>
				</table>
			</td>
			<td class="text-right totals">SUB TOTAL</td>
			<td class="text-right totals"><?php print sb_number_format($order->total_tax); ?></td>
		</tr>
		<tr>
			<td colspan="3"></td>
			<td class="text-right totals">GRAND TOTAL</td>
			<td class="text-right totals"><b class="text-red" style="font-size:16px;"><?php print sb_number_format($order->total); ?></b></td>
		</tr>
		</tfoot>
		</table>
		<?php include 'texto-pie-nota.php'; ?>
	</div><!-- end class="nota" -->
	<?php include 'pie.php';?>
</div>
<?php 
?>