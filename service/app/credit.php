<?php
header('Content-type:application/json;charset=UTF-8');
include '../../config/config.php';
include __ROOT__.'/controller/userController.php';
$check = json_decode(userController::isLogin(),true);
if ($check['code'] == 200 && count($check['data']) == 1) {
	$users = $check['data'];
	$user = $users[0];
	$userId = $user['id'];
	include __ROOT__.'/controller/creditController.php';
	$url = explode("/", $_SERVER['PHP_SELF']);
	$method = $url[count($url)-1];
	switch ($method) {
		case 'signIn':
			echo creditController::signIn($userId);
			break;
		case 'isSignIn':
			echo creditController::isSignIn($userId);
			break;

		case 'getRecord':
			echo creditController::getRecord($userId);
			break;
		case 'exchange':
			echo creditController::exchange($userId);
			break;
	}
} else {
	echo json_encode(new jsonBean(403,array(),'未登录'),JSON_UNESCAPED_UNICODE);
}
?>