/**
 * 
 */
jQuery(function()
{
	jQuery('.datepicker').datepicker({
		format: window.lt_date_format || 'dd-mm-yyyy',
		weekStart: 0,
		//todayBtn: true,
		autoclose: true,
	    todayHighlight: true,
	    language: "es"
	    //clearBtn: true,
	    //startDate: 2015//'3d'
	});
	//##find for video iframe
	jQuery('iframe[src*=youtube], iframe[src*=vimeo]').each(function(i, iframe)
	{
		jQuery(iframe).wrap('<div class="video-container"></div>');
	});
});