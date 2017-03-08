<?php
header('Content-type:application/json;charset=UTF-8');
include '../../config/config.php';
include __ROOT__.'/controller/adminController.php';
$check = json_decode(adminController::isLogin(),true);
if ($check['code'] == 200) {
	include __ROOT__.'/controller/orderController.php';
	$url = explode("/", $_SERVER['PHP_SELF']);
	$method = $url[count($url)-1];
	switch($method) {
		case 'orderList' :
			echo orderController::orderList();
			break;
		case 'orderCount' :
			echo orderController::orderCount();
			break;
		case 'orderDetail' :
			echo orderController::orderDetail();
			break;
	}
}
?>