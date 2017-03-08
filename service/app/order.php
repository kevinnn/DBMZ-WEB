<?php
header('Content-type:application/json;charset=UTF-8');
include '../../config/config.php';
include __ROOT__.'/controller/userController.php';
$check = json_decode(userController::isLogin(),true);
if ($check['code'] == 200 && count($check['data']) == 1) {
	$users = $check['data'];
	$user = $users[0];
	$userId = $user['id'];
	include __ROOT__.'/controller/orderController.php';
	$url = explode("/", $_SERVER['PHP_SELF']);
	$method = $url[count($url)-1];
	switch ($method) {
		case 'getByYungou':
			echo orderController::getByYungou($userId);
			break;
		
		case 'getByUser':
			echo orderController::getByUser($userId);
			break;

	}
} else {
	echo json_encode(new jsonBean(403,array(),'未登录'),JSON_UNESCAPED_UNICODE);
}
?>