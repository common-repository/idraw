<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
</head>
<body>

<?php

/**
 * upload image to wp-content/uploads
 *
 * @author     www.5idraw.com
 * @copyright  www.5idraw.com
 */

define('WP_ADMIN', true);

require_once('../../../wp-load.php');

// Flash often fails to send cookies with the POST or upload, so we need to pass it in GET or POST instead
if ( is_ssl() && empty($_COOKIE[SECURE_AUTH_COOKIE]) && !empty($_REQUEST['auth_cookie']) )
	$_COOKIE[SECURE_AUTH_COOKIE] = $_REQUEST['auth_cookie'];
elseif ( empty($_COOKIE[AUTH_COOKIE]) && !empty($_REQUEST['auth_cookie']) )
	$_COOKIE[AUTH_COOKIE] = $_REQUEST['auth_cookie'];
if ( empty($_COOKIE[LOGGED_IN_COOKIE]) && !empty($_REQUEST['logged_in_cookie']) )
	$_COOKIE[LOGGED_IN_COOKIE] = $_REQUEST['logged_in_cookie'];
unset($current_user);

require_once('../../../wp-admin/admin.php');

header('Content-Type: text/html; charset=' . get_option('blog_charset'));

if ( !current_user_can('upload_files') )
	wp_die(__('You do not have permission to upload files.'));

function getWordPressRoot() {
	$docroot=$_SERVER['DOCUMENT_ROOT'];
	$wpabsroot=ABSPATH;
	$wprelroot=str_replace($docroot, "", $wpabsroot);

	return $wprelroot;
}

$WP_ROOT=getWordPressRoot();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['dataurl']) && isset($_POST['filename'])) {
        // Decode the base64-encoded data
        $data = $_POST['dataurl'];
        $data = substr($data, strpos($data, ',') + 1);
        $png = base64_decode($data);
		$filename = $_POST['filename'];
		$oldfilename = $_POST['oldfilename'];
	
		$Y = date("Y"); 
		$m = date("n"); 
		$d = date("j"); 

		$wp_root_path = str_replace('/wp-content/themes', '', get_theme_root());
		$image_dir = $wp_root_path . "/wp-content/uploads/" . $Y . "/" . $m . "/" . $d . "/";

		$filename = basename($filename);

		if($oldfilename) {
			$oldfilename = basename($oldfilename);
		}

		if(!file_exists($image_dir)) {
			mkdir($image_dir, 0755, true);
		}
		else {
			if($oldfilename) {
				$oldfullname =  $image_dir . $oldfilename;
				if(file_exists($oldfullname)) {
					unlink($oldfullname);
				}
			}
		}

		$fullname = $image_dir . $filename;

		$file = fopen($fullname, "wb+");
		if($file) {
			fwrite($file, $png);
			fclose($file);
			chmod($fullname, 0644);
		}

		$url = "/" . $WP_ROOT . "/wp-content/uploads/" . $Y . "/" . $m . "/" . $d . "/" . $filename;
		$url = str_replace('//', '/', $url);
		$url = str_replace('//', '/', $url);
		
		$locale="en_US";
		if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
			$locale = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
		}

		if(strstr($locale, "zh")) {
			echo "<p><big>图片上传成功，关闭当前对话框返回编辑器:</big><br>";
		}
		else {
			echo "<p><big>Image is uploaded, close this dialog return back to editor:</big><br>";
		}

		echo "<center><img border=\"2\" src=\"" . $url . "\"/></center>";
    } else {
		echo "invalid params";
    }
}
else {
	echo $WP_ROOT;
	echo " not post";
}
?>

</body>
</html>

