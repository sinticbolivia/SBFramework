jQuery(function($) 
{
	var animating 		= false,
		submitPhase1 	= 1100,
		submitPhase2 	= 400,
		logoutPhase1 	= 800,
		$login 			= $(".login"),
		$app 			= $(".app");
  
	function ripple(elem, e) 
	{
	    jQuery(".ripple").remove();
	    var elTop = elem.offset().top,
	        elLeft = elem.offset().left,
	        x = e.pageX - elLeft,
	        y = e.pageY - elTop;
	    var $ripple = $("<div class='ripple'></div>");
	    $ripple.css({top: y, left: x});
	    elem.append($ripple);
	};
  
	jQuery('#form-login').submit(function(e) 
	{
	    if (animating) 
	    	return;
	    animating = true;
	    var form = jQuery(this);
	    var that = form.find('button.login__submit:first');
	    ripple($(that), e);
	    $(that).addClass("processing");
	    var params = jQuery('#form-login').serialize() + '&ajax=1';
	    jQuery.post('login.php', params, function(res)
	    {
	    	if( res.status == 'ok' )
	    	{
	    		window.location = res.redirect;
	    	}
	    	else
	    	{
	    		animating = false;
	    		jQuery(".ripple").remove();
	    		jQuery(that).removeClass("processing");
	    		alert(res.error);
	    	}
	    });
	    return false;
	    /*
	    setTimeout(function() 
	    {
	    	$(that).addClass("success");
	    	setTimeout(function() {
	    		$app.show();
	    		$app.css("top");
	    		$app.addClass("active");
	    	}, submitPhase2 - 70);
	    	setTimeout(function() {
	    		$login.hide();
	    		$login.addClass("inactive");
	    		animating = false;
	    		$(that).removeClass("success processing");
	    	}, submitPhase2);
	    }, submitPhase1);
	    */
	    
	});
	/*
	jQuery(document).on("click", ".app__logout", function(e) 
	{
	    if (animating) return;
	    $(".ripple").remove();
	    animating = true;
	    var that = this;
	    $(that).addClass("clicked");
	    setTimeout(function() {
	      $app.removeClass("active");
	      $login.show();
	      $login.css("top");
	      $login.removeClass("inactive");
	    }, logoutPhase1 - 120);
	    setTimeout(function() {
	      $app.hide();
	      animating = false;
	      $(that).removeClass("clicked");
	    }, logoutPhase1);
	});
	*/
});