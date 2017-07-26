<?php
$product_colors_cfg = $service->color;
//print_r($product_colors_cfg);
$dp_colors = dp_get_colores();
?>
<form action="" method="post" class="form-group-sm">
	<input type="hidden" name="task" value="save_order_item_variation" />
	<input type="hidden" name="product_id" value="<?php print $service->product_id; ?>"  />
	<input type="hidden" name="variation" value="color" />
	<div id="variations-container">
		<?php 
		$var_color = isset($variations['color']) && count($variations['color']) ? (array)$variations['color'] : array(array('quantity' => 1, 'color' => '', 'stock_code' => '', 'price' => 0));
		?>
		<?php foreach($var_color as $index => $__color): $color = (array)$__color;?>
                <div class="row" data-row="<?php print $index; ?>">
                    <input type="hidden" name="color[<?php print $index; ?>][stock_code]" value="<?php print $color['stock_code'] ?>" class="stock_code" />
                    <input type="hidden" name="color[<?php print $index; ?>][price]" value="<?php print $color['price'] ?>" class="price" />
                    <input type="hidden" name="color[<?php print $index; ?>][color_label]" value="<?php print @$color['color_label']; ?>" class="color_label" />
                    <div class="col-xs-2 col-md-2">
                        <div class="form-group">
                            <label>Cantidad</label>
                            <input type="number" min="1" name="color[<?php print $index ?>][quantity]" value="<?php print $color['quantity']; ?>" class="form-control" />
                        </div>
                    </div>
                    <div class="col-xs-4 col-md-4">
                        <div class="form-group">
                            <label>Color</label>
                            <select name="color[<?php print $index ?>][color]" class="form-control selectpicker attribute-color" data-size="8">
                                <?php foreach($service->$setting as $_color): $label = array_search($_color->code, $dp_colors); ?>
                                <option value="<?php print $_color->code; ?>" 
                                    <?php print $color['color'] == $_color->code ? 'selected' : ''; ?>
                                    data-content="<span class='status-color' style='background-color:<?php print $_color->code; ?>;'> </span><?php print $label ?>"
                                    data-price="<?php print $_color->price; ?>"
                                    data-stock_code="<?php print $_color->stock_code; ?>"
                                    data-color_label="<?php print $label; ?>">
                                    <?php print $label; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-4 col-md-4">
                        <div class="form-group">
                            <label>Nombre del Archivo</label>
                            <input type="text" name="" value="" class="form-control" />
                        </div>
                    </div>
                    <div class="col-xs-2 col-md-2">
                        <div class="form-group">
                            <label>&nbsp;</label><br/>
                            <a href="javascript:;" class="btn btn-default btn-sm btn-remove-variation"><span class="glyphicon glyphicon-trash"></span></a>
                        </div>
                    </div>
                </div>
		<?php endforeach; ?>
	</div>
	<div class="add-variation"><hr/><a href="javascript:;" class="btn-add-variation"><span class="glyphicon glyphicon-plus-sign"></span></a></div>
	<div class="form-group">
		<a href="javascript:;" onclick="parent.jQuery('#modal-configure').modal('hide');" class="btn btn-gray"><?php _e('Cancelar', 'digiprint'); ?></a>
		<button type="submit" class="btn btn-gray"><?php _e('Guardar Configuracion', 'digiprint'); ?></button>
	</div>
</form>
<script>
jQuery(function()
{
    jQuery('.btn-add-variation').click(function()
    {
        var container = jQuery('#variations-container');
        var total_rows  = container.find('.row').length;
        var new_index  = total_rows;
        var new_row     = container.find('.row').first().clone();
        var select      = new_row.find('select').clone();
        new_row.find('.bootstrap-select').remove();
        new_row.find('.col-md-4:first .form-group').append(select);
        jQuery(select).selectpicker({
          style: 'btn-default',
          size: 8
        });
        new_row.find('input,select').each(function(i, obj)
        {
            obj.name = obj.name.replace('color[0]', 'color['+new_index+']');
            obj.value = '';
        });
        container.append(new_row);
    });
    jQuery(document).on('hidden.bs.select', '.attribute-color', function(e)
    {
        var row     = jQuery(this).parents('.row');
        var option  = jQuery(this).find('option:selected').get(0);
        row.find('.stock_code').val(option.dataset.stock_code);
        row.find('.price').val(option.dataset.price);
        row.find('.color_label').val(option.dataset.color_label);
    });
    jQuery(document).on('click', '.btn-remove-variation', function(e)
    {
        jQuery(this).parents('.row:first').remove();
        var container = jQuery('#variations-container');
        container.find('.row').each(function(ir, row)
        {
            row.dataset.row = ir;
            jQuery(row).find('input,select').each(function(i, obj)
            {
                obj.name = obj.name.replace(/(\w+)\[(\d+)\]/, '$1['+ir+']');
            });
        });
        
    });
});
</script>