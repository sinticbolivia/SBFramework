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
	if( jQuery('#store_id').val().length <= 0 || jQuery('#store_id').val() == -1 )
	{
		alert('Ingrese la unidad de medida para el producto');
		return false;
	}
	*/
	return true;
}
function reloadImages(product_id)
{
	jQuery('#product-gallery').html('Loading images...');
	jQuery.get('index.php?mod=mb&task=get_images&id='+product_id, function(res)
	{
		if( res.status == 'ok' )
		{
			jQuery('#product-gallery').html(res.html);
		}
	});
}
function GetWarehouses(store_id, product_id)
{
	var container 	= document.getElementById('warehouses-container');
	container.innerHTML = '';
	var endpoint	= 'index.php?mod=mb&task=ajax&action=get_warehouses_html&store_id=' + store_id;
	if( product_id )
	{
		endpoint += '&product_id=' + product_id;
	}
	jQuery.get(endpoint, function(res)
	{
		if( res && res.status == 'ok')
		{
			container.innerHTML = res.html;
			/*
			res.items.forEach(function(w)
			{
				var col 		= document.createElement('div');
				var panel		= document.createElement('div');
				var panelHeading	= document.createElement('div');
				var panelBody	= document.createElement('div');
				col.className 	= 'col-xs-12 col-sm-4 col-md-3 col-lg-3';
				panel.className	= 'panel panel-default';
				panelHeading	= 'panel-heading';
				panelBody		= 'panel-body';
				panel.appendChild(panelHeading);
				panel.appendChild(panelBody);
				col.appendChild(panel);
			});
			*/
		}
		console.log(res);
	});
}
jQuery(function()
{
	jQuery('#store_id').change(function()
	{
		var cats = jQuery('#category_id');
		var types = jQuery('#type_id');
		
		cats.html('');
		types.html('');
		
		if( this.value <= 0 )
			return false;
		
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
			if( res && res.length )
			{
				jQuery.each(res, function(i, type)
				{
					var op = jQuery('<option value="'+type.type_id+'">'+type.type+'</option>');
					types.append(op);
				});
				
			}
		});
		GetWarehouses(this.value);

	});
	jQuery('#product-form').submit(validate_form_product);
	jQuery('#product-tabs a').click(function (e) 
	{
		e.preventDefault();
		jQuery(this).tab('show');
		if( jQuery(this).attr('href') == '#kardex' )
		{
			jQuery('#kardex-table').datagrid();
		}
	});
	jQuery(document).on('click', '.btn-delete-img', function(e)
	{
		var $this = this;
		jQuery.get('index.php?mod=mb&task=remove_image&id='+this.dataset.id, function(res)
		{
			reloadImages($this.dataset.product_id);
		});
		return false;
	});
	jQuery(document).on('click', '.btn-set-featured-img', function(e)
	{
		var a = jQuery(this).parents('.product-image');
		jQuery.get('index.php?mod=mb&task=set_featured_image&id='+this.dataset.product_id + '&img_id='+this.dataset.id,
					function(res)
		{
			jQuery('.product-image .btn-set-featured-img').css('display', 'inline');
			jQuery('.product-image .featured').remove();
			a.find('.btn-set-featured-img').css('display', 'none');
			a.append('<span class="featured">Featured Image</span>');
		});
		return false;
	});
});