<?php
header('Content-type:application/json;charset=UTF-8');
include '../../config/config.php';
include __ROOT__.'/controller/adminController.php';
$check = json_decode(adminController::isLogin(),true);
if ($check['code'] == 200) {
	include __ROOT__.'/controller/showController.php';
	$url = explode("/", $_SERVER['PHP_SELF']);
	$method = $url[count($url)-1];
	switch($method) {
		case 'showList' :
			echo showController::getShowList();
			break;
		case 'getShowInfo' :
			echo showController::getShowInfo();
			break;
		case 'deleteShow' :
			echo showController::deleteShow();
			break;
		case 'approveShow' :
			echo showController::approveShow();
			break;
		case 'setIsApproved' :
			echo showController::setIsApproved();
			break;
	}
}
?>