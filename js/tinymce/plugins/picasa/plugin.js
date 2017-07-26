/**
 * Picasa selector - a TinyMCE Picasa/Google+ image selector plugin
 * picasa/js/picasa.js
 *
 * This is not free software
 *
 * Plugin info: http://www.cfconsultancy.nl/
 * Author: Ceasar Feijen
 *
 * Version: 1.1 released 07/10/2013 
 */
tinymce.PluginManager.add('picasa', function(editor) {

    function openmanager() {
        var title="Choose Picasa/Google+ images";
        if (typeof editor.settings.flickr_title !== "undefined" && editor.settings.flickr_title) {
            title=editor.settings.flickr_title;
        }
        win = editor.windowManager.open({
            title: title,
            file: tinyMCE.baseURL + '/plugins/picasa/picasa.html',
            filetype: 'image',
	    	width: 765,
            height: 520,
            inline: 1,
            buttons: [{
                text: 'Cancel',
                onclick: function() {
                    this.parent()
                        .parent()
                        .close();
                }
            }]
        });

    }
	editor.addButton('picasa', {
		icon: true,
		image: tinyMCE.baseURL + '/plugins/picasa/icon.png',
		tooltip: 'Insert Picasa/Google+ images',
		shortcut: 'Ctrl+S',
		onclick: openmanager
	});

	editor.addShortcut('Ctrl+S', '', openmanager);

	editor.addMenuItem('picasa', {
		icon:'media',
		text: 'Insert Picasa/Google+ images',
		shortcut: 'Ctrl+S',
		onclick: openmanager,
		context: 'insert'
	});
});
