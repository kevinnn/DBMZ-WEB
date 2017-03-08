<?php
header('Content-type:application/json;charset=UTF-8');
include '../../config/config.php';
include __ROOT__.'/controller/adminController.php';
$check = json_decode(adminController::isLogin(),true);
if ($check['code'] == 200) {
	include __ROOT__.'/controller/uploaderController.php';
	$url = explode("/", $_SERVER['PHP_SELF']);
	$head = $url[count($url)-2];

	$middle = $url[count($url)-1];

	date_default_timezone_set('PRC');
	$t = explode('.', microtime(true));
	$tail = date('Y-m-d_H:i:s').':'.$t[1];
	$name = $head.'_'.$middle.'_'.$tail;

	switch($head) {
		case 'product' :
			switch($middle) {
				case 'thumbnail':
					echo uploaderController::uploader($name);
					break;
				case 'show':
					echo uploaderController::uploader($name);
					break;
			}
			break;
		case 'ueditor' :
			echo uploaderController::ueditorUploader('ueditor_'.$tail);
			break;
		case 'category' :
			echo uploaderController::uploader($name);
			break;
		case 'banner' :
			echo uploaderController::uploader($name);
			break;
		case 'user' :
			echo uploaderController::uploader($name);
			break;
	}
}
?>