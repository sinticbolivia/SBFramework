/**
 * 
 */
 function getPosition(element) 
 {
    var xPosition = 0;
    var yPosition = 0;
  
    while( element ) 
    {
        xPosition += (element.offsetLeft - element.scrollLeft + element.clientLeft);
        yPosition += (element.offsetTop - element.scrollTop + element.clientTop);
        element = element.offsetParent;
    }
    return { x: xPosition, y: yPosition };
}

var window_height = window.innerHeight;//window.screen.availHeight;
var showing = false;

jQuery(function()
{
	jQuery(window).resize(function(){window_height = window.innerHeight;});
	jQuery('nav > ul > li').hover(function()
	{
		
		if( jQuery(this).hasClass('showing') )
		{
			return true;
		}
		else
		{
			jQuery('nav ul li ul').css('display', 'none');
			jQuery('nav ul li').removeClass('showing');
		}
		if( jQuery(this).find('ul:first').length <= 0 )
			return true;	
		jQuery(this).addClass('showing')
		
		var ul = jQuery(this).find('ul:first');
		window.current_menu = ul.get(0);
		ul.css('display', 'block');
		var position 	= getPosition(ul.get(0));
		var y 			= parseFloat(position.y);
		var real_y 		= y + ul.height();
		
		if( real_y > window_height )
		{
			var diff = real_y - window_height;
			ul.css('top', '-' + (diff + 50 ) + 'px');
		}
		
		ul.bind('mouseleave', function()
		{
			showing = false;
			jQuery(this).css('display', 'none');
			jQuery('nav ul li').removeClass('showing');
			jQuery(this).unbind('mouseleave');
		});
		showing = true;
	}, 
	function()
	{
	});
	jQuery('.datepicker').datepicker({
		format: lt.dateformat,
		weekStart: 0,
		//todayBtn: true,
		autoclose: true,
	    todayHighlight: true,
	    language: "es"
	    //clearBtn: true,
	    //startDate: 2015//'3d'
	});
});
