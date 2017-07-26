/**
 * 
 */
jQuery(function()
{
	jQuery('nav ul li').hover(function()
	{
		jQuery(this).find('ul:first').css('display', 'block');
	}, 
	function()
	{
		jQuery(this).find('ul:first').css('display', 'none');
	});
	jQuery('.confirm').click(function()
	{
		if( confirm(jQuery(this).data('message'), 'Confirmar accion') )
		{
			return true;
		}
		return false;
	});
	jQuery('.datepicker').datepicker({
		format: 'dd-mm-yyyy',
		weekStart: 0,
		//todayBtn: true,
		autoclose: true,
	    todayHighlight: true,
	    language: "es"
	    //clearBtn: true,
	    //startDate: 2015//'3d'
	});
	//##initialize popover
	jQuery('.has-popover').popover({trigger: 'hover',placement:'auto', html: true, container:'body'});
});
