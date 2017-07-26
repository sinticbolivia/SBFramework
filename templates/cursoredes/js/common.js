var CR = 
{
	SetupFrontSections: function()
	{
		if( window.innerWidth > 375 )
		{
			jQuery('#front-slides').css('height', window.innerHeight + 'px');
			jQuery('#contact-content, #contact-content .map').css('height', window.innerHeight + 'px');
		}
		
	},
	SetupGoogleMaps: function()
	{
	}
};
jQuery(function()
{
	CR.SetupFrontSections();
});