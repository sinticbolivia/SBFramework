/**
 * Flickr search - a TinyMCE flickr image search and place plugin
 * flickr/js/flickr.js
 *
 * This is not free software
 *
 * Plugin info: http://www.cfconsultancy.nl/
 * Author: Ceasar Feijen
 *
 * Version: 1.0 released 25/09/2013
 */
tinymce.PluginManager.add('flickr',function(editor){function openmanager(){var title="Choose Flickr image";if(typeof editor.settings.flickr_title!=="undefined"&&editor.settings.flickr_title){title=editor.settings.flickr_title;}
win=editor.windowManager.open({title:title,file:tinyMCE.baseURL+'/plugins/flickr/flickr.html',filetype:'image',width:785,height:460,inline:1,buttons:[{text:'Cancel',onclick:function(){this.parent().parent().close();}}]});}
editor.addButton('flickr',{icon:true,image:tinyMCE.baseURL+'/plugins/flickr/icon.png',tooltip:'Insert/edit Flickr image',shortcut:'Ctrl+L',onclick:openmanager});editor.addShortcut('Ctrl+L','',openmanager);editor.addMenuItem('flickr',{icon:'media',text:'Insert Flickr image',shortcut:'Ctrl+L',onclick:openmanager,context:'insert'});});
