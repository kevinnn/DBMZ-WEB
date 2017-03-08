<?php
header('Content-type:application/json;charset=UTF-8');
include '../../config/config.php';
include __ROOT__.'/controller/userController.php';
$check = json_decode(userController::isLogin(),true);
if ($check['code'] == 200 && count($check['data']) == 1) {
	$users = $check['data'];
	$user = $users[0];
	$userId = $user['id'];

	$url = explode("/", $_SERVER['PHP_SELF']);
	$method = $url[count($url)-1];
	switch($method) {
		case 'province' :
			include __ROOT__.'/controller/provinceController.php';
			echo provinceController::getAll();
			break;
		case 'city':
			include __ROOT__.'/controller/cityController.php';
			echo cityController::getAllByProvince();
			break;
		case 'area':
			include __ROOT__.'/controller/areaController.php';
			echo areaController::getAllByCity();
			break;

		case 'add':
			include __ROOT__.'/controller/addressController.php';
			echo addressController::add($userId);
			break;

		case 'addMobile':
			include __ROOT__.'/controller/addressController.php';
			echo addressController::addMobile($userId);
			break;

		case 'remove':
			include __ROOT__.'/controller/addressController.php';
			echo addressController::remove($userId);
			break;

		case 'getAll':
			include __ROOT__.'/controller/addressController.php';
			echo addressController::getAll($userId);
			break;

		case 'getById':
			include __ROOT__.'/controller/addressController.php';
			echo addressController::getById($userId);
			break;

		case 'update':
			include __ROOT__.'/controller/addressController.php';
			echo addressController::modify($userId);
			break;

	}	
} else {
	echo json_encode(new jsonBean(403,array(),'未登录'),JSON_UNESCAPED_UNICODE);
}
?>