<?php
header('Content-type:application/json;charset=UTF-8');
include '../../config/config.php';
include __ROOT__.'/controller/adminController.php';
$check = json_decode(adminController::isLogin(),true);
if ($check['code'] == 200) {
	include __ROOT__.'/controller/delegateController.php';
	$url = explode("/", $_SERVER['PHP_SELF']);
	$method = $url[count($url)-1];
	switch($method) {
		case 'getWithdrawRecords' :
			echo delegateController::getWithdrawRecords();
			break;
		case 'approveWithdraw' :
			echo delegateController::approveWithdraw();
			break;
		case 'addDelegate' :
			echo delegateController::addDelegate();
			break;
		case 'getWithdrawCount' :
			echo delegateController::getWithdrawCount();
			break;
		case 'getDelegateList' :
			echo delegateController::getDelegateList();
			break;
		case 'getDelegateCount' :
			echo delegateController::getDelegateCount();
			break;
	}
}
?>