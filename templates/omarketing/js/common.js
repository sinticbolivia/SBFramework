jQuery(function()
{
	jQuery('#navigation li').hover(function()
	{
		ul = jQuery(this).find('ul:first');
		window.ul = ul;
		if( ul.find('li').length > 7 )
		{
			ul.addClass('submenu-large');
		}
		
		jQuery(ul).css('display', 'block').css('height', 'auto');
	}, 
	function()
	{
		ul = jQuery(this).find('ul:first');
		ul.removeClass('submenu-large');
		ul.css('height', '0').css('display', 'none');
	});
	if( jQuery("#map1").length > 0)
	{
		var myLatLng = typeof map_location != 'undefined' ? map_location : {lat: -16.497918, lng: -68.134592};
		var map = new google.maps.Map(document.getElementById('map1'), {
		    zoom: 16,
		    center: myLatLng
		});
		var marker = new google.maps.Marker({
		    position: myLatLng,
		    map: map,
		    title: '',
		    //icon: lt.template_url + "/images/marker.png"
		});
		var infowindow = new google.maps.InfoWindow({
		    content: typeof map_location != 'undefined' ? map_location.address : 'No hay informacion disponible'
		});
		infowindow.open(map, marker);
	}
	jQuery('#form-contacto').submit(function()
	{
		var form = jQuery('#form-mensaje');
		form.find('[type=submit]').prop('disabled', true);
		form.removeClass();
		form.addClass('alert alert-info fade in');
		form.html('Enviando mensaje... <img src="'+lt.baseurl+'/images/spin.gif" />');
		var params = jQuery(this).serialize();
		jQuery.post('index.php', params, function(res)
		{
			form.removeClass();
			form.find('[type=submit]').prop('disabled', false);
			if( res.status == 'ok' )
			{
				jQuery('#form-contacto').css('display', 'none');
				form.addClass('alert alert-success fade in').html('<b>'+res.message+'</b>');
			}
			else if( res.status == 'error' )
			{
				form.addClass('alert alert-danger fade in').html('<b>'+res.error+'</b>');
			}
			else
			{
				form.addClass('alert alert-danger fade in').html('<b>Oops!!!, algo paso, porfavor vuelve a intentarlo mas tarde.</b>');
			}
		});
		return false;
	});
	jQuery('.form-newsletter').submit(function()
	{
		var params = jQuery(this).serialize();
		jQuery.post('index.php', params, function(res)
		{
			if( res.status == 'ok' )
			{
				alert(res.message);
			}
			else if( res.status == 'error' )
			{
				alert(res.error);
			}
			else 
			{
				alert('Ocurrio un error, porfavor intente mas tarde');
			}
		});
		return false;
	});
});