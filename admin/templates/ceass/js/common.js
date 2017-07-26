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
function setMenuHeight()
{
	
	var bh = jQuery('body').height();
	var ch = jQuery('#container').height();
	var height = bh;
	if( ch > bh )
		height = ch;
	
	jQuery('#menu').css('height', height + 'px');
}
var window_height = window.innerHeight;//window.screen.availHeight;
var showing = false;
jQuery(function()
{
	jQuery(window).resize(function(){window_height = window.innerHeight;});
	//document.body.watch('height', function(property, oldHeight, newHeight){console.log('www')});
	//jQuery(document).bind('DOMSubtreeModified', function(){set_menu_height();})
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
		//console.log( window.scrollMaxY );
		//set_menu_height();
	}, 
	function()
	{
		//jQuery(this).find('ul:first').css('display', 'none');
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
	//##Setup mobile menu
	jQuery('#mobile-menu #menu-home').click(function()
	{
		jQuery('#menu').toggle();
	});
	if( jQuery('#mobile-menu').css('display') == 'block' )
	{
		jQuery('#menu nav ul li a').click(function()
		{
			if(jQuery(this).parent().find('ul').length > 0 )
			{
				return false;
			}
		});
	}
	jQuery('#mobile-menu ul li').hover(function()
	{
		jQuery(this).find('ul:first').css('display', 'block');
	},
	function()
	{
		jQuery(this).find('ul:first').css('display', 'none');
	});
});
jQuery(window).load(function()
{
	setMenuHeight();
});