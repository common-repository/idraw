<html>
<head>
  <meta charset="utf-8">
  <title>Draw flowchart.UML.Chart online.</title>

  <link rel="stylesheet" type="text/css" href="./diagram/css/drawing.css" media="all">  
  <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.21/themes/base/jquery-ui.css"rel="stylesheet" type="text/css"/>

  <script type="text/javascript" src="./js/upload_image.js"></script>
  <script type="text/javascript" src="./diagram/js/idraw.php"></script>
  <script type="text/javascript" src="../../../../wp-includes/js/tinymce/tiny_mce_popup.js"></script>

<script type="text/javascript">
tinyMCEPopup.requireLangPack();

<? 
$docroot=$_SERVER['DOCUMENT_ROOT'];
define('IDRAW_PATH', dirname(__FILE__) . '/');
$wprelroot=str_replace($docroot, "", IDRAW_PATH); 
$wprelroot=str_replace("/wp-content/plugins/idraw/tinymce", "", $wprelroot); 

echo "var UPLOAD_ROOT=\"" . "/" . $wprelroot . "wp-content/uploads/\";";
echo "\n";
echo "var UPLOAD_URL=\"/" . $wprelroot . "wp-content/plugins/idraw/idraw_upload.php\";";
?>

var EditorStorage = {
	imageWidth:840,
	isDataUrl: function(url) {
		if(url && url.indexOf("data://") === 0) {
			return true;
		}
		else {
			return false;
		}
	},

	close: function() {
		var fileName = null;
		var title = "idraw_image";
		var str = this.idrawStr;
		var dataUrl = this.dataUrl;
		var ed = tinyMCEPopup.editor, dom = ed.dom;
		
		if(this.image && !this.isDataUrl(this.image.src)) {
			fileName = this.image.src;
		}

		if(!str || !dataUrl) {
			tinyMCEPopup.close();
			
			return;
		}

		var src = uploadImage(UPLOAD_ROOT, UPLOAD_URL, dataUrl, fileName);
		this.saveImage(src, str, tinyMCEPopup);

		return;
	},

	save: function(str, dataUrl) {
		this.idrawStr = str;
		this.dataUrl = dataUrl;

		return;
	}
}

var gApp;
idrawRegisterAll();
delayLoadScripts(".", true);

setTimeout(function() {
	if(!gApp) {
		var ed = tinyMCEPopup.editor;
		var dom = ed.dom;
		var json = tinyMCEPopup.getWindowArg("json");
		var image = tinyMCEPopup.getWindowArg("image");

		var storage = EditorStorage;
		storage.image = image;
		storage.saveImage = tinyMCEPopup.getWindowArg("saveImage");
		var width = tinyMCEPopup.getWindowArg("width");
		var height = tinyMCEPopup.getWindowArg("height");
		var canvas = canvas = document.getElementById("idraw_canvas");
		canvas.width = width - 10;
		canvas.height = height - 10;
		gApp = idrawEditInline("idraw_canvas", json, storage);
	}
}, 500);

</script>

</head>
<body>

<canvas border="1" width="800" height="600" id="idraw_canvas"></canvas>

</body>
</html>

