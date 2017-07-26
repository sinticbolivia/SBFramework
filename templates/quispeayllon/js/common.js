jQuery(function()
{
	if( jQuery("#map").length > 0)
	{
		var myLatLng = map_location || {lat: -16.497918, lng: -68.134592};
		var map = new google.maps.Map(document.getElementById('map'), {
		    zoom: 18,
		    center: myLatLng
		});
		var marker = new google.maps.Marker({
		    position: myLatLng,
		    map: map,
		    title: '',
		    //icon: "templates/univida/images/marker-2.1.png"
		});
		var infowindow = new google.maps.InfoWindow({
		    content: map_location.address || 'No hay informacion disponible'
		});
		infowindow.open(map, marker);
	}
});