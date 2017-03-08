<?php
header('Content-type:application/json;charset=UTF-8');
include '../../config/config.php';
include __ROOT__.'/controller/adminController.php';
$check = json_decode(adminController::isLogin(),true);
if ($check['code'] == 200) {
	include __ROOT__.'/controller/bannerController.php';
	$url = explode("/", $_SERVER['PHP_SELF']);
	$method = $url[count($url)-1];
	switch($method) {
		case 'bannerList' :
			echo bannerController::bannerList();
			break;
		case 'addBanner' :
			echo bannerController::addBanner();
			break;
		case 'updateBanner' : 
			echo bannerController::updateBanner();
			break;
		case 'bannerEdit' :
			echo bannerController::bannerEdit();
			break;
		case 'deleteBanner':
			echo bannerController::deleteBanner();
			break;
	}
}
?>