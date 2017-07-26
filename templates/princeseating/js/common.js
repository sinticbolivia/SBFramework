jQuery(function()
{
	jQuery('.products-menu-item > .submenu').addClass('products-menu');
	jQuery('#navigation-menu > li').hover(function()
	{
		jQuery(this).find('.submenu:first').css('display', 'block');
	}, 
	function()
	{
		jQuery(this).find('.submenu:first').css('display', 'none');
	});
});