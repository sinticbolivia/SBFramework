function _3dc_vertical_center()
{
	jQuery('.vertical-center').each(function(i, el)
	{
		var ph	= jQuery(el).parent().height();
		var h 	= jQuery(el).height();
		var padding_top = ((ph / 2) - (h / 2));
		console.log('parent-height:' + ph);
		console.log('text-height:' + h);
		console.log(padding_top);
		jQuery(el).css('padding-top', padding_top + 'px');
	});
}
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
	jQuery('#btn-menu-mobile').click(function()
	{
		if( this.dataset.show == 'yes' )
		{
			jQuery('#navigation ul:first').css('display', 'none');
			this.dataset.show = 'no';
		}
		else
		{
			jQuery('#navigation ul:first').css('display', 'block');
			this.dataset.show = 'yes';
		}
	});
	jQuery(window).resize(function()
	{
		jQuery('.front-section').css('min-height', window.innerHeight + 'px');
	});
	jQuery('.front-section').css('min-height', window.innerHeight + 'px');
	/*
	if( jQuery('.isotope-gallery').length > 0 )
	{
		jQuery('.gallery-img img').each(function(i, img)
		{
			var h = img.clientHeight;
			var w = img.clientWidth;
			jQuery(img).css({width:(w/2)+'px',height:(h/2)+'px'});
		});
		jQuery('.isotope-gallery').isotope({
			// options
			itemSelector: '.gallery-img',
			percentPosition: true,
			layoutMode: 'masonry'
		});
	}
	*/
	_3dc_vertical_center();
});

jQuery(window).load(function()
{
	
});