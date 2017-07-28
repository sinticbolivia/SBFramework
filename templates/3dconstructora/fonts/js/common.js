jQuery(function()
{
	jQuery('#navigation li').hover(function()
	{
		jQuery(this).find('a').addClass('hover');
	}, 
	function()
	{
		jQuery(this).find('a').removeClass('hover');
	});
	jQuery(window).resize(function()
	{
		jQuery('.front-section').css('height', window.innerHeight + 'px');
	});
	jQuery('.front-section').css('height', window.innerHeight + 'px');
	if( jQuery('.isotope-gallery').length > 0 )
	{
		jQuery('.isotope-gallery').isotope({
			// options
			itemSelector: '.gallery-img',
			layoutMode: 'fitRows'
		});
	}
});