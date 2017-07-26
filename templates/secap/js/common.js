jQuery(function()
{
	var btn_mobile 	= jQuery('#btn-mobile-menu');
	var nav			= jQuery('#navigation');
	btn_mobile.click(function()
	{
		if( !jQuery(this).hasClass('toggle') )
		{
			nav.css('display', 'block');
			jQuery(this).addClass('toggle');
		}
		else
		{
			nav.css('display', 'none');
			jQuery(this).removeClass('toggle');
		}
	});
	
	jQuery('#navigation ul li').hover(function(e)
	{
		var ul = jQuery(this).find('ul:first');
		if( btn_mobile.css('display') == 'inline' )
		{
			ul.css('position', 'relative');
		}
		else
		{
			ul.css('position', 'absolute');
		}
		ul.css('display', 'block');
	}, 
	function(e)
	{
		var ul = jQuery(this).find('ul:first');
		ul.css('display', 'none');
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
		    icon: lt.template_url + "/images/marker.png"
		});
		var infowindow = new google.maps.InfoWindow({
		    content: typeof map_location != 'undefined' ? map_location.address : 'No hay informacion disponible'
		});
		infowindow.open(map, marker);
	}
	jQuery('#form-contact').submit(function(e)
	{
		var form	= this;
		if( this.firstname.value.length <= 0 )
		{
			alert('Debes ingresar tu nombre');
			return false;
		}
		if( this.lastname.value.length <= 0 )
		{
			alert('Debes ingresar tu apellido');
			return false;
		}
		if( this.email.value.length <= 0 )
		{
			alert('Debes ingresar tu email');
			return false;
		}
		var params = jQuery(this).serialize();
		jQuery('#btn-send-contact').prop('disabled', true);
		jQuery('#btn-send-contact').html('Enviando...');
		/*
		jQuery.post('index.php', params, function(res)
		{
			jQuery('#btn-send-contact').html('Enviar')
			jQuery('#btn-send-contact').prop('disabled', false);
			this.firstname.value = '';
			this.lastname.value = '';
			this.email.value = '';
			this.message.value = '';
			alert(res.mensaje);
		});
		return false;
		*/
		return true;
	});
});