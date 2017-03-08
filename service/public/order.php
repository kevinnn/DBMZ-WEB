<?php
header('Content-type:application/json;charset=UTF-8');
include '../../config/config.php';
include __ROOT__.'/controller/orderController.php';
include __ROOT__.'/controller/userController.php';
$check = json_decode(userController::isLogin(),true);
$userId = null;
if ($check['code'] == 200 && count($check['data']) == 1) {
	$users = $check['data'];
	$user = $users[0];
	$userId = $user['id'];
}
$url = explode("/", $_SERVER['PHP_SELF']);
$method = $url[count($url)-1];
switch($method) {
	case 'winOrder' :
		echo orderController::winOrder();
		break;
	case 'ordering' :
		echo orderController::ordering();
		break;

	case 'orderByYungou' :
		echo orderController::orderByYungou();
		break;

	case 'orderByYungouMobile':
		echo orderController::orderByYungouMobile();
		break;

	case 'getNumbers':
		echo orderController::getNumbers();
		break;

	case 'winOrderDetail' :
		echo orderController::winOrderDetail();
		break;

	case 'getOrderCount' :
		echo orderController::getOrderCount();
		break;

	case 'getRecord':
		echo orderController::getRecord($userId);
		break;

	case 'getCountRecord':
		echo orderController::getCountRecord($userId);
		break;

	case 'getWinRecord' :
		echo orderController::getWinRecord($userId);
		break;

	case 'getCountWinRecord' :
		echo orderController::getCountWinRecord($userId);
		break;
}
?>