<?php
header('Content-type:application/json;charset=UTF-8');
include '../../config/config.php';
include __ROOT__.'/controller/showController.php';
include __ROOT__.'/controller/userController.php';
$userId = null;
$check = json_decode(userController::isLogin(),true);
if ($check['code'] == 200 && count($check['data']) == 1) {
	$users = $check['data'];
	$user = $users[0];
	$userId = $user['id'];
}
$url = explode("/", $_SERVER['PHP_SELF']);
$method = $url[count($url)-1];
switch($method) {
	case 'getAll' :
		echo showController::getAll();
		break;
	case 'getCountAll':
		echo showController::getCountAll();
		break;
	case 'getByProduct':
		echo showController::getByProduct();
		break;
	case 'getDetail':
		echo showController::getDetail();
		break;
	case 'getCountByProduct':
		echo showController::getCountByProduct();
		break;

	case 'getAllByUser':
		echo showController::getAllByUser($userId);
		break;

	case 'getCountAllByUser':
		echo showController::getCountAllByUser($userId);
		break;
}
?>