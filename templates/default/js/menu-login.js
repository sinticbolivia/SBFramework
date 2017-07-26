/**
 * 
 */
jQuery(function()
{
	jQuery('.sb-menu-item a.remote-login').click(function()
	{
		var form = jQuery('<form target="_blank" method="post"/>').attr('action', jQuery(this).attr('href'));
		var username = jQuery('<input type="hidden" name="login" value="'+jQuery(this).data().username+'" />');
		var password = jQuery('<input type="hidden" name="pword" value="'+jQuery(this).data().password+'" />');
		var action = jQuery('<input type="hidden" name="action" value="do_login" />');
		form.append(username);
		form.append(password);
		form.append(action);
		jQuery('body').append(form);
		form.submit();
		return false;
	});
});