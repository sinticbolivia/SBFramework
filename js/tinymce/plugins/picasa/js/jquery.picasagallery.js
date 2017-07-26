// #Copyright (c) 2011 Alan Hamlett <alan.hamlett@gmail.com>
// #
// # Permission is hereby granted, free of charge, to any person obtaining a copy
// # of this software and associated documentation files (the "Software"), to deal
// # in the Software without restriction, including without limitation the rights
// # to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
// # copies of the Software, and to permit persons to whom the Software is
// # furnished to do so, subject to the following conditions:
// #
// # The above copyright notice and this permission notice shall be included in
// # all copies or substantial portions of the Software.
// #
// # THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
// # IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
// # FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
// # AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
// # LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
// # OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
// # THE SOFTWARE.

(function( window, $, undefined ) {

    var VERSION = '1.0';

    // Private methods
    var busy = false;

    var picasagallery_load_albums = function() {
        if(busy)
            return;
        busy = true;

        var data = this.data('picasagallery'); // original options passed to picasagallery()
        if(!data)
            data = $(this).parent().data('picasagallery');

        //console.log(data);

        var protocol = document.location.protocol == 'http:' ? 'http:' : 'https:';
        var url    = protocol + '//picasaweb.google.com/data/feed/api/user/' + data.username + '?kind=album&access=public&alt=json';

        // print loading message
        this.html('<div class="picasagallery_loading"><img src="js/picasa.gif" width="28" height="28" alt="loading..." border="0" /></div>');

        // make ajax call to get public picasaweb albums
        $.ajax(url, {
        	dataType: "jsonp",
        	timeout: 2000,
        	complete: $.proxy(function(ajaxData) {

       	     	if(ajaxData.status != 200){
       				this.html('<div class="picasagallery_loading">Error: user not found</div>');
	                busy = false;
	                return false;
                }
	            json = ajaxData.responseJSON;

	           // initialize album html content
	            this.html("<div></div><div></div>");
	            this.children('div:last').hide();

	            // no albums
                if(json == undefined || json.feed == undefined || json.feed.entry == undefined) {
       				this.html('<div class="picasagallery_loading">No albums for this user</div>');
	                busy = false;
	                return false;
	            }

           		// success, so set cookie
			    setCookie('picasa_username',data.username,365);

	            // loop through albums
	            for(var i = 0; i < json.feed.entry.length; i++) {
	                var album_title = htmlencode(json.feed.entry[i].title.$t);
	                var album_title_compare = json.feed.entry[i].title.$t;
	                var album_link = '#';
	                for(var j = 0; j < json.feed.entry[i].link.length; j++) {
	                    if (json.feed.entry[i].link[j].type == 'text/html')
	                        album_link = htmlencode(json.feed.entry[i].link[j].href);
	                }

	                // skip this album if in hide_albums array
	                if ($.inArray(album_title_compare, data.hide_albums) > -1) {
	                    continue;
	                }

	                // get album thumbnail
	                var img_src = json.feed.entry[i].media$group.media$content[0].url.split('/');
	                var img_filename = img_src.pop();
	                var img_src = img_src.join('/');
	                var img_num = json.feed.entry[i].gphoto$numphotos.$t;
	                // skip this album if o images
	                if (img_num == 0) {
	                    continue;
	                }
	                if (img_num == 1) {
	                    img_numname = ' photo';
	                }else{
	                    img_numname = ' photo\'s';
	                }

	                // append html for this album
	                this.children('div:first').append(
	                    "<div class='picasagallery_album'><img src='" +
	                    img_src + '/s' + data.thumbnail_width + ( data.thumbnail_cropped ? '-c' : '' ) + '/' + img_filename +
	                    "' alt='" + json.feed.entry[i].gphoto$name.$t + "' title='" + album_title +
	                    "'/><div class='title'>" + album_title + "</div><p>" +
	                    json.feed.entry[i].gphoto$numphotos.$t +
	                       img_numname +
	                    '</p></div>'
	                ).children('div:last').children('img:first').data('album', json.feed.entry[i].gphoto$name.$t).click(picasagallery_load_album);

	            }

	            // append blank div to resize parent elements
	            this.children('div:first').append('<div style="clear:both"></div>');

                busy = false;

	        }, this)
        }
        );
    };

    var picasagallery_load_album = function() {
        if(busy)
            return;
        busy = true;

        //var dom = $(this).parent().parent().parent(); // original album element
        var dom = $('.picasagallery');
        var data = dom.data('picasagallery'); // original options passed to picasagallery()
        var album = $(this).data('album');
        var protocol = document.location.protocol == 'http:' ? 'http:' : 'https:';
		var url = protocol + '//picasaweb.google.com/data/feed/api/user/' + data.username + '/album/' + album + '?kind=photo&alt=json';

        // initialize album html content
        dom.children('div:last').html('<div class="picasagallery_loading"><img src="js/picasa.gif" width="28" height="28" alt="loading..." border="0" /></div>').show();
        dom.children('div:first').hide();

        // make ajax call to get album's images
        $.getJSON(url, 'callback=?', $.proxy(function(json) {

            // reset album html
            dom.children('div:last').html('');

            // loop through album's images
            for(i = 0; i < json.feed.entry.length; i++) {

                // get image properties
                var summary = htmlencode(json.feed.entry[i].summary.$t);
                var img_src = json.feed.entry[i].content.src.split('/');
                var img_filename = img_src.pop();
                var img_src = img_src.join('/');
                var screen_width = $(window).width();
                // add html for this image
                var html = "<img data-src='"+img_src+"' data-filename='"+img_filename+"' data-title='"+summary+"' class='picasagallery_thumbnail img-thumbnail' src='" +
                           img_src + '/s' + data.thumbnail_width + ( data.thumbnail_cropped ? '-c' : '' ) + '/' + img_filename +
                           "' alt='" +
                           summary +
                           "' title='" +
                           summary +
                           "'/>"
                ;
                dom.children('div:last').append(html);
            }

                    $('.return_btn').show(300);

            // append blank div to resize parent elements
            dom.children('div:last').append('<div style="clear:both"></div>');

            busy = false;
        }, this));
    };

    var htmlencode = function(str) {
        while(str.search("'") + str.search('"') + str.search("<") + str.search(">") > -4) {
            str = str.replace("'","&#39;").replace('"', "&#34;").replace("<","&lt;").replace(">","&gt;");
        }
        return str;
    }

    var picasagallery_error = function(msg) {
        if (typeof console === "undefined" || typeof console.error === "undefined") {
            if( typeof console.log === "undefined" ) {
                alert('Picasa Gallery Error: ' + msg);
            } else {
                console.log('Picasa Gallery Error: ' + msg);
            }
        } else {
            console.error('Picasa Gallery Error: ' + msg);
        }
    }

    // Public method
    $.fn.picasagallery = function(options) {
        this.data('picasagallery', $.extend({
            'username': '',
            'hide_albums': [],
            'thumbnail_width': '160',
            'thumbnail_cropped': true
        }, options));
        if (this.data('picasagallery') === undefined) {
            picasagallery_error('Cannot call method \'picasagallery\' of undefined. Must be called on a jQuery DOM object.');
            return;
        }
        if (!this.data('picasagallery').username) {
            picasagallery_error('Missing username.');
            return;
        }
        this.addClass('picasagallery');
        picasagallery_load_albums.apply(this);
        return this;
    };

}) ( window, jQuery );

