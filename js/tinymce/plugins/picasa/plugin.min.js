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
tinymce.PluginManager.add("picasa",function(e){function t(){var t="Choose Picasa/Google+ images";if(typeof e.settings.flickr_title!=="undefined"&&e.settings.flickr_title){t=e.settings.flickr_title}win=e.windowManager.open({title:t,file:tinyMCE.baseURL+"/plugins/picasa/picasa.html",filetype:"image",width:765,height:540,inline:1,buttons:[{text:"Cancel",onclick:function(){this.parent().parent().close()}}]})}e.addButton("picasa",{icon:true,image:tinyMCE.baseURL+"/plugins/picasa/icon.png",tooltip:"Insert Picasa/Google+ images",shortcut:"Ctrl+S",onclick:t});e.addShortcut("Ctrl+S","",t);e.addMenuItem("picasa",{icon:"media",text:"Insert Picasa/Google+ images",shortcut:"Ctrl+S",onclick:t,context:"insert"})})
