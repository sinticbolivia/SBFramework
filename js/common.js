/**
 * 
 */
//================================== //
//replace native javascript function //
//================================== //
var alert_showing = false;
window.alert = function(text, on_close_callback, title)
{
	if( alert_showing )
		return false;
	alert_showing = true;
	
	var label_alert = 'Alert';
	if(window.lt && lt.locate && lt.locale.label_alert)
	{
		label_alert = lt.locale.label_alert;
	}
	if( title )
	{
		label_alert = title;
	}
	var btn_close = window.lt && lt.locale && lt.locale.btn_label_close || 'Close';
	var tpl = '<div class="modal fade" id="js-dlg-alert" tabindex="-1" role="dialog">\
				<div class="modal-dialog" role="document">\
		    		<div class="modal-content">\
		      			<div class="modal-header">\
		        			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>\
		        			<h4 class="modal-title" id="js-dlg-alert-title">'+label_alert+'</h4>\
		      			</div>\
		      			<div class="modal-body">'+text+'</div>\
		      			<div class="modal-footer">\
		        			<button type="button" id="btn-close-dlg-alert" class="btn btn-primary" data-dismiss="modal">'+btn_close+'</button>\
		      			</div>\
		    		</div>\
				</div>\
			</div>';
	if( jQuery('#js-dlg-alert').length > 0 )
	{
		jQuery('#js-dlg-alert').remove();
	}
	jQuery('body').append(tpl);
	jQuery(document).on('click', '#btn-close-dlg-alert', function()
	{
		alert_showing = false;
		if( on_close_callback )
		{
			on_close_callback();
		}
	});
	jQuery('#js-dlg-alert').modal('show');
	
	return false;
};
window.confirm = function(text, title, callback, e)
{
	var event = e || window.event || arguments.callee.caller.arguments[0];
	//console.log(arguments.callee.caller.arguments);
	var btn_accept = window.lt && lt.locale && lt.locale.btn_label_accept ? lt.locale.btn_label_accept : 'Accept';
	var btn_close = window.lt && lt.locale && lt.locale.btn_label_close ? lt.locale.btn_label_close : 'Close';
	var el  = event.currentTarget || event.target;
	
	var href = (el.href) ? "document.location = '" + el.href + "';" : '#';
	if( callback && typeof callback == 'function' )
	{
		window.confirm_callback = callback;
		href = 'window.confirm_callback();' ;
	}
	var tpl = '<div class="modal fade" id="js-dlg-confirm" tabindex="-1" role="dialog">\
				<div class="modal-dialog" role="document">\
		    		<div class="modal-content">\
		      			<div class="modal-header">\
		        			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>\
		        			<h4 class="modal-title" id="myModalLabel">'+title+'</h4>\
		      			</div>\
		      			<div class="modal-body">'+text+'</div>\
		      			<div class="modal-footer">\
		        			<button type="button" class="btn btn-default" data-dismiss="modal">'+btn_close+'</button>\
		        			<button type="button" class="btn btn-primary" onclick="'+ href +';">'+btn_accept+'</button>\
		      			</div>\
		    		</div>\
				</div>\
			</div>';
	var html = tpl;
	if( jQuery('#js-dlg-confirm').length > 0 )
	{
		jQuery('#js-dlg-confirm').remove();
	}
	jQuery('body').append(html);
	jQuery('#js-dlg-confirm').modal('show');
	
	return false;
};
function printUrl(url)
{
	if( jQuery('#print-iframe').length > 0 )
	{
		jQuery('#print-iframe').remove();
	}
	var iframe = jQuery('<iframe id="print-iframe" src="'+url+'" style="display:none;"></iframe>');
	jQuery('body').append(iframe);
	try
	{
		iframe.load(function()
		{
			if(iframe.get(0).contentWindow.printIFrame)
				iframe.get(0).contentWindow.printIFrame();
			else
				iframe.get(0).contentWindow.print();
		});
		
	}
	catch(e)
	{
		alert(e);
	}
	
	return false;
}
jQuery(function()
{
	jQuery('.confirm').click(function(e)
	{
		//console.log(e);
		if( confirm(jQuery(this).data('message'), 'Confirmar accion', null, e) )
		{
			return true;
		}
		return false;
	});
	//##initialize popover
	jQuery('.has-popover').popover({trigger: 'hover',placement:'auto', html: true, container:'body'});
	//##table selector
	jQuery(document).on('click', 'input[type=checkbox].tcb-select-all', function()
	{
		jQuery(this).parents('table').find('tbody tr .tcb-select').prop('checked', this.checked);
	});
    //##avoid for input submission by pressing enter
    jQuery('form.avoid-submission input').keydown(function(e)
    {
        if( e.keyCode == 13 )
        {
            e.preventDefault();
        }
    });
});
function createCookie(name, value, days) {
    var expires;

    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toGMTString();
    } else {
        expires = "";
    }
    document.cookie = encodeURIComponent(name) + "=" + encodeURIComponent(value) + expires + "; path=/";
}

function readCookie(name) {
    var nameEQ = encodeURIComponent(name) + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) === ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) === 0) return decodeURIComponent(c.substring(nameEQ.length, c.length));
    }
    return null;
}

function eraseCookie(name) {
    createCookie(name, "", -1);
}
