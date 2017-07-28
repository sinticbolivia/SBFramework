jQuery(function()
{
	jQuery('.dropdown-categories a').click(function(a)
	{
		
		var submenu = jQuery(this).parents('li:first').find('ul.submenu');
		if( submenu && submenu.length > 0 )
		{
			if( submenu.hasClass('active') )
			{
				submenu.slideUp();
				submenu.removeClass('active');
			}
			else
			{
				jQuery('.dropdown-categories .submenu.active').slideUp().removeClass('active');
				submenu.slideDown().addClass('active');
			}
			
			//jQuery('.dropdown-categories .submenu').toggleClass('active');
			/*
			jQuery('.dropdown-categories .submenu.active').removeClass('active');
			submenu.addClass('active');
			*/
			return false;
		}
		return true;
	});
});