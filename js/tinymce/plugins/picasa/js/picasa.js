/**
 * Picasa selector - a TinyMCE picasa image selector plugin
 * picasa/js/picasa.js
 *
 * This is not free software
 *
 * Plugin info: http://www.cfconsultancy.nl/
 * Author: Ceasar Feijen
 *
 * Version: 1.1 released 07/10/2013
 */

// Set options
var full_width = 800;
var hiding_albums = ['Profile Photos', 'Scrapbook Photos', 'Instant Upload', 'Photos from posts'];
var lightbox = true;
var relname = 'lightbox';

$(function(){

	if (lightbox != true) {
		$('.lightbox').hide();
	}

    $('.return_btn').hide();
    var user = getCookie('picasa_username');
    var width = getCookie('picasa_width');
    if (width!="" && width!=null) {
        $('#inpWidth').val(width);
    }

    if (user!="" && user!=null) {
        $('#inpUsername').val(user);
        $('.picasagallery').picasagallery({
                username:$('#inpUsername').val(),
                hide_albums: hiding_albums,
                thumbnail_width: '120', // width of album and photo thumbnails
                thumbnail_cropped: true
        });
    }

    $('.picasa_init').on('click',function(){
        $('.return_btn').hide();
        $('.picasagallery').html('');
        $('.picasagallery').picasagallery({
                username:$('#inpUsername').val(),
                hide_albums: hiding_albums,
                thumbnail_width: '120', // width of album and photo thumbnails
                thumbnail_cropped: true
        });
    });

    $('.picasagallery ').on('click','.picasagallery_thumbnail',function(){
        var _this=$(this);
        $('.picasagallery_thumbnail').removeClass('selected');
        $('#inpUrl').val(_this.attr('data-src'));
        $('#inpTitle').val(_this.attr('data-title'));
        $('#inpFilename').val(_this.attr('data-filename'));
        _this.addClass('selected');
        });
    });

	$('#inpUsername').keypress(function(event){
	    var keycode = (event.keyCode ? event.keyCode : event.which);
	    if(keycode == '13'){
            $(this).blur();
            $('.picasa_init').focus().click();
	    }
	});

function I_Close() {
    parent.tinymce.activeEditor.windowManager.close();
}

function convertQuotes(string){
    return string.replace(/["']/g, "");
}

function I_Insert() {

    setCookie('picasa_width',$('#inpWidth').val(),365);
    /* Link URL */
    var sLinkURL = "";
    if ($("#chkOpenLarger").prop('checked') ) {
	var ss = $("#inpUrl").val()+"/s"+full_width;
        if ($('#selCrop').val()==1) {
            ss+="-c";
        }
        ss+="/"+$('#inpFilename').val();
    	var imgrel = ' rel="'+relname+'"';
	sLinkURL = ss;
    }
    /* Link Title */
    var sTitle = $("#inpTitle").val().substring(0,125);

    /* Link Css Style */
    var sCssStyle = "";
    if ($("#selAlign").val() == "left")
		sCssStyle = " style='float:left;margin:0 10px 0 0;'";
    else if ($("#selAlign").val() == "right")
		sCssStyle = " style='float:right;margin:0 0 0 10px;'";

    /* Image URL */
    var sImgURL = $("#inpUrl").val()+"/s"+$("#inpWidth").val();
    if ($('#selCrop').val()==1) {
        sImgURL+="-c";
    }

    if ($("#inpUrl").val() == "") return false;

    sImgURL+="/"+$('#inpFilename').val();

    if (sLinkURL != "") {
       var sHTML = '<a title="' + convertQuotes(sTitle) + '" href="' + sLinkURL + '"' + imgrel + '><img' + sCssStyle + ' alt="' + convertQuotes(sTitle) + '" src="' + sImgURL + '" border="0"></a>';
    }else{
       var sHTML = '<img' + sCssStyle + ' alt="' + convertQuotes(sTitle) + '" src="' + sImgURL + '">';
    }

    parent.tinymce.activeEditor.insertContent(sHTML);

}

function setCookie(c_name,value,exdays)
{
    var exdate=new Date();
    exdate.setDate(exdate.getDate() + exdays);
    var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
    document.cookie=c_name + "=" + c_value;
}

function getCookie(c_name)
{
    var c_value = document.cookie;
    var c_start = c_value.indexOf(" " + c_name + "=");
    if (c_start == -1)
      {
      c_start = c_value.indexOf(c_name + "=");
      }
    if (c_start == -1)
      {
      c_value = null;
      }
    else
      {
      c_start = c_value.indexOf("=", c_start) + 1;
      var c_end = c_value.indexOf(";", c_start);
      if (c_end == -1)
      {
    c_end = c_value.length;
    }
    c_value = unescape(c_value.substring(c_start,c_end));
    }
    return c_value;
}
