<?php
header('Content-type:application/json;charset=UTF-8');
include '../../config/config.php';
include __ROOT__.'/controller/adminController.php';
$check = json_decode(adminController::isLogin(),true);
if ($check['code'] == 200) {
	include __ROOT__.'/controller/userController.php';
	$url = explode("/", $_SERVER['PHP_SELF']);
	$method = $url[count($url)-1];
	switch($method) {
		case 'addUser' :
			echo userController::addUser();
			break;
		case 'userList' :
			echo userController::userList();
			break;
		case 'userCount' :
			echo userController::userCount();
			break;
		case 'userEdit' :
			echo userController::userEdit();
			break;
		case 'updateUser' :
			echo userController::updateUser();
			break;
		case 'deleteUser' :
			echo userController::deleteUser();
			break;
		case 'isUserExit' :
			echo userController::isUserExit();
			break;
		case 'selectUser' :
			echo userController::selectUser();
			break;
		case 'deposit' :
			echo userController::deposit();
			break;
		case 'depositCount' :
			echo userController::depositCount();
			break;
	}
}
?>