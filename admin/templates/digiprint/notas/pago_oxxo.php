<?php
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
	<h1 style="background:#c1272d;color:#fff;padding:5px;margin:0;font-size:20px;">NOTIFICACION DE PAGO OXXO</h1>
	<div class="nota">
		<br/>
		Estimado(a) <?php printf("%s %s", $order->customer->first_name, $order->customer->last_name); ?><br/>
		<p>
			Tu compra se realizo con exito. Gracias por prferir DigiPrint.<br/>
			A continuacion detallaremos toda la informacion de su nueva orden.<br/>
		</p>
		<h4>NO DE ORDEN: <?php print sb_fill_zeros($order->sequence); ?></h4>
		<p>
			Te informamos que su pago debera ser realizado en las proximas 24Hrs por medio de OXXO.<br/>
			Es importante que imprima usted este correo y lo muestre en el Oxxo de su preferencia para que pueda realizar el pago.
		</p>
		<h2>MONTO: <?php print sb_number_format($order->total); ?></h2>
		<p>
			Numero de Referencia: xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx<br/>
			Fecha de vencimiento: 
		</p>
		<?php include 'text-pie-nota.php'; ?>
	</div>
	<?php include 'pie.php'; ?>
</div>