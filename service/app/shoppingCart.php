<?php
header('Content-type:application/json;charset=UTF-8');
include '../../config/config.php';
include __ROOT__.'/controller/userController.php';
$check = json_decode(userController::isLogin(),true);
if ($check['code'] == 200 && count($check['data']) == 1) {
	include __ROOT__.'/controller/shoppingCartController.php';
	$url = explode("/", $_SERVER['PHP_SELF']);
	$method = $url[count($url)-1];
	$users = $check['data'];
	$user = $users[0];
	$userId = $user['id'];
	switch ($method) {
		case 'list':
			echo shoppingCartController::getList($userId);
			break;
		case 'add':
			echo shoppingCartController::add($userId);
			break;
		case 'remove':
			echo shoppingCartController::remove();
			break;	
		case 'removeLot':
			echo shoppingCartController::removeLot();
			break;
		case 'intoCart':
			echo shoppingCartController::intoCart($userId);
			break;
	}
} else {
	echo json_encode(new jsonBean(403,array(),'未登录'),JSON_UNESCAPED_UNICODE);
}
?>