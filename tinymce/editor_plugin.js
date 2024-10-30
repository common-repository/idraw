/**
 * editor_plugin_src.js
 *
 * Copyright 2012, www.5idraw.com
 * Released under LGPL License.
 *
 * License: http://www.5idraw.com
 * Contributing: http://www.5idraw.com
 */

function getViewPort() {
 var viewportwidth;
 var viewportheight;
	
 if (typeof window.innerWidth != 'undefined'){
  viewportwidth = window.innerWidth,
  viewportheight = window.innerHeight
 }
 else if (typeof document.documentElement != 'undefined'
  && typeof document.documentElement.clientWidth !=
  'undefined' && document.documentElement.clientWidth != 0)
 {
  viewportwidth = document.documentElement.clientWidth,
  viewportheight = document.documentElement.clientHeight
 }
 else{
  viewportwidth = document.getElementsByTagName('body')[0].clientWidth,
  viewportheight = document.getElementsByTagName('body')[0].clientHeight
 }

 return {width:viewportwidth, height:viewportheight};
}

(function(tinymce) {
	tinymce.create('tinymce.plugins.IDrawPlugin', {
		init : function(ed, url) {
			// Register commands
			function isIDrawImg(node) {
				return node && node.nodeName === 'IMG' && node.title === "5idraw";
			};

			function saveImage(src, json) {
				setTimeout(function(){
					var dom = ed.dom;
					var img = dom.create('img', {
						src : src,
						alt : encodeURI(json), 
						title : "5idraw",
						border : 2
						});

					tinyMCE.execCommand("mceRepaint");
					tinyMCE.activeEditor.selection.setNode(img);
				}, 4000);

				return;
			}

			ed.addCommand('mceIDraw', function() {
				var data = null;
				var image = null;
				var img = ed.selection.getNode();
				if (isIDrawImg(img)) {
					image = img;
					data = decodeURI(img.alt);
				}
				var view = getViewPort();
				var width = view.width < 1000 ? view.width * 0.9 : 1000;
				var height = view.height * 0.9;

				ed.windowManager.open({
					file : url + "/idraw.php",
					width : width,
					height : height,
					inline : 1
				}, {
					width : width,
					height : height,
					json: data,
					image: image,
					saveImage: saveImage
				});
			});

			// Register buttons
			ed.addButton('idraw', {title : 'Draw diagrams with www.5idraw.com.', image: url + "/img/idraw.png", cmd : 'mceIDraw'});
		},

		getInfo : function() {
			return {
				longname : '5idraw',
				author : '5idraw.com',
				authorurl : 'http://www.5idraw.com',
				infourl : 'http://www.5idraw.com',
				version : tinymce.majorVersion + "." + tinymce.minorVersion
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('idraw', tinymce.plugins.IDrawPlugin);
})(tinymce);

