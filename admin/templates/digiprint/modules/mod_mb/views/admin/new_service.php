<?php
?>

<div class="wrap">
	<h2 id="page-title">
		<?php if( !isset($the_product) ): _e('Nuevo Servicio', 'digiprint'); ?>
		<?php else: _e('Editar Servicio', 'digiprint'); ?>
		<?php endif; ?>
		<span class="pull-right">
			<a class="btn btn-gray btn-gray-cancel" href="<?php print SB_Route::_('index.php?mod=mb'); ?>"><?php print SB_Text::_('Cancel and Back', 'mb')?></a>
			<a href="javascript:;" class="btn btn-orange" onclick="document.getElementById('product-form').submit();">
				<span class="icon-save-white"></span><?php print SB_Text::_('Save and Add', 'mb'); ?>
			</a>
			<a href="javascript:;" class="btn btn-success" onclick="document.getElementById('product-form').submit();">
				<span class="icon-save-white"></span><?php print SB_Text::_('Save', 'mb'); ?>
			</a>
		</span>
	</h2>
		<form id="product-form" action="" method="post">
			<input type="hidden" name="mod" value="mb" />
			<input type="hidden" name="task" value="save" />
			<?php if( isset($base_type) ): ?>
			<input type="hidden" name="base_type" value="<?php print $base_type; ?>" />
			<?php endif; ?>
			<?php if( isset($the_product) ): ?>
			<input type="hidden" id="product_id" name="id" value="<?php print $the_product->product_id; ?>" />
			<?php endif; ?>
			<div class="row">
				<div class="col-md-3">
					<div class="form-group">
						<label><?php print SB_Text::_('Code', 'mb'); ?></label>
						<input type="text" id="product_code" name="product_code" value="<?php print isset($the_product) ? $the_product->product_code : ''; ?>"
								class="form-control" />
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label><?php print SB_Text::_('Product Name', 'digiprint'); ?></label>
						<input type="text" id="product_name" name="product_name" value="<?php print isset($the_product) ? $the_product->product_name : ''; ?>"
								class="form-control" />
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label class="control-label"><?php print SB_Text::_('Category:', 'mb'); ?></label>
						<select id="category_id" name="category_id" class="form-control">
							<option value="-1">-- <?php print SB_Text::_('category', 'mb'); ?> --</option>
							<?php if( isset($categories) ):foreach($categories as $cat): ?>
							<option value="<?php print $cat->category_id; ?>"
								<?php print (isset($the_product) && in_array($cat->category_id, $the_product->categories_ids)) ? 'selected' : '';?>><?php print $cat->name; ?></option>
							<?php endforeach; endif;?>
						</select>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-9">
					<div class="form-group">
						<label><?php print SB_Text::_('Description', 'mb') ?></label>
						<textarea id="description" name="description" class="form-control"><?php print isset($the_product) ? $the_product->product_description : ''; ?></textarea>
					</div>
				</div>
				<div class="col-md-3">
					<?php
					$cfg = isset($the_product) ? (array)json_decode($the_product->settings) : array(); 
					?>
					<fieldset>
						<legend><?php _e('Configuracion', 'digiprint'); ?></legend>
						<div class="row">
							<div class="col-xs-12 col-md-6">
								<div class="">
									<label><input type="checkbox" name="meta[settings][]" value="color" <?php print in_array('color', $cfg) ? 'checked' : ''; ?> /><?php _e('Color', 'digiprint'); ?></label>
								</div>
								<div class="">
									<label><input type="checkbox" name="meta[settings][]" value="talla" <?php print in_array('talla', $cfg) ? 'checked' : ''; ?> /><?php _e('Talla', 'digiprint'); ?></label>
								</div>
								<div class="">
									<label><input type="checkbox" name="meta[settings][]" value="genero" <?php print in_array('genero', $cfg) ? 'checked' : ''; ?> /><?php _e('Genero', 'digiprint'); ?></label>
								</div>
								<div class="">
									<label><input type="checkbox" name="meta[settings][]" value="estilos" <?php print in_array('estilos', $cfg) ? 'checked' : ''; ?> /><?php _e('Estilos', 'digiprint'); ?></label>
								</div>
							</div>
							<div class="col-xs-12 col-md-6">
								<div class="">
									<label><input type="checkbox" name="meta[settings][]" value="dimension" <?php print in_array('dimension', $cfg) ? 'checked' : ''; ?> /><?php _e('Dimension', 'digiprint'); ?></label>
								</div>
								<div class="">
									<label><input type="checkbox" name="meta[settings][]" value="diseno" <?php print in_array('diseno', $cfg) ? 'checked' : ''; ?> /><?php _e('Dise&ntilde;o', 'digiprint'); ?></label>
								</div>
								<div class="">
									<label><input type="checkbox" name="meta[settings][]" value="ojillos" <?php print in_array('ojillos', $cfg) ? 'checked' : ''; ?> /><?php _e('Ojillos', 'digiprint'); ?></label>
								</div>
								<div class="">
									<label><input type="checkbox" name="meta[settings][]" value="bastilla" <?php print in_array('bastilla', $cfg) ? 'checked' : ''; ?> /><?php _e('Bastilla', 'digiprint'); ?></label>
								</div>
							</div>
						</div>
					</fieldset>
				</div>
			</div>
			<h2 class="text-center"><?php _e('Configuraci&oacute;n de Precios', 'digiprint'); ?></h2>
			<div class="row">
				<?php for($i = 1; $i < 6; $i++): ?>
				<?php
				$data = isset($the_product) ? json_decode($the_product->{'_price_range_'.$i}) : new stdClass();
				?>
				<div class="rango-precio">
					<fieldset>
						<legend><?php printf(__('Rango de Precio %d', 'digiprint'), $i); ?></legend>
						<table class="table table-condensed">
						<tr>
							<td>
								<input type="text" name="meta[_price_range_<?php print $i; ?>][from]" 
									value="<?php print @$data->from; ?>" class="form-control" style="width:42px;"  /></td>
							<td>A</td>
							<td><input type="text" name="meta[_price_range_<?php print $i; ?>][to]" value="<?php print @$data->to; ?>" class="form-control" style="width:42px;" /></td>
							<td>=</td>
							<td><input type="text" name="meta[_price_range_<?php print $i; ?>][equal]" value="<?php print @$data->equal; ?>" class="form-control" style="width:42px;" /></td>
						</tr>
						</table>
					</fieldset>
				</div>
				<?php endfor; ?>
			</div>
			<div class="row">
				<div class="col-xs-12 col-md-6">
					<div id="color-container">
						<?php
						$colors = array((object)array('code' => '', 'price' => 0, 'stock_code' => '')); 
						if( isset($the_product) )
						{
							$data = json_decode($the_product->color);
							
							if( is_array($data))
							{
								$colors = $data;
							}
						}
						?>
						<?php foreach($colors as $index => $item): ?>
						<div class="row" data-row="<?php print $index; ?>">
							<div class="col-md-4">
								<label class="control-label">Color</label>
								<select id="color_0" name="meta[color][<?php print	$index; ?>][code]" class="form-control selectpicker">
									<?php foreach(dp_get_colores() as $color => $code): ?>
									<?php $status_color = "<span class='status-color' style='background-color:".$code."'>&nbsp;</span>$color"; ?>
									<option value="<?php print $code; ?>" data-content="<?php print $status_color; ?>"
										<?php print $item->code == $code ? 'selected' : ''; ?>>
										<?php print $color; ?>
									</option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="col-md-4">
								<label>Precio Adicional</label>
								<input type="text" name="meta[color][<?php print $index; ?>][price]" value="<?php print $item->price; ?>" class="form-control" />
							</div>
							<div class="col-md-4">
								<label>Codigo Stock</label>
								<input type="text" name="meta[color][<?php print $index; ?>][stock_code]" value="<?php print $item->stock_code; ?>" class="form-control" />
							</div>
						</div>
						<?php endforeach; ?>
					</div><!-- end id="color-container" -->
					<div class="nueva-configuracion">
						<a href="javascript:;" class="btn-nueva-configuration" data-tipo="color"><span class="glyphicon glyphicon-plus"></span></a>
					</div>
				</div>
				<div class="col-xs-12 col-md-6">
					<div id="tallas-container">
						<?php
						$tallas = array((object)array('talla' => '', 'price' => 0, 'stock_code' => '')); 
						if( isset($the_product) )
						{
							$data = json_decode($the_product->talla);
							
							if( is_array($data))
							{
								$tallas = $data;
							}
						}
						?>
						<?php foreach($tallas as $i => $item): ?>
						<div class="row" data-row="<?php print $i; ?>">
							<div class="col-md-4">
								<label class="control-label">Talla Disponible</label>
								<select id="talla_0" name="meta[talla][<?php print $i; ?>][talla]" class="form-control">
									<?php foreach(dp_tallas() as $talla): ?>
									<option value="<?php print $talla; ?>" <?php print $item->talla == $talla ? 'selected' : ''?>>
										<?php print $talla; ?>
									</option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="col-md-4">
								<label>Precio Adicional</label>
								<input type="text" name="meta[talla][<?php print $i; ?>][price]" value="<?php print $item->price; ?>" class="form-control" />
							</div>
							<div class="col-md-4">
								<label>Codigo Stock</label>
								<input type="text" name="meta[talla][<?php print $i; ?>][stock_code]" value="<?php print $item->stock_code; ?>" class="form-control" />
							</div>
						</div>
						<?php endforeach; ?>
					</div>
					<div class="nueva-configuracion">
						<a href="javascript:;" class="btn-nueva-configuration" data-tipo="talla"><span class="glyphicon glyphicon-plus"></span></a>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12 col-md-6">
					<div id="genero-container">
						<?php
						$generos = array((object)array('genero' => '', 'price' => 0, 'stock_code' => '')); 
						if( isset($the_product) )
						{
							$data = json_decode($the_product->genero);
							
							if( is_array($data))
							{
								$generos = $data;
							}
						}
						?>
						<?php foreach($generos as $i => $item): ?>
						<div class="row" data-row="<?php print $i; ?>">
							<div class="col-md-4">
								<label class="control-label">Genero Disponible</label>
								<select id="genero_0" name="meta[genero][<?php print $i; ?>][genero]" class="form-control">
									<option value="hombre" <?php print $item->genero == 'hombre' ? 'selected' : ''; ?>>Hombre</option>
									<option value="mujer" <?php print $item->genero == 'mujer' ? 'selected' : ''; ?>>Mujer</option>
								</select>
							</div>
							<div class="col-md-4">
								<label>Precio Adicional</label>
								<input type="text" name="meta[genero][<?php print $i; ?>][price]" value="<?php print $item->price; ?>" class="form-control" />
							</div>
							<div class="col-md-4">
								<label>Codigo Stock</label>
								<input type="text" name="meta[genero][<?php print $i; ?>][stock_code]" value="<?php print $item->stock_code; ?>" class="form-control" />
							</div>
						</div>
						<?php endforeach; ?>
					</div>
					<div class="nueva-configuracion">
						<a href="javascript:;" class="btn-nueva-configuration" data-tipo="genero"><span class="glyphicon glyphicon-plus"></span></a>
					</div>
				</div>
				<div class="col-xs-12 col-md-6">
					<div id="estilos-container">
						<?php
						$estilos = array((object)array('estilo' => '', 'price' => 0, 'stock_code' => '')); 
						if( isset($the_product) )
						{
							$data = json_decode($the_product->estilo);
							
							if( is_array($data))
							{
								$estilos = $data;
							}
						}
						?>
						<?php foreach($estilos as $i => $item): ?>
						<div class="row" data-row="<?php print $i; ?>">
							<div class="col-md-4">
								<label class="control-label">Estilo Disponible</label>
								<select id="estilo_0" name="meta[estilo][<?php print $i; ?>][estilo]" class="form-control">
									<?php foreach(dp_estilos() as $estilo => $label): ?>
									<option value="<?php print $estilo; ?>" <?php print $item->estilo == $estilo ? 'selected' : ''; ?>>
										<?php print $label; ?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="col-md-4">
								<label>Precio Adicional</label>
								<input type="text" name="meta[estilo][<?php print $i; ?>][price]" value="<?php print $item->price; ?>" class="form-control" />
							</div>
							<div class="col-md-4">
								<label>Codigo Stock</label>
								<input type="text" name="meta[estilo][<?php print $i; ?>][stock_code]" value="<?php print $item->stock_code; ?>" class="form-control" />
							</div>
						</div>
						<?php endforeach; ?>
					</div>
					<div class="nueva-configuracion">
						<a href="javascript:;" class="btn-nueva-configuration" data-tipo="estilo"><span class="glyphicon glyphicon-plus"></span></a>
					</div>
				</div>
			</div>
		</form>
	<script>
	function validate_form_product()
	{
		if( jQuery('#product_name').val().length <= 0 )
		{
			alert('Ingrese un nombre para el producto');
			return false;
		}
		/*
		if( jQuery('#product_measure_unit').val().length <= 0 )
		{
			alert('Ingrese la unidad de medida para el producto');
			return false;
		}
		*/
		if( jQuery('#store_id').val().length <= 0 || jQuery('#store_id').val() == -1 )
		{
			alert('Ingrese la unidad de medida para el producto');
			return false;
		}
		return true;
	}
	jQuery(function()
	{
		jQuery('#store_id').change(function()
		{
			if( this.value <= 0 )
				return false;
			var cats = jQuery('#category_id');
			var types = jQuery('#type_id');
			cats.html('');
			types.html('');
			jQuery.get('index.php?mod=mb&task=ajax&action=get_store_cats&store_id='+this.value, function(res)
			{
				cats.append('<option value="-1">-- category --</option>');
				if( res.status == 'ok' )
				{
					jQuery.each(res.categories, function(i, cat)
					{
						var op = jQuery('<option value="'+cat.category_id+'">'+cat.name+'</option>');
						cats.append(op);
					});

				}
			});
			jQuery.get('index.php?mod=mb&task=ajax&action=get_store_types&store_id='+this.value, function(res)
			{
				types.append('<option value="-1">-- type --</option>');
				if( res.status == 'ok' )
				{
					jQuery.each(res.types, function(i, type)
					{
						var op = jQuery('<option value="'+type.type_id+'">'+type.type+'</option>');
						types.append(op);
					});

				}
			});
		});
		jQuery('#product-form').submit(validate_form_product);
		jQuery('.btn-nueva-configuration').click(function()
		{
			var type = this.dataset.tipo;
			var container 	= null;
			var rows		= null;
			var new_row		= null;
			var new_index	= null;
			var meta_name	= '';
			if( type == 'color' )
			{
				container = jQuery('#color-container');
				meta_name	= 'color';
			}
			else if( type == 'genero' )
			{
				container = jQuery('#genero-container');
				meta_name	= 'genero';
			}
			else if( type == 'talla' )
			{
				container = jQuery('#tallas-container');
				meta_name	= 'talla';
			}
			else if( type == 'estilo' )
			{
				container = jQuery('#estilos-container');
				meta_name	= 'estilo';
			}
			rows		= container.find('.row');
			new_index	= rows.length;
			new_row		= jQuery(rows[0]).clone();
			new_row.get(0).dataset.row = new_index;
			if( type == 'color' )
			{
				var select = new_row.find('select').clone();
				new_row.find('.bootstrap-select').remove();
				new_row.find('.col-md-4:first').append(select);
				jQuery(select).selectpicker({
				  style: 'btn-default',
				  size: 12
				});
			}
			new_row.find('input,select').each(function(i, obj)
			{
				obj.name = obj.name.replace('meta['+meta_name+'][0]', 'meta['+meta_name+']['+new_index+']');
				obj.value = '';
			});
			container.append(new_row);
			return false;
		});
	});
	</script>
</div>
