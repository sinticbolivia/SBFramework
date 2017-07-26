var Tiquina = 
{
	SetupFrontSections: function()
	{
		if( window.innerWidth > 375 )
		{
			jQuery('.front-section').css('height', window.innerHeight + 'px');
		}
		
	},
	SetupMenu: function()
	{
		jQuery('#navigation ul.menu li').hover(function()
		{
			jQuery(this).find('.submenu:first').css({display:'block', opacity: 1});
		},
		function()
		{
			jQuery(this).find('.submenu:first').css({display:'none', opacity: 0});
		});
	}
};
jQuery(function()
{
	Tiquina.SetupMenu();
	jQuery('.gallery-image').hover(function()
	{
		jQuery(this).find('a img').addClass('current');
		jQuery(this).find('.description').css('opacity', 1);
	}, 
	function()
	{
		jQuery(this).find('a img').removeClass('current');
		jQuery(this).find('.description').css('opacity', 0);
	});
	jQuery('#btn-hamburguer').click(function()
	{
		jQuery('#mobile-navigation').css('width', '80%');
		return false;
	});
	jQuery('#btn-close-mobile-navigation').click(function()
	{
		jQuery('#mobile-navigation').css('width', '0');
		return false;
	});
	Tiquina.SetupFrontSections();
	jQuery(window).resize(Tiquina.SetupFrontSections);
});